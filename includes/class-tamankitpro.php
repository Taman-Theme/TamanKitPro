<?php
/**
 * Class TamanKitPro
 *
 * @package Taman Kit Pro.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'TamanKitPro' ) ) {
	/**
	 * Main Elementor TamanKit Extension Class
	 *
	 * The main class that initiates and runs the plugin.
	 *
	 * @since 1.0.0
	 */
	class TamanKitPro {

		/**
		 * Current theme template
		 *
		 * @var String
		 */
		public $template;

		/**
		 * Plugin Version
		 *
		 * @since 1.0.0
		 *
		 * @var string The plugin version.
		 */
			const VERSION = '1.0.0';

		/**
		 * Minimum Elementor Version
		 *
		 * @since 1.0.0
		 *
		 * @var string Minimum Elementor version required to run the plugin.
		 */
		const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

		/**
		 * Minimum PHP Version
		 *
		 * @since 1.0.0
		 *
		 * @var string Minimum PHP version required to run the plugin.
		 */
		const MINIMUM_PHP_VERSION = '7.0';

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @static
		 *
		 * @var TamanKitPro The single instance of the class.
		 */
		private static $instance = null;

		/**
		 * Helpers Class for Taman Kit
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 * @var class Helpers Class for Taman Kit.
		 */
		public $taman_kit_helpers;

		/**
		 * Helpers Class for el widgets
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 * @var class Helpers Class for Taman Kit widgets.
		 */
		public $widgets_helpers;

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
		 * @return TamanKitPro An instance of the class.
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
			require_once TAMAN_KIT_PRO_DIR . '/includes/helpers/class-tamankitprohelpers.php';

			if ( class_exists( 'TamanKitProHelpers' ) ) {
				$this->taman_kit_helpers = new TamanKitProHelpers();

			}

			$this->on_plugins_loaded();

		}

		/**
		 * Load Textdomain
		 *
		 * Load plugin localization files.
		 *
		 * Fired by `init` action hook.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function i18n() {

			load_plugin_textdomain( 'taman-kit-pro' );

		}

		/**
		 * On Plugins Loaded
		 *
		 * Checks if Elementor has loaded, and performs some compatibility checks.
		 * If All checks pass, inits the plugin.
		 *
		 * Fired by `plugins_loaded` action hook.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function on_plugins_loaded() {

			if ( $this->is_compatible() ) {
				add_action( 'elementor/init', array( $this, 'init' ) );
			}

		}

		/**
		 * Compatibility Checks
		 *
		 * Checks if the installed version of Elementor meets the plugin's minimum requirement.
		 * Checks if the installed PHP version meets the plugin's minimum requirement.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function is_compatible() {

			// Check if Elementor installed and activated.
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
				return false;
			}

			// Check for required Elementor version.
			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
				return false;
			}

			// Check for required PHP version.
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
				return false;
			}

			return true;

		}

		/**
		 * Initialize the plugin
		 *
		 * Load the plugin only after Elementor (and other plugins) are loaded.
		 * Load the files required to run the plugin.
		 *
		 * Fired by `plugins_loaded` action hook.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init() {

			$this->i18n();
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
			add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'additional_icons' ), 9999999, 1 );

		}

		/**
		 * Init Widgets
		 *
		 * Include widgets files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init_widgets() {

			require_once TAMAN_KIT_PRO_DIR . '/includes/helpers/class-tamankitpro-registerwidget.php';

			if ( class_exists( 'TamanKitPro_RegisterWidget' ) ) {
				$this->widgets_helpers = new TamanKitPro_RegisterWidget();
			}
		}

		/**
		 * Init Controls
		 *
		 * Include controls files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init_controls() {

			// Include Control files.
			require_once __DIR__ . '/controls/test-control.php';

			// Register control.
			\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have Elementor installed or activated.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_missing_main_plugin() {

			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification
			}

			$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'taman-kit-pro' ),
				'<strong>' . esc_html__( 'Taman Kit Extension', 'taman-kit-pro' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'taman-kit-pro' ) . '</strong>'
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required Elementor version.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_minimum_elementor_version() {

			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification
			}

			$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'taman-kit-pro' ),
				'<strong>' . esc_html__( 'Taman Kit Extension', 'taman-kit-pro' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'taman-kit-pro' ) . '</strong>',
				self::MINIMUM_ELEMENTOR_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required PHP version.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_minimum_php_version() {

			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification
			}

			$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'taman-kit-pro' ),
				'<strong>' . esc_html__( 'Taman Kit Extension', 'taman-kit-pro' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'taman-kit-pro' ) . '</strong>',
				self::MINIMUM_PHP_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

		}

		/**
		 * Additional icons.
		 *
		 * @param array $icons .
		 */
		public function additional_icons( $icons ) {
			$new_icons = apply_filters( 'taman_kit_pro_custom_icons', array() );

			return array_merge( $icons, $new_icons );
		}


	}
}
