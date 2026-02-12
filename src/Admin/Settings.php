<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

use Netivo\Module\WooCommerce\ProductPreorder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

/**
 * Handles the integration of custom settings for WooCommerce related to preorders.
 */
class Settings {
	protected string $options_slug = '_nt_manage_preorder';

	public function __construct() {
		add_filter( 'woocommerce_get_sections_products', [ $this, 'filter_woocommerce_get_sections_products' ], 10, 1 );

		add_action( 'woocommerce_get_settings_products', [
			$this,
			'filter_woocommerce_get_settings_for_section'
		], 10, 2 );
	}

	/**
	 * Filters the WooCommerce product sections to include a custom section.
	 *
	 * @param array $sections An array of existing WooCommerce product sections.
	 *
	 * @return array Modified array of WooCommerce product sections.
	 */
	public function filter_woocommerce_get_sections_products( array $sections ): array {
		$sections[ $this->options_slug ] = __( 'Ustawienia Preorderu', 'netivo' );

		return $sections;
	}

	/**
	 * Filters the WooCommerce settings for a specified section.
	 *
	 * This method customizes the settings array for a specific WooCommerce settings section,
	 * adding configuration options for displaying pre-order information.
	 *
	 * @param array $settings The original settings for the section.
	 * @param string $section_id The ID of the section being filtered.
	 *
	 * @return array The modified settings array for the section.
	 */
	public function filter_woocommerce_get_settings_for_section( array $settings, string $section_id ): array {
		if ( $section_id !== $this->options_slug ) {
			return $settings;
		}

		$settings = [];

		$settings[] = array(
			'name' => __( 'Informacja o preorderze', 'netivo' ),
			'type' => 'title',
			'desc' => __( 'Opcje pozwalające na konfiguracje wyświetlania informacji o preorderze', 'netivo' ),
			'id'   => 'nt_preorder_settings'
		);

		$settings[] = array(
			'title'    => __( 'Ustawienia Preorderu', 'netivo' ),
			'id'       => 'nt_preorder_text',
			'type'     => 'text',
			'default'  => __( '[PRZEDSPRZEDAŻ]', 'netivo' ),
			'desc'     => __( 'Tekst wyświetlany przy tytule produktu', 'netivo' ),
			'desc_tip' => true
		);

		$settings[] = array(
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
		);

		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'nt_preorder_settings'
		);

		return $settings;
	}
}