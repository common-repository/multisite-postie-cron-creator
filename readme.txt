=== Multisite Postie CRON Creator ===
Contributors: rhellewellgmailcom
Donate link: https://www.cellarweb.com
Tags: postie, cron
Requires at least: 4.6
Tested up to: 6.4
Stable tag: 1.02
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The Postie plugin doesn't always grab new mail, especially on lower-volume sites. So this plugin creates a CRON command line to force Postie 'get mail' process on all multi-sites or single sites. You will need to insert the actual CRON job on your server; hosting sites will have specific instructions. Requires Postie plugin.

== Description ==

We created this plugin to create the command file needed to ensure that the Postie process to convert emails to posts will run often enough. This is especially useful for low-volume sites.

Although written for our multisite installations, you can use this on single sites too. If you add/subtract multisites, run this plugin again to update your site list.

Make sure that you test the CRON command once before letting it run every xx minutes. Contact your hosting place, or site admins, for help with enabling and testing CRON commands.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the plugin
1. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)

== Frequently Asked Questions ==

= What does this do? =

Postie uses WP's CRON functions to 'get' the mail. But on low-volume sites, there might be a delay when that happens.

So the Postie developers recommend that you create a CRON job with the commands to 'force' a Postie CRON.

But if you have multiple sites (a multisite), it's a pain to manually create a CRON for each site.

This plugin creates the CRON command line, and an input file containing a list of all of your site URLS. All you have to do is copy the CRON command line to your server, usually via your hosting's Control Panel.

= Will this work on single sites? =

Yes. 

= What if I add a new site to my multisite? =

Just run the plugin info/settings again, and a new input file will be created. 

No need to change your CRON job.

= How do I install/activate/ = 

Install the plugin in the usual way. If you are on a multisite, then you must add the plugin via the 'master' dashboard. Then go to any site (usually the first/main site and 'Network Activate'. )

= How do I get the CRON command? = 

Once installed and activated, use the Settings page. The CRON command, along with other information, will be displayed. 

Add that CRON job to your site, using the normal procedure. Most hosting places have instructions on how to add CRON jobs.

= Any requirements? =

Yes. You should have the Postie plugin installed and activated. This plugin does not check for that, but there is no reason to have this plugin without Postie, so no harm done. 


= Do I need to make any changes in Postie settings? =

Nope.

= What if I have a problem - or a suggestion? =

Just use the Support area here. Or you can contact us via https://www.cellarweb.com .

= Do you have other plugins? =

Yep. Several. See the Info/Settings screen when you activate the plugin.

= Do you have other offerings? =

Yes. Our most popular is a spam-bot blocking Contact form. And you can easily adapt the contact form to your site - or use it on a non-WordPress form.

Get all the details at https://www.FormSpammerTrap.com . It's all free.


== Screenshots ==



== Changelog ==

* Version 1.02 (18 Nov 2023)
	- Fixed warning about the version number
	- Tested with WP 6.4x

* Version 1.01 (25 Nov 2019)
	- Fixed requirement checking; it was backwards.
	- Removed checking for Postie plugin active, as it wasn't reliable. 
	- Added some more "FAQ" stuff.
	- Fixed running plugin on single site by checking for multisite; if not, just the single site's URL is used.
	
* Initial Release - Version 1.00 (25 Nov 2019)

== Upgrade Notice ==

* Version 1.02 (18 Nov 2023)
	- Fixed warning about the version number
	- Tested with WP 6.4x

* Version 1.01 (25 Nov 2019)
	- Fixed requirement checking; it was backwards.
	- Removed checking for Postie plugin active, as it wasn't reliable.
 	- Added some more "FAQ" stuff.	
 	- Fixed running plugin on single site by checking for multisite; if not, just the single site's URL is used.

* Initial Release - Version 1.00 (25 Nov 2019)

