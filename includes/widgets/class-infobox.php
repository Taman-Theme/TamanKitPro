<?php
/**
 * Elementor infobox Widget.
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
use Elementor\Control_Media;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * InfoBox class
 */
class InfoBox extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve InfoBox widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-infobox';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve infobox widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Info Box', 'taman-kit' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve infobox widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-info-box';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the infobox widget belongs to.
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
	 * Retrieve the list of scripts.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'',
		);
	}

	/**
	 * Register infobox widget controls.
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
				 * Content Tab: Icon
				 * -------------------------------------------------
				 */
		$this->start_controls_section(
			'section_icon',
			array(
				'label' => __( 'Icon', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'       => esc_html__( 'Type', 'taman-kit-pro' ),
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
					'text'  => array(
						'title' => esc_html__( 'Text', 'taman-kit-pro' ),
						'icon'  => 'fa fa-font',
					),
				),
				'default'     => 'icon',
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'            => __( 'Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
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
			'icon_text',
			array(
				'label'     => __( 'Icon Text', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => '1',
				'condition' => array(
					'icon_type' => 'text',
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Image', 'taman-kit-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
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
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 30,
					'unit' => 'px',
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
					'icon_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_rotation',
			array(
				'label'      => __( 'Rotate', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 360,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'icon_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-icon' => 'transform: rotate( {{SIZE}}deg );',
				),
			)
		);

		$this->add_responsive_control(
			'icon_img_width',
			array(
				'label'      => __( 'Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 25,
						'max'  => 600,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 25,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.tk-info-box-top .tk-info-box-icon img, {{WRAPPER}}.tk-info-box-left .tk-info-box-icon-wrap, {{WRAPPER}}.tk-info-box-right .tk-info-box-icon-wrap' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'icon_position',
			array(
				'label'              => __( 'Icon', 'taman-kit-pro' ) . ' ' . __( 'Position', 'taman-kit-pro' ),
				'type'               => Controls_Manager::CHOOSE,
				'default'            => 'top',
				'options'            => array(
					'left'  => array(
						'title' => __( 'Icon on Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'top'   => array(
						'title' => __( 'Icon on Top', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right' => array(
						'title' => __( 'Icon on Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition'          => array(
					'icon_type!' => 'none',
				),
				'prefix_class'       => 'tk-info-box%s-',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'icon_vertical_position',
			array(
				'label'                => __( 'Vertical Align', 'taman-kit-pro' ),
				'description'          => __( 'Works in case of left and right icon position', 'taman-kit-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'top',
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
				'selectors'            => array(
					'(desktop){{WRAPPER}}.tk-info-box-left .tk-info-box'        => 'align-items: {{VALUE}};',
					'(desktop){{WRAPPER}}.tk-info-box-right .tk-info-box'       => 'align-items: {{VALUE}};',
					'(tablet){{WRAPPER}}.tk-info-box-tablet-left .tk-info-box'  => 'align-items: {{VALUE}};',
					'(tablet){{WRAPPER}}.tk-info-box-tablet-right .tk-info-box' => 'align-items: {{VALUE}};',
					'(mobile){{WRAPPER}}.tk-info-box-mobile-left .tk-info-box'  => 'align-items: {{VALUE}};',
					'(mobile){{WRAPPER}}.tk-info-box-mobile-right .tk-info-box' => 'align-items: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'condition'            => array(
					'icon_type' => array( 'icon', 'image', 'text' ),
				),
			)
		);

		$this->end_controls_section();

		/**
				 * Content Tab: Content
				 * -------------------------------------------------
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
				'label'   => __( 'Title', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Title', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'sub_heading',
			array(
				'label'   => __( 'Subtitle', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Subtitle', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'Description', 'taman-kit-pro' ),
				'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Enter info box description', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'divider_title_switch',
			array(
				'label'        => __( 'Title Separator', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'taman-kit-pro' ),
				'label_off'    => __( 'Off', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'taman-kit-pro' ),
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
			'sub_title_html_tag',
			array(
				'label'     => __( 'Subtitle HTML Tag', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h5',
				'options'   => array(
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
				'condition' => array(
					'sub_heading!' => '',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Link
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_link',
			array(
				'label' => __( 'Link', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'link_type',
			array(
				'label'   => __( 'Link Type', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => __( 'None', 'taman-kit-pro' ),
					'box'    => __( 'Box', 'taman-kit-pro' ),
					'icon'   => __( 'Image/Icon', 'taman-kit-pro' ),
					'title'  => __( 'Title', 'taman-kit-pro' ),
					'button' => __( 'Button', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
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
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'link_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'button_visible',
			array(
				'label'        => __( 'Show Button', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'link_type' => 'box',
				),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'      => __( 'Button', 'taman-kit-pro' ) . ' ' . __( 'Text', 'taman-kit-pro' ),
				'type'       => Controls_Manager::TEXT,
				'dynamic'    => array(
					'active' => true,
				),
				'default'    => __( 'Get Started', 'taman-kit-pro' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'link_type',
							'operator' => '==',
							'value'    => 'button',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'link_type',
									'operator' => '==',
									'value'    => 'box',
								),
								array(
									'name'     => 'button_visible',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'select_button_icon',
			array(
				'label'            => __( 'Button', 'taman-kit-pro' ) . ' ' . __( 'Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon',
				'conditions'       => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'link_type',
							'operator' => '==',
							'value'    => 'button',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'link_type',
									'operator' => '==',
									'value'    => 'box',
								),
								array(
									'name'     => 'button_visible',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'      => __( 'Icon Position', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'after',
				'options'    => array(
					'after'  => __( 'After', 'taman-kit-pro' ),
					'before' => __( 'Before', 'taman-kit-pro' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'link_type',
									'operator' => '==',
									'value'    => 'button',
								),
								array(
									'name'     => 'select_button_icon[value]',
									'operator' => '!=',
									'value'    => '',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'link_type',
									'operator' => '==',
									'value'    => 'box',
								),
								array(
									'name'     => 'button_visible',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'select_button_icon[value]',
									'operator' => '!=',
									'value'    => '',
								),
							),
						),
					),
				),
			)
		);

		$this->end_controls_section();

		/**
		 *Taman kit massage active.
		 */
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
		*=============================== Style Tab: Style =================================
		*
		*==================================================================================
		*/

		/**
			 * Style Tab: Info Box
			 * -------------------------------------------------
			 */
		$this->start_controls_section(
			'section_info_box_style',
			array(
				'label' => __( 'Info Box', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'infobox_boxshadow',
			array(
				'label'    => __( 'Box Shadow', 'taman-kit-pro' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => taman_kit_box_shadow(),
				'default'  => '',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'taman-kit-pro' ),
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_info_box_style' );

		$this->start_controls_tab(
			'tab_info_box_normal',
			array(
				'label' => __( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'info_box_bg',
				'label'    => __( 'Background', 'taman-kit-pro' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tk-info-box-container',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'info_box_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-info-box-container',
			)
		);

		$this->add_control(
			'info_box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'info_box_shadow',
				'selector' => '{{WRAPPER}} .tk-info-box-container',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_info_box_hover',
			array(
				'label' => __( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'info_box_bg_hover',
				'label'    => __( 'Background', 'taman-kit-pro' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tk-info-box-container:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'info_box_border_hover',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-info-box-container:hover',
			)
		);

		$this->add_control(
			'info_box_border_radius_hover',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-container:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'info_box_shadow_hover',
				'selector' => '{{WRAPPER}} .tk-info-box-container:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'info_box_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'info_box_min_height',
			array(
				'label'      => __( 'Min Height', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					),
					'vh' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'vh' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-container' => 'min-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Icon
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_icon_style',
			array(
				'label'     => __( 'Icon', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'icon_type!' => 'none',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'icon_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => array(
					'icon_type' => 'text',
				),
				'selector'  => '{{WRAPPER}} .tk-info-box-icon',
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
					'{{WRAPPER}} .tk-info-box-icon'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .tk-info-box-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'icon_type!' => 'image',
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
					'{{WRAPPER}} .tk-info-box-icon' => 'background-color: {{VALUE}}',
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
				'condition'   => array(
					'icon_type!' => 'none',
				),
				'selector'    => '{{WRAPPER}} .tk-info-box-icon',
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
					'{{WRAPPER}} .tk-info-box-icon, {{WRAPPER}} .tk-info-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .tk-info-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .tk-info-box-icon-wrap' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
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
					'{{WRAPPER}} .tk-info-box-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tk-info-box-icon:hover svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'icon_type!' => 'image',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'icon_type!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-icon:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'icon_type!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-icon:hover .fa' => 'border-color: {{VALUE}}',
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
		 * Style Tab: Title
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_title_style',
			array(
				'label' => __( 'Title', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-info-box-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => __( 'Hover Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-container:hover .tk-info-box-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'heading!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .tk-info-box-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-info-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'subtitle_heading',
			array(
				'label'     => __( 'Sub Title', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'sub_heading!' => '',
				),
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'sub_heading!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'subtitle_color_hover',
			array(
				'label'     => __( 'Hover Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-container:hover .tk-info-box-subtitle' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'sub_heading!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'subtitle_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => array(
					'sub_heading!' => '',
				),
				'selector'  => '{{WRAPPER}} .tk-info-box-subtitle',
			)
		);

		$this->add_responsive_control(
			'subtitle_margin',
			array(
				'label'      => __( 'Margin Bottom', 'taman-kit-pro' ),
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
				'condition'  => array(
					'sub_heading!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Title Separator
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_title_divider_style',
			array(
				'label'     => __( 'Title Separator', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_title_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_title_border_type',
			array(
				'label'     => __( 'Border Type', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'none'   => __( 'None', 'taman-kit-pro' ),
					'solid'  => __( 'Solid', 'taman-kit-pro' ),
					'double' => __( 'Double', 'taman-kit-pro' ),
					'dotted' => __( 'Dotted', 'taman-kit-pro' ),
					'dashed' => __( 'Dashed', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'divider_title_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_title_width',
			array(
				'label'      => __( 'Border Width', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-info-box-divider' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_title_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_title_border_height',
			array(
				'label'      => __( 'Border Height', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 2,
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 20,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_title_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_title_border_color',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'divider_title_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_title_align',
			array(
				'label'     => __( 'Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-divider-wrap'   => 'display: flex; justify-content: {{VALUE}};',
				),
				'condition' => array(
					'divider_title_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_title_margin',
			array(
				'label'      => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-info-box-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_title_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Description
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_description_style',
			array(
				'label'     => __( 'Description', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-description' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_control(
			'description_color_hover',
			array(
				'label'     => __( 'Hover Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-container:hover .tk-info-box-description' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'description_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .tk-info-box-description',
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'description_margin',
			array(
				'label'      => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-info-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'description!' => '',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_button_style',
			array(
				'label'     => __( 'Button', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'link_type' => 'button',
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
					'link_type' => 'button',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'taman-kit-pro' ),
				'condition' => array(
					'link_type' => 'button',
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
					'{{WRAPPER}} .tk-info-box-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tk-info-box-button .tk-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-button' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
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
				'selector'    => '{{WRAPPER}} .tk-info-box-button',
				'condition'   => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .tk-info-box-button',
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-info-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .tk-info-box-button',
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'info_box_button_icon_heading',
			array(
				'label'     => __( 'Button Icon', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'link_type'                  => 'button',
					'select_button_icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
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
				'condition'   => array(
					'link_type'                  => 'button',
					'select_button_icon[value]!' => '',
				),
				'selectors'   => array(
					'{{WRAPPER}} .tk-info-box .tk-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'taman-kit-pro' ),
				'condition' => array(
					'link_type' => 'button',
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
					'{{WRAPPER}} .tk-info-box-button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tk-info-box-button:hover .tk-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-info-box-button:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
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
					'{{WRAPPER}} .tk-info-box-button:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_animation',
			array(
				'label'     => __( 'Animation', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .tk-info-box-button:hover',
				'condition' => array(
					'link_type' => 'button',
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
	 * Render info box icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_infobox_icon() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'icon_text', 'none' );
		$this->add_render_attribute( 'icon_text', 'class', 'tk-icon-text' );

		$this->add_render_attribute( 'icon', 'class', array( 'tk-info-box-icon', 'tk-icon' ) );

		if ( $settings['hover_animation_icon'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['hover_animation_icon'] );
		}

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default.
			$settings['icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon' ) ); ?>>
			<?php if ( 'icon' === $settings['icon_type'] && $has_icon ) { ?>
				<?php
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
				} elseif ( ! empty( $settings['icon'] ) ) {
					?>
					<i <?php echo wp_kses_post( $this->get_render_attribute_string( 'i' ) ); ?>></i>
					<?php
				}
				?>
				<?php
			} elseif ( 'image' === $settings['icon_type'] ) {

				if ( ! empty( $settings['image']['url'] ) ) {
					echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' ) );
				}
			} elseif ( 'text' === $settings['icon_type'] ) {
				?>
				<span class="tk-icon-text">
					<?php echo wp_kses_post( $settings['icon_text'] ); ?>
				</span>
			<?php } ?>
		</span>
		<?php
	}

	/**
	 * Render info box button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_infobox_button() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'button_text', 'none' );
		$this->add_render_attribute( 'button_text', 'class', 'tk-button-text' );

		$this->add_render_attribute(
			'info-box-button',
			'class',
			array(
				'tk-info-box-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			)
		);

		if ( 'button' === $settings['link_type'] ) {
			$tk_button_html_tag = ( 'button' === $settings['link_type'] ) ? 'a' : 'div';
		} elseif ( 'box' === $settings['link_type'] && 'yes' === $settings['button_visible'] ) {
			$tk_button_html_tag = ( 'button' === $settings['link_type'] ) ? 'div' : 'div';
		}

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'info-box-button', 'class', 'elementor-animation-' . $settings['button_animation'] );
		}

		if ( ! isset( $settings['button_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default.
			$settings['button_icon'] = '';
		}

		$has_icon = ! empty( $settings['button_icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'button-icon', 'class', $settings['button_icon'] );
			$this->add_render_attribute( 'button-icon', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $settings['select_button_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['select_button_icon'] );
		$is_new   = ! isset( $settings['button_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( 'button' === $settings['link_type'] || ( 'box' === $settings['link_type'] && 'yes' === $settings['button_visible'] ) ) {
			?>
			<?php if ( '' !== $settings['button_text'] || $has_icon ) { ?>
				<div class="tk-info-box-footer">
					<<?php echo esc_attr( $tk_button_html_tag ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'info-box-button' ) ) . wp_kses_post( $this->get_render_attribute_string( 'link' ) ); ?>>
						<?php if ( 'before' === $settings['button_icon_position'] && $has_icon ) { ?>
							<span class='tk-button-icon tk-icon'>
								<?php
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $settings['select_button_icon'], array( 'aria-hidden' => 'true' ) );
								} elseif ( ! empty( $settings['button_icon'] ) ) {
									?>
									<i <?php echo wp_kses_post( $this->get_render_attribute_string( 'button-icon' ) ); ?>></i>
									<?php
								}
								?>
							</span>
						<?php } ?>
						<?php if ( ! empty( $settings['button_text'] ) ) { ?>
							<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'button_text' ) ); ?>>
								<?php echo esc_attr( $settings['button_text'] ); ?>
							</span>
						<?php } ?>
						<?php if ( 'after' === $settings['button_icon_position'] && $has_icon ) { ?>
							<span class='tk-button-icon tk-icon'>
								<?php
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $settings['select_button_icon'], array( 'aria-hidden' => 'true' ) );
								} elseif ( ! empty( $settings['button_icon'] ) ) {
									?>
									<i <?php echo wp_kses_post( $this->get_render_attribute_string( 'button-icon' ) ); ?>></i>
									<?php
								}
								?>
							</span>
						<?php } ?>
					</<?php echo esc_attr( $tk_button_html_tag ); ?>>
				</div>
			<?php } ?>
			<?php
		}
	}

	/**
	 * Render info box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings          = $this->get_settings_for_display();
		$boxshadow         = '';
		$infobox_boxshadow = $settings['infobox_boxshadow'];

		foreach ( $infobox_boxshadow as $shadow ) {
			$boxshadow .= ' ' . $shadow;
		}

		$this->add_render_attribute(
			array(
				'info-box'           => array(
					'class' => array(
						'tk-info-box',
					),
				),
				'info-box-container' => array(
					'class' => array(
						'tk-info-box-container',
						$boxshadow,
					),
				),
				'title-container'    => array(
					'class' => 'tk-info-box-title-container',
				),
				'heading'            => array(
					'class' => 'tk-info-box-title',
				),
				'sub_heading'        => array(
					'class' => 'tk-info-box-subtitle',
				),
				'description'        => array(
					'class' => 'tk-info-box-description',
				),
			)
		);

		$tk_if_html_tag    = 'div';
		$tk_title_html_tag = 'div';

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_inline_editing_attributes( 'description', 'basic' );

		if ( 'none' !== $settings['link_type'] ) {
			if ( ! empty( $settings['link']['url'] ) ) {
				$this->add_link_attributes( 'link', $settings['link'] );

				if ( 'box' === $settings['link_type'] ) {
					$tk_if_html_tag = 'a';
				} elseif ( 'title' === $settings['link_type'] ) {
					$tk_title_html_tag = 'a';
				}
			}
		}
		?>
		<<?php echo esc_attr( $tk_if_html_tag ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'info-box-container' ) ) . wp_kses_post( $this->get_render_attribute_string( 'link' ) ); ?>>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'info-box' ) ); ?>>
			<?php if ( 'none' !== $settings['icon_type'] ) { ?>
				<div class="tk-info-box-icon-wrap">
					<?php if ( 'icon' === $settings['link_type'] ) { ?>
						<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'link' ) ); ?>>
					<?php } ?>
					<?php
						// Icon.
						$this->render_infobox_icon();
					?>
					<?php if ( 'icon' === $settings['link_type'] ) { ?>
						</a>
					<?php } ?>
				</div>
			<?php } ?>
			<div class="tk-info-box-content">
				<div class="tk-info-box-title-wrap">
					<?php
					if ( ! empty( $settings['heading'] ) ) {
						echo '<' . esc_attr( $tk_title_html_tag ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'title-container' ) ) . wp_kses_post( $this->get_render_attribute_string( 'link' ) ) . '>';
						printf( '<%1$s %2$s>', esc_attr( $settings['title_html_tag'] ), wp_kses_post( $this->get_render_attribute_string( 'heading' ) ) );
						echo wp_kses_post( $settings['heading'] );
						printf( '</%1$s>', esc_attr( $settings['title_html_tag'] ) );
						echo '</' . esc_attr( $tk_title_html_tag ) . '>';
					}

					if ( ! empty( $settings['sub_heading'] ) ) {
						printf( '<%1$s %2$s>', esc_attr( $settings['sub_title_html_tag'] ), wp_kses_post( $this->get_render_attribute_string( 'sub_heading' ) ) );
						echo wp_kses_post( $settings['sub_heading'] );
						printf( '</%1$s>', esc_attr( $settings['sub_title_html_tag'] ) );
					}
					?>
				</div>

				<?php if ( 'yes' === $settings['divider_title_switch'] ) { ?>
					<div class="tk-info-box-divider-wrap">
						<div class="tk-info-box-divider"></div>
					</div>
				<?php } ?>

				<?php if ( ! empty( $settings['description'] ) ) { ?>
					<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'description' ) ); ?>>
						<?php echo wp_kses_post( $this->parse_text_editor( nl2br( $settings['description'] ) ) ); ?>
					</div>
				<?php } ?>
				<?php
					// Button.
					$this->render_infobox_button();
				?>
			</div>
		</div>
		</<?php echo esc_attr( $tk_if_html_tag ); ?>>
		<?php
	}

	/**
	 * Render info box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			var tk_if_html_tag = 'div',
				tk_title_html_tag = 'div',
				tk_button_html_tag = 'div',
				iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' ),
				buttonIconHTML = elementor.helpers.renderIcon( view, settings.select_button_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				buttonMigrated = elementor.helpers.isIconMigrated( settings, 'select_button_icon' ),
				infobox_boxshadow = settings.infobox_boxshadow,
				boxshadow ='';

				_.each( settings.infobox_boxshadow, function( item ) {
					boxshadow +=' ' + item;
					});

			if ( settings.link.url != '' ) {
				if ( settings.link_type == 'box' ) {
					var tk_if_html_tag = 'a';
				}
				else if ( settings.link_type == 'title' ) {
					var tk_title_html_tag = 'a';
				}
				else if ( settings.link_type == 'button' ) {
					var tk_button_html_tag = 'a';
				}
			}
		#>
		<{{{tk_if_html_tag}}} class="tk-info-box-container {{boxshadow}}" href="{{settings.link.url}}">
			<div class="tk-info-box tk-info-box-{{ settings.icon_position }}">
				<# if ( settings.icon_type != 'none' ) { #>
					<div class="tk-info-box-icon-wrap">
						<# if ( settings.link_type == 'icon' ) { #>
							<a href="{{settings.link.url}}">
						<# } #>
						<span class="tk-info-box-icon tk-icon elementor-animation-{{ settings.hover_animation_icon }}">
							<# if ( settings.icon_type == 'icon' ) { #>
								<# if ( settings.icon || settings.selected_icon ) { #>
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
								{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ settings.icon }}" aria-hidden="true"></i>
								<# } #>
								<# } #>
							<# } else if ( settings.icon_type == 'image' ) { #>
								<#
								var image = {
									id: settings.image.id,
									url: settings.image.url,
									size: settings.image_size,
									dimension: settings.image_custom_dimension,
									model: view.getEditModel()
								};
								var image_url = elementor.imagesManager.getImageUrl( image );
								#>
								<img src="{{{ image_url }}}" />
							<# } else if ( settings.icon_type == 'text' ) { #>
								<span class="tk-icon-text elementor-inline-editing" data-elementor-setting-key="icon_text" data-elementor-inline-editing-toolbar="none">
									{{{ settings.icon_text }}}
								</span>
							<# } #>
						</span>
						<# if ( settings.link_type == 'icon' ) { #>
							</a>
						<# } #>
					</div>
				<# } #>
				<div class="tk-info-box-content">
					<div class="tk-info-box-title-wrap">
						<# if ( settings.heading ) { #>
							<{{tk_title_html_tag}} class="tk-info-box-title-container" href="{{settings.link.url}}">
								<{{settings.title_html_tag}} class="tk-info-box-title elementor-inline-editing" data-elementor-setting-key="heading" data-elementor-inline-editing-toolbar="none">
									{{{ settings.heading }}}
								</{{settings.title_html_tag}}>
							</{{tk_title_html_tag}}>
						<# } #>
						<# if ( settings.sub_heading ) { #>
							<{{settings.sub_title_html_tag}} class="tk-info-box-subtitle elementor-inline-editing" data-elementor-setting-key="sub_heading" data-elementor-inline-editing-toolbar="none">
								{{{ settings.sub_heading }}}
							</{{settings.sub_title_html_tag}}>
						<# } #>
					</div>

					<# if ( settings.divider_title_switch == 'yes' ) { #>
						<div class="tk-info-box-divider-wrap">
							<div class="tk-info-box-divider"></div>
						</div>
					<# } #>

					<# if ( settings.description ) { #>
						<div class="tk-info-box-description elementor-inline-editing" data-elementor-setting-key="description" data-elementor-inline-editing-toolbar="basic">
							{{{ settings.description }}}
						</div>
					<# } #>
					<# if ( settings.link_type == 'button' || ( settings.link_type == 'box' && settings.button_visible == 'yes' ) ) { #>
						<# if ( settings.button_text != '' || settings.button_icon != '' ) { #>
							<div class="tk-info-box-footer">
								<{{tk_button_html_tag}} href="{{ settings.link.url }}" class="tk-info-box-button elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{ settings.button_animation }}">
									<# if ( settings.button_icon_position == 'before' ) { #>
										<# if ( settings.button_icon || settings.select_button_icon ) { #>
											<span class="tk-button-icon tk-icon">
												<# if ( buttonIconHTML && buttonIconHTML.rendered && ( ! settings.button_icon || buttonMigrated ) ) { #>
												{{{ buttonIconHTML.value }}}
												<# } else { #>
													<i class="{{ settings.button_icon }}" aria-hidden="true"></i>
												<# } #>
											</span>
										<# } #>
									<# } #>
									<# if ( settings.button_text ) { #>
										<span class="tk-button-text elementor-inline-editing" data-elementor-setting-key="button_text" data-elementor-inline-editing-toolbar="none">
											{{{ settings.button_text }}}
										</span>
									<# } #>
									<# if ( settings.button_icon_position == 'after' ) { #>
										<# if ( settings.button_icon || settings.select_button_icon ) { #>
											<span class="tk-button-icon tk-icon">
												<# if ( buttonIconHTML && buttonIconHTML.rendered && ( ! settings.button_icon || buttonMigrated ) ) { #>
												{{{ buttonIconHTML.value }}}
												<# } else { #>
													<i class="{{ settings.button_icon }}" aria-hidden="true"></i>
												<# } #>
											</span>
										<# } #>
									<# } #>
								</{{tk_button_html_tag}}>
							</div>
						<# } #>
					<# } #>
				</div>
			</div>
		</{{{tk_if_html_tag}}}>
		<?php
	}

	/**
	 * Render info box widget output in the editor.
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
