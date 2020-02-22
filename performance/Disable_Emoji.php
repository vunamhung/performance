<?php

namespace vnh\performance;

class Disable_Emoji {
	public function __construct() {
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		add_filter('emoji_svg_url', '__return_false');
		add_filter('tiny_mce_plugins', [$this, 'disable_emoji_tinymce']);
		add_filter('wp_resource_hints', [$this, 'disable_emoji_dns_prefetch'], 10, 2);
	}

	public function disable_emoji_tinymce($plugins) {
		if (is_array($plugins)) {
			return array_diff($plugins, ['wpemoji']);
		}

		return [];
	}

	public function disable_emoji_dns_prefetch($urls, $relation_type) {
		if ($relation_type === 'dns-prefetch') {
			$emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2.2.1/svg/');
			$urls = array_diff($urls, [$emoji_svg_url]);
		}

		return $urls;
	}
}
