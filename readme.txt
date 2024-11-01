=== Today In History ===
Contributors: bdoga, tdawg2
Plugin URI: http://www.macnative.com/development/today-in-history/
Author URI: http://www.macnative.com
Donate link: http://www.macnative.com/development/donate 
Tags: social, network, networks, count, friends, crowd, clan, contacts, stats, statistics, followers, readers, facebook, google+, google plus, twitter, feedburner, youtube, vimeo, number, raw
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 0.5.1

Today In History provides a simple widget that displays notable events that have occurred previously on this day in history.

== Description ==

The Today In History widget gives you access to a database of notable events that have happened throughout history. The database is hosted with [Macnative.com][1] and the widget queries it once a day to update the contents of the widget. 

Please visit the [Plugin Homepage][2] for more information

 [1]: http://www.macnative.com/
 [2]: http://www.macnative.com/development/today-in-history/

== Installation ==

1. Unzip and upload the "Today in History" plugin folder to wp-content/plugins/
1. Activate the plugin from your WordPress admin panel.
1. Installation finished.

== Frequently Asked Questions ==

= How do I customize the appearance of the widget? = 

In the widget options there is a checkbox titled 'Default Styling' if you uncheck that box, you can include the following CSS in your own css file with any required changes to apply your custom look and feel to the widget.

	<style type="text/css">
		h5.TIH_Date {
			font-size: 18px;
			font-style: italic;
			color: #555;
			font-weight: bold;
			text-align: center;
		}
		p.TIH_Event {
			text-indent: 25px;
			margin-top: 10px;
			padding-bottom: 10px;
		}
		div#TIH_Bottom {
			margin: 5px 0 5px 20px;
			clear: both;
			font-size: 8px !important;
		}
		#tihBottom a {
			font-size: 8px !important;
		}
	</style>

= How do I manage what items appear =

In the Widget Settings there are multiple checkboxes that give you access to specific categories of content. Simply uncheck any categories that you would rather not have in your widget, click save and you should be good to go.

= What if my favorite historical event is not in the database? =

[Drop me a line][3] and I will work on getting it added.
 [3]: http://www.macnative.com/contact-me/

== Screenshots == 

1. Admin Interface
2. Example Widget Output

== Changelog ==

= 0.5.1 [2012-11-03] = 
* Fixed and Issue that caused the Plugin Version to report incorrectly

= 0.5 [2012-11-03] = 
* Fixed and Issue that caused the results to not return the correct day

= 0.4.2 [2012-08-23] = 
* Updated for Wordpress 3.4.1

= 0.4.1 [2012-06-16] = 
* Updated for Wordpress 3.4

= 0.4 [2012-04-25] = 
* Updated for Wordpress 3.3.2

= 0.3 [2012-01-29] = 
* Initial Release

== Upgrade Notice == 


