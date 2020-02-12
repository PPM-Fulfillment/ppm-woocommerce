<?php
  /**
   * Plugin Name: PPM Fulfillment - Woo Commerce
   * Plugin URI: https://github.com/PPM-Fulfillment/ppm-woocommerce
   * Description: Fulfill your WooCommerce orders through PPM Fulfillment
   * Author: Andrew Ek
   */

function ppm_settings_init() {
  // Create the options page
  add_action("admin_menu", "ppm_settings_page");

  add_options_page(
    "PPM Settings Page",
    "PPM Settings Page",
    "manage_options",
    "ppm-settings",
    "ppm_settings_page"
  );


  // Create an option for the PPM API Key under the "ppm" namespace
  register_setting("ppm", "ppm_api_key");

  add_settings_section(
    "ppm_config_section",
    "PPM Fulfillment",
    "ppm_config_section_cb",
    "ppm"
  );

  add_settings_field(
    "ppm_api_key",
    "PPM API Key",
    "ppm_api_key_cb",
    "ppm",
    "ppm_config_section"
  );
}

/**
 * Register our Settings at large
 */
add_action("admin_init", "ppm_settings_init");

/**
 * Define our Config Section
 */
function ppm_config_section_cb($args) {
  ?>
  echo "<p>PPM Fulfillment Configuration</p>";
  <?php
}

/**
 * Define our API Key Form HTML
 */
function ppm_api_key_cb() {
  $setting = get_option("ppm_api_key");

  ?>
    <label for="ppm_api_key">
    <input type="text" name="ppm_api_key" value="<?php echo isset($setting) ? esc_attr($setting) : ""; ?>">
  <?php
}
