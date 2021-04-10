<?php
/**
 * Elementor businesshours Widget.
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
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Business Hours class
 */
class BusinessHours extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve businesshours widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-businesshours';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve businesshours widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Business Hours', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve businesshours widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-clock-o';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the businesshours widget belongs to.
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
	 * Register businesshours widget controls.
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
				 * Content Tab: Business Hours
				 */
		$this->start_controls_section(
			'section_business_hours',
			array(
				'label' => __( 'Business Hours', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'business_timings',
			array(
				'label'   => __( 'Business Timings', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'predefined',
				'options' => array(
					'predefined' => __( 'Predefined', 'taman-kit-pro' ),
					'custom'     => __( 'Custom', 'taman-kit-pro' ),
				),
			)
		);

		$tk_business_hours = array(
			'00:00' => '12:00 AM',
			'00:30' => '12:30 AM',
			'01:00' => '1:00 AM',
			'01:30' => '1:30 AM',
			'02:00' => '2:00 AM',
			'02:30' => '2:30 AM',
			'03:00' => '3:00 AM',
			'03:30' => '3:30 AM',
			'04:00' => '4:00 AM',
			'04:30' => '4:30 AM',
			'05:00' => '5:00 AM',
			'05:30' => '5:30 AM',
			'06:00' => '6:00 AM',
			'06:30' => '6:30 AM',
			'07:00' => '7:00 AM',
			'07:30' => '7:30 AM',
			'08:00' => '8:00 AM',
			'08:30' => '8:30 AM',
			'09:00' => '9:00 AM',
			'09:30' => '9:30 AM',
			'10:00' => '10:00 AM',
			'10:30' => '10:30 AM',
			'11:00' => '11:00 AM',
			'11:30' => '11:30 AM',
			'12:00' => '12:00 PM',
			'12:30' => '12:30 PM',
			'13:00' => '1:00 PM',
			'13:30' => '1:30 PM',
			'14:00' => '2:00 PM',
			'14:30' => '2:30 PM',
			'15:00' => '3:00 PM',
			'15:30' => '3:30 PM',
			'16:00' => '4:00 PM',
			'16:30' => '4:30 PM',
			'17:00' => '5:00 PM',
			'17:30' => '5:30 PM',
			'18:00' => '6:00 PM',
			'18:30' => '6:30 PM',
			'19:00' => '7:00 PM',
			'19:30' => '7:30 PM',
			'20:00' => '8:00 PM',
			'20:30' => '8:30 PM',
			'21:00' => '9:00 PM',
			'21:30' => '9:30 PM',
			'22:00' => '10:00 PM',
			'22:30' => '10:30 PM',
			'23:00' => '11:00 PM',
			'23:30' => '11:30 PM',
			'24:00' => '12:00 PM',
			'24:30' => '12:30 PM',
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'day',
			array(
				'label'   => __( 'Day', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'Monday',
				'options' => array(
					'Monday'    => __( 'Monday', 'taman-kit-pro' ),
					'Tuesday'   => __( 'Tuesday', 'taman-kit-pro' ),
					'Wednesday' => __( 'Wednesday', 'taman-kit-pro' ),
					'Thursday'  => __( 'Thursday', 'taman-kit-pro' ),
					'Friday'    => __( 'Friday', 'taman-kit-pro' ),
					'Saturday'  => __( 'Saturday', 'taman-kit-pro' ),
					'Sunday'    => __( 'Sunday', 'taman-kit-pro' ),
				),
			)
		);

		$repeater->add_control(
			'closed',
			array(
				'label'        => __( 'Closed?', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'No', 'taman-kit-pro' ),
				'label_off'    => __( 'Yes', 'taman-kit-pro' ),
				'return_value' => 'no',
			)
		);

		$repeater->add_control(
			'opening_hours',
			array(
				'label'     => __( 'Opening Hours', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '09:00',
				'options'   => $tk_business_hours,
				'condition' => array(
					'closed' => 'no',
				),
			)
		);

		$repeater->add_control(
			'closing_hours',
			array(
				'label'     => __( 'Closing Hours', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '17:00',
				'options'   => $tk_business_hours,
				'condition' => array(
					'closed' => 'no',
				),
			)
		);

		$repeater->add_control(
			'closed_text',
			array(
				'label'       => __( 'Closed Text', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Closed', 'taman-kit-pro' ),
				'default'     => __( 'Closed', 'taman-kit-pro' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'closed',
							'operator' => '!=',
							'value'    => 'no',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'highlight',
			array(
				'label'        => __( 'Highlight', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'highlight_bg',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row{{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'highlight' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'highlight_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row{{CURRENT_ITEM}} .tk-business-day, {{WRAPPER}} .tk-business-hours .tk-business-hours-row{{CURRENT_ITEM}} .tk-business-timing' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'highlight' => 'yes',
				),
			)
		);

		$this->add_control(
			'business_hours',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'day' => 'Monday',
					),
					array(
						'day' => 'Tuesday',
					),
					array(
						'day' => 'Wednesday',
					),
					array(
						'day' => 'Thursday',
					),
					array(
						'day' => 'Friday',
					),
					array(
						'day'             => 'Saturday',
						'closed'          => 'yes',
						'highlight'       => 'yes',
						'highlight_color' => '#bc1705',
					),
					array(
						'day'             => 'Sunday',
						'closed'          => 'yes',
						'highlight'       => 'yes',
						'highlight_color' => '#bc1705',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ day }}}',
				'condition'   => array(
					'business_timings' => 'predefined',
				),
			)
		);

		$repeater_custom = new Repeater();

		$repeater_custom->add_control(
			'day',
			array(
				'label'   => __( 'Day', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Monday',
			)
		);

		$repeater_custom->add_control(
			'closed',
			array(
				'label'        => __( 'Closed?', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'No', 'taman-kit-pro' ),
				'label_off'    => __( 'Yes', 'taman-kit-pro' ),
				'return_value' => 'no',
			)
		);

		$repeater_custom->add_control(
			'time',
			array(
				'label'     => __( 'Time', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '09:00 AM - 05:00 PM',
				'condition' => array(
					'closed' => 'no',
				),
			)
		);

		$repeater_custom->add_control(
			'closed_text',
			array(
				'label'       => __( 'Closed Text', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Closed', 'taman-kit-pro' ),
				'default'     => __( 'Closed', 'taman-kit-pro' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'closed',
							'operator' => '!=',
							'value'    => 'no',
						),
					),
				),
			)
		);

		$repeater_custom->add_control(
			'highlight',
			array(
				'label'        => __( 'Highlight', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$repeater_custom->add_control(
			'highlight_bg',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row{{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'highlight' => 'yes',
				),
			)
		);

		$repeater_custom->add_control(
			'highlight_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row{{CURRENT_ITEM}} .tk-business-day, {{WRAPPER}} .tk-business-hours .tk-business-hours-row{{CURRENT_ITEM}} .tk-business-timing' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'highlight' => 'yes',
				),
			)
		);

		$this->add_control(
			'business_hours_custom',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'day' => 'Monday',
					),
					array(
						'day' => 'Tuesday',
					),
					array(
						'day' => 'Wednesday',
					),
					array(
						'day' => 'Thursday',
					),
					array(
						'day' => 'Friday',
					),
					array(
						'day'             => 'Saturday',
						'closed'          => 'yes',
						'highlight'       => 'yes',
						'highlight_color' => '#bc1705',
					),
					array(
						'day'             => 'Sunday',
						'closed'          => 'yes',
						'highlight'       => 'yes',
						'highlight_color' => '#bc1705',
					),
				),
				'fields'      => $repeater_custom->get_controls(),
				'title_field' => '{{{ day }}}',
				'condition'   => array(
					'business_timings' => 'custom',
				),
			)
		);

		$this->add_control(
			'hours_format',
			array(
				'label'        => __( '24 Hours Format?', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'business_timings' => 'predefined',
				),
			)
		);

		$this->add_control(
			'days_format',
			array(
				'label'     => __( 'Days Format', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'long',
				'options'   => array(
					'long'  => __( 'Long', 'taman-kit-pro' ),
					'short' => __( 'Short', 'taman-kit-pro' ),
				),
				'condition' => array(
					'business_timings' => 'predefined',
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
				 * Style Tab: Row Style
				 */
		$this->start_controls_section(
			'section_rows_style',
			array(
				'label' => __( 'Rows Style', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_rows_style' );

		$this->start_controls_tab(
			'tab_row_normal',
			array(
				'label' => __( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'row_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_row_hover',
			array(
				'label' => __( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'row_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'stripes',
			array(
				'label'        => __( 'Striped Rows', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_alternate_style' );

		$this->start_controls_tab(
			'tab_even',
			array(
				'label'     => __( 'Even Row', 'taman-kit-pro' ),
				'condition' => array(
					'stripes' => 'yes',
				),
			)
		);

		$this->add_control(
			'row_even_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f5f5f5',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:nth-child(even)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'stripes' => 'yes',
				),
			)
		);

		$this->add_control(
			'row_even_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:nth-child(even)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'stripes' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_odd',
			array(
				'label'     => __( 'Odd Row', 'taman-kit-pro' ),
				'condition' => array(
					'stripes' => 'yes',
				),
			)
		);

		$this->add_control(
			'row_odd_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:nth-child(odd)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'stripes' => 'yes',
				),
			)
		);

		$this->add_control(
			'row_odd_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:nth-child(odd)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'stripes' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rows_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '8',
					'right'    => '10',
					'bottom'   => '8',
					'left'     => '10',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'rows_margin',
			array(
				'label'      => __( 'Margin Bottom', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 80,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'closed_row_heading',
			array(
				'label'     => __( 'Closed Row', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'closed_row_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row.row-closed' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'closed_row_day_color',
			array(
				'label'     => __( 'Day Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row.row-closed .tk-business-day' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'closed_row_tex_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row.row-closed .tk-business-timing' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'divider_heading',
			array(
				'label'     => __( 'Rows Divider', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rows_divider_style',
			array(
				'label'     => __( 'Divider Style', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => __( 'None', 'taman-kit-pro' ),
					'solid'  => __( 'Solid', 'taman-kit-pro' ),
					'dashed' => __( 'Dashed', 'taman-kit-pro' ),
					'dotted' => __( 'Dotted', 'taman-kit-pro' ),
					'groove' => __( 'Groove', 'taman-kit-pro' ),
					'ridge'  => __( 'Ridge', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:not(:last-child)' => 'border-bottom-style: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rows_divider_color',
			array(
				'label'     => __( 'Divider Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:not(:last-child)' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'rows_divider_style!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'rows_divider_weight',
			array(
				'label'      => __( 'Divider Weight', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 1 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rows_divider_style!' => 'none',
				),
			)
		);

		$this->end_controls_section();

		/**
				 * Style Tab: Business Hours
				 */
		$this->start_controls_section(
			'section_business_hours_style',
			array(
				'label' => __( 'Business Hours', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_hours_style' );

		$this->start_controls_tab(
			'tab_hours_normal',
			array(
				'label' => __( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Day', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'day_alignment',
			array(
				'label'     => __( 'Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-day' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'day_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-day' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .tk-business-hours .tk-business-day',
			)
		);

		$this->add_control(
			'hours_heading',
			array(
				'label'     => __( 'Hours', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'hours_alignment',
			array(
				'label'     => __( 'Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'right',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-timing' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hours_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-timing' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hours_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .tk-business-hours .tk-business-timing',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hours_hover',
			array(
				'label' => __( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'day_color_hover',
			array(
				'label'     => __( 'Day Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:hover .tk-business-day' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'hours_color_hover',
			array(
				'label'     => __( 'Hours Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-business-hours .tk-business-hours-row:hover .tk-business-timing' => 'color: {{VALUE}}',
				),
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
	 * Render business hours widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'business-hours', 'class', 'tk-business-hours' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'business-hours' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			if ( 'predefined' === $settings['business_timings'] ) {
				$this->render_business_hours_predefined();
			} elseif ( 'custom' === $settings['business_timings'] ) {
				$this->render_business_hours_custom();
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render predefined business hours widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_business_hours_predefined() {
		$settings = $this->get_settings();
		$i        = 1;
		foreach ( $settings['business_hours'] as $index => $item ) :
			?>
			<?php
			$this->add_render_attribute( 'row' . $i, 'class', 'tk-business-hours-row clearfix elementor-repeater-item-' . esc_attr( $item['_id'] ) );
			if ( 'no' !== $item['closed'] ) {
				$this->add_render_attribute( 'row' . $i, 'class', 'row-closed' );
			}
			?>
			<div <?php echo $this->get_render_attribute_string( 'row' . $i ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<span class="tk-business-day">
					<?php
					if ( 'long' === $settings['days_format'] ) {
						echo ucwords( esc_attr( $item['day'] ) );
					} else {
						echo ucwords( esc_attr( substr( $item['day'], 0, 3 ) ) );
					}
					?>
				</span>
				<span class="tk-business-timing">
					<?php if ( 'no' === $item['closed'] ) { ?>
						<span class="tk-opening-hours">
							<?php
							if ( 'yes' === $settings['hours_format'] ) {
								echo esc_attr( $item['opening_hours'] );
							} else {
								echo esc_attr( date( 'g:i A', strtotime( $item['opening_hours'] ) ) );
							}
							?>
						</span>
						-
						<span class="tk-closing-hours">
							<?php
							if ( 'yes' === $settings['hours_format'] ) {
								echo esc_attr( $item['closing_hours'] );
							} else {
								echo esc_attr( date( 'g:i A', strtotime( $item['closing_hours'] ) ) );
							}
							?>
						</span>
						<?php
					} else {
						if ( $item['closed_text'] ) {
							echo $item['closed_text'];
						} else {
							esc_attr_e( 'Closed', 'taman-kit-pro' );
						}
					}
					?>
				</span>
			</div>
			<?php
			$i++;
		endforeach;
	}

	/**
	 * Render custom business hours widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_business_hours_custom() {
		$settings = $this->get_settings();
		$i        = 1;
		?>
			<?php foreach ( $settings['business_hours_custom'] as $index => $item ) : ?>
				<?php
				$this->add_render_attribute( 'row' . $i, 'class', 'tk-business-hours-row clearfix elementor-repeater-item-' . esc_attr( $item['_id'] ) );
				if ( 'no' !== $item['closed'] ) {
					$this->add_render_attribute( 'row' . $i, 'class', 'row-closed' );
				}
				?>
				<div <?php echo $this->get_render_attribute_string( 'row' . $i ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php if ( $item['day'] ) { ?>
						<span class="tk-business-day">
							<?php
								echo esc_attr( $item['day'] );
							?>
						</span>
					<?php } ?>
					<span class="tk-business-timing">
						<?php
						if ( 'no' === $item['closed'] && $item['time'] ) {
							echo esc_attr( $item['time'] );
						} else {
							if ( $item['closed_text'] ) {
								echo $item['closed_text'];
							} else {
								esc_attr_e( 'Closed', 'taman-kit-pro' );
							}
						}
						?>
					</span>
				</div>
				<?php
				$i++;
			endforeach;
			?>
		<?php
	}

	/**
	 * Render business hours widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			function tk_timeTo12HrFormat(time) {
				// Take a time in 24 hour format and format it in 12 hour format
				var ampm = 'AM';

				var first_part = time.substring(0,2);
				var second_part = time.substring(3,5);

				if (first_part >= 12) {
					ampm = 'PM';
				}

				if (first_part == 00) {
					first_part = 12;
				}

				if (first_part >= 1 && first_part < 10) {
					var first_part = first_part.substr(1, 2);
				}

				if (first_part > 12) {
					first_part = first_part - 12;
				}

				formatted_time = first_part + ':' + second_part + ' ' + ampm;

				return formatted_time;
			}

			function business_hours_predefined_template() {
				_.each( settings.business_hours, function( item ) { #>
					<#
						var closed = ( item.closed != 'no' ) ? 'row-closed' : '';
					#>
					<div class="tk-business-hours-row clearfix elementor-repeater-item-{{ item._id }} {{ closed }}">
						<span class="tk-business-day">
							<# if ( settings.days_format == 'long' ) { #>
								{{ item.day }}
							<# } else { #>
								{{ item.day.substring(0,3) }}
							<# } #>
						</span>
						<span class="tk-business-timing">
							<# if ( item.closed == 'no' ) { #>
								<span class="tk-opening-hours">
									<# if ( settings.hours_format == 'yes' ) { #>
										{{ item.opening_hours }}
									<# } else { #>
										{{ tk_timeTo12HrFormat( item.opening_hours ) }}
									<# } #>
								</span>
								-
								<span class="tk-closing-hours">
									<# if ( settings.hours_format == 'yes' ) { #>
										{{ item.closing_hours }}
									<# } else { #>
										{{ tk_timeTo12HrFormat( item.closing_hours ) }}
									<# } #>
								</span>
							<# } else { #>
								<# if ( item.closed_text != '' ) { #>
									{{ item.closed_text }}
								<# } else { #>
									<?php esc_attr_e( 'Closed', 'taman-kit-pro' ); ?>
								<# } #>
							<# } #>
						</span>
					</div>
				<# } );
			}

			function business_hours_custom_template() {
				_.each( settings.business_hours_custom, function( item ) { #>
					<#
						var closed = ( item.closed != 'no' ) ? 'row-closed' : '';
					#>
					<div class="tk-business-hours-row clearfix elementor-repeater-item-{{ item._id }} {{ closed }}">
						<# if ( item.day != '' ) { #>
							<span class="tk-business-day">
								{{ item.day }}
							</span>
						<# } #>
						<span class="tk-business-timing">
							<# if ( item.closed == 'no' && item.time != '' ) { #>
								{{ item.time }}
							<# } else { #>
								<# if ( item.closed_text != '' ) { #>
									{{ item.closed_text }}
								<# } else { #>
									<?php esc_attr_e( 'Closed', 'taman-kit-pro' ); ?>
								<# } #>
							<# } #>
						</span>
					</div>
				<# } );
			}
		#>
		<div class="tk-business-hours">
			<#
				if ( settings.business_timings == 'predefined' ) {
					business_hours_predefined_template();
				} else {
					business_hours_custom_template();
				}
			#>
		</div>
		<?php
	}

	/**
	 * Render divider widget output in the editor.
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
