<?php
/**
 * Elementor Circleprogress Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

namespace TamanKitPro\Widgets;

use TamanKitPro\Modules\TemplatesPro;

// Elementor Classes.
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Responsive\Responsive;
use Elementor\Modules\DynamicTags\Module as TagsModule;


/**
 * Circleprogress widget class
 */
class Circleprogress  extends Widget_Base {

	/**
	 * Widget base constructor.
	 *
	 * Initializing the widget base class.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array      $data Widget data. Default is an empty array.
	 * @param array|null $args Optional. Widget default arguments. Default is null.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'taman-kit-circle',
			TAMAN_KIT_PRO_URL . 'public/css/widgets/circle.css',
			array(),
			\TamanKitProHelpers::taman_kit_pro_ver(),
			'all'
		);

	}

	/**
	 * Get widget name.
	 *
	 * Retrieve circleprogress widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-circleprogress';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve circleprogress  widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Circleprogressbar', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve circleprogress  widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon  eicon-counter-circle';
	}


	/**
	 * Get style dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_style_depends() {
		return array( 'taman-kit-circle' );
	}

	/**
	 * Script Depends
	 */
	public function get_script_depends() {
		return array(
			'jquery-numerator',
			'elementor-waypoints',
		);
	}
	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the circleprogress  widget belongs to.
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
	 * Register circleprogress  widget controls.
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
		*==================================== controls & sections =========================
		*
		*==================================================================================
		*/

		$this->start_controls_section(
			'section_values',
			array(
				'label' => esc_html__( 'Values', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'values_type',
			array(
				'label'   => esc_html__( 'Progress Values Type', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'percent',
				'options' => array(
					'percent'  => esc_html__( 'Percent', 'taman-kit-pro' ),
					'absolute' => esc_html__( 'Absolute', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'percent_value',
			array(
				'label'      => esc_html__( 'Current Percent', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'dynamic'    => version_compare( ELEMENTOR_VERSION, '2.7.0', '>=' ) ?
					array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
							TagsModule::NUMBER_CATEGORY,
						),
					) : array(),
				'condition'  => array(
					'values_type' => 'percent',
				),
			)
		);

		$this->add_control(
			'absolute_value_curr',
			array(
				'label'     => esc_html__( 'Current Value', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 50,
				'dynamic'   => version_compare( ELEMENTOR_VERSION, '2.7.0', '>=' ) ?
					array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
							TagsModule::NUMBER_CATEGORY,
						),
					) : array(),
				'condition' => array(
					'values_type' => 'absolute',
				),
			)
		);

		$this->add_control(
			'absolute_value_max',
			array(
				'label'     => esc_html__( 'Max Value', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 100,
				'dynamic'   => version_compare( ELEMENTOR_VERSION, '2.7.0', '>=' ) ?
					array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
							TagsModule::NUMBER_CATEGORY,
						),
					) : array(),
				'condition' => array(
					'values_type' => 'absolute',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'prefix',
			array(
				'label'       => esc_html__( 'Value Number Prefix', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => '+',
			)
		);

		$this->add_control(
			'suffix',
			array(
				'label'       => esc_html__( 'Value Number Suffix', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '%',
				'placeholder' => '%',
			)
		);

		$this->add_control(
			'thousand_separator',
			array(
				'label'     => esc_html__( 'Show Thousand Separator in Value', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'taman-kit-pro' ),
				'label_off' => esc_html__( 'Hide', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Counter Title', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'   => esc_html__( 'Counter Subtitle', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'percent_position',
			array(
				'label'   => esc_html__( 'Percent Position', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'in-circle',
				'options' => array(
					'in-circle'  => esc_html__( 'Inside of Circle', 'taman-kit-pro' ),
					'out-circle' => esc_html__( 'Outside of Circle', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'labels_position',
			array(
				'label'   => esc_html__( 'Label Position', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'out-circle',
				'options' => array(
					'in-circle'  => esc_html__( 'Inside of Circle', 'taman-kit-pro' ),
					'out-circle' => esc_html__( 'Outside of Circle', 'taman-kit-pro' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_size',
			array(
				'label' => esc_html__( 'Settings', 'taman-kit-pro' ),
			)
		);

		$this->add_responsive_control(
			'circle_size',
			array(
				'label'       => esc_html__( 'Circle Size', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 185,
				),
				'range'       => array(
					'px' => array(
						'min' => 100,
						'max' => 600,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .circle-progress-bar' => 'max-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .circle-progress'     => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .position-in-circle'  => 'height: {{SIZE}}{{UNIT}}',

				),
				'render_type' => 'template',
			)
		);

		$this->add_responsive_control(
			'value_stroke',
			array(
				'label'      => esc_html__( 'Value Stoke Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 7,
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 300,
					),
				),
			)
		);

		$this->add_responsive_control(
			'bg_stroke',
			array(
				'label'      => esc_html__( 'Background Stoke Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 7,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
			)
		);

		$this->add_control(
			'bg_stroke_type',
			array(
				'label'       => esc_html__( 'Background Stroke Type', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'color'    => array(
						'title' => esc_html__( 'Classic', 'taman-kit-pro' ),
						'icon'  => 'fa fa-paint-brush',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'taman-kit-pro' ),
						'icon'  => 'fa fa-barcode',
					),
				),
				'default'     => 'color',
				'label_block' => false,
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'val_bg_color',
			array(
				'label'     => esc_html__( 'Background Stroke Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e6e9ec',
				'condition' => array(
					'bg_stroke_type' => array( 'color' ),
				),
			)
		);

		$this->add_control(
			'val_bg_gradient_color_a',
			array(
				'label'     => esc_html__( 'Background Stroke Color A', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#54595f',
				'condition' => array(
					'bg_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_bg_gradient_color_b',
			array(
				'label'     => esc_html__( 'Background Stroke Color B', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#858d97',
				'condition' => array(
					'bg_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_bg_gradient_angle',
			array(
				'label'     => esc_html__( 'Background Stroke Gradient Angle', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 45,
				'min'       => 0,
				'max'       => 360,
				'step'      => 0,
				'condition' => array(
					'bg_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_stroke_type',
			array(
				'label'       => esc_html__( 'Value Stroke Type', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'color'    => array(
						'title' => esc_html__( 'Classic', 'taman-kit-pro' ),
						'icon'  => 'fa fa-paint-brush',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'taman-kit-pro' ),
						'icon'  => 'fa fa-barcode',
					),
				),
				'default'     => 'color',
				'label_block' => false,
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'val_stroke_color',
			array(
				'label'     => esc_html__( 'Value Stroke Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6ec1e4',
				'condition' => array(
					'val_stroke_type' => array( 'color' ),
				),
			)
		);

		$this->add_control(
			'val_stroke_gradient_color_a',
			array(
				'label'     => esc_html__( 'Value Stroke Color A', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6ec1e4',
				'condition' => array(
					'val_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_stroke_gradient_color_b',
			array(
				'label'     => esc_html__( 'Value Stroke Color B', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#b6e0f1',
				'condition' => array(
					'val_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_stroke_gradient_angle',
			array(
				'label'     => esc_html__( 'Value Stroke Angle', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 45,
				'min'       => 0,
				'max'       => 360,
				'step'      => 1,
				'condition' => array(
					'val_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'duration',
			array(
				'label'   => esc_html__( 'Animation Duration', 'taman-kit-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1000,
				'min'     => 100,
				'step'    => 100,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_progress_style',
			array(
				'label'      => esc_html__( 'Progress Circle Style', 'taman-kit-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'circle_fill_color',
			array(
				'label'     => esc_html__( 'Circle Fill Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .circle-progress__meter' => 'fill: {{VALUE}}',
				),
			),
		);

		$this->add_control(
			'line_endings',
			array(
				'label'     => esc_html__( 'Progress Line Endings', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'butt',
				'options'   => array(
					'butt'  => esc_html__( 'Flat', 'taman-kit-pro' ),
					'round' => esc_html__( 'Rounded', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-progress__value' => 'stroke-linecap: {{VALUE}}',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'circle_box_shadow',
				'label'    => esc_html__( 'Circle Box Shadow', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .circle-progress',
			),
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content Style', 'taman-kit-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'number_style',
			array(
				'label'     => esc_html__( 'Number Styles', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
		);

		$this->add_control(
			'number_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-counter .circle-val' => 'color: {{VALUE}}',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .circle-counter .circle-val',
			),
		);

		$this->add_responsive_control(
			'number_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-val' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_responsive_control(
			'number_prefix_font_size',
			array(
				'label'      => esc_html__( 'Prefix Font Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_responsive_control(
			'number_prefix_gap',
			array(
				'label'      => esc_html__( 'Prefix Gap (px)', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_responsive_control(
			'number_prefix_alignment',
			array(
				'label'       => esc_html__( 'Prefix Alignment', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'center',
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'align-self: {{VALUE}};',
				),
			),
		);

		$this->add_responsive_control(
			'number_suffix_font_size',
			array(
				'label'      => esc_html__( 'Suffix Font Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_responsive_control(
			'number_suffix_gap',
			array(
				'label'      => esc_html__( 'Suffix Gap (px)', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'margin-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'margin-right: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_responsive_control(
			'number_suffix_alignment',
			array(
				'label'       => esc_html__( 'Suffix Alignment', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'center',
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'align-self: {{VALUE}};',
				),
			),
		);

		$this->add_control(
			'title_style',
			array(
				'label'     => esc_html__( 'Title Styles', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-counter .circle-counter__title' => 'color: {{VALUE}}',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .circle-counter .circle-counter__title',
			),
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-counter__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_control(
			'subtitle_style',
			array(
				'label'     => esc_html__( 'Subtitle Styles', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-counter .circle-counter__subtitle' => 'color: {{VALUE}}',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .circle-counter .circle-counter__subtitle',
			),
		);

		$this->add_responsive_control(
			'subtitle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-counter__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->end_controls_section();

	}

	/**
	 * Circle progress counter
	 *
	 * @param string $position .
	 */
	protected function counter( $position ) {
		$settings = $this->get_settings_for_display();

		$perc_position   = $settings['percent_position'];
		$labels_position = $settings['labels_position'];
		$title           = $settings['title'];
		$subtitle        = $settings['subtitle'];

		$__prefix = $settings['prefix'];
		$__suffix = $settings['suffix'];
		$value    = $settings['percent_value']['size'];
		$position = $position;

		if ( 'percent' === $settings['values_type'] ) {
			$value = $settings['percent_value']['size'];
		} else {
			$value = $settings['absolute_value_curr'];
		}

		$this->add_render_attribute(
			'circle-counter',
			array(
				'class'         => 'circle-counter__number',
				'data-to-value' => $value,
			)
		);

		?>
		<div class="circle-counter">
				<?php if ( $perc_position === $position ) { ?>
				<div class="circle-val">
					<span class="circle-counter__prefix"><?php echo esc_html( $__prefix ); ?></span>
						<?php

						if ( ! empty( $settings['thousand_separator'] ) ) {
							$this->add_render_attribute( 'circle-counter', 'data-delimiter', ',' );
						}
						?>
					<span <?php echo $this->get_render_attribute_string( 'circle-counter' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>0</span>

					<span class="circle-counter__suffix"><?php echo esc_html( $__suffix ); ?></span>
				</div>
					<?php
				}
				if ( $labels_position === $position ) {
					?>
					<div class="circle-counter__content">
						<div class="circle-counter__title"><?php echo esc_html( $title ); ?></div>
						<div class="circle-counter__subtitle"><?php echo esc_html( $subtitle ); ?></div>
					</div>
			<?php } ?>
		</div>
		<?php

	}

	/**
	 * Render circleprogress widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$perc_position   = $settings['percent_position'];
		$labels_position = $settings['labels_position'];

		$this->add_render_attribute(
			'circle-wrap',
			array(
				'class'         => 'circle-progress-wrap',
				'data-duration' => $this->get_settings_for_display( 'duration' ),
			)
		);

		$this->add_render_attribute(
			'circle-bar',
			array(
				'class' => 'circle-progress-bar',
			)
		);

		?>
			<div class="elementor-tk-circle-progress tk-elements">
				<div <?php echo $this->get_render_attribute_string( 'circle-wrap' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div <?php echo $this->get_render_attribute_string( 'circle-bar' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php

							include TemplatesPro::get_templatet( 'progress', 'circle' );

						if ( 'in-circle' === $perc_position || 'in-circle' === $labels_position ) {
							echo '<div class="position-in-circle">';
							$this->counter( 'in-circle' );
							echo '</div>';
						}
						?>
					</div>
						<?php
						if ( 'out-circle' === $perc_position || 'out-circle' === $labels_position ) {
							echo '<div class="position-below-circle">';
							$this->counter( 'out-circle' );
							echo '</div>';
						}
						?>
				</div>
			</div>
		<?php
	}
}
