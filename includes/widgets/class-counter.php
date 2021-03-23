<?php
/**
 * Elementor counter Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

namespace TamanKitPro\Widgets;

// Elementor Classes.
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Scheme_Color;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Undocumented class
 */
class Counter extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve counter widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk_counter';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve counter widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Counter', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve counter widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-counter';
	}


	/**
	 * Script Depends
	 */
	public function get_script_depends() {
		return array(
			'jquery-numerator',
			'elementor-waypoints',
			'lottie-js',
			'taman-kit-pro',
		);
	}


	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the counter widget belongs to.
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
	 * Register counter widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		/*
		*==================================================================================
		*
		*==================================== CONTENT TAB =================================
		*
		*==================================================================================
		*/
		$this->start_controls_section(
			'tk_counter_global_settings',
			array(
				'label' => esc_html__( 'Counter', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_counter_title',
			array(
				'label'       => esc_html__( 'Title', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Enter title for stats counter block', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_counter_start_value',
			array(
				'label'   => esc_html__( 'Starting Number', 'taman-kit-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'dynamic' => array( 'active' => true ),
				'default' => 0,
			)
		);

		$this->add_control(
			'tk_counter_end_value',
			array(
				'label'   => esc_html__( 'Ending Number', 'taman-kit-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'dynamic' => array( 'active' => true ),
				'default' => 500,
			)
		);

		$this->add_control(
			'tk_counter_t_separator',
			array(
				'label'       => esc_html__( 'Thousands Separator', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Separator converts 125000 into 125,000', 'taman-kit-pro' ),
				'default'     => ',',
			)
		);

		$this->add_control(
			'tk_counter_d_after',
			array(
				'label'   => esc_html__( 'Digits After Decimal Point', 'taman-kit-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
			)
		);

		$this->add_control(
			'tk_counter_preffix',
			array(
				'label'       => esc_html__( 'Value Prefix', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Enter prefix for counter value', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_counter_suffix',
			array(
				'label'       => esc_html__( 'Value Suffix', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Enter suffix for counter value', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_counter_speed',
			array(
				'label'       => esc_html__( 'Rolling Time', 'taman-kit-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'How long should it take to complete the digit?', 'taman-kit-pro' ),
				'default'     => 3,
			)
		);

		$this->add_responsive_control(
			'counter_align',
			array(
				'label'     => esc_html__( 'Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .tk-counter:not(.top)' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}} .tk-counter.top'       => 'align-items: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_counter_display_options',
			array(
				'label' => esc_html__( 'Display Options', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_counter_icon_image',
			array(
				'label'       => esc_html__( 'Icon Type', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Use a font awesome icon or upload a custom image', 'taman-kit-pro' ),
				'options'     => array(
					'icon'      => esc_html__( 'Font Awesome', 'taman-kit-pro' ),
					'custom'    => esc_html__( 'Image', 'taman-kit-pro' ),
					'animation' => esc_html__( 'Lottie Animation', 'taman-kit-pro' ),
				),
				'default'     => 'icon',
			)
		);

		$this->add_control(
			'tk_counter_icon_updated',
			array(
				'label'            => esc_html__( 'Select an Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'tk_counter_icon',
				'default'          => array(
					'value'   => 'fas fa-clock',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'tk_counter_icon_image' => 'icon',
				),
			)
		);

		$this->add_control(
			'tk_counter_image_upload',
			array(
				'label'     => esc_html__( 'Upload Image', 'taman-kit-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'tk_counter_icon_image' => 'custom',
				),
			)
		);

		$this->add_control(
			'lottie_url',
			array(
				'label'       => esc_html__( 'Animation JSON URL', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
				'label_block' => true,
				'condition'   => array(
					'tk_counter_icon_image' => 'animation',
				),
			)
		);

		$this->add_control(
			'lottie_loop',
			array(
				'label'        => esc_html__( 'Loop', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'tk_counter_icon_image' => 'animation',
				),
			)
		);

		$this->add_control(
			'lottie_reverse',
			array(
				'label'        => esc_html__( 'Reverse', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition'    => array(
					'tk_counter_icon_image' => 'animation',
				),
			)
		);

		$this->add_control(
			'tk_counter_icon_position',
			array(
				'label'       => esc_html__( 'Icon Position', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Choose a position for your icon', 'taman-kit-pro' ),
				'default'     => 'no-animation',
				'options'     => array(
					'top'   => esc_html__( 'Top', 'taman-kit-pro' ),
					'right' => esc_html__( 'Right', 'taman-kit-pro' ),
					'left'  => esc_html__( 'Left', 'taman-kit-pro' ),
				),
				'default'     => 'top',
				'separator'   => 'after',
			)
		);

		$this->add_control(
			'tk_counter_icon_animation',
			array(
				'label'       => esc_html__( 'Animations', 'taman-kit-pro' ),
				'type'        => Controls_Manager::ANIMATION,
				'render_type' => 'template',
			)
		);

		$this->add_responsive_control(
			'value_align',
			array(
				'label'     => esc_html__( 'Value Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-value-wrap' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'title_align',
			array(
				'label'     => esc_html__( 'Title Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-title' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'tk_counter_title!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_counter_icon_style_tab',
			array(
				'label' => esc_html__( 'Icon', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tk_counter_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-icon .icon i' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'tk_counter_icon_image' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'tk_counter_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'size' => 70,
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
					'em' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-icon .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'tk_counter_icon_image' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'tk_counter_image_size',
			array(
				'label'      => esc_html__( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 300,
					),
					'em' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-icon img.custom-image' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tk-counter-area .tk-counter-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'tk_counter_icon_image!' => 'icon',
				),
			)
		);

		$this->add_control(
			'tk_counter_icon_style',
			array(
				'label'       => esc_html__( 'Style', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'We are giving you three quick preset if you are in a hurry. Otherwise, create your own with various options', 'taman-kit-pro' ),
				'options'     => array(
					'simple' => esc_html__( 'Simple', 'taman-kit-pro' ),
					'circle' => esc_html__( 'Circle Background', 'taman-kit-pro' ),
					'square' => esc_html__( 'Square Background', 'taman-kit-pro' ),
					'design' => esc_html__( 'Custom', 'taman-kit-pro' ),
				),
				'default'     => 'simple',
			)
		);

		$this->add_control(
			'tk_counter_icon_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'condition' => array(
					'tk_counter_icon_style!' => 'simple',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-icon .icon-bg' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_counter_icon_bg_size',
			array(
				'label'     => esc_html__( 'Background size', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 150,
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 600,
					),
				),
				'condition' => array(
					'tk_counter_icon_style!' => 'simple',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-icon span.icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_counter_icon_v_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 150,
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 600,
					),
				),
				'condition' => array(
					'tk_counter_icon_style!' => 'simple',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-icon span.icon' => 'line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'tk_icon_border',
				'selector'  => '{{WRAPPER}} .tk-counter-area .tk-counter-icon .design',
				'condition' => array(
					'tk_counter_icon_style' => 'design',
				),
			)
		);

		$this->add_control(
			'tk_icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-icon .design' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'tk_counter_icon_style' => 'design',
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_counter_title_style',
			array(
				'label'     => esc_html__( 'Title', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tk_counter_title!' => '',
				),
			)
		);

		$this->add_control(
			'tk_counter_title_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tk_counter_title_typho',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-counter-area .tk-counter-title',
			)
		);

		$this->add_control(
			'title_background',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-title' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'title_border',
				'selector' => '{{WRAPPER}} .tk-counter-title',
			)
		);

		$this->add_control(
			'title_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-title' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'tk_counter_title_shadow',
				'selector' => '{{WRAPPER}} .tk-counter-area .tk-counter-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_counter_value_style',
			array(
				'label' => esc_html__( 'Value', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tk_counter_value_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-area .tk-counter-init' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'tk_counter_value_typho',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .tk-counter-area .tk-counter-init',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'value_background',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-init' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'value_border',
				'selector' => '{{WRAPPER}} .tk-counter-init',
			)
		);

		$this->add_control(
			'value_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-init' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'value_margin',
			array(
				'label'      => esc_html__( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-init' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'value_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-counter-init' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_counter_suffix_prefix_style',
			array(
				'label' => esc_html__( 'Prefix & Suffix', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tk_counter_prefix_color',
			array(
				'label'     => esc_html__( 'Prefix Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-area span#prefix' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'tk_counter_prefix_typo',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .tk-counter-area span#prefix',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'tk_counter_suffix_color',
			array(
				'label'     => esc_html__( 'Suffix Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-counter-area span#suffix' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'tk_counter_suffix_typo',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .tk-counter-area span#suffix',
				'separator' => 'after',
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
	 * Counter content
	 */
	public function get_counter_content() {

		$settings = $this->get_settings_for_display();

		$start_value = $settings['tk_counter_start_value'];

		?>

		<div class="tk-init-wrapper">

			<div class="tk-counter-value-wrap">
				<?php if ( ! empty( $settings['tk_counter_preffix'] ) ) : ?>
					<span id="prefix" class="counter-su-pre"><?php echo $settings['tk_counter_preffix']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>

				<span class="tk-counter-init" id="counter-<?php echo esc_attr( $this->get_id() ); ?>"><?php echo esc_html( $start_value ); ?></span>

				<?php if ( ! empty( $settings['tk_counter_suffix'] ) ) : ?>
					<span id="suffix" class="counter-su-pre"><?php echo $settings['tk_counter_suffix']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $settings['tk_counter_title'] ) ) : ?>
				<h4 class="tk-counter-title">
					<div <?php echo $this->get_render_attribute_string( 'tk_counter_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php echo $settings['tk_counter_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</h4>
			<?php endif; ?>
		</div>

		<?php
	}

	/**
	 * Counter icon
	 */
	public function get_counter_icon() {

		$settings = $this->get_settings_for_display();

		$icon_style = 'simple' !== $settings['tk_counter_icon_style'] ? ' icon-bg ' . $settings['tk_counter_icon_style'] : '';

		$animation = $settings['tk_counter_icon_animation'];

		$icon_type = $settings['tk_counter_icon_image'];

		$flex_width = '';

		if ( 'icon' === $icon_type ) {
			if ( ! empty( $settings['tk_counter_icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['tk_counter_icon'] );
				$this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
			}

			$migrated = isset( $settings['__fa4_migrated']['tk_counter_icon_updated'] );
			$is_new   = empty( $settings['tk_counter_icon'] ) && Icons_Manager::is_migration_allowed();

		} elseif ( 'custom' === $icon_type ) {
			$alt = esc_attr( Control_Media::get_image_alt( $settings['tk_counter_image_upload'] ) );

			$this->add_render_attribute( 'image', 'class', 'custom-image' );
			$this->add_render_attribute( 'image', 'src', $settings['tk_counter_image_upload']['url'] );
			$this->add_render_attribute( 'image', 'alt', $alt );

			if ( 'simple' === $settings['tk_counter_icon_style'] ) {
				$flex_width = ' flex-width ';
			}
		} else {
			$this->add_render_attribute(
				'counter_lottie',
				array(
					'class'               => array(
						'tk-counter-animation',
						'tk-lottie-animation',
					),
					'data-lottie-url'     => $settings['lottie_url'],
					'data-lottie-loop'    => $settings['lottie_loop'],
					'data-lottie-reverse' => $settings['lottie_reverse'],
				)
			);
		}

		?>

		<div class="tk-counter-icon">

			<span class="icon<?php echo esc_attr( $flex_width ); ?><?php echo esc_attr( $icon_style ); ?>" data-animation="<?php echo esc_attr( $animation ); ?>">

				<?php if ( 'icon' === $icon_type && ( ! empty( $settings['tk_counter_icon_updated']['value'] ) || ! empty( $settings['tk_counter_icon'] ) ) ) : ?>
					<?php
					if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $settings['tk_counter_icon_updated'], array( 'aria-hidden' => 'true' ) );
					else :
						?>
						<i <?php echo $this->get_render_attribute_string( 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></i>
					<?php endif; ?>
				<?php elseif ( 'custom' === $icon_type && ! empty( $settings['tk_counter_image_upload']['url'] ) ) : ?>
					<img <?php echo $this->get_render_attribute_string( 'image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php else : ?>
					<div <?php echo $this->get_render_attribute_string( 'counter_lottie' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>
				<?php endif; ?>

			</span>
		</div>

		<?php
	}

	/**
	 * Render counter widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'tk_counter_title' );

		$position = $settings['tk_counter_icon_position'];

		$flex_width = '';
		if ( 'custom' === $settings['tk_counter_icon_image'] && 'simple' === $settings['tk_counter_icon_style'] ) {
			$flex_width = ' flex-width ';
		}

		$this->add_render_attribute(
			'counter',
			array(
				'class'           => array( 'tk-counter', 'tk-counter-area', $position ),
				'data-duration'   => $settings['tk_counter_speed'] * 1000,
				'data-from-value' => $settings['tk_counter_start_value'],
				'data-to-value'   => $settings['tk_counter_end_value'],
				'data-delimiter'  => $settings['tk_counter_t_separator'],
				'data-rounding'   => empty( $settings['tk_counter_d_after'] ) ? 0 : $settings['tk_counter_d_after'],
			)
		);

		?>

		<div <?php echo $this->get_render_attribute_string( 'counter' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
				$this->get_counter_icon();
				$this->get_counter_content();
			?>
		</div>

		<?php
	}

	/**
	 * Render Counter widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#

			var iconImage,
				position;

			view.addInlineEditingAttributes('title');

			position = settings.tk_counter_icon_position;

			var delimiter = settings.tk_counter_t_separator,
				round     = '' === settings.tk_counter_d_after ? 0 : settings.tk_counter_d_after;

			view.addRenderAttribute( 'counter', 'class', [ 'tk-counter', 'tk-counter-area', position ] );
			view.addRenderAttribute( 'counter', 'data-duration', settings.tk_counter_speed * 1000 );
			view.addRenderAttribute( 'counter', 'data-from-value', settings.tk_counter_start_value );
			view.addRenderAttribute( 'counter', 'data-to-value', settings.tk_counter_end_value );
			view.addRenderAttribute( 'counter', 'data-delimiter', delimiter );
			view.addRenderAttribute( 'counter', 'data-rounding', round );

			function getCounterContent() {

				var startValue = settings.tk_counter_start_value;

				view.addRenderAttribute( 'counter_wrap', 'class', 'tk-init-wrapper' );

				view.addRenderAttribute( 'value', 'id', 'counter-' + view.getID() );

				view.addRenderAttribute( 'value', 'class', 'tk-counter-init' );

			#>

				<div {{{ view.getRenderAttributeString('counter_wrap') }}}>

					<div class="tk-counter-value-wrap">
						<# if ( '' !== settings.tk_counter_preffix ) { #>
							<span id="prefix" class="counter-su-pre">{{{ settings.tk_counter_preffix }}}</span>
						<# } #>

						<span {{{ view.getRenderAttributeString('value') }}}>{{{ startValue }}}</span>

						<# if ( '' !== settings.tk_counter_suffix ) { #>
							<span id="suffix" class="counter-su-pre">{{{ settings.tk_counter_suffix }}}</span>
						<# } #>
					</div>

					<# if ( '' !== settings.tk_counter_title ) { #>
						<h4 class="tk-counter-title">
							<div {{{ view.getRenderAttributeString('title') }}}>
								{{{ settings.tk_counter_title }}}
							</div>
						</h4>
					<# } #>
				</div>

			<#
			}

			function getCounterIcon() {

				var iconStyle = 'simple' !== settings.tk_counter_icon_style ? ' icon-bg ' + settings.tk_counter_icon_style : '',
					animation = settings.tk_counter_icon_animation,
					flexWidth = '';

				var iconType = settings.tk_counter_icon_image;

				if( 'icon' === iconType ) {
					var iconHTML = elementor.helpers.renderIcon( view, settings.tk_counter_icon_updated, { 'aria-hidden': true }, 'i' , 'object' ),
						migrated = elementor.helpers.isIconMigrated( settings, 'tk_counter_icon_updated' );
				} else if( 'custom' === iconType ) {
					if( 'simple' ===  settings.tk_counter_icon_style ) {
						flexWidth = ' flex-width ';
					}
				} else {

					view.addRenderAttribute( 'counter_lottie', {
						'class': [
							'tk-counter-animation',
							'tk-lottie-animation'
						],
						'data-lottie-url': settings.lottie_url,
						'data-lottie-loop': settings.lottie_loop,
						'data-lottie-reverse': settings.lottie_reverse
					});

				}

				view.addRenderAttribute( 'icon_wrap', 'class', 'tk-counter-icon');

				var iconClass = 'icon' + flexWidth + iconStyle;

			#>

			<div {{{ view.getRenderAttributeString('icon_wrap') }}}>
				<span data-animation="{{ animation }}" class="{{ iconClass }}">
					<# if( 'icon' === iconType && ( '' !== settings.tk_counter_icon_updated.value || '' !== settings.tk_counter_icon ) ) {
						if ( iconHTML && iconHTML.rendered && ( ! settings.tk_counter_icon || migrated ) ) { #>
							{{{ iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.tk_counter_icon }}" aria-hidden="true"></i>
						<# } #>
					<# } else if( 'custom' === iconType && '' !== settings.tk_counter_image_upload.url ) { #>
						<img class="custom-image" src="{{ settings.tk_counter_image_upload.url }}">
					<# } else { #>
						<div {{{ view.getRenderAttributeString('counter_lottie') }}}></div>
					<# } #>
				</span>
			</div>

			<#
			}

		#>

		<div {{{ view.getRenderAttributeString('counter') }}}>
			<#
				getCounterIcon();
				getCounterContent();
			#>
		</div>

		<?php
	}

}
