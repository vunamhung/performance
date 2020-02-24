<?php

namespace vnh\performance;

class Disable_Dashicons {
	public function __construct() {
		add_action('wp_enqueue_scripts', [$this, 'disable_dashicons']);
	}

	public function disable_dashicons() {
		if (!is_user_logged_in()) {
			wp_dequeue_style('dashicons');
			wp_deregister_style('dashicons');
		}
	}
}
