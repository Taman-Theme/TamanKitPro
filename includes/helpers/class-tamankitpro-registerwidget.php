<?php
/**
 * Class TamanKitPro Widget Register helper Class
 *
 * @package taman-kit.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TamanKitPro_RegisterWidget' ) ) {
	/**
	 * TamanKit Widget helper Class
	 *
	 * The main class that initiates and runs the plugin.
	 *
	 * @since 1.0.0
	 */
	class TamanKitPro_RegisterWidget {

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function __construct() {

			$this->initialize_hooks();
		}

		/**
		 * Initialize the plugin.
		 *
		 * @since 1.0.0
		 */
		public function initialize_hooks() {

			do_action( 'tamankitpro_register_widget_hooks' );
		}

		/**
		 * Load Widget.
		 *
		 * @since 1.0.0
		 *
		 * @param array $widgets widgets class Names.
		 */
		public static function register_widget( $widgets = array() ) {

			$widget_manager = \Elementor\Plugin::instance()->widgets_manager;

			if ( is_array( $widgets ) && ! empty( $widgets ) ) {

				foreach ( $widgets as $widget ) {

					require_once TAMAN_KIT_PRO_DIR . '/includes/widgets/class-' . strtolower( $widget ) . '.php';

					$class_name = '\TamanKitPro\Widgets\\' . $widget;
					$widget_manager->register_widget_type( new $class_name() );
				}
			}

		}

	}
}
