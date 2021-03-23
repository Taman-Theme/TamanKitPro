<?php
/**
 * Elementor Divider Widget.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

namespace TamanKitPro\Widgets;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Undocumented class
 */
class Divider extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve divider widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'divider';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve divider widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Divider', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve divider widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-divider';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the divider widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'taman-kit' );
	}

	/**
	 * Register divider widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {  // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		/*
		*==================================================================================
		*
		*==================================== CONTENT TAB =================================
		*
		*==================================================================================
		*/

		$this->start_controls_section(
			'section_buton',
			array(
				'label' => esc_html__( 'Divider', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'divider_type',
			array(
				'label'       => esc_html__( 'Type', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'plain' => array(
						'title' => esc_html__( 'Plain', 'taman-kit-pro' ),
						'icon'  => 'fa fa-ellipsis-h',
					),
					'text'  => array(
						'title' => esc_html__( 'Text', 'taman-kit-pro' ),
						'icon'  => 'fa fa-file-text-o',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'taman-kit-pro' ),
						'icon'  => 'fa fa-certificate',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'taman-kit-pro' ),
						'icon'  => 'fa fa-picture-o',
					),
				),
				'default'     => 'plain',
			)
		);

		$this->add_control(
			'divider_direction',
			array(
				'label'     => esc_html__( 'Direction', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => esc_html__( 'Horizontal', 'taman-kit-pro' ),
					'vertical'   => esc_html__( 'Vertical', 'taman-kit-pro' ),
				),
				'condition' => array(
					'divider_type' => 'plain',
				),
			)
		);

		$this->add_control(
			'divider_text',
			array(
				'label'     => esc_html__( 'Text', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Divider Text', 'taman-kit-pro' ),
				'condition' => array(
					'divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'            => esc_html__( 'Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'divider_icon',
				'default'          => array(
					'value'   => 'fas fa-grip-horizontal',
					'library' => 'solid',
				),
				'condition'        => array(
					'divider_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'text_html_tag',
			array(
				'label'     => esc_html__( 'HTML Tag', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'span',
				'options'   => array(
					'h1'   => esc_html__( 'H1', 'taman-kit-pro' ),
					'h2'   => esc_html__( 'H2', 'taman-kit-pro' ),
					'h3'   => esc_html__( 'H3', 'taman-kit-pro' ),
					'h4'   => esc_html__( 'H4', 'taman-kit-pro' ),
					'h5'   => esc_html__( 'H5', 'taman-kit-pro' ),
					'h6'   => esc_html__( 'H6', 'taman-kit-pro' ),
					'div'  => esc_html__( 'div', 'taman-kit-pro' ),
					'span' => esc_html__( 'span', 'taman-kit-pro' ),
					'p'    => esc_html__( 'p', 'taman-kit-pro' ),
				),
				'condition' => array(
					'divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'divider_image',
			array(
				'label'     => esc_html__( 'Image', 'taman-kit-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'divider_type' => 'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'divider_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		if ( ! taman_kit_is_active() ) {
			$this->start_controls_section(
				'section_upgrade_tamankit',
				array(
					'label' => taman_kit_massage_active( 'title' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'upgrade_tamankit_notice',
				array(
					'label'           => '',
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => taman_kit_massage_active( 'massage' ),
					'content_classes' => 'upgrade-tamankit-notice elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->end_controls_section();
		}

		/*
		*==================================================================================
		*
		*=============================== Style Tab: Styling =================================
		*
		*==================================================================================
		*/
		$this->start_controls_section(
			'section_divider_style',
			array(
				'label' => esc_html__( 'Divider', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'divider_vertical_align',
			array(
				'label'                => esc_html__( 'Vertical Alignment', 'taman-kit-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'default'              => 'middle',
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .divider-text-wrap' => 'align-items: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'condition'            => array(
					'divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => esc_html__( 'Style', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'taman-kit-pro' ),
					'dashed' => esc_html__( 'Dashed', 'taman-kit-pro' ),
					'dotted' => esc_html__( 'Dotted', 'taman-kit-pro' ),
					'double' => esc_html__( 'Double', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-divider, {{WRAPPER}} .divider-border' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'horizontal_height',
			array(
				'label'          => esc_html__( 'Height', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 60,
					),
				),
				'default'        => array(
					'size' => 1,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-divider.horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-divider.tk-divider-horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border'        => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
				'conditions'     => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'divider_type',
									'operator' => '==',
									'value'    => 'plain',
								),
								array(
									'name'     => 'divider_direction',
									'operator' => '==',
									'value'    => 'horizontal',
								),
							),
						),
						array(
							'name'     => 'divider_type',
							'operator' => '!=',
							'value'    => 'plain',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'vertical_height',
			array(
				'label'          => esc_html__( 'Height', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'        => array(
					'size' => 80,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-divider.vertical' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-divider.tk-divider-vertical' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border'      => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'divider_type'      => 'plain',
					'divider_direction' => 'vertical',
				),
			)
		);

		$this->add_responsive_control(
			'horizontal_width',
			array(
				'label'          => esc_html__( 'Width', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 1200,
					),
				),
				'default'        => array(
					'size' => 300,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-divider.horizontal'  => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-divider.tk-divider-horizontal' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-text-container' => 'width: {{SIZE}}{{UNIT}};',
				),
				'conditions'     => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'divider_type',
									'operator' => '==',
									'value'    => 'plain',
								),
								array(
									'name'     => 'divider_direction',
									'operator' => '==',
									'value'    => 'horizontal',
								),
							),
						),
						array(
							'name'     => 'divider_type',
							'operator' => '!=',
							'value'    => 'plain',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'vertical_width',
			array(
				'label'          => esc_html__( 'Width', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'        => array(
					'size' => 3,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-divider.vertical'    => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-divider.tk-divider-vertical' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-text-container' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'divider_type'      => 'plain',
					'divider_direction' => 'vertical',
				),
			)
		);

		$this->add_control(
			'divider_border_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-divider, {{WRAPPER}} .divider-border' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'divider_type' => 'plain',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_before_after_style' );

		$this->start_controls_tab(
			'tab_before_style',
			array(
				'label'     => esc_html__( 'Before', 'taman-kit-pro' ),
				'condition' => array(
					'divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'divider_before_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'divider_type!' => 'plain',
				),
				'selectors' => array(
					'{{WRAPPER}} .divider-border-left .divider-border' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_after_style',
			array(
				'label'     => esc_html__( 'After', 'taman-kit-pro' ),
				'condition' => array(
					'divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'divider_after_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'divider_type!' => 'plain',
				),
				'selectors' => array(
					'{{WRAPPER}} .divider-border-right .divider-border' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Text
		 */
		$this->start_controls_section(
			'section_text_style',
			array(
				'label'     => esc_html__( 'Text', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'text_position',
			array(
				'label'        => esc_html__( 'Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'center',
				'prefix_class' => 'tk-divider-',
			)
		);

		$this->add_control(
			'divider_text_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'divider_type' => 'text',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-divider-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography',
				'label'     => esc_html__( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .tk-divider-text',
				'condition' => array(
					'divider_type' => 'text',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'divider_text_shadow',
				'selector' => '{{WRAPPER}} .tk-divider-text',
			)
		);

		$this->add_responsive_control(
			'text_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'condition'  => array(
					'divider_type' => 'text',
				),
				'selectors'  => array(
					'{{WRAPPER}}.tk-divider-center .tk-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tk-divider-left .tk-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tk-divider-right .tk-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Icon
		 */
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'     => esc_html__( 'Icon', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'        => esc_html__( 'Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'center',
				'prefix_class' => 'tk-divider-',
			)
		);

		$this->add_control(
			'divider_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'divider_type' => 'icon',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-divider-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .tk-divider-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'condition'  => array(
					'divider_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-divider-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_rotation',
			array(
				'label'          => esc_html__( 'Icon Rotation', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 360,
					),
				),
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-divider-icon .fa' => 'transform: rotate( {{SIZE}}deg );',
				),
				'condition'      => array(
					'divider_type' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'condition'  => array(
					'divider_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}}.tk-divider-center .tk-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tk-divider-left .tk-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tk-divider-right .tk-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Image
		 */
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => esc_html__( 'Image', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_type' => 'image',
				),
			)
		);

		$this->add_control(
			'image_position',
			array(
				'label'        => esc_html__( 'Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'center',
				'prefix_class' => 'tk-divider-',
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'          => esc_html__( 'Width', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 1200,
					),
				),
				'default'        => array(
					'size' => 80,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'condition'      => array(
					'divider_type' => 'image',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-divider-image' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'divider_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-divider-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'condition'  => array(
					'divider_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}}.tk-divider-center .tk-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tk-divider-left .tk-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tk-divider-right .tk-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/*
	*==================================================================================
	*
	*=============================== Widget Output ====================================
	*
	*==================================================================================
	*/

	/**
	 * Render divider widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		$classes = array( 'tk-divider' );

		if ( $settings['divider_direction'] ) {
			$classes[] = 'tk-divider-' . $settings['divider_direction'];
			$classes[] = $settings['divider_direction'];
		}

		if ( $settings['divider_style'] ) {
			$classes[] = 'tk-divider-' . $settings['divider_style'];
			$classes[] = $settings['divider_style'];
		}

		$this->add_render_attribute( 'divider', 'class', $classes );

		$this->add_render_attribute( 'divider-content', 'class', array( 'tk-divider-' . $settings['divider_type'], 'tk-icon' ) );

		$this->add_inline_editing_attributes( 'divider_text', 'none' );
		$this->add_render_attribute( 'divider_text', 'class', 'tk-divider-' . $settings['divider_type'] );

		if ( 'icon' === $settings['divider_type'] ) {
			if ( ! isset( $settings['divider_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
				// Add old default.
				$settings['divider_icon'] = 'fa fa-gem';
			}

			$has_icon = ! empty( $settings['divider_icon'] );

			if ( $has_icon ) {
				$this->add_render_attribute( 'i', 'class', $settings['divider_icon'] );
				$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
			}

			$icon_attributes = $this->get_render_attribute_string( 'divider_icon' );

			if ( ! $has_icon && ! empty( $settings['icon']['value'] ) ) {
				$has_icon = true;
			}
			$migrated = isset( $settings['__fa4_migrated']['icon'] );
			$is_new   = ! isset( $settings['divider_icon'] ) && Icons_Manager::is_migration_allowed();
		}
		?>
		<div class="tk-divider-wrap">
			<?php
			if ( 'plain' === $settings['divider_type'] ) {
				?>
				<div <?php echo $this->get_render_attribute_string( 'divider' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>
				<?php
			} else {
				?>
				<div class="divider-text-container">
					<div class="divider-text-wrap">
						<span class="tk-divider-border-wrap divider-border-left">
							<span class="divider-border"></span>
						</span>
						<span class="tk-divider-content">
							<?php if ( 'text' === $settings['divider_type'] && $settings['divider_text'] ) { ?>
								<?php
									printf( '<%1$s %2$s>%3$s</%1$s>', $settings['text_html_tag'], $this->get_render_attribute_string( 'divider_text' ), $settings['divider_text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							<?php } elseif ( 'icon' === $settings['divider_type'] ) { ?>
								<?php if ( ! empty( $settings['divider_icon'] ) || ( ! empty( $settings['icon']['value'] ) && $is_new ) ) { ?>
									<span <?php echo $this->get_render_attribute_string( 'divider-content' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );
										} elseif ( ! empty( $settings['divider_icon'] ) ) {
											?>
												<i <?php echo $this->get_render_attribute_string( 'i' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></i>
												<?php
										}
										?>
									</span>
								<?php } ?>
							<?php } elseif ( 'image' === $settings['divider_type'] ) { ?>
								<span <?php echo $this->get_render_attribute_string( 'divider-content' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
									<?php
										$image = $settings['divider_image'];
									if ( $image['url'] ) {
										echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'divider_image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
									?>
								</span>
							<?php } ?>
						</span>
						<span class="tk-divider-border-wrap divider-border-right">
							<span class="divider-border"></span>
						</span>
					</div>
				</div>
				<?php
			}
			?>
		</div>    
		<?php
	}

	/**
	 * Render divider widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = elementor.helpers.isIconMigrated( settings, 'icon' );   
		#>
		<div class="tk-divider-wrap">
			<# if ( settings.divider_type == 'plain' ) { #>
				<div class="tk-divider tk-divider-{{ settings.divider_direction }} {{ settings.divider_direction }} tk-divider-{{ settings.divider_style }} {{ settings.divider_style }} "></div>
			<# } else { #>
				<div class="divider-text-container">
					<div class="divider-text-wrap">
						<span class="tk-divider-border-wrap divider-border-left">
							<span class="divider-border"></span>
						</span>
						<span class="tk-divider-content">
							<# if ( settings.divider_type == 'text' && settings.divider_text != '' ) { #>
								<{{ settings.text_html_tag }} class="tk-divider-{{ settings.divider_type }} elementor-inline-editing" data-elementor-setting-key="divider_text" data-elementor-inline-editing-toolbar="none">
									{{ settings.divider_text }}
								</{{ settings.text_html_tag }}>
							<# } else if ( settings.divider_type == 'icon' && settings.divider_icon != '' ) { #>
								<span class="tk-divider-{{ settings.divider_type }} tk-icon">
									<# if ( settings.divider_icon || settings.icon ) { #>
									<# if ( iconHTML && iconHTML.rendered && ( ! settings.divider_icon || migrated ) ) { #>
									{{{ iconHTML.value }}}
									<# } else { #>
										<i class="{{ settings.divider_icon }}" aria-hidden="true"></i>
									<# } #>
									<# } #>
								</span>
							<# } else if ( settings.divider_type == 'image' ) { #>
								<span class="tk-divider-{{ settings.divider_type }}">
									<#
									var image = {
										id: settings.divider_image.id,
										url: settings.divider_image.url,
										size: settings.image_size,
										dimension: settings.image_custom_dimension,
										model: view.getEditModel()
									};
									var image_url = elementor.imagesManager.getImageUrl( image );
									#>
									<img src="{{{ image_url }}}" />
								</span>
							<# } #>
						</span>
						<span class="tk-divider-border-wrap divider-border-right">
							<span class="divider-border"></span>
						</span>
					</div>
				</div>
			<# } #>
		</div>
		<?php

	}

}
