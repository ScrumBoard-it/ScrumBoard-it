import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux'
import { createStore } from 'redux'
import {
  BrowserRouter as Router,
  Route
} from 'react-router-dom'
import { reduceApp } from './reducers'
import './index.css';
import App from './App';
import OAuthCallback from './container/OAuthCallback';
import registerServiceWorker from './registerServiceWorker';

let store = createStore(reduceApp)

ReactDOM.render(
  <Provider store={store}>
    <Router>
      <div class="full-height">
        <Route exact path="/" component={App}/>
        <Route path="/auth/github/callback" component={OAuthCallback}/>
      </div>
    </Router>
  </Provider>,
  document.getElementById('root')
);
registerServiceWorker();
