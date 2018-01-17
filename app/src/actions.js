export const SET_CONFIG = 'SET_CONFIG'
export const FETCH_BOARDS_REQUEST = 'FETCH_BOARDS_REQUEST'
export const FETCH_BOARDS_FAILURE = 'FETCH_BOARDS_FAILURE'
export const FETCH_BOARDS_SUCCESS = 'FETCH_BOARDS_SUCCESS'


export function setConfig(token) {
  return { type: SET_CONFIG, token }
}

export function fetchBoards() {
  return { type: FETCH_BOARDS_REQUEST }
}

export function fetchBoardsFailure(error) {
  return { type: FETCH_BOARDS_FAILURE, error: error.error }
}

export function fetchBoardsSuccess(response) {
  return { type: FETCH_BOARDS_SUCCESS, response }
}