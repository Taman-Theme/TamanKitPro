<?php
/**
 * Render flipbox output on the frontend.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Elementor\Utils;

$settings     = $this->get_settings_for_display();
$shadow       = ' ';
$_box_shadows = $settings['elements_box_shadow'];

foreach ( $_box_shadows as $element ) {

	$shadow .= ' ' . $element;
}

extract(
	shortcode_atts(
		array(
			'image'          => array(),
			'link'           => array(),
			'image_size'     => 'full',
			'flip_effect'    => 'flip',
			'flip_direction' => 'right',
			'icon_shape'     => 'circle',
			'icon_view'      => 'default',
			'show_button'    => '',
			'button_text'    => '',
		),
		$settings
	)
);

$class = $this->get_name();

/* #region Flip Box classes */
$widget_class = array( $class, 'flip-box-effect-' . esc_attr( $flip_effect ) );
$icon_class   = array( $class . '__icon-wrapper', 'stratum-view-' . $icon_view );

if ( $flip_effect == 'flip' || $flip_effect == 'slide' || $flip_effect == 'push' ) {
	array_push(
		$widget_class,
		'flip-box-direction-' . esc_attr( $flip_direction )
	);
}

if ( $icon_view != 'default' && $icon_shape == 'circle' || $icon_shape == 'square' ) {
	array_push(
		$icon_class,
		'stratum-shape-' . esc_attr( $icon_shape )
	);
}

$this->add_render_attribute( 'widget', 'class', $widget_class );
$this->add_render_attribute( 'widget', 'class', $shadow );
$this->add_render_attribute( 'icon-wrapper', 'class', $icon_class );
/* #endregion */

$widget_class = $this->get_render_attribute_string( 'widget' );

echo '<div ' . $widget_class . '>';
echo '<div class="' . esc_attr( $class . '__inner' ) . '">';
echo '<div class="' . esc_attr( $class . '__layer ' ) . esc_attr( $class . '__front' ) . '">';
echo '<div class="' . esc_attr( $class . '__layer__overlay' ) . '">';
echo '<div class="' . esc_attr( $class . '__layer__inner' ) . '">';
$graphic = $settings['graphic_element'];

if ( 'icon' === $graphic ) {
	$icon_wrapper = $this->get_render_attribute_string( 'icon-wrapper' );
		$icon     = $settings['selected_icon'];

		echo '<div ' . $icon_wrapper . '>';
		echo '<div class="' . esc_attr( $this->get_name() . '__icon' ) . '">';
		\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
		echo '</div>';
		echo '</div>';

} elseif ( $graphic == 'image' ) {
	$id = $image['id'];

	if ( ! empty( $id ) ) {
		$url    = wp_get_attachment_image_url( $image['id'], $image_size );
		$srcset = wp_get_attachment_image_srcset( $image['id'], $image_size );
	}

	echo '<div class="' . esc_attr( $this->get_name() . '__image' ) . '">';
	echo '<img src="' . ( empty( $id ) ? Utils::get_placeholder_image_src() : esc_url( $url ) ) . ' class="' . esc_attr( $this->get_name() . '__image' ) . ( ! empty( $id ) ? ' wp-image-' . esc_attr( $id ) : '' ) . ( ! empty( $id ) ? ' srcset="' . $srcset : '' ) . '"/>';
	echo '</div>';
}
$title       = $settings['front_title_text'];
$description = $settings['front_description_text'];

echo '<h3 class="' . esc_attr( $class . '__title' ) . '">' . esc_html( $title ) . '</h3>';
echo '<div class="' . esc_attr( $class . '__description' ) . '">' . esc_html( $description ) . '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="' . esc_attr( $class . '__layer ' ) . esc_attr( $class . '__back' ) . '">';
echo '<div class="' . esc_attr( $class . '__layer__overlay' ) . '">';
echo '<div class="' . esc_attr( $class . '__layer__inner' ) . '">';

$title       = $settings['back_title_text'];
$description = $settings['back_description_text'];
echo '<h3 class="' . esc_attr( $class . '__title' ) . '">' . esc_html( $title ) . '</h3>';
echo '<div class="' . esc_attr( $class . '__description' ) . '">' . esc_html( $description ) . '</div>';

if ( ! empty( $button_text ) && $show_button == 'yes' ) {

	$this->add_render_attribute(
		'button',
		'class',
		array(
			'tk-flipbox__button',
		)
	);
	if ( ! empty( $link['url'] ) ) {
		$this->add_link_attributes( 'button', $link );
	}
	$button_class = $this->get_render_attribute_string( 'button' );
	echo '<a ' . ( empty( $link['url'] ) ? "href='#' " : '' ) . $button_class . '>' . esc_html( $button_text ) . '</a>';

}
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
