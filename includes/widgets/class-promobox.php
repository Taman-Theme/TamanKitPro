<?php
/**
 * Elementor Promo Box Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

namespace TamanKitPro\Widgets;

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
 * Promo Box class
 */
class PromoBox extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve promobox widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-promobox';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve promobox widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Promo Box', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve promobox widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-call-to-action';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the promobox widget belongs to.
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
	 * Register promobox widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		/*
		*==================================================================================
		*
		*==================================== CONTENT TAB =================================
		*
		*==================================================================================
		*/

		/**
		 * Content Tab: Content
		 */
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'taman-kit-pro' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Heading', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'heading_html_tag',
			array(
				'label'   => __( 'Heading HTML Tag', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
					'h1'   => __( 'H1', 'taman-kit-pro' ),
					'h2'   => __( 'H2', 'taman-kit-pro' ),
					'h3'   => __( 'H3', 'taman-kit-pro' ),
					'h4'   => __( 'H4', 'taman-kit-pro' ),
					'h5'   => __( 'H5', 'taman-kit-pro' ),
					'h6'   => __( 'H6', 'taman-kit-pro' ),
					'div'  => __( 'div', 'taman-kit-pro' ),
					'span' => __( 'span', 'taman-kit-pro' ),
					'p'    => __( 'p', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'divider_heading_switch',
			array(
				'label'        => __( 'Heading Divider', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'taman-kit-pro' ),
				'label_off'    => __( 'Off', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'heading!' => '',
				),
			)
		);

		$this->add_control(
			'sub_heading',
			array(
				'label'       => __( 'Sub Heading', 'taman-kit-pro' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Sub heading', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'sub_heading_html_tag',
			array(
				'label'   => __( 'Sub Heading HTML Tag', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h5',
				'options' => array(
					'h1'   => __( 'H1', 'taman-kit-pro' ),
					'h2'   => __( 'H2', 'taman-kit-pro' ),
					'h3'   => __( 'H3', 'taman-kit-pro' ),
					'h4'   => __( 'H4', 'taman-kit-pro' ),
					'h5'   => __( 'H5', 'taman-kit-pro' ),
					'h6'   => __( 'H6', 'taman-kit-pro' ),
					'div'  => __( 'div', 'taman-kit-pro' ),
					'span' => __( 'span', 'taman-kit-pro' ),
					'p'    => __( 'p', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'divider_subheading_switch',
			array(
				'label'        => __( 'Sub Heading Divider', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'taman-kit-pro' ),
				'label_off'    => __( 'Off', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'sub_heading!' => '',
				),
			)
		);

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Description', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Enter promo box description', 'taman-kit-pro' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Icon
		 */
		$this->start_controls_section(
			'section_promo_box_icon',
			array(
				'label' => __( 'Icon', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'icon_switch',
			array(
				'label'        => __( 'Show Icon', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'     => __( 'Icon Type', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'icon',
				'options'   => array(
					'icon'  => __( 'Icon', 'taman-kit-pro' ),
					'image' => __( 'Image', 'taman-kit-pro' ),
				),
				'condition' => array(
					'icon_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'            => __( 'Choose', 'taman-kit-pro' ) . ' ' . __( 'Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-gem',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_image',
			array(
				'label'     => __( 'Image', 'taman-kit-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'image_size' and 'image_custom_dimension'.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'image',
				),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'     => __( 'Icon Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'above-title',
				'options'   => array(
					'above-title' => __( 'Above Title', 'taman-kit-pro' ),
					'below-title' => __( 'Below Title', 'taman-kit-pro' ),
				),
				'condition' => array(
					'icon_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

			/**
		 * Content Tab: Button
		 */
		$this->start_controls_section(
			'section_promo_box_button',
			array(
				'label' => __( 'Button', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'button_switch',
			array(
				'label'        => __( 'Button', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'taman-kit-pro' ),
				'label_off'    => __( 'Off', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Get Started', 'taman-kit-pro' ),
				'condition' => array(
					'button_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'taman-kit-pro' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'button_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/*
		*==================================================================================
		*
		*=============================== Style Tab: Style =================================
		*
		*==================================================================================
		*/

		/**
				 * Style Tab: Promo Box
				 */
		$this->start_controls_section(
			'section_promo_box_style',
			array(
				'label' => __( 'Promo Box', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_bg',
				'label'    => __( 'Background', 'taman-kit-pro' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tk-promo-box-bg',
			)
		);

		$this->add_responsive_control(
			'box_height',
			array(
				'label'      => __( 'Height', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box' => 'height: {{SIZE}}{{UNIT}}',
				),
				'separator'  => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'promo_box_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-promo-box-wrap',
			)
		);

		$this->add_control(
			'promo_box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box, {{WRAPPER}} .tk-promo-box-wrap, {{WRAPPER}} .tk-promo-box .tk-promo-box-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Overlay
		 */
		$this->start_controls_section(
			'section_promo_overlay_style',
			array(
				'label' => __( 'Overlay', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_switch',
			array(
				'label'        => __( 'Overlay', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'taman-kit-pro' ),
				'label_off'    => __( 'Off', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'overlay_color',
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .tk-promo-box-overlay',
				'condition' => array(
					'overlay_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Content
		 */
		$this->start_controls_section(
			'section_promo_content_style',
			array(
				'label' => __( 'Content', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'align',
			array(
				'label'       => __( 'Alignment', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .tk-promo-box' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'vertical_align',
			array(
				'label'       => __( 'Vertical Alignment', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'middle',
				'options'     => array(
					'top'    => array(
						'title' => __( 'Top', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tk-promo-box-inner-content'   => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'content_bg',
				'label'     => __( 'Background', 'taman-kit-pro' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .tk-promo-box-inner',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'content_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-promo-box-inner',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_width',
			array(
				'label'      => __( 'Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-wrap' => 'width: {{SIZE}}{{UNIT}}',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Icon
		 */
		$this->start_controls_section(
			'section_promo_box_icon_style',
			array(
				'label'     => __( 'Icon', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'icon_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Icon Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'icon_img_width',
			array(
				'label'      => __( 'Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 500,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-icon img' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'image',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => __( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'icon_color_normal',
			array(
				'label'     => __( 'Icon Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-icon'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .tk-promo-box-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-icon' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-promo-box-icon',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-icon, {{WRAPPER}} .tk-promo-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'       => __( 'Margin', 'taman-kit-pro' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'placeholder' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'selectors'   => array(
					'{{WRAPPER}} .tk-promo-box-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => __( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => __( 'Icon Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tk-promo-box-icon:hover svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-icon:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'hover_animation_icon',
			array(
				'label' => __( 'Icon Animation', 'taman-kit-pro' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Heading
		 */
		$this->start_controls_section(
			'section_promo_box_heading_style',
			array(
				'label' => __( 'Heading', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-promo-box-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Heading Divider Section
		 */
		$this->start_controls_section(
			'section_heading_divider_style',
			array(
				'label'     => __( 'Heading Divider', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_heading_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_heading_type',
			array(
				'label'     => __( 'Divider Type', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'border',
				'options'   => array(
					'border' => __( 'Border', 'taman-kit-pro' ),
					'image'  => __( 'Image', 'taman-kit-pro' ),
				),
				'condition' => array(
					'divider_heading_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_title_image',
			array(
				'label'     => __( 'Image', 'taman-kit-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'image',
				),
			)
		);

		$this->add_control(
			'divider_heading_border_type',
			array(
				'label'     => __( 'Border Type', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'taman-kit-pro' ),
					'double' => __( 'Double', 'taman-kit-pro' ),
					'dotted' => __( 'Dotted', 'taman-kit-pro' ),
					'dashed' => __( 'Dashed', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-heading-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'border',
				),
			)
		);

		$this->add_responsive_control(
			'divider_title_width',
			array(
				'label'      => __( 'Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-heading-divider' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_heading_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_heading_border_weight',
			array(
				'label'      => __( 'Border Weight', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 4,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-heading-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'border',
				),
			)
		);

		$this->add_control(
			'divider_heading_border_color',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-heading-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'border',
				),
			)
		);

		$this->add_responsive_control(
			'divider_title_margin',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-heading-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_heading_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Sub Heading Section
		 */
		$this->start_controls_section(
			'section_subheading_style',
			array(
				'label' => __( 'Sub Heading', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-promo-box-subtitle',
			)
		);

		$this->add_responsive_control(
			'subtitle_margin',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Sub Heading Divider Section
		 */
		$this->start_controls_section(
			'section_subheading_divider_style',
			array(
				'label'     => __( 'Sub Heading Divider', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_subheading_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_subheading_type',
			array(
				'label'     => __( 'Divider Type', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'border',
				'options'   => array(
					'border' => __( 'Border', 'taman-kit-pro' ),
					'image'  => __( 'Image', 'taman-kit-pro' ),
				),
				'condition' => array(
					'divider_subheading_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_subheading_image',
			array(
				'label'     => __( 'Image', 'taman-kit-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'image',
				),
			)
		);

		$this->add_control(
			'divider_subheading_border_type',
			array(
				'label'     => __( 'Border Type', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'taman-kit-pro' ),
					'double' => __( 'Double', 'taman-kit-pro' ),
					'dotted' => __( 'Dotted', 'taman-kit-pro' ),
					'dashed' => __( 'Dashed', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-subheading-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				),
			)
		);

		$this->add_responsive_control(
			'divider_subheading_width',
			array(
				'label'      => __( 'Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-subheading-divider' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_subheading_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_subheading_border_weight',
			array(
				'label'      => __( 'Border Weight', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 4,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-subheading-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				),
			)
		);

		$this->add_control(
			'divider_subheading_border_color',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-subheading-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				),
			)
		);

		$this->add_responsive_control(
			'divider_subheading_margin',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-subheading-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Description Section
		 */
		$this->start_controls_section(
			'section_promo_description_style',
			array(
				'label' => __( 'Description', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-promo-box-content',
			)
		);

		$this->add_responsive_control(
			'content_margin',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 0,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Button
		 */
		$this->start_controls_section(
			'section_promo_box_button_style',
			array(
				'label'     => __( 'Button', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'button_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => __( 'Size', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => __( 'Extra Small', 'taman-kit-pro' ),
					'sm' => __( 'Small', 'taman-kit-pro' ),
					'md' => __( 'Medium', 'taman-kit-pro' ),
					'lg' => __( 'Large', 'taman-kit-pro' ),
					'xl' => __( 'Extra Large', 'taman-kit-pro' ),
				),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-promo-box-button',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-promo-box-button',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-promo-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .tk-promo-box-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-button:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-promo-box-button:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label' => __( 'Animation', 'taman-kit-pro' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .tk-promo-box-button:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

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
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_promobox_icon() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'icon', 'class', array( 'tk-promo-box-icon', 'tk-icon' ) );

		if ( $settings['hover_animation_icon'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['hover_animation_icon'] );
		}

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// Aadd old default.
			$settings['icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		$icon_attributes = $this->get_render_attribute_string( 'icon' );

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		<div class="tk-promo-box-icon-wrap">
			<span <?php echo $this->get_render_attribute_string( 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php if ( 'icon' === $settings['icon_type'] ) { ?>
					<span class="tk-promo-box-icon-inner">
						<?php
						if ( $is_new || $migrated ) {
							Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
						} elseif ( ! empty( $settings['icon'] ) ) {
							?>
							<i <?php echo $this->get_render_attribute_string( 'i' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></i>
							<?php
						}
						?>
					</span>
				<?php } elseif ( 'image' === $settings['icon_type'] ) { ?>
					<?php if ( ! empty( $settings['icon_image']['url'] ) ) { ?>
					<span class="tk-promo-box-icon-inner">
						<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'icon_image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<?php } ?>
				<?php } ?>
			</span>
		</div>
		<?php
	}

	/**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'promo-box', 'class', 'tk-promo-box' );

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_render_attribute( 'heading', 'class', 'tk-promo-box-title' );

		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_render_attribute( 'sub_heading', 'class', 'tk-promo-box-subtitle' );

		$this->add_inline_editing_attributes( 'content', 'none' );
		$this->add_render_attribute( 'content', 'class', 'tk-promo-box-content' );

		$this->add_inline_editing_attributes( 'button_text', 'none' );

		$this->add_render_attribute(
			'button_text',
			'class',
			array(
				'tk-promo-box-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			)
		);

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button_text', $settings['link'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'promo-box' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="tk-promo-box-bg"></div>
			<?php if ( 'yes' === $settings['overlay_switch'] ) { ?>
				<div class="tk-promo-box-overlay"></div>
			<?php } ?>
			<div class="tk-promo-box-wrap">
				<div class="tk-promo-box-inner">
					<div class="tk-promo-box-inner-content">
						<?php
						if ( 'yes' === $settings['icon_switch'] ) {
							if ( 'above-title' === $settings['icon_position'] ) {
								$this->render_promobox_icon();
							}
						}
						?>

						<?php if ( ! empty( $settings['heading'] ) ) { ?>
							<<?php echo $settings['heading_html_tag']; ?> <?php echo $this->get_render_attribute_string( 'heading' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo $this->parse_text_editor( $settings['heading'] );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</<?php echo $settings['heading_html_tag']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php } ?>

						<?php if ( 'yes' === $settings['divider_heading_switch'] ) { ?>
							<div class="tk-promo-box-heading-divider-wrap">
								<div class="tk-promo-box-heading-divider">
									<?php if ( 'image' === $settings['divider_heading_type'] && $settings['divider_title_image']['url'] ) { ?>
										<img src="<?php echo esc_url( $settings['divider_title_image']['url'] ); ?>">
									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php
						if ( 'yes' === $settings['icon_switch'] ) {
							if ( 'below-title' === $settings['icon_position'] ) {
								$this->render_promobox_icon();
							}
						}
						?>

						<?php if ( ! empty( $settings['sub_heading'] ) ) { ?>
							<<?php echo $settings['sub_heading_html_tag']; ?> <?php echo $this->get_render_attribute_string( 'sub_heading' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo $settings['sub_heading']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</<?php echo $settings['sub_heading_html_tag']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php } ?>

						<?php if ( 'yes' === $settings['divider_subheading_switch'] ) { ?>
							<div class="tk-promo-box-subheading-divider-wrap">
								<div class="tk-promo-box-subheading-divider">
									<?php if ( 'image' === $settings['divider_subheading_type'] && $settings['divider_subheading_image']['url'] ) { ?>
										<img src="<?php echo esc_url( $settings['divider_subheading_image']['url'] ); ?>">
									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php if ( ! empty( $settings['content'] ) ) { ?>
							<div <?php echo $this->get_render_attribute_string( 'content' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo $this->parse_text_editor( $settings['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php } ?>
						<?php if ( 'yes' === $settings['button_switch'] ) { ?>
							<?php if ( ! empty( $settings['button_text'] ) ) { ?>
								<div class="tk-promo-box-footer">
									<a <?php echo $this->get_render_attribute_string( 'button_text' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
										<?php echo esc_attr( $settings['button_text'] ); ?>
									</a>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render promo box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			function icon_template() {
				var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
				#>
				<div class="tk-promo-box-icon-wrap">
					<span class="tk-promo-box-icon tk-icon elementor-animation-{{ settings.hover_animation_icon }}">
						<# if ( settings.icon_type == 'icon' ) { #>
							<span class="tk-promo-box-icon-inner">
								<# if ( settings.icon || settings.selected_icon ) { #>
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
								{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ settings.icon }}" aria-hidden="true"></i>
								<# } #>
								<# } #>
							</span>
						<# } else if ( settings.icon_type == 'image' ) { #>
							<span class="tk-promo-box-icon-inner">
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
							</span>
						<# } #>
					</span>
				</div>
				<#
			}
		#>
		<div class="tk-promo-box">
			<div class="tk-promo-box-bg"></div>
			<# if ( settings.overlay_switch == 'yes' ) { #>
				<div class="tk-promo-box-overlay"></div>
			<# } #>
			<div class="tk-promo-box-wrap">
				<div class="tk-promo-box-inner">
					<div class="tk-promo-box-inner-content">
						<# if ( settings.icon_switch == 'yes' ) { #>
							<# if ( settings.icon_position == 'above-title' ) { #>
								<# icon_template(); #>
							<# } #>
						<# }

						if ( settings.heading != '' ) {
							var heading = settings.heading;

							view.addRenderAttribute( 'heading', 'class', 'tk-promo-box-title' );

							view.addInlineEditingAttributes( 'heading' );

							var heading_html = '<' + settings.heading_html_tag + ' ' + view.getRenderAttributeString( 'heading' ) + '>' + heading + '</' + settings.heading_html_tag + '>';

							print( heading_html );
						}

						if ( settings.divider_heading_switch == 'yes' ) { #>
							<div class="tk-promo-box-heading-divider-wrap">
								<div class="tk-promo-box-heading-divider">
									<# if ( settings.divider_heading_type == 'image' ) { #>
										<# if ( settings.divider_title_image.url != '' ) { #>
											<img src="{{ settings.divider_title_image.url }}">
										<# } #>
									<# } #>
								</div>
							</div>
						<# }

						if ( settings.icon_switch == 'yes' ) {
							if ( settings.icon_position == 'below-title' ) {
								icon_template();
							}
						}

						if ( settings.sub_heading != '' ) {
							var sub_heading = settings.sub_heading;

							view.addRenderAttribute( 'sub_heading', 'class', 'tk-promo-box-subtitle' );

							view.addInlineEditingAttributes( 'sub_heading' );

							var sub_heading_html = '<' + settings.sub_heading_html_tag + ' ' + view.getRenderAttributeString( 'sub_heading' ) + '>' + sub_heading + '</' + settings.sub_heading_html_tag + '>';

							print( sub_heading_html );
						} #>

						<# if ( settings.divider_subheading_switch == 'yes' ) { #>
							<div class="tk-promo-box-subheading-divider-wrap">
								<div class="tk-promo-box-subheading-divider">
									<# if ( settings.divider_subheading_type == 'image' ) { #>
										<# if ( settings.divider_subheading_image.url != '' ) { #>
											<img src="{{ settings.divider_subheading_image.url }}">
										<# } #>
									<# } #>
								</div>
							</div>
						<# }

						if ( settings.content != '' ) {
							var content = settings.content;

							view.addRenderAttribute( 'content', 'class', 'tk-promo-box-content' );

							view.addInlineEditingAttributes( 'content' );

							var content_html = '<div' + ' ' + view.getRenderAttributeString( 'content' ) + '>' + content + '</div>';

							print( content_html );
						}

						if ( settings.button_switch == 'yes' ) { #>
							<# if ( settings.button_text != '' ) { #>
								<div class="tk-promo-box-footer">
									<#
										var button_text = settings.button_text;

										view.addRenderAttribute( 'button_text', 'class', [ 'tk-promo-box-button', 'elementor-button', 'elementor-size-' + settings.button_size, 'elementor-animation-' + settings.button_hover_animation ] );

										view.addInlineEditingAttributes( 'button_text' );

										var button_html = '<a href="' + settings.link.url + '"' + ' ' + view.getRenderAttributeString( 'button_text' ) + '>' + button_text + '</a>';

										print( button_html );
									#>
								</div>
							<# } #>
						<# } #>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render promo box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * Remove this after Elementor v3.3.0
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->content_template();
	}

}
