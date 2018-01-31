import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';
import { OauthApi } from 'scrumboard-it-client';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import Paper from 'material-ui/Paper';
import CircularProgress from 'material-ui/CircularProgress';

import { fetchOauthToken, fetchOauthTokenSuccess, fetchOauthTokenFailure } from './../../actions'

const getParameterByName = (name, url) => {
  if (!url) url = window.location.href;
  name = name.replace(/[[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

const mapStateToProps = state => {
  return {
    loading: state.oauthTokenLoading,
    token: state.oauthToken,
    error: state.oauthTokenError,
    provider: state.provider,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchOauthToken: (provider) => {
      dispatch(fetchOauthToken())

      const api = new OauthApi();
      api.getAuthorizationToken(provider, getParameterByName('code')).then(function(data) {
        dispatch(fetchOauthTokenSuccess(data));
      }, function(error) {
        dispatch(fetchOauthTokenFailure(error));
      });
    }
  }
}

class OAuthCallback extends Component {
  componentDidMount() {
    const { fetchOauthToken, provider } = this.props;
    fetchOauthToken(provider);
  }

  render() {
    const { loading, token, error, provider } = this.props;

    return (
      <MuiThemeProvider>
        <Paper className="provider-config" zDepth={1}>
          <h2>{provider.charAt(0).toUpperCase() + provider.slice(1)} configuration</h2>

          {(loading) && (
            <div className="loading-screen"><CircularProgress /></div>
          )}
          {(error) && (
            <p>{error}</p>
          )}
          {token && (
            <Redirect to="/" />
          )}
        </Paper>
      </MuiThemeProvider>
    );
  }
};

export default connect(mapStateToProps, mapDispatchToProps)(OAuthCallback);