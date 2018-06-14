$(document).ready(function() {
	
	$('#category').niceSelect();

});


var titles = document.querySelectorAll('.support__list-title');



function openQ(event) {
	event.target.nextElementSibling.classList.toggle('support__list-text--active');
	event.target.children[0].children[1].classList.toggle('support__plus-2--active');
}






for (var i = 0; i < titles.length; i++) {
	titles[i].addEventListener('click', openQ);
}