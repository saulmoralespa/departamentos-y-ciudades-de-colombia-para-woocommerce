<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$methods = array();

?>
    <tr valign="top">
        <th colspan="2" scope="row" class="titledesc"><?php echo __( 'Configured Shipping filter By Cities  Methods in all shipping zones', 'departamentos-y-ciudades-de-colombia-para-woocommerce' ) ?></th>
    </tr>
    <tr valign="top">
        <td colspan="2" class="forminp" id="<?php echo $this->id; ?>_rules_shipping_methods"><?php echo sprintf(__( 'This table shows <a href="%s">all shipping zones</a> and for each zone the shipping methods provided by this plugin.' , 'departamentos-y-ciudades-de-colombia-para-woocommerce'), admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ); ?>

            <table class="rules_shipping_methods wc-shipping-zones widefat striped" cellspacing="0">
                <thead>
                <tr>
                    <th class="name"    ><?php echo __( 'Zone name', 'shipping-filter-by-cities' ); ?></th>
                    <th class="methods" ><?php echo __( 'Configured Shipping filter By Cities methods' , 'shipping-filter-by-cities'); ?></th>
                    <th class="methods" ><?php echo __( 'Other methods' , 'shipping-filter-by-cities'); ?></th>
                </tr>
                </thead>
                <tbody class="wc-shipping-zone-rows">
                <?php
                $zones = WC_Shipping_Zones::get_zones();
                // get_zones does NOT include the global (fallback) zone => add it manually!
                $globalshippingzone = new WC_Shipping_Zone(0);
                $globalzone                            = $globalshippingzone->get_data();
                $globalzone['formatted_zone_location'] = $globalshippingzone->get_formatted_location();
                $globalzone['shipping_methods']        = $globalshippingzone->get_shipping_methods();
                $zones[] = $globalzone;
                foreach ($zones as $zone) {
                    $zoneid = isset($zone['zone_id'])?$zone['zone_id']:$zone['id'];
                    ?>
                    <tr>
                        <td class="name"><a href="<?php echo admin_url(sprintf('admin.php?page=wc-settings&tab=shipping&zone_id=%d', $zoneid )); ?>"><?php echo $zone['zone_name']; ?> (<?php echo $zone['formatted_zone_location']; ?>)</a></td>
                        <td class="methods wc-shipping-zone-methods ">
                            <ul>
                                <?php
                                foreach ($zone['shipping_methods'] as $method) {
                                    if ($method->id == 'filters_by_cities_shipping_method') {
                                        $methodclass = ($method->enabled=='no')?'method_disabled':'method_enabled';
                                        $methodurl = admin_url(sprintf('admin.php?page=wc-settings&tab=shipping&instance_id=%d', $method->instance_id));
                                        ?>
                                        <li class="<?php echo $methodclass;?>"><a href="<?php echo $methodurl;?>"><?php echo $method->title; ?></a></li>
                                        <?php
                                    }
                                } ?>
                            </ul>
                        </td>
                        <td class="methods wc-shipping-zone-methods ">
                            <ul>
                                <?php
                                foreach ($zone['shipping_methods'] as $method) {
                                    if ($method->id != 'filters_by_cities_shipping_method') {
                                        $methodclass = ($method->enabled=='no')?'method_disabled':'method_enabled';
                                        ?>
                                        <li class="<?php echo $methodclass;?>"><?php echo $method->title; ?></li>
                                        <?php
                                    }
                                } ?>
                            </ul>
                        </td>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </td>
    </tr>