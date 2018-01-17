import React, { Component } from 'react';
import { connect } from 'react-redux'

import BoardList from '../../components/BoardList';
import TaskListContainer from '../TaskListContainer';

import { fetchBoards, fetchBoardsFailure, fetchBoardsSuccess, selectBoard } from '../../actions';

const mapStateToProps = state => {
  return {
    boards: state.boards,
    loading: state.boardsLoading,
    error: state.boardsError,
    providerConfig: state.providerConfig,
    selectedBoardId: state.selectedBoardId,
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
    selectBoard: (boardId) => {
      dispatch(selectBoard(boardId))
    },
  }
}

class BoardListContainer extends Component {
  componentDidMount() {
    const { fetchBoards, providerConfig } = this.props;
    fetchBoards(providerConfig);
  }
  
  render() {
    const { boards, loading, error, selectBoard, selectedBoardId } = this.props;
    
    if (selectedBoardId) {
      return (
          <TaskListContainer boardId={selectedBoardId} />
      )
    } else if (loading) {
      return <p>Loading boards</p>
    } else if (error){
      return <p>{error.message}</p>
    } else {
      return <BoardList boards={boards} onBoardClick={selectBoard} />
    }
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(BoardListContainer);