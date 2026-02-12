<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Test\Admin;

use PHPUnit\Framework\TestCase;
use Netivo\Module\WooCommerce\ProductPreorder\Admin\Settings;

class SettingsTest extends TestCase {

	public function test_filter_woocommerce_get_sections_products() {
		$settings = new Settings();
		$sections = [
			'general' => 'General'
		];

		$result = $settings->filter_woocommerce_get_sections_products( $sections );

		$this->assertArrayHasKey( '_nt_manage_preorder', $result );
		$this->assertEquals( 'Ustawienia Preorderu', $result['_nt_manage_preorder'] );
	}

	public function test_filter_woocommerce_get_settings_for_section_wrong_id() {
		$settings          = new Settings();
		$original_settings = [ 'existing' => 'setting' ];

		$result = $settings->filter_woocommerce_get_settings_for_section( $original_settings, 'wrong_section' );

		$this->assertEquals( $original_settings, $result );
	}

	public function test_filter_woocommerce_get_settings_for_section_correct_id() {
		$settings          = new Settings();
		$original_settings = [ 'existing' => 'setting' ];

		$result = $settings->filter_woocommerce_get_settings_for_section( $original_settings, '_nt_manage_preorder' );

		$this->assertNotEquals( $original_settings, $result );
		$this->assertCount( 4, $result );
		$this->assertEquals( 'nt_preorder_settings', $result[0]['id'] );
		$this->assertEquals( 'nt_preorder_text', $result[1]['id'] );
		$this->assertEquals( 'nt_preorder_position', $result[2]['id'] );
	}
}
