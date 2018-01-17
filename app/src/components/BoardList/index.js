import React from 'react';

const Board = ({ id, name, onClick }) => {
  return (
    <tr>
      <td>{id}</td>
      <td><a onClick={() => {onClick(id)}}>{name}</a></td>
    </tr>
  );
}

const BoardList = ({ boards, onBoardClick }) => {
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
          {boards.map((board) => <Board id={board.id} key={board.id} name={board.name} onClick={onBoardClick} />)}
        </tbody>
      </table>
    </div>
  );
}

export default BoardList;
