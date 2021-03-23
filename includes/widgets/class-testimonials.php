<?php
/**
 * Elementor testimonials Widget.
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
use Elementor\Plugin;
use Elementor\Core\Schemes;
use Elementor\Control_Media;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Responsive\Responsive;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;


/**
 * Testimonials class
 */
class Testimonials extends \Elementor\Widget_Base {

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
			'taman-kit-testimonials',
			TAMAN_KIT_PRO_URL . 'public/css/widgets/testimonial.css',
			array(),
			\TamanKitProHelpers::taman_kit_pro_ver(),
			'all'
		);
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_style_depends() {
		return array( 'taman-kit-testimonials' );
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve testimonials widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-testimonials';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve testimonials widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Testimonials', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve testimonials widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-testimonial-carousel';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the testimonials widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'taman-kit' );
	}

	public function get_script_depends() {
		return array(
			'tk-slick',
			'jquery-resize',
			'imagesloaded',
			'taman-kit-pro',
		);
	}


	/**
	 * Register testimonials widget controls.
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
			'section_testimonials',
			array(
				'label' => __( 'Testimonials', 'taman-kit-pro' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'Image', 'taman-kit-pro' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'   => __( 'Name', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'John Doe', 'taman-kit-pro' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'position',
			array(
				'label'   => __( 'Position', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'CEO', 'taman-kit-pro' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'rating',
			array(
				'label' => __( 'Rating', 'taman-kit-pro' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 0,
				'max'   => 5,
				'step'  => 0.1,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'taman-kit-pro' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'placeholder' => 'https://www.your-link.com',
			)
		);

		$this->add_control(
			'testimonials',
			array(
				'label'   => '',
				'type'    => Controls_Manager::REPEATER,
				'default' => array(
					array(
						'name'     => __( 'John Doe', 'taman-kit-pro' ),
						'position' => __( 'CEO', 'taman-kit-pro' ),
						'content'  => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'taman-kit-pro' ),
					),
					array(
						'name'     => __( 'John Doe', 'taman-kit-pro' ),
						'position' => __( 'CEO', 'taman-kit-pro' ),
						'content'  => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'taman-kit-pro' ),
					),
					array(
						'name'     => __( 'John Doe', 'taman-kit-pro' ),
						'position' => __( 'CEO', 'taman-kit-pro' ),
						'content'  => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'taman-kit-pro' ),
					),
				),
				'fields'  => $repeater->get_controls(),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'     => __( 'Layout', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'carousel',
				'options'   => array(
					'carousel'  => __( 'Carousel', 'taman-kit-pro' ),
					'slideshow' => __( 'Slideshow', 'taman-kit-pro' ),
					'grid'      => __( 'Grid', 'taman-kit-pro' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'              => __( 'Columns', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
				),
				'prefix_class'       => 'elementor-grid%s-',
				'frontend_available' => true,
				'condition'          => array(
					'layout' => 'grid',
				),
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'   => __( 'Skin', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'skin-1',
				'options' => array(
					'skin-1' => __( 'Skin 1', 'taman-kit-pro' ),
					'skin-2' => __( 'Skin 2', 'taman-kit-pro' ),
					'skin-3' => __( 'Skin 3', 'taman-kit-pro' ),
					'skin-4' => __( 'Skin 4', 'taman-kit-pro' ),
					'skin-5' => __( 'Skin 5', 'taman-kit-pro' ),
					'skin-6' => __( 'Skin 6', 'taman-kit-pro' ),
					'skin-7' => __( 'Skin 7', 'taman-kit-pro' ),
					'skin-8' => __( 'Skin 8', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'content_style',
			array(
				'label'        => __( 'Content Style', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => array(
					'default' => __( 'Default', 'taman-kit-pro' ),
					'bubble'  => __( 'Bubble', 'taman-kit-pro' ),
				),
				'prefix_class' => 'tk-testimonials-content-',
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'   => __( 'Show Image', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''   => __( 'Yes', 'taman-kit-pro' ),
					'no' => __( 'No', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'image_position',
			array(
				'label'     => __( 'Image Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'inline',
				'options'   => array(
					'inline'  => __( 'Inline', 'taman-kit-pro' ),
					'stacked' => __( 'Stacked', 'taman-kit-pro' ),
				),
				'condition' => array(
					'show_image' => '',
					'skin'       => array( 'skin-1', 'skin-2', 'skin-3', 'skin-4' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'full',
				'condition' => array(
					'show_image' => '',
				),
			)
		);

		$this->add_control(
			'show_quote',
			array(
				'label'   => __( 'Show Quote', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => array(
					''   => __( 'Yes', 'taman-kit-pro' ),
					'no' => __( 'No', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'quote_position',
			array(
				'label'        => __( 'Quote Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'above',
				'options'      => array(
					'above'        => __( 'Above Content', 'taman-kit-pro' ),
					'before'       => __( 'Before Content', 'taman-kit-pro' ),
					'before-after' => __( 'Before/After Content', 'taman-kit-pro' ),
				),
				'prefix_class' => 'tk-testimonials-quote-position-',
				'condition'    => array(
					'show_quote' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_order_style',
			array(
				'label'     => __( 'Order', 'taman-kit-pro' ),
				'condition' => array(
					'image_position' => 'stacked',
				),
			)
		);

		$this->add_control(
			'image_order',
			array(
				'label'     => __( 'Image', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 4,
				'step'      => 1,
				'condition' => array(
					'image_position' => 'stacked',
				),
			)
		);

		$this->add_control(
			'name_order',
			array(
				'label'     => __( 'Name', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2,
				'min'       => 1,
				'max'       => 4,
				'step'      => 1,
				'condition' => array(
					'image_position' => 'stacked',
				),
			)
		);

		$this->add_control(
			'position_order',
			array(
				'label'     => __( 'Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'min'       => 1,
				'max'       => 4,
				'step'      => 1,
				'condition' => array(
					'image_position' => 'stacked',
				),
			)
		);

		$this->add_control(
			'rating_order',
			array(
				'label'     => __( 'Rating', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4,
				'min'       => 1,
				'max'       => 4,
				'step'      => 1,
				'condition' => array(
					'image_position' => 'stacked',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_options',
			array(
				'label'     => __( 'Slider Options', 'taman-kit-pro' ),
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
				),
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'     => __( 'Effect', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slide',
				'options'   => array(
					'slide' => __( 'Slide', 'taman-kit-pro' ),
					'fade'  => __( 'Fade', 'taman-kit-pro' ),
				),
				'condition' => array(
					'layout' => 'slideshow',
				),
			)
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides Per View', 'taman-kit-pro' ),
				'options'            => $slides_per_view,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'condition'          => array(
					'layout' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides to Scroll', 'taman-kit-pro' ),
				'description'        => __( 'Set how many slides are scrolled per swipe.', 'taman-kit-pro' ),
				'options'            => $slides_per_view,
				'default'            => '1',
				'tablet_default'     => '1',
				'mobile_default'     => '1',
				'condition'          => array(
					'layout' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'condition'    => array(
					'layout' => array( 'carousel', 'slideshow' ),
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'taman-kit-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 3000,
				'frontend_available' => true,
				'condition'          => array(
					'layout'   => array( 'carousel', 'slideshow' ),
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'infinite_loop',
			array(
				'label'        => __( 'Infinite Loop', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'layout' => array( 'carousel', 'slideshow' ),
				),
			)
		);

		$this->add_control(
			'animation_speed',
			array(
				'label'              => __( 'Animation Speed', 'taman-kit-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 600,
				'frontend_available' => true,
				'condition'          => array(
					'layout' => array( 'carousel', 'slideshow' ),
				),
			)
		);

		$this->add_control(
			'center_mode',
			array(
				'label'              => __( 'Center Mode', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'label_on'           => __( 'Yes', 'taman-kit-pro' ),
				'label_off'          => __( 'No', 'taman-kit-pro' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'separator'          => 'before',
			)
		);

		$this->add_responsive_control(
			'center_padding',
			array(
				'label'          => __( 'Center Padding', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 40,
					'unit' => 'px',
				),
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 500,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'condition'      => array(
					'center_mode' => 'yes',
				),
			)
		);

		$this->add_control(
			'name_navigation_heading',
			array(
				'label'     => __( 'Navigation', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
				),
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'        => __( 'Arrows', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'layout' => array( 'carousel', 'slideshow' ),
				),
			)
		);

		$this->add_control(
			'thumbnail_nav',
			array(
				'label'              => __( 'Thumbnail Navigation', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => __( 'Yes', 'taman-kit-pro' ),
				'label_off'          => __( 'No', 'taman-kit-pro' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'layout' => 'slideshow',
				),
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'              => __( 'Dots', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => __( 'Yes', 'taman-kit-pro' ),
				'label_off'          => __( 'No', 'taman-kit-pro' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'conditions'         => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'carousel',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'orientation',
			array(
				'label'     => __( 'Orientation', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => __( 'Horizontal', 'taman-kit-pro' ),
					'vertical'   => __( 'Vertical', 'taman-kit-pro' ),
				),
				'separator' => 'before',
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
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

		$this->start_controls_section(
			'section_testimonial_style',
			array(
				'label' => __( 'Testimonial', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'row_spacing',
			array(
				'label'     => __( 'Row Spacing', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonials .tk-grid-item-wrap' => 'margin-bottom: {{SIZE}}px;',
				),
				'condition' => array(
					'layout' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'column_spacing',
			array(
				'label'     => __( 'Column Spacing', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonials .tk-testimonial-slide, {{WRAPPER}} .tk-testimonials .tk-grid-item-wrap' => 'padding-left: calc({{SIZE}}px/2); padding-right: calc({{SIZE}}px/2);',
					'{{WRAPPER}} .tk-testimonials .slick-list, {{WRAPPER}} .tk-elementor-grid' => 'margin-left: calc(-{{SIZE}}px/2); margin-right: calc(-{{SIZE}}px/2);',
				),
				'separator' => 'after',
				'condition' => array(
					'layout!' => 'slideshow',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'slide_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tk-testimonial, {{WRAPPER}} .tk-testimonials-wrap .tk-testimonials-thumb-item:before',
			)
		);

		$this->add_control(
			'slide_border',
			array(
				'label'     => __( 'Border', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial, {{WRAPPER}} .tk-testimonials-wrap .tk-testimonials-thumb-item:before' => 'border-style: solid',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'slide_border_color',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tk-testimonials-wrap .tk-testimonials-thumb-item:before' => 'border-color: transparent transparent {{VALUE}} {{VALUE}};',
				),
				'condition' => array(
					'slide_border' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'slide_border_width',
			array(
				'label'     => __( 'Border Width', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial, {{WRAPPER}} .tk-testimonials-wrap .tk-testimonials-thumb-item:before' => 'border-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tk-testimonials-wrap .tk-testimonials-thumb-item:before' => 'top: -{{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'slide_border' => 'yes',
				),
			)
		);

		$this->add_control(
			'slide_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-testimonial' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'slide_box_shadow',
				'selector' => '{{WRAPPER}} .tk-testimonial',
			)
		);

		$this->add_responsive_control(
			'slide_padding',
			array(
				'label'     => __( 'Inner Padding', 'taman-kit-pro' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'slide_outer_padding',
			array(
				'label'       => __( 'Outer Padding', 'taman-kit-pro' ),
				'description' => __( 'You must add outer padding for showing box shadow', 'taman-kit-pro' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'selectors'   => array(
					'{{WRAPPER}} .tk-testimonial-outer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .tk-testimonials-wrap .tk-testimonials-thumb-item:before' => 'margin-top: -{{BOTTOM}}{{UNIT}}',
				),
				'condition'   => array(
					'layout' => array( 'carousel', 'slideshow' ),
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Content
		 */
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Content', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-content, {{WRAPPER}} .tk-testimonial-content:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'content_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-testimonial-content',
			)
		);

		$this->add_control(
			'border',
			array(
				'label'     => __( 'Border', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-content, {{WRAPPER}} .tk-testimonial-content:after' => 'border-style: solid',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .tk-testimonial-content:after' => 'border-color: transparent {{VALUE}} {{VALUE}} transparent',
				),
				'condition' => array(
					'border' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'border_width',
			array(
				'label'     => __( 'Border Width', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-content, {{WRAPPER}} .tk-testimonial-content:after' => 'border-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tk-testimonial-skin-1 .tk-testimonial-content:after' => 'margin-top: -{{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tk-testimonial-skin-2 .tk-testimonial-content:after' => 'margin-bottom: -{{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tk-testimonial-skin-3 .tk-testimonial-content:after' => 'margin-left: -{{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tk-testimonial-skin-4 .tk-testimonial-content:after' => 'margin-right: -{{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'border' => 'yes',
				),
			)
		);

		$this->add_control(
			'content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-testimonial-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_gap',
			array(
				'label'          => __( 'Gap', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => '',
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'separator'      => 'before',
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonial-skin-1 .tk-testimonial-content, {{WRAPPER}} .tk-testimonial-skin-5 .tk-testimonial-content, {{WRAPPER}} .tk-testimonial-skin-6 .tk-testimonial-content, {{WRAPPER}} .tk-testimonial-skin-7 .tk-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-testimonial-skin-2 .tk-testimonial-content' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-testimonial-skin-3 .tk-testimonial-content' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-testimonial-skin-4 .tk-testimonial-content' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'content_text_alignment',
			array(
				'label'     => __( 'Text Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'default'   => 'center',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'details_h_alignment',
			array(
				'label'        => __( 'Name and Position Alignment', 'taman-kit-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'toggle'       => false,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'center',
				'prefix_class' => 'tk-testimonials-h-align-',
				'condition'    => array(
					'skin' => array( 'skin-1', 'skin-2', 'skin-5', 'skin-6', 'skin-7' ),
				),
			)
		);

		$this->add_control(
			'details_v_alignment',
			array(
				'label'                => __( 'Name and Position Alignment', 'taman-kit-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'toggle'               => false,
				'default'              => 'middle',
				'options'              => array(
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
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'prefix_class'         => 'tk-testimonials-v-align-',
				'condition'            => array(
					'skin' => array( 'skin-3', 'skin-4' ),
				),
			)
		);

		$this->add_control(
			'image_v_alignment',
			array(
				'label'                => __( 'Image Alignment', 'taman-kit-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'toggle'               => false,
				'default'              => 'middle',
				'options'              => array(
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
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'prefix_class'         => 'tk-testimonials-v-align-',
				'condition'            => array(
					'skin' => array( 'skin-5', 'skin-6' ),
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .tk-testimonial-content',
			)
		);

		$this->add_control(
			'name_style_heading',
			array(
				'label'     => __( 'Name', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'name_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-testimonial-name',
			)
		);

		$this->add_responsive_control(
			'name_gap',
			array(
				'label'          => __( 'Gap', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => '',
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonial-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'position_style_heading',
			array(
				'label'     => __( 'Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'position_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-position' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'position_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-testimonial-position',
			)
		);

		$this->add_responsive_control(
			'position_gap',
			array(
				'label'          => __( 'Gap', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => '',
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonial-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'quote_style_heading',
			array(
				'label'     => __( 'Quote', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_quote' => '',
				),
			)
		);

		$this->add_control(
			'quote_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonial-text:before, {{WRAPPER}} .tk-testimonial-text:after' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_quote' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'quote_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'selector'  => '{{WRAPPER}} .tk-testimonial-text:before, {{WRAPPER}} .tk-testimonial-text:after',
				'condition' => array(
					'show_quote' => '',
				),
			)
		);

		$this->add_responsive_control(
			'quote_margin',
			array(
				'label'              => __( 'Margin', 'taman-kit-pro' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', '%' ),
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}}.tk-testimonials-quote-position-above .tk-testimonial-text:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					'show_quote'     => '',
					'quote_position' => array( 'above', 'before' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => __( 'Image', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_image' => '',
				),
			)
		);

		$this->add_responsive_control(
			'image_size',
			array(
				'label'          => __( 'Size', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 200,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonial-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tk-testimonials-content-bubble.tk-testimonials-h-align-right .tk-testimonial-skin-1 .tk-testimonial-content:after, {{WRAPPER}}.tk-testimonials-content-bubble.tk-testimonials-h-align-right .tk-testimonial-skin-2 .tk-testimonial-content:after' => 'right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}}.tk-testimonials-content-bubble.tk-testimonials-h-align-left .tk-testimonial-skin-1 .tk-testimonial-content:after, {{WRAPPER}}.tk-testimonials-content-bubble.tk-testimonials-h-align-left .tk-testimonial-skin-2 .tk-testimonial-content:after' => 'left: calc({{SIZE}}{{UNIT}}/2);',
				),
				'condition'      => array(
					'show_image' => '',
				),
			)
		);

		$this->add_responsive_control(
			'image_gap',
			array(
				'label'          => __( 'Gap', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonials-image-stacked .tk-testimonial-image, {{WRAPPER}} .tk-testimonial-skin-7 .tk-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-testimonials-image-inline .tk-testimonial-image, {{WRAPPER}} .tk-testimonial-skin-5 .tk-testimonial-image, {{WRAPPER}} .tk-testimonial-skin-8 .tk-testimonial-image' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.tk-testimonials-h-align-right .tk-testimonials-image-inline .tk-testimonial-image, {{WRAPPER}} .tk-testimonial-skin-6 .tk-testimonial-image' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
				),
				'condition'      => array(
					'show_image' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'image_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-testimonial-image img',
				'condition'   => array(
					'show_image' => '',
				),
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-testimonial-image, {{WRAPPER}} .tk-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_image' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'image_box_shadow',
				'selector'  => '{{WRAPPER}} .tk-testimonial-image img',
				'condition' => array(
					'show_image' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rating_style',
			array(
				'label' => __( 'Rating', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'star_style',
			array(
				'label'        => __( 'Icon', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'star_fontawesome' => 'Font Awesome',
					'star_unicode'     => 'Unicode',
				),
				'default'      => 'star_fontawesome',
				'render_type'  => 'template',
				'prefix_class' => 'elementor--star-style-',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'unmarked_star_style',
			array(
				'label'       => __( 'Unmarked Style', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'solid'   => array(
						'title' => __( 'Solid', 'taman-kit-pro' ),
						'icon'  => 'fa fa-star',
					),
					'outline' => array(
						'title' => __( 'Outline', 'taman-kit-pro' ),
						'icon'  => 'fa fa-star-o',
					),
				),
				'default'     => 'solid',
			)
		);

		$this->add_control(
			'star_size',
			array(
				'label'     => __( 'Size', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'star_space',
			array(
				'label'     => __( 'Spacing', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'stars_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'stars_unmarked_color',
			array(
				'label'     => __( 'Unmarked Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'     => __( 'Navigation Arrows', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'select_arrow',
			array(
				'label'                  => __( 'Choose Arrow', 'taman-kit-pro' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'arrow',
				'label_block'            => false,
				'default'                => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => 'svg',
				'recommended'            => array(
					'fa-regular' => array(
						'arrow-alt-circle-right',
						'caret-square-right',
						'hand-point-right',
					),
					'fa-solid'   => array(
						'angle-right',
						'angle-double-right',
						'chevron-right',
						'chevron-circle-right',
						'arrow-right',
						'long-arrow-alt-right',
						'caret-right',
						'caret-square-right',
						'arrow-circle-right',
						'arrow-alt-circle-right',
						'toggle-right',
						'hand-point-right',
					),
				),
				'condition'              => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => __( 'Arrows Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_horitonal_position',
			array(
				'label'      => __( 'Horizontal Alignment', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 450,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_vertical_position',
			array(
				'label'      => __( 'Vertical Alignment', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -400,
						'max'  => 400,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-arrow-next, {{WRAPPER}} .tk-arrow-prev' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			array(
				'label'     => __( 'Normal', 'taman-kit-pro' ),
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-slider-arrow' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_color_normal',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-slider-arrow' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'arrows_border_normal',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-slider-arrow',
				'separator'   => 'before',
				'condition'   => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			array(
				'label'     => __( 'Hover', 'taman-kit-pro' ),
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-slider-arrow:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_color_hover',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-slider-arrow:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-slider-arrow:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-slider-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'condition'  => array(
					'layout' => array( 'carousel', 'slideshow' ),
					'arrows' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'      => __( 'Pagination: Dots', 'taman-kit-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'dots_position',
			array(
				'label'        => __( 'Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'inside'  => __( 'Inside', 'taman-kit-pro' ),
					'outside' => __( 'Outside', 'taman-kit-pro' ),
				),
				'default'      => 'outside',
				'prefix_class' => 'tk-slick-slider-dots-',
				'conditions'   => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'dots_size',
			array(
				'label'      => __( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots li button' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'dots_gap',
			array(
				'label'      => __( 'Gap Between Dots', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots li' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'dots_top_spacing',
			array(
				'label'      => __( 'Top Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'dots_vertical_alignment',
			array(
				'label'      => __( 'Vertical Alignment', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			array(
				'label'      => __( 'Normal', 'taman-kit-pro' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'dots_color_normal',
			array(
				'label'      => __( 'Color', 'taman-kit-pro' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots li' => 'background: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'active_dot_color_normal',
			array(
				'label'      => __( 'Active Color', 'taman-kit-pro' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots li.slick-active' => 'background: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'dots_border_normal',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-slick-slider .slick-dots li',
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'dots_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			array(
				'label'      => __( 'Hover', 'taman-kit-pro' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'dots_color_hover',
			array(
				'label'      => __( 'Color', 'taman-kit-pro' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots li:hover' => 'background: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'dots_border_color_hover',
			array(
				'label'      => __( 'Border Color', 'taman-kit-pro' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-slick-slider .slick-dots li:hover' => 'border-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'carousel',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'layout',
									'operator' => '==',
									'value'    => 'slideshow',
								),
								array(
									'name'     => 'dots',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'thumbnail_nav',
									'operator' => '!==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumbnail_nav_style',
			array(
				'label'     => __( 'Thumbnail Navigation', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_nav_thumbs_size',
			array(
				'label'          => __( 'Thumbnails Size', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 200,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonials-thumb-item img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_nav_thumbs_gap',
			array(
				'label'          => __( 'Thumbnails Gap', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonials-thumb-item-wrap' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-testimonials-thumb-pagination' => 'margin-left: -{{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_nav_thumbs_arrow_size',
			array(
				'label'          => __( 'Arrow Size', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 200,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonials-wrap .tk-testimonials-thumb-item:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_nav_thumb_nav_spacing',
			array(
				'label'          => __( 'Top Spacing', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 30,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-testimonials-thumb-item' => 'padding-top: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_thumbnail_nav_style' );

		$this->start_controls_tab(
			'tab_thumbnail_nav_normal',
			array(
				'label'     => __( 'Normal', 'taman-kit-pro' ),
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_nav_grayscale_normal',
			array(
				'label'        => __( 'Grayscale', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumbnail_nav_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-testimonials-thumb-image',
				'condition'   => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_nav_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-testimonials-thumb-image, {{WRAPPER}} .tk-testimonials-thumb-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'thumbnail_nav_box_shadow',
				'selector'  => '{{WRAPPER}} .tk-testimonials-thumb-image',
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_nav_scale_normal',
			array(
				'label'     => __( 'Scale', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0.5,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonials-thumb-image' => 'transform: scale({{SIZE}});',
				),
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumbnail_nav_hover',
			array(
				'label'     => __( 'Hover', 'taman-kit-pro' ),
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_nav_grayscale_hover',
			array(
				'label'        => __( 'Grayscale', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'thumbnail_nav_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonials-thumb-image:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thumbnail_nav_box_shadow_hover',
				'selector' => '{{WRAPPER}} .tk-testimonials-thumb-image:hover',
			)
		);

		$this->add_control(
			'thumbnail_nav_scale_hover',
			array(
				'label'     => __( 'Scale', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0.5,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-testimonials-thumb-image:hover' => 'transform: scale({{SIZE}});',
				),
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumbnail_nav_active',
			array(
				'label'     => __( 'Active', 'taman-kit-pro' ),
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_nav_grayscale_active',
			array(
				'label'        => __( 'Grayscale', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'thumbnail_nav_border_color_active',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-active-slide .tk-testimonials-thumb-image' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'thumbnail_nav_box_shadow_active',
				'selector'  => '{{WRAPPER}} .tk-active-slide .tk-testimonials-thumb-image',
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_nav_scale_active',
			array(
				'label'     => __( 'Scale', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0.5,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-active-slide .tk-testimonials-thumb-image' => 'transform: scale({{SIZE}});',
				),
				'condition' => array(
					'layout'        => 'slideshow',
					'thumbnail_nav' => 'yes',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}
	/**================================================================ */


	/**
	 * Slider Settings.
	 *
	 * @access public
	 */
	public function slider_settings() {
		$settings = $this->get_settings();

		if ( 'carousel' === $settings['layout'] ) {
			$slides_to_show        = ( $settings['slides_per_view'] ) ? absint( $settings['slides_per_view'] ) : 3;
			$slides_to_show_tablet = ( $settings['slides_per_view_tablet'] ) ? absint( $settings['slides_per_view_tablet'] ) : 2;
			$slides_to_show_mobile = ( $settings['slides_per_view_mobile'] ) ? absint( $settings['slides_per_view_mobile'] ) : 1;

			$slides_to_scroll        = ( $settings['slides_to_scroll'] ) ? absint( $settings['slides_to_scroll'] ) : 1;
			$slides_to_scroll_tablet = ( $settings['slides_to_scroll_tablet'] ) ? absint( $settings['slides_to_scroll_tablet'] ) : 1;
			$slides_to_scroll_mobile = ( $settings['slides_to_scroll_mobile'] ) ? absint( $settings['slides_to_scroll_mobile'] ) : 1;
		} else {
			$slides_to_show          = 1;
			$slides_to_show_tablet   = 1;
			$slides_to_show_mobile   = 1;
			$slides_to_scroll        = 1;
			$slides_to_scroll_tablet = 1;
			$slides_to_scroll_mobile = 1;
		}

		$slider_options = array(
			'slidesToShow'   => $slides_to_show,
			'slidesToScroll' => $slides_to_scroll,
			'autoplay'       => ( 'yes' === $settings['autoplay'] ),
			'autoplaySpeed'  => ( $settings['autoplay_speed'] ) ? $settings['autoplay_speed'] : 3000,
			'speed'          => ( $settings['animation_speed'] ) ? $settings['animation_speed'] : 600,
			'fade'           => ( 'fade' === $settings['effect'] && 'slideshow' === $settings['layout'] ),
			'vertical'       => ( 'vertical' === $settings['orientation'] ),
			'adaptiveHeight' => false,
			'loop'           => ( 'yes' === $settings['infinite_loop'] ),
			'rtl'            => is_rtl(),
		);

		if ( 'yes' === $settings['center_mode'] ) {
			$center_mode           = true;
			$center_padding        = ( $settings['center_padding']['size'] ) ? $settings['center_padding']['size'] . 'px' : '0px';
			$center_padding_tablet = ( $settings['center_padding_tablet']['size'] ) ? $settings['center_padding_tablet']['size'] . 'px' : '0px';
			$center_padding_mobile = ( $settings['center_padding_mobile']['size'] ) ? $settings['center_padding_mobile']['size'] . 'px' : '0px';

			$slider_options['centerMode']    = $center_mode;
			$slider_options['centerPadding'] = $center_padding;
		} else {
			$center_mode           = false;
			$center_padding_tablet = '0px';
			$center_padding_mobile = '0px';
		}

		if ( 'yes' === $settings['arrows'] ) {
			$migration_allowed = Icons_Manager::is_migration_allowed();

			if ( ! isset( $settings['arrow'] ) && ! $migration_allowed ) {
				// add old default.
				$settings['arrow'] = 'fa fa-angle-right';
			}

			$has_icon = ! empty( $settings['arrow'] );

			if ( ! $has_icon && ! empty( $settings['select_arrow']['value'] ) ) {
				$has_icon = true;
			}

			$migrated = isset( $settings['__fa4_migrated']['select_arrow'] );
			$is_new   = ! isset( $settings['arrow'] ) && $migration_allowed;

			if ( $is_new || $migrated ) {
				$arrow = $settings['select_arrow']['value'];
			}

			if ( $arrow ) {
				$next_arrow = $arrow;
				$prev_arrow = str_replace( 'right', 'left', $arrow );
			} else {
				$next_arrow = 'fa fa-angle-right';
				$prev_arrow = 'fa fa-angle-left';
			}

			$slider_options['arrows']    = true;
			$slider_options['prevArrow'] = '<div class="tk-slider-arrow tk-arrow tk-arrow-prev"><i class="' . $prev_arrow . '"></i></div>';
			$slider_options['nextArrow'] = '<div class="tk-slider-arrow tk-arrow tk-arrow-next"><i class="' . $next_arrow . '"></i></div>';
		} else {
			$slider_options['arrows'] = false;
		}

		if ( 'carousel' === $settings['layout'] && 'yes' === $settings['dots'] ) {
			$slider_options['dots'] = true;
		} elseif ( 'slideshow' === $settings['layout'] && 'yes' === $settings['dots'] && 'yes' !== $settings['thumbnail_nav'] ) {
			$slider_options['dots'] = true;
		} else {
			$slider_options['dots'] = false;
		}

		$elementor_bp_tablet = get_option( 'elementor_viewport_lg' );
		$elementor_bp_mobile = get_option( 'elementor_viewport_md' );
		$bp_tablet           = ! empty( $elementor_bp_tablet ) ? $elementor_bp_tablet : 1025;
		$bp_mobile           = ! empty( $elementor_bp_mobile ) ? $elementor_bp_mobile : 768;

		$slider_options['responsive'] = array(
			array(
				'breakpoint' => $bp_tablet,
				'settings'   => array(
					'slidesToShow'   => $slides_to_show_tablet,
					'slidesToScroll' => $slides_to_scroll_tablet,
					'centerMode'     => $center_mode,
					'centerPadding'  => $center_padding_tablet,
				),
			),
			array(
				'breakpoint' => $bp_mobile,
				'settings'   => array(
					'slidesToShow'   => $slides_to_show_mobile,
					'slidesToScroll' => $slides_to_scroll_mobile,
					'centerMode'     => $center_mode,
					'centerPadding'  => $center_padding_mobile,
				),
			),
		);

		$this->add_render_attribute(
			'testimonials',
			array(
				'data-slider-settings' => wp_json_encode( $slider_options ),
			)
		);
	}

	/**
	 * Testimonial_footer function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_testimonial_footer( $item, $index ) {
		$settings = $this->get_settings_for_display();
		?>
		<div class="tk-testimonial-footer">
			<div class="tk-testimonial-footer-inner">
				<?php
				if ( 'stacked' === $settings['image_position'] ) {
					$elements_order = array();

					$elements_order['image']    = $settings['image_order'];
					$elements_order['name']     = $settings['name_order'];
					$elements_order['position'] = $settings['position_order'];
					$elements_order['rating']   = $settings['rating_order'];

					for ( $i = 0; $i <= 4; $i++ ) {
						if ( $i === $elements_order['image'] ) {
							$this->render_image( $item, $index );
						}

						if ( $i === $elements_order['name'] ) {
							$this->render_name( $item, $index );
						}

						if ( $i === $elements_order['position'] ) {
							$this->render_position( $item, $index );
						}

						if ( $i === $elements_order['rating'] ) {
							$this->render_stars( $item, $settings );
						}
					}
				} else {
					$this->render_image( $item, $index );
					?>
					<div class="tk-testimonial-cite">
						<?php
							$this->render_name( $item, $index );

							$this->render_position( $item, $index );

							$this->render_stars( $item, $settings );
						?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render_testimonial function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_testimonial_default( $item, $index ) {
		$settings = $this->get_settings_for_display();
		?>
		<div class="tk-testimonial-content">
			<?php
				$this->render_description( $item );
			?>
		</div>
		<?php
		$this->render_testimonial_footer( $item, $index );
	}

	/**
	 * Testimonial_skin_2 function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_testimonial_skin_2( $item, $index ) {
		$settings = $this->get_settings_for_display();

		$this->render_testimonial_footer( $item, $index );
		?>
		<div class="tk-testimonial-content">
			<?php
				$this->render_description( $item );
			?>
		</div>
		<?php
	}

	/**
	 * Testimonial_skin_3 function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_testimonial_skin_3( $item, $index ) {
		$settings = $this->get_settings_for_display();

		$this->render_testimonial_footer( $item, $index );
		?>
		<div class="tk-testimonial-content-wrap">
			<div class="tk-testimonial-content">
				<?php
					$this->render_description( $item );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Testimonial_skin_4 function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_testimonial_skin_4( $item, $index ) {
		$settings = $this->get_settings_for_display();

		$this->render_testimonial_footer( $item, $index );
		?>
		<div class="tk-testimonial-content-wrap">
			<div class="tk-testimonial-content">
				<?php
					$this->render_description( $item );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Testimonial_skin_5 function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_testimonial_skin_5( $item, $index ) {
		$settings = $this->get_settings_for_display();

		$this->render_image( $item, $index );
		?>
		<div class="tk-testimonial-content-wrap">
			<div class="tk-testimonial-content">
				<?php
					$this->render_description( $item );
				?>
			</div>
			<div class="tk-testimonial-footer">
				<div class="tk-testimonial-footer-inner">
					<div class="tk-testimonial-cite">
						<?php
							$this->render_name( $item, $index );

							$this->render_position( $item, $index );

							$this->render_stars( $item, $settings );
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Testimonial_skin_6 function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_testimonial_skin_8( $item, $index ) {
		$settings = $this->get_settings_for_display();
		?>
		<div class="tk-testimonial-content">
			<?php $this->render_image( $item, $index ); ?>
			<div class="tk-testimonial-content-wrap">
				<?php
					$this->render_description( $item );

					$this->render_stars( $item, $settings );
				?>
			</div>
		</div>
		<div class="tk-testimonial-footer">
			<div class="tk-testimonial-footer-inner">
				<div class="tk-testimonial-cite">
					<?php
						$this->render_name( $item, $index );

						$this->render_position( $item, $index );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render_thumbnails
	 */
	protected function render_thumbnails() {
		$settings   = $this->get_settings_for_display();
		$thumbnails = $settings['testimonials'];
		?>
		<div class="tk-testimonials-thumb-pagination">
			<?php
			foreach ( $thumbnails as $index => $item ) {
				if ( $item['image']['url'] ) {
					if ( $item['image']['id'] ) {
						$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'thumbnail', $settings );
					} else {
						$image_url = $item['image']['url'];
					}
					?>
					<div class="tk-testimonials-thumb-item-wrap tk-grid-item-wrap">
						<div class="tk-grid-item tk-testimonials-thumb-item">
							<div class="tk-testimonials-thumb-image">
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $item['image'] ) ); ?>" />
							</div>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render_image function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_image( $item, $index ) {
		$settings = $this->get_settings_for_display();

		if ( '' === $settings['show_image'] ) {
			$link_key = $this->get_repeater_setting_key( 'image_link', 'testimonials', $index );

			if ( ! empty( $item['link']['url'] ) ) {
				$this->add_link_attributes( $link_key, $item['link'] );
			}

			if ( $item['image']['url'] ) {
				?>
				<div class="tk-testimonial-image">
					<?php
					if ( $item['image']['id'] ) {
						$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'thumbnail', $settings );
					} else {
						$image_url = $item['image']['url'];
					}

					$image_html = '<img src="' . $image_url . '" alt="' . esc_attr( Control_Media::get_image_alt( $item['image'] ) ) . '">';
					if ( ! empty( $item['link']['url'] ) ) :
						$image_html = '<a ' . $this->get_render_attribute_string( $link_key ) . '>' . $image_html . '</a>';
					endif;
					echo $image_html;
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Render_name function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_name( $item, $index ) {
		$settings = $this->get_settings_for_display();

		if ( '' === $item['name'] ) {
			return;
		}

		$member_key = $this->get_repeater_setting_key( 'name', 'testimonials', $index );
		$link_key   = $this->get_repeater_setting_key( 'name_link', 'testimonials', $index );

		if ( ! empty( $item['link']['url'] ) ) {
			$this->add_link_attributes( $link_key, $item['link'] );
		}

		$this->add_render_attribute( $member_key, 'class', 'tk-testimonial-name' );

		if ( ! empty( $item['link']['url'] ) ) :
			?>
			<a <?php echo $this->get_render_attribute_string( $member_key ) . ' ' . $this->get_render_attribute_string( $link_key ); ?>><?php echo $item['name']; ?></a>
			<?php
		else :
			?>
			<div <?php echo $this->get_render_attribute_string( $member_key ); ?>><?php echo $item['name']; ?></div>
			<?php
		endif;
	}

	/**
	 * Render_position function
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 */
	protected function render_position( $item, $index ) {
		$settings = $this->get_settings_for_display();

		if ( '' === $item['position'] ) {
			return;
		}

		$position_key = $this->get_repeater_setting_key( 'position', 'testimonials', $index );
		$link_key     = $this->get_repeater_setting_key( 'position_link', 'testimonials', $index );

		if ( ! empty( $item['link']['url'] ) ) {
			$this->add_link_attributes( $link_key, $item['link'] );
		}

		$this->add_render_attribute( $position_key, 'class', 'tk-testimonial-position' );

		if ( ! empty( $item['link']['url'] ) ) :
			?>
			<a <?php echo $this->get_render_attribute_string( $position_key ) . ' ' . $this->get_render_attribute_string( $link_key ); ?>><?php echo $item['position']; ?></a>
			<?php
		else :
			?>
			<div <?php echo $this->get_render_attribute_string( $position_key ); ?>><?php echo $item['position']; ?></div>
			<?php
		endif;
	}

	/**
	 * Render_description function
	 *
	 * @param [type] $item .
	 */
	protected function render_description( $item ) {
		$settings = $this->get_settings_for_display();

		if ( '' === $item['content'] ) {
			return;
		}
		?>
		<div class="tk-testimonial-text">
			<?php echo $this->parse_text_editor( $item['content'] ); ?>
		</div>
		<?php
	}

	/**
	 * Render_stars function
	 *
	 * @param [type] $item .
	 * @param [type] $settings .
	 */
	protected function render_stars( $item, $settings ) {
		$icon = '&#61445;';

		if ( ! empty( $item['rating'] ) ) {
			if ( 'star_fontawesome' === $settings['star_style'] ) {
				if ( 'outline' === $settings['unmarked_star_style'] ) {
					$icon = '&#61446;';
				}
			} elseif ( 'star_unicode' === $settings['star_style'] ) {
				$icon = '&#9733;';

				if ( 'outline' === $settings['unmarked_star_style'] ) {
					$icon = '&#9734;';
				}
			}

			$rating         = (float) $item['rating'] > 5 ? 5 : $item['rating'];
			$floored_rating = (int) $rating;
			$stars_html     = '';

			for ( $stars = 1; $stars <= 5; $stars++ ) {
				if ( $stars <= $floored_rating ) {
					$stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
				} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
					$stars_html .= '<i class="elementor-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
				} else {
					$stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
				}
			}

			echo '<div class="elementor-star-rating">' . $stars_html . '</div>';
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
	 * Render testimonials widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		include TemplatesPro::get_templatet( 'testimonials', 'testimonials' );

	}


}
