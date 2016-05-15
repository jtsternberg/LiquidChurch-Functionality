<?php
/**
 * Plugin Name: LiquidChurch Functionality
 * Plugin URI:  http://www.liquidchurch.com/
 * Description: Adds custom functionality for use on http://www.liquidchurch.com
 * Version:     0.0.0
 * Author:      Justin Sternberg
 * Author URI:  http://dsgnwrks.pro
 * Donate link: http://www.liquidchurch.com/
 * License:     GPLv2
 * Text Domain: lc-func
 * Domain Path: /languages
 *
 * @link http://www.liquidchurch.com/
 *
 * @package LiquidChurch Functionality
 * @version 0.0.0
 */

/**
 * Copyright (c) 2016 Justin Sternberg (email : justin@dsgnwrks.pro)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Autoloads files with classes when needed
 *
 * @since  NEXT
 * @param  string $class_name Name of the class being requested.
 * @return void
 */
function lc_func_autoload_classes( $class_name ) {
	if ( 0 !== strpos( $class_name, 'LCF_' ) ) {
		return;
	}

	$filename = strtolower( str_replace(
		'_', '-',
		substr( $class_name, strlen( 'LCF_' ) )
	) );

	LiquidChurch_Functionality::include_file( 'includes/class-' . $filename );
}
spl_autoload_register( 'lc_func_autoload_classes' );

/**
 * Main initiation class
 *
 * @since  NEXT
 */
final class LiquidChurch_Functionality {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  NEXT
	 */
	const VERSION = '0.0.0';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var LiquidChurch_Functionality
	 * @since  NEXT
	 */
	protected static $single_instance = null;

	/**
	 * Instance of LCF_Metaboxes
	 *
	 * @since NEXT
	 * @var LCF_Metaboxes
	 */
	protected $metaboxes;

	/**
	 * Instance of LCF_Shortcodes
	 *
	 * @since NEXT
	 * @var LCF_Shortcodes
	 */
	protected $shortcodes;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  NEXT
	 * @return LiquidChurch_Functionality A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  NEXT
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function plugin_classes() {
		// Only create the full metabox object if in the admin.
		if ( is_admin() ) {
			$this->metaboxes = new LCF_Metaboxes( $this );
			$this->metaboxes->hooks();
		} else {
			$this->metaboxes = (object) array();
		}

		// Set these properties either way.
		$this->metaboxes->resources_box_id  = 'gc_addtl_resources_metabox';
		$this->metaboxes->resources_meta_id = 'gc_addtl_resources';

		$this->shortcodes = new LCF_Shortcodes( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _activate() {
		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _deactivate() {}

	/**
	 * Init hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'lc-func', false, dirname( $this->basename ) . '/languages/' );
			$this->plugin_classes();
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  NEXT
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice.
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin.
			add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function deactivate_me() {
		deactivate_plugins( $this->basename );
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  NEXT
	 * @return boolean True if requirements are met.
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('').
		// We have met all requirements.
		return class_exists( 'GC_Sermons_Plugin' );
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function requirements_not_met_notice() {
		// Output our error.
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'LiquidChurch Functionality is missing the <a href="https://github.com/jtsternberg/GC-Sermons">GC Sermons plugin</a> and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'lc-func' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  NEXT
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'metaboxes':
			case 'shortcodes':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  NEXT
	 * @param  string $filename Name of the file to be included.
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the LiquidChurch_Functionality object and return it.
 * Wrapper for LiquidChurch_Functionality::get_instance()
 *
 * @since  NEXT
 * @return LiquidChurch_Functionality  Singleton instance of plugin class.
 */
function lc_func() {
	return LiquidChurch_Functionality::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( lc_func(), 'hooks' ) );

register_activation_hook( __FILE__, array( lc_func(), '_activate' ) );
register_deactivation_hook( __FILE__, array( lc_func(), '_deactivate' ) );
