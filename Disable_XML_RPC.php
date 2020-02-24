<?php

namespace vnh\performance;

class Disable_XML_RPC {
	public function __construct() {
		add_filter('xmlrpc_enabled', '__return_false');
		add_filter('pings_open', '__return_false', 9999);
		add_filter('wp_headers', [$this, 'remove_x_pingback']);
	}

	public function remove_x_pingback($headers) {
		unset($headers['X-Pingback'], $headers['x-pingback']);
		return $headers;
	}
}
