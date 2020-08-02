

		function myFunction(x) {
		  x.classList.toggle("change");
		}

		$(document).ready(function(){
			$(".mobile-header").click(function(){
				$(".header-menu").toggleClass("show");
			});
		});
