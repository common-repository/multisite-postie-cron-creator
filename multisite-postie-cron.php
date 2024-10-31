<?php
/*
Plugin Name: Multisite Postie CRON Creator
Plugin URI: https://cellarweb.com/wordpress-plugins/
Description: The Postie plugin doesn't always grab new mail, especially on lower-volume sites. So this plugin creates a CRON command line to force Postie 'get mail' process on all multi-sites or single sites. You will need to insert the actual CRON job on your server; hosting sites will have specific instructions. Requires Postie plugin.
Text Domain:
Author: Rick Hellewell / CellarWeb.com
Version: 1.02
Requires at least: 4.9.6
PHP Version: 5.3
Author URI: https://CellarWeb.com
License: GPLv2 or later
 */

/*
Copyright (c) 2015-2019 by Rick Hellewell and CellarWeb.com
All Rights Reserved

email: rhellewell@gmail.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
global $CWMSPC_version;
$CWMSPC_version = "1.02  (18 Nov 2023)";
global $atts; // used for the shortcode parameters
include_once ABSPATH . 'wp-admin/includes/plugin.php';

if (!CWMSPC_is_requirements_met()) {
	add_action('admin_init', 'CWMSPC_disable_plugin');
	add_action('admin_notices', 'CWMSPC_show_notice');
	CWMSPC_deregister();
	return;
}

// Add settings link on plugin page
function CWMSPC_settings_link($links) {
	$settings_link = '<a href="options-general.php?page=CWMSPC_settings" title="Multisite Postie CRON Creator">Multisite Postie CRON Creator Info/Usage</a>';
	array_unshift($links, $settings_link);
	return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'CWMSPC_settings_link');

//  build the class for all of this
class CWMSPC_Settings_Page {
// start your engines!

	public function __construct() {
		add_action('admin_menu', array($this, 'CWMSPC_add_plugin_page'));
	}

	// add options page

	public function CWMSPC_add_plugin_page() {
		// This page will be under "Settings"
		add_options_page('Multisite Postie CRON Creator Info/Usage', 'Multisite Postie CRON Creator Info/Usage', 'manage_options', 'CWMSPC_settings', array($this, 'CWMSPC_create_admin_page'));
	}
	// options page callback

	public function CWMSPC_create_admin_page() {
		global $CWMSPC_version;
		// Set class property
		$this->options = get_option('CWMSPC_options');
		?>

<div class = 'CWMSPC_header'>
    <h1 align="center" >Multisite Postie CRON Creator</h1>
    <p>Version: <?php echo $CWMSPC_version; ?></p>
</div>
    <div class="CWMSPC_options">
        <?php CWMSPC_info_top();?>
        <h3>Information (background geeky stuff)</h3>
        <p>This is the sample CRON command to run WGET to run a forced Postie on a site every 30 minutes:</p>
<p class='CWMSPC_url_list'><code>*/30 * * * * /usr/bin/wget -O /dev/null https://www.example.com/wp-cron.php >/dev/null 2>&1</code></p>
        <p>This is a sample command to do a bunch of sites that are in 'text_file.txt'</p>
        <p class='CWMSPC_url_list'><code>*/30 * * * * /usr/bin/wget  -i text_file.txt >/dev/null 2>&1</code></p>
<p>This is the sample way to call WGET no matter where it is (uses the 'env' value)</p>
<p class='CWMSPC_url_list'><code>/usr/bin/env wget [options] [url]</code></p>
    <h3>Some paths that will be needed</h3>
 <?php

		echo "<div class='CWMSPC_url_list'>";
		echo "<b>current working directory</b> " . getcwd() . "<br>";
		$plugin_path = plugin_dir_path(__FILE__);
		echo "<b>plugin path</b> " . $plugin_path . "<br>";
		$cron_file = $plugin_path . "CWMSPC_file.txt";
		echo "<b>CRON batch file</b> " . $cron_file;
		echo "</div>";
		$postie_urls = CWMSPC_get_sites();
		$url_list    = "";
		foreach ($postie_urls as $url_item) {
			$url_list .= $url_item . PHP_EOL;
		}
		?>
    <h3>Site URLs found</h3>
        <p>These are the URLs needed to run a forced Postie on all sites. A multisite installation will show all subsites. A single site installation will show just the single site.</p>
    <?php

		echo "<div class='CWMSPC_url_list'>";

		echo nl2br($url_list);

		echo "</div>"; ?>
<h3>Creating the CRON URL text file</h3>
<p>So this file has been created: </p>
<p class='CWMSPC_url_list'><?php echo $cron_file; ?></p>
<?php $success = file_put_contents($cron_file, $url_list);
		if ($success) {echo "&nbsp;&nbsp;&nbsp;File created OK!<br>";} else {echo "&nbsp;&nbsp;&nbsp;File not created<br>";}
		?>
 <h3>Here's the entire CRON command!</h3>
 <p>If file was created OK, this is the CRON command to use:</p>
 <?php $cmd_chars = strlen($cron_file) + 30;?>
 <p class='CWMSPC_url_list'><input class="CWMSPC_tiny" id='croncmd' size='<?php echo $cmd_chars; ?>' value="   */30 * * * * /usr/bin/wget  -i <?php echo $cron_file; ?> >/dev/null 2>&1" /></p>
<p>Copy the above command to your clipboard (double-click field, then Ctrl+C to copy). Contact your hosting place on how to add it as a CRON command. Change the time interval as needed.</p>
<p>Remove the <code><?php echo htmlentities(">/dev/null 2>&1"); ?></code> part to show output when it runs (see your hosting help files for further information). </p>
<h3>That's all, folks!</h3>
<p>If you put the above command as a CRON command, all of the sites will do the Postie check every 30 minutes. </p>
<p>"Postie" is the plugin that converts an email to a post. But on low-traffic sites, the process may not run as often as needed> So the CRON command we built here will force a Postie check every 30 minutes. That will help low-volume sites process their emails into posts using Postie.</p>
<h3>Trademarks and Stuff</h3>
<p>"Postie" is the property of the Postie folks. Go here for information: <a href="http://postieplugin.com/" target="_blank">http://postieplugin.com/</a></p>
<p>We just built this plugin because we have some sites that don't run the Postie 'get mail' thing often enough. Now we don't have that problem.</p>
<p>Once you get the command as a CRON (and test it), you can deactivate this plugin. But keep it around in case you need it when you add new sites - just activate, go to this settings page, and the file will be created. Your existing CRON command will not need to be changed.</p>
<p> Questions? Contact us here: <a href="https://cellarweb.com/contactus/" target="_blank">https://cellarweb.com/contactus/</a></p>
    </div>

    <div class='CWMSPC_sidebar'>
        <?php CWMSPC_sidebar();?>
    </div>
<!-- not sure why this one is needed ... -->
<div class="CWMSPC_footer">
    <?php CWMSPC_footer();?>
</div>

<?php
return;
	}
	// print the Section text

	public function CWMSPC_print_section_info() {
		print '<h3><strong>Information about Multisite Postie CRON Creator from CellarWeb.com</strong></h3>';
	}
}
// end of the class stuff

if (is_admin()) {
	$my_settings_page = new CWMSPC_Settings_Page();
	//  ------------------------------------------------------------------
	// supporting functions
	//  ------------------------------------------------------------------
	function CWMSPC_script() {
		?>
<script>
function CopyTextAction() {
    var CopyText = document.getElementById("croncmd");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert("Copied the text: " + copyText.value);
}
</script>
<?php
return;
	}

//  display the top info part of the page
	//  ------------------------------------------------------------------
	function CWMSPC_info_top() {
		global $CWMSPC_version;

		?>
<h3><strong>Multisite Postie CRON Creator</strong></h3>

<p>We created this plugin to create the command file needed to ensure that the Postie process to convert emails to posts will run often enough. The default is for Postie to use the wp-cron function, but that only gets run during a site access. Low-volume sites may not run the Postie process often, so this pluginis especially useful for low-volume sites.</p>
<p>Although written for our multisite installations, you can use this on single sites too. If you add/subtract multisites, run this plugin again to update your site list.</p>
<p>Make sure that you test the CRON command once before letting it run every 30 minutes. Contact your hosting place, or site admins, for help with enabling and testing CRON commands.</p>

<hr />
<?php
}

	//  ------------------------------------------------------------------
	// ``end of admin area
	//here's the closing bracket for the is_admin thing
}
//  ------------------------------------------------------------------
// register/deregister/uninstall hooks
register_activation_hook(__FILE__, 'CWMSPC_register');
register_deactivation_hook(__FILE__, 'CWMSPC_deregister');
register_uninstall_hook(__FILE__, 'CWMSPC_uninstall');

// register/deregister/uninstall options (even though there aren't options)
function CWMSPC_register() {
	return;
}

function CWMSPC_deregister() {
	return;
}

function CWMSPC_uninstall() {
	return;
}

//  ------------------------------------------------------------------
function CWMSPC_get_sites() {
	$final_site_list = array();
	if (is_multisite()) {
	$subsites_object = get_sites();
	$subsites        = CWMSPC_objectToArray($subsites_object);
	$subsites_copy   = $subsites;
	$cron_urls       = array();
	foreach ($subsites as $subsite) {
		$cron_url    = "https://" . $subsite['domain'] . $subsite['path'] . "wp-cron.php";
		$cron_urls[] = $cron_url;
	}
	} else {
		// single site, so don't die...
		$cron_url = home_url() . "/wp-cron.php"; 
		$cron_urls[] = $cron_url;
		}
	return $cron_urls;
}

//  ------------------------------------------------------------------
function CWMSPC_is_requirements_met() {
	$min_wp  = '4.6';
	$min_php = '5.3.0';
	$ok = true;
	
	// Check for WordPress version
	if (version_compare(get_bloginfo('version'), $min_wp, '<')) {
		$ok = false;	
	}

	// Check the PHP version
	if (version_compare(PHP_VERSION, $min_php, '<')) {
		$ok = false;	
	}
	
	return $ok;
}

//  ------------------------------------------------------------------
function CWMSPC_disable_plugin() {
//    if ( current_user_can('activate_plugins') && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
	if (is_plugin_active(plugin_basename(__FILE__))) {
		deactivate_plugins(plugin_basename(__FILE__));

		// Hide the default "Plugin activated" notice
		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}
	}
}

//  ------------------------------------------------------------------
function CWMSPC_show_notice() {
	echo '<div class="notice notice-error is-dismissible"><p><strong>Multisite Postie Cron Creator</strong> cannot be activated - requires at least WordPress 4.6 and PHP 5.3.&nbsp;&nbsp;&nbsp;The Multisite Postie Cron Creator pluginautomatically deactivated.</p></div>';
	return;
}


//  ----------------------------------------------------------------------
function CWMSPC_objectToArray($object) { // convert object to array, required for get_sites() loop
	if (!is_object($object) && !is_array($object)) {
		return $object;
	}

	return array_map('CWMSPC_objectToArray', (array) $object);
}

//  ------------------------------------------------------------------
// set up
function CWMSPC_shortcodes_init() {
	global $CWMSPC_version;
	// get some CSS loaded for the settings page
	wp_register_style('CWMSPC_namespace', plugins_url('/css/settings.css', __FILE__), array(), $CWMSPC_version);
	wp_enqueue_style('CWMSPC_namespace'); // gets the above css file in the proper spot
}

add_action('init', 'CWMSPC_shortcodes_init', 999);
// ============================================================================
//  settings page sidebar content
//  ------------------------------------------------------------------
function CWMSPC_sidebar() {
	?>
<div>
<h3 align="center">But wait, there's more!</h3>
<p>There's our plugin that will automatically add your <strong>Amazon Affiliate code</strong> to any Amazon links - even links entered in comments by others!&nbsp;&nbsp;&nbsp;Check out our nifty <a href="https://wordpress.org/plugins/amazolinkenator/" target="_blank">AmazoLinkenator</a>! It will probably increase your Amazon Affiliate revenue!</p>
<p>We've got a <a href="https://wordpress.org/plugins/simple-gdpr/" target="_blank"><strong>Simple GDPR</strong></a> plugin that displays a GDPR banner for the user to acknowledge. And it creates a generic Privacy page, and will put that Privacy Page link at the bottom of all pages.</p>
<p>How about our <strong><a href="https://wordpress.org/plugins/url-smasher/" target="_blank">URL Smasher</a></strong> which automatically shortens URLs in pages/posts/comments?</p>
<p><a href="https://wordpress.org/plugins/blog-to-html/" target="_blank"><strong>Blog To HTML</strong></a> : a simple way to export all blog posts (or specific categories) to an HTML file. No formatting, and will include any pictures or galleries. A great way to convert your blog site to an ebook.</p>
<hr />
<p><strong>To reduce and prevent spam</strong>, check out:</p>
<p><b>The ultimate contact form spam preventer</b>: <a href="https://www.formspammertrapc.om" target="_blank" title="Contact Form Spam Preventer">FormSpammerTrap</a>. You can put your contact form on any page by modifying a template from your theme - just add three lines of code. It is also great for non-WP site. Very easy to implement - and it's free!. Check the <a href="https://www.formspammertrapc.om" target="_blank" title="Prevent Contact Form Spam for Free!">site</a> for details. </p>
<p><a href="https://wordpress.org/plugins/formspammertrap-for-comments/" target="_blank"><strong>FormSpammerTrap for Comments</strong></a>: reduces spam without captchas, silly questions, or hidden fields - which don't always work. </p>
<p><a href="https://wordpress.org/plugins/formspammertrap-for-contact-form-7/" target="_blank"><strong>FormSpammerTrap for Contact Form 7</strong></a>: reduces spam when you use Contact Form 7 forms. All you do is add a little shortcode to the contact form.</p>
<hr />
<p>For <strong>multisites</strong>, we've got:
<ul>
    <li><strong><a href="https://wordpress.org/plugins/multisite-comment-display/" target="_blank">Multisite Comment Display</a></strong> to show all comments from all subsites.</li>
    <li><strong><a href="https://wordpress.org/plugins/multisite-post-reader/" target="_blank">Multisite Post Reader</a></strong> to show all posts from all subsites.</li>
    <li><strong><a href="https://wordpress.org/plugins/multisite-media-display/" target="_blank">Multisite Media Display</a></strong> shows all media from all subsites with a simple shortcode. You can click on an item to edit that item. </li>
</ul>
</p>
<hr />
<p><strong>They are all free and fully featured!</strong></p>
<hr />
<p>I don't drink coffee, but if you are inclined to donate because you like my WordPress plugins, go right ahead! I'll grab a nice hot chocolate, and maybe a blueberry muffin. Thanks!</p>
<div align="center">
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="SKSN99LR67WS6">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
</div>
<hr />
<p><strong>Privacy Notice</strong>: This plugin does not store or use any personal information or cookies.</p>
</div>
<?php

	return;
}

function CWMSPC_footer() {
	?>
<p align="center"><strong>Copyright &copy; 2016- <?php echo date('Y'); ?> by Rick Hellewell and <a href="http://CellarWeb.com" title="CellarWeb" >CellarWeb.com</a> , All Rights Reserved. Released under GPL2 license. <a href="http://cellarweb.com/contact-us/" target="_blank" title="Contact Us">Contact us page</a>.</strong></p>
<?php

	return;
}
