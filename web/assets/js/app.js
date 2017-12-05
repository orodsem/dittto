import React from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from './store';	
import RecognitionListing from './components/recognition/recognition-received';
import Pagination from './components/pagination'
import {initRecogList} from './actions/recognitionListAction';

const recogListRoot = document.getElementById('recognition-listing');
const recogListData = recogListRoot.dataset;

store.subscribe(() => {
  console.log('State Changed: ', store.getState());
});

if (recogListRoot) {
  try {

  	store.dispatch(initRecogList(recogListData));

    ReactDOM.render(
      <Provider store={store}>
        <div>
          <RecognitionListing />
          <Pagination />  
        </div>        
      </Provider>,
      recogListRoot
    );
  } catch (error) {
    console.error(error);
  }
}