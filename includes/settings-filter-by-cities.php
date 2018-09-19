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

return array(
    'title' => array(
        'title' 		=> __( 'Method Title', 'woocommerce' ),
        'type' 			=> 'text',
        'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
        'default'		=> __( 'Shipping filter By cities', 'woocommerce' ),
        'desc_tip'		=> true
    ),
    'tax_status' => array(
        'title' 		=> __( 'Tax Status', 'woocommerce' ),
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