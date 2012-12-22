=== Plugin Name ===
Contributors: Mike_Cowobo
Donate link: http://trenvo.com
Tags: multisite, network, defaults, pages
Requires at least: 3.5
Tested up to: 3.5
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically add default pages to new blogs in your network.

== Description ==

This plugin automatically adds pages to new sites in your network, based on the included INI, an INI file loaded by another plugin, or custom hooked code by another plugin.

This plugin contains an example that adds an empty home page and a simple Grunion contact form page to a new site.

= Usage =

Simplest way of using this plugin is by editing its .ini file directly, but this will be overwritten by updates.

Alternatively, you can create your own plugin hooking that replaces the ini file (filter 'default_pages_ini_replace'), adds extra ini files to the extra ini files array (filter 'default_pages_ini_append'), or that hooks directly in the config (filter 'default_pages').

INI syntax is as follows (see pages.ini for examples):

`[page-slug]
field = content`

Fields can be any field as per wp_insert_post().

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 0.1 =
* First version