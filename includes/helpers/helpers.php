<?php
/**
 * Plugin Helper Functions.
 *
 * @package Taman Kit Pro
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Undocumented function
 */
function taman__kit_pro_enqueue_scripts() {
	wp_register_script( 'lottie-js', TAMAN_KIT_PRO_URL . 'public/js/lottie.js', array(), TamanKitProHelpers::taman_kit_pro_ver(), true );
	wp_register_script( 'jquery-countdown', TAMAN_KIT_PRO_URL . 'public/js/jquery-countdown.js', array(), TamanKitProHelpers::taman_kit_pro_ver(), true );
	wp_register_script( 'anime-js', TAMAN_KIT_PRO_URL . 'public/js/anime.min.js', array(), TamanKitProHelpers::taman_kit_pro_ver(), true );
	wp_register_script( 'jquery-event-move', TAMAN_KIT_PRO_URL . 'public/js/jquery.event.move.js', array(), TamanKitProHelpers::taman_kit_pro_ver(), true );
	wp_register_script( 'tk-imgcompare', TAMAN_KIT_PRO_URL . 'public/js/imgcompare.js', array(), TamanKitProHelpers::taman_kit_pro_ver(), true );
	wp_register_script( 'tk-tooltip', TAMAN_KIT_PRO_URL . 'public/js/tooltip.js', array(), TamanKitProHelpers::taman_kit_pro_ver(), true );
	wp_register_script( 'jquery-resize', TAMAN_KIT_PRO_URL . 'public/js/jquery-resize/jquery.resize.min.js', array(), TamanKitProHelpers::taman_kit_pro_ver(), true );
	wp_register_script( 'tk-slick', TAMAN_KIT_PRO_URL . 'public/js/slick/slick.min.js', array(), TamanKitProHelpers::taman_kit_pro_ver(), true );

}
add_action( 'wp_enqueue_scripts', 'taman__kit_pro_enqueue_scripts' );


if ( TamanKitProHelpers::is_active( 'elementor.php' ) ) {

	add_action(
		'tamankitpro_register_widget_hooks',
		function() {
			$widget = array(
				'FlipBox',
				'Testimonials',
				'InfoBox',
				'BusinessHours',
				'PromoBox',
				'FancyHeading',
				'TeamMember',
				'Video',
			);

			$widget[] = 'Circleprogress';
			$widget[] = 'Progress';
			$widget[] = 'Instagram';
			$widget[] = 'Contactform7';
			$widget[] = 'Divider';
			$widget[] = 'Counter';
			$widget[] = 'CountDown';
			$widget[] = 'ImageComparison';
			$widget[] = 'Pricetable';

			TamanKitPro_RegisterWidget::register_widget( $widget );
		}
	);


}
