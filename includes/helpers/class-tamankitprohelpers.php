<?php
/**
 * Class TamanKitProHelpers
 *
 * @package Taman Kit Pro.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TamanKitProHelpers' ) ) {
	/**
	 * Helpers Class for Taman Kit
	 *
	 * @since 1.0.0
	 */
	class TamanKitProHelpers {

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @static
		 *
		 * @var TamanKitProHelpers The single instance of the class.
		 */
		private static $instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 * @static
		 *
		 * @return TamanKitProHelpers  An instance of the class.
		 */
		public static function instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;

		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function __construct() {

			$this->initialize_hooks();

			if ( $this->is_active( 'elementor.php' ) ) {

				add_action( 'wp_enqueue_scripts', array( $this, 'register_style' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
			}

		}


		/**
		 * Initialize the plugin.
		 *
		 * @since 1.0.0
		 */
		public function initialize_hooks() {

			add_action(
				'tamankitpro_initialize_hooks',
				function() {
					$this->load_files(
						array(
							'includes/helpers/helpers',
							'/includes/modules/class-templatespro',
						)
					);
				}
			);

			do_action( 'tamankitpro_initialize_hooks' );
		}

		/**
		 * Taman Kit Ver.
		 *
		 * @since 1.0.0
		 */
		public function taman_kit_pro_ver() {
			$ver = TAMAN_KIT_PRO_VER;
			$dev = time();
			return $dev;
		}


		/**
		 * Registers a stylesheets.
		 *
		 * @since 1.0.0
		 */
		public function register_style() {

			wp_register_style( 'taman-kit-pro-style', TAMAN_KIT_PRO_URL . 'public/css/style.css', array(), $this->taman_kit_pro_ver(), 'all' );

			wp_enqueue_style( 'taman-kit-pro-style' );

		}

		/**
		 * Registers a scripts.
		 *
		 * @since 1.0.0
		 */
		public function register_scripts() {

			wp_register_script( 'taman-kit-pro', TAMAN_KIT_PRO_URL . 'public/js/taman-kit.js', array(), self::taman_kit_pro_ver(), true );

		}


		/**
		 * Check if a plugin is active
		 *
		 * @param string $plugin_main_file main file of the plugin, eg. woocommerce.php.
		 * @return bool True if plugin is active, false otherwise.
		 */
		public static function is_active( $plugin_main_file ) {

			// get the list of plugins.
			$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

			// escape characters that have special meaning in regex.
			$plugin_main_file    = preg_quote( $plugin_main_file, '/' );
			$is_plugin_installed = false;

			// Loop through the active plugins.
			foreach ( $active_plugins as $plugin ) {
				if ( preg_match( '/.+\/' . $plugin_main_file . '/', $plugin ) ) {
					$is_plugin_installed = true;
					break;
				}
			}

			return $is_plugin_installed;
		}

		/**
		 * Check if a plugin is network active
		 *
		 * @param string $plugin_main_file main file of the plugin, eg. woocommerce.php.
		 * @return bool True if plugin is active, false otherwise.
		 */
		public static function is_network_active( $plugin_main_file ) {

			// if not a multisite, don't check.
			if ( ! is_multisite() ) {
				return false;
			}

			// get the list of plugins.
			$active_plugins = get_site_option( 'active_sitewide_plugins' );

			// escape characters that have special meaning in regex.
			$plugin_main_file = preg_quote( $plugin_main_file, '/' );
			$is_plugin_active = false;

			// Loop through the active plugins.
			foreach ( $active_plugins as $plugin_name => $plugin_activation ) {
				if ( preg_match( '/.+\/' . $plugin_main_file . '/', $plugin_name ) ) {
					$is_plugin_active = true;
					break;
				}
			}

			return $is_plugin_active;
		}

		/**
		 * Check if a must use (mu) plugin exists.
		 *
		 * Mu plugins are always active. So there's no need to check if they are.
		 * active or not. We just need to check that they are in the list.
		 *
		 * @param string $plugin_main_file main file of the plugin, eg. woocommerce.php.
		 * @return bool True if plugin matches, false otherwise.
		 */
		public static function is_mu_active( $plugin_main_file ) {
			$_mu_plugins = get_mu_plugins();

			if ( isset( $_mu_plugins[ $plugin_main_file ] ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Loads specified PHP files from the plugin directory.
		 *
		 * @access public
		 *
		 * @param array $file_names PHP files from the plugin directory.
		 */
		public function load_files( $file_names = array() ) {
			foreach ( $file_names as $file_name ) {
				$path = TAMAN_KIT_PRO_DIR . '/' . $file_name . '.php';
				if ( file_exists( $path ) ) {
					require_once $path;
				}
			}
		}


	}

}
