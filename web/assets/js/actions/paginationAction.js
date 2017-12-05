export function goToNextPage(page, currentPage){
	return {
		type: 'PAGINATION_NEXT_PAGE',
		payload: {
			page,
			currentPage
		}
	};
}