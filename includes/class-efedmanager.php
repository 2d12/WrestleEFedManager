<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.2d12.com
 * @since      1.0.0
 *
 * @package    efedmanager
 * @subpackage efedmanager/includes
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
 * @package    efedmanager
 * @subpackage efedmanager/includes
 * @author     E. Steev Ramsdell <steev@2d12.com>
 */
class efedmanager {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      efedmanager_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	
	/* 
	 * The federation post type.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      efedmanager_Federation   $federation_type    Used just for constructor.
	 */
	protected $federation_type;
	
	protected $worker_type;
	protected $weightclass_type;
	protected $alignment_type;
	protected $gender_type;
	protected $title_type;
	protected $event_type;
	protected $match_type;
	protected $roster_type;
	protected $team_type;
	
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
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'efedmanager';

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
	 * - efedmanager_Loader. Orchestrates the hooks of the plugin.
	 * - efedmanager_i18n. Defines internationalization functionality.
	 * - efedmanager_Admin. Defines all hooks for the admin area.
	 * - efedmanager_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-efedmanager-loader.php';
		$this->loader = new efedmanager_Loader();		
	
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-efedmanager-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-efedmanager-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-efedmanager-public.php';
		
		/**
		 * The classes responsible for defining the various post types.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/federations/class-efedmanager-federation.php';		
		$this->federation_type = new efedmanager_Federation();
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/workers/class-efedmanager-worker.php';		
		$this->worker_type = new efedmanager_Worker();
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/teams/class-efedmanager-teams.php';		
		$this->team_type = new efedmanager_Team();

		require_once plugin_dir_path( dirname( 
		__FILE__ ) ) . 'includes/matches/class-efedmanager-match.php';		
		$this->match_type = new efedmanager_Match();
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/alignments/class-efedmanager-alignment.php';		
		$this->alignment_type = new efedmanager_Alignment();
				
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/genders/class-efedmanager-gender.php';		
		$this->gender_type = new efedmanager_Gender();
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/weightclasses/class-efedmanager-weightclass.php';		
		$this->weightclass_type = new efedmanager_WeightClass();
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/rosters/class-efedmanager-roster.php';		
		$this->roster_type = new efedmanager_Roster();
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/championships/class-efedmanager-championship.php';		
		$this->title_type = new efedmanager_Championship();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the efedmanager_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new efedmanager_i18n();

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

		$plugin_admin = new efedmanager_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new efedmanager_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    efedmanager_Loader    Orchestrates the hooks of the plugin.
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

	
}
