// @flow
'use strict';

const http = require('request-promise');

module.exports.all = (event: any, context: any, callback: (error: ?Error, data: ?LambdaResponse) => void) => {
  getNextBoards(event, [], null, callback)
};

module.exports.id = (event: any, context: any, callback: (error: ?Error, data: ?LambdaResponse) => void) => {
  const boardId: string = event.pathParameters.boardId
  const query = `query {
    node(id: "${boardId}") {
      ... on Project {
        id
        name
        owner {
          ... on Repository {
            nameWithOwner
          }
        }
      }
    }
  }`;

  queryGithub(event, query)
    .then(rawData => {
      const data: IdResponse = {
        provider: 'Github',
        board: extractBoard(rawData)
      };

      callback(null, {
        statusCode: 200,
        headers: {
          "Access-Control-Allow-Origin" : "*",
          "Access-Control-Allow-Credentials" : true
        },
        body: JSON.stringify(data),
      });
    })
};

module.exports.tasks = (event: any, context: any, callback: (error: ?Error, data: ?LambdaResponse) => void) => {
  const boardId: string = event.pathParameters.boardId
  const query = `query {
    node(id: "${boardId}") {
      ... on Project {
        columns(first: 100) {
          nodes {
            cards(first: 100) {
              edges {
                node {
                  id
                  note
                  content {
                    ... on Issue {
                      number
                      title
                      body
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }`;

  queryGithub(event, query)
    .then(rawData => {
      const data: TasksResponse = {
        provider: 'Github',
        tasks: extractTasks(rawData)
      };

      callback(null, {
        statusCode: 200,
        headers: {
          "Access-Control-Allow-Origin" : "*",
          "Access-Control-Allow-Credentials" : true
        },
        body: JSON.stringify(data),
      });
    })
};

function queryGithub(event: any, query: string): Promise<any> {
  let options = {
    method: 'POST',
    uri: 'https://api.github.com/graphql',
    body: {
      query
    },
    headers: {
      'Authorization': `${getAuthorizationn(event)}`,
      'User-Agent': process.env.SERVICE_NAME || 'scrumboardit-server-local'
    },
    json: true
  };

  return http(options)
}

function getNextBoards(event: any, previousBoards: Board[], startAfter: ?string, callback: (error: ?any, data: ?LambdaResponse) => void): void {
  let repositoriesFilter = [
    "first: 100",
    "affiliations:ORGANIZATION_MEMBER"
  ];
  if (startAfter) {
    repositoriesFilter.push(`after: "${startAfter}"`);
  }

  let query = `query {
      viewer {
      repositories(${repositoriesFilter.join(', ')}) {
        pageInfo {
          endCursor
          hasNextPage
          hasPreviousPage
          startCursor
        }
        nodes {
          nameWithOwner
          projects(first:5, states:OPEN) {
            nodes {
              id
              name
            }
          }
        }
      }
    }
  }`;

  queryGithub(event, query)
    .then(rawData => {
      let pageBoards: Board[] = extractBoards(rawData);
      let hasNextPage: boolean = rawData.data.viewer.repositories.pageInfo.hasNextPage;
      let endCursor: string = rawData.data.viewer.repositories.pageInfo.endCursor;
      let boards: Board[] = previousBoards.concat(pageBoards);
      if (hasNextPage) {
        getNextBoards(event, boards, endCursor, callback);
      } else {
        const data: AllResponse = {
          provider: 'Github',
          boards
        };
        
        callback(null, {
          statusCode: 200,
          headers: {
            "Access-Control-Allow-Origin" : "*",
            "Access-Control-Allow-Credentials" : true
          },
          body: JSON.stringify(data),
        });
      }
    })
    .catch(err => {
      callback(null, {
        statusCode: err.statusCode,
        body: JSON.stringify({
          provider: 'Github',
          error: err.error
        }),
      });
    });
}

function extractBoard(rawData: any): Board {
  return {
    id: rawData.data.node.id,
    name: `[${rawData.data.node.owner.nameWithOwner}] ${rawData.data.node.name}`
  };
}

function extractTasks(rawData: any): Task[] {
  let tasks: Task[] = [];
  
  rawData.data.node.columns.nodes.forEach(column => {
    column.cards.edges.forEach(edge => {
      const card = edge.node
      let task: Task = {
        id: card.id,
        description: "",
      };

      if (card.note) {
        task.description = card.note;
      } else if (card.content) {
        const issue = card.content;

        task.key = `#${issue.number}`;
        task.title = issue.title;
        task.description = issue.body;
      }

      tasks.push(task);
    });
  });

  return tasks;
}

function extractBoards(rawData: any): Board[] {
  let boards: Board[] = [];

  rawData.data.viewer.repositories.nodes.forEach(repositorie => {
    repositorie.projects.nodes.forEach(project => {
      boards.push({
        id: project.id,
        name: `[${repositorie.nameWithOwner}] ${project.name}`
      });
    });
  });

  return boards;
}

function getAuthorizationn(event: any): string {
  let authorization = "";

  if (process.env.IS_LOCAL && process.env.GITHUB_TOKEN) {
    authorization = `bearer ${process.env.GITHUB_TOKEN}`
  } else if (event.headers && event.headers.Authorization) {
    authorization = event.headers.Authorization;
  }

  return authorization;
}

type AllResponse = {
  provider: string,
  boards: Board[],
}

type IdResponse = {
  provider: string,
  board: Board,
}

type TasksResponse = {
  provider: string,
  tasks: Task[],
}

type LambdaResponse = {
  statusCode: number,
  body: string,
}

type Board = {
  id: string,
  name: string,
}

type Task = {
  id: string,
  key?: string,
  value?: number,
  estimation?: number,
  roi?: number,
  title?: string,
  description: string,
}
