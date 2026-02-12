<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

class Panel {

	public function __construct() {
		new Settings();
		new Product();
	}
}