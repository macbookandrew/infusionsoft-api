<?php
/*
 * Plugin Name: Infusionsoft Delete Orders
 * Plugin URI: https://github.com/macbookandrew/infusionsoft-api
 * Description: Delete Infusionsoft Orders
 * Version: 1.0.0
 * Author: AndrewRMinion Design
 * Author URI: https://andrewrminion.com
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create MySQL table:
    CREATE TABLE `infusionsoft_orders_to_delete` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `deleted` tinyint(1) DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=128042 DEFAULT CHARSET=utf8;
*/

/**
 * Delete Infusionsoft orders
 * @param  array  $attributes array of attributes
 * @return string HTML content
 */
function armd_infusionsoft_delete_orders( $attributes ) {
    global $wpdb;
    $shortcode_content = '';
    $orders_to_delete = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM infusionsoft_orders_to_delete WHERE deleted NOT LIKE "1"', $wpdb->prefix ) );

    foreach ( $orders_to_delete as $order ) {
        $result = Infusionsoft_InvoiceService::deleteInvoice($order->id);
        $shortcode_content .= '<li>Deleting invoice ' . $order->id . ': ' . $result . '</li>';
        $update = $wpdb->get_results( $wpdb->prepare( 'UPDATE infusionsoft_orders_to_delete SET deleted = "1" WHERE id = %d', $order->id ) );
    }

    return $shortcode_content;
}
add_shortcode( 'infusionsoft_delete_orders', 'armd_infusionsoft_delete_orders' );
