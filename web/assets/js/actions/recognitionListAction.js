
export function initRecogList(recognitionList){
	return {
		type: 'INIT_RECOG_LIST',
		payload: recognitionList
	};
}

export function startFetchRecogList(recognitionList){
	return {
		type: 'FETCH_RECOG_LIST_START'
	};
}

export function fetchRecogListFulfilled(recognitionList){
	return {
		type: 'FETCH_RECOG_LIST_FULFILLED',
		payload: recognitionList
	};
}

export function fetchRecogListError(message){
	return {
		type: 'FETCH_RECOG_LIST_ERROR',
		payload: message
	};
}