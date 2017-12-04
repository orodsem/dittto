import React from 'react';
import ReactDOM from 'react-dom';
import RecognitionListing from './components/recognition/recognition-received';

const recogList = document.getElementById('recognition-listing');

if (recogList) {
  try {
    ReactDOM.render(
      <RecognitionListing {...(recogList.dataset)}/>,
      recogList
    );
  } catch (error) {
    console.error(error);
  }
}