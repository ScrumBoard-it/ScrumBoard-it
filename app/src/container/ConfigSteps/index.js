import React from 'react';
import { connect } from 'react-redux'

import BoardListContainer from '../../container/BoardListContainer';
import ProviderConfig from '../../container/ProviderConfig';

const mapStateToProps = state => {
  return {
    initialized: state.initialized,
    provider: state.provider,
  }
}

const ConfigSteps = ({initialized, provider}) => {
  if (!initialized) {
    return <ProviderConfig provider={provider} />
  } else {
    return <BoardListContainer />
  }
}

export default connect(mapStateToProps)(ConfigSteps);