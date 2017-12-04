import React from 'react';

class Pagination extends React.Component {
	render(){

		let totalNumberOfPages = Math.ceil(this.props.recognitionReceivedCount/this.props.itemsPerPage);
		let currentPage = parseInt(this.props.currentPage);
		let numOfPagesToDisplay = 3;
		let pages = [];


		if (currentPage > 1) {
			pages.push( <li key='jump-to-first'><a href={'/recognition/received/'+(currentPage-1)}>Jump to first</a></li> );
			pages.push( <li key='prev'><a href={'/recognition/received/'+(currentPage-1)}>Prev</a></li> );
		}

		for(let i = 0; i < numOfPagesToDisplay; i++){
			let url = '/recognition/received/'+(currentPage+i);
			let activeClass = (currentPage == i+1) ? 'active' : '';

			pages.push( <li className={activeClass} key={i}><a href={url}>{currentPage+i}</a></li> );
		}

		if (currentPage < totalNumberOfPages){
			pages.push( <li key='next'><a href={'/recognition/received/'+(currentPage+1)}>Next</a></li> );

			pages.push(	<li key='jump-to-last'><a href={'/recognition/received/'+totalNumberOfPages}>Jump to Last</a></li> );
		}

		return(
			<ul className="pagination">{pages}</ul>
		);
	}
}

export default Pagination;