import { SET_CONFIG } from './actions';

const initialState = {
  initialized: false,
  provider: 'github',
  providerConfig: null,
}

export function reduceApp(state = initialState, action) {
  switch (action.type) {
    case SET_CONFIG:
      return Object.assign({}, state, {
        initialized: true,
        providerConfig: {
          token: action.token,
        },
      })
    default:  
      return state
  }  
}