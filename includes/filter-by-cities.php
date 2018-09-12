<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 10/09/18
 * Time: 05:32 PM
 */

function filters_by_cities_method() {
    if ( ! class_exists( 'Filters_By_Cities_Method' ) ) {

        class Filters_By_Cities_Method extends WC_Shipping_Method
        {

            /**
             * Constructor for your shipping class
             *
             * @access public
             * @return void
             */
            public function __construct($instance_id = 0)
            {
                $this->id                 = 'filters_by_cities_shipping_method';
                $this->instance_id				= absint( $instance_id );
                $this->method_title       = __( 'Shipping filter By Cities', 'departamentos-y-ciudades-de-colombia-para-woocommerce' );
                $this->method_description = __( 'Allows adding rules by city', 'departamentos-y-ciudades-de-colombia-para-woocommerce' );

                $this->supports = array(
                    'settings',
                    'shipping-zones',
                    'instance-settings'
                );

                $this->init();

                $this->logger = new WC_Logger();
            }

            /**
             * Init your settings
             *
             * @access public
             * @return void
             */
            function init() {
                // Load the settings API
                $this->instance_form_fields = $this->define_instance_form_fields();
                $this->form_fields = $this->define_global_form_fields();
                $this->title = $this->get_option('title');
                $this->tax_status = $this->get_option( 'tax_status' );
                $this->cost = $this->get_option( 'cost' );
                $this->cities = $this->get_option( 'cities' );


                $this->init_form_fields();
                $this->init_settings();

                // Save settings in admin if you have any defined
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }


            public function define_instance_form_fields()
            {

                return include 'settings-filter-by-cities.php';
            }

            public function define_global_form_fields()
            {

                return array(
                    'methods' => array(
                        'type' => 'rules_shipping_methods',
                    ),
                );
            }

            /**
             * generate_rules_shipping_methods_html function.
             *
             * @access public
             * @return string
             */
            public function generate_rules_shipping_methods_html() {
                ob_start();
                include_once 'admin/html/html-shipping-methods.php';
                return ob_get_clean();
            }

            /**
             * @access public
             * @param mixed $package
             * @return void
             */
            public function calculate_shipping( $package )
            {
                $rate = array(
                    'id' => $this->id,
                    'label'   => $this->title,
                    'cost'		=> 0,
                    'package' => $package,
                );

                $city_destination = $package['destination']['city'];

                if (in_array($city_destination, $this->cities)){
                    $rate['cost'] += $this->cost;
                    $this->add_rate( $rate );
                }

                /*$print = print_r($city_destination, true);
                $this->logger->add('filter-by-cities', $print);*/
            }

            public function showCitiesRegions()
            {
                if (!isset($_REQUEST['instance_id']))
                    return array();
                $ins = WC_Shipping_Zones::get_zone_by( 'instance_id', $_REQUEST['instance_id'] );
                $data = $ins->get_data();
                if (!isset($data['zone_locations']))
                    return array();
                $zones = $data['zone_locations'];

                $cities = array();

                foreach ($zones as $zone){
                    $place = explode(':', $zone->code );
                    $states = WC_States_Places_Colombia::get_places( $place[0] );
                    $cities =  array_merge($cities,$this->orderArray($states[$place[1]]));
                }

                return $cities;
            }


            public function orderArray($array)
            {
                $cities = array();

                foreach ($array as $arr){
                    $cities[$arr] = $arr;
                }

                return $cities;
            }

        }
    }
}