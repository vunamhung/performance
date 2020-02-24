<?php

namespace vnh\performance;

class Clean_Post_Classes {
	public function __construct() {
		add_filter('post_class', [$this, 'clean_post_classes']);
	}

	public function clean_post_classes($classes) {
		if (!is_array($classes)) {
			return $classes;
		}

		// Change hentry to entry, remove if adding microformat support
		$key = array_search('hentry', $classes, true);
		if ($key !== false) {
			$classes = array_replace($classes, [$key => 'entry']);
		}

		$allowed_classes = ['entry', 'type-' . get_post_type()];

		return array_intersect($classes, $allowed_classes);
	}
}
