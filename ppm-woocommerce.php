<?php
  /**
   * Plugin Name: PPM Fulfillment - Woo Commerce
   * Plugin URI: https://github.com/PPM-Fulfillment/ppm-woocommerce
   * Description: Fulfill your WooCommerce orders through PPM Fulfillment
   * Author: Andrew Ek
   * Text Domain: ppm-woo
   */

define("PPM_WOOCOMMERCE_VERSION", "1.0.0");

function ppm_woo_admin_menu() {
  add_menu_page(
    __("PPM Options", "ppm-woo"),
    __("PPM Options", "ppm-woo"),
    "manage_options",
    "ppm-woo-options",
    "ppm_woo_contents",
  );
}

function ppm_woo_contents() {
  ?>
  <h1>PPM Fulfillment Options</h1>
  <form method="POST" action="options.php">
  <?php
  settings_fields("ppm-woo-options");
  do_settings_sections("ppm-woo-options");
  submit_button();
  ?>
  </form>
  <?php
}

function ppm_woo_settings_init() {
  add_settings_section(
    "ppm_woo_settings_section",
    __("PPM Configuration", "ppm-woo"),
    "ppm_settings_section_callback_function",
    "ppm-woo-options"
  );

  add_settings_field(
    "ppm_woo_api_key",
    __("PPM Fulfillment API Key", "ppm-woo"),
    "ppm_woo_api_key_markup",
    "ppm-woo-options",
    "ppm_woo_settings_section"
  );
  add_settings_field(
    "ppm_woo_owner_code",
    __("PPM Fulfillment Owner Code", "ppm-woo"),
    "ppm_woo_owner_code_markup",
    "ppm-woo-options",
    "ppm_woo_settings_section"
  );
  add_settings_field(
    "ppm_woo_api_url",
    __("PPM Fulfillment API URL", "ppm-woo"),
    "ppm_woo_api_url_markup",
    "ppm-woo-options",
    "ppm_woo_settings_section"
  );

  register_setting("ppm-woo-options", "ppm_woo_api_key");
  register_setting("ppm-woo-options", "ppm_woo_owner_code");
  register_setting("ppm-woo-options", "ppm_woo_api_url");
}

function ppm_settings_section_callback_function() {
  echo "<p>Set your config values for PPM Fulfillment</p>";
}

function ppm_woo_api_key_markup() {
  generic_field_markup("ppm_woo_api_key", "PPM Fulfillment API Key");
}

function ppm_woo_owner_code_markup() {
  generic_field_markup("ppm_woo_owner_code", "PPM Fulfillment Owner Code");
}

function ppm_woo_api_url_markup() {
  generic_field_markup(
    "ppm_woo_api_url", 
    "PPM Fulfillment API URL", 
    "https://portal.ppmfulfillment.com/api/External/ThirdPartyOrders"
  );
}

function generic_field_markup($field_id, $field_name, $default="") {
  ?>
  <label for="<?php echo $field_id; ?>"><?php _e($field_name, "ppm-woo"); ?></label>
  <input 
    type="text" 
    id="<?php echo $field_id; ?>" 
    name="<?php echo $field_id; ?>"
    value="<?php echo get_option($field_id, $default); ?>"
  >
  <?php
}

add_action("admin_menu", "ppm_woo_admin_menu");
add_action("admin_init", "ppm_woo_settings_init");