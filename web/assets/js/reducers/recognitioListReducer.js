const recognitionListReducer = (state={
	recogReceived: [],
	recogReceivedCount: 0,
	itemsPerPage: 0,
	currentPage: 0,
}, action) => {
	switch(action.type) {
		case 'INIT_RECOG_LIST':
			console.log('INIT_RECOG_LIST!!!!!');
	    return {...state, ...action.payload};
	  	break;

		case 'FETCH_RECOG_LIST_FULFILLED':
			console.log('FETCH_RECOG_LIST_FULFILLED!!!!!');
	    return {
	    	...state,
	    	recogReceived: action.payload.receivedRecognition,
	    	recogReceivedCount: action.payload.receivedRecognitionCount,
	    	itemsPerPage: action.payload.itemsPerPage,
	    	currentPage: action.payload.currentPage
	    };
	  	break;
	}

	return state;
};

export default recognitionListReducer;