import React from 'react';
import { connect } from 'react-redux'

import BoardListContainer from '../../container/BoardListContainer';
import ProviderConfig from '../../components/ProviderConfig';

import { setConfig } from '../../actions';

const mapStateToProps = state => {
  return {
    initialized: state.initialized,
    provider: state.provider,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onSubmitConfig: token => {
      dispatch(setConfig(token))
    }
  }
}

const ConfigSteps = ({initialized, provider, onSubmitConfig}) => {
  if (!initialized) {
    return <ProviderConfig provider={provider} onSubmit={onSubmitConfig} />
  } else {
    return <BoardListContainer />
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(ConfigSteps);