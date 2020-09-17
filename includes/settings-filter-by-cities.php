<?php
/**
 * Legacy flat rate settings.
 *
 * @package WooCommerce\Shipping
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$cost_desc = __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'woocommerce' ) . '<br/>' . __( 'Supports the following placeholders: <code>[qty]</code> = number of items, <code>[cost]</code> = cost of items, <code>[fee percent="10" min_fee="20"]</code> = Percentage based fee.', 'woocommerce' );

$settings =  array(
    'single_method' => array(
        'title' 		=> __( 'Single method', 'departamentos-y-ciudades-de-colombia-para-woocommerce' ),
        'type' 			=> 'select',
        'description' 	=> __( 'When doing single shipping method, it eliminates all others and imposes its own rules', 'departamentos-y-ciudades-de-colombia-para-woocommerce' ),
        'class'         => 'wc-enhanced-select',
        'default' 		=> 'yes',
        'desc_tip'		=> true,
        'options'		=> array(
            'yes' 	=> __( 'Yes', 'woocommerce' ),
            'no' 		=> __( 'No', 'woocommerce' )
        )
    ),

    'title' => array(
        'title' 		=> __( 'Method title', 'woocommerce' ),
        'type' 			=> 'text',
        'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
        'default'		=> __( 'Shipping filter By cities', 'woocommerce' ),
        'desc_tip'		=> true
    ),
    'tax_status' => array(
        'title' 		=> __( 'Tax status', 'woocommerce' ),
        'type' 			=> 'select',
        'class'         => 'wc-enhanced-select',
        'default' 		=> 'taxable',
        'options'		=> array(
            'taxable' 	=> __( 'Taxable', 'woocommerce' ),
            'none' 		=> _x( 'None', 'Tax status', 'woocommerce' )
        )
    ),
    'cost' => array(
        'title' => __('Coste', 'departamentos-y-ciudades-de-colombia-para-woocommerce'),
        'type' 			=> 'text',
        'description' 	=> $cost_desc,
        'default'		=> '0',
        'desc_tip'		=> true
    ),
    'cities' => array(
        'title' => __('Cities','departamentos-y-ciudades-de-colombia-para-woocommerce'),
        'type' => 'multiselect',
        'class'       => 'wc-enhanced-select',
        'description' => __( 'Select the city referring to the region that you have previously added', 'departamentos-y-ciudades-de-colombia-para-woocommerce' ),
        'options' => $this->showCitiesRegions(),
        'desc_tip'    => true,
    )
);

$shipping_classes = WC()->shipping->get_shipping_classes();

if ( ! empty( $shipping_classes ) ) {
    $settings['class_costs'] = array(
        'title'       => __( 'Shipping class costs', 'woocommerce' ),
        'type'        => 'title',
        'default'     => '',
        /* translators: %s: URL for link. */
        'description' => sprintf( __( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
    );
    foreach ( $shipping_classes as $shipping_class ) {
        if ( ! isset( $shipping_class->term_id ) ) {
            continue;
        }
        $settings[ 'class_cost_' . $shipping_class->term_id ] = array(
            /* translators: %s: shipping class name */
            'title'             => sprintf( __( '"%s" shipping class cost', 'woocommerce' ), esc_html( $shipping_class->name ) ),
            'type'              => 'text',
            'placeholder'       => __( 'N/A', 'woocommerce' ),
            'description'       => $cost_desc,
            'default'           => $this->get_option( 'class_cost_' . $shipping_class->slug ), // Before 2.5.0, we used slug here which caused issues with long setting names.
            'desc_tip'          => true,
            'sanitize_callback' => array( $this, 'sanitize_cost' ),
        );
    }
    $settings['no_class_cost'] = array(
        'title'             => __( 'No shipping class cost', 'woocommerce' ),
        'type'              => 'text',
        'placeholder'       => __( 'N/A', 'woocommerce' ),
        'description'       => $cost_desc,
        'default'           => '',
        'desc_tip'          => true,
        'sanitize_callback' => array( $this, 'sanitize_cost' ),
    );
    $settings['type'] = array(
        'title'   => __( 'Calculation type', 'woocommerce' ),
        'type'    => 'select',
        'class'   => 'wc-enhanced-select',
        'default' => 'class',
        'options' => array(
            'class' => __( 'Per class: Charge shipping for each shipping class individually', 'woocommerce' ),
            'order' => __( 'Per order: Charge shipping for the most expensive shipping class', 'woocommerce' ),
        ),
    );
}
return $settings;