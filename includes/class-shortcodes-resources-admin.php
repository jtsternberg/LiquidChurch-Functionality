<?php
/**
 * LiquidChurch Functionality Shortcodes Resources Admin
 *
 * @since NEXT
 * @package LiquidChurch Functionality
 */

/**
 * LiquidChurch Functionality Shortcodes Resources Admin.
 *
 * @since NEXT
 */
class LCF_Shortcodes_Resources_Admin extends WDS_Shortcode_Admin {
	/**
	 * Shortcode Run object
	 *
	 * @var   LCF_Shortcodes_Resources_Run
	 * @since 0.1.0
	 */
	protected $run;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $run LCF_Shortcodes_Resources_Run object.
	 * @return void
	 */
	public function __construct( LCF_Shortcodes_Resources_Run $run ) {
		$this->run = $run;

		parent::__construct(
			$this->run->shortcode,
			LiquidChurch_Functionality::VERSION,
			$this->run->atts_defaults
		);
	}

	/**
	 * Sets up the button
	 *
	 * @return array
	 */
	function js_button_data() {
		return array(
			'qt_button_text' => __( 'Sermon Resources', 'lc-func' ),
			'button_tooltip' => __( 'Sermon Resources', 'lc-func' ),
			'icon'           => 'dashicons-media-interactive',
			// 'mceView'        => true, // The future
		);
	}

	/**
	 * Adds fields to the button modal using CMB2
	 *
	 * @param $fields
	 * @param $button_data
	 *
	 * @return array
	 */
	function fields( $fields, $button_data ) {
		$fields[] = array(
			'name'    => __( 'Resource Type', 'lc-func' ),
			'desc'    => __( 'Select the type of resource to display.', 'lc-func' ),
			'id'      => 'resource_type',
			'type'    => 'multicheck_inline',
			'default' => $this->atts_defaults['resource_type'],
			'options' => array(
				'files' => __( 'Files', 'lc-func' ),
				'urls'  => __( 'URLs', 'lc-func' ),
			),
		);

		$fields[] = array(
			'name'    => __( 'File Type', 'lc-func' ),
			'desc'    => __( 'Only applies if checking "Files" as the Resource Type.', 'lc-func' ),
			'id'      => 'resource_file_type',
			'type'    => 'multicheck_inline',
			'default' => $this->atts_defaults['resource_file_type'],
			'options' => array(
				'image' => __( 'Image', 'lc-func' ),
				'video' => __( 'Video', 'lc-func' ),
				'audio' => __( 'Audio', 'lc-func' ),
				'pdf'   => __( 'PDF', 'lc-func' ),
				'zip'   => __( 'Zip', 'lc-func' ),
				'other' => __( 'Other', 'lc-func' ),
			),
		);

		$fields[] = array(
			'name' => __( 'Use the Display Name', 'lc-func' ),
			'desc' => __( 'By default, the Resource Name will be used.', 'lc-func' ),
			'id'   => 'resource_display_name',
			'type' => 'checkbox',
		);

		// $fields[] = array(
		// 	'name' => __( 'Sermon ID', 'lc-func' ),
		// 	'desc' => __( 'By default, will use the current ID.', 'lc-func' ),
		// 	'id'   => 'resource_post_id',
		// 	'type' => 'text_small',
		// );
		$fields[] = array(
			'name'            => __( 'Sermon ID', 'lc-func' ),
			'desc'            => __( 'Blank, "recent", or "0" will play the most recent video. Otherwise enter a post ID. click the magnifying glass to search for a Sermon post.', 'lc-func' ),
			'id'              => 'resource_post_id',
			'type'            => 'post_search_text',
			'post_type'       => gc_sermons()->sermons->post_type(),
			'select_type'     => 'radio',
			'select_behavior' => 'replace',
		);

		$fields[] = array(
			'name'    => __( 'Extra CSS Classes', 'lc-func' ),
			'desc'    => __( 'Enter classes separated by spaces (e.g. "class1 class2")', 'lc-func' ),
			'type'    => 'text',
			'id'      => 'resource_extra_classes',
		);

		return $fields;
	}
}