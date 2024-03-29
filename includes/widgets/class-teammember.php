<?php
/**
 * Elementor Team Member Widget.
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
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Team Member class
 */
class TeamMember extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve teammember widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-teammember';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve teammember widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Team Member', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve teammember widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-person';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the teammember widget belongs to.
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
			'jquery-simple-text-rotator',
		);
	}

	/**
	 * Register teammember widget controls.
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
			'section_image',
			array(
				'label' => __( 'Image', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
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

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'label'   => __( 'Image Size', 'taman-kit-pro' ),
				'default' => 'medium_large',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_details',
			array(
				'label' => __( 'Details', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'team_member_name',
			array(
				'label'   => __( 'Name', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'John Doe', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'team_member_position',
			array(
				'label'   => __( 'Position', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'WordPress Developer', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'team_member_description_switch',
			array(
				'label'        => __( 'Show Description', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'team_member_description',
			array(
				'label'     => __( 'Description', 'taman-kit-pro' ),
				'type'      => Controls_Manager::WYSIWYG,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Enter member description here which describes the position of member in company', 'taman-kit-pro' ),
				'condition' => array(
					'team_member_description_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'link_type',
			array(
				'label'   => __( 'Link Type', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'  => __( 'None', 'taman-kit-pro' ),
					'image' => __( 'Image', 'taman-kit-pro' ),
					'title' => __( 'Title', 'taman-kit-pro' ),
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_social_links',
			array(
				'label' => __( 'Social Links', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'member_social_links',
			array(
				'label'        => __( 'Show Social Links', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'taman-kit-pro' ),
				'label_off'    => __( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'select_social_icon',
			array(
				'label'            => __( 'Social Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'social_icon',
				'recommended'      => array(
					'fa-brands' => array(
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'elementor',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'stumbleupon',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					),
					'fa-solid'  => array(
						'envelope',
						'link',
						'rss',
					),
				),
			)
		);

		$repeater->add_control(
			'social_link',
			array(
				'label'       => __( 'Social Link', 'taman-kit-pro' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'placeholder' => __( 'Enter URL', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'team_member_social',
			array(
				'label'       => __( 'Add Social Links', 'taman-kit-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'select_social_icon' => array(
							'value'   => 'fab fa-facebook',
							'library' => 'fa-brands',
						),
						'social_link'        => array(
							'url' => '#',
						),
					),
					array(
						'select_social_icon' => array(
							'value'   => 'fab fa-twitter',
							'library' => 'fa-brands',
						),
						'social_link'        => array(
							'url' => '#',
						),
					),
					array(
						'select_social_icon' => array(
							'value'   => 'fab fa-youtube',
							'library' => 'fa-brands',
						),
						'social_link'        => array(
							'url' => '#',
						),
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( select_social_icon, social, true, migrated, true ) }}}',
				'condition'   => array(
					'member_social_links' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_box_settings',
			array(
				'label' => __( 'Settings', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'name_html_tag',
			array(
				'label'   => __( 'Name HTML Tag', 'taman-kit-pro' ),
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
			'position_html_tag',
			array(
				'label'   => __( 'Position HTML Tag', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'div',
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
			'social_links_position',
			array(
				'label'     => __( 'Social Links Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after_desc',
				'options'   => array(
					'before_desc' => __( 'Before Description', 'taman-kit-pro' ),
					'after_desc'  => __( 'After Description', 'taman-kit-pro' ),
				),
				'condition' => array(
					'member_social_links' => 'yes',
					'overlay_content'     => array( 'none', 'all_content' ),
				),
			)
		);

		$this->add_control(
			'overlay_content',
			array(
				'label'   => __( 'Overlay Content', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'         => __( 'None', 'taman-kit-pro' ),
					'social_icons' => __( 'Social Icons', 'taman-kit-pro' ),
					'content'      => __( 'Description', 'taman-kit-pro' ),
					'all_content'  => __( 'Description + Social Icons', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'member_title_divider',
			array(
				'label'        => __( 'Divider after Name', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Show', 'taman-kit-pro' ),
				'label_off'    => __( 'Hide', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'member_position_divider',
			array(
				'label'        => __( 'Divider after Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'hide',
				'label_on'     => __( 'Show', 'taman-kit-pro' ),
				'label_off'    => __( 'Hide', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'team_member_position!' => '',
				),
			)
		);

		$this->add_control(
			'member_description_divider',
			array(
				'label'        => __( 'Divider after Description', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'hide',
				'label_on'     => __( 'Show', 'taman-kit-pro' ),
				'label_off'    => __( 'Hide', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'team_member_description_switch' => 'yes',
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
			'section_content_style',
			array(
				'label' => __( 'Content', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'member_box_alignment',
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'content_background',
				'label'     => __( 'Background', 'taman-kit-pro' ),
				'types'     => array( 'classic', 'gradient' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .tk-tm-content-normal',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'member_content_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'separator'   => 'before',
				'selector'    => '{{WRAPPER}} .tk-tm-content',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .tk-tm-content',
			)
		);

		$this->add_responsive_control(
			'member_box_content_margin',
			array(
				'label'      => __( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .tk-tm-content-normal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'member_box_content_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-tm-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_overlay_style',
			array(
				'label'     => __( 'Overlay', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'overlay_content!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'overlay_alignment',
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-overlay-content-wrap' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'overlay_content!' => 'none',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'overlay_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .tk-tm-overlay-content-wrap:before',
				'condition' => array(
					'overlay_content!' => 'none',
				),
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'     => __( 'Opacity', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-overlay-content-wrap:before' => 'opacity: {{SIZE}};',
				),
				'condition' => array(
					'overlay_content!' => 'none',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_image_style',
			array(
				'label' => __( 'Image', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'member_image_width',
			array(
				'label'          => __( 'Image Width', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 1200,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-image' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'member_image_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-tm-image img',
			)
		);

		$this->add_control(
			'member_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-tm-image img, {{WRAPPER}} .tk-tm-overlay-content-wrap:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_name_style',
			array(
				'label' => __( 'Name', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'member_name_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'selector' => '{{WRAPPER}} .tk-tm-name',
			)
		);

		$this->add_control(
			'member_name_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'member_name_margin',
			array(
				'label'          => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-tm-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'name_divider_heading',
			array(
				'label'     => __( 'Divider', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'name_divider_color',
			array(
				'label'     => __( 'Divider Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-title-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'name_divider_style',
			array(
				'label'     => __( 'Divider Style', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'taman-kit-pro' ),
					'dotted' => __( 'Dotted', 'taman-kit-pro' ),
					'dashed' => __( 'Dashed', 'taman-kit-pro' ),
					'double' => __( 'Double', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-title-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'name_divider_width',
			array(
				'label'          => __( 'Divider Width', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 100,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 800,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-title-divider' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'name_divider_height',
			array(
				'label'          => __( 'Divider Height', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 4,
				),
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 20,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-title-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'name_divider_margin',
			array(
				'label'          => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-tm-title-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_position_style',
			array(
				'label' => __( 'Position', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'member_position_typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .tk-tm-position',
			)
		);

		$this->add_control(
			'member_position_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-position' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'member_position_margin',
			array(
				'label'          => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-tm-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'position_divider_heading',
			array(
				'label'     => __( 'Divider', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'position_divider_color',
			array(
				'label'     => __( 'Divider Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-position-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'position_divider_style',
			array(
				'label'     => __( 'Divider Style', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'taman-kit-pro' ),
					'dotted' => __( 'Dotted', 'taman-kit-pro' ),
					'dashed' => __( 'Dashed', 'taman-kit-pro' ),
					'double' => __( 'Double', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-position-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'position_divider_width',
			array(
				'label'          => __( 'Divider Width', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 100,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 800,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-position-divider' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'position_divider_height',
			array(
				'label'          => __( 'Divider Height', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 4,
				),
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 20,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-position-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'position_divider_margin',
			array(
				'label'          => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-tm-position-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_description_style',
			array(
				'label'     => __( 'Description', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'member_description_typography',
				'label'     => __( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .tk-tm-description',
				'condition' => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
				),
			)
		);

		$this->add_control(
			'member_description_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-description' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
				),
			)
		);

		$this->add_responsive_control(
			'member_description_margin',
			array(
				'label'          => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-tm-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
				),
			)
		);

		$this->add_control(
			'description_divider_heading',
			array(
				'label'     => __( 'Divider', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
					'member_description_divider'     => 'yes',
				),
			)
		);

		$this->add_control(
			'description_divider_color',
			array(
				'label'     => __( 'Divider Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-description-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
					'member_description_divider'     => 'yes',
				),
			)
		);

		$this->add_control(
			'description_divider_style',
			array(
				'label'     => __( 'Divider Style', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'taman-kit-pro' ),
					'dotted' => __( 'Dotted', 'taman-kit-pro' ),
					'dashed' => __( 'Dashed', 'taman-kit-pro' ),
					'double' => __( 'Double', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-description-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
					'member_description_divider'     => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'description_divider_width',
			array(
				'label'          => __( 'Divider Width', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 100,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 800,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-description-divider' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
					'member_description_divider'     => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'description_divider_height',
			array(
				'label'          => __( 'Divider Height', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 4,
				),
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 20,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-description-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
					'member_description_divider'     => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'description_divider_margin',
			array(
				'label'          => __( 'Margin Bottom', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-tm-description-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'team_member_description_switch' => 'yes',
					'team_member_description!'       => '',
					'member_description_divider'     => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_social_links_style',
			array(
				'label' => __( 'Social Links', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'member_icons_gap',
			array(
				'label'          => __( 'Icons Gap', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 60,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-social-links li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'member_icon_size',
			array(
				'label'          => __( 'Icon Size', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 30,
					),
				),
				'default'        => array(
					'size' => '14',
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_links_style' );

		$this->start_controls_tab(
			'tab_links_normal',
			array(
				'label' => __( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'member_links_icons_color',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'member_links_bg_color',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'member_links_border',
				'label'       => __( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'separator'   => 'before',
				'selector'    => '{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap',
			)
		);

		$this->add_control(
			'member_links_border_radius',
			array(
				'label'      => __( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'member_links_padding',
			array(
				'label'      => __( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_links_hover',
			array(
				'label' => __( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'member_links_icons_color_hover',
			array(
				'label'     => __( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'member_links_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'member_links_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-tm-social-links .tk-tm-social-icon-wrap:hover' => 'border-color: {{VALUE}};',
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

	protected function render_image() {
		$settings = $this->get_settings();
		$link_key = 'link';

		if ( ! empty( $settings['image']['url'] ) ) {
			if ( 'image' === $settings['link_type'] && $settings['link']['url'] ) {
				printf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( $link_key ), Group_Control_Image_Size::get_attachment_image_html( $settings ) );
			} else {
				echo Group_Control_Image_Size::get_attachment_image_html( $settings );
			}
		}
	}

	protected function render_name() {
		$settings = $this->get_settings_for_display();

		$member_key = 'team_member_name';
		$link_key   = 'link';

		$this->add_inline_editing_attributes( $member_key, 'none' );
		$this->add_render_attribute( $member_key, 'class', 'tk-tm-name' );

		if ( $settings[ $member_key ] ) {
			if ( 'title' === $settings['link_type'] && $settings['link']['url'] ) {
				printf( '<%1$s %2$s><a %3$s>%4$s</a></%1$s>', $settings['name_html_tag'], $this->get_render_attribute_string( $member_key ), $this->get_render_attribute_string( $link_key ), $settings['team_member_name'] );
			} else {
				printf( '<%1$s %2$s>%3$s</%1$s>', $settings['name_html_tag'], $this->get_render_attribute_string( $member_key ), $settings['team_member_name'] );
			}
		}

		if ( 'yes' === $settings['member_title_divider'] ) { ?>
			<div class="tk-tm-title-divider-wrap">
				<div class="tk-tm-divider tk-tm-title-divider"></div>
			</div>
			<?php
		}
	}

	protected function render_position() {
		$settings = $this->get_settings_for_display();
		$this->add_inline_editing_attributes( 'team_member_position', 'none' );
		$this->add_render_attribute( 'team_member_position', 'class', 'tk-tm-position' );

		if ( $settings['team_member_position'] ) {
			printf( '<%1$s %2$s>%3$s</%1$s>', $settings['position_html_tag'], $this->get_render_attribute_string( 'team_member_position' ), $settings['team_member_position'] );
		}

		if ( 'yes' === $settings['member_position_divider'] ) {
			?>
			<div class="tk-tm-position-divider-wrap">
				<div class="tk-tm-divider tk-tm-position-divider"></div>
			</div>
			<?php
		}
	}

	protected function render_description() {
		$settings = $this->get_settings_for_display();
		$this->add_inline_editing_attributes( 'team_member_description', 'basic' );
		$this->add_render_attribute( 'team_member_description', 'class', 'tk-tm-description' );

		if ( 'yes' === $settings['team_member_description_switch'] ) {
			if ( $settings['team_member_description'] ) {
				?>
				<div <?php echo $this->get_render_attribute_string( 'team_member_description' ); ?>>
					<?php echo $this->parse_text_editor( $settings['team_member_description'] ); ?>
				</div>
			<?php } ?>
			<?php if ( 'yes' === $settings['member_description_divider'] ) { ?>
				<div class="tk-tm-description-divider-wrap">
					<div class="tk-tm-divider tk-tm-description-divider"></div>
				</div>
				<?php
			}
		}
	}

	protected function render_social_links() {
		$settings = $this->get_settings_for_display();
		$i        = 1;

		$fallback_defaults = array(
			'fa fa-facebook',
			'fa fa-twitter',
			'fa fa-google-plus',
		);

		$migration_allowed = Icons_Manager::is_migration_allowed();

		// add old default
		if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
			$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
		}

		$migrated = isset( $item['__fa4_migrated']['select_social_icon'] );
		$is_new   = ! isset( $item['icon'] ) && $migration_allowed;
		?>
		<div class="tk-tm-social-links-wrap">
			<ul class="tk-tm-social-links">
				<?php foreach ( $settings['team_member_social'] as $index => $item ) : ?>
					<?php
					$migrated = isset( $item['__fa4_migrated']['select_social_icon'] );
					$is_new   = empty( $item['social_icon'] ) && $migration_allowed;
					$social   = '';

					// add old default
					if ( empty( $item['social_icon'] ) && ! $migration_allowed ) {
						$item['social_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-wordpress';
					}

					if ( ! empty( $item['social_icon'] ) ) {
						$social = str_replace( 'fa fa-', '', $item['social_icon'] );
					}

					if ( ( $is_new || $migrated ) && 'svg' !== $item['select_social_icon']['library'] ) {
						$social = explode( ' ', $item['select_social_icon']['value'], 2 );
						if ( empty( $social[1] ) ) {
							$social = '';
						} else {
							$social = str_replace( 'fa-', '', $social[1] );
						}
					}
					if ( 'svg' === $item['select_social_icon']['library'] ) {
						$social = '';
					}

					$this->add_render_attribute( 'social-link', 'class', 'tk-tm-social-link' );
					$social_link_key = 'social-link' . $i;
					if ( ! empty( $item['social_link']['url'] ) ) {
						$this->add_link_attributes( $social_link_key, $item['social_link'] );
					}
					?>
					<li>
						<?php
							// if ( $item['social_icon'] ) :
						?>
								<a <?php echo $this->get_render_attribute_string( $social_link_key ); ?>>
									<span class="tk-tm-social-icon-wrap">
										<span class="elementor-screen-only"><?php echo ucwords( $social ); ?></span>
										<span class="tk-tm-social-icon tk-icon">
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $item['select_social_icon'], array( 'aria-hidden' => 'true' ) );
										} else {
											?>
											<i class="<?php echo esc_attr( $item['social_icon'] ); ?>"></i>
										<?php } ?>
										</span>
									</span>
								</a>
							<?php
							// endif;
							?>
					</li>
					<?php
					$i++;
				endforeach;
				?>
			</ul>
		</div>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$link_key = 'link';

		if ( 'none' !== $settings['link_type'] ) {
			if ( ! empty( $settings['link']['url'] ) ) {
				$this->add_link_attributes( $link_key, $settings['link'] );
			}
		}
		?>
		<div class="tk-tm-wrapper">
			<div class="tk-tm">
				<div class="tk-tm-image"> 
					<?php $this->render_image(); ?>

					<?php if ( 'none' !== $settings['overlay_content'] ) { ?>
						<div class="tk-tm-overlay-content-wrap">
							<div class="tk-tm-content">
								<?php
								if ( 'yes' === $settings['member_social_links'] ) {
									if ( 'social_icons' === $settings['overlay_content'] ) {
										$this->render_social_links();
									} elseif ( 'all_content' === $settings['overlay_content'] ) {
										if ( 'before_desc' === $settings['social_links_position'] ) {
											$this->render_social_links();
										}
									}
								}

								if ( 'content' === $settings['overlay_content'] || 'all_content' === $settings['overlay_content'] ) {
									$this->render_description();
								}

								if ( 'yes' === $settings['member_social_links'] && 'all_content' === $settings['overlay_content'] ) {
									if ( 'after_desc' === $settings['social_links_position'] ) {
										$this->render_social_links();
									}
								}
								?>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="tk-tm-content tk-tm-content-normal">
					<?php
					// Name
					$this->render_name();

					// Position
					$this->render_position();

					if ( 'yes' === $settings['member_social_links'] && ( 'none' === $settings['overlay_content'] || 'content' === $settings['overlay_content'] ) ) {
						if ( 'none' === $settings['overlay_content'] ) {
							if ( 'before_desc' === $settings['social_links_position'] ) {
								$this->render_social_links();
							}
						} else {
							$this->render_social_links();
						}
					}

					if ( 'none' === $settings['overlay_content'] || 'social_icons' === $settings['overlay_content'] ) {
						$this->render_description();
					}

					if ( 'yes' === $settings['member_social_links'] && ( 'none' === $settings['overlay_content'] || 'content' === $settings['overlay_content'] ) ) {
						if ( 'after_desc' === $settings['social_links_position'] && 'none' === $settings['overlay_content'] ) {
							$this->render_social_links();
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function _content_template() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		?>
		<#
			function member_image() {
				if ( '' !== settings.image.url ) {
					var image = {
						id: settings.image.id,
						url: settings.image.url,
						size: settings.image_size,
						dimension: settings.image_custom_dimension,
						model: view.getEditModel()
					};

					var image_url = elementor.imagesManager.getImageUrl( image );
				}
		   
				if ( settings.image.url != '' ) {
					if ( settings.link_type == 'image' && settings.link.url != '' ) {
						var target = settings.link.is_external ? ' target="_blank"' : '';
						var nofollow = settings.link.nofollow ? ' rel="nofollow"' : '';
						#>
						<a href="{{ settings.link.url }}"{{ target }}{{ nofollow }}>
							<img src="{{ image_url }}" alt="">
						</a>
					<# } else { #>
						<img src="{{ image_url }}" alt="">
					<# }
				}
			}

			function member_name() {
				if ( settings.team_member_name != '' ) {
					var name = settings.team_member_name;

					view.addRenderAttribute( 'team_member_name', 'class', 'tk-tm-name' );

					view.addInlineEditingAttributes( 'team_member_name' );

					var name_html = '<' + settings.name_html_tag  + ' ' + view.getRenderAttributeString( 'team_member_name' ) + '>' + name + '</' + settings.name_html_tag + '>';
				}
		   
				if ( settings.link_type == 'title' && settings.link.url != '' ) { #>
					<#
					var target = settings.link.is_external ? ' target="_blank"' : '';
					var nofollow = settings.link.nofollow ? ' rel="nofollow"' : '';
					#>
					<a href="{{ settings.link.url }}"{{ target }}{{ nofollow }}>
						<# print( name_html ); #>
					</a>
				<# } else {
					print( name_html );
				}

				if ( settings.member_title_divider == 'yes' ) { #>
					<div class="tk-tm-title-divider-wrap">
						<div class="tk-tm-divider tk-tm-title-divider"></div>
					</div>
				<# }
			}

			function member_position() {
				if ( settings.team_member_position != '' ) {
					var position = settings.team_member_position;

					view.addRenderAttribute( 'team_member_position', 'class', 'tk-tm-position' );

					view.addInlineEditingAttributes( 'team_member_position' );

					var position_html = '<' + settings.position_html_tag  + ' ' + view.getRenderAttributeString( 'team_member_position' ) + '>' + position + '</' + settings.position_html_tag + '>';

					print( position_html );
				}
		   
				if ( settings.member_position_divider == 'yes' ) { #>
					<div class="tk-tm-position-divider-wrap">
						<div class="tk-tm-divider tk-tm-position-divider"></div>
					</div>
				<# }
			}

			function member_description() {
				if ( settings.team_member_description_switch == 'yes' ) {
					if ( settings.team_member_description != '' ) {
						var description = settings.team_member_description;

						view.addRenderAttribute( 'team_member_description', 'class', 'tk-tm-description' );

						view.addInlineEditingAttributes( 'team_member_description', 'advanced' );

						var description_html = '<' + settings.position_html_tag  + ' ' + view.getRenderAttributeString( 'team_member_description' ) + '>' + description + '</' + settings.position_html_tag + '>';

						print( description_html );
					}
		   
					if ( settings.member_description_divider == 'yes' ) { #>
						<div class="tk-tm-description-divider-wrap">
							<div class="tk-tm-divider tk-tm-description-divider"></div>
						</div>
					<# }
				}
			}
					   
			function member_social_links() { #>
				<# var iconsHTML = {}; #>
				<div class="tk-tm-social-links-wrap">
					<ul class="tk-tm-social-links">
						<# _.each( settings.team_member_social, function( item, index ) {
							var migrated = elementor.helpers.isIconMigrated( item, 'select_social_icon' );
								social = elementor.helpers.getSocialNetworkNameFromIcon( item.select_social_icon, item.social_icon, false, migrated );
							#>
							<li>
								<# if ( item.social_icon || item.select_social_icon ) { #>
									<# if ( item.social_link && item.social_link.url ) { #>
										<a class="tk-tm-social-link" href="{{ item.social_link.url }}">
									<# } #>
										<span class="tk-tm-social-icon-wrap">
											<span class="tk-tm-social-icon tk-icon">
												<span class="elementor-screen-only">{{{ social }}}</span>
												<#
													iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.select_social_icon, {}, 'i', 'object' );
													if ( ( ! item.social_icon || migrated ) && iconsHTML[ index ] && iconsHTML[ index ].rendered ) { #>
														{{{ iconsHTML[ index ].value }}}
													<# } else { #>
														<i class="{{ item.social_icon }}"></i>
													<# }
												#>
											</span>
										</span>
									<# if ( item.social_link && item.social_link.url ) { #>
										</a>
									<# } #>
								<# } #>
							</li>
						<# } ); #>
					</ul>
				</div>
		<# } #>

		<div class="tk-tm-wrapper">
			<div class="tk-tm">
				<div class="tk-tm-image"> 
					<# member_image(); #>

					<# if ( settings.overlay_content != 'none' ) { #>
						<div class="tk-tm-overlay-content-wrap">
							<div class="tk-tm-content">
								<#
									if ( settings.member_social_links == 'yes' ) {
										if ( settings.overlay_content == 'social_icons' ) {
											member_social_links();
										} else if ( settings.overlay_content == 'all_content' ) {
											if ( settings.social_links_position == 'before_desc' ) {
												member_social_links();
											}
										}
									}

									if ( settings.overlay_content == 'content' || settings.overlay_content == 'all_content' ) {
										member_description();
									}

									if ( settings.member_social_links == 'yes' && settings.overlay_content == 'all_content' ) {
										if ( settings.social_links_position == 'after_desc' ) {
											member_social_links();
										}
									}
								#>
							</div>
						</div>
					<# } #>
				</div>
				<div class="tk-tm-content tk-tm-content-normal">
					<#
						member_name();
						member_position();

						if ( settings.member_social_links == 'yes' && ( settings.overlay_content == 'none' || settings.overlay_content == 'content' ) ) {
							if ( settings.overlay_content == 'none' ) {
								if ( settings.social_links_position == 'before_desc' ) {
									member_social_links();
								}
							} else {
								member_social_links();
							}
						}

						if ( settings.overlay_content == 'none' || settings.overlay_content == 'social_icons' ) {
							member_description();
						}

						if ( settings.member_social_links == 'yes' && ( settings.overlay_content == 'none' || settings.overlay_content == 'content' ) ) {
							if ( settings.social_links_position == 'after_desc' && settings.overlay_content == 'none' ) {
								member_social_links();
							}
						}
					#>
				</div>
			</div>
		</div>
		<?php
	}

}
