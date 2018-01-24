import React, { Component } from 'react';
import { connect } from 'react-redux'
import CircularProgress from 'material-ui/CircularProgress';
import Dialog from 'material-ui/Dialog';
import FlatButton from 'material-ui/FlatButton';

import TaskList from '../../components/TaskList';

import { fetchTasks, fetchTasksFailure, fetchTasksSuccess, addTaskToPool, openDialog, closeDialog } from '../../actions';

const mapStateToProps = state => {
  return {
    tasks: state.tasks.filter((task) => state.printPool.indexOf(task) === -1),
    loading: state.tasksLoading,
    error: state.tasksError,
    providerConfig: state.providerConfig,
    boardId: state.selectedBoard.id,
    dialogOpen: state.dialogOpen,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchTasks: ({ token }, boardId) => {
      dispatch(fetchTasks())
      
      fetch(`https://api.scrumboard-it.org/boards/${boardId}/tasks`, {
        headers: new Headers({
          'Authorization': `Bearer ${token}`,
        })
      }).then((response) => {
        if (response.ok) {
          response.json().then((data) => {
            dispatch(fetchTasksSuccess(data));
            if (data.tasks.length === 0) {
              dispatch(openDialog());
            }
          })
        } else {
          response.json().then((error) => {
            dispatch(fetchTasksFailure(error));
          })
        }
      }).catch((error) => {
        dispatch(fetchTasksFailure(error));
      })
    },
    onAddTask: (task) => {
      dispatch(addTaskToPool(task));
    },
    doOpenDialog: () => {
      dispatch(openDialog());
    },
    doCloseDialog: () => {
      dispatch(closeDialog());
    },
  }
}

class TaskListContainer extends Component {
  componentDidMount() {
    const { fetchTasks, providerConfig, boardId } = this.props;
    fetchTasks(providerConfig, boardId);
  }
  
  render() {
    const { tasks, onAddTask, loading, error, dialogOpen, doCloseDialog } = this.props;

    const buttons = [
      <FlatButton
        label="OK"
        primary={true}
        onClick={doCloseDialog}
      />,
    ];

    if (loading) {
      return <div className="loading-screen"><CircularProgress /></div>
    } else if (error) {
      return <p>{error.message}</p>
    } else {
      return (
        <div>
          <Dialog title="No tasks" actions={buttons} modal={false} open={dialogOpen}>
            This board has no task, create some tasks to view them here.
          </Dialog>
          <TaskList tasks={tasks} onAdd={onAddTask} />
        </div>
      );
    }
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(TaskListContainer);