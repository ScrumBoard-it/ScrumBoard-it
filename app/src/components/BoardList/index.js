import React, { Component } from 'react';
import Board from '../Board';

class BoardList extends Component {
  constructor(props) {
    super(props);
    this.state = {
      boards: []
    };
  }

  componentDidMount() {
    const token = '';
    const url = 'https://api.scrumboard-it.org/boards';
    const headers = new Headers();
    headers.append('Authorization', 'Bearer ' + token);
    
    fetch(url, {
      headers: headers
    }).then((response) => {
      return response.json();
    }).then((json) => {
      console.info(json);
      this.setState({boards: json.boards});
    }).catch((error) => {
      console.error(error);
    })
  }

  render() {
    return (
      <div>
        <p>Select a board:</p>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
            </tr>
          </thead>
          <tbody>
            {this.state.boards.map((item) => <Board id={item.id} key={item.key} name={item.name} />)}
          </tbody>
        </table>
      </div>
    );
  }
}

export default BoardList;
