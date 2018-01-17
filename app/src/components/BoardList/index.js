import React, { Component } from 'react';
import Board from '../Board';

const BoardList = ({ boards }) => {
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
          {boards.map((item) => <Board id={item.id} key={item.key} name={item.name} />)}
        </tbody>
      </table>
    </div>
  );
}

export default BoardList;
