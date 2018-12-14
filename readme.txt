=== Business Profile ===
Contributors: NateWr, fatmedia
Author URI: https://github.com/NateWr
Plugin URL: http://themeofthecrop.com
Requires at Least: 4.4
Tested Up To: 5.0
Tags: business profile, seo, local seo, schema, address, google map, contact, phone
Stable tag: 1.2.3
License: GPLv2 or later
Donate link: http://themeofthecrop.com

Display your business's contact details with seo-friendly Schema.org markup. Supports a Google Map, opening hours and more.

== Description ==

Add your business contact details to your site with seo-friendly Schema.org markup. This plugin adds a Contact Card block for the Gutenberg editor, a widget and a `[contact-card]` shortcode. You can use these to display the following on any page:

* Business name
* Address
* Phone number
* Contact page link or email address
* Link to Google Map with directions to your establishment
* Google Map showing your location
* Opening hours

Schema.org markup helps search engines like Google discover your address, phone number and opening hours so that they can display them with your listing on Google.

Supports [multi-location businesses](http://doc.themeofthecrop.com/plugins/business-profile/user/getting-started/locations) with a custom Locations post type.

This plugin is part of a suite of plugins for restaurants. [Take online reservations](https://themeofthecrop.com/plugins/restaurant-reservations/?utm_source=Plugin&utm_medium=Plugin%20Description&utm_campaign=Business%20Profile) and build [responsive online menus](https://themeofthecrop.com/plugins/food-and-drink-menu/?utm_source=Plugin&utm_medium=Plugin%20Description&utm_campaign=Business%20Profile) at [Theme of the Crop](https://themeofthecrop.com/?utm_source=Plugin&utm_medium=Plugin%20Description&utm_campaign=Business%20Profile).

= How to use =

View the [help guide](http://doc.themeofthecrop.com/plugins/business-profile/?utm_source=Plugin&utm_medium=Plugin%Description&utm_campaign=Business%20Profile) to learn how to set up and display your Business Profile.

= Developers =

This plugin is packed with templates and hooks so you can extend it as needed. Read the [developer documentation](http://doc.themeofthecrop.com/plugins/business-profile/developer/). Development takes place on [GitHub](https://github.com/NateWr/business-profile/), so fork it up.

== Installation ==

1. Unzip `business-profile.zip`
2. Upload the contents of `business-profile.zip` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to the Business Profile page in your admin menu. You will find it near the bottom.

== Frequently Asked Questions ==

= Is there a shortcode to print the contact card? =

Yes, you can use `[contact-card]`. The documentation includes [all of the shortcode attributes](http://doc.themeofthecrop.com/plugins/business-profile/user/faq#shortcode).

= It asks me for a Google Maps API Key but I don’t know what it is or how to get it. =

Google now requires that you have your own API key to display a map on your website. The documentation includes a walkthrough to help you [generate a Google Maps API key](http://doc.themeofthecrop.com/plugins/business-profile/user/faq#google-maps-api-key).

= Google Maps shows my business in the wrong location =

Unfortunately, in some cases Google is unable to find the right latitude and longitude to match your address.

In some cases, you may be able to get it to properly locate you by tweaking the address. Sometimes Google just needs a bit of help. Once you’ve got the right coordinates you can go back and restore your original address, and save the form without touching the coordinates again.

If you’re unable to get Google to recognize your location, the best thing to do is to leave the Google Map out when you print your contact card. You will also want to hide the Get Directions link, because Google will guide your customers to the wrong location.

There’s not much I can do about this, unfortunately. Even if you were able to manually set the latitude and longitude, Google would still show bad directions, because it uses the address, not the coordinates, for this feature.

= What’s the Schema Type? =

This allows you to let search engines like Google know exactly what kind of business you run.

That way, when someone looks for a real estate agent or a restaurant in your area, they’ll know to include you in their search results.

You may not find a type that’s a perfect match for your business. Choose the option that’s most appropriate for your business, and fall back to a more generic type, such as Local Business, if you need.

= More questions =

You'll find more help in the [User Guide](http://doc.themeofthecrop.com/plugins/business-profile/user/). Developers interested in templates, filters and theme support can view the [Developer Documentation](http://doc.themeofthecrop.com/plugins/business-profile/developer/).

== Screenshots ==

1. Display a full contact card on the front-end with the shortcode [contact-card] or use the widget to add it to a sidebar.
2. An easy-to-use form lets you add all of the information, locate the correct map coordinates and set up your opening hours.
3. Choose what information to display with the widget, or check out the shortcode attributes in the help document included.
4. Optional multi-location support to easily display all of your locations.
5. Add a contact card to any page or post with the block.

== Changelog ==

= 1.2.3 (2018-12-14) =
* Fix: fatal error in old versions of PHP (< 5.4)

= 1.2.2 (2018-12-12) =
* Fix: contact card block loads in editor without saved location

= 1.2.1 (2018-12-11) =
* Update: .pot file with new translation strings

= 1.2 (2018-12-11) =
* Add: gutenberg block for the contact card

= 1.1.5 (2018-09-26) =
* Fix: Address coordinate lookups need to be https:// and use api key

= 1.1.4 (2017-04-21) =
* Add: business image to comply with Google requirements

= 1.1.3 (2017-03-21) =
* Fix: Fatal error with location schedule metabox

= 1.1.2 (2017-03-14) =
* Fix: Don't display contact card for unpublished locations
* Fix: PHP Notice on post editing page (h/t @robneu)
* Add: Italian and Swedish translations (h/t @lucspe and Daniel Schwitzkey)
* Update: Always instantiate post type class
* Update: Give settings table rows class attributes (h/t @lucspe)

= 1.1.1 (2016-06-28) =
* Add field for Google Maps API Key to follow new API guidelines

= 1.1 (2016-06-20) =
* Add: multi-location support
* Add: filter to adjust available schema types
* Add: templates for contact cards and opening hours
* Add: helper functions for templating
* Add: add_theme_support() args for disabling scripts, styles and append to content
* Update: implement WP coding standards. h/t @robnue

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

= 1.1.5 =
This update fixes the address coordinate lookup, so that coordinates can be set for more reliable Google Map display.

= 1.1.4 =
This update adds a new image setting, which Google now requires for local businesses. You're strongly encouraged to add an image for your business.

= 1.1.3 =
This update fixes a critical bug when trying to add or edit a location's opening hours.

= 1.1.2 =
This minor update fixes a few obscure bugs and prevents the [contact-card] shortcode from displaying an unpublished location. It also adds Italian and Swedish translations.

= 1.1.1 =
This update adds support for a Google Maps API Key. Since June 22, 2016, Google Maps will require all _new_ websites to use an API key in order to display maps. Instructions can be found near the new API Key field in your Business Profile.

= 1.1 =
This major update adds support for multiple locations, refactors the codebase to follow WP coding guidelines, and adds several templates and helper functions for customization.

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
