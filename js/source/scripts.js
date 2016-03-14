jQuery(document).ready(function() {
	var international = jQuery("#international");
	var us = jQuery("#location-pulldown");

	international.change(function() {
		if (international.val()) {
			us.unbind();
			us.val('');
			us.parent().submit();
		}
	});
	
	us.change(function() {
		if (us.val()) {
			international.unbind();
			international.val('');
			us.parent().submit();
		}
	});
});
