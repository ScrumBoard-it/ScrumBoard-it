import React, { Component } from 'react';
import { connect } from 'react-redux'

import BoardList from '../../components/BoardList';

import { fetchBoards, fetchBoardsFailure, fetchBoardsSuccess } from '../../actions';

const mapStateToProps = state => {
  return {
    boards: state.boards,
    loading: state.boardsLoading,
    error: state.boardsError,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchBoards: () => {
      dispatch(fetchBoards())

      const token = '';
      
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
    }
  }
}

class BoardListContainer extends Component {
  componentDidMount() {
    const { fetchBoards } = this.props;
    fetchBoards();
  }
  
  render() {
    const { boards, loading, error } = this.props;
    
    if (loading) {
      return <p>Loading boards</p>
    } else if (error){
      return <p>{error.message}</p>
    } else {
      console.log(boards)
      return <BoardList boards={boards} />
    }
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(BoardListContainer);