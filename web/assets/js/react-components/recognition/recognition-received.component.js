class RecognitionListing extends React.Component {
	render(){

		let recogList = this.props.receivedRecognition.map( (item) => {

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
					{recogList}
				</table>

				<ul className="pagination">
	        <li><a href="#">1</a></li>
	        <li><a href="#">2</a></li>
	        <li><a href="#">3</a></li>
	        <li><a href="#">4</a></li>
	        <li><a href="#">5</a></li>
		    </ul>			
			</div>
		);
	}
}

window.RecognitionListing =  RecognitionListing;
