<?php

namespace vnh\performance;

class Remove_Query_Strings {
	public function __construct() {
		add_filter('script_loader_src', [$this, 'remove_query_strings_split'], 15);
		add_filter('style_loader_src', [$this, 'remove_query_strings_split'], 15);
	}

	public function remove_query_strings_split($src) {
		$output = preg_split("/(&ver|\?ver)/", $src);

		return $output[0];
	}
}
