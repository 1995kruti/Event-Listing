<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://github.com/1995kruti
 * @since      1.0.0
 *
 * @package    Event_Listing
 * @subpackage Event_Listing/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Event_Listing
 * @subpackage Event_Listing/includes
 * @author     Kruti Modi <krutimodi1112@gmail.com>
 */
class Event_Listing {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Event_Listing_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EVENT_LISTING_VERSION' ) ) {
			$this->version = EVENT_LISTING_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'event-listing';
		$this->aps_post_types[] = $this->events_get_all_post_type();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Event_Listing_Loader. Orchestrates the hooks of the plugin.
	 * - Event_Listing_i18n. Defines internationalization functionality.
	 * - Event_Listing_Admin. Defines all hooks for the admin area.
	 * - Event_Listing_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-listing-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-listing-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-event-listing-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-event-listing-public.php';

		$this->loader = new Event_Listing_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Event_Listing_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Event_Listing_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Event_Listing_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin,'register_events_custom_post_type');
		$this->loader->add_action( 'init', $plugin_admin,'add_event_type_taxonomy');		
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin,'events_metabox');
		$this->loader->add_action( 'save_post',$plugin_admin,'eventlisting_save_settings',10,2);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Event_Listing_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_load_events', $plugin_public, 'load_events' , 10);
		$this->loader->add_action( 'wp_ajax_nopriv_load_events', $plugin_public, 'load_events' , 10);
		$this->loader->add_action( 'init', $plugin_public, 'register_event_listing_shortcode');
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Event_Listing_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	/**
	 * Retrive the settings of the individual event
	 * 
	 * @since     1.0.0
	 * @return    array    The settings of the plugin.
	 */
	public function event_get_options() {
		global $post;
		$options = [];
		$options['event_date'] = get_post_meta($post->ID,'event_date',true);
		$options['event_location'] = get_post_meta($post->ID,'event_location',true);
		$options['event_fees'] = get_post_meta($post->ID,'event_fees',true);
		 return $options;
	}

	/**
	 * Get all post types of wordpress
	 * 
	 * @since     1.0.0
	 * @return    array   The list of post types.
	 */
	
	public function events_get_all_post_type() {
		$all_post_types	   = get_post_types(array('public'   => true,'_builtin' => false),'names','and'); // set arguments to list out the post types
		
		$all_post_types['posts'] = 'post';
		return $all_post_types;
	}

	public function load_events(){
		
		$event_type = $_POST['event_type'];
		$paged = $_POST['current_page'];
		$text_domain = $_POST['text_domain'];

		$get_posts_data = new WP_Query(
		    array(
		        'post_type'      => 'event_listing',
		        'posts_per_page' => 5,
		        'order'          => 'DESC',
		        'post_status'    => array('publish'),
		        'paged'          => $paged,
		        'tax_query' => array(
				    array(
				        'taxonomy' => 'event_type',
				        'terms' => $event_type,
				        'field' => 'slug',
				        'operator' => 'IN'
				    )
				),

		    )
		);
		$html = "";
		if($get_posts_data->have_posts()):
			// $html = "<div class='event_listing_block'>";
			while ($get_posts_data->have_posts()):
				$get_posts_data->the_post();
				$html .= "<div class='item'><div class='aps_main'>";
				if(has_post_thumbnail($post_item_val->ID)): 
                $html .= _e(get_the_post_thumbnail( $post_item_val->ID, 'large' )); 
            	else:
				$html .= "<img src=".esc_url(plugins_url($text_domain).'/public/images/place_holder.jpg')." class='event-placeholder-img' alt='event-placeholder' style='height: auto; width:300px;'/>";
				endif;
				$html .= "<div class='aps_desc'><a href=".esc_url(get_the_permalink($post_item_val->ID)).">";
				$html .= "<h3>".esc_attr_e( $post_item_val->post_title)."</h3>";
				$html .= "</a></div>";
				$html .= "</div></div>";

			endwhile;
			wp_reset_query();
		else:
			$html .= "<div><h3>No Events Found.</h3></div>";			
		endif;
		
		echo json_encode(array("html" => $html));
		wp_die();
	}

}
