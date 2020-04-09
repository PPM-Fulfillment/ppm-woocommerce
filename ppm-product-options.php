<?php
/**
 * The code here sets options on individual products. Right now, those options are:
 * + fulfilled_by_ppm - default FALSE
 * + ppm_sku - default ""
 * 
 * These options are where/how we indicate whether a product is to be fulfilled by PPM Fulfillment.
 * Products fulfilled by PPM will use the ppm_sku value, if present, or the product's default sku.
 */

 /**
  * Add a "PPM SKU" text field - this is under the "Inventory" tab on a product page
  */
 function ppm_woo_add_ppm_sku() {
    $args = array(
        "label" => __("PPM SKU", "ppm-woo"),
        "placeholder" => __("Enter PPM SKU here", "ppm-woo"),
        "id" => "ppm_sku",
        "desc_tip" => true,
        "description" => __(
            "This SKU identifies a product in the PPM System; it's required if you're fulfilling with PPM.", 
            "woocommerce"
        ),
    );
    woocommerce_wp_text_input($args);
 }
 add_action("woocommerce_product_options_sku", "ppm_woo_add_ppm_sku");

/**
 * Save PPM SKU on product save
 */
function ppm_woo_save_ppm_sku($post_id) {
    $product = wc_get_product($post_id);

    $ppm_sku = isset($_POST['ppm_sku']) ? sanitize_text_field($_POST['ppm_sku']) : "";

    $product->update_meta_data("ppm_sku", $ppm_sku);
    $product->save();
}
add_action("woocommerce_process_product_meta", "ppm_woo_save_ppm_sku");

 /**
  * Add a "Fulfilled by PPM" checkbox - this is under the "Inventory" tab on a product page
  */
 function ppm_woo_add_fulfilled_by_ppm() {
    $args = array(
        "label" => "Fulfilled by PPM?",
        "id" => "ppm_fulfilled_by",
        "desc_tip" => true,
        "description" => __("Is this item fulfilled by PPM? Check the box if so", "ppm-woo"),
    );
    woocommerce_wp_checkbox($args);
 }
 add_action("woocommerce_product_options_sku", "ppm_woo_add_fulfilled_by_ppm");
 
 /**
 * Save "Fulfilled by PPM" on product save
 */
function ppm_woo_save_ppm_fulfilled_by($post_id) {
    $product = wc_get_product($post_id);

    $ppm_fulfilled_by = isset($_POST['ppm_fulfilled_by']) ? sanitize_text_field($_POST['ppm_fulfilled_by']) : "";

    $product->update_meta_data("ppm_fulfilled_by", $ppm_fulfilled_by);
    $product->save();
}
add_action("woocommerce_process_product_meta", "ppm_woo_save_ppm_fulfilled_by");