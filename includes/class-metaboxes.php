<?php
/**
 * LiquidChurch Functionality Metaboxes
 *
 * @since NEXT
 * @package LiquidChurch Functionality
 */

/**
 * LiquidChurch Functionality Metaboxes.
 *
 * @since NEXT
 */
class LCF_Metaboxes {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		// add_filter( 'gcs_taxonomies_series', array( $this, 'series_tax_override' ) );
		// add_filter( 'gcs_taxonomies_speaker', array( $this, 'speaker_tax_override' ) );
		// add_filter( 'gcs_taxonomies_tag', array( $this, 'tag_tax_override' ) );
		// add_filter( 'gcs_taxonomies_topic', array( $this, 'topic_tax_override' ) );
		// add_filter( 'gcst_taxonomies_position', array( $this, 'position_tax_override' ) );
		// add_filter( 'gcs_post_types_sermon', array( $this, 'sermon_override' ) );
		// add_filter( 'gcst_post_types_staff', array( $this, 'staff_override' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_metabox' ), 99 );
	}

	public function add_metabox( $metabox ) {

		$cmb = new_cmb2_box( array(
			'id'           => 'gc_addtl_resources_metabox',
			'title'        => __( 'Additional Resources', 'gc-sermons' ),
			'object_types' => array( gc_sermons()->sermons->post_type() ),
		) );

		$group_field_id = $cmb->add_field( array(
			'id'   => 'gc_addtl_resources',
			// 'name' => __( 'Additional Resources', 'lc-func' ),
			'type'        => 'group',
			'options'     => array(
				'group_title'   => __( 'Resource {#}', 'lc-func' ), // {#} gets replaced by row number
				'add_button'    => __( 'Add Another Resource', 'lc-func' ),
				'remove_button' => __( 'Remove Resource', 'lc-func' ),
				'sortable'      => true,
			),
			'after_group' => array( $this, 'enqueu_box_js' ),
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => __( 'Resource Name', 'cmb2' ),
			'desc' => __( 'e.g., "Audio for Faces of Grace Sermon")', 'lc-func' ),
			'id'   => 'name',
			'type' => 'text',
		) );
		$cmb->add_group_field( $group_field_id, array(
			'name' => __( 'Display Name', 'cmb2' ),
			'desc' => __( 'e.g., "Download Audio"', 'lc-func' ),
			'id'   => 'display_name',
			'type' => 'text',
		) );
		$cmb->add_group_field( $group_field_id, array(
			'name' => __( 'URL or File', 'cmb2' ),
			'desc' => __( 'e.g., "I want to link to an audio sermon OR I want to upload an audio sermon"', 'lc-func' ),
			'id'   => 'file',
			'type' => 'file',
		) );
		$cmb->add_group_field( $group_field_id, array(
			'name' => __( 'Type of Resource', 'cmb2' ),
			'desc' => __( 'e.g., Video / Audio / PDF / ZIP / Other', 'lc-func' ),
			'id'   => 'type',
			'type' => 'text',
		) );

	}

	public function enqueu_box_js( $args ) {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'lc-func-admin',
			LiquidChurch_Functionality::url( "assets/js/liquidchurch-functionality{$min}.js" ),
			array( 'cmb2-scripts' ),
			LiquidChurch_Functionality::VERSION,
			1
		);

		wp_localize_script( 'lc-func-admin', 'LiquidChurchAdmin', array( 'id' => $args['id'] ) );
	}

}
