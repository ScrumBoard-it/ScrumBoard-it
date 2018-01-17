import React, { Component } from 'react';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import './App.css';

import ConfigSteps from './container/ConfigSteps';

class App extends Component {
  render() {
    return (
      <MuiThemeProvider>
        <AppBar title="ScrumBoard-it" />
        <RaisedButton label="Default" />
        <ConfigSteps />
      </MuiThemeProvider>
    );
  }
}

export default App;
