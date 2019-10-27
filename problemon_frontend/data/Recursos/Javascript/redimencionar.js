$(screen).resize(function(){
	redimencionar();
});
function redimencionar(){
	$(document).ready(function () {
		var windowHeight = $('main').height();
		var headerHeight = $('.header').height();
		var footerHeight = $('.footer2').height();
		var menuHeight = $('.container-fluid').height();
		var navbarHeight= $('.navbar-nav').height();
		var contentHeight = windowHeight -  menuHeight  ;
		$('#contenidopagina').css('height', contentHeight);
		$('#contenidopagina').css('padding-top', menuHeight-navbarHeight);
	});
}
window.onload=function(){
	redimencionar();
}
