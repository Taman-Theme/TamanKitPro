<?php
/**
 * Elementor progress  Widget.
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
 * Progress widget class
 */
class Progress  extends Widget_Base {

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
			'taman-kit-progress',
			TAMAN_KIT_PRO_URL . 'public/css/widgets/progress.css',
			array(),
			\TamanKitProHelpers::taman_kit_pro_ver(),
			'all'
		);

	}

	/**
	 * Get widget name.
	 *
	 * Retrieve progress widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-progress';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve progress  widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Progressbar', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve progress  widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon  eicon-skill-bar';
	}


	/**
	 * Get style dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_style_depends() {
		return array( 'taman-kit-progress' );
	}

	/**
	 * Script Depends
	 */
	public function get_script_depends() {
		return array(
			'anime-js',
			'elementor-waypoints',
			'taman-kit-pro',
		);
	}
	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the progress  widget belongs to.
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
	 * Register progress  widget controls.
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

		$this->start_controls_section(
			'section_progress',
			array(
				'label' => esc_html__( 'Progress Bar', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'progress_type',
			array(
				'label'   => esc_html__( 'Type', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'type-1',
				'options' => array(
					'type-1' => esc_html__( 'Inside the bar', 'taman-kit-pro' ),
					'type-2' => esc_html__( 'Placed above ', 'taman-kit-pro' ),
					'type-3' => esc_html__( 'Shown as tip', 'taman-kit-pro' ),
					'type-4' => esc_html__( 'On the right', 'taman-kit-pro' ),
					'type-5' => esc_html__( 'Inside the empty bar', 'taman-kit-pro' ),
					'type-6' => esc_html__( 'Inside the bar with title', 'taman-kit-pro' ),
					'type-7' => esc_html__( 'Inside the vertical bar', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'taman-kit-pro' ),
				'default'     => esc_html__( 'Title', 'taman-kit-pro' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Select an Icon', 'taman-kit-pro' ),
				'type'    => Controls_Manager::ICONS,
				'default' => '',
			)
		);

		$this->add_control(
			'percent',
			array(
				'label'   => esc_html__( 'Percentage', 'taman-kit-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 50,
				'min'     => 0,
				'max'     => 100,
				'dynamic' => version_compare( ELEMENTOR_VERSION, '2.7.0', '>=' ) ?
					array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
							TagsModule::NUMBER_CATEGORY,
						),
					) : array(),
			)
		);

		$this->end_controls_section();

		/**
		 * Progress Bar Style Section
		 */
		$this->start_controls_section(
			'section_progress_style',
			array(
				'label'      => esc_html__( 'Progress Bar', 'taman-kit-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'progress_wrapper_height',
			array(
				'label'      => esc_html__( 'Progress Height', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__wrapper' => 'height: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_responsive_control(
			'progress_wrapper_width',
			array(
				'label'      => esc_html__( 'Progress Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'condition'  => array(
					'progress_type' => array( 'type-7' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__wrapper' => 'width: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_responsive_control(
			'progress_wrapper_margin',
			array(
				'label'      => esc_html__( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'progress_wrapper_background',
				'selector' => '{{WRAPPER}} .tk-progress-bar__wrapper',
			),
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'progress_wrapper_border',
				'label'       => esc_html__( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-progress-bar__wrapper',
			),
		);

		$this->add_responsive_control(
			'progress_wrapper_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'progress_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .tk-progress-bar__wrapper',
			),
		);

		$this->add_control(
			'status_bar_heading',
			array(
				'label'     => esc_html__( 'Status Bar', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
		);

		$this->add_responsive_control(
			'progress_status_height',
			array(
				'label'      => esc_html__( 'Status Bar Height', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__status-bar' => 'height: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'status_bar_background',
				'selector' => '{{WRAPPER}} .tk-progress-bar__status-bar',
			),
		);

		$this->add_responsive_control(
			'status_bar_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__status-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->end_controls_section();

		/**
		 * Title Style Section
		 */
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Title', 'taman-kit-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'title_alignment',
			array(
				'label'       => esc_html__( 'Title Alignment', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => '',
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'taman-kit-pro' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'taman-kit-pro' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'condition'   => array(
					'progress_type' => array( 'type-1', 'type-2', 'type-3', 'type-5' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tk-progress-bar__title' => 'align-self: {{VALUE}};',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'title_background',
				'selector' => '{{WRAPPER}} .tk-progress-bar__title',
			),
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'title_border',
				'label'       => esc_html__( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-progress-bar__title',
			),
		);

		$this->add_responsive_control(
			'title_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'title_box_shadow',
				'selector' => '{{WRAPPER}} .tk-progress-bar__title',
			),
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_control(
			'title_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-progress-bar__title-icon' => 'color: {{VALUE}}',
				),
			),
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__title-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__title-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_control(
			'title_text_heading',
			array(
				'label'     => esc_html__( 'Text', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-progress-bar__title-text' => 'color: {{VALUE}}',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-progress-bar__title-text',
			),
		);

		$this->add_responsive_control(
			'text_alignment',
			array(
				'label'       => esc_html__( 'Text Alignment', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => '',
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
					'{{WRAPPER}} .tk-progress-bar__title-text' => 'align-self: {{VALUE}};',
				),
			),
		);

		$this->end_controls_section();

		/**
		 * Percent Style Section
		 */
		$this->start_controls_section(
			'section_percent_style',
			array(
				'label'      => esc_html__( 'Percent', 'taman-kit-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'percent_width',
			array(
				'label'      => esc_html__( 'Percent Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
				),
				'condition'  => array(
					'progress_type' => array( 'type-3' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__percent' => 'width: {{SIZE}}{{UNIT}}; margin-right: calc( {{SIZE}}{{UNIT}}/-2 );',
				),
			),
		);

		$this->add_responsive_control(
			'percent_alignment',
			array(
				'label'       => esc_html__( 'Percent Alignment', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => '',
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'taman-kit-pro' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'taman-kit-pro' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'condition'   => array(
					'progress_type' => array( 'type-1', 'type-2' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tk-progress-bar__percent' => 'align-self: {{VALUE}};',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'percent_background',
				'selector' => '{{WRAPPER}} .tk-progress-bar__percent',
			),
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'percent_border',
				'label'       => esc_html__( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-progress-bar__percent',
			),
		);

		$this->add_responsive_control(
			'percent_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__percent' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'percent_box_shadow',
				'selector' => '{{WRAPPER}} .tk-progress-bar__percent',
			),
		);

		$this->add_responsive_control(
			'percent_margin',
			array(
				'label'      => esc_html__( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__percent' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_responsive_control(
			'percent_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__percent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_control(
			'percent_color',
			array(
				'label'     => esc_html__( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-progress-bar__percent' => 'color: {{VALUE}}',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'percent_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-progress-bar__percent',
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
				'selectors'  => array(
					'{{WRAPPER}} .tk-progress-bar__percent  .tk-progress-bar__percent-suffix' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
		);

		$this->add_responsive_control(
			'percent_suffix_alignment',
			array(
				'label'       => esc_html__( 'Percent Suffix Alignment', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-progress-bar__percent  .tk-progress-bar__percent-suffix' => 'align-self: {{VALUE}};',
				),
			),
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
	 * Undocumented function
	 */
	public function render_icon_title() {
		$settings = $this->get_settings_for_display();

		$title = $settings['title'];

		$icon = Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );

		echo '<span class="tk-progress-bar__title-icon tk-elements-icon"><i ' . esc_attr( $icon ) . '></i></span>';
		echo '<span class="tk-progress-bar__title-text">' . esc_html( $title ) . '</span>';
	}

	/**
	 * Render progress  widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
			$settings = $this->get_settings_for_display();

			$this->add_render_attribute(
				'main-container',
				'class',
				array(
					'tk-progress-bar',
					'tk-progress-bar-' . $settings['progress_type'],
				)
			);

			$this->add_render_attribute( 'main-container', 'data-percent', $settings['percent'] );
			$this->add_render_attribute( 'main-container', 'data-type', $settings['progress_type'] );

		?>
		<div <?php echo $this->get_render_attribute_string( 'main-container' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php

			$type = $settings['progress_type'];

			switch ( $type ) {

				case 'type-1':
					?>
					<div class="tk-progress-bar__inner">
						<div class="tk-progress-bar__title">
							<?php
								$this->render_icon_title();

							?>
										</div>
						<div class="tk-progress-bar__wrapper">
							<div class="tk-progress-bar__status-bar">
								<div class="tk-progress-bar__percent"><span class="tk-progress-bar__percent-value">0</span><span
										class="tk-progress-bar__percent-suffix">&#37;</span></div>
							</div>
						</div>
					</div>
					<?php
					break;

				case 'type-2':
					?>
						<div class="tk-progress-bar__inner">
							<div class="tk-progress-bar__percent"><span class="tk-progress-bar__percent-value">0</span><span
									class="tk-progress-bar__percent-suffix">&#37;</span></div>
							<div class="tk-progress-bar__wrapper">
								<div class="tk-progress-bar__status-bar"></div>
							</div>
							<div class="tk-progress-bar__title">
								<?php
									$this->render_icon_title();
								?>
							</div>
						</div>
						<?php
					break;

				case 'type-3':
					?>
						<div class="tk-progress-bar__inner">
							<div class="tk-progress-bar__wrapper">
								<div class="tk-progress-bar__status-bar">
									<div class="tk-progress-bar__percent"><span class="tk-progress-bar__percent-value">0</span><span
											class="tk-progress-bar__percent-suffix">&#37;</span></div>
								</div>
							</div>
							<div class="tk-progress-bar__title">
								<?php
								$this->render_icon_title();
								?>
							</div>
						</div>
						<?php
					break;

				case 'type-4':
					?>
						<div class="tk-progress-bar__inner">
							<div class="tk-progress-bar__title">
								<?php
								$this->render_icon_title();
								?>
							</div>
							<div class="tk-progress-bar__wrapper">
								<div class="tk-progress-bar__status-bar"></div>
							</div>
							<div class="tk-progress-bar__percent"><span class="tk-progress-bar__percent-value">0</span><span
									class="tk-progress-bar__percent-suffix">&#37;</span></div>
						</div>
						<?php
					break;

				case 'type-5':
					?>
						<div class="tk-progress-bar__inner">
							<div class="tk-progress-bar__title">
								<?php
								$this->render_icon_title();
								?>
							</div>
							<div class="tk-progress-bar__wrapper">
								<div class="tk-progress-bar__status-bar"></div>
								<div class="tk-progress-bar__percent"><span class="tk-progress-bar__percent-value">0</span><span
										class="tk-progress-bar__percent-suffix">&#37;</span></div>
							</div>
						</div>
						<?php
					break;

				case 'type-6':
					?>
					<div class="tk-progress-bar__inner">
						<div class="tk-progress-bar__wrapper">
							<div class="tk-progress-bar__status-bar"></div>
							<div class="tk-progress-bar__status">
								<div class="tk-progress-bar__percent"><span class="tk-progress-bar__percent-value">0</span><span
										class="tk-progress-bar__percent-suffix">&#37;</span></div>
								<div class="tk-progress-bar__title">
								<?php
								$this->render_icon_title();
								?>
								</div>
							</div>
						</div>
					</div>
					<?php
					break;

				case 'type-7':
					?>
						<div class="tk-progress-bar__inner">
							<div class="tk-progress-bar__wrapper">
								<div class="tk-progress-bar__percent"><span class="tk-progress-bar__percent-value">0</span><span
										class="tk-progress-bar__percent-suffix">&#37;</span></div>
								<div class="tk-progress-bar__status-bar"></div>
							</div>
							<div class="tk-progress-bar__title">
								<?php
									$this->render_icon_title();
								?>
							</div>
						</div>
						<?php
					break;

			}

			?>
		</div>
			<?php
	}

}
