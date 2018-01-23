import React from 'react';
import { List, ListItem } from 'material-ui/List';
import Paper from 'material-ui/Paper';
import './BoardList.css';

const BoardList = ({ boards, onBoardClick }) => {
  return (
    <div className="board-list">
      <p>Select a board:</p>
      <Paper zDepth={2}>
        <List>
          {boards.map((board) => <div key={board.id}><ListItem primaryText={board.name} onClick={() => { onBoardClick(board) }} /></div>)}
        </List>
      </Paper>
    </div>
  );
}

export default BoardList;
