class RecognitionListingPagination extends React.Component {
	render(){

		let totalNumberOfPages = Math.ceil(this.props.recognitionReceivedCount/this.props.itemsPerPage);
		let currentPage = this.props.currentPage;
		let numOfPagesToDisplay = 3;
		let pages = [];

		if (currentPage > 1) {

			pages.push(
				<li><a href={'/recognition/received/'+(currentPage-1)}>Jump to first</a></li>
			);

			pages.push(
				<li><a href={'/recognition/received/'+(currentPage-1)}>Prev</a></li>
			);

		}

		for(let i = 0; i < numOfPagesToDisplay; i++){

			let url = '/recognition/received/'+(currentPage+i);

			pages.push(
				<li><a href={url}>{currentPage+i}</a></li>
			);
		}

		if (currentPage < totalNumberOfPages){
			pages.push(
				<li><a href={'/recognition/received/'+(currentPage+1)}>Next</a></li>
			);

			pages.push(
				<li><a href={'/recognition/received/'+totalNumberOfPages}>Jump to Last</a></li>
			);
		}

		return(
			<ul className="pagination">
        {pages}
	    </ul>
		);
	}
}

class RecognitionListing extends React.Component {

	render(){

		let recogListRows = this.props.receivedRecognition.map( (item) => {

			let formattedDate = moment(item.receivedAt.date).format('MM-DD-YYYY, h:mm a');

			return (
				<tr>
					<td>{item.id}</td>
					<td>{item.responseType}</td>
					<td>{item.senderName}</td>
					<td>{formattedDate}</td>
				</tr>
			);
		});

		return(

			<div className="recognition-list">
				<table className="table table-striped table-condensed">				
					<tr>
						<th>ID</th>
						<th>Response Type</th>
						<th>Sender</th>
						<th>Received At</th>
					</tr>

					{recogListRows}

				</table>

				<RecognitionListingPagination 
					recognitionReceivedCount={this.props.recognitionReceivedCount}
					itemsPerPage={this.props.itemsPerPage}
					currentPage={this.props.currentPage}/>
							
			</div>
		);
	}
}

window.RecognitionListing =  RecognitionListing;
