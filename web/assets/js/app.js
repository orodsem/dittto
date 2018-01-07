import React from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from './store';	
import RecognitionListing from './components/recognition/recognition-received';
import {initRecogList} from './actions/recognitionListAction';

const recogListRoot = document.getElementById('recognition-listing');
const recogReceivedParsed = JSON.parse(recogListRoot.dataset.recogReceived);
const recogListData = {...recogListRoot.dataset, recogReceived: recogReceivedParsed};
console.log(recogListData, 'recogListData');

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
        </div>        
      </Provider>,
      recogListRoot
    );
  } catch (error) {
    console.error(error);
  }
}