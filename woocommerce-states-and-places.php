<?php
/**
 * Plugin Name: Departamentos y Ciudades de Colombia para Woocommerce
 * Description: Plugin modificado con los departementos y ciudades de Colombia
 * Version: 1.1.3
 * Author: Saul Morales Pacheco
 * Author URI: https://saulmoralespa.com
 * Text Domain: woocommerce-extension
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Die if accessed directly
 */
defined( 'ABSPATH' ) or die( 'You can not access this file directly!' );

/**
 * Check if WooCommerce is active
 */
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    require_once ('includes/states-places.php');
    /**
    * Instantiate class
    */
    $state_place_CO = new WC_States_Places_Colombia(__FILE__);
    $GLOBALS['wc_states_places'] = $state_place_CO;



    require_once ('includes/filter-by-cities.php');

    add_action( 'woocommerce_shipping_init', 'filters_by_cities_method' );

    function add_filters_by_cities_method( $methods ) {
        $methods['filters_by_cities_shipping_method'] = 'Filters_By_Cities_Method';
        return $methods;
    }

    add_filter( 'woocommerce_shipping_methods', 'add_filters_by_cities_method' );
};