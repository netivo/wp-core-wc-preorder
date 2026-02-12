<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

/**
 * Represents a user interface panel that initializes required settings
 * and product-related components upon creation.
 */
class Panel {

	public function __construct() {
		new Settings();
		new Product();
	}
}