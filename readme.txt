=== Links to Web Proxy ===
Contributors: michaelmarus, trabaria
Tags: proxy, proxies, web proxy
Requires at least: 2.9.2
Tested up to: 3.2.1
Stable tag: 0.1

Need to run your links through a proxy? This plugin provides a fast, efficient proxy which also fixes most encoding problems from the original link content.

== Description ==
Create a Web proxy for links in your Wordpress CMS.  This can be useful for Cross-Domain calls.  The plugin also fixes most orginating content character encoding problems.  I've used this plugin to create Web proxies for ArcGIS MapServers and to clean invalid characters from feeds.

== Installation ==
1. Extract `links-to-web-proxy.zip` to the `plugins` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. To allow a Web proxy for links, add/edit links with the category `allow-proxy`
1. Go to the Settings > Links to Web Proxy Settings screen to grab the link URLs for links where the proxy is allowed

== Frequently Asked Questions ==

= How do I fix problems with feeds which have invalid UTF-8 characters? =

If the original feed sends a common character set in the header, then you should use the Clean ASCII Proxy Link.

== Screenshots ==

1. The Settings > Links to Web Proxy Settings screen where you can grab the link proxy urls for allowed proxies.

== Changelog ==
= 0.1 =
* First release.
== Upgrade Notice ==
= 0.1 =
First version of the plugin