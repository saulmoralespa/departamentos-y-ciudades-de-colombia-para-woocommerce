<?php
/**
 * Plugin Name: Departamentos y Ciudades de Colombia para Woocommerce
 * Description: Plugin modificado con los departamentos y ciudades de Colombia
 * Version: 2.0.16
 * Author: Saul Morales Pacheco
 * Author URI: https://saulmoralespa.com
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: departamentos-y-ciudades-de-colombia-para-woocommerce
 * Domain Path: /languages
 * WC tested up to: 8.5.1
 * WC requires at least: 6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('plugins_loaded','states_places_colombia_init');
add_action(
    'before_woocommerce_init',
    function () {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }
);

function states_places_colombia_smp_notices($notice){
    ?>
    <div class="error notice">
        <p><?php echo $notice; ?></p>
    </div>
    <?php
}

function states_places_colombia_init(){
    load_plugin_textdomain('departamentos-y-ciudades-de-colombia-para-woocommerce',
        FALSE, dirname(plugin_basename(__FILE__)) . '/languages');

    if(!departamentos_ciudades_colombia_para_woocommerce_requirements()) return;

    if (!class_exists('WC_States_Places_Colombia')) require_once ('includes/states-places.php');

    if (!function_exists('filters_by_cities_method')) require_once ('includes/filter-by-cities.php');

    /**
     * Instantiate class
     */
    $GLOBALS['wc_states_places'] = new WC_States_Places_Colombia(__FILE__);

    add_filter( 'woocommerce_shipping_methods', function ($methods){
        $methods['filters_by_cities_shipping_method'] = 'Filters_By_Cities_Method';
        return $methods;
    });

    add_filter( 'woocommerce_default_address_fields', function( $fields ){
        if ($fields['city']['priority'] < $fields['state']['priority']){
            $state_priority = $fields['state']['priority'];
            $fields['state']['priority'] = $fields['city']['priority'];
            $fields['city']['priority'] = $state_priority;
        }
        return $fields;
    }, 1000, 1 );

    add_action( 'woocommerce_shipping_init', 'filters_by_cities_method' );
}

function departamentos_ciudades_colombia_para_woocommerce_requirements(){

    if ( ! function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

    if ( ! is_plugin_active(
        'woocommerce/woocommerce.php'
    ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() {
                    states_places_colombia_smp_notices( 'Departamentos y Ciudades de Colombia para Woocommerce requiere que se encuentre instalado y activo el plugin: Woocommerce' );
                }
            );
        }
        return false;
    }

    return true;
}
