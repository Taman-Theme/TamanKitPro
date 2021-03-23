<?php
/**
 * Testimonials circle template
 *
 * @package Taman Kit.
 */

use TamanKitPro\Modules\TemplatesPro;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'testimonials-wrap', 'class', 'tk-testimonials-wrap' );

		$this->add_render_attribute( [
			'testimonials' => [
				'class' => [
					'tk-testimonials',
					'tk-testimonials-' . $settings['layout'],
					'tk-testimonials-image-' . $settings['image_position'],
				],
				'data-layout' => $settings['layout'],
			],
			'testimonial' => [
				'class' => [
					'tk-testimonial',
					'tk-testimonial-' . $settings['skin'],
				],
			],
		] );

		if ( 'carousel' === $settings['layout'] || 'slideshow' === $settings['layout'] ) {
			$this->add_render_attribute( 'testimonials', 'class', 'tk-slick-slider' );

			$this->slider_settings();

			$this->add_render_attribute( 'testimonial-wrap', 'class', 'tk-testimonial-slide' );
		} else {
			$this->add_render_attribute( [
				'testimonials' => [
					'class' => 'tk-elementor-grid',
				],
				'testimonial-wrap' => [
					'class' => 'tk-grid-item-wrap',
				],
				'testimonial' => [
					'class' => 'tk-grid-item',
				],
			] );
		}

		if ( 'slideshow' === $settings['layout'] && 'yes' === $settings['thumbnail_nav'] ) {
			if ( 'yes' === $settings['thumbnail_nav_grayscale_normal'] ) {
				$this->add_render_attribute( 'testimonials-wrap', 'class', 'tk-thumb-nav-gray' );
			}
			if ( 'yes' === $settings['thumbnail_nav_grayscale_hover'] ) {
				$this->add_render_attribute( 'testimonials-wrap', 'class', 'tk-thumb-nav-gray-hover' );
			}
			if ( 'yes' === $settings['thumbnail_nav_grayscale_active'] ) {
				$this->add_render_attribute( 'testimonials-wrap', 'class', 'tk-thumb-nav-gray-active' );
			}
		}

		$this->add_render_attribute( 'testimonial-outer', 'class', 'tk-testimonial-outer' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'testimonials-wrap' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'testimonials' ); ?>>
				<?php foreach ( $settings['testimonials'] as $index => $item ) : ?>
					<div <?php echo $this->get_render_attribute_string( 'testimonial-wrap' ); ?>>
						<div <?php echo $this->get_render_attribute_string( 'testimonial-outer' ); ?>>
							<div <?php echo $this->get_render_attribute_string( 'testimonial' ); ?>>
								<?php
								switch ( $settings['skin'] ) {
									case 'skin-2':
										$this->render_testimonial_skin_2( $item, $index );
										break;
									case 'skin-3':
										$this->render_testimonial_skin_3( $item, $index );
										break;
									case 'skin-4':
										$this->render_testimonial_skin_4( $item, $index );
										break;
									case 'skin-5':
									case 'skin-6':
									case 'skin-7':
										$this->render_testimonial_skin_5( $item, $index );
										break;
									case 'skin-8':
										$this->render_testimonial_skin_8( $item, $index );
										break;
									default:
										$this->render_testimonial_default( $item, $index );
										break;
								}
								?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			if ( 'slideshow' === $settings['layout'] && 'yes' === $settings['thumbnail_nav'] ) {
				$this->render_thumbnails();
			}
			?>
		</div>
