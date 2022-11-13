<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://github.com/1995kruti
 * @since      1.0.0
 *
 * @package    Event_Listing
 * @subpackage Event_Listing/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Event_Listing
 * @subpackage Event_Listing/admin
 * @author     Kruti Modi <krutimodi1112@gmail.com>
 */
class Event_Listing_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Event_Listing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Event_Listing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/event-listing-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Event_Listing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Event_Listing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/event-listing-admin.js', array( 'jquery' ), $this->version, false );

	}

	/*
	 * Register Events custom post types
	 */
	public function register_events_custom_post_type() {
		
		$supports = array(
			'title', 'thumbnail'
		);
		$labels = array(
			'name' => _x('Events', 'plural'),
			'singular_name' => _x('events', 'singular'),
			'menu_name' => _x('Events', 'admin menu'),
			'name_admin_bar' => _x('Events', 'admin bar'),
			'add_new' => _x('Add New Event', 'add new'),
			'add_new_item' => __('Add New Event'),
			'new_item' => __('New Event'),
			'edit_item' => __('Edit Event'),
			'view_item' => __('View Event'),
			'all_items' => __('All Events'),
			'not_found' => __('No Event found.'),
			'register_meta_box_cb' => 'events_metabox',
		);
		$args = array(
			'supports' => $supports,
			'labels' => $labels,
			'hierarchical' => false,
			'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
			'publicly_queryable' => false,  // you should be able to query it
			'show_ui' => true,  // you should be able to edit it in wp-admin
			'exclude_from_search' => true,  // you should exclude it from search results
			'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
			'has_archive' => false,  // it shouldn't have archive page
			'rewrite' => false,  // it shouldn't have rewrite rules
		);
		register_post_type('event_listing', $args);

	}

	/**
	 * Add Event Type taxonomies
	 *
	 * Additional custom taxonomies can be defined here
	 * https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	public function add_event_type_taxonomy() {
	  
	  register_taxonomy('event_type', 'event_listing', array(
	    
	    'hierarchical' => true,
	    'labels' => array(
	      'name' => _x( 'Event Type', 'taxonomy general name' ),
	      'singular_name' => _x( 'Event Type', 'taxonomy singular name' ),
	      'search_items' =>  __( 'Search Event Type' ),
	      'all_items' => __( 'All Event Types' ),
	      'parent_item' => __( 'Parent Event Type' ),
	      'parent_item_colon' => __( 'Parent Event Type' ),
	      'edit_item' => __( 'Edit Event Type' ),
	      'update_item' => __( 'Update Event Type' ),
	      'add_new_item' => __( 'Add New Event Type' ),
	      'new_item_name' => __( 'New Event Type Name' ),
	      'menu_name' => __( 'Event Type' ),
	    ),	    
	    'rewrite' => array(
	      'slug' => 'event-type', 
	      'with_front' => false, 
	      'hierarchical' => true 
	    ),
	  ));
	}

	/**
	 * Create action for the MEta Field For CPT
	 *
	 * @since    1.0.0
	 */
	public function events_metabox() {
	    add_meta_box(
	        'event-listing-setting',
	        __( 'Event Listing Setting', 'event-listing' ),
	        array($this,'event_listing_settings'),
	        'event_listing'
	    );
	}

	/**
	 * Events edit settings page 
	 *
	 * @since    1.0.0
	 */
	public static function event_listing_settings() {
		include dirname(__FILE__).'/partials/event-listing-admin-display.php';
	}

	/**
	 * Update events admin settings
	 * 
	 * @since    1.0.0
	 */
	public function eventlisting_save_settings($event_id,$event_listing) {
		if ( $event_listing->post_type == 'event_listing' ) {

			$status = 'false';
			if(isset( $_POST['eventlisting_cpt_nonce'] ) &&
				wp_verify_nonce( $_POST['eventlisting_cpt_nonce'], 'eventlisting_cpt_nonce')	
			):
				$aps_object  = new Event_Listing();
				$aps_options = $aps_object->event_get_options();  
				
				$aps_options['event_date'] = stripslashes($_POST['event_date']);
				$aps_options['event_location'] = stripslashes($_POST['event_location']);
				$aps_options['event_fees'] = (int)sanitize_text_field($_POST['event_fees']);
				

				// $response = update_post_meta('anypostslider_options', $aps_options);
				foreach ( $aps_options as $aps_options_key => $aps_options_value ) {
        				$response = update_post_meta( $event_id, $aps_options_key, $aps_options_value );
    			}
				if($response):
					$status = 'true';
				endif;
			else:
			endif;
		}

	}

}
  