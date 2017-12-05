/*
	
	Redux Setup:
	--------------
	1. Setup the store
	2. Setup the reducers
	3. Setup actions
	4. Setup components
	5. Setup Providers

*/

import React from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from './store';	
import RecognitionListing from './components/recognition/recognition-received';
import {initRecogList} from './actions/recognitionListAction';


const recogListRoot = document.getElementById('recognition-listing');
const recogListData = recogListRoot.dataset;


// console.log(recogListData, 'recogListData');


if (recogListRoot) {
  try {

  	// store.dispatch(initRecogList(recogListData));

    ReactDOM.render(
      <RecognitionListing {...(recogListRoot.dataset)}/>,      
      recogListRoot
    );
  } catch (error) {
    console.error(error);
  }
}