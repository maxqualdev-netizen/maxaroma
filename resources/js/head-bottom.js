$(function () {
        $(".select").selectbox({
            speed: 450
        });
    });
$(document).ready(function(){

	//Accordion Nav
	$('.mainNav').navAccordion({
		expandButtonText: '+',  //Text inside of buttons can be HTML
		collapseButtonText: '-'
	}, 
	function(){
		console.log('Callback')
	});
	
});
