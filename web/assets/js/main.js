(function(){

	$(document).ready(function(){
		
		$('.open-sidebar-btn').on('click', function(){			
			openNav();
			$(this).hide();
		});

		$('.close-sidebar-btn').on('click', function(){					
			closeNav();
			$('.open-sidebar-btn').show();
		})


	});

	/* Set the width of the side navigation to 250px and the left margin of the page content to 250px */
	function openNav() {
		$('#mySidenav').css({'width':'250px', 'border-right':'5px solid #34495e'});
		$('.main-wrapper').css('margin-left', '250px');
	}

	/* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
	function closeNav() {
		$('#mySidenav').css({'width':'0', 'border-right':'0px'});
		$('.main-wrapper').css('margin-left', '0');
	}

})();