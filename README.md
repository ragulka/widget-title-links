Widget Title Links
==================

Adds possibility to create clickable widget titles in Wordpress.

== Description ==

This plugins simply adds an extra field to all widgets that lets you
add a link to the widget's title. When the widget is displayed, the title is now clickable and links to the url you entered.

Works with all widgets!

== Installation ==

1. Upload `widget-title-links` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Dance!

== Frequently Asked Questions ==

None at the moment

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.1.1 =
* Fixed a last minute bug

= 1.1 =
* Fixed "The plugin does not have a valid header" issue
* Refactored the plugin onto a class
* Uses dynamic_sidebar_params filter instead of wiget_title filter. This will hopefully make this plugin compatible with all widgets.

= 1.0 =
* Initial release