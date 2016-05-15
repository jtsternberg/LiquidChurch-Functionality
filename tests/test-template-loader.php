<?php

class LCF_Template_Loader_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'LCF_Template_Loader') );
	}

	function test_class_access() {
		$this->assertTrue( lc_func()->template-loader instanceof LCF_Template_Loader );
	}
}
