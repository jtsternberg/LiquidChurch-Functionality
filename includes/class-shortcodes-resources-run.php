<?php
/**
 * LiquidChurch Functionality Shortcodes Resources Run
 *
 * @since NEXT
 * @package LiquidChurch Functionality
 */

/**
 * LiquidChurch Functionality Shortcodes Resources Run.
 *
 * @since NEXT
 */
class LCF_Shortcodes_Resources_Run extends WDS_Shortcodes {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'sermon_resources';

	/**
	 * Additional Resources meta id.
	 *
	 * @see  LCF_Metaboxes::$resources_meta_id
	 * @var   string
	 * @since NEXT
	 */
	protected $meta_id = '';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'resource_type'          => array( 'files', 'urls', ), // File or URL
		'resource_file_type'     => array( 'image', 'video', 'audio', 'pdf', 'zip', 'other', ), // Only applies if 'type' is 'file',
		'resource_display_name'  => false, // Uses Resource Name by default
		'resource_post_id'       => 0, // Uses `get_the_id()` by default
		'resource_extra_classes' => '', // For cusotm styling
	);

	/**
	 * Constructor replacement. (Can't use __construct as it does not match
	 * the abstract WDS_Shortcodes constructor signature)
	 *
	 * @since  NEXT
	 *
	 * @param  string $meta_id Resource meta id
	 *
	 * @return void
	 */
	public function init( $meta_id ) {
		$this->meta_id = $meta_id;
	}

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		$output = $this->_shortcode();

		return apply_filters( 'lc_sermon_resources_shortcode_output', $output, $this );
	}

	protected function _shortcode() {

		$post_id = $this->att( 'resource_post_id', get_the_id() );
		if ( 'this' === $post_id ) {
			$post_id = get_the_id();
		}

		if ( ! $post_id ) {
			return '<!-- no resources found -->';
		}

		$resources = $this->get_resources( $post_id );

		if ( empty( $resources ) || ! is_array( $resources ) ) {
			return '<!-- no resources found -->';
		}

		$args = array(
			'resources' => $resources,
			'items'     => $this->list_items( $resources, $this->att( 'resource_display_name' ) ),
		);

		// Get parsed attribute values
		foreach ( $this->shortcode_object->atts as $key => $value ) {
			$args[ $key ] = $this->att( $key );
		}

		return LCF_Template_Loader::get_template( 'sermon-resources-shortcode', $args );
	}

	protected function get_resources( $post_id ) {
		$resources = get_post_meta( $post_id, $this->meta_id, 1 );

		$allowed_types = $this->att( 'resource_type' );

		$diff_types      = array_diff( $this->atts_defaults['resource_type'], $allowed_types );
		$diff_file_types = array_diff( $this->atts_defaults['resource_file_type'], $this->att( 'resource_file_type' ) );

		if ( empty( $diff_types ) && empty( $diff_file_types ) ) {
			// Ok, send it all back.
			return $resources;
		}

		$obj = $this->shortcode_object;
		$obj->wants_urls = in_array( 'urls', $allowed_types );
		$obj->wants_files = in_array( 'files', $allowed_types );

		if ( ! $obj->wants_files && ! $obj->wants_urls ) {
			// Ok.. you asked for it, send nothing back.
			return array();
		}

		if ( ! $obj->wants_files ) {

			// send only urls
			// we can ignore file types.
			return array_filter( $resources, array( $this, 'is_url_resource' ) );
		}

		// filter rest
		return array_filter( $resources, array( $this, 'filter_resources_by_types' ) );
	}

	public function filter_resources_by_types( $resource ) {

		// If this is a url resource
		if ( $this->is_url_resource( $resource ) ) {
			// Then check if urls are allowed
			return $this->shortcode_object->wants_urls;
		}

		// Ok, we have a file type, but is it the requested file type?
		return in_array( $resource['type'], (array) $this->att( 'resource_file_type' ) );
	}

	public function is_url_resource( $resource ) {
		$is_url = ! isset( $resource['type'] ) || ! trim( $resource['type'] );
		return $is_url;
	}

	protected function list_items( $resources, $resource_display_name ) {
		$items = '';

		foreach ( $resources as $index => $resource ) {

			$resource['do_display_name'] = $resource_display_name;

			$type = isset( $resource['type'] ) ? $resource['type'] : '';
			if ( 'video' === $type && isset( $resource['file'] ) ) {
				$resource['embed_args'] = array( 'url' => $resource['file'] );
			}
			$resource['item'] = LCF_Template_Loader::get_template( 'sermon-resources-shortcode-item', $type, $resource );

			$resource['index'] = $index;

			$items .= LCF_Template_Loader::get_template( 'sermon-resources-shortcode-li', $resource );
		}

		return $items;
	}

}
