import React from 'react';
import {connect} from 'react-redux';
import Pagination from '../pagination';
import axios from 'axios';
import * as recogList from '../../actions/recognitionListAction';

@connect((store) => {
	return {		
		recogReceived: store.recognitionList.recogReceived,
		recogReceivedCount: store.recognitionList.recogReceivedCount,
		itemsPerPage: store.recognitionList.itemsPerPage,
		currentPage: store.recognitionList.currentPage,
	}
})
class RecognitionListing extends React.Component {

	constructor(props) {
    super(props);
    this.handlePagination = this.handlePagination.bind(this);;
    this.fetchRecogListing = this.fetchRecogListing.bind(this);
  }

	handlePagination(page){		
		this.fetchRecogListing(page);
	}

	fetchRecogListing(page){
		
		this.props.dispatch((dispatch) => {
			dispatch(recogList.startFetchRecogList());
			axios.get('http://127.0.0.1:8000/recognition/received/'+page)
				.then((response) => {
					dispatch(recogList.fetchRecogListFulfilled(response.data));	
					console.log(response.data, 'RESPONSE!');
				}).catch((err) => {
					dispatch(recogList.fetchRecogListError, "ERROR: " + err);
				});
		});

	}

	render(){

		let {recogReceived} = this.props;
		let recogListRows = recogReceived.map( (item) => {

			let formattedDate = moment(item.receivedAt.date).format('MM-DD-YYYY, h:mm a');

			return (
				<tr key={item.id}>
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
					<tbody>

					<tr>
						<th>ID</th>
						<th>Response Type</th>
						<th>Sender</th>
						<th>Received At</th>
					</tr>

					{recogListRows }

					</tbody>
				</table>

				<Pagination handlePagination={this.handlePagination}/>

			</div>
		);
	}
}

export default RecognitionListing;
