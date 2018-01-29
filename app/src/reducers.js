import { FETCH_BOARDS_REQUEST, FETCH_BOARDS_FAILURE, FETCH_BOARDS_SUCCESS, SELECT_BOARD, UNSELECT_BOARD, FETCH_TASKS_FAILURE, FETCH_TASKS_REQUEST, FETCH_TASKS_SUCCESS, ADD_TASK_TO_POOL, REMOVE_TASK_FROM_POOL, TOGGLE_POOL_VIEW, FETCH_OAUTH_CONFIG_REQUEST, FETCH_OAUTH_TOKEN_FAILURE, FETCH_OAUTH_CONFIG_SUCCESS, FETCH_OAUTH_CONFIG_FAILURE, FETCH_OAUTH_TOKEN_REQUEST, FETCH_OAUTH_TOKEN_SUCCESS } from './actions';

const providerConfigSerialized = localStorage.getItem('providerConfig')
const initialState = {
  initialized: (providerConfigSerialized) ? true : false,
  provider: 'github',
  providerConfig: JSON.parse(providerConfigSerialized),
  boards: [],
  boardsLoading: false,
  boardsError: null,
  selectedBoard: null,
  tasks: [],
  tasksLoading: false,
  tasksError: null,
  printPool: [],
  poolTemplateView: false,
  oauthConfig: null,
  oauthConfigLoading: false,
  oauthConfigError: null,
  oauthToken: null,
  oauthTokenLoading: false,
  oauthTokenError: null,
}

export function reduceApp(state = initialState, action) {
  switch (action.type) {
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
    case SELECT_BOARD:
      return Object.assign({}, state, {
        selectedBoard: action.board,
      })
    case UNSELECT_BOARD:
      return Object.assign({}, state, {
        selectedBoard: null,
        tasks: [],
        printPool: [],
        tasksLoading: false,
        tasksError: null,
      })
    case FETCH_TASKS_REQUEST:
      return Object.assign({}, state, {
        tasksLoading: true,
      })
    case FETCH_TASKS_FAILURE:
      return Object.assign({}, state, {
        tasksLoading: false,
        tasksError: action.error,
        tasks: [],
        printPool: [],
      })
    case FETCH_TASKS_SUCCESS:
      return Object.assign({}, state, {
        tasksLoading: false,
        tasksError: null,
        tasks: action.response.tasks,
      })
      case ADD_TASK_TO_POOL:
      return Object.assign({}, state, {
        printPool: [...state.printPool, action.task],
      })
      case REMOVE_TASK_FROM_POOL:
      return Object.assign({}, state, {
        printPool: state.printPool.filter((task) => task !== action.task),
      })
      case TOGGLE_POOL_VIEW:
      return Object.assign({}, state, {
        poolTemplateView: !state.poolTemplateView,
      })
      case FETCH_OAUTH_CONFIG_REQUEST:
        return Object.assign({}, state, {
          oauthConfigLoading: true,
        })
      case FETCH_OAUTH_CONFIG_FAILURE:
        return Object.assign({}, state, {
          oauthConfigLoading: false,
          oauthConfigError: action.error,
        })
      case FETCH_OAUTH_CONFIG_SUCCESS:
        return Object.assign({}, state, {
          oauthConfigLoading: false,
          oauthConfigError: null,
          oauthConfig: action.response,
        })
      case FETCH_OAUTH_TOKEN_REQUEST:
        return Object.assign({}, state, {
          oauthTokenLoading: true,
        })
      case FETCH_OAUTH_TOKEN_FAILURE:
        return Object.assign({}, state, {
          oauthTokenLoading: false,
          oauthTokenError: action.error,
        })
      case FETCH_OAUTH_TOKEN_SUCCESS:
        const providerConfig = {
          token: action.response.token,
        }
        localStorage.setItem('providerConfig', JSON.stringify(providerConfig))

        return Object.assign({}, state, {
          initialized: true,
          providerConfig,
          oauthTokenLoading: false,
          oauthTokenError: null,
          oauthToken: action.response,
        })
      default:  
      return state
    }  
  }