<?php

namespace vnh\performance;

class Enable_Blank_Favicon {
	public function __construct() {
		add_action('wp_head', [$this, 'blank_favicon']);
	}

	public function blank_favicon() {
		echo '<link href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" rel="icon" type="image/x-icon" />';
	}
}
