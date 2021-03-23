<?php
/**
 * Elementor flipbox Widget.
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
use \Elementor\Plugin;
use Elementor\Core\Schemes;
use Elementor\Control_Media;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Responsive\Responsive;
use Elementor\Modules\DynamicTags\Module as TagsModule;



/**
 * FlipBox class
 */
class FlipBox extends \Elementor\Widget_Base {

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
			'taman-kit-flipbox',
			TAMAN_KIT_PRO_URL . 'public/css/widgets/flipbox.css',
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
		return array( 'taman-kit-flipbox' );
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve flipbox widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-flipbox';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve flipbox widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'FlipBox', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve flipbox widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-flip-box';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the flipbox widget belongs to.
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
	 * Register flipbox widget controls.
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
			'section_content',
			array(
				'label' => esc_html__( 'General Settings', 'stratum' ),
			)
		);

			$this->add_control(
				'flip_effect',
				array(
					'label'       => esc_html__( 'Flip Effect', 'stratum' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'flip',
					'label_block' => false,
					'options'     => array(
						'flip'     => esc_html__( 'Flip', 'stratum' ),
						'slide'    => esc_html__( 'Slide', 'stratum' ),
						'push'     => esc_html__( 'Push', 'stratum' ),
						'fade'     => esc_html__( 'Fade', 'stratum' ),
						'zoom-in'  => esc_html__( 'Zoom In', 'stratum' ),
						'zoom-out' => esc_html__( 'Zoom Out', 'stratum' ),
					),
				)
			);

			$this->add_control(
				'flip_direction',
				array(
					'label'       => esc_html__( 'Flip Direction', 'stratum' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'right',
					'label_block' => false,
					'options'     => array(
						'right' => esc_html__( 'Right', 'stratum' ),
						'left'  => esc_html__( 'Left', 'stratum' ),
						'up'    => esc_html__( 'Up', 'stratum' ),
						'down'  => esc_html__( 'Down', 'stratum' ),
					),
					'condition'   => array(
						'flip_effect!' => array(
							'fade',
							'zoom-in',
							'zoom-out',
						),
					),
				)
			);

			$this->add_responsive_control(
				'height',
				array(
					'label'      => esc_html__( 'Height', 'stratum' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'min' => 100,
							'max' => 1000,
						),
						'vh' => array(
							'min' => 10,
							'max' => 100,
						),
					),
					'size_units' => array( 'px', 'vh' ),
					'selectors'  => array(
						'{{WRAPPER}} .tk-flipbox' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'stratum' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 200,
						),
					),
					'separator'  => 'after',
					'selectors'  => array(
						'{{WRAPPER}} .tk-flipbox__layer,
                        {{WRAPPER}} .tk-flipbox__layer__overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'elements_box_shadow',
				array(
					'label'     => __( 'Box Shadow', 'plugin-domain' ),
					'type'      => \Elementor\Controls_Manager::SELECT2,
					'multiple'  => true,
					'options'   => taman_kit_box_shadow(),
					'default'   => '',
					'multiple'  => true,
					'separator' => 'after',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_front_content',
			array(
				'label' => esc_html__( 'Front', 'stratum' ),
			)
		);

			$this->start_controls_tabs( 'front_content_tabs' );

			$this->start_controls_tab( 'front_content_tab', array( 'label' => esc_html__( 'Content', 'stratum' ) ) );

					$this->add_control(
						'graphic_element',
						array(
							'label'   => esc_html__( 'Graphic Element', 'stratum' ),
							'type'    => Controls_Manager::CHOOSE,
							'options' => array(
								'icon'  => array(
									'title' => esc_html__( 'Icon', 'stratum' ),
									'icon'  => 'eicon-star',
								),
								'image' => array(
									'title' => esc_html__( 'Image', 'stratum' ),
									'icon'  => 'fas fa-image',
								),
								'none'  => array(
									'title' => esc_html__( 'None', 'stratum' ),
									'icon'  => 'eicon-ban',
								),
							),
							'default' => 'icon',
						)
					);

					$this->add_control(
						'selected_icon',
						array(
							'label'     => esc_html__( 'Icon', 'stratum' ),
							'type'      => \Elementor\Controls_Manager::ICONS,
							'default'   => array(
								'value'   => 'fas fa-star',
								'library' => 'solid',
							),
							'condition' => array(
								'graphic_element' => 'icon',
							),
						)
					);

					$this->add_control(
						'icon_view',
						array(
							'label'     => esc_html__( 'View', 'stratum' ),
							'type'      => Controls_Manager::SELECT,
							'options'   => array(
								'default' => esc_html__( 'Default', 'stratum' ),
								'stacked' => esc_html__( 'Stacked', 'stratum' ),
								'framed'  => esc_html__( 'Framed', 'stratum' ),
							),
							'default'   => 'default',
							'condition' => array(
								'graphic_element' => 'icon',
							),
						)
					);

					$this->add_control(
						'icon_shape',
						array(
							'label'     => esc_html__( 'Shape', 'stratum' ),
							'type'      => Controls_Manager::SELECT,
							'options'   => array(
								'circle' => esc_html__( 'Circle', 'stratum' ),
								'square' => esc_html__( 'Square', 'stratum' ),
							),
							'default'   => 'circle',
							'condition' => array(
								'icon_view!'      => 'default',
								'graphic_element' => 'icon',
							),
						)
					);

					$this->add_control(
						'image',
						array(
							'label'     => esc_html__( 'Choose Image', 'stratum' ),
							'type'      => Controls_Manager::MEDIA,
							'default'   => array(
								'url' => Utils::get_placeholder_image_src(),
							),
							'dynamic'   => array(
								'active' => true,
							),
							'condition' => array(
								'graphic_element' => 'image',
							),
						)
					);

					$this->add_control(
						'image_size',
						array(
							'type'      => 'select',
							'label'     => esc_html__( 'Image Size', 'stratum' ),
							'default'   => 'full',
							'options'   => array( 'thumbnail', 'medium', 'medium_large', 'large', 'full' ),
							'condition' => array(
								'graphic_element' => 'image',
							),
						)
					);

					$this->add_control(
						'front_title_text',
						array(
							'label'       => esc_html__( 'Title & Description', 'stratum' ),
							'type'        => Controls_Manager::TEXT,
							'default'     => esc_html__( 'This is the title', 'stratum' ),
							'placeholder' => esc_html__( 'Enter your title', 'stratum' ),
							'dynamic'     => array(
								'active' => true,
							),
							'label_block' => true,
							'separator'   => 'before',
						)
					);

					$this->add_control(
						'front_description_text',
						array(
							'label'       => esc_html__( 'Description', 'stratum' ),
							'type'        => Controls_Manager::TEXTAREA,
							'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'stratum' ),
							'placeholder' => esc_html__( 'Enter your description', 'stratum' ),
							'separator'   => 'none',
							'dynamic'     => array(
								'active' => true,
							),
							'rows'        => 10,
							'show_label'  => false,
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'front_background_tab', array( 'label' => esc_html__( 'Background', 'stratum' ) ) );

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'     => 'front_background',
							'types'    => array( 'classic', 'gradient' ),
							'selector' => '{{WRAPPER}} .tk-flipbox__front',
						)
					);

					$this->add_control(
						'front_background_overlay',
						array(
							'label'     => esc_html__( 'Background Overlay', 'stratum' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .tk-flipbox__front .tk-flipbox__layer__overlay' => 'background-color: {{VALUE}};',
							),
							'separator' => 'before',
							'condition' => array(
								'front_background_image[id]!' => '',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'      => 'front_background_gradient_overlay',
							'types'     => array( 'gradient' ),
							'selector'  => '{{WRAPPER}} .tk-flipbox__front .tk-flipbox__layer__overlay',
							'condition' => array(
								'front_background_image[id]!' => '',
							),
						)
					);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_back_content',
			array(
				'label' => esc_html__( 'Back', 'stratum' ),
			)
		);

			$this->start_controls_tabs( 'back_content_tabs' );

			$this->start_controls_tab( 'back_content_tab', array( 'label' => esc_html__( 'Content', 'stratum' ) ) );

					$this->add_control(
						'back_title_text',
						array(
							'label'       => esc_html__( 'Title & Description', 'stratum' ),
							'type'        => Controls_Manager::TEXT,
							'default'     => esc_html__( 'This is the title', 'stratum' ),
							'placeholder' => esc_html__( 'Enter your title', 'stratum' ),
							'dynamic'     => array(
								'active' => true,
							),
							'label_block' => true,
							'separator'   => 'before',
						)
					);

					$this->add_control(
						'back_description_text',
						array(
							'label'       => esc_html__( 'Description', 'stratum' ),
							'type'        => Controls_Manager::TEXTAREA,
							'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'stratum' ),
							'placeholder' => esc_html__( 'Enter your description', 'stratum' ),
							'separator'   => 'none',
							'dynamic'     => array(
								'active' => true,
							),
							'rows'        => 10,
							'show_label'  => false,
						)
					);

					$this->add_control(
						'show_button',
						array(
							'label'     => esc_html__( 'Show button', 'stratum' ),
							'type'      => Controls_Manager::SWITCHER,
							'default'   => '',
							'separator' => 'before',
						)
					);

					$this->add_control(
						'button_text',
						array(
							'label'     => esc_html__( 'Button Text', 'stratum' ),
							'type'      => Controls_Manager::TEXT,
							'default'   => esc_html__( 'Click Here', 'stratum' ),
							'dynamic'   => array(
								'active' => true,
							),
							'condition' => array(
								'show_button!' => '',
							),
						)
					);

					$this->add_control(
						'link',
						array(
							'label'       => esc_html__( 'Link', 'stratum' ),
							'type'        => Controls_Manager::URL,
							'dynamic'     => array(
								'active' => true,
							),
							'condition'   => array(
								'show_button!' => '',
							),
							'placeholder' => esc_html__( 'https://your-link.com', 'stratum' ),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'back_background_tab', array( 'label' => esc_html__( 'Background', 'stratum' ) ) );

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'     => 'back_background',
							'types'    => array( 'classic', 'gradient' ),
							'selector' => '{{WRAPPER}} .tk-flipbox__back',
						)
					);

					$this->add_control(
						'back_background_overlay',
						array(
							'label'     => esc_html__( 'Background Overlay', 'stratum' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .tk-flipbox__back .tk-flipbox__layer__overlay' => 'background-color: {{VALUE}};',
							),
							'separator' => 'before',
							'condition' => array(
								'back_background_image[id]!' => '',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'      => 'back_background_gradient_overlay',
							'types'     => array( 'gradient' ),
							'selector'  => '{{WRAPPER}} .tk-flipbox__back .tk-flipbox__layer__overlay',
							'condition' => array(
								'back_background_image[id]!' => '',
							),
						)
					);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/*
		*==================================================================================
		*
		*=============================== Style Tab: Style =================================
		*
		*==================================================================================
		*/

		$this->start_controls_section(
			'section_syle_front',
			array(
				'label' => esc_html__( 'Front', 'stratum' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'front_padding',
				array(
					'label'      => esc_html__( 'Padding', 'stratum' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tk-flipbox__front .tk-flipbox__layer__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'front_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'stratum' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'stratum' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'stratum' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'stratum' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'default'   => 'center',
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__front .tk-flipbox__layer__inner' => 'text-align: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'front_vertical_position',
				array(
					'label'                => esc_html__( 'Vertical Position', 'stratum' ),
					'type'                 => Controls_Manager::CHOOSE,
					'options'              => array(
						'top'    => array(
							'title' => esc_html__( 'Top', 'stratum' ),
							'icon'  => 'eicon-v-align-top',
						),
						'middle' => array(
							'title' => esc_html__( 'Middle', 'stratum' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'stratum' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors_dictionary' => array(
						'top'    => 'flex-start',
						'middle' => 'center',
						'bottom' => 'flex-end',
					),
					'selectors'            => array(
						'{{WRAPPER}} .tk-flipbox__front .tk-flipbox__layer__overlay' => 'justify-content: {{VALUE}}',
					),
					'default'              => 'middle',
					'separator'            => 'after',
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'front_border',
					'selector'  => '{{WRAPPER}} .tk-flipbox__front',
					'separator' => 'before',
				)
			);

			$this->add_control(
				'heading_icon_style',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Icon', 'stratum' ),
					'condition' => array(
						'graphic_element' => 'icon',
					),
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'icon_spacing',
				array(
					'label'     => esc_html__( 'Spacing', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'graphic_element' => 'icon',
					),
				)
			);

			$this->add_control(
				'icon_primary_color',
				array(
					'label'     => esc_html__( 'Primary Color', 'stratum' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .stratum-view-default .tk-flipbox__icon' => 'color: {{VALUE}}',
						'{{WRAPPER}} .stratum-view-stacked .tk-flipbox__icon' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .stratum-view-framed  .tk-flipbox__icon' => 'border-color: {{VALUE}}',
					),
					'condition' => array(
						'graphic_element' => 'icon',
					),
				)
			);

			$this->add_control(
				'icon_secondary_color',
				array(
					'label'     => esc_html__( 'Secondary Color', 'stratum' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'condition' => array(
						'graphic_element' => 'icon',
						'icon_view!'      => 'default',
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__icon' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_size',
				array(
					'label'     => esc_html__( 'Icon Size', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 6,
							'max' => 300,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'graphic_element' => 'icon',
					),
				)
			);

			$this->add_responsive_control(
				'icon_padding',
				array(
					'label'     => esc_html__( 'Icon Padding', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__icon' => 'padding: {{SIZE}}{{UNIT}};',
					),
					'range'     => array(
						'em' => array(
							'min' => 0,
							'max' => 5,
						),
					),
					'condition' => array(
						'graphic_element' => 'icon',
						'icon_view!'      => 'default',
					),
				)
			);

			$this->add_responsive_control(
				'icon_rotate',
				array(
					'label'     => esc_html__( 'Icon Rotation', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => array(
						'unit' => 'deg',
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
					),
					'condition' => array(
						'graphic_element' => 'icon',
					),
				)
			);

			$this->add_responsive_control(
				'icon_border_width',
				array(
					'label'     => esc_html__( 'Border Width', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__icon' => 'border-width: {{SIZE}}{{UNIT}}',
					),
					'condition' => array(
						'graphic_element' => 'icon',
						'icon_view'       => 'framed',
					),
				)
			);

			$this->add_responsive_control(
				'icon_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'stratum' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tk-flipbox__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'graphic_element' => 'icon',
						'icon_view!'      => 'default',
						'icon_shape!'     => 'circle',
					),
				)
			);

			$this->add_control(
				'heading_image_style',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Image', 'stratum' ),
					'condition' => array(
						'graphic_element' => 'image',
					),
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'image_spacing',
				array(
					'label'     => esc_html__( 'Spacing', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'graphic_element' => 'image',
					),
				)
			);

			$this->add_responsive_control(
				'image_width',
				array(
					'label'      => esc_html__( 'Size', 'stratum' ) . ' (%)',
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( '%' ),
					'default'    => array(
						'unit' => '%',
					),
					'range'      => array(
						'%' => array(
							'min' => 5,
							'max' => 100,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .tk-flipbox__image img' => 'width: {{SIZE}}{{UNIT}}',
					),
					'condition'  => array(
						'graphic_element' => 'image',
					),
				)
			);

			$this->add_control(
				'image_opacity',
				array(
					'label'     => esc_html__( 'Opacity', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => array(
						'size' => 1,
					),
					'range'     => array(
						'px' => array(
							'max'  => 1,
							'min'  => 0.10,
							'step' => 0.01,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__image' => 'opacity: {{SIZE}};',
					),
					'condition' => array(
						'graphic_element' => 'image',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'image_border',
					'selector'  => '{{WRAPPER}} .tk-flipbox__image img',
					'condition' => array(
						'graphic_element' => 'image',
					),
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'image_border_radius',
				array(
					'label'     => esc_html__( 'Border Radius', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 200,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
					),
					'condition' => array(
						'graphic_element' => 'image',
					),
				)
			);

			$this->add_control(
				'front_heading_title_style',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Title', 'stratum' ),
					'separator' => 'before',
					'condition' => array(
						'front_title_text!' => '',
					),
				)
			);

			$this->add_responsive_control(
				'front_title_spacing',
				array(
					'label'     => esc_html__( 'Spacing', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__front .tk-flipbox__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'front_description_text!' => '',
						'front_title_text!'       => '',
					),
				)
			);

			$this->add_control(
				'front_title_color',
				array(
					'label'     => esc_html__( 'Text Color', 'stratum' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__front .tk-flipbox__title' => 'color: {{VALUE}}',

					),
					'condition' => array(
						'front_title_text!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'front_title_typography',
					'scheme'    => Schemes\Typography::TYPOGRAPHY_1,
					'selector'  => '{{WRAPPER}} .tk-flipbox__front .tk-flipbox__title',
					'condition' => array(
						'front_title_text!' => '',
					),
				)
			);

			$this->add_control(
				'front_heading_description_style',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Description', 'stratum' ),
					'separator' => 'before',
					'condition' => array(
						'front_description_text!' => '',
					),
				)
			);

			$this->add_control(
				'front_description_color',
				array(
					'label'     => esc_html__( 'Text Color', 'stratum' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__front .tk-flipbox__description' => 'color: {{VALUE}}',
					),
					'condition' => array(
						'front_description_text!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'front_description_typography',
					'scheme'    => Schemes\Typography::TYPOGRAPHY_3,
					'selector'  => '{{WRAPPER}} .tk-flipbox__front .tk-flipbox__description',
					'condition' => array(
						'front_description_text!' => '',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_syle_back',
			array(
				'label' => esc_html__( 'Back', 'stratum' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'back_padding',
				array(
					'label'      => esc_html__( 'Padding', 'stratum' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tk-flipbox__back .tk-flipbox__layer__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'back_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'stratum' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'stratum' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'stratum' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'stratum' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'default'   => 'center',
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__back .tk-flipbox__layer__inner' => 'text-align: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'back_vertical_position',
				array(
					'label'                => esc_html__( 'Vertical Position', 'stratum' ),
					'type'                 => Controls_Manager::CHOOSE,
					'options'              => array(
						'top'    => array(
							'title' => esc_html__( 'Top', 'stratum' ),
							'icon'  => 'eicon-v-align-top',
						),
						'middle' => array(
							'title' => esc_html__( 'Middle', 'stratum' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'stratum' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors_dictionary' => array(
						'top'    => 'flex-start',
						'middle' => 'center',
						'bottom' => 'flex-end',
					),
					'selectors'            => array(
						'{{WRAPPER}} .tk-flipbox__back .tk-flipbox__layer__overlay' => 'justify-content: {{VALUE}}',
					),
					'default'              => 'middle',
					'separator'            => 'after',
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'back_border',
					'selector'  => '{{WRAPPER}} .tk-flipbox__back',
					'separator' => 'before',
				)
			);

			$this->add_control(
				'back_heading_title_style',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Title', 'stratum' ),
					'separator' => 'before',
					'condition' => array(
						'back_title_text!' => '',
					),
				)
			);

			$this->add_responsive_control(
				'back_title_spacing',
				array(
					'label'     => esc_html__( 'Spacing', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__back .tk-flipbox__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'back_description_text!' => '',
						'back_title_text!'       => '',
					),
				)
			);

			$this->add_control(
				'back_title_color',
				array(
					'label'     => esc_html__( 'Text Color', 'stratum' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__back .tk-flipbox__title' => 'color: {{VALUE}}',

					),
					'condition' => array(
						'back_title_text!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'back_title_typography',
					'scheme'    => Schemes\Typography::TYPOGRAPHY_1,
					'selector'  => '{{WRAPPER}} .tk-flipbox__back .tk-flipbox__title',
					'condition' => array(
						'back_title_text!' => '',
					),
				)
			);

			$this->add_control(
				'back_heading_description_style',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Description', 'stratum' ),
					'separator' => 'before',
					'condition' => array(
						'back_description_text!' => '',
					),
				)
			);

			$this->add_control(
				'back_description_color',
				array(
					'label'     => esc_html__( 'Text Color', 'stratum' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__back .tk-flipbox__description' => 'color: {{VALUE}}',
					),
					'condition' => array(
						'back_description_text!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'back_description_typography',
					'scheme'    => Schemes\Typography::TYPOGRAPHY_3,
					'selector'  => '{{WRAPPER}} .tk-flipbox__back .tk-flipbox__description',
					'condition' => array(
						'back_description_text!' => '',
					),
				)
			);

			$this->add_responsive_control(
				'back_description_spacing',
				array(
					'label'     => esc_html__( 'Spacing', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__back .tk-flipbox__description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'back_description_text!' => '',
						'back_title_text!'       => '',
					),
				)
			);

			$this->add_control(
				'heading_button',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Button', 'stratum' ),
					'separator' => 'before',
					'condition' => array(
						'button_text!' => '',
						'show_button!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'button_typography',
					'selector'  => '{{WRAPPER}} .tk-flipbox__button',
					'scheme'    => Schemes\Typography::TYPOGRAPHY_4,
					'condition' => array(
						'button_text!' => '',
						'show_button!' => '',
					),
				)
			);

			$this->start_controls_tabs( 'button_tabs' );

				$this->start_controls_tab(
					'normal',
					array(
						'label'     => esc_html__( 'Normal', 'stratum' ),
						'condition' => array(
							'button_text!' => '',
							'show_button!' => '',
						),
					)
				);

					$this->add_control(
						'button_text_color',
						array(
							'label'     => esc_html__( 'Text Color', 'stratum' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .tk-flipbox__button' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'button_background_color',
						array(
							'label'     => esc_html__( 'Background Color', 'stratum' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .tk-flipbox__button' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'button_border_color',
						array(
							'label'     => esc_html__( 'Border Color', 'stratum' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .tk-flipbox__button' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'hover',
					array(
						'label'     => esc_html__( 'Hover', 'stratum' ),
						'condition' => array(
							'button_text!' => '',
							'show_button!' => '',
						),
					)
				);

					$this->add_control(
						'button_hover_text_color',
						array(
							'label'     => esc_html__( 'Text Color', 'stratum' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .tk-flipbox__button:hover' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'button_hover_background_color',
						array(
							'label'     => esc_html__( 'Background Color', 'stratum' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .tk-flipbox__button:hover' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'button_hover_border_color',
						array(
							'label'     => esc_html__( 'Border Color', 'stratum' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .tk-flipbox__button:hover' => 'border-color: {{VALUE}};',
							),
						)
					);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'button_border_width',
				array(
					'label'     => esc_html__( 'Border Width', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__button' => 'border-width: {{SIZE}}{{UNIT}};',
					),
					'separator' => 'before',
					'condition' => array(
						'button_text!' => '',
						'show_button!' => '',
					),
				)
			);

			$this->add_responsive_control(
				'button_border_radius',
				array(
					'label'     => esc_html__( 'Border Radius', 'stratum' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .tk-flipbox__button' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
					'separator' => 'after',
					'condition' => array(
						'button_text!' => '',
						'show_button!' => '',
					),
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
	 * Render flipbox widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		include TemplatesPro::get_templatet( 'flipbox', 'flipbox' );

	}

}
