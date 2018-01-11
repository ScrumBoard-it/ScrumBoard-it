import React, { Component } from 'react';
import Board from '../Board';

class BoardList extends Component {
  render() {
    return (
      <div>
        <p>Select a board:</p>
        <table>
          <tr>
            <th>ID</th>
            <th>Name</th>
          </tr>
          <Board id="1" name="Self" />
        </table>
      </div>
    );
  }
}

export default BoardList;
