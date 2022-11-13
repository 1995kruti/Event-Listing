(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 $(document).on("ready",function(){
	 	$(document).find(".event_type_btn").on("click",function(){
		 	var current_eventtype = $(this).attr('data-event_type');
		 	var current_page = $(".current_event_page").val();
		 	var text_domain = eventlist_ajax_object.text_domain;
		 	jQuery.ajax({
			    type: "POST",
			    url: eventlist_ajax_object.ajaxurl,
			    data: {
			    	action : 'load_events',
			    	dataType : 'json',
		 		event_type : current_eventtype,
		 		current_page : current_page,
		 		domain : text_domain
			    },
			    success: function(data){
			    	console.log(data);
				$(document).find(".event_listing_block").append(data.html);
			    }
			});

		 });
	 });
	 

})( jQuery );
