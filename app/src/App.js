import React, { Component } from 'react';
import { connect } from 'react-redux'
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import FlatButton from 'material-ui/FlatButton/FlatButton';
import FontIcon from 'material-ui/FontIcon';
import './App.css';

import ConfigSteps from './container/ConfigSteps';

import { unselectBoard } from './actions';

const mapStateToProps = state => {
  return {
    onTaskList: (state.selectedBoard !== null)
  }
}

const mapDispatchToProps = dispatch => {
  return {
    backClick: () => {
      dispatch(unselectBoard())
    },
  }
}

const BackButton = ({ backClick }) => {
  return (
    <FlatButton
      label="Boards"
      labelStyle={{'color': '#ffffff'}}
      style={{'marginTop': '0.4em'}}
      primary={true}
      icon={<FontIcon className="material-icons" color="#ffffff">arrow_back</FontIcon>}
      onClick={backClick}
    />
  );
}

class App extends Component {
  render() {
    const { backClick, onTaskList } = this.props;

    const appBarProps = {};
    if (onTaskList) {
      appBarProps.iconElementLeft = (<BackButton backClick={backClick} />);
    } else {
      appBarProps.showMenuIconButton = false;
    }

    return (
      <MuiThemeProvider>
        <div className="full-height">
          <AppBar title="ScrumBoard-it" className="no-print" {...appBarProps} />
          <div className="center">
            <ConfigSteps />
          </div>
        </div>
      </MuiThemeProvider>
    );
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
