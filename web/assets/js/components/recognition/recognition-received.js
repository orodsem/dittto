import React from 'react';
import Pagination from '../pagination'

class RecognitionListing extends React.Component {

	render(){

		console.log(this.props, 'PROPS');
		let {recogReceived} = this.props; 

		recogReceived = JSON.parse(recogReceived);

		console.log(recogReceived, 'recogReceived');

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

				{
				<Pagination 
					recognitionReceivedCount={this.props.recogReceivedCount}
					itemsPerPage={this.props.itemsPerPage}
					currentPage={this.props.currentPage}/>
				}
							
			</div>
		);
	}
}

export default RecognitionListing;
