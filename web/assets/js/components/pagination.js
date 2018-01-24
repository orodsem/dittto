import React from 'react';
import {connect} from 'react-redux';
import {setPage} from '../actions/paginationAction';

@connect((store) => {
	return {
		recogReceivedCount: store.recognitionList.recogReceivedCount,
		itemsPerPage: store.recognitionList.itemsPerPage,
		currentPage: store.recognitionList.currentPage,
	}
})
class Pagination extends React.Component {

	setPage(page){
		this.props.handlePagination(page);
	}

	render(){

		console.log(this.props, 'PROPS');
		const defaultNumOfPagesToDisplay = 3;
		let totalNumberOfPages = Math.ceil(this.props.recogReceivedCount/this.props.itemsPerPage);
		let currentPage = parseInt(this.props.currentPage);		
		let numOfPagesToDisplay = (defaultNumOfPagesToDisplay > totalNumberOfPages) ? totalNumberOfPages : defaultNumOfPagesToDisplay;
		let pages = [];
		let page = 1;
		let activeClass = '';

		if (currentPage > 1) {
			pages.push( <li key='jump-to-first'><a href="#" onClick={() => this.setPage(1)}>Jump to first</a></li> );
			pages.push( <li key='prev'><a href="#" onClick={() => this.setPage(currentPage-1)}>Prev</a></li> );
		}

		for(let i = 1; i <= numOfPagesToDisplay; i++){				

			if (currentPage < numOfPagesToDisplay) {
				let activeClass = (currentPage == i) ? 'active' : '';
				pages.push( <li className={activeClass} key={i}><a href="#" onClick={() => this.setPage(i)}>{i}</a></li> );
			}

			if ( currentPage >= numOfPagesToDisplay){
				
				if (currentPage == totalNumberOfPages) {
					page = (currentPage+i) - (numOfPagesToDisplay);
					activeClass = (currentPage == page) ? 'active' : '';
					pages.push( <li className={activeClass} key={i}><a href="#" data-page={page} onClick={() => this.setPage( (currentPage+i) - (numOfPagesToDisplay) )}>{page}</a></li> );	
				}else{
					page = (currentPage+i) - (numOfPagesToDisplay-1);
					activeClass = (currentPage == page) ? 'active' : '';
					pages.push( <li className={activeClass} key={i}><a href="#" data-page={page} onClick={() => this.setPage( page )}>{page}</a></li> );	
				}

			}

		}

		if (currentPage < totalNumberOfPages){
			pages.push( <li key='next'><a href="#" onClick={() => this.setPage(currentPage+1)}>Next</a></li> );
			pages.push(	<li key='jump-to-last'><a href="#" onClick={() => this.setPage(totalNumberOfPages)}>Jump to Last</a></li> );
		}

		return(
			<ul className="pagination">{pages}</ul>
		);
	}
}

export default Pagination;