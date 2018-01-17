import { SET_CONFIG, FETCH_BOARDS_REQUEST, FETCH_BOARDS_FAILURE, FETCH_BOARDS_SUCCESS } from './actions';

const providerConfigSerialized = localStorage.getItem('providerConfig')
const initialState = {
  initialized: (providerConfigSerialized) ? true : false,
  provider: 'github',
  providerConfig: JSON.parse(providerConfigSerialized),
  boards: [],
  boardsLoading: false,
  boardsError: null,
}

export function reduceApp(state = initialState, action) {
  switch (action.type) {
    case SET_CONFIG:
      const providerConfig = {
        token: action.token,
      }
      localStorage.setItem('providerConfig', JSON.stringify(providerConfig))
      
      return Object.assign({}, state, {
        initialized: true,
        providerConfig,
      })
    case FETCH_BOARDS_REQUEST:
      return Object.assign({}, state, {
        boardsLoading: true,
      })
    case FETCH_BOARDS_FAILURE:
      return Object.assign({}, state, {
        boardsLoading: false,
        boardsError: action.error,
        boards: [],
      })
    case FETCH_BOARDS_SUCCESS:
      return Object.assign({}, state, {
        boardsLoading: false,
        boardsError: null,
        boards: action.response.boards,
      })
    default:  
      return state
  }  
}