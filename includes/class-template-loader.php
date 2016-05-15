<?php
/**
 * LiquidChurch Functionality Template Loader
 *
 * @since NEXT
 * @package LiquidChurch Functionality
 */

/**
 * LiquidChurch Functionality Template Loader.
 *
 * @since NEXT
 */
class LCF_Template_Loader extends GCS_Template_Loader {

	/**
	 * HTML view template loader constructor.
	 *
	 * @param string  $template The template file name, relative to the includes/templates/ folder - with or without .php extension
	 * @param string  $name     The name of the specialised template. If array, will take the place of the $args.
	 * @param array   $args     An array of arguments to extract as variables into the template
	 *
	 * @return void
	 */
	public function __construct( $template, $name = null, array $args = array() ) {
		parent::__construct( $template, $name, $args );
		add_filter( "template_locations_for_{$this->template}", array( $this, 'add_to_template_stack' ) );
	}

	/**
	 * Add liquid-church template locations to the locations stack.
	 *
	 * @since NEXT
	 *
	 * @param array  $locations Modified array of locations.
	 */
	public function add_to_template_stack( $locations ) {
		$locations = array_reverse( $locations );

		$locations[] = LiquidChurch_Functionality::dir( 'templates/' );
		$locations[] = TEMPLATEPATH . '/liquidchurch-functionality/';
		$locations[] = STYLESHEETPATH . '/liquidchurch-functionality/';

		$locations = array_reverse( $locations );

		return $locations;
	}

	/**
	 * Get a rendered HTML view with the given arguments and return the view's contents.
	 *
	 * @since  NEXT
	 *
	 * @see GCS_Template_Loader::get_template()
	 */
	public static function get_template( $template, $name = null, array $args = array() ) {
		$view = new self( $template, $name, $args );
		return $view->load();
	}

	/**
	 * Render an HTML view with the given arguments and output the view's contents.
	 *
	 * @since  NEXT
	 *
	 * @see GCS_Template_Loader::output_template()
	 */
	public static function output_template( $template, $name = null, array $args = array() ) {
		$view = new self( $template, $name, $args );
		$view->load( 1 );
	}

}
