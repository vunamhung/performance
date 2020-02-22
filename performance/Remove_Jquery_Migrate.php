<?php

namespace vnh\performance;

use WP_Scripts;

class Remove_Jquery_Migrate {
	public function __construct() {
		add_filter('wp_default_scripts', [$this, 'remove_jquery_migrate']);
	}

	public function remove_jquery_migrate(WP_Scripts $scripts) {
		if (!is_admin() && isset($scripts->registered['jquery'])) {
			$script = $scripts->registered['jquery'];

			if ($script->deps) {
				$script->deps = array_diff($script->deps, ['jquery-migrate']);
			}
		}
	}
}
