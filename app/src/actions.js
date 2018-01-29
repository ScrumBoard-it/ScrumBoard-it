export const FETCH_BOARDS_REQUEST = 'FETCH_BOARDS_REQUEST'
export const FETCH_BOARDS_FAILURE = 'FETCH_BOARDS_FAILURE'
export const FETCH_BOARDS_SUCCESS = 'FETCH_BOARDS_SUCCESS'
export const SELECT_BOARD = 'SELECT_BOARD'
export const UNSELECT_BOARD = 'UNSELECT_BOARD'
export const FETCH_TASKS_REQUEST = 'FETCH_TASKS_REQUEST'
export const FETCH_TASKS_FAILURE = 'FETCH_TASKS_FAILURE'
export const FETCH_TASKS_SUCCESS = 'FETCH_TASKS_SUCCESS'
export const ADD_TASK_TO_POOL = 'ADD_TASK_TO_POOL'
export const REMOVE_TASK_FROM_POOL = 'REMOVE_TASK_FROM_POOL'
export const TOGGLE_POOL_VIEW = 'TOGGLE_POOL_VIEW'
export const FETCH_OAUTH_CONFIG_REQUEST = 'FETCH_OAUTH_CONFIG_REQUEST'
export const FETCH_OAUTH_CONFIG_FAILURE = 'FETCH_OAUTH_CONFIG_FAILURE'
export const FETCH_OAUTH_CONFIG_SUCCESS = 'FETCH_OAUTH_CONFIG_SUCCESS'
export const FETCH_OAUTH_TOKEN_REQUEST = 'FETCH_OAUTH_TOKEN_REQUEST'
export const FETCH_OAUTH_TOKEN_FAILURE = 'FETCH_OAUTH_TOKEN_FAILURE'
export const FETCH_OAUTH_TOKEN_SUCCESS = 'FETCH_OAUTH_TOKEN_SUCCESS'

export function fetchBoards() {
  return { type: FETCH_BOARDS_REQUEST }
}

export function fetchBoardsFailure(error) {
  return { type: FETCH_BOARDS_FAILURE, error: error.error }
}

export function fetchBoardsSuccess(response) {
  return { type: FETCH_BOARDS_SUCCESS, response }
}

export function selectBoard(board) {
  return { type: SELECT_BOARD, board }
}

export function unselectBoard() {
  return { type: UNSELECT_BOARD }
}

export function fetchTasks(boardId) {
  return { type: FETCH_TASKS_REQUEST, boardId }
}

export function fetchTasksFailure(error) {
  return { type: FETCH_TASKS_FAILURE, error: error.error }
}

export function fetchTasksSuccess(response) {
  return { type: FETCH_TASKS_SUCCESS, response }
}

export function addTaskToPool(task) {
  return { type: ADD_TASK_TO_POOL, task }
}

export function removeTaskFromPool(task) {
  return { type: REMOVE_TASK_FROM_POOL, task }
}

export function togglePoolView() {
  return { type: TOGGLE_POOL_VIEW }
}

export function fetchOauthConfig() {
  return { type: FETCH_OAUTH_CONFIG_REQUEST }
}

export function fetchOauthConfigFailure(error) {
  return { type: FETCH_OAUTH_CONFIG_FAILURE, error: error.error }
}

export function fetchOauthConfigSuccess(response) {
  return { type: FETCH_OAUTH_CONFIG_SUCCESS, response }
}

export function fetchOauthToken() {
  return { type: FETCH_OAUTH_TOKEN_REQUEST }
}

export function fetchOauthTokenFailure(error) {
  return { type: FETCH_OAUTH_TOKEN_FAILURE, error: error.error }
}

export function fetchOauthTokenSuccess(response) {
  return { type: FETCH_OAUTH_TOKEN_SUCCESS, response }
}