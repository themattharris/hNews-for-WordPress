=== hNews for WordPress ===
Contributors: themattharris, ben_campbell
Tags: hNews, microformats, news, journalism
Stable tag: trunk
Requires at least: 2.8
Tested up to: 3.0

Adds extra fields to a post for storing hNews specific information. Includes a settings page for adding default values for the fields.

== Description ==

Adds extra fields to a post for storing hNews specific information. Includes a settings page for adding default values for the fields.

This version is the development trunk and is still in Alpha. It is not fully tested for production use yet.

== Frequently Asked Questions ==

= How do I contribute? =

Whilst the code for the plugin is hosted on [WordPress Plugins SVN](http://plugins.svn.wordpress.org/hnews-for-wordpress/) the development takes place
on [GitHub](http://github.com/themattharris/hNews-for-WordPress). If you wish to contribute to the project you can fork the code from there or give feedback
using the Issues and Wiki on there.

= How do I add the fields to my template =

Included in the plugin is the default WordPress Kubrik theme with hNews fields added to it. Checkout the single.php and page.php files to see how we've added the fields to the template.
The plugin includes a function [code]hnews_meta[/code] which can be used to produce the hnews meta markup block.
