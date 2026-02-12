<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

use Netivo\Module\WooCommerce\ProductPreorder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

class Settings {
	protected string $options_slug = 'netivo_manage_preorder';

	public function __construct() {
		add_filter( 'woocommerce_settings_tabs_array', [ $this, 'filter_woocommerce_settings_tabs_array' ] );

		add_action( 'woocommerce_sections_preorder-settings', [
			$this,
			'action_woocommerce_sections_preorder_settings'
		] );
	}

	public function filter_woocommerce_settings_tabs_array( $settings_tabs ) {
		$settings_tabs['preorder-settings'] = __( 'Ustawienia Preorderu', 'netivo' );

		return $settings_tabs;
	}

	public function action_woocommerce_sections_preorder_settings() {
		global $current_section;

		$tab_id = 'preorder-settings';

		$sections = array(
			'' => __( 'Ogólne', 'netivo' ),
			'section-1' => __( 'sekcja 1', 'netivo' ),
		);

		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $array_keys as $id => $label ) {
			echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $tab_id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}

		echo '</ul><br class="clear" />';
	}
}