<?php

namespace vnh\performance;

class Remove_Comment_URLs {
	public function __construct() {
		add_filter('get_comment_author_link', [$this, 'remove_comment_author_link'], 10, 2);
		add_filter('comment_form_default_fields', [$this, 'remove_website_field'], 9999);
		add_filter('get_comment_author_url', '__return_false');
	}

	public function remove_comment_author_link($return, $author) {
		return $author;
	}

	public function remove_website_field($fields) {
		unset($fields['url']);
		return $fields;
	}
}
