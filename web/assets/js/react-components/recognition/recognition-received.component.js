class RecognitionListing extends React.Component {
	render(){

		let recogList = this.props.receivedRecognition.map( (item) => {
			return (
				<tr>
					<td>{item.id}</td>
					<td>{item.responseType}</td>
					<td>{item.senderName}</td>
					<td>{item.recognitionId}</td>
				</tr>
			);
		});

		return(

			<div className="recognition-list">
				<h2>Received Recognition</h2>
				<table className="table table-striped table-condensed">
					<tr>
						<th>ID</th>
						<th>Response Type</th>
						<th>Sender</th>
						<th>Recognition ID</th>
					</tr>
					{recogList}
				</table>				
			</div>
		);
	}
}

window.RecognitionListing =  RecognitionListing;
