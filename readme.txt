=== Departamentos y Ciudades de Colombia para Woocommerce ===
Contributors: saulmorales
Donate link: https://saulmoralespa.com/donation
Tags: woocommerce, Colombia, departamentos, ciudades, states cities,woocommerce departamentos de Colombia, woocommerce ciudades de Colombia, desplegable, departamentos desplegables, ciudades desplegables, city dropdown, state dropdown, city select, cities select,
seleccionar ciudades,seleccionar departamentos
Requires PHP: 7.3
Requires at least: 6.0
Tested up to: 6.4.2
Stable tag: 2.0.16
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WordPress plugin that shows dropdowns for State and City Select for WooCommerce

== Description ==

This WooCommerce plugin transforms the text input for states, the city or town. With this plugin you can provide a list of states and cities to be shown as a select dropdown.

This will be shown in checkout pages, edit addresses pages, shipping calculator, etc.

= Supported Countries =
 * Colombia

== Installation ==

= Minimum Requirements =

WordPress 6.0  or greater
Woocommerce 2.2 or greater
PHP version 7.3 or greater
MySQL version 5.0 or greater

= Automatic installation =

- Automatic installation is the easiest option as WordPress handles the file transfers itself, and you don't need to leave your web browser. To do an automatic install of WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click `Add New`.
- Search for "Departamentos y Ciudades de Colombia para Woocommerce", install and activate.
- Available [@Github](https://github.com/saulmoralespa/departamentos-y-ciudades-de-colombia-para-woocommerce).


= Manual installation =

[See wordpress codex](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation)


= Updating =


Automatic updates should work like a charm; as is the best practice, back up should be undertaken before updates.

If on the off-chance you do encounter issues with the shop/category pages after an update you simply need to flush the permalinks by going to WordPress > Settings > Permalinks and hitting 'save'. That should return things to normal.


This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin
4. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)


== Frequently Asked Questions ==

= It includes all the cities of the 32 departments?

Only major cities of each department.

= Something else you have not told me?

* Bogotá is not included as a state, because it is not discarded
* In the shipping area so you can impose rules you must first add the department in the field of states

= They are not loading the states or departments at the checkout to what is due?

[Please view topic](https://wordpress.org/support/topic/conflicto-con-checkout-field-editor-for-woocommerce)

== Screenshots ==
1. States dropdown.
2. States dropdown on search.
3. Cities dropdown on search
4. Choose department WooCommerce.
5. Shipping rule by city
6. Reflection shipping rule by city

== Changelog ==
= 1.0 =
* 24/10/2016 First release.
= 1.1 =
* Added Bogotá D.C as state and localities.
= 1.1.2 =
* Eliminated localities of Bogotá D.C
= 1.1.3 =
* Added method shipping filter for city
= 1.1.4 =
* added languages Spanish Colombia and fixed state
= 1.1.7 =
* added cost of shipping class
= 1.1.17 =
* added new cities
= 1.1.18 =
* fixed delete other methods shipping
= 1.1.19 =
* added optional single method
= 1.1.20 =
* added order of fields state and city
= 1.1.21 =
* update readme version Woocommerce
= 1.1.22 =
* update readme version
= 1.1.23 =
* update cities
= 1.1.24 =
* update notices
= 1.1.25 =
* update city Codazzi, Cesar
= 1.1.26 =
* fixed class cost
= 1.1.27 =
*  Updated wp compatible version
= 1.1.28 =
*  Updated priority states and places
= 1.1.29 =
*  compatibility with version 5.5  of WordPress
= 1.1.30 =
*  Added cities in Antioquia and Casanare
= 1.1.31 =
*  Updated cities in Cauca and Valle del Cauca
= 1.1.32 =
* Updated readme version WordPress
= 1.1.33 =
* Updated readme version WordPress
= 2.0.0 =
* Added Bogotá D.C as state and localities
= 2.0.1 =
* Updated readme version wordpresss
= 2.0.2 =
* Deleted Bogotá D.C of states
= 2.0.3 =
* Updated Woocommerce compatible version
= 2.0.4 =
* Added compatibility for multisites
= 2.0.5 =
* Updated readme version wordpresss
= 2.0.6 =
* Updated readme version wordpresss and cities
= 2.0.7 =
* Updated container in place-select.js
= 2.0.8 =
* * Updated container in place-select.js
= 2.0.9 =
* Updated compatibility for MultiVendorX
= 2.0.10 =
* Updated wp compatible version
= 2.0.11 =
* *Updated wp compatible version
= 2.0.12 =
* Fixed function is_plugin_active
= 2.0.13 =
* Fixed function is_plugin_active
= 2.0.14 =
* Refactor load places and states
= 2.0.15 =
* Added filter filters_by_cities_shipping_method_params_rate
* Updated compatibility for Woocommerce HPOS
= 2.0.16 =
*  Added city Los santos of Santander