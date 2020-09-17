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

                parent::__construct($instance_id);

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
                $this->single_method = $this->get_option('single_method');
                $this->title = $this->get_option('title');
                $this->tax_status = $this->get_option( 'tax_status' );
                $this->cost = $this->get_option( 'cost' );
                $this->cities = $this->get_option( 'cities' );
                $this->type = $this->get_option( 'type', 'class' );


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
            public function generate_rules_shipping_methods_html()
            {
                ob_start();
                include_once 'admin/html/html-shipping-methods.php';
                return ob_get_clean();
            }

            /**
             * @access public
             * @param mixed $package
             * @return void
             */
            public function calculate_shipping( $package = array() )
            {
                $rate = array(
                    'id' => $this->id,
                    'label'   => $this->title,
                    'cost'		=> 0,
                    'package' => $package,
                );

                $city_destination = $package['destination']['city'];
                // Calculate the costs.
                $has_costs = false; // True when a cost is set. False if all costs are blank strings.
                $cost      = $this->get_option( 'cost' );
                if ( '' !== $cost ) {
                    $has_costs    = true;
                    $rate['cost'] = $this->evaluate_cost(
                        $cost, array(
                            'qty'  => $this->get_package_item_qty( $package ),
                            'cost' => $package['contents_cost'],
                        )
                    );
                }
                // Add shipping class costs.
                $shipping_classes = WC()->shipping->get_shipping_classes();
                if ( ! empty( $shipping_classes ) ) {
                    $found_shipping_classes = $this->find_shipping_classes( $package );
                    $highest_class_cost     = 0;
                    foreach ( $found_shipping_classes as $shipping_class => $products ) {
                        // Also handles BW compatibility when slugs were used instead of ids.
                        $shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
                        $class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );
                        if ( '' === $class_cost_string ) {
                            continue;
                        }
                        $has_costs  = true;
                        $class_cost = $this->evaluate_cost(
                            $class_cost_string, array(
                                'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
                                'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
                            )
                        );
                        if ( 'class' === $this->type ) {
                            $rate['cost'] += $class_cost * array_sum( wp_list_pluck( $products, 'quantity' ) );
                        } else {
                            $highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
                        }

                        $logger = new WC_Logger();
                        $logger->add('departamento-ciudades', print_r($class_cost, true));
                    }
                    if ( 'order' === $this->type && $highest_class_cost ) {
                        $rate['cost'] += $highest_class_cost;
                    }
                }

                if ( $has_costs ) {
                    $this->add_rate( $rate );
                }
            }

            /**
             * See if the method is available.
             *
             * @param array $package Package information.
             * @return bool
             */
            public function is_available( $package )
            {
                $city_destination = $package['destination']['city'];

                if (!empty($this->cities)){
                    if (!in_array($city_destination, $this->cities))
                        return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', false, $package, $this );
                    if ($this->single_method === 'yes')
                    add_filter( 'woocommerce_package_rates', array($this, 'unset_filters_by_cities_shipping_method_zones') , 10, 2 );
                }
                return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package, $this );
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
                    if (strpos($zone->code, ':') !== false){
                        $place = explode(':', $zone->code );
                        $states = WC_States_Places_Colombia::get_places( $place[0] );
                        $cities =  array_merge($cities,$this->orderArray($states[$place[1]]));
                    }
                }

                return $cities;
            }

            public function unset_filters_by_cities_shipping_method_zones($rates, $package)
            {
                $all_free_rates = array();
                foreach ( $rates as $rate_id => $rate ) {
                    if ( $this->id === $rate->method_id ) {
                        $all_free_rates[ $rate_id ] = $rate;
                        break;
                    }
                }

                if ( empty( $all_free_rates )) {
                    return $rates;
                } else {
                    return $all_free_rates;
                }
            }


            public function orderArray($array)
            {
                $cities = array();

                foreach ($array as $arr){
                    $cities[$arr] = $arr;
                }

                return $cities;
            }

            /**
             * Finds and returns shipping classes and the products with said class.
             *
             * @param mixed $package Package of items from cart.
             * @return array
             */
            public function find_shipping_classes( $package )
            {
                $found_shipping_classes = array();
                foreach ( $package['contents'] as $item_id => $values ) {
                    if ( $values['data']->needs_shipping() ) {
                        $found_class = $values['data']->get_shipping_class();
                        if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
                            $found_shipping_classes[ $found_class ] = array();
                        }
                        $found_shipping_classes[ $found_class ][ $item_id ] = $values;
                    }
                }
                return $found_shipping_classes;
            }

            /**
             * Evaluate a cost from a sum/string.
             *
             * @param  string $sum Sum of shipping.
             * @param  array  $args Args.
             * @return string
             */
            protected function evaluate_cost( $sum, $args = array() )
            {
                include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';
                // Allow 3rd parties to process shipping cost arguments.
                $args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
                $locale         = localeconv();
                $decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
                $this->fee_cost = $args['cost'];
                // Expand shortcodes.
                add_shortcode( 'fee', array( $this, 'fee' ) );
                $sum = do_shortcode(
                    str_replace(
                        array(
                            '[qty]',
                            '[cost]',
                        ),
                        array(
                            $args['qty'],
                            $args['cost'],
                        ),
                        $sum
                    )
                );
                remove_shortcode( 'fee' );
                // Remove whitespace from string.
                $sum = preg_replace( '/\s+/', '', $sum );
                // Remove locale from string.
                $sum = str_replace( $decimals, '.', $sum );
                // Trim invalid start/end characters.
                $sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );
                // Do the math.
                return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
            }

            /**
             * Get items in package.
             *
             * @param  array $package Package of items from cart.
             * @return int
             */
            public function get_package_item_qty( $package ) {
                $total_quantity = 0;
                foreach ( $package['contents'] as $item_id => $values ) {
                    if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
                        $total_quantity += $values['quantity'];
                    }
                }
                return $total_quantity;
            }

        }
    }
}