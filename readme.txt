=== Extended Page List ===
Contributors: grandslambert
Donate link: http://plugins.grandslambert.com/plugins/extended-page-lists/donate.html
Tags: page, post, post_type, content, excerpt, widget, sidebar
Requires at least: 2.5
Tested up to: 3.2.1
Stable tag: trunk

Add custom configured page lists to your posts, pages and sidebar.

== Description ==

Add custom configured page lists to your posts, pages and sidebar.

= Features =

* <strong>New</strong>: Now supports custom post types in short code.
* Full control of the HTML tags used to display the page list
* Include the page content or excerpt when listing the pages.
* Display subpages or full page hierarchy.
* Better sidebar widget for site navigation.
* Supports post thumbnails for content / excerpts.

= Languages =

* German (de_DE) translation submitted by Connie Müller-Godecke

== Installation ==

1. Upload `extended-page-list` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin on the options menu screen.

== Changelog ==

= 1.0 - July 22, 2011 =

* Fixed a bug that prevented shortcode from working twice on a single page.
* Fixed a bug that made the bock_tag and item_tag work properly.
* Added the block_class and item_class options to the output.

= 0.9.2 - February 19th, 2011 =

* Fixed a bug that prevented language files from loading.
* Added a German Translation based on version 0.9.
* Fixed an infinite loop issue when using the show_excerpt parameter where child pages used the shortcode.

= 0.9.1 - January 25, 2011 =

* Fixed a bug that broke the Appearence->Widgets manager.

= 0.9 - January 22, 2011 =
* Added support for custom post types in short code.
* Switched to wp_list_pages for pages - uses a filter to add content or excerpt.
* Cleaned up code to work more efficiently.
* Changed all display inputs to require '_' between words, such as showcontent is now show_content.
* Fixed language support to load language files from the correct folder.

= 0.8 - August 23rd, 2010 =

* Fixed errors in generated code.
* Fixed miscellaneous hidden errors.
* Added language support.

= 0.7 - August 6th, 2010 =

* Added support for post thumbnails.
* Fixed some incorrect tags when using excerpts.
* Fixed some broken links in the plugin.
* Fixed an issue where the link to page on more link was not working.

= 0.6 - December 18th, 2009 =

* Fixed a bug that prevent the plugin settings from saving in Wordpress MU
* Removing some debug code that displayed in some cases.

= 0.5.3 - October 28th, 2009 =

* Fixed a problem where the plugin path was not working with symbolic links.

= 0.5.2 - October 28th, 2009 =

* Fixed a bug in the widget form that did not save certain data.
* Cleaned up some heavy code to speed up processing of excerpts.

= 0.5.1 - October 27th, 2009 =

* Fixed a bug in the widget where errors appeared when the type was set to "Selected Pages Only" by no pages where selected.

= 0.5 - October 27th, 2009 =

== Upgrade Notice ==

= 1.0 =
* Fixes many of the bugs pertaining to tags.

= 0.9 =
*Shortcode attributes have changed! Check old code as it may no longer work.

= 0.8 =
*Fixed some formatting issues in the widget.

= 0.7 =
*Added thumbnail support and fixed some bad coding in excerpts.

= 0.6 =
*Required for use on Wordpress MU. Will copy multiple options into one record.

= 0.5 =
* First release

== Frequently Asked Questions ==

= Where can I get support? =

http://support.grandslambert.com/forum/extended-page-lists

== Screenshots ==

1. The settings screen.
2. The widget form.
3. A sample sidebar result displaying all pages.
4. Sample shortcode output using custom tags and excerpts.