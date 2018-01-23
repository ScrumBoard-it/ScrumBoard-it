import React from 'react';
import { List, ListItem } from 'material-ui/List';
import Divider from 'material-ui/Divider';
import Paper from 'material-ui/Paper';

const BoardList = ({ boards, onBoardClick }) => {
  return (
    <div>
      <p>Select a board:</p>
      <Paper zDepth={2}>
        <List>
          {boards.map((board) => <div><ListItem primaryText={board.name} onClick={() => { onBoardClick(board) }} key={board.id} /></div>)}
        </List>
      </Paper>
    </div>
  );
}

export default BoardList;
