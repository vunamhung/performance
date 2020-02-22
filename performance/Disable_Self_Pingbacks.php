<?php

namespace vnh\performance;

class Disable_Self_Pingbacks {
	public function __construct() {
		add_action('pre_ping', [$this, 'disable_self_pingbacks']);
	}

	public function disable_self_pingbacks(&$links) {
		$home = get_option('home');

		foreach ($links as $l => $link) {
			if (strpos($link, $home) === 0) {
				unset($links[$l]);
			}
		}
	}
}
