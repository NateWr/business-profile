=== Business Profile ===
Contributors: NateWr
Author URI: https://github.com/NateWr
Plugin URL: http://themeofthecrop.com
Requires at Least: 3.9
Tested Up To: 4.5
Tags: business profile, address, google map, schema, contact, phone, address, seo
Stable tag: 1.1
License: GPLv2 or later
Donate link: http://themeofthecrop.com

Display your business's contact details with seo-friendly Schema.org markup. Supports a Google Map, opening hours and more.

== Description ==

Add your business contact details to your site with seo-friendly Schema.org markup. This plugin adds a Contact Card widget and a  `[contact-card]` shortcode. You can use these to display the following on any page:

* Business name
* Address
* Phone number
* Contact page link or email address
* Link to Google Map with directions to your establishment
* Google Map showing your location
* Opening hours

Schema.org markup helps search engines like Google discover your address, phone number and opening hours so that they can display them with your listing on Google.

**Sorry, it does not support multiple locations.**

This plugin is part of a group of plugins for restaurants. Check out the [Food and Drink Menu](http://wordpress.org/plugins/food-and-drink-menu/), [Restaurant Reservations](http://wordpress.org/plugins/restaurant-reservations/) and [Good Reviews for WordPress](http://wordpress.org/plugins/good-reviews-wp/) plugins as well.

= How to use =

View the [help guide](http://doc.themeofthecrop.com/plugins/business-profile/?utm_source=Plugin&utm_medium=Plugin%Description&utm_campaign=Business%20Profile) to learn how to set up and display your Business Profile.

= Developers =

This plugin is packed with hooks so you can extend it as needed. Development takes place on [GitHub](https://github.com/NateWr/business-profile/), so fork it up.

== Installation ==

1. Unzip `business-profile.zip`
2. Upload the contents of `business-profile.zip` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to the Business Profile page in your admin menu. You will find it near the bottom.

== Screenshots ==

1. Display a full contact card on the front-end with the shortcode [contact-card] or use the widget to add it to a sidebar.
2. An easy-to-use form lets you add all of the information, locate the correct map coordinates and set up your opening hours.
3. Choose what information to display with the widget, or check out the shortcode attributes in the help document included.

== Changelog ==

= 1.0.9 (2016-02-12) =
* Fix: compatibility with wp-cli
* Fix: allow short weekday names to be translated
* Update: "get directions" link now opens in a new window/tab
* Update: widget now uses shortcode to print output
* Update: remove deprecated sensor attribute from Google Maps api call
* Add: make Google Maps objects and options available in global scope
* Add: allow map options to be filtered
* Add: javascript event triggered when map initialized

= 1.0.8 (2015-10-01) =
* Update: Simple Admin Pages lib to v2.0 (#27)
* Fix: line breaks can disrupt get directions link in embedded map (#17)

= 1.0.7 (2015-10-01) =
* Add: show shortcode on business profile page
* Add: obfuscate email address if displayed in contact details
* Fix: compatibility problems when the Google Maps API is already loaded
* New and updated translations: Dutch, Hebrew, Spanish (Colombia), Portugese, Spanish, Czech

= 1.0.6 (2015-04-03) =
* Fix: validation errors with address markup
* Fix: validation errors with contactPoint markup

= 1.0.5 (2014-09-21) =
* Fix: restore lost option to show contact info in widget options when Restaurant Reservations is activated

= 1.0.4 (2014-09-11) =
* Fix: contact link/email doesn't get shown.

= 1.0.3 (2014-09-04) =
* Fix: swapped desc/url meta values. h/t @thatryan

= 1.0.2 (2014-07-16) =
* Update Simple Admin Pages library to v2.0.a.7

= 1.0.1 (2014-07-16) =
* Fix character-case error and rename integrations file for better standardization

= 1.0 (2014-07-16) =
* Initial public release on WordPress.org
* Add an option to display a link to a booking form if the Restaurant Reservations plugin is active
* Fix: skip a scheduling rule if no weekdays are set. h/t @jasonhobbsllc

= 0.0.1 (2014-05-26) =
* Initial release

== Upgrade Notice ==

= 1.0.9 =
This update fixes wp-cli compatibility and adds a number of useful development filters/features for interacting with the map objects.

= 1.0.8 =
This update fixes an error in the Get Directions link that appears inside of embedded maps. I recommend you update as it likely effects a lot of people.

= 1.0.7 =
This update fixes some compatibility problems with third-party plugins or themes, disguises the email address if displayed, and updates a bunch of translations.

= 1.0.6 =
This update fixes validation errors with the address and contact point schema.org markup. It is strongly recommended that you update to improve Google compatibility.

= 1.0.5 =
This minor update fixes a problem in which the option to show or hide the contact details in a widget had disappeared.

= 1.0.4 =
This minor update fixes a problem in which a contact page or email address wouldn't get printed with the contact card.

= 1.0.3 =
This minor update fixes a problem in the Schema.org markup where your business description and URL got mixed up.

= 1.0.2 =
This version updates a library used by the plugin to increase compatibility with the other plugins in my restaurant plugin suite.

= 1.0.1 =
This update fixes a letter-case bug that may effect some installations.

= 1.0 =
This initial public release adds an integration with the Restaurant Reservations plugin. If the plugin is active it will now let you display a link to the booking form.
