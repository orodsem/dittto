export function goToNextPage(page, currentPage){
	return {
		type: 'PAGINATION_NEXT_PAGE',
		payload: {
			page,
			currentPage
		}
	};
}

export function setPage(page){
	return {
		type: 'PAGINATION_SET_PAGE',
		payload: {
			page
		}
	};
}