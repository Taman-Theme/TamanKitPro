<?php
/**
 * Pricing table widget output in the editor template
 *
 * @package Taman Kit.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<#
	var buttonClasses = 'tk-pricing-table-button elementor-button elementor-size-' + settings.table_button_size + ' elementor-animation-' + settings.button_hover_animation;

	var $i = 1,
		symbols = {
			dollar: '&#36;',
			euro: '&#128;',
			franc: '&#8355;',
			pound: '&#163;',
			ruble: '&#8381;',
			shekel: '&#8362;',
			baht: '&#3647;',
			yen: '&#165;',
			won: '&#8361;',
			guilder: '&fnof;',
			peso: '&#8369;',
			peseta: '&#8359;',
			lira: '&#8356;',
			rupee: '&#8360;',
			indian_rupee: '&#8377;',
			real: 'R$',
			krona: 'kr'
		},
		symbol = '',
		iconHTML = {},
		iconsHTML = {},
		migrated = {},
		iconsMigrated = {},
		tooltipIconHTML = {};

	if ( settings.currency_symbol ) {
		if ( 'custom' !== settings.currency_symbol ) {
			symbol = symbols[ settings.currency_symbol ] || '';
		} else {
			symbol = settings.currency_symbol_custom;
		}
	}

	if ( settings.currency_format == 'raised' ) {
		var table_price = settings.table_price.toString(),
			price = table_price.split( '.' ),
			intvalue = price[0],
			fraction = price[1];
	} else {
		var intvalue = settings.table_price,
			fraction = '';
	}

	function get_tooltip_attributes( item, toolTipKey ) {
		view.addRenderAttribute( toolTipKey, 'class', 'tk-pricing-table-tooptip' );
		view.addRenderAttribute( toolTipKey, 'data-tooltip', item.tooltip_content );
		view.addRenderAttribute( toolTipKey, 'data-tooltip-position', 'tt-' + settings.tooltip_position );
		view.addRenderAttribute( toolTipKey, 'data-tooltip-size', settings.tooltip_size );

		if ( settings.tooltip_width.size ) {
			view.addRenderAttribute( toolTipKey, 'data-tooltip-width', settings.tooltip_width.size );
		}

		if ( settings.tooltip_animation_in ) {
			view.addRenderAttribute( toolTipKey, 'data-tooltip-animation-in', settings.tooltip_animation_in );
		}

		if ( settings.tooltip_animation_out ) {
			view.addRenderAttribute( toolTipKey, 'data-tooltip-animation-out', settings.tooltip_animation_out );
		}
	}
#>
<div class="tk-pricing-table-container">
	<div class="tk-pricing-table">
		<div class="tk-pricing-table-head">
			<# if ( settings.icon_type != 'none' ) { #>
				<div class="tk-pricing-table-icon-wrap">
					<# if ( settings.icon_type == 'icon' ) { #>
						<# if ( settings.table_icon || settings.select_table_icon ) { #>
							<span class="tk-pricing-table-icon tk-icon">
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.table_icon || migrated ) ) { #>
								{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ settings.table_icon }}" aria-hidden="true"></i>
								<# } #>
							</span>
						<# } #>
					<# } else if ( settings.icon_type == 'image' ) { #>
						<span class="tk-pricing-table-icon tk-pricing-table-icon-image">
							<# if ( settings.icon_image.url != '' ) { #>
								<#
								var image = {
									id: settings.icon_image.id,
									url: settings.icon_image.url,
									size: settings.image_size,
									dimension: settings.image_custom_dimension,
									model: view.getEditModel()
								};
								var image_url = elementor.imagesManager.getImageUrl( image );
								#>
								<img src="{{{ image_url }}}" />
							<# } #>
						</span>
					<# } #>
				</div>
			<# } #>
			<div class="tk-pricing-table-title-wrap">
				<# if ( settings.table_title ) { #>
					<h3 class="tk-pricing-table-title elementor-inline-editing" data-elementor-setting-key="table_title" data-elementor-inline-editing-toolbar="none">
						{{{ settings.table_title }}}
					</h3>
				<# } #>
				<# if ( settings.table_subtitle ) { #>
					<h4 class="tk-pricing-table-subtitle elementor-inline-editing" data-elementor-setting-key="table_subtitle" data-elementor-inline-editing-toolbar="none">
						{{{ settings.table_subtitle }}}
					</h4>
				<# } #>
			</div>
		</div>
		<div class="tk-pricing-table-price-wrap">
			<div class="tk-pricing-table-price">
				<# if ( settings.discount === 'yes' && settings.table_original_price > 0 ) { #>
					<span class="tk-pricing-table-price-original">
						<# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
							{{{ settings.table_original_price + symbol }}}
						<# } else { #>
							{{{ symbol + settings.table_original_price }}}
						<# } #>
					</span>
				<# } #>
				<# if ( ! _.isEmpty( symbol ) && ( 'before' == settings.currency_position || _.isEmpty( settings.currency_position ) ) ) { #>
					<span class="tk-pricing-table-price-prefix">{{{ symbol }}}</span>
				<# } #>
				<span class="tk-pricing-table-price-value">
					<span class="tk-pricing-table-integer-part">
						{{{ intvalue }}}
					</span>
					<# if ( fraction ) { #>
						<span class="tk-pricing-table-after-part">
							{{{ fraction }}}
						</span>
					<# } #>
				</span>
				<# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
					<span class="tk-pricing-table-price-prefix">{{{ symbol }}}</span>
				<# } #>
				<# if ( settings.table_duration ) { #>
					<span class="tk-pricing-table-price-duration elementor-inline-editing" data-elementor-setting-key="table_duration" data-elementor-inline-editing-toolbar="none">
						{{{ settings.table_duration }}}
					</span>
				<# } #>
			</div>
		</div>
		<# if ( settings.table_button_position == 'above' ) { #>
			<div class="tk-pricing-table-button-wrap">
				<#
				if ( settings.table_button_text ) {
				var button_text = settings.table_button_text;

				view.addRenderAttribute( 'table_button_text', 'class', buttonClasses );

				view.addInlineEditingAttributes( 'table_button_text' );

				var button_text_html = '<a ' + 'href="' + settings.link.url + '"' + view.getRenderAttributeString( 'table_button_text' ) + '>' + button_text + '</a>';

				print( button_text_html );
				}
				#>
			</div>
		<# } #>
		<ul class="tk-pricing-table-features">
			<# var i = 1; #>
			<# _.each( settings.table_features, function( item, index ) {
				var featureContentKey = view.getRepeaterSettingKey( 'feature_content_key', 'table_features', index );
				view.addRenderAttribute( featureContentKey, 'class', 'tk-pricing-table-feature-content' );

				var tooltipIconKey = view.getRepeaterSettingKey( 'tooltip_icon_key', 'table_features', index );
				view.addRenderAttribute( tooltipIconKey, 'class', 'tk-pricing-table-tooltip-icon' );

				if ( 'yes' === settings.show_tooltip && item.tooltip_content ) {
					if ( 'text' === settings.tooltip_display_on ) {
						get_tooltip_attributes( item, featureContentKey );
						if ( 'click' === settings.tooltip_trigger ) {
							view.addRenderAttribute( featureContentKey, 'class', 'tk-tooltip-click' );
						}
					} else {
						get_tooltip_attributes( item, tooltipIconKey );
						if ( 'click' === settings.tooltip_trigger ) {
							view.addRenderAttribute( tooltipIconKey, 'class', 'tk-tooltip-click' );
						}
					}
				} #>
				<li class="elementor-repeater-item-{{ item._id }} <# if ( item.exclude == 'yes' ) { #> excluded <# } #>">
					<div {{{ view.getRenderAttributeString( featureContentKey ) }}}>
						<# if ( item.select_feature_icon || item.feature_icon.value ) { #>
							<span class="tk-pricing-table-fature-icon tk-icon">
							<#
								iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.select_feature_icon, { 'aria-hidden': true }, 'i', 'object' );
								iconsMigrated[ index ] = elementor.helpers.isIconMigrated( item, 'select_feature_icon' );
								if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.feature_icon || iconsMigrated[ index ] ) ) { #>
									{{{ iconsHTML[ index ].value }}}
								<# } else { #>
									<i class="{{ item.feature_icon }}" aria-hidden="true"></i>
								<# }
							#>
							</span>
						<# } #>

						<#
							var feature_text = item.feature_text;

							view.addRenderAttribute( 'table_features.' + (i - 1) + '.feature_text', 'class', 'tk-pricing-table-feature-text' );

							view.addInlineEditingAttributes( 'table_features.' + (i - 1) + '.feature_text' );

							var feature_text_html = '<span' + ' ' + view.getRenderAttributeString( 'table_features.' + (i - 1) + '.feature_text' ) + '>' + feature_text + '</span>';

							print( feature_text_html );
						#>

						<#
						if ( 'yes' === settings.show_tooltip && 'icon' === settings.tooltip_display_on && item.tooltip_content ) {
							tooltipIconHTML = elementor.helpers.renderIcon( view, settings.tooltip_icon, { 'aria-hidden': true }, 'i', 'object' );
							var tooltip_icon_html = '<span' + ' ' + view.getRenderAttributeString( tooltipIconKey ) + '>' + tooltipIconHTML.value + '</span>';

							print( tooltip_icon_html );
						}
						#>
					</div>
				</li>
			<# i++ } ); #>
		</ul>
		<div class="tk-pricing-table-footer">
			<#
			if ( settings.table_button_position == 'below' ) {
				if ( settings.table_button_text ) {
				var button_text = settings.table_button_text;

				view.addRenderAttribute( 'table_button_text', 'class', buttonClasses );

				view.addInlineEditingAttributes( 'table_button_text' );

				var button_text_html = '<a ' + 'href="' + settings.link.url + '"' + view.getRenderAttributeString( 'table_button_text' ) + '>' + button_text + '</a>';

				print( button_text_html );
				}
			}

			if ( settings.table_additional_info ) {
			var additional_info_text = settings.table_additional_info;

			view.addRenderAttribute( 'table_additional_info', 'class', 'tk-pricing-table-additional-info' );

			view.addInlineEditingAttributes( 'table_additional_info' );

			var additional_info_text_html = '<div ' + view.getRenderAttributeString( 'table_additional_info' ) + '>' + additional_info_text + '</div>';

			print( additional_info_text_html );
			}
			#>
		</div>
	</div>
	<# if ( settings.show_ribbon == 'yes' && settings.ribbon_title != '' ) { #>
		<div class="tk-pricing-table-ribbon tk-pricing-table-ribbon-{{ settings.ribbon_style }} tk-pricing-table-ribbon-{{ settings.ribbon_position }}">
			<div class="tk-pricing-table-ribbon-inner">
				<div class="tk-pricing-table-ribbon-title">
					<# print( settings.ribbon_title ); #>
				</div>
			</div>
		</div>
	<# } #>
</div>
