import React, { Component } from 'react';

const Board = ({ id, name, onClick }) => {
  return (
    <tr>
      <td>{id}</td>
      <td><a onClick={() => {onClick(id)}}>{name}</a></td>
    </tr>
  );
}

export default Board;
