=== Plugin Name ===
Contributors: RPG84
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=97K9JBA7Z2K7Q
Tags: tradetracker, store, productfeed, affiliate, daisycon, zanox, cleafs, tradedoubler, paidonresults, M4N, xml productfeed
Requires at least: 4
Tested up to: 4.1
Stable tag: 4.6

A plugin that lets you import an XML productfeed from TradeTracker. 

== Description ==

This plugin gives you the abillity to add a store to your WordPress, based on a tradetracker productfeed. Tradetracker is an affiliate system that has
the abillity to generate a product feed for you. So you can have a store that brings in money without the hassle of owning a complete webstore. All you need to do is choose a store connected on tradetracker and add it. Users of your blog will then see the products on your blog and when interested they will be sent to the store. When they buy an item you will get a percentage of that sale.

You can also add zanox, daisycon, tradedoubler, paidonresults and cleafs With the new premium addons.
 
Plugin also supports Lightbox. So if you don't have it yet i would advise to install http://wordpress.org/extend/plugins/wp-jquery-lightbox/

So remember: This plugin will not give you the ability to sell you own stuff. It gives you the ability to import product feeds from affiliate networks.

== Installation ==

1. Upload `tradetracker-store` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Adjust all settings in the TT Store on the left side of the admin menu

== Frequently Asked Questions ==
= Do you have an example ? =
You can see the plugin in action on: http://www.debesteschoenen.nl
This site also uses the Statistics addon (The outpage you see before you go to the affiliates website)

= Have you gotten any questions yet? =

You can find als FAQs here: http://wpaffiliatefeed.com/category/frequently-asked-questions/

== Screenshots ==

1. This is how the items will be shown on your site
2. This is the settings menu in the admin area
3. Here you select which products you want to show on your site

== Changelog ==
= 4.6 =
- Major improvements on the importer. 
- Manual importer received a nicer interface
- You can now select which feeds should be auto imported. It will still update all feeds in the manual import

= 4.5.65 = 
- Improved error for write access

= 4.5.64 =
- Added a way to test an XML feed in the XML Feed menu

= 4.5.63 =
- Added link to search pages FAQ 

= 4.5.62 =
- Small fix for when store table is not created in a new installation

= 4.5.61 = 
- Store table was missing 2 collums

= 4.5.60 = 
- Remove duplicate indexes in the database
- Improved check in import to prevent reimporting the same feed over and over again

= 4.5.59 =
- You can now copy a store

= 4.5.58 = 
- Small change so items per page is not adjusted by changing price

= 4.5.57 =
- Added link to the FAQ for import errors in to the email.

= 4.5.56 =
- Small change so you can show first and last page number

= 4.5.55 =
- Added a sorting option for the price for visitors on your site
- Gave the page selection and the price sorter selection a class so you can style it in css

= 4.5.53 =
- Fixed small mistake that removed the price from the slider

= 4.5.52 = 
- Price slider should work on mobile too now

= 4.5.51 =
- Store settings now also have a min price. So you are able to show items between a price range

= 4.5.49 = 
- Store settings allow you to change currency for the price filter


= 4.5.48 = 
- Small update to the z-index of most admin menu's
- Some removals of mysql functions because 3.9 uses mysqli

Full changelog on http://wpaffiliatefeed.com/category/releaselog/

== Upgrade Notice == 
= 4.5.19 =
Two new addons, 1 xml provider called Managementboek and a statistics addon

= 4.5.17 =
Modify Errors issue that came up on certain servers is now resolved 

= 4.5.16 =

This update will make the import of feeds with high amount of extra fields/categories quicker

= 4.5.15 = 
Support for longer XML Feed urls

= 4.5.13 =
Removes the error messages in the admin menu

= 4.5.12 =
This fixes the errors that came up due to the new Wordpress version. Also using the wordpress jquery for the price range so it should work on every site.

= 4.5.4 =
This version will support the changes in the Tradetracker Daily feed

= 4.5.1 = 
This version is needed to get the new Tradetracker feeds working

= 4.1.1 =
Deleting of stores, better error messages for failed import
