<?php
/**
 * Render pricetable widget output on the frontend template
 *
 * @package Taman Kit.
 */

use TamanKitPro\Modules\TemplatesPro;
use Elementor\Icons_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



$settings = $this->get_settings_for_display();
$symbol   = '';

if ( ! empty( $settings['currency_symbol'] ) ) {
	if ( 'custom' !== $settings['currency_symbol'] ) {
		$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
	} else {
		$symbol = $settings['currency_symbol_custom'];
	}
}

if ( ! isset( $settings['table_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
	// Add old default.
	$settings['table_icon'] = 'fa fa-star';
}

		$has_icon = ! empty( $settings['table_icon'] );

if ( $has_icon ) {
	$this->add_render_attribute( 'i', 'class', $settings['table_icon'] );
	$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
}

if ( ! $has_icon && ! empty( $settings['select_table_icon']['value'] ) ) {
	$has_icon = true;
}

$migrated = isset( $settings['__fa4_migrated']['select_table_icon'] );
$is_new   = ! isset( $settings['table_icon'] ) && Icons_Manager::is_migration_allowed();

$this->add_inline_editing_attributes( 'table_title', 'none' );
$this->add_render_attribute( 'table_title', 'class', 'tk-pricing-table-title' );

$this->add_inline_editing_attributes( 'table_subtitle', 'none' );
$this->add_render_attribute( 'table_subtitle', 'class', 'tk-pricing-table-subtitle' );

$this->add_render_attribute( 'table_price', 'class', 'tk-pricing-table-price-value' );

$this->add_inline_editing_attributes( 'table_duration', 'none' );
$this->add_render_attribute( 'table_duration', 'class', 'tk-pricing-table-price-duration' );

$this->add_inline_editing_attributes( 'table_additional_info', 'none' );
$this->add_render_attribute( 'table_additional_info', 'class', 'tk-pricing-table-additional-info' );

$this->add_render_attribute( 'pricing-table', 'class', 'tk-pricing-table' );

$this->add_render_attribute( 'feature-list-item', 'class', '' );

$this->add_inline_editing_attributes( 'table_button_text', 'none' );

$this->add_render_attribute(
	'table_button_text',
	'class',
	array(
		'tk-pricing-table-button',
		'elementor-button',
		'elementor-size-' . $settings['table_button_size'],
	)
);

if ( ! empty( $settings['link']['url'] ) ) {
	$this->add_link_attributes( 'table_button_text', $settings['link'] );
}

$this->add_render_attribute( 'pricing-table-duration', 'class', 'tk-pricing-table-price-duration' );
if ( 'wrap' === $settings['duration_position'] ) {
	$this->add_render_attribute( 'pricing-table-duration', 'class', 'next-line' );
}

if ( $settings['button_hover_animation'] ) {
	$this->add_render_attribute( 'table_button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
}

if ( 'raised' === $settings['currency_format'] ) {
	$price    = explode( '.', $settings['table_price'] );
	$intvalue = $price[0];
	$fraction = '';
	if ( 2 === count( $price ) ) {
		$fraction = $price[1];
	}
} else {
	$intvalue = $settings['table_price'];
	$fraction = '';
}
?>
<div class="tk-pricing-table-container">
	<div <?php echo $this->get_render_attribute_string( 'pricing-table' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="tk-pricing-table-head">
			<?php if ( 'none' !== $settings['icon_type'] ) { ?>
				<div class="tk-pricing-table-icon-wrap">
					<?php if ( 'icon' === $settings['icon_type'] && $has_icon ) { ?>
						<span class="tk-pricing-table-icon tk-icon">
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $settings['select_table_icon'], array( 'aria-hidden' => 'true' ) );
							} elseif ( ! empty( $settings['table_icon'] ) ) {
								?>
								<i <?php echo $this->get_render_attribute_string( 'i' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></i>
								<?php
							}
							?>
						</span>
					<?php } elseif ( 'image' === $settings['icon_type'] ) { ?>
						<?php
						$image = $settings['icon_image'];
						if ( $image['url'] ) {
							?>
							<span class="tk-pricing-table-icon tk-pricing-table-icon-image">
								<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'icon_image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
			<div class="tk-pricing-table-title-wrap">
				<?php if ( $settings['table_title'] ) { ?>
					<h3 <?php echo $this->get_render_attribute_string( 'table_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php echo $settings['table_title']; ?>
					</h3>
				<?php } ?>
				<?php if ( $settings['table_subtitle'] ) { ?>
					<h4 <?php echo $this->get_render_attribute_string( 'table_subtitle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php echo $settings['table_subtitle']; ?>
					</h4>
				<?php } ?>
			</div>
		</div>
		<div class="tk-pricing-table-price-wrap">
			<div class="tk-pricing-table-price">
				<?php if ( 'yes' === $settings['discount'] && $settings['table_original_price'] ) { ?>
					<span class="tk-pricing-table-price-original">
						<?php
						if ( $symbol && 'after' === $settings['currency_position'] ) {
							echo $settings['table_original_price'] . $symbol;
						} else {
							echo $symbol . $settings['table_original_price'];
						}
						?>
					</span>
				<?php } ?>
				<?php if ( $symbol && ( 'before' === $settings['currency_position'] || '' === $settings['currency_position'] ) ) { ?>
					<span class="tk-pricing-table-price-prefix">
						<?php echo $symbol; ?>
					</span>
				<?php } ?>
				<span <?php echo $this->get_render_attribute_string( 'table_price' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<span class="tk-pricing-table-integer-part">
						<?php echo $intvalue; ?>
					</span>
					<?php if ( $fraction ) { ?>
						<span class="tk-pricing-table-after-part">
							<?php echo $fraction; ?>
						</span>
					<?php } ?>
				</span>
				<?php if ( $symbol && 'after' === $settings['currency_position'] ) { ?>
					<span class="tk-pricing-table-price-prefix">
						<?php echo $symbol; ?>
					</span>
				<?php } ?>
				<?php if ( $settings['table_duration'] ) { ?>
					<span <?php echo $this->get_render_attribute_string( 'table_duration' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php echo $settings['table_duration']; ?>
					</span>
				<?php } ?>
			</div>
		</div>
		<?php if ( 'above' === $settings['table_button_position'] ) { ?>
			<div class="tk-pricing-table-button-wrap">
				<?php if ( $settings['table_button_text'] ) { ?>
					<a <?php echo $this->get_render_attribute_string( 'table_button_text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php echo $settings['table_button_text']; ?>
					</a>
				<?php } ?>
			</div>
		<?php } ?>
		<ul class="tk-pricing-table-features">
			<?php foreach ( $settings['table_features'] as $index => $item ) : ?>
				<?php
				$fallback_defaults = array(
					'fa fa-check',
					'fa fa-times',
					'fa fa-dot-circle-o',
				);

				$migration_allowed = Icons_Manager::is_migration_allowed();

				// Add old default.
				if ( ! isset( $item['feature_icon'] ) && ! $migration_allowed ) {
					$item['feature_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
				}

				$migrated = isset( $item['__fa4_migrated']['select_feature_icon'] );
				$is_new   = ! isset( $item['feature_icon'] ) && $migration_allowed;

				$feature_list_key = $this->get_repeater_setting_key( 'feature_list_key', 'table_features', $index );
				$this->add_render_attribute( $feature_list_key, 'class', 'elementor-repeater-item-' . $item['_id'] );

				$feature_content_key = $this->get_repeater_setting_key( 'feature_content_key', 'table_features', $index );
				$this->add_render_attribute( $feature_content_key, 'class', 'tk-pricing-table-feature-content' );

				$tooltip_icon_key = $this->get_repeater_setting_key( 'tooltip_icon_key', 'table_features', $index );
				$this->add_render_attribute( $tooltip_icon_key, 'class', 'tk-pricing-table-tooltip-icon' );

				if ( 'yes' === $settings['show_tooltip'] && $item['tooltip_content'] ) {
					if ( 'text' === $settings['tooltip_display_on'] ) {
						$this->get_tooltip_attributes( $item, $feature_content_key );
						if ( 'click' === $settings['tooltip_trigger'] ) {
							$this->add_render_attribute( $feature_content_key, 'class', 'tk-tooltip-click' );
						}
					} else {
						$this->get_tooltip_attributes( $item, $tooltip_icon_key );
						if ( 'click' === $settings['tooltip_trigger'] ) {
							$this->add_render_attribute( $tooltip_icon_key, 'class', 'tk-tooltip-click' );
						}
					}
				}

				$feature_key = $this->get_repeater_setting_key( 'feature_text', 'table_features', $index );
				$this->add_render_attribute( $feature_key, 'class', 'tk-pricing-table-feature-text' );
				$this->add_inline_editing_attributes( $feature_key, 'none' );

				if ( 'yes' === $item['exclude'] ) {
					$this->add_render_attribute( $feature_list_key, 'class', 'excluded' );
				}
				?>
				<li <?php echo $this->get_render_attribute_string( $feature_list_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div <?php echo $this->get_render_attribute_string( $feature_content_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php
						if ( ! empty( $item['select_feature_icon'] ) || ( ! empty( $item['feature_icon']['value'] ) && $is_new ) ) :
							echo '<span class="tk-pricing-table-fature-icon tk-icon">';
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $item['select_feature_icon'], array( 'aria-hidden' => 'true' ) );
							} else {
								?>
								<i class="<?php echo $item['feature_icon']; ?>" aria-hidden="true"></i>
								<?php
							}
							echo '</span>';
							endif;
						?>
						<?php if ( $item['feature_text'] ) { ?>
							<span <?php echo $this->get_render_attribute_string( $feature_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo $item['feature_text']; ?>
							</span>
						<?php } ?>
						<?php if ( 'yes' === $settings['show_tooltip'] && 'icon' === $settings['tooltip_display_on'] && $item['tooltip_content'] ) { ?>
							<span <?php echo $this->get_render_attribute_string( $tooltip_icon_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php \Elementor\Icons_Manager::render_icon( $settings['tooltip_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
						<?php } ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="tk-pricing-table-footer">
			<?php if ( 'below' === $settings['table_button_position'] ) { ?>
				<?php if ( $settings['table_button_text'] ) { ?>
					<a <?php echo $this->get_render_attribute_string( 'table_button_text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php echo $settings['table_button_text']; ?>
					</a>
				<?php } ?>
			<?php } ?>
			<?php if ( $settings['table_additional_info'] ) { ?>
				<div <?php echo $this->get_render_attribute_string( 'table_additional_info' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php echo $this->parse_text_editor( $settings['table_additional_info'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php if ( 'yes' === $settings['show_ribbon'] && $settings['ribbon_title'] ) { ?>
		<?php
			$classes = array(
				'tk-pricing-table-ribbon',
				'tk-pricing-table-ribbon-' . $settings['ribbon_style'],
				'tk-pricing-table-ribbon-' . $settings['ribbon_position'],
			);
			$this->add_render_attribute( 'ribbon', 'class', $classes );
			?>
		<div <?php echo $this->get_render_attribute_string( 'ribbon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="tk-pricing-table-ribbon-inner">
				<div class="tk-pricing-table-ribbon-title">
					<?php echo $settings['ribbon_title']; ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<?php
