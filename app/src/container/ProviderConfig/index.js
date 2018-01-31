import React, { Component } from 'react';
import { connect } from 'react-redux';
import { OauthApi } from 'scrumboard-it-client';
import RaisedButton from 'material-ui/RaisedButton';
import FontIcon from 'material-ui/FontIcon';
import Paper from 'material-ui/Paper';
import CircularProgress from 'material-ui/CircularProgress';

import './ProviderConfig.css';

import { fetchOauthConfig, fetchOauthConfigSuccess, fetchOauthConfigFailure } from './../../actions'

const mapStateToProps = state => {
  return {
    config: state.oauthConfig,
    configLoading: state.oauthConfigLoading,
    configError: state.oauthConfigError,
    token: state.oauthToken,
    tokenLoading: state.oauthTokenLoading,
    tokenError: state.oauthTokenError,
    provider: state.provider,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchOauthConfig: (provider) => {
      dispatch(fetchOauthConfig())

      const api = new OauthApi();
      api.getAuthorizationConfig(provider).then(function(data) {
        dispatch(fetchOauthConfigSuccess(data));
      }, function(error) {
        dispatch(fetchOauthConfigFailure(error));
      });
    }
  }
}

class ProviderConfig extends Component {
  componentDidMount() {
    const { fetchOauthConfig, provider } = this.props;
    fetchOauthConfig(provider);
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