import React, { Component } from 'react';
import { connect } from 'react-redux';
import { ApiClient, BoardApi } from 'scrumboard-it-client';
import CircularProgress from 'material-ui/CircularProgress';

import TaskList from '../../components/TaskList';

import { fetchTasks, fetchTasksFailure, fetchTasksSuccess, addTaskToPool } from '../../actions';

const mapStateToProps = state => {
  return {
    tasks: state.tasks.filter((task) => state.printPool.indexOf(task) === -1),
    loading: state.tasksLoading,
    error: state.tasksError,
    providerConfig: state.providerConfig,
    boardId: state.selectedBoard.id,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchTasks: ({ token }, boardId) => {
      dispatch(fetchTasks())

      const defaultClient = ApiClient.instance;
      let Bearer = defaultClient.authentications['Bearer'];
      Bearer.apiKey = token
      Bearer.apiKeyPrefix = 'Bearer'

      const api = new BoardApi();
      api.getTasksByBoardId(boardId).then(function(data) {
        dispatch(fetchTasksSuccess(data));
      }, function(error) {
        dispatch(fetchTasksFailure(error));
      });
    },
    onAddTask: (task) => {
      dispatch(addTaskToPool(task));
    }
  }
}

class TaskListContainer extends Component {
  componentDidMount() {
    const { fetchTasks, providerConfig, boardId } = this.props;
    fetchTasks(providerConfig, boardId);
  }

  render() {
    const { tasks, onAddTask, loading, error } = this.props;

    if (loading) {
      return <div className="loading-screen"><CircularProgress /></div>
    } else if (error) {
      return <p>{error.message}</p>
    } else {
      return <TaskList tasks={tasks} onAdd={onAddTask} />
    }
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(TaskListContainer);