const recognitionListReducer = (state={
	recogReceived: [],
	recogReceivedCount: 0,
	itemsPerPage: 0,
	currentPage: 0,
}, action) => {
	switch(action.type) {
		case 'INIT_RECOG_LIST':
	    return {...state, ...action.payload};
	}

	return state;
};

export default recognitionListReducer;