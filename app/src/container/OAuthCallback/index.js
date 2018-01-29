import React, { Component } from 'react';
import { connect } from 'react-redux'
import { Redirect } from 'react-router-dom'
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import Paper from 'material-ui/Paper';
import CircularProgress from 'material-ui/CircularProgress';
import { fetchOauthToken, fetchOauthTokenSuccess, fetchOauthTokenFailure } from './../../actions'

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
    fetchOauthToken: () => {
      dispatch(fetchOauthToken())

      const url = new URL('https://api.scrumboard-it.org/oauth/github/token'),
      params = {
        code: getParameterByName('code')
      }
      Object.keys(params).forEach(key => url.searchParams.append(key, params[key]))
      fetch(url)
        .then((response) => {
          if (response.ok) {
            response.json().then((data) => {
              dispatch(fetchOauthTokenSuccess(data));
            })
          } else {
            response.json().then((error) => {
              dispatch(fetchOauthTokenFailure(error));
            })
          }
        }).catch((error) => {
          dispatch(fetchOauthTokenFailure(error));
        })
    }
  }
}

class OAuthCallback extends Component {
  componentDidMount() {
    const { fetchOauthToken } = this.props;
    fetchOauthToken();
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

const getParameterByName = (name, url) => {
  if (!url) url = window.location.href;
  name = name.replace(/[[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

export default connect(mapStateToProps, mapDispatchToProps)(OAuthCallback);