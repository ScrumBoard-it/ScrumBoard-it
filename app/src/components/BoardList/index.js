import React from 'react';
import { List, ListItem } from 'material-ui/List';

const BoardList = ({ boards, onBoardClick }) => {
  return (
    <div>
      <p>Select a board:</p>
      <List>
        {boards.map((board) => <ListItem primaryText={board.name} onClick={() => { onBoardClick(board) }} key={board.id} />)}
      </List>
    </div>
  );
}

export default BoardList;
