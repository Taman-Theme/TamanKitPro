<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<svg class="circle-progress" width="<?php echo esc_attr( $size ); ?>" height="<?php echo esc_attr( $size ); ?>" viewBox="<?php echo esc_attr( $viewbox ); ?>" data-radius="<?php echo esc_attr( $radius ); ?>" data-circumference="<?php echo esc_attr( $circumference ); ?>" data-responsive-sizes="<?php echo esc_attr( json_encode( $responsive_sizes ) ); ?>">
	<linearGradient id="circle-progress-meter-gradient-<?php echo esc_attr( $this->get_id() ); ?>" gradientUnits="objectBoundingBox" gradientTransform="rotate(<?php echo $val_bg_gradient_angle; ?> 0.5 0.5)" x1="-0.25" y1="0.5" x2="1.25" y2="0.5">
		<stop offset="0%" stop-color="<?php echo esc_attr( $settings['val_bg_gradient_color_a'] ); ?>"/>
		<stop offset="100%" stop-color="<?php echo esc_attr( $settings['val_bg_gradient_color_b'] ); ?>"/>
	</linearGradient>
	<linearGradient id="circle-progress-value-gradient-<?php echo esc_attr( $this->get_id() ); ?>" gradientUnits="objectBoundingBox" gradientTransform="rotate(<?php echo $val_stroke_gradient_angle; ?> 0.5 0.5)" x1="-0.25" y1="0.5" x2="1.25" y2="0.5">
		<stop offset="0%" stop-color="<?php echo esc_attr( $settings['val_stroke_gradient_color_a'] ); ?>"/>
		<stop offset="100%" stop-color="<?php echo esc_attr( $settings['val_stroke_gradient_color_b'] ); ?>"/>
	</linearGradient>
	<circle
		class="circle-progress__meter"
		cx="<?php echo esc_attr( $center ); ?>"
		cy="<?php echo esc_attr( $center ); ?>"
		r="<?php echo esc_attr( $radius ); ?>"
		stroke="<?php echo esc_attr( $meter_stroke ); ?>"
		stroke-width="<?php echo esc_attr( $bg_stroke ); ?>"
		fill="none"
	/>
	<circle
		class="circle-progress__value"
		cx="<?php echo esc_attr( $center ); ?>"
		cy="<?php echo esc_attr( $center ); ?>"
		r="<?php echo esc_attr( $radius ); ?>"
		stroke="<?php echo esc_attr( $value_stroke ); ?>"
		stroke-width="<?php echo esc_attr( $val_stroke ); ?>"
		data-value="<?php echo esc_attr( $value ); ?>"
		style="stroke-dasharray: <?php echo esc_attr( $circumference ); ?>; stroke-dashoffset: <?php echo esc_attr( $circumference ); ?>;"
		fill="none"
	/>
</svg>
