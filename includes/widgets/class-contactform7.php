<?php
/**
 * Contact form7 Widget.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

namespace TamanKitPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class Contactform7
 */
class Contactform7 extends Widget_Base {

	/**
	 * Get widget get_name.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'tamankit-contact-form';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Contact Form7', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-form-horizontal';
	}

	/**
	 * Get widget categories.
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
	 * Get script depends
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_custom_help_url() {
		return '#';
	}

	/**
	 * Register Contact Form 7 controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {  // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		$this->start_controls_section(
			'tk_section_wpcf7_form',
			array(
				'label' => esc_html__( 'Contact Form', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_wpcf7_form',
			array(
				'label'       => esc_html__( 'Select Your Contact Form', 'taman-kit-pro' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_wpcf_forms(),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_wpcf7_fields',
			array(
				'label' => esc_html__( 'Fields', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_wpcf7_fields_heading',
			array(
				'label' => esc_html__( 'Width', 'taman-kit-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'tk_input_width',
			array(
				'label'      => esc_html__( 'Input Field', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1200,
					),
					'em' => array(
						'min' => 1,
						'max' => 80,
					),
				),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-text, {{WRAPPER}} .tk-cf7-container .wpcf7-file' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_textarea_width',
			array(
				'label'      => esc_html__( 'Text Area', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1200,
					),
					'em' => array(
						'min' => 1,
						'max' => 80,
					),
				),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container textarea.wpcf7-textarea' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tk_wpcf7_fields_height_heading',
			array(
				'label' => esc_html__( 'Height', 'taman-kit-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'tk_input_height',
			array(
				'label'      => esc_html__( 'Input Field', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 500,
					),
					'em' => array(
						'min' => 1,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-text' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_textarea_height',
			array(
				'label'      => esc_html__( 'Text Area', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1200,
					),
					'em' => array(
						'min' => 1,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container textarea.wpcf7-textarea' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_wpcf7_button',
			array(
				'label' => esc_html__( 'Button', 'taman-kit-pro' ),
			)
		);

		$this->add_responsive_control(
			'tk_button_width',
			array(
				'label'      => esc_html__( 'Width', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1200,
					),
					'em' => array(
						'min' => 1,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_button_height',
			array(
				'label'      => esc_html__( 'Height', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 500,
					),
					'em' => array(
						'min' => 1,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_contact_form_styles',
			array(
				'label' => esc_html__( 'Form', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tk_input_background',
			array(
				'label'     => esc_html__( 'Input Field Background', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input, {{WRAPPER}} .tk-cf7-container textarea' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'tk_input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container input, {{WRAPPER}} .tk-cf7-container textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tk_input_border',
				'selector' => '{{WRAPPER}} .tk-cf7-container input, {{WRAPPER}} .tk-cf7-container textarea',
			)
		);

		$this->add_responsive_control(
			'tk_input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container input, {{WRAPPER}} .tk-cf7-container textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_input_margin',
			array(
				'label'      => esc_html__( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container input, {{WRAPPER}} .tk-cf7-container textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tk_input_focus',
			array(
				'label'     => esc_html__( 'Focus Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-text:focus, {{WRAPPER}} .tk-cf7-container textarea.wpcf7-textarea:focus , {{WRAPPER}} .tk-cf7-container .wpcf7-file:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tk_input_focus_border_color',
			array(
				'label'     => esc_html__( 'Focus Line Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'tk_input_focus_border_animation' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}.tk-contact-form-anim-yes .wpcf7-span.is-focused::after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'input_button_shadow',
				'selector' => '{{WRAPPER}} .tk-cf7-container input.wpcf7-text, {{WRAPPER}} .tk-cf7-container textarea.wpcf7-textarea, {{WRAPPER}} .tk-cf7-container .wpcf7-file',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_contact_form_typography',
			array(
				'label' => esc_html__( 'Labels', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tk_heading_default',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Default Typography', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_contact_form_color',
			array(
				'label'     => esc_html__( 'Default Font Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container, {{WRAPPER}} .tk-cf7-container label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tk_contact_form_default_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-cf7-container',
			)
		);

		$this->add_control(
			'tk_heading_input',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Input Typography', 'taman-kit-pro' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'tk_contact_form_field_color',
			array(
				'label'     => esc_html__( 'Input Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-text, {{WRAPPER}} .tk-cf7-container textarea.wpcf7-textarea' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tk_contact_form_field_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-cf7-container input.wpcf7-text, {{WRAPPER}} .tk-cf7-container textarea.wpcf7-textarea',
			)
		);

		$this->add_control(
			'tk_contact_form_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tk-cf7-container ::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tk-cf7-container ::-ms-input-placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_contact_form_submit_button_styles',
			array(
				'label' => esc_html__( 'Button', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_title_tk_btn_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-cf7-container input.wpcf7-submit',
			)
		);

		$this->add_responsive_control(
			'section_title_tk_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tk_button_tabs' );

		$this->start_controls_tab( 'normal', array( 'label' => esc_html__( 'Normal', 'taman-kit-pro' ) ) );

		$this->add_control(
			'tk_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tk_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tk_btn_border',
				'selector' => '{{WRAPPER}} .tk-cf7-container input.wpcf7-submit',
			)
		);

		$this->add_responsive_control(
			'tk_btn_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit' => 'border-radius: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tk_hover',
			array(
				'label' => esc_html__( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tk_button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tk_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tk-cf7-container input.wpcf7-submit:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .tk-cf7-container input.wpcf7-submit',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Get wpcf_forms
	 */
	protected function get_wpcf_forms() {

		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			return array();
		}

		$forms = \WPCF7_ContactForm::find(
			array(
				'orderby' => 'title',
				'order'   => 'ASC',
			)
		);

		if ( empty( $forms ) ) {
			return array();
		}

		$result = array();

		foreach ( $forms as $item ) {
			$key            = sprintf( '%1$s::%2$s', $item->id(), $item->title() );
			$result[ $key ] = $item->title();
		}

		return $result;
	}

	/**
	 * Render Contact Form 7 widget output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		if ( ! empty( $settings['tk_wpcf7_form'] ) ) {

			$this->add_render_attribute( 'container', 'class', 'tk-cf7-container' );

			?>

			<div <?php echo $this->get_render_attribute_string( 'container' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php echo do_shortcode( '[contact-form-7 id="' . $settings['tk_wpcf7_form'] . '" ]' ); ?>
			</div>

			<?php
		}

	}
}
