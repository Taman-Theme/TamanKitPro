<?php
/**
 * Elementor Fancy Heading Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 * @package Taman Kit Pro.
 */

namespace TamanKitPro\Widgets;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
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
 * Fancy Heading class
 */
class FancyHeading extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve fancyheading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-fancyheading';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve fancyheading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Fancy Heading', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve fancyheading widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-font';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the fancyheading widget belongs to.
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
	 * Register fancyheading widget controls.
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
			 * Content Tab: Fancy Heading
			 */
		$this->start_controls_section(
			'section_heading',
			array(
				'label' => __( 'Fancy Heading', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'heading_text',
			array(
				'label'       => __( 'Heading', 'taman-kit-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'rows'        => 2,
				'default'     => __( 'Add Your Heading Text Here', 'taman-kit-pro' ),
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
				'label_block' => true,
				'placeholder' => 'https://www.your-link.com',
			)
		);

		$this->add_control(
			'heading_html_tag',
			array(
				'label'       => __( 'HTML Tag', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => 'h2',
				'options'     => array(
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

		$this->add_responsive_control(
			'align',
			array(
				'label'       => __( 'Alignment', 'taman-kit-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'    => array(
						'title' => __( 'Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'taman-kit-pro' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
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
			 * Style Tab: First Part
			 */
		$this->start_controls_section(
			'heading_section_style',
			array(
				'label' => __( 'Fancy Heading', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'label'    => __( 'Typography', 'taman-kit-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tk-heading-text',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'heading_text_shadow',
				'selector' => '{{WRAPPER}} .tk-heading-text',
			)
		);

		$this->add_control(
			'heading_fill',
			array(
				'label'        => __( 'Fill', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'solid'    => __( 'Color', 'taman-kit-pro' ),
					'gradient' => __( 'Background', 'taman-kit-pro' ),
				),
				'default'      => 'solid',
				'prefix_class' => 'tk-heading-fill-',
				'separator'    => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'gradient',
				'types'     => array( 'gradient', 'classic' ),
				'selector'  => '{{WRAPPER}} .tk-heading-text',
				'default'   => 'gradient',
				'condition' => array(
					'heading_fill' => 'gradient',
				),
			)
		);

		$this->add_control(
			'heading_text_color',
			array(
				'label'     => __( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-heading-text' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'heading_fill' => 'solid',
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
	 * Render fancy heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'fancy-heading', 'class', 'tk-fancy-heading' );
		$this->add_inline_editing_attributes( 'heading_text', 'basic' );
		$this->add_render_attribute( 'heading_text', 'class', 'tk-heading-text' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'fancy-heading-link', $settings['link'] );
		}

		if ( $settings['heading_text'] ) {
			printf( '<%1$s %2$s>', $settings['heading_html_tag'], $this->get_render_attribute_string( 'fancy-heading' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( ! empty( $settings['link']['url'] ) ) {
				printf( '<a %1$s>', $this->get_render_attribute_string( 'fancy-heading-link' ) ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				printf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'heading_text' ), $this->parse_text_editor( $settings['heading_text'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( ! empty( $settings['link']['url'] ) ) {
				printf( '</a>' ); }
			printf( '</%1$s>', $settings['heading_html_tag'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Render fancy heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<{{{settings.heading_html_tag}}} class="tk-fancy-heading">
			<# if ( settings.link.url ) { #><a href="{{settings.link.url}}"><# } #>
				<#
				if ( settings.heading_text != '' ) {
					var heading_text = settings.heading_text;

					view.addRenderAttribute( 'heading_text', 'class', 'tk-heading-text' );

					view.addInlineEditingAttributes( 'heading_text' );

					var heading_text_html = '<span' + ' ' + view.getRenderAttributeString( 'heading_text' ) + '>' + heading_text + '</span>';

					print( heading_text_html );
				}
				#>
			<# if ( settings.link.url ) { #></a><# } #>
		</{{{settings.heading_html_tag}}}>
		<?php
	}

	/**
	 * Render fancy heading widget output in the editor.
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
