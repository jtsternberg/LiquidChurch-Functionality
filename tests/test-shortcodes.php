<?php

class LCF_Shortcodes_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'LCF_Shortcodes') );
	}

	function test_class_access() {
		$this->assertTrue( lc_func()->shortcodes instanceof LCF_Shortcodes );
	}
}
