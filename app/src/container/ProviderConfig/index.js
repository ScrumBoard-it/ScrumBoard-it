import React, { Component } from 'react';
import { connect } from 'react-redux'
import RaisedButton from 'material-ui/RaisedButton';
import FontIcon from 'material-ui/FontIcon';
import Paper from 'material-ui/Paper';
import CircularProgress from 'material-ui/CircularProgress';
import { fetchOauthConfig, fetchOauthConfigSuccess, fetchOauthConfigFailure } from './../../actions'
import './ProviderConfig.css';

const mapStateToProps = state => {
  return {
    config: state.oauthConfig,
    configLoading: state.oauthConfigLoading,
    configError: state.oauthConfigError,
    token: state.oauthToken,
    tokenLoading: state.oauthTokenLoading,
    tokenError: state.oauthTokenError,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchOauthConfig: () => {
      dispatch(fetchOauthConfig())

      fetch('https://api.scrumboard-it.org/oauth/github/config')
        .then((response) => {
          if (response.ok) {
            response.json().then((data) => {
              dispatch(fetchOauthConfigSuccess(data));
            })
          } else {
            response.json().then((error) => {
              dispatch(fetchOauthConfigFailure(error));
            })
          }
        }).catch((error) => {
          dispatch(fetchOauthConfigFailure(error));
        })
    }
  }
}

class ProviderConfig extends Component {
  componentDidMount() {
    const { fetchOauthConfig } = this.props;
    fetchOauthConfig();
  }

  render() {
    const { config, configLoading, tokenLoading, provider } = this.props;
    const callbackUrl = `${window.location.origin}/auth/github/callback`;
    const githubAuthorizeUrl = (client_id) => `https://github.com/login/oauth/authorize?client_id=${client_id}&redirect_uri=${encodeURIComponent(callbackUrl)}&scope=repo&response_type=code`;

    return (
      <Paper className="provider-config" zDepth={1}>
        <h2>{provider.charAt(0).toUpperCase() + provider.slice(1)} configuration</h2>

        <p>
          To be able to print your Github cards you need to authorize ScrumBoard-it
          to access your repo informations
        </p>

        {(configLoading || tokenLoading) && (
          <div className="loading-screen"><CircularProgress /></div>
        )}
        {config && (
          <RaisedButton label="Authorize" primary={true} href={githubAuthorizeUrl(config.client_id)} icon={<FontIcon className="material-icons">lock</FontIcon>} />
        )}
      </Paper>
    );
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(ProviderConfig);