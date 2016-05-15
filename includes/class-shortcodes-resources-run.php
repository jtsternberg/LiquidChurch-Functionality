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
	public $meta_id = '';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'resource_type'          => 'all', // File or URL
		'resource_type'          => array( 'files', 'urls', ), // File or URL
		// 'resource_file_type'     => 'all', // Only applies if 'type' is 'file',
		'resource_file_type'     => array( 'image', 'video', 'audio', 'pdf', 'zip', 'other', ), // Only applies if 'type' is 'file',
		'resource_display_name'  => false, // Uses Resource Name by default
		'resource_post_id'       => 0, // Uses `get_the_id()` by default
		'resource_extra_classes' => '', // For cusotm styling
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {

		$post_id = $this->att( 'post_id', get_the_id() );

		if ( ! $post_id ) {
			return '<!-- no resources found -->';
		}

		$resources = $this->get_resources( $post_id );

		if ( empty( $resources ) || ! is_array( $resources ) ) {
			return '<!-- no resources found -->';
		}

		// @todo implement template loader

		$output = '';
		$output .= '<div class="gc-sermon-resources-wrap ' . esc_attr( $this->att( 'resource_extra_classes' ) ) . '">';
		$output .= '<xmp>$this->shortcode_object->atts: '. print_r( $this->shortcode_object->atts, true ) .'</xmp>';

		$output .= '<ul class="gc-sermon-resources-list">';

		foreach ( $resources as $index => $resource ) {
			$output .= $this->list_item( $index, $resources );
		}

		$output .= '</ul>';

		$output .= '</div>';

		return $output;
	}

	protected function get_resources( $post_id ) {
		return get_post_meta( $post_id, $this->meta_id, 1 );
	}

	protected function list_item( $index, $resources ) {
		$item = '';

		return $item;
	}

}
