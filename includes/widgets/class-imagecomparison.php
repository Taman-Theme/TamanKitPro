<?php
/**
 * Elementor imagecomparison Widget.
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
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Responsive\Responsive;
use Elementor\Modules\DynamicTags\Module as TagsModule;
/**
 * ImageComparison class
 */
class ImageComparison extends \Elementor\Widget_Base {

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
			'tk-imagecomparison',
			TAMAN_KIT_PRO_URL . 'public/css/widgets/imagecomparison.css',
			array(),
			\TamanKitProHelpers::taman_kit_pro_ver(),
			'all'
		);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve imagecomparison widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-imagecomparison';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve imagecomparison widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'ImageComparison', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve imagecomparison widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-image-before-after';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the imagecomparison widget belongs to.
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
	 * Script Depends
	 */
	public function get_script_depends() {
		return array(
			'jquery-event-move',
			'tk-imgcompare',
			'imagesloaded',
			'taman-kit-pro',
		);
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_style_depends() {
		return array( 'tk-imagecomparison' );
	}

	/**
	 * Register imagecomparison widget controls.
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
			'tk_img_compare_original_image_section',
			array(
				'label' => __( 'Original Image', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_image_comparison_original_image',
			array(
				'label'       => __( 'Choose Image', 'taman-kit-pro' ),
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => array( 'active' => true ),
				'description' => __( 'It\'s recommended to use two images that have the same size', 'taman-kit-pro' ),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'tk_img_compare_original_img_label_switcher',
			array(
				'label'   => __( 'Label', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'tk_img_compare_original_img_label',
			array(
				'label'       => __( 'Text', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Before', 'taman-kit-pro' ),
				'placeholder' => 'Before',
				'condition'   => array(
					'tk_img_compare_original_img_label_switcher'  => 'yes',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'tk_img_compare_original_hor_label_position',
			array(
				'label'     => __( 'Horizontal Position', 'taman-kit-pro' ),
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
				'condition' => array(
					'tk_img_compare_original_img_label_switcher' => 'yes',
					'tk_image_comparison_orientation' => 'vertical',
				),
				'default'   => 'center',
			)
		);

		$this->add_responsive_control(
			'tk_img_compare_original_label_horizontal_offset',
			array(
				'label'      => __( 'Horizontal Offset', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'condition'  => array(
					'tk_img_compare_original_img_label_switcher' => 'yes',
					'tk_image_comparison_orientation' => 'horizontal',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-horizontal .tk-twentytwenty-before-label' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tk_img_compare_original_label_position',
			array(
				'label'     => __( 'Vertical Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'top'    => array(
						'title' => __( 'Top', 'taman-kit-pro' ),
						'icon'  => 'fa fa-arrow-circle-up',
					),
					'middle' => array(
						'title' => __( 'Middle', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'fa fa-arrow-circle-down',
					),
				),
				'condition' => array(
					'tk_img_compare_original_img_label_switcher' => 'yes',
					'tk_image_comparison_orientation' => 'horizontal',
				),
				'default'   => 'middle',
			)
		);

		$this->add_responsive_control(
			'tk_img_compare_original_label_vertical_offset',
			array(
				'label'      => __( 'Vertical Offset', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'condition'  => array(
					'tk_img_compare_original_img_label_switcher' => 'yes',
					'tk_image_comparison_orientation' => 'vertical',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-vertical .tk-twentytwenty-before-label' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_image_comparison_modified_image_section',
			array(
				'label' => __( 'Modified Image', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_image_comparison_modified_image',
			array(
				'label'       => __( 'Choose Image', 'taman-kit-pro' ),
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => array( 'active' => true ),
				'description' => __( 'It\'s recommended to use two images that have the same size', 'taman-kit-pro' ),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'tk_image_comparison_modified_image_label_switcher',
			array(
				'label'   => __( 'Label', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'tk_image_comparison_modified_image_label',
			array(
				'label'       => __( 'Text', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'After',
				'default'     => __( 'After', 'taman-kit-pro' ),
				'condition'   => array(
					'tk_image_comparison_modified_image_label_switcher'  => 'yes',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'tk_img_compare_modified_hor_label_position',
			array(
				'label'       => __( 'Horizontal Position', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
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
				'condition'   => array(
					'tk_image_comparison_modified_image_label_switcher' => 'yes',
					'tk_image_comparison_orientation' => 'vertical',
				),
				'default'     => 'center',
				'label_block' => true,
			)
		);

		$this->add_responsive_control(
			'tk_img_compare_modified_label_horizontal_offset',
			array(
				'label'      => __( 'Horizontal Offset', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'condition'  => array(
					'tk_image_comparison_modified_image_label_switcher' => 'yes',
					'tk_image_comparison_orientation' => 'horizontal',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-horizontal .tk-twentytwenty-after-label' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tk_img_compare_modified_label_position',
			array(
				'label'     => __( 'Vertical Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'top'    => array(
						'title' => __( 'Top', 'taman-kit-pro' ),
						'icon'  => 'fa fa-arrow-circle-up',
					),
					'middle' => array(
						'title' => __( 'Middle', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'fa fa-arrow-circle-down',
					),
				),
				'condition' => array(
					'tk_image_comparison_modified_image_label_switcher' => 'yes',
					'tk_image_comparison_orientation' => 'horizontal',
				),
				'default'   => 'middle',
			)
		);

		$this->add_responsive_control(
			'tk_img_compare_modified_label_vertical_offset',
			array(
				'label'      => __( 'Vertical Offset', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'condition'  => array(
					'tk_image_comparison_modified_image_label_switcher' => 'yes',
					'tk_image_comparison_orientation' => 'vertical',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-vertical .tk-twentytwenty-after-label' => 'bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_img_compare_display_options',
			array(
				'label' => __( 'Display Options', 'taman-kit-pro' ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'prmium_img_compare_images_size',
				'default' => 'full',
			)
		);

		$this->add_control(
			'tk_image_comparison_orientation',
			array(
				'label'       => __( 'Orientation', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'horizontal' => __( 'Vertical', 'taman-kit-pro' ),
					'vertical'   => __( 'Horizontal', 'taman-kit-pro' ),
				),
				'default'     => 'horizontal',
				'label_block' => true,
			)
		);

		$this->add_control(
			'tk_img_compare_visible_ratio',
			array(
				'label'   => __( 'Visible Ratio', 'taman-kit-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min'     => 0,
				'step'    => 0.1,
				'max'     => 1,
			)
		);

		$this->add_control(
			'tk_image_comparison_add_drag_handle',
			array(
				'label'       => __( 'Show Drag Handle', 'taman-kit-pro' ),
				'description' => __( 'Show drag handle between the images', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'label_on'    => 'Show',
				'label_off'   => 'Hide',
			)
		);

		$this->add_control(
			'tk_image_comparison_add_separator',
			array(
				'label'       => __( 'Show Separator', 'taman-kit-pro' ),
				'description' => __( 'Show separator between the images', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => 'Show',
				'label_off'   => 'Hide',
				'condition'   => array(
					'tk_image_comparison_add_drag_handle' => 'yes',
				),
			)
		);

		$this->add_control(
			'tk_image_comparison_interaction_mode',
			array(
				'label'       => __( 'Interaction Mode', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'mousemove' => __( 'Mouse Move', 'taman-kit-pro' ),
					'drag'      => __( 'Mouse Drag', 'taman-kit-pro' ),
					'click'     => __( 'Mouse Click', 'taman-kit-pro' ),
				),
				'default'     => 'mousemove',
				'label_block' => true,
			)
		);

		$this->add_control(
			'tk_image_comparison_overlay',
			array(
				'label'     => __( 'Overlay Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => 'Show',
				'label_off' => 'Hide',

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

		$this->start_controls_section(
			'tk_img_compare_original_img_label_style_tab',
			array(
				'label'     => __( 'First Label', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tk_img_compare_original_img_label_switcher'  => 'yes',
				),
			)
		);

		$this->add_control(
			'tk_image_comparison_original_label_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-twentytwenty-before-label span'   => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tk_image_comparison_original_label_typo',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-twentytwenty-before-label span',
			)
		);

		$this->add_control(
			'tk_image_comparison_original_label_background_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-twentytwenty-before-label span'  => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tk_image_comparison_original_label_border',
				'selector' => '{{WRAPPER}} .tk-twentytwenty-before-label span',
			)
		);

		$this->add_control(
			'tk_image_comparison_original_label_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-before-label span' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tk_image_comparison_original_label_box_shadow',
				'selector' => '{{WRAPPER}} .tk-twentytwenty-before-label span',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'tk_image_comparison_original_label_text_shadow',
				'label'    => __( 'Shadow', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-twentytwenty-before-label span',
			)
		);

		$this->add_responsive_control(
			'tk_image_comparison_original_label_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-before-label span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_image_comparison_modified_image_label_style_tab',
			array(
				'label'     => __( 'Second Label', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tk_image_comparison_modified_image_label_switcher'  => 'yes',
				),
			)
		);

		$this->add_control(
			'tk_image_comparison_modified_label_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-twentytwenty-after-label span'   => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tk_image_comparison_modified_label_typo',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-twentytwenty-after-label span',
			)
		);

		$this->add_control(
			'tk_image_comparison_modified_label_background_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-twentytwenty-after-label span'  => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tk_image_comparison_modified_label_border',
				'selector' => '{{WRAPPER}} .tk-twentytwenty-after-label span',
			)
		);

		$this->add_control(
			'tk_image_comparison_modified_label_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-after-label span' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tk_image_comparison_modified_label_box_shadow',
				'selector' => '{{WRAPPER}} .tk-twentytwenty-after-label span',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'tk_image_comparison_modified_label_text_shadow',
				'label'    => __( 'Shadow', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-twentytwenty-after-label span',
			)
		);

		$this->add_responsive_control(
			'tk_image_comparison_modified_label_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-after-label span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_image_comparison_drag_style_settings',
			array(
				'label'     => __( 'Drag', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tk_image_comparison_add_drag_handle' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'tk_image_comparison_drag_width',
			array(
				'label'       => __( 'Width', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Enter Drag width in (PX), default is 50px', 'taman-kit-pro' ),
				'size_units'  => array( 'px', 'em' ),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .tk-twentytwenty-handle' => 'width:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_image_comparison_drag_height',
			array(
				'label'       => __( 'Height', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'description' => __( 'Enter Drag height in (PX), default is 50px', 'taman-kit-pro' ),
				'size_units'  => array( 'px', 'em' ),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .tk-twentytwenty-handle' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tk_image_comparison_drag_background_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-twentytwenty-handle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tk_image_comparison_drag_border',
				'selector' => '{{WRAPPER}} .tk-twentytwenty-handle',
			)
		);

		$this->add_control(
			'tk_image_comparison_drag_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-handle' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tk_image_comparison_drag_box_shadow',
				'selector' => '{{WRAPPER}} .tk-twentytwenty-handle',
			)
		);

		$this->add_responsive_control(
			'tk_image_comparison_drag_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-twentytwenty-handle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_image_comparison_arrow_style',
			array(
				'label'     => __( 'Arrows', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tk_image_comparison_add_drag_handle' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'tk_image_comparison_arrows_size',
			array(
				'label'       => __( 'Size', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .tk-twentytwenty-left-arrow' => 'border: {{SIZE}}px inset transparent; border-right: {{SIZE}}px solid; margin-top: -{{size}}px',
					'{{WRAPPER}} .tk-twentytwenty-right-arrow' => 'border: {{SIZE}}px inset transparent; border-left: {{SIZE}}px solid; margin-top: -{{size}}px',
					'{{WRAPPER}} .tk-twentytwenty-down-arrow' => 'border: {{SIZE}}px inset transparent; border-top: {{SIZE}}px solid; margin-left: -{{size}}px',
					'{{WRAPPER}} .tk-twentytwenty-up-arrow' => 'border: {{SIZE}}px inset transparent; border-bottom: {{SIZE}}px solid; margin-left: -{{size}}px',
				),
			)
		);

		$this->add_control(
			'tk_image_comparison_arrows_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-twentytwenty-left-arrow' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} .tk-twentytwenty-right-arrow' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .tk-twentytwenty-down-arrow' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .tk-twentytwenty-up-arrow' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_img_compare_separator_style_settings',
			array(
				'label'     => __( 'Separator', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tk_image_comparison_add_drag_handle' => 'yes',
					'tk_image_comparison_add_separator'   => 'yes',
				),
			)
		);

		$this->add_control(
			'tk_img_compare_separator_background_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-twentytwenty-handle:after, {{WRAPPER}} .tk-twentytwenty-handle:before'  => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_img_compare_separator_spacing',
			array(
				'label'       => __( 'Spacing', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .tk-twentytwenty-horizontal .tk-twentytwenty-handle:after' => 'top: {{SIZE}}%;',
					'{{WRAPPER}} .tk-twentytwenty-horizontal .tk-twentytwenty-handle:before' => 'bottom: {{SIZE}}%;',
					'{{WRAPPER}} .tk-twentytwenty-vertical .tk-twentytwenty-handle:after' => 'right: {{SIZE}}%;',
					'{{WRAPPER}} .tk-twentytwenty-vertical .tk-twentytwenty-handle:before' => 'left: {{SIZE}}%;',
				),
			)
		);

		$this->add_responsive_control(
			'tk_img_compare_separator_width',
			array(
				'label'       => __( 'Height', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em' ),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .tk-twentytwenty-vertical .tk-twentytwenty-handle:before,{{WRAPPER}} .tk-twentytwenty-vertical .tk-twentytwenty-handle:after' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'tk_image_comparison_add_drag_handle' => 'yes',
					'tk_image_comparison_add_separator'   => 'yes',
					'tk_image_comparison_orientation'     => 'vertical',
				),
			)
		);

		$this->add_responsive_control(
			'tk_img_compare_separator_height',
			array(
				'label'       => __( 'Width', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em', '%' ),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .tk-twentytwenty-horizontal .tk-twentytwenty-handle:after,{{WRAPPER}} .tk-twentytwenty-horizontal .tk-twentytwenty-handle:before' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'tk_image_comparison_add_drag_handle' => 'yes',
					'tk_image_comparison_add_separator'   => 'yes',
					'tk_image_comparison_orientation'     => 'horizontal',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tk_img_compare_separator_shadow',
				'selector' => '{{WRAPPER}} .tk-twentytwenty-handle:after,{{WRAPPER}} .tk-twentytwenty-handle:before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_image_comparison_contents_wrapper_style_settings',
			array(
				'label' => __( 'Container', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tk_image_comparison_overlay_background',
			array(
				'label'     => __( 'Overlay Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-twentytwenty-overlay.tk-twentytwenty-show:hover'  => 'background: {{VALUE}};',
				),
				'condition' => array(
					'tk_image_comparison_overlay' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tk_image_comparison_contents_wrapper_border',
				'selector' => '{{WRAPPER}} .tk-images-compare-container',
			)
		);

		$this->add_control(
			'tk_image_comparison_contents_wrapper_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-images-compare-container' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tk_image_comparison_contents_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .tk-images-compare-container',
			)
		);

		$this->add_responsive_control(
			'tk_image_comparison_contents_wrapper_margin',
			array(
				'label'      => __( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-images-compare-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
		/**=================================================================== */

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

	}

	/*
	*==================================================================================
	*
	*=============================== Widget Output ====================================
	*
	*==================================================================================
	*/

	/**
	 * Render imagecomparison widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$original_image = $settings['tk_image_comparison_original_image'];

		$modified_image = $settings['tk_image_comparison_modified_image'];

		$original_image_src = Group_Control_Image_Size::get_attachment_image_src( $original_image['id'], 'prmium_img_compare_images_size', $settings );

		$original_image_src = empty( $original_image_src ) ? $original_image['url'] : $original_image_src;

		$modified_image_src = Group_Control_Image_Size::get_attachment_image_src( $modified_image['id'], 'prmium_img_compare_images_size', $settings );

		$modified_image_src = empty( $modified_image_src ) ? $modified_image['url'] : $modified_image_src;

		$img_compare_setttings = array(
			'orientation'  => $settings['tk_image_comparison_orientation'],
			'visibleRatio' => ! empty( $settings['tk_img_compare_visible_ratio'] ) ? $settings['tk_img_compare_visible_ratio'] : 0.1,
			'switchBefore' => ( 'yes' === $settings['tk_img_compare_original_img_label_switcher'] ) ? true : false,
			'beforeLabel'  => ( 'yes' === $settings['tk_img_compare_original_img_label_switcher'] && ! empty( $settings['tk_img_compare_original_img_label'] ) ) ? $settings['tk_img_compare_original_img_label'] : '',
			'switchAfter'  => ( 'yes' === $settings['tk_image_comparison_modified_image_label_switcher'] ) ? true : false,
			'afterLabel'   => ( 'yes' === $settings['tk_image_comparison_modified_image_label_switcher'] && ! empty( $settings['tk_image_comparison_modified_image_label'] ) ) ? $settings['tk_image_comparison_modified_image_label'] : '',
			'mouseMove'    => ( 'mousemove' === $settings['tk_image_comparison_interaction_mode'] ) ? true : false,
			'clickMove'    => ( 'click' === $settings['tk_image_comparison_interaction_mode'] ) ? true : false,
			'showDrag'     => ( 'yes' === $settings['tk_image_comparison_add_drag_handle'] ) ? true : false,
			'showSep'      => ( 'yes' === $settings['tk_image_comparison_add_separator'] ) ? true : false,
			'overlay'      => ( 'yes' === $settings['tk_image_comparison_overlay'] ) ? false : true,
			'beforePos'    => $settings['tk_img_compare_original_label_position'],
			'afterPos'     => $settings['tk_img_compare_modified_label_position'],
			'verbeforePos' => $settings['tk_img_compare_original_hor_label_position'],
			'verafterPos'  => $settings['tk_img_compare_modified_hor_label_position'],
		);

		$this->add_render_attribute( 'image-compare', 'id', 'tk-image-comparison-contents-wrapper-' . $this->get_id() );

		$this->add_render_attribute( 'image-compare', 'class', array( 'tk-images-compare-container', 'tk-twentytwenty-container' ) );

		$this->add_render_attribute( 'image-compare', 'data-settings', wp_json_encode( $img_compare_setttings ) );

		$this->add_render_attribute( 'first-image', 'src', $original_image_src );

		$this->add_render_attribute( 'second-image', 'src', $modified_image_src );

		$this->add_render_attribute( 'first-image', 'alt', $settings['tk_img_compare_original_img_label'] );

		$this->add_render_attribute( 'second-image', 'alt', $settings['tk_image_comparison_modified_image_label'] );
		?>

	<div class="tk-image-comparison-contents-wrapper tk-twentytwenty-wrapper">
		<div <?php echo $this->get_render_attribute_string( 'image-compare' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<img <?php echo $this->get_render_attribute_string( 'first-image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<img <?php echo $this->get_render_attribute_string( 'second-image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		</div>
	</div>

		<?php

	}

}
