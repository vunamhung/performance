<?php

namespace vnh\performance;

use vnh\contracts\Initable;
use vnh\Performance_Settings;

class Performance_Config implements Initable {
	public $settings;

	public function __construct(Performance_Settings $settings) {
		$this->settings = $settings;
	}

	public function init() {
		if ($this->settings->get_option('remove_jquery_migrate')) {
			new Remove_Jquery_Migrate();
		}

		if ($this->settings->get_option('remove_query_strings') && !is_admin()) {
			new Remove_Query_Strings();
		}

		if ($this->settings->get_option('disable_comments')) {
			$disable_comments = new Disable_Comments($this->settings);
			$disable_comments->boot();
		}

		if ($this->settings->get_option('disable_emoji')) {
			new Disable_Emoji();
		}

		if ($this->settings->get_option('disable_embeds')) {
			new Disable_Embeds();
		}

		if ($this->settings->get_option('disable_self_pingbacks')) {
			new Disable_Self_Pingbacks();
		}

		if ($this->settings->get_option('disable_dashicons')) {
			new Disable_Dashicons();
		}

		if ($this->settings->get_option('enable_blank_favicon')) {
			new Enable_Blank_Favicon();
		}

		if ($this->settings->get_option('remove_comment_urls')) {
			new Remove_Comment_URLs();
		}

		if ($this->settings->get_option('disable_xmlrpc')) {
			new Disable_XML_RPC();
		}

		if ($this->settings->get_option('hide_wp_version')) {
			remove_action('wp_head', 'wp_generator');
			add_filter('the_generator', '__return_false');
		}

		if ($this->settings->get_option('remove_rss_feed_links')) {
			remove_action('wp_head', 'feed_links', 2);
			remove_action('wp_head', 'feed_links_extra', 3);
		}

		if ($this->settings->get_option('remove_rest_api_links')) {
			remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
			remove_action('wp_head', 'rest_output_link_wp_head');
			remove_action('template_redirect', 'rest_output_link_header', 11);
		}

		if ($this->settings->get_option('remove_shortlink')) {
			remove_action('wp_head', 'wp_shortlink_wp_head');
			remove_action('template_redirect', 'wp_shortlink_header', 11);
		}

		if ($this->settings->get_option('remove_wlwmanifest_link')) {
			remove_action('wp_head', 'wlwmanifest_link');
		}

		if ($this->settings->get_option('remove_rsd_link')) {
			remove_action('wp_head', 'rsd_link');
		}
	}
}
