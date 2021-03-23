<?php
/**
 * Elementor price table Widget.
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
use Elementor\Control_Media;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Responsive\Responsive;
use Elementor\Modules\DynamicTags\Module as TagsModule;


/**
 * PricingTable class
 */
class Pricetable extends \Elementor\Widget_Base {

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
			'taman-pricetable',
			TAMAN_KIT_PRO_URL . 'public/css/widgets/pricetable.css',
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
		return array( 'taman-pricetable' );
	}

	/**
	 * Script Depends
	 */
	public function get_script_depends() {
		return array(
			'tk-tooltip',
			'taman-kit-pro',
		);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve pricetable widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-pricetable';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve pricetable widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Price Table', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve pricetable widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-price-table';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the pricetable widget belongs to.
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
	 * Register pricetable widget controls.
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
				 * Content Tab: Header
				 * -------------------------------------------------
				 */
		$this->start_controls_section(
			'section_header',
			array(
				'label' => __( 'Header', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'  => array(
						'title' => esc_html__( 'None', 'taman-kit-pro' ),
						'icon'  => 'fa fa-ban',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'taman-kit-pro' ),
						'icon'  => 'fa fa-star',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'taman-kit-pro' ),
						'icon'  => 'fa fa-picture-o',
					),
				),
				'default'     => 'none',
			)
		);

		$this->add_control(
			'select_table_icon',
			array(
				'label'            => __( 'Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'table_icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'icon_type' => 'icon',
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
					'icon_type' => 'image',
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
					'icon_type' => 'image',
				),
			)
		);

		$this->add_control(
			'table_title',
			array(
				'label'   => __( 'Title', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Title', 'taman-kit-pro' ),
				'title'   => __( 'Enter table title', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'table_subtitle',
			array(
				'label'   => __( 'Subtitle', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Subtitle', 'taman-kit-pro' ),
				'title'   => __( 'Enter table subtitle', 'taman-kit-pro' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Pricing
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_pricing',
			array(
				'label' => __( 'Pricing', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'currency_symbol',
			array(
				'label'   => __( 'Currency Symbol', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''             => __( 'None', 'taman-kit-pro' ),
					'dollar'       => '&#36; ' . __( 'Dollar', 'taman-kit-pro' ),
					'euro'         => '&#128; ' . __( 'Euro', 'taman-kit-pro' ),
					'baht'         => '&#3647; ' . __( 'Baht', 'taman-kit-pro' ),
					'franc'        => '&#8355; ' . __( 'Franc', 'taman-kit-pro' ),
					'guilder'      => '&fnof; ' . __( 'Guilder', 'taman-kit-pro' ),
					'krona'        => 'kr ' . __( 'Krona', 'taman-kit-pro' ),
					'lira'         => '&#8356; ' . __( 'Lira', 'taman-kit-pro' ),
					'peseta'       => '&#8359 ' . __( 'Peseta', 'taman-kit-pro' ),
					'peso'         => '&#8369; ' . __( 'Peso', 'taman-kit-pro' ),
					'pound'        => '&#163; ' . __( 'Pound Sterling', 'taman-kit-pro' ),
					'real'         => 'R$ ' . __( 'Real', 'taman-kit-pro' ),
					'ruble'        => '&#8381; ' . __( 'Ruble', 'taman-kit-pro' ),
					'rupee'        => '&#8360; ' . __( 'Rupee', 'taman-kit-pro' ),
					'indian_rupee' => '&#8377; ' . __( 'Rupee (Indian)', 'taman-kit-pro' ),
					'shekel'       => '&#8362; ' . __( 'Shekel', 'taman-kit-pro' ),
					'yen'          => '&#165; ' . __( 'Yen/Yuan', 'taman-kit-pro' ),
					'won'          => '&#8361; ' . __( 'Won', 'taman-kit-pro' ),
					'custom'       => __( 'Custom', 'taman-kit-pro' ),
				),
				'default' => 'dollar',
			)
		);

		$this->add_control(
			'currency_symbol_custom',
			array(
				'label'     => __( 'Custom Symbol', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => '',
				'condition' => array(
					'currency_symbol' => 'custom',
				),
			)
		);

		$this->add_control(
			'table_price',
			array(
				'label'   => __( 'Price', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => '49.99',
			)
		);

		$this->add_control(
			'currency_format',
			array(
				'label'   => __( 'Currency Format', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'raised',
				'options' => array(
					'raised' => __( 'Raised', 'taman-kit-pro' ),
					''       => __( 'Normal', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'discount',
			array(
				'label'        => __( 'Discount', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'taman-kit-pro' ),
				'label_off'    => __( 'Off', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'table_original_price',
			array(
				'label'     => __( 'Original Price', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => '69',
				'condition' => array(
					'discount' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_duration',
			array(
				'label'   => __( 'Duration', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'per month', 'taman-kit-pro' ),
			)
		);

		$this->end_controls_section();

		/**
				 * Content Tab: Features
				 * -------------------------------------------------
				 */
		$this->start_controls_section(
			'section_features',
			array(
				'label' => __( 'Features', 'taman-kit-pro' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'feature_text',
			array(
				'label'       => __( 'Text', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'Feature', 'taman-kit-pro' ),
				'default'     => __( 'Feature', 'taman-kit-pro' ),
			)
		);

		$repeater->add_control(
			'exclude',
			array(
				'label'        => __( 'Exclude', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'tooltip_content',
			array(
				'label'   => __( 'Tooltip Content', 'taman-kit-pro' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'This is a tooltip', 'taman-kit-pro' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'select_feature_icon',
			array(
				'label'            => __( 'Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'default'          => array(
					'value'   => 'far fa-arrow-alt-circle-right',
					'library' => 'fa-regular',
				),
				'fa4compatibility' => 'feature_icon',
			)
		);

		$repeater->add_control(
			'feature_icon_color',
			array(
				'label'     => __( 'Icon Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .tk-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .tk-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'select_feature_icon[value]!' => '',
				),
			)
		);

		$repeater->add_control(
			'feature_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'feature_bg_color',
			array(
				'name'      => 'feature_bg_color',
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_features',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'feature_text'        => __( 'Feature #1', 'taman-kit-pro' ),
						'select_feature_icon' => 'fa fa-check',
					),
					array(
						'feature_text'        => __( 'Feature #2', 'taman-kit-pro' ),
						'select_feature_icon' => 'fa fa-check',
					),
					array(
						'feature_text'        => __( 'Feature #3', 'taman-kit-pro' ),
						'select_feature_icon' => 'fa fa-check',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ feature_text }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltip',
			array(
				'label' => __( 'Tooltip', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'show_tooltip',
			array(
				'label'        => __( 'Enable Tooltip', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'tooltip_trigger',
			array(
				'label'              => __( 'Trigger', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'hover',
				'options'            => array(
					'hover' => __( 'Hover', 'taman-kit-pro' ),
					'click' => __( 'Click', 'taman-kit-pro' ),
				),
				'frontend_available' => true,
				'condition'          => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_size',
			array(
				'label'     => __( 'Size', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default' => __( 'Default', 'taman-kit-pro' ),
					'tiny'    => __( 'Tiny', 'taman-kit-pro' ),
					'small'   => __( 'Small', 'taman-kit-pro' ),
					'large'   => __( 'Large', 'taman-kit-pro' ),
				),
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_position',
			array(
				'label'     => __( 'Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top',
				'options'   => array(
					'top'    => __( 'Top', 'taman-kit-pro' ),
					'bottom' => __( 'Bottom', 'taman-kit-pro' ),
					'left'   => __( 'Left', 'taman-kit-pro' ),
					'right'  => __( 'Right', 'taman-kit-pro' ),
				),
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_arrow',
			array(
				'label'              => __( 'Show Arrow', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'taman-kit-pro' ),
					'no'  => __( 'No', 'taman-kit-pro' ),
				),
				'frontend_available' => true,
				'condition'          => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_display_on',
			array(
				'label'              => __( 'Display On', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'text',
				'options'            => array(
					'text' => __( 'Text', 'taman-kit-pro' ),
					'icon' => __( 'Icon', 'taman-kit-pro' ),
				),
				'frontend_available' => true,
				'condition'          => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_icon',
			array(
				'label'     => __( 'Icon', 'taman-kit-pro' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-info-circle',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				),
			)
		);

		$this->add_control(
			'tooltip_distance',
			array(
				'label'       => __( 'Distance', 'taman-kit-pro' ),
				'description' => __( 'The distance between the text/icon and the tooltip.', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => '',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'.tk-tooltip.tk-tooltip-{{ID}}.tt-top' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
					'.tk-tooltip.tk-tooltip-{{ID}}.tt-bottom' => 'transform: translateY({{SIZE}}{{UNIT}});',
					'.tk-tooltip.tk-tooltip-{{ID}}.tt-left' => 'transform: translateX(-{{SIZE}}{{UNIT}});',
					'.tk-tooltip.tk-tooltip-{{ID}}.tt-right' => 'transform: translateX({{SIZE}}{{UNIT}});',
				),
				'condition'   => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_animation_in',
			array(
				'label'     => __( 'Animation In', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT2,
				'default'   => '',
				'options'   => taman_kit_tooltip_animations(),
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_animation_out',
			array(
				'label'     => __( 'Animation Out', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT2,
				'default'   => '',
				'options'   => taman_kit_tooltip_animations(),
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Ribbon
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_ribbon',
			array(
				'label' => __( 'Ribbon', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'show_ribbon',
			array(
				'label'        => __( 'Show Ribbon', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'ribbon_style',
			array(
				'label'     => __( 'Style', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'1' => __( 'Default', 'taman-kit-pro' ),
					'2' => __( 'Circle', 'taman-kit-pro' ),
					'3' => __( 'Flag', 'taman-kit-pro' ),
				),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_control(
			'ribbon_title',
			array(
				'label'     => __( 'Title', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'New', 'taman-kit-pro' ),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'ribbon_size',
			array(
				'label'      => __( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
					'em' => array(
						'min' => 1,
						'max' => 15,
					),
				),
				'default'    => array(
					'size' => 4,
					'unit' => 'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-ribbon-2' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_ribbon'  => 'yes',
					'ribbon_style' => array( '2' ),
				),
			)
		);

		$this->add_responsive_control(
			'top_distance',
			array(
				'label'      => __( 'Distance from Top', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-ribbon' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_ribbon'  => 'yes',
					'ribbon_style' => array( '2', '3' ),
				),
			)
		);

		$ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

		$this->add_responsive_control(
			'ribbon_distance',
			array(
				'label'     => __( 'Distance', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
				),
				'condition' => array(
					'show_ribbon'  => 'yes',
					'ribbon_style' => array( '1' ),
				),
			)
		);

		$this->add_control(
			'ribbon_position',
			array(
				'label'       => __( 'Position', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'label_block' => false,
				'options'     => array(
					'left'  => array(
						'title' => __( 'Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'     => 'right',
				'condition'   => array(
					'show_ribbon'  => 'yes',
					'ribbon_style' => array( '1', '2', '3' ),
				),
			)
		);

		$this->end_controls_section();

		/**
				 * Content Tab: Button
				 * -------------------------------------------------
				 */
		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Button', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'table_button_position',
			array(
				'label'   => __( 'Button Position', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => array(
					'above' => __( 'Above Features', 'taman-kit-pro' ),
					'below' => __( 'Below Features', 'taman-kit-pro' ),
					'none'  => __( 'None', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'table_button_text',
			array(
				'label'   => __( 'Button Text', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Get Started', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'taman-kit-pro' ),
				'label_block' => true,
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
			)
		);

		$this->add_control(
			'table_additional_info',
			array(
				'label'   => __( 'Additional Info', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Enter additional info here', 'taman-kit-pro' ),
				'title'   => __( 'Additional Info', 'taman-kit-pro' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Table
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => __( 'Table', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_align',
			array(
				'label'        => __( 'Alignment', 'taman-kit-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'options'      => array(
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
				'default'      => '',
				'prefix_class' => 'tk-pricing-table-align-',
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
		 * Style Tab: Header
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_header_style',
			array(
				'label' => __( 'Header', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_title_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-head' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'table_header_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'condition'   => array(
					'table_button_text!' => '',
				),
				'selector'    => '{{WRAPPER}} .tk-pricing-table-head',
			)
		);

		$this->add_responsive_control(
			'table_title_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_title_icon',
			array(
				'label'     => __( 'Icon', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'icon_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_size',
			array(
				'label'      => __( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => 26,
				),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'condition'  => array(
					'icon_type'                 => 'icon',
					'select_table_icon[value]!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_image_width',
			array(
				'label'      => __( 'Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 120,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'icon_type'   => 'image',
					'icon_image!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-icon' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'table_icon_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'icon_type!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-icon' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_icon_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => array(
					'icon_type'                 => 'icon',
					'select_table_icon[value]!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tk-pricing-table-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_margin',
			array(
				'label'      => __( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'icon_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'icon_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'table_icon_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'condition'   => array(
					'icon_type!' => 'none',
				),
				'selector'    => '{{WRAPPER}} .tk-pricing-table-icon',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'icon_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-icon, {{WRAPPER}} .tk-pricing-table-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_title_heading',
			array(
				'label'     => __( 'Title', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'table_title_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_title_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-pricing-table-title',
			)
		);

		$this->add_control(
			'table_subtitle_heading',
			array(
				'label'     => __( 'Sub Title', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'table_subtitle!' => '',
				),
			)
		);

		$this->add_control(
			'table_subtitle_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'condition' => array(
					'table_subtitle!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'table_subtitle_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_2,
				'condition' => array(
					'table_subtitle!' => '',
				),
				'selector'  => '{{WRAPPER}} .tk-pricing-table-subtitle',
			)
		);

		$this->add_responsive_control(
			'table_subtitle_spacing',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'table_subtitle!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-subtitle' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Pricing
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_pricing_style',
			array(
				'label' => __( 'Pricing', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'table_pricing_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .tk-pricing-table-price',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'table_price_color_normal',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-price' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_price_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-price' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'price_border_normal',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-pricing-table-price',
			)
		);

		$this->add_control(
			'pricing_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_pricing_width',
			array(
				'label'      => __( 'Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 25,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-price' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_price_margin',
			array(
				'label'      => __( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_price_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'pa_logo_wrapper_shadow',
				'selector' => '{{WRAPPER}} .tk-pricing-table-price',
			)
		);

		$this->add_control(
			'table_curreny_heading',
			array(
				'label'     => __( 'Currency Symbol', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'currency_size',
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
					'{{WRAPPER}} .tk-pricing-table-price-prefix' => 'font-size: calc({{SIZE}}em/100)',
				),
				'condition' => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'currency_position',
			array(
				'label'       => __( 'Position', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'before',
				'options'     => array(
					'before' => array(
						'title' => __( 'Before', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'after'  => array(
						'title' => __( 'After', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
			)
		);

		$this->add_control(
			'currency_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'taman-kit-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'top',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .tk-pricing-table-price-prefix' => 'align-self: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_duration_heading',
			array(
				'label'     => __( 'Duration', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'duration_position',
			array(
				'label'        => __( 'Duration Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'wrap',
				'options'      => array(
					'nowrap' => __( 'Same Line', 'taman-kit-pro' ),
					'wrap'   => __( 'Next Line', 'taman-kit-pro' ),
				),
				'prefix_class' => 'tk-pricing-table-price-duration-',
			)
		);

		$this->add_control(
			'duration_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-price-duration' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'duration_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .tk-pricing-table-price-duration',
			)
		);

		$this->add_responsive_control(
			'duration_spacing',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}.tk-pricing-table-price-duration-wrap .tk-pricing-table-price-duration' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'duration_position' => 'wrap',
				),
			)
		);

		$this->add_control(
			'table_original_price_style_heading',
			array(
				'label'     => __( 'Original Price', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'discount' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_original_price_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'discount' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-price-original' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_original_price_text_size',
			array(
				'label'      => __( 'Font Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'condition'  => array(
					'discount' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-price-original' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Features
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_features_style',
			array(
				'label' => __( 'Features', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_features_align',
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
					'{{WRAPPER}} .tk-pricing-table-features'   => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'table_features_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-features' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_features_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-features' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_features_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '20',
					'right'    => '',
					'bottom'   => '20',
					'left'     => '',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_features_margin',
			array(
				'label'      => __( 'Margin Bottom', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-features' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'table_features_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'selector'  => '{{WRAPPER}} .tk-pricing-table-features',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'table_features_icon_heading',
			array(
				'label'     => __( 'Icon', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'table_features_icon_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-fature-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tk-pricing-table-fature-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_features_icon_size',
			array(
				'label'      => __( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-fature-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_features_icon_spacing',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-fature-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_features_rows_heading',
			array(
				'label'     => __( 'Rows', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'table_features_spacing',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-features li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_features_alternate',
			array(
				'label'        => __( 'Striped Rows', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'table_features_rows_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-features li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_features_style' );

		$this->start_controls_tab(
			'tab_features_even',
			array(
				'label'     => __( 'Even', 'taman-kit-pro' ),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_features_bg_color_even',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-features li:nth-child(even)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_features_text_color_even',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-features li:nth-child(even)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_features_odd',
			array(
				'label'     => __( 'Odd', 'taman-kit-pro' ),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_features_bg_color_odd',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-features li:nth-child(odd)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_features_text_color_odd',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-features li:nth-child(odd)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'table_divider_heading',
			array(
				'label'     => __( 'Divider', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'table_feature_divider',
				'label'       => __( 'Divider', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-pricing-table-features li',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltips_style',
			array(
				'label'     => __( 'Tooltip', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.tk-tooltip.tk-tooltip-{{ID}} .tk-tooltip-content' => 'background-color: {{VALUE}};',
					'.tk-tooltip.tk-tooltip-{{ID}}.tt-top .tk-tooltip-callout:after'    => 'border-top-color: {{VALUE}};',
					'.tk-tooltip.tk-tooltip-{{ID}}.tt-bottom .tk-tooltip-callout:after' => 'border-bottom-color: {{VALUE}};',
					'.tk-tooltip.tk-tooltip-{{ID}}.tt-left .tk-tooltip-callout:after'   => 'border-left-color: {{VALUE}};',
					'.tk-tooltip.tk-tooltip-{{ID}}.tt-right .tk-tooltip-callout:after'  => 'border-right-color: {{VALUE}};',
				),
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.tk-tooltip.tk-tooltip-{{ID}} .tk-tooltip-content' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_width',
			array(
				'label'       => __( 'Width', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min'  => 50,
						'max'  => 400,
						'step' => 1,
					),
				),
				'selectors'   => array(
					'.tk-tooltip.tk-tooltip-{{ID}}' => 'max-width: {{SIZE}}{{UNIT}};',
				),
				'render_type' => 'template',
				'condition'   => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'tooltip_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '.tk-tooltip.tk-tooltip-{{ID}}',
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tooltip_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.tk-tooltip.tk-tooltip-{{ID}} .tk-tooltip-content',
				'condition'   => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.tk-tooltip.tk-tooltip-{{ID}} .tk-tooltip-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'tooltip_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.tk-tooltip.tk-tooltip-{{ID}} .tk-tooltip-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'tooltip_box_shadow',
				'selector'  => '.tk-tooltip.tk-tooltip-{{ID}} .tk-tooltip-content',
				'condition' => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_icon_style_heading',
			array(
				'label'     => __( 'Tooltip Icon', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				),
			)
		);

		$this->add_control(
			'tooltip_icon_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-features .tk-pricing-table-tooltip-icon' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'tooltip_icon_size',
			array(
				'label'      => __( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-features .tk-pricing-table-tooltip-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Ribbon
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_ribbon_style',
			array(
				'label' => __( 'Ribbon', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ribbon_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-ribbon .tk-pricing-table-ribbon-inner' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .tk-pricing-table-ribbon-3.tk-pricing-table-ribbon-right:before' => 'border-left-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'ribbon_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-ribbon .tk-pricing-table-ribbon-inner' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .tk-pricing-table-ribbon .tk-pricing-table-ribbon-inner',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .tk-pricing-table-ribbon .tk-pricing-table-ribbon-inner',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_button_style',
			array(
				'label'     => __( 'Button', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->add_control(
			'table_button_size',
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
					'table_button_text!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'      => __( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-button-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'table_button_text!'    => '',
					'table_button_position' => 'above',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'taman-kit-pro' ),
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-button' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-button' => 'color: {{VALUE}}',
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
				'condition'   => array(
					'table_button_text!' => '',
				),
				'selector'    => '{{WRAPPER}} .tk-pricing-table-button',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => array(
					'table_button_text!' => '',
				),
				'selector'  => '{{WRAPPER}} .tk-pricing-table-button',
			)
		);

		$this->add_responsive_control(
			'table_button_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'condition'  => array(
					'table_button_text!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'table_button_text!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pa_pricing_table_button_shadow',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selector'  => '{{WRAPPER}} .tk-pricing-table-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'taman-kit-pro' ),
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-button:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_hover',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'condition'   => array(
					'table_button_text!' => '',
				),
				'selector'    => '{{WRAPPER}} .tk-pricing-table-button:hover',
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label'     => __( 'Animation', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
				 * Style Tab: Footer
				 * -------------------------------------------------
				 */
		$this->start_controls_section(
			'section_table_footer_style',
			array(
				'label' => __( 'Footer', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_footer_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-footer' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '30',
					'right'    => '30',
					'bottom'   => '30',
					'left'     => '30',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_additional_info_heading',
			array(
				'label'     => __( 'Additional Info', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'table_additional_info!' => '',
				),
			)
		);

		$this->add_control(
			'additional_info_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'default'   => '',
				'condition' => array(
					'table_additional_info!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-additional-info' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'additional_info_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_additional_info!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-pricing-table-additional-info' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'additional_info_margin',
			array(
				'label'      => __( 'Margin Top', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-additional-info' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'table_additional_info!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'additional_info_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'condition'  => array(
					'table_additional_info!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-pricing-table-additional-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'additional_info_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'condition' => array(
					'table_additional_info!' => '',
				),
				'selector'  => '{{WRAPPER}} .tk-pricing-table-additional-info',
			)
		);

		$this->end_controls_section();

	}



	/**
	 * Undocumented function
	 *
	 * @param [type] $symbol_name .
	 */
	private function get_currency_symbol( $symbol_name ) {
		$symbols = array(
			'dollar'       => '&#36;',
			'euro'         => '&#128;',
			'franc'        => '&#8355;',
			'pound'        => '&#163;',
			'ruble'        => '&#8381;',
			'shekel'       => '&#8362;',
			'baht'         => '&#3647;',
			'yen'          => '&#165;',
			'won'          => '&#8361;',
			'guilder'      => '&fnof;',
			'peso'         => '&#8369;',
			'peseta'       => '&#8359',
			'lira'         => '&#8356;',
			'rupee'        => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real'         => 'R$',
			'krona'        => 'kr',
		);
		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
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
	 *
	 * @param [type] $item .
	 * @param [type] $tooltip_key .
	 * @return void
	 */
	protected function get_tooltip_attributes( $item, $tooltip_key ) {
		$settings         = $this->get_settings_for_display();
		$tooltip_position = 'tt-' . $settings['tooltip_position'];

		$this->add_render_attribute(
			$tooltip_key,
			array(
				'class'                 => 'tk-pricing-table-tooptip',
				'data-tooltip'          => $item['tooltip_content'],
				'data-tooltip-position' => $tooltip_position,
				'data-tooltip-size'     => $settings['tooltip_size'],
			)
		);

		if ( $settings['tooltip_width'] ) {
			$this->add_render_attribute( $tooltip_key, 'data-tooltip-width', $settings['tooltip_width']['size'] );
		}

		if ( $settings['tooltip_animation_in'] ) {
			$this->add_render_attribute( $tooltip_key, 'data-tooltip-animation-in', $settings['tooltip_animation_in'] );
		}

		if ( $settings['tooltip_animation_out'] ) {
			$this->add_render_attribute( $tooltip_key, 'data-tooltip-animation-out', $settings['tooltip_animation_out'] );
		}
	}

	/**
	 * Render pricetable widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		include TemplatesPro::get_templatet( 'pricetable', 'pricetable' );

	}

	/**
	 * Render pricing table widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * Remove this after Elementor v3.3.0
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		include TemplatesPro::get_templatet( 'pricetable', 'content' );
	}
}
