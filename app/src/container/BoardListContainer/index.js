import React, { Component } from 'react';
import { connect } from 'react-redux'
import CircularProgress from 'material-ui/CircularProgress';
import FlatButton from 'material-ui/FlatButton';
import FontIcon from 'material-ui/FontIcon';

import BoardList from '../../components/BoardList';
import TaskListContainer from '../TaskListContainer';

import { fetchBoards, fetchBoardsFailure, fetchBoardsSuccess, selectBoard, unselectBoard } from '../../actions';

const mapStateToProps = state => {
  return {
    boards: state.boards,
    loading: state.boardsLoading,
    error: state.boardsError,
    providerConfig: state.providerConfig,
    selectedBoard: state.selectedBoard,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchBoards: ({ token }) => {
      dispatch(fetchBoards())
      
      fetch('https://api.scrumboard-it.org/boards', {
        headers: new Headers({
          'Authorization': `Bearer ${token}`,
        })
      }).then((response) => {
        if (response.ok) {
          response.json().then((data) => {
            dispatch(fetchBoardsSuccess(data));
          })
        } else {
          response.json().then((error) => {
            dispatch(fetchBoardsFailure(error));
          })
        }
      }).catch((error) => {
        dispatch(fetchBoardsFailure(error));
      })
    },
    selectBoard: (board) => {
      dispatch(selectBoard(board))
    },
    backClick: () => {
      dispatch(unselectBoard())
    },
  }
}

class BoardListContainer extends Component {
  componentDidMount() {
    const { fetchBoards, providerConfig } = this.props;
    fetchBoards(providerConfig);
  }
  
  render() {
    const { boards, loading, error, selectBoard, selectedBoard, backClick } = this.props;
    
    if (selectedBoard) {
      return (
        <div>
          <FlatButton
            label={selectedBoard.name}
            fullWidth={true}
            icon={<FontIcon className="material-icons">arrow_back</FontIcon>}
            onClick={backClick}
          />
          <TaskListContainer boardId={selectedBoard} />
        </div>
      )
    } else if (loading) {
      return <CircularProgress />
    } else if (error){
      return <p>{error.message}</p>
    } else {
      return <BoardList boards={boards} onBoardClick={selectBoard} />
    }
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(BoardListContainer);