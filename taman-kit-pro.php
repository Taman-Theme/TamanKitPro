<?php
/**
 * Plugin Name:       Taman Kit Pro
 * Plugin URI:        https://#
 * Description:       Premium addons for Elementor page builder.
 * Version:           1.0.0
 * Author:            Mohamed Taman
 * Author URI:        https://profiles.wordpress.org/mohamedtaman
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       taman-kit-pro
 * Domain Path:       /languages
 *
 * @package taman-kit.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TAMAN_KIT_PRO_VER', '1.0.0' );
define( 'TAMAN_KIT_PRO_DIR', plugin_dir_path( __FILE__ ) );
define( 'TAMAN_KIT_PRO_BASE', plugin_basename( __FILE__ ) );
define( 'TAMAN_KIT_PRO_URL', plugins_url( '/', __FILE__ ) );


require_once TAMAN_KIT_PRO_DIR . '/includes/class-tamankitpro.php';
/**
 * Load the Plugin Class.
 */
function taman_kit_pro_init() {
	TamanKitPro::instance();
}

add_action( 'plugins_loaded', 'taman_kit_pro_init' );
