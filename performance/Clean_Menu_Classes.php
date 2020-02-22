<?php

namespace vnh\performance;

class Clean_Menu_Classes {
	public function __construct() {
		add_filter('nav_menu_css_class', [$this, 'clean_menu_classes'], 5, 4);
	}

	public function clean_menu_classes($classes, $item, $args, $depth) {
		if (!is_array($classes)) {
			return $classes;
		}

		foreach ($classes as $i => $class) {
			// Remove class with menu item id
			$id = strtok($class, 'menu-item-');
			if (0 < (int) $id) {
				unset($classes[$i]);
			}
			// Remove menu-item-type-*
			if (false !== strpos($class, 'menu-item-type-')) {
				unset($classes[$i]);
			}
			// Remove menu-item-object-*
			if (false !== strpos($class, 'menu-item-object-')) {
				unset($classes[$i]);
			}
			// Change page ancestor to menu ancestor
			if ($class === 'current-page-ancestor') {
				$classes[] = 'current-menu-ancestor';
				unset($classes[$i]);
			}
		}

		// Remove submenu class if depth is limited
		if (isset($args->depth) && 1 === $args->depth) {
			$classes = array_diff($classes, ['menu-item-has-children']);
		}

		return $classes;
	}
}
