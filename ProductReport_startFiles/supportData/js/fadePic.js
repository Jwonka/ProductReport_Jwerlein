$(document).ready(function() {

	//console.log('Document ready');

	$('#fadeImage img').fadeTo("slow", 0.5);
	
	// on hover
	$('#fadeImage img').hover(
		function() {
		//console.log('Hover in');
		// fade the image to full opacity
		$(this).fadeTo("slow", 1);
	
		}, 
		function() {
		//console.log('Hover out');
		// handle mouseout - fade back
		$(this).fadeTo("slow", 0.5);
		}
	);
});
