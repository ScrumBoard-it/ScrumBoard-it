import React, { Component } from 'react';
import logo from './logo.svg';
import './App.css';

import BoardList from './components/BoardList';

class App extends Component {
  render() {
    return (
      <div>
        <BoardList />
      </div>
    );
  }
}

export default App;
