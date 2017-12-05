import React from 'react';
import {connect} from 'react-redux';
import {goToNextPage} from '../actions/recognitionListAction';

@connect((store) => {
	return {
		recogReceivedCount: store.recognitionList.recogReceivedCount,
		itemsPerPage: store.recognitionList.itemsPerPage,
		currentPage: store.recognitionList.currentPage,
	}
})
class Pagination extends React.Component {
	render(){

		console.log(this.props, 'PROPS');

		let totalNumberOfPages = Math.ceil(this.props.recogReceivedCount/this.props.itemsPerPage);
		let currentPage = parseInt(this.props.currentPage);
		let numOfPagesToDisplay = 3;
		let pages = [];


		if (currentPage > 1) {
			pages.push( <li key='jump-to-first'><a href="#">Jump to first</a></li> );
			pages.push( <li key='prev'><a href="#">Prev</a></li> );
		}

		for(let i = 0; i < numOfPagesToDisplay; i++){
			let url = '/recognition/received/'+(currentPage+i);
			let activeClass = (currentPage == i+1) ? 'active' : '';

			pages.push( <li className={activeClass} key={i}><a href="#">{currentPage+i}</a></li> );
		}

		if (currentPage < totalNumberOfPages){
			pages.push( <li key='next'><a href="#">Next</a></li> );
			pages.push(	<li key='jump-to-last'><a href="#">Jump to Last</a></li> );
		}

		return(
			<ul className="pagination">{pages}</ul>
		);
	}
}

export default Pagination;