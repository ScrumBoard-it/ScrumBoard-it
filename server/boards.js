// @flow
'use strict';

const rp = require('request-promise');

module.exports.all = (event: any, context: any, callback: (error: ?Error, data: ?LambdaResponse) => void) => {
  getNextBoards(event, [], null, callback)
};

function getNextBoards(event: any, previousBoards: Boards[], startAfter: ?string, callback: (error: ?any, data: ?LambdaResponse) => void): void {
  let repositoriesFilter = [
    "first: 100",
    "affiliations:ORGANIZATION_MEMBER"
  ];
  if (startAfter) {
    repositoriesFilter.push(`after: "${startAfter}"`);
  }

  let options = {
    method: 'POST',
    uri: 'https://api.github.com/graphql',
    body: {
      query: `query {
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
      }`
    },
    headers: {
      'Authorization': `${getAuthorizationn(event)}`,
      'User-Agent': process.env.SERVICE_NAME || 'scrumboardit-server-local'
    },
    json: true
  };

  rp(options)
    .then(rawData => {
      let pageBoards: Boards[] = extractBoards(rawData);
      let hasNextPage: boolean = rawData.data.viewer.repositories.pageInfo.hasNextPage;
      let endCursor: string = rawData.data.viewer.repositories.pageInfo.endCursor;
      let boards: Boards[] = previousBoards.concat(pageBoards);
      if (hasNextPage) {
        getNextBoards(event, boards, endCursor, callback);
      } else {
        callback(null, {
          statusCode: 200,
          body: JSON.stringify({
            provider: 'Github',
            boards
          }),
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

function extractBoards(rawData: any): Boards[] {
  let boards: Boards[] = [];

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

type LambdaResponse = {
  statusCode: number,
  body: string
}

type Boards = {
  id?: number,
  name: string,
}
