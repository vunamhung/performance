<?php

namespace vnh\performance;

class Disable_Embeds {
	public function __construct() {
		add_action('init', [$this, 'disable_embeds'], 9999);
	}

	public function disable_embeds() {
		global $wp;
		$wp->public_query_vars = array_diff($wp->public_query_vars, ['embed']);
		remove_action('rest_api_init', 'wp_oembed_register_route');
		add_filter('embed_oembed_discover', '__return_false');
		remove_filter('oembed_dataparse', 'wp_filter_oembed_result');
		remove_action('wp_head', 'wp_oembed_add_discovery_links');
		remove_action('wp_head', 'wp_oembed_add_host_js');
		//add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
		add_filter('rewrite_rules_array', [$this, 'disable_embeds_rewrites']);
		remove_filter('pre_oembed_result', [$this, 'wp_filter_pre_oembed_result']);
	}

	public function disable_embeds_tiny_mce_plugin($plugins) {
		return array_diff($plugins, ['wpembed']);
	}

	public function disable_embeds_rewrites($rules) {
		foreach ($rules as $rule => $rewrite) {
			if (false !== strpos($rewrite, 'embed=true')) {
				unset($rules[$rule]);
			}
		}

		return $rules;
	}
}
