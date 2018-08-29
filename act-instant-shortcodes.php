<?php
/*
Plugin Name: ACT Instant Shortcodes
Plugin URI: http://cgd.io/
Description:  Instantly output a shortcode when a template is loaded into a post by changing its wrapper. Example: Change [foo] to *|foo|*, to run on template load.
Version: 1.0.2
Author: CGD Inc.
Author URI: http://objectiv.co
GitHub URI:: https://github.com/clifgriffin/ACT-Instant-Shortcodes

------------------------------------------------------------------------
Copyright 2013-2016 Clif Griffin Development Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

class ACT_Instant_Shortcodes {
	public function __construct() {
		add_action('act_loaded', array($this, 'init') );
	}

	function init() {
		// Filter stub post before it's saved
		add_filter('act_load_template', array($this, 'load_template_shortcodes') );

		// Add docs to settings page
		add_action('act_admin_page_after_row', array($this, 'add_docs_to_admin'), 10, 1 );
	}

	function load_template_shortcodes( $stub_post ) {
		// Find double bracketed shortcodes
		$stub_post['post_content'] = preg_replace_callback('/\*\|.*\|\*/', array($this, 'process_shortcodes'), $stub_post['post_content']);

		return $stub_post;
	}

	function process_shortcodes($matches) {
		// assume only one match per call
		$match = $matches[0];

		// Transform into normal shortcodes
		$match = str_replace(array('*|', '|*'), array('[', ']'), $match);

		// Process shortcodes
		$match = do_shortcode($match);

		return $match;
	}

	function add_docs_to_admin( $plugin ) {
		?>
		<tr>
			<th scope="row" valign="top">Instant Shortcodes</th>
			<td>
				<p><img style="border: 2px #ccc solid" src="<?php echo plugins_url('assets/example.png', __FILE__); ?>" /></p>
				<p style="margin-bottom: 20px;"><em>ACT Instant Shortcodes</em> does not have any settings. An instant shortcode is one that is processed immediately upon loading a template.<br /> By contrast, a shortcode is normally processed when a post is viewed.</p>
				<p>To make a shortcode instant, change its wrapper from [] to *||*.</p>
				<p style="margin-bottom: 20px;"><b>Example:</b> <br /> To make the gallery shortcode instant, you would change [gallery] to *|gallery|*. </p>
				<p>This also applies to shortcodes with options. [foo bar="true"] becomes *|foo bar="true"|*.</p>
			</td>
		</tr>
		<?php
	}
}

$ACT_Instant_Shortcodes = new ACT_Instant_Shortcodes();
