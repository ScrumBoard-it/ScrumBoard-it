import React, { Component } from 'react';
import Board from '../Board';
import axios from 'axios';

class BoardList extends Component {
  constructor(props) {
    super(props);
    this.state = {
      boards: []
    };
  }

  componentDidMount() {
    const url = 'https://w002fc2noj.execute-api.eu-central-1.amazonaws.com/dev/boards';
    //const url = 'http://api.navitia.io/v1'
    axios.request({
      method: 'get',
      url: url
    }).then((response) => {
      console.log(response.data);
    }).catch((error) => {
      console.error(error);
    });
    this.setState({boards: [
      {id: 1, key: 1, title: 'Self'}
    ]});
  }

  render() {
    return (
      <div>
        <p>Select a board:</p>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
            </tr>
          </thead>
          <tbody>
            {this.state.boards.map((item) => <Board id={item.id} key={item.key} name={item.title} />)}
          </tbody>
        </table>
      </div>
    );
  }
}

export default BoardList;
