<?php
/**
 * Plugin Name: Departamentos y Ciudades de Colombia para Woocommerce
 * Description: Plugin modificado con los departementos y ciudades de Colombia
 * Version: 1.1.7
 * Author: Saul Morales Pacheco
 * Author URI: https://saulmoralespa.com
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: departamentos-y-ciudades-de-colombia-para-woocommerce
 * Domain Path: /languages
 */

/**
 * Die if accessed directly
 */
defined( 'ABSPATH' ) or die( 'You can not access this file directly!' );

add_action('plugins_loaded','states_places_Colombia_init',0);

function states_places_Colombia_init(){
    load_plugin_textdomain('departamentos-y-ciudades-de-colombia-para-woocommerce',
        FALSE, dirname(plugin_basename(__FILE__)) . '/languages');
}

/**
 * Check if WooCommerce is active
 */
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    require_once ('includes/states-places.php');
    /**
    * Instantiate class
    */
    $GLOBALS['wc_states_places'] = new WC_States_Places_Colombia(__FILE__);



    require_once ('includes/filter-by-cities.php');

    add_action( 'woocommerce_shipping_init', 'filters_by_cities_method' );

    function add_filters_by_cities_method( $methods ) {
        $methods['filters_by_cities_shipping_method'] = 'Filters_By_Cities_Method';
        return $methods;
    }

    add_filter( 'woocommerce_shipping_methods', 'add_filters_by_cities_method' );
};