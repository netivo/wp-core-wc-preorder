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
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	public function add_menu(): void {
		add_options_page(
			'Ustawienia preorderu',
			'Preorder',
			'manage_options',
			$this->options_slug,
			'render_options_page'
		);
	}

	public function render_options_page(): void {
		add_settings_section(
			'preorder_section',
			'',
			'',
			$this->options_slug
		);

		register_setting();
	}
}