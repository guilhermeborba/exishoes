=== Add Chat App Button ===
Contributors: udidol
Tags: whatsapp, button, whatsapp button
Requires at least: 4.6
Tested up to: 6.0
Stable tag: 2.0.5
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add Chat App Button enables adding a customizeable click-to-chat button that opens a chat on WhatsApp.
This plugin is not affiliated with WhatsApp or Facebook in any way, it just provides an easy way to integrate a WhatsApp chat button into your website.
The plugin lets you choose between a simple WhatsApp icon and a rectangle with a custom text label.

== Description ==

The *Add Chat App Button* plugin enables adding a customizable click-to-chat WhatsApp button.
The plugin lets you choose between a simple WhatsApp icon and a rectangle with a custom text label (see screenshots).

== Screenshots ==

1. The "General Settings" tab in the plugin settings page
2. The "Button Design" tab in the plugin settings page
3. An example of the side-floating rectangle button design. You can customize the text on the button, as well as the button's background and text colors, in the "Button Design" tab.
4. An example of the plain WhatsApp icon that can be used as the floating button
5. An example of a fixed button anchored in the bottom of the page
 
== Usage == 

To enable the WhatsApp button, check the first checkbox in the settings page. You will find the settings page in the admin dashboard menu, under Settings->Add Chat App Button.

You must enter an international phone number (only numbers, **NO plus sign (+), NO dashes(-)**) in order to enable the WhatsApp button. Even if you manage to save settings into the database without entering a phone number, your button might not work properly.

The plugin settings page has two tabs: "General Settings" and "Button Design".

*The General Settings tab* includes options such as limiting the button to only display under a certain screen resolution (in pixels), attaching a default message that will be populated in the user's phone when they click the button, and more.

*The Button Design tab* includes controls for the button color, label text color, button type (side-bearing rectangle, WhatsApp icon, fixed-bottom button), and more. The Button Design tab also includes a smartphone mockup with a **live** preview screen, enabling you to see a real-time (estimated) rendering of how your button would look, on the fly, as you are changing its design.

The plugin detects whether the site visitor is using a desktop or mobile device and serves a different link for each accordingly.

== Installation ==

1. Upload the plugin files (the contents of add-whatsapp-button.zip) to the `/wp-content/plugins/add-whatsapp-button` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. On the WordPress Admin menu, go to Settings->Add Chat App Button to configure the plugin

== Frequently Asked Questions ==

= I see there is a setting for limiting the display hours for the WhatsApp button. What timezone does the plugin use for this setting? =

*Add Chat App Button* uses Javascript to check the time on the client's device. So it will be displayed/hidden according to the set time in each client's timezone.

== Changelog ==

= 2.0.5 =
* Updated compatibility with WordPress 6.0

= 2.0.3 =
* Updated compatibility with WordPress 5.9

= 2.0.2 =
* Updated compatibility with WordPress 5.8.2

= 2.0.0 =
* Complete PHP rewrite to use OOP and scoped methods instead of global functions.
* Bug fixes
* Updated compatibility with WordPress 5.8.0

= 1.2.1 =
* Bug fixes

= 1.2 =
* Changed plugin display name to comply with Facebook's trademark requirements
* Updated support for Wordpress 5.2.3

= 1.1.3 =
* Some bug fixes

= 1.1.1 =
* Updated support for WordPress 5.2

= 1.1.0 =
* Updated compatibility with WordPress 5.1.1
* Added a "Hide Button" option to the WhatsApp button. It adds a small button on the corner of the WhatsApp button that enables site visitors to hide the WhatsApp button if it bothers them. The "Hide Button" has two options: 
  * Full Remove: Completely remove the button from the page
  * Hide with Toggle Button: Slides the WhatsApp button to the side (outside page boundaries). This enables the site visitor to un-hide the WhatsApp button later, if they want.

= 1.0.4 =
* Updated compatibility with WordPress 5.1
* Minor bug fixes

= 1.0.3 =
* Updated compatibility with WordPress 5.0
* Minor bug fixes
 
= 1.0.2 =
* Minor bug fixes
 
= 1.0.1 =
* Fixed a bug with identification of the User Agent in Apple mobile devices
 
== Upgrade Notice ==
 
= 1.0.1 =
The bug fixed in this release prevented the plugin from working properly in Apple mobile devices. Please Upgrade