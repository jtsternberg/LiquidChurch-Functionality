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
class LCF_Template_Loader {

	/**
	 * Array of arguments for template
	 *
	 * @var array
	 * @since  NEXT
	 */
	public $args = array();

	/**
	 * Template names array
	 *
	 * @var array
	 * @since  NEXT
	 */
	public $templates = array();

	/**
	 * Template name
	 *
	 * @var string
	 * @since  NEXT
	 */
	public $template = '';

	/**
	 * Render an HTML view with the given arguments and return the view's contents.
	 *
	 * @param string  $template The template file name, relative to the includes/templates/ folder - with or without .php extension
	 * @param string  $name     The name of the specialised template. If array, will take the place of the $args.
	 * @param array   $args     An array of arguments to extract as variables into the template
	 *
	 * @return void
	 */
	public function __construct( $template, $name = null, array $args = array() ) {
		if ( empty( $template ) ) {
			throw new Exception( 'Template variable required for '. __CLASS__ .'.' );
		}

		$templates = array();

		if ( is_array( $name ) ) {
			$this->args = $name;
		} else {
			$this->args = $args;

			$name = (string) $name;
			if ( '' !== $name ) {
				$this->templates[] = "{$template}-{$name}.php";
			}
		}

		$this->templates[] = "{$template}.php";
	}

	/**
	 * Loads the view and outputs it
	 *
	 * @since  NEXT
	 *
	 * @param  boolean $echo Whether to output or return the template
	 *
	 * @return string        Rendered template
	 */
	public function load( $echo = false ) {
		$template = $this->locate_template();

		// Filter args before outputting template.
		$this->args = apply_filters( "template_attributes_for_{$this->template}", $this->args );

		try {
			ob_start();
			// Do html
			include $template;
			// grab the data from the output buffer and add it to our $content variable
			$content = ob_get_clean();
		} catch ( Exception $e ) {
			wpdie( $e->getMessage() );
		}

		if ( ! $echo ) {
			return $content;
		}

		echo $content;
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH and then this plugin's /templates
	 * so that themes which inherit from a parent theme can just overload one file.
	 *
	 * @since  NEXT
	 *
	 * @return string The located template filename.
	 */
	protected function locate_template() {
		$located = '';
		foreach ( $this->templates as $template ) {
			if ( $located = $this->_locate( $template ) ) {
				$this->template = $template;
				return $located;
			}
		}

		return $located;
	}

	/**
	 * Searches for template in 1) child theme, 2) parent theme, 3) this plugin.
	 *
	 * @since  NEXT
	 *
	 * @param  string $template Template file to search for.
	 *
	 * @return void
	 */
	protected function _locate( $template ) {
		$theme_file_path = '/liquidchurch-functionality/' . $template;

		$located = '';
		if ( file_exists( STYLESHEETPATH . $theme_file_path ) ) {
			$located = STYLESHEETPATH . $theme_file_path;
		} elseif ( file_exists( TEMPLATEPATH . $theme_file_path ) ) {
			$located = TEMPLATEPATH . $theme_file_path;
		} elseif(
			( $look = LiquidChurch_Functionality::dir( 'templates/' . $template ) )
			&& file_exists( $look )
		) {
			$located = $look;
		}

		return $located;
	}

	public function get( $arg, $default = null ) {
		if ( isset( $this->args[ $arg ] ) ) {
			return $this->args[ $arg ];
		}

		return $default;
	}

	public function output( $arg, $esc_cb = '', $default = null ) {
		$val = $this->get( $arg, $default );

		echo $esc_cb ? $esc_cb( $val ) : $val;
	}

	public function __toString() {
		return $this->load();
	}

}
