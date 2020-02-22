<?php

namespace vnh\performance;

use vnh\contracts\Bootable;
use vnh\Performance_Settings;

class Disable_Comments implements Bootable {
	public $settings;

	public function __construct(Performance_Settings $settings) {
		$this->settings = $settings;
	}

	public function boot() {
		//Disable Built-in Recent Comments Widget
		add_action('widgets_init', [$this, 'disable_recent_comments_widget']);

		//Check for XML-RPC
		if (empty($this->settings->get_option('disable_xmlrpc'))) {
			add_filter('wp_headers', [new Disable_XML_RPC(), 'remove_x_pingback']);
		}

		//Check for Feed Links
		if (empty($this->settings->get_option('remove_rss_feed_links'))) {
			remove_action('wp_head', 'feed_links_extra', 3);
		}

		//Disable Comment Feed Requests
		add_action('template_redirect', [$this, 'disable_comment_feed_requests'], 9);

		//Remove Comment Links from the Admin Bar
		add_action('template_redirect', [$this, 'remove_admin_bar_comment_links']); //frontend
		add_action('admin_init', [$this, 'remove_admin_bar_comment_links']); //admin

		//Finish Disabling Comments
		add_action('wp_loaded', [$this, 'wp_loaded_disable_comments']);
	}

	public function disable_recent_comments_widget() {
		unregister_widget('WP_Widget_Recent_Comments');
		add_filter('show_recent_comments_widget_style', '__return_false');
	}

	public function disable_comment_feed_requests() {
		if (is_comment_feed()) {
			wp_die(esc_html__('Comments are disabled.', 'vnh_textdomain'), '', ['response' => 403]);
		}
	}

	public function remove_admin_bar_comment_links() {
		if (is_admin_bar_showing()) {
			remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
		}
	}

	public function wp_loaded_disable_comments() {
		//Remove Comment Support from All Post Types
		$post_types = get_post_types(['public' => true]);
		if (!empty($post_types)) {
			foreach ($post_types as $post_type) {
				if (post_type_supports($post_type, 'comments')) {
					remove_post_type_support($post_type, 'comments');
					remove_post_type_support($post_type, 'trackbacks');
				}
			}
		}

		//Close Comment Filters
		add_filter('comments_array', '__return_empty_array', 20, 2);
		add_filter('comments_open', '__return_false', 20, 2);
		add_filter('pings_open', '__return_false', 20, 2);

		if (is_admin()) {
			//Remove Menu Links + Disable Admin Pages
			add_action('admin_menu', [$this, 'admin_menu_remove_comments'], 9999);

			//Hide Comments from Dashboard
			add_action('admin_print_styles-index.php', [$this, 'hide_dashboard_comments_css']);

			//Hide Comments from Profile
			add_action('admin_print_styles-profile.php', [$this, 'hide_profile_comments_css']);

			//Remove Recent Comments Meta
			add_action('wp_dashboard_setup', [$this, 'remove_recent_comments_meta']);

			//Disable Pingback Flag
			add_filter('pre_option_default_pingback_flag', '__return_zero');
		} else {
			//Replace Comments Template with a Blank One
			add_filter('comments_template', [$this, 'blank_comments_template'], 20);

			//Remove Comment Reply Script
			wp_deregister_script('comment-reply');

			//Disable the Comments Feed Link
			add_filter('feed_links_show_comments_feed', '__return_false');
		}
	}

	public function admin_menu_remove_comments() {
		global $pagenow;

		//Remove Comment + Discussion Menu Links
		remove_menu_page('edit-comments.php');
		remove_submenu_page('options-general.php', 'options-discussion.php');

		//Disable Comments Pages
		if ($pagenow === 'comment.php' || $pagenow === 'edit-comments.php') {
			wp_die(esc_html__('Comments are disabled.', 'vnh_textdomain'), '', ['response' => 403]);
		}

		//Disable Discussion Page
		if ($pagenow === 'options-discussion.php') {
			wp_die(esc_html__('Comments are disabled.', 'vnh_textdomain'), '', ['response' => 403]);
		}
	}

	public function hide_dashboard_comments_css() {
		echo '<style> #dashboard_right_now .comment-count, #dashboard_right_now .comment-mod-count, #latest-comments, #welcome-panel .welcome-comments { display: none !important; } </style>';
	}

	public function hide_profile_comments_css() {
		echo '<style> .user-comment-shortcuts-wrap { display: none !important; } </style>';
	}

	public function remove_recent_comments_meta() {
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	}

	public function blank_comments_template() {
		return __DIR__ . '/comments-template.php';
	}
}
