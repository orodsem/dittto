import {combineReducers} from 'redux';
import recognitionListReducer from './recognitioListReducer.js';

const allReducers = combineReducers({
	recognitionList: recognitionListReducer
});

export default allReducers;