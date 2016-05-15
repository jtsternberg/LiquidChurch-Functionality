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
		'resource_type'          => 'all', // File or URL
		'resource_type'          => array( 'files', 'urls', ), // File or URL
		// 'resource_file_type'     => 'all', // Only applies if 'type' is 'file',
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

		$post_id = $this->att( 'post_id', get_the_id() );

		if ( ! $post_id ) {
			return '<!-- no resources found -->';
		}

		// $types = $this->att( 'resource_type' );
		// $file_types = $this->att( 'resource_file_type' );

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

		return $this->view( 'sermon-resources-shortcode', $args );
	}

	protected function get_resources( $post_id ) {
		return get_post_meta( $post_id, $this->meta_id, 1 );
	}

	protected function list_items( $resources, $resource_display_name ) {
		$items = '';

		foreach ( $resources as $index => $resource ) {

			$resource['do_display_name'] = $resource_display_name;

			$type = isset( $resource['type'] ) ? $resource['type'] : '';
			$resource['item'] = $this->view( 'sermon-resources-shortcode-item', $type, $resource );

			$resource['index'] = $index;

			$items .= $this->view( 'sermon-resources-shortcode-li', $resource );
		}

		return $items;
	}

	public function view( $template, $name = null, array $args = array() ) {
		$view = new LCF_Template_Loader( $template, $name, $args );
		return $view->load();
	}

}

/*

$this->args: Array
(
    [name] => A picture of freedom
    [display_name] => View Picture
    [file_id] => 17232
    [file] => http://dev.generations/wp-content/uploads/2010/08/82171660_960x540.jpg
    [type] => image
    [index] => 0
)


$args: Array
(
    [resources] => Array
        (
            [0] => Array
                (
                    [name] => A picture of freedom
                    [display_name] => View Picture
                    [file_id] => 17232
                    [file] => http://dev.generations/wp-content/uploads/2010/08/82171660_960x540.jpg
                    [type] => image
                )

            [1] => Array
                (
                    [name] => Some audio
                    [display_name] => Download Audio
                    [file_id] => 6797
                    [file] => http://dev.generations/wp-content/uploads/2014/10/Troy-Oct-5th-2014.mp3
                    [type] => audio
                )

        )

    [items] => <li id="gc-sermon-resources-list-item-0" class="gc-sermon-resources-list-item gc-sermon-resources-list-item-image">
	<a href="http://dev.generations/wp-content/uploads/2010/08/82171660_960x540.jpg">
					A picture of freedom			</a>
</li>
<li id="gc-sermon-resources-list-item-1" class="gc-sermon-resources-list-item gc-sermon-resources-list-item-audio">
	<a href="http://dev.generations/wp-content/uploads/2014/10/Troy-Oct-5th-2014.mp3">
					Some audio			</a>
</li>

    [resource_type] => |~'files','urls'~|
    [resource_file_type] => |~'image','video','audio','pdf','zip','other'~|
    [resource_display_name] => true
    [resource_post_id] => 8861
    [resource_extra_classes] => class123
)
*/
