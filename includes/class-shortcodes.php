<?php
/**
 * LiquidChurch Functionality Shortcodes
 *
 * @since NEXT
 * @package LiquidChurch Functionality
 */

/**
 * LiquidChurch Functionality Shortcodes.
 *
 * @since NEXT
 */
class LCF_Shortcodes {

	/**
	 * Instance of LCF_Shortcodes_Resources
	 *
	 * @var LCF_Shortcodes_Resources
	 */
	protected $resources;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->resources = new LCF_Shortcodes_Resources( $plugin );
	}

	/**
	 * Magic getter for our object. Allows getting but not setting.
	 *
	 * @param string $field
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		return $this->{$field};
	}

}
