<?php
/**
 * LiquidChurch Functionality Shortcodes Resources
 *
 * @since NEXT
 * @package LiquidChurch Functionality
 */

/**
 * LiquidChurch Functionality Shortcodes Resources.
 *
 * @since NEXT
 */
class LCF_Shortcodes_Resources {

	/**
	 * Instance of LCF_Shortcodes_Resources_Run
	 *
	 * @var LCF_Shortcodes_Resources_Run
	 */
	protected $run;

	/**
	 * Instance of LCF_Shortcodes_Resources_Admin
	 *
	 * @var LCF_Shortcodes_Resources_Admin
	 */
	protected $admin;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run = new LCF_Shortcodes_Resources_Run();
		$this->run->meta_id = $plugin->metaboxes->resources_meta_id;

		$this->admin = new LCF_Shortcodes_Resources_Admin( $this->run );

		$this->run->hooks();
		$this->admin->hooks();
	}

}
