const recognitionListReducer = (state=[], action) => {

	switch(action.type) {
		case 'RECOGNITION_LIST_INIT':
    console.log('RECOGNITION_LIST_INIT');
    break;
	}

	return state;
};

export default recognitionListReducer;