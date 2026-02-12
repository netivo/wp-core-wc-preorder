<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

use Netivo\Module\WooCommerce\ProductPreorder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

class Settings {
	protected string $options_slug = '_nt_manage_preorder';

	public function __construct() {
		add_filter( 'woocommerce_get_sections_products', [ $this, 'filter_woocommerce_get_sections_products' ] );

		add_action( 'woocommerce_get_settings_products', [
			$this,
			'filter_woocommerce_get_settings_for_section'
		] );
	}

	public function filter_woocommerce_get_sections_products( $sections ) {
		$sections[ $this->options_slug ] = __( 'Ustawienia Preorderu', 'netivo' );

		return $sections;
	}

	public function filter_woocommerce_get_settings_for_section( $settings, $section_id ) {
		if ( $section_id !== $this->options_slug ) {
			return $settings;
		}

		$settings = array(
			array(
				'title'    => __( 'Ustawienia Preorderu', 'netivo' ),
				'id'       => 'nt_preorder_text',
				'type'     => 'text',
				'default'  => __( '[PRZEDSPRZEDAŻ]', 'netivo' ),
				'desc'     => __( 'Tekst wyświetlany przy tytule produktu', 'netivo' ),
				'desc_tip' => true
			),
			array(
				'title'    => __( 'Pozycja tekstu w tytule produktu', 'netivo' ),
				'id'       => 'nt_preorder_position',
				'type'     => 'select',
				'default'  => 'before',
				'options'  => array(
					'before' => __( 'Przed tytułem', 'netivo' ),
					'after'  => __( 'Za tytułem', 'netivo' )
				),
				'desc'     => __( 'Wybierz, czy tekst ma być wyświetlany przed czy za tytułem produktu.', 'netivo' ),
				'desc_tip' => true
			)
		);

		return $settings;
	}
}