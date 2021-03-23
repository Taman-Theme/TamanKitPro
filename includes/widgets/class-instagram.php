<?php
/**
 * Elementor instagram Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

namespace TamanKitPro\Widgets;

// Elementor Classes.
use Elementor\Utils;
use Elementor\Scheme_Color;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;


/**
 * Instagram Widget
 */
class Instagram extends \Elementor\Widget_Base {
	/**
	 * Instagram Access token.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $insta_access_token = null;

	/**
	 * Instagram API URL.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $insta_api_url = 'https://www.instagram.com/';

	/**
	 * Official Instagram API URL.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $insta_official_api_url = 'https://graph.instagram.com/';

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

		wp_register_script(
			'ofi',
			TAMAN_KIT_PRO_URL . 'public/js/ofi.min.js',
			array(),
			\TamanKitProHelpers::taman_kit_pro_ver(),
			true
		);

	}


	/**
	 * Get style dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_style_depends() {
		return array();
	}

	/**
	 * Set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_script_depends() {
		return array(

			'ofi',
			'taman-kit-pro',

		);
	}
	/**
	 * Get widget name.
	 *
	 * Retrieve instagram widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk_instagram';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve instagram widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Instagram Grid', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve instagram widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-instagram-gallery';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the instagram widget belongs to.
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
	 * Register instagram feed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->register_controls();
	}


	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		/*
		*==================================================================================
		*
		*==================================== CONTENT TAB =================================
		*
		*==================================================================================
		*/

		/* Content Tab: Instagram Account */
		$this->register_content_instaaccount_controls();

		/* Content Tab: General Settings */
		$this->register_content_general_settings_controls();

		/* Content Tab: Help Docs */
		$this->register_content_help_docs();

		/* Content Tab: Upgrade Pro */
		$this->register_content_upgrade_pro();

		/* Style Tab: Layout */
		$this->register_style_layout_controls();

		/* Style Tab: Images */
		$this->register_style_images_controls();

		/* Style Tab: Content */
		$this->register_style_content_controls();

		/* Style Tab: Overlay */
		$this->register_style_overlay_controls();

		/* Style Tab: Feed Title */
		$this->register_style_feed_title_controls();

		/* Style Tab: Fraction */
		$this->register_style_fraction_controls();

	}

	/*
	*====================== Content Tab: Instagram Account ===========================
	*/

	/**
	 *  Register content insta account controls
	 */
	protected function register_content_instaaccount_controls() {
		$this->start_controls_section(
			'section_instaaccount',
			array(
				'label' => esc_html__( 'Instagram Account', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'insta_display',
			array(
				'label'   => esc_html__( 'Display', 'taman-kit-pro' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'feed',
			)
		);

		if ( ! $this->get_insta_global_access_token() ) {
			$this->add_control(
				'access_token_missing',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
						/* translators: draft saved date format, see http://php.net/date */
						esc_html__( 'The global Instagram access token is missing. You can add it %1$shere%2$s or use a custom one below.', 'taman-kit-pro' ),
						'<a target="_blank" href="#">',
						'</a>'
					),
					'content_classes' => 'tk-editor-info',
					'condition'       => array(
						'insta_display' => 'feed',

					),
				)
			);
		}

		$this->add_control(
			'access_token',
			array(
				'label'       => esc_html__( 'Custom Access Token', 'taman-kit-pro' ),
				'description' => esc_html__( 'Overrides global Instagram access token', 'taman-kit-pro' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'condition'   => array(
					'insta_display' => 'feed',

				),
			)
		);

		$this->add_control(
			'insta_hashtag',
			array(
				'label'       => esc_html__( 'Hashtag', 'taman-kit-pro' ),
				'description' => esc_html__( 'Enter without the # symbol', 'taman-kit-pro' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'condition'   => array(
					'insta_display' => 'tags',

				),
			)
		);

		$this->add_control(
			'cache_timeout',
			array(
				'label'   => esc_html__( 'Cache Timeout', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hour',
				'options' => array(
					'none'   => esc_html__( 'None', 'taman-kit-pro' ),
					'minute' => esc_html__( 'Minute', 'taman-kit-pro' ),
					'hour'   => esc_html__( 'Hour', 'taman-kit-pro' ),
					'day'    => esc_html__( 'Day', 'taman-kit-pro' ),
					'week'   => esc_html__( 'Week', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_control(
			'images_count',
			array(
				'label'      => esc_html__( 'Images Count', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 5 ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
			)
		);

		$this->add_control(
			'resolution',
			array(
				'label'   => esc_html__( 'Image Resolution', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'thumbnail'           => esc_html__( 'Thumbnail (150x150)', 'taman-kit-pro' ),
					'low_resolution'      => esc_html__( 'Low Resolution (320x320)', 'taman-kit-pro' ),
					'standard_resolution' => esc_html__( 'Standard Resolution (640x640)', 'taman-kit-pro' ),
					'high'                => esc_html__( 'High Resolution (original)', 'taman-kit-pro' ),
				),
				'default' => 'low_resolution',
			)
		);

		$this->end_controls_section();
	}

	/*
	 * ====================== Content Tab: General Settings ===========================
	 */

	/**
	 * Register content general settings controls
	 */
	protected function register_content_general_settings_controls() {
		$this->start_controls_section(
			'section_general_settings',
			array(
				'label' => esc_html__( 'General Settings', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'feed_layout',
			array(
				'label'              => esc_html__( 'Layout', 'taman-kit-pro' ),
				'type'               => Controls_Manager::HIDDEN,
				'default'            => 'grid',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'grid_cols',
			array(
				'label'          => esc_html__( 'Grid Columns', 'taman-kit-pro' ),
				'type'           => Controls_Manager::SELECT,
				'label_block'    => false,
				'default'        => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'        => array(
					'2' => esc_html__( '2', 'taman-kit-pro' ),
					'3' => esc_html__( '3', 'taman-kit-pro' ),
					'4' => esc_html__( '4', 'taman-kit-pro' ),
					'5' => esc_html__( '5', 'taman-kit-pro' ),
					'6' => esc_html__( '6', 'taman-kit-pro' ),
				),

				'render_type'    => 'template',
				'condition'      => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_control(
			'insta_likes',
			array(
				'label'              => esc_html__( 'Likes', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => esc_html__( 'Show', 'taman-kit-pro' ),
				'label_off'          => esc_html__( 'Hide', 'taman-kit-pro' ),
				'return_value'       => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'insta_comments',
			array(
				'label'              => esc_html__( 'Comments', 'taman-kit-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => esc_html__( 'Show', 'taman-kit-pro' ),
				'label_off'          => esc_html__( 'Hide', 'taman-kit-pro' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'insta_caption',
			array(
				'label'        => esc_html__( 'Caption', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Show', 'taman-kit-pro' ),
				'label_off'    => esc_html__( 'Hide', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'insta_caption_length',
			array(
				'label'     => esc_html__( 'Caption Length', 'taman-kit-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => 30,
				'condition' => array(
					'insta_caption' => 'yes',
				),
			)
		);

		$this->add_control(
			'content_visibility',
			array(
				'label'      => esc_html__( 'Content Visibility', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'always',
				'options'    => array(
					'always' => esc_html__( 'Always', 'taman-kit-pro' ),
					'hover'  => esc_html__( 'On Hover', 'taman-kit-pro' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'insta_image_popup',
			array(
				'label'        => esc_html__( 'Lightbox', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'taman-kit-pro' ),
				'label_off'    => esc_html__( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'insta_image_link',
			array(
				'label'        => esc_html__( 'Image Link', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'taman-kit-pro' ),
				'label_off'    => esc_html__( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'insta_image_popup!' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_profile_link',
			array(
				'label'        => esc_html__( 'Show Link to Instagram Profile?', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'taman-kit-pro' ),
				'label_off'    => esc_html__( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'insta_link_title',
			array(
				'label'     => esc_html__( 'Link Title', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Follow Us @ Instagram', 'taman-kit-pro' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_profile_url',
			array(
				'label'       => esc_html__( 'Instagram Profile URL', 'taman-kit-pro' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_icon',
			array(
				'label'            => esc_html__( 'Title Icon', 'taman-kit-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'insta_title_icon',
				'recommended'      => array(
					'fa-brands'  => array(
						'instagram',
					),
					'fa-regular' => array(
						'user',
						'user-circle',
					),
					'fa-solid'   => array(
						'user',
						'user-circle',
						'user-check',
						'user-graduate',
						'user-md',
						'user-plus',
						'user-tie',
					),
				),
				'default'          => array(
					'value'   => 'fab fa-instagram',
					'library' => 'fa-brands',
				),
				'condition'        => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_title_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'before_title' => esc_html__( 'Before Title', 'taman-kit-pro' ),
					'after_title'  => esc_html__( 'After Title', 'taman-kit-pro' ),
				),
				'default'   => 'before_title',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/*
	*====================== Content Tab: Help Docs ===========================
	*/

	/**
	 * Content Tab: Help Docs
	 *
	 * @since 1.4.8
	 * @access protected
	 */
	protected function register_content_help_docs() {

		$help_docs = '#';

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 1.4.8
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				array(
					'label' => esc_html__( 'Help Docs', 'taman-kit-pro' ),
				)
			);

			$hd_counter = 1;
			foreach ( $help_docs as $hd_title => $hd_link ) {
				$this->add_control(
					'help_doc_' . $hd_counter,
					array(
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf( '%1$s ' . $hd_title . ' %2$s', '<a href="' . $hd_link . '" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => 'tk-editor-doc-links',
					)
				);

				$hd_counter++;
			}

			$this->end_controls_section();
		}
	}

	/*
	*====================== Content Tab: Upgrade Pro ===========================
	*/

	/**
	 * Content Tab: Upgrade Pro
	 *
	 * @since 1.4.8
	 * @access protected
	 */
	protected function register_content_upgrade_pro() {
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
		*==================================== STYLE TAB =================================
		*
		*==================================================================================
		*/

	/**
	 * Style Tab: Layout
	 */
	protected function register_style_layout_controls() {
		$this->start_controls_section(
			'section_layout_style',
			array(
				'label'     => esc_html__( 'Layout', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);$this->add_responsive_control(
			'columns_gap',
			array(
				'label'          => esc_html__( 'Columns Gap', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-instafeed-grid .tk-feed-item' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .tk-instafeed-grid' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
				),
				'render_type'    => 'template',
				'condition'      => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_responsive_control(
			'rows_gap',
			array(
				'label'          => esc_html__( 'Rows Gap', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-instafeed-grid .tk-feed-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'render_type'    => 'template',
				'condition'      => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Images
	 */
	protected function register_style_images_controls() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Images', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_image_style' );

		$this->start_controls_tab(
			'tab_image_normal',
			array(
				'label' => esc_html__( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'insta_image_grayscale',
			array(
				'label'        => esc_html__( 'Grayscale Image', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'taman-kit-pro' ),
				'label_off'    => esc_html__( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'images_border',
				'label'       => esc_html__( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-instagram-feed .tk-if-img',
			)
		);

		$this->add_control(
			'images_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-if-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_image_hover',
			array(
				'label' => esc_html__( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'insta_image_grayscale_hover',
			array(
				'label'        => esc_html__( 'Grayscale Image', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'taman-kit-pro' ),
				'label_off'    => esc_html__( 'No', 'taman-kit-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'images_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-feed-item:hover .tk-if-img' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Content
	 */
	protected function register_style_content_controls() {
		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'taman-kit-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'content_typography',
				'label'      => esc_html__( 'Typography', 'taman-kit-pro' ),
				'selector'   => '{{WRAPPER}} .tk-feed-item .tk-overlay-container',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'likes_comments_color',
			array(
				'label'      => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-feed-item .tk-overlay-container' => 'color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'content_vertical_align',
			array(
				'label'                => esc_html__( 'Vertical Align', 'taman-kit-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'toggle'               => false,
				'default'              => 'middle',
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .tk-overlay-container' => 'justify-content: {{VALUE}};',
				),
				'conditions'           => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'content_horizontal_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'taman-kit-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'toggle'               => false,
				'default'              => 'center',
				'options'              => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .tk-overlay-container' => 'align-items: {{VALUE}};',
				),
				'conditions'           => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'text_align',
			array(
				'label'      => esc_html__( 'Text Align', 'taman-kit-pro' ),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'taman-kit-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'    => 'center',
				'selectors'  => array(
					'{{WRAPPER}} .tk-overlay-container' => 'text-align: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-overlay-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'icons_heading',
			array(
				'label'      => esc_html__( 'Icons', 'taman-kit-pro' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2.5,
						'step' => 0.1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-feed-item .tk-if-icon' => 'font-size: {{SIZE}}em;',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Overlay
	 */
	protected function register_style_overlay_controls() {
		$this->start_controls_section(
			'section_overlay_style',
			array(
				'label' => esc_html__( 'Overlay', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_blend_mode',
			array(
				'label'     => esc_html__( 'Blend Mode', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'normal',
				'options'   => array(
					'normal'      => esc_html__( 'Normal', 'taman-kit-pro' ),
					'multiply'    => esc_html__( 'Multiply', 'taman-kit-pro' ),
					'screen'      => esc_html__( 'Screen', 'taman-kit-pro' ),
					'overlay'     => esc_html__( 'Overlay', 'taman-kit-pro' ),
					'darken'      => esc_html__( 'Darken', 'taman-kit-pro' ),
					'lighten'     => esc_html__( 'Lighten', 'taman-kit-pro' ),
					'color-dodge' => esc_html__( 'Color Dodge', 'taman-kit-pro' ),
					'color'       => esc_html__( 'Color', 'taman-kit-pro' ),
					'hue'         => esc_html__( 'Hue', 'taman-kit-pro' ),
					'hard-light'  => esc_html__( 'Hard Light', 'taman-kit-pro' ),
					'soft-light'  => esc_html__( 'Soft Light', 'taman-kit-pro' ),
					'difference'  => esc_html__( 'Difference', 'taman-kit-pro' ),
					'exclusion'   => esc_html__( 'Exclusion', 'taman-kit-pro' ),
					'saturation'  => esc_html__( 'Saturation', 'taman-kit-pro' ),
					'luminosity'  => esc_html__( 'Luminosity', 'taman-kit-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-overlay-container' => 'mix-blend-mode: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_overlay_style' );

		$this->start_controls_tab(
			'tab_overlay_normal',
			array(
				'label' => esc_html__( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'image_overlay_normal',
				'label'    => esc_html__( 'Overlay', 'taman-kit-pro' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array(
					'image',
				),
				'selector' => '{{WRAPPER}} .tk-instagram-feed .tk-overlay-container',
			)
		);

		$this->add_control(
			'image_overlay_opacity_normal',
			array(
				'label'      => esc_html__( 'Overlay Opacity', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-overlay-container' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover',
			array(
				'label' => esc_html__( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'image_overlay_hover',
				'label'    => esc_html__( 'Overlay', 'taman-kit-pro' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array(
					'image',
				),
				'selector' => '{{WRAPPER}} .tk-instagram-feed .tk-feed-item:hover .tk-overlay-container',
			)
		);

		$this->add_control(
			'image_overlay_opacity_hover',
			array(
				'label'      => esc_html__( 'Overlay Opacity', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-feed-item:hover .tk-overlay-container' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Feed Title
	 */
	protected function register_style_feed_title_controls() {
		$this->start_controls_section(
			'section_feed_title_style',
			array(
				'label'     => esc_html__( 'Feed Title', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'feed_title_position',
			array(
				'label'        => esc_html__( 'Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'middle',
				'options'      => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'taman-kit-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'prefix_class' => 'tk-insta-title-',
				'condition'    => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'feed_title_typography',
				'label'     => esc_html__( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .tk-instagram-feed-title',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			array(
				'label'     => esc_html__( 'Normal', 'taman-kit-pro' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color_normal',
			array(
				'label'     => esc_html__( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed-title-wrap a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tk-instagram-feed-title-wrap .tk-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed-title-wrap' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'title_border_normal',
				'label'       => esc_html__( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-instagram-feed-title-wrap',
			)
		);

		$this->add_control(
			'title_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed-title-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_hover',
			array(
				'label'     => esc_html__( 'Hover', 'taman-kit-pro' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed-title-wrap a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tk-instagram-feed-title-wrap a:hover .tk-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed-title-wrap:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'title_border_hover',
				'label'       => esc_html__( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-instagram-feed-title-wrap:hover',
			)
		);

		$this->add_control(
			'title_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed-title-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'insta_profile_link' => 'yes',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'title_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'taman-kit-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 4 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-icon-before_title' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tk-instagram-feed .tk-icon-after_title' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Arrows
	 */
	protected function register_style_arrows_controls() {
		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'     => esc_html__( 'Arrows', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'arrows'      => 'yes',
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'arrow',
			array(
				'label'       => esc_html__( 'Choose Arrow', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => 'fa fa-angle-right',
				'options'     => array(
					'fa fa-angle-right'          => esc_html__( 'Angle', 'taman-kit-pro' ),
					'fa fa-angle-double-right'   => esc_html__( 'Double Angle', 'taman-kit-pro' ),
					'fa fa-chevron-right'        => esc_html__( 'Chevron', 'taman-kit-pro' ),
					'fa fa-chevron-circle-right' => esc_html__( 'Chevron Circle', 'taman-kit-pro' ),
					'fa fa-arrow-right'          => esc_html__( 'Arrow', 'taman-kit-pro' ),
					'fa fa-long-arrow-right'     => esc_html__( 'Long Arrow', 'taman-kit-pro' ),
					'fa fa-caret-right'          => esc_html__( 'Caret', 'taman-kit-pro' ),
					'fa fa-caret-square-o-right' => esc_html__( 'Caret Square', 'taman-kit-pro' ),
					'fa fa-arrow-circle-right'   => esc_html__( 'Arrow Circle', 'taman-kit-pro' ),
					'fa fa-arrow-circle-o-right' => esc_html__( 'Arrow Circle O', 'taman-kit-pro' ),
					'fa fa-toggle-right'         => esc_html__( 'Toggle', 'taman-kit-pro' ),
					'fa fa-hand-o-right'         => esc_html__( 'Hand', 'taman-kit-pro' ),
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => esc_html__( 'Arrows Size', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'left_arrow_position',
			array(
				'label'      => esc_html__( 'Align Left Arrow', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'right_arrow_position',
			array(
				'label'      => esc_html__( 'Align Right Arrow', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			array(
				'label' => esc_html__( 'Normal', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'arrows_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'arrows_border_normal',
				'label'       => esc_html__( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-instagram-feed .tk-swiper-button',
			)
		);

		$this->add_control(
			'arrows_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			array(
				'label' => esc_html__( 'Hover', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'arrows_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			array(
				'label'      => esc_html__( 'Padding', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .tk-swiper-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Pagination: Dots
	 */
	protected function register_style_dots_controls() {
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'     => esc_html__( 'Pagination: Dots', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_position',
			array(
				'label'        => esc_html__( 'Position', 'taman-kit-pro' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'inside'  => esc_html__( 'Inside', 'taman-kit-pro' ),
					'outside' => esc_html__( 'Outside', 'taman-kit-pro' ),
				),
				'default'      => 'outside',
				'prefix_class' => 'swiper-container-dots-',
				'condition'    => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_size',
			array(
				'label'      => esc_html__( 'Size', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'taman-kit-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			array(
				'label'     => esc_html__( 'Normal', 'taman-kit-pro' ),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullet' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'active_dot_color_normal',
			array(
				'label'     => esc_html__( 'Active Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'dots_border_normal',
				'label'       => esc_html__( 'Border', 'taman-kit-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullet',
				'condition'   => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_margin',
			array(
				'label'              => esc_html__( 'Margin', 'taman-kit-pro' ),
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
					'{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			array(
				'label'     => esc_html__( 'Hover', 'taman-kit-pro' ),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tk-instagram-feed .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Pagination: Fraction
	 * -------------------------------------------------
	 */
	protected function register_style_fraction_controls() {
		$this->start_controls_section(
			'section_fraction_style',
			array(
				'label'     => esc_html__( 'Pagination: Fraction', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->add_control(
			'fraction_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'fraction_typography',
				'label'     => esc_html__( 'Typography', 'taman-kit-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .swiper-pagination-fraction',
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get Instagram access token.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_insta_access_token() {
		$settings = $this->get_settings_for_display();

		if ( ! $this->insta_access_token ) {
			$custom_access_token = $settings['access_token'];

			if ( '' !== trim( $custom_access_token ) ) {
				$this->insta_access_token = $custom_access_token;
			} else {
				$this->insta_access_token = $this->get_insta_global_access_token();
			}
		}

		return $this->insta_access_token;
	}

	/**
	 * Get Instagram access token from PowerPack options.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_insta_global_access_token() {
		return '';
	}

	/**
	 * Retrieve a URL for own photos.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_feed_endpoint() {
		return $this->insta_official_api_url . 'me/media/';
	}

	/**
	 * Retrieve a URL for photos by hashtag.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_tags_endpoint() {
		return $this->insta_api_url . 'explore/tags/%s/';
	}

	/**
	 * Undocumented function
	 */
	public function get_user_endpoint() {
		return $this->insta_official_api_url . 'me/';
	}

	/**
	 * Undocumented function
	 */
	public function get_user_media_endpoint() {
		return $this->insta_official_api_url . '%s/media/';
	}

	/**
	 * Undocumented function
	 */
	public function get_media_endpoint() {
		return $this->insta_official_api_url . '%s/';
	}

	/**
	 * Undocumented function
	 */
	public function get_user_url() {
		$url = $this->get_user_endpoint();
		$url = add_query_arg(
			array(
				'access_token' => $this->get_insta_access_token(),
			),
			$url
		);

		return $url;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $user_id .
	 */
	public function get_user_media_url( $user_id ) {
		$url = sprintf( $this->get_user_media_endpoint(), $user_id );
		$url = add_query_arg(
			array(
				'access_token' => $this->get_insta_access_token(),
				'fields'       => 'id,like_count',
			),
			$url
		);

		return $url;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $media_id .
	 */
	public function get_media_url( $media_id ) {
		$url = sprintf( $this->get_media_endpoint(), $media_id );
		$url = add_query_arg(
			array(
				'access_token' => $this->get_insta_access_token(),
				'fields'       => 'id,media_type,media_url,timestamp,like_count',
			),
			$url
		);

		return $url;
	}

	/**
	 * Undocumented function
	 */
	public function get_insta_user_id() {
		$result = $this->get_insta_remote( $this->get_user_url() );
		return $result;
	}


	/**
	 * User id
	 *
	 * @param [type] $user_id .
	 */
	public function get_insta_user_media( $user_id ) {
		$result = $this->get_insta_remote( $this->get_user_media_url( $user_id ) );

		return $result;
	}

	/**
	 * Undocumented function
	 */
	public function get_insta_media( $media_id ) {
		$result = $this->get_insta_remote( $this->get_media_url( $media_id ) );

		return $result;
	}


	/**
	 * Add a placeholder for the widget in the elementor editor
	 *
	 * @since 1.0.0
	 * @param array $args .
	 * @access public
	 */
	public function render_editor_placeholder( $args = array() ) {

		if ( ! \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			return;
		}

		$defaults = array(
			'title' => $this->get_title(),
			'body'  => __( 'This is a placeholder for this widget and is visible only in the editor.', 'taman-kit-pro' ),
		);

		$args = wp_parse_args( $args, $defaults );

		$this->add_render_attribute(
			array(
				'placeholder'         => array(
					'class' => 'uk-placeholder uk-text-center uk-background-muted',
				),
				'placeholder-title'   => array(
					'class' => 'tk-editor-placeholder-title',
				),
				'placeholder-content' => array(
					'class' => 'tk-editor-placeholder-content',
				),
			)
		);

		?><div <?php echo $this->get_render_attribute_string( 'placeholder' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<h4 <?php echo $this->get_render_attribute_string( 'placeholder-title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php echo esc_html( $args['title'] ); ?>
			</h4>
			<div <?php echo $this->get_render_attribute_string( 'placeholder-content' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php echo esc_html( $args['body'] ); ?>
			</div>
		</div>
		<?php
	}



	/**
	 * Retrieve a grab URL.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_fetch_url() {
		$settings = $this->get_settings();

		if ( 'tags' === $settings['insta_display'] ) {
			$url = sprintf( $this->get_tags_endpoint(), $settings['insta_hashtag'] );
			$url = add_query_arg( array( '__a' => 1 ), $url );

		} elseif ( 'feed' === $settings['insta_display'] ) {
			$url = $this->get_feed_endpoint();
			$url = add_query_arg(
				array(
					'fields'       => 'id,media_type,media_url,thumbnail_url,permalink,caption,likes_count,likes',
					'access_token' => $this->get_insta_access_token(),
				),
				$url
			);
		}

		return $url;
	}

	/**
	 * Get thumbnail data from response data
	 *
	 * @param array $post .
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_insta_feed_thumbnail_data( $post ) {
		$thumbnail = array(
			'thumbnail' => false,
			'low'       => false,
			'standard'  => false,
			'high'      => false,
		);

		if ( ! empty( $post['images'] ) && is_array( $post['images'] ) ) {
			$data = $post['images'];

			$thumbnail['thumbnail'] = array(
				'src'           => $data['thumbnail']['url'],
				'config_width'  => $data['thumbnail']['width'],
				'config_height' => $data['thumbnail']['height'],
			);

			$thumbnail['low'] = array(
				'src'           => $data['low_resolution']['url'],
				'config_width'  => $data['low_resolution']['width'],
				'config_height' => $data['low_resolution']['height'],
			);

			$thumbnail['standard'] = array(
				'src'           => $data['standard_resolution']['url'],
				'config_width'  => $data['standard_resolution']['width'],
				'config_height' => $data['standard_resolution']['height'],
			);

			$thumbnail['high'] = $thumbnail['standard'];
		}

		return $thumbnail;
	}



	/**
	 * Get data from response
	 *
	 * @since  1.0.0
	 * @param [type] $response .
	 * @return void
	 */
	public function get_insta_feed_response_data( $response ) {
		$settings = $this->get_settings();

		if ( ! array_key_exists( 'data', $response ) ) { // Avoid PHP notices.
			return;
		}

		$response_posts = $response['data'];

		if ( empty( $response_posts ) ) {
			return array();
		}

		$return_data  = array();
		$images_count = ! empty( $settings['images_count']['size'] ) ? $settings['images_count']['size'] : 5;
		$posts        = array_slice( $response_posts, 0, $images_count, true );

		foreach ( $posts as $post ) {
			$_post = array();

			$_post['id']       = $post['id'];
			$_post['link']     = $post['permalink'];
			$_post['caption']  = '';
			$_post['image']    = 'VIDEO' === $post['media_type'] ? $post['thumbnail_url'] : $post['media_url'];
			$_post['comments'] = ! empty( $post['comments_count'] ) ? $post['comments_count'] : 0;
			$_post['likes']    = ! empty( $post['likes_count'] ) ? $post['likes_count'] : 0;

			$_post['thumbnail'] = $this->get_insta_feed_thumbnail_data( $post );

			if ( ! empty( $post['caption'] ) ) {
				$_post['caption'] = wp_html_excerpt( $post['caption'], $this->get_settings( 'insta_caption_length' ), '&hellip;' );
			}

			$return_data[] = $_post;
		}

		return $return_data;
	}

	/**
	 * Get data from response
	 *
	 * @param array $response .
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_insta_tags_response_data( $response ) {
		$settings       = $this->get_settings();
		$response_posts = $response['graphql']['hashtag']['edge_hashtag_to_media']['edges'];

		$insta_caption_length = ( $settings['insta_caption_length'] ) ? $settings['insta_caption_length'] : 30;

		if ( empty( $response_posts ) ) {
			$response_posts = $response['graphql']['hashtag']['edge_hashtag_to_top_posts']['edges'];
		}

		$return_data  = array();
		$images_count = ! empty( $settings['images_count']['size'] ) ? $settings['images_count']['size'] : 5;
		$posts        = array_slice( $response_posts, 0, $images_count, true );

		foreach ( $posts as $post ) {
			$_post = array();

			$_post['link']      = sprintf( $this->insta_api_url . 'p/%s/', $post['node']['shortcode'] );
			$_post['caption']   = '';
			$_post['comments']  = $post['node']['edge_media_to_comment']['count'];
			$_post['likes']     = $post['node']['edge_liked_by']['count'];
			$_post['thumbnail'] = $this->get_insta_tags_thumbnail_data( $post );

			if ( isset( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
				$_post['caption'] = wp_html_excerpt( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'], $insta_caption_length, '&hellip;' );
			}

			$return_data[] = $_post;
		}

		return $return_data;
	}

	/**
	 * Generate thumbnail resources.
	 *
	 * @since 1.0.0
	 * @param array $post data .
	 *
	 * @return array
	 */
	public function get_insta_tags_thumbnail_data( $post ) {
		$post = $post['node'];

		$thumbnail = array(
			'thumbnail' => false,
			'low'       => false,
			'standard'  => false,
			'high'      => false,
		);

		if ( is_array( $post['thumbnail_resources'] ) && ! empty( $post['thumbnail_resources'] ) ) {
			foreach ( $post['thumbnail_resources'] as $key => $resources_data ) {

				if ( 150 === $resources_data['config_width'] ) {
					$thumbnail['thumbnail'] = $resources_data;
					continue;
				}

				if ( 320 === $resources_data['config_width'] ) {
					$thumbnail['low'] = $resources_data;
					continue;
				}

				if ( 640 === $resources_data['config_width'] ) {
					$thumbnail['standard'] = $resources_data;
					continue;
				}
			}
		}

		if ( ! empty( $post['display_url'] ) ) {
			$thumbnail['high'] = array(
				'src'           => $post['display_url'],
				'config_width'  => $post['dimensions']['width'],
				'config_height' => $post['dimensions']['height'],
			);
		}

		return $thumbnail;
	}

	/**
	 * Get Insta Thumbnail Image URL
	 *
	 * @since  1.0.0
	 * @return string   The url of the instagram post image
	 */
	protected function get_insta_image_size() {
		$settings = $this->get_settings();

		$size = $settings['resolution'];

		switch ( $size ) {
			case 'thumbnail':
				return 'thumbnail';
			case 'low_resolution':
				return 'low';
			case 'standard_resolution':
				return 'standard';
			default:
				return 'low';
		}
	}

	/**
	 * Retrieve response from API
	 *
	 * @since  1.0.0
	 * @param [type] $url .
	 * @return  array|WP_Error
	 */
	public function get_insta_remote( $url ) {
		$response = wp_remote_get(
			$url,
			array(
				'timeout'   => 60,
				'sslverify' => false,
			)
		);

		$response_code = wp_remote_retrieve_response_code( $response );
		$result        = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $response_code ) {

			$message = is_array( $result ) && isset( $result['error']['message'] ) ?

			$this->render_editor_placeholder(
				array(
					'title' => $result['error']['message'],
				)
			)

			: $this->render_editor_placeholder(
				array(
					'title' => esc_html__( 'No posts found', 'taman-kit-pro' ),
				)
			);

			return new \WP_Error( $response_code, $message );
		}

		if ( ! is_array( $result ) ) {
			return new \WP_Error( 'error', esc_html__( 'Data Error', 'taman-kit-pro' ) );
		}

		return $result;
	}

	/**
	 * Sanitize endpoint.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function sanitize_endpoint() {
		$settings = $this->get_settings();

		return in_array( $settings['insta_display'], array( 'feed', 'tags' ) ) ? $settings['insta_display'] : 'feed';
	}

	/**
	 * Get transient key.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_transient_key() {
		$settings = $this->get_settings();

		$endpoint             = $this->sanitize_endpoint();
		$target               = ( 'tags' === $endpoint ) ? sanitize_text_field( $settings['insta_hashtag'] ) : 'user';
		$insta_caption_length = ( $settings['insta_caption_length'] ) ? $settings['insta_caption_length'] : 30;
		$images_count         = $settings['images_count']['size'];

		return sprintf(
			'tke_instagram_%s_%s_posts_count_%s_caption_%s',
			$endpoint,
			$target,
			$images_count,
			$insta_caption_length
		);
	}

	/**
	 * Render Instagram profile link.
	 *
	 * @since  1.0.0
	 */
	public function get_insta_profile_link() {
		$settings = $this->get_settings_for_display();

		if ( ! isset( $settings['insta_title_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['insta_title_icon'] = 'fa fa-instagram';
		}

		$has_icon = ! empty( $settings['insta_title_icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['insta_title_icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $settings['title_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['title_icon'] );
		$is_new   = ! isset( $settings['insta_title_icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( 'title-icon', 'class', 'tk-icon tk-icon-' . $settings['insta_title_icon_position'] );

		if ( 'yes' === $settings['insta_profile_link'] && $settings['insta_link_title'] ) {
			?>
			<span class="tk-instagram-feed-title-wrap">
				<a <?php echo $this->get_render_attribute_string( 'instagram-profile-link' ); ?>>
					<span class="tk-instagram-feed-title">
						<?php if ( 'before_title' === $settings['insta_title_icon_position'] && $has_icon ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $settings['title_icon'], array( 'aria-hidden' => 'true' ) );
							} elseif ( ! empty( $settings['insta_title_icon'] ) ) {
								?>
								<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
								<?php
							}
							?>
						</span>
						<?php } ?>

						<?php echo esc_attr( $settings['insta_link_title'] ); ?>

						<?php if ( 'after_title' === $settings['insta_title_icon_position'] && $has_icon ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $settings['title_icon'], array( 'aria-hidden' => 'true' ) );
							} elseif ( ! empty( $settings['insta_title_icon'] ) ) {
								?>
								<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
								<?php
							}
							?>
						</span>
						<?php } ?>
					</span>
				</a>
			</span>
			<?php
		}
	}

	/**
	 * Retrieve Instagram posts.
	 *
	 * @since  1.0.0
	 * @param  array $settings .
	 * @return array
	 */
	public function get_insta_posts( $settings ) {
		$settings = $this->get_settings();

		$transient_key = md5( $this->get_transient_key() );

		$data = get_transient( $transient_key );

		if ( ! empty( $data ) && 1 !== $settings['cache_timeout'] && array_key_exists( 'thumbnail_resources', $data[0] ) ) {
			return $data;
		}

		$response = $this->get_insta_remote( $this->get_fetch_url() );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$data = ( 'tags' === $settings['insta_display'] ) ? $this->get_insta_tags_response_data( $response ) : $this->get_insta_feed_response_data( $response );

		if ( empty( $data ) ) {
			return array();
		}

		set_transient( $transient_key, $data, $settings['cache_timeout'] );

		return $data;
	}
	/**
	 * Render load more button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  2.2.6
	 * @access protected
	 */
	protected function render_api_images() {
		$settings = $this->get_settings();

		$gallery = $this->get_insta_posts( $settings );

		if ( empty( $gallery ) || is_wp_error( $gallery ) ) {
			$message = is_wp_error( $gallery ) ? $gallery->get_error_message() : esc_html__( 'No Posts Found', 'taman-kit-pro' );

			echo $message;
			return;
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'insta-feed-wrap' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div <?php echo $this->get_render_attribute_string( 'insta-feed-inner' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<div <?php echo $this->get_render_attribute_string( 'insta-feed-container' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php $this->get_insta_profile_link(); ?>
					<div <?php echo $this->get_render_attribute_string( 'insta-feed' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php
						foreach ( $gallery as $index => $item ) {
							$item_key = $this->get_repeater_setting_key( 'item', 'insta_images', $index );
							$this->add_render_attribute( $item_key, 'class', 'tk-feed-item' );

							?>
							<div <?php echo $this->get_render_attribute_string( $item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<div class="tk-feed-item-inner">
								<?php $this->render_image_thumbnail( $item, $index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		if ( 'carousel' === $settings['feed_layout'] ) {
			$layout = 'carousel';
		} else {
			$layout = 'grid';
		}

		$this->add_render_attribute(
			'insta-feed-wrap',
			'class',
			array(
				'tk-instagram-feed',
				'clearfix',
				'tk-instagram-feed-' . $layout,
				'tk-instagram-feed-' . $settings['content_visibility'],
			)
		);

		/*
		*==================================================================================
		*
		*==================================== Render CONTENT =================================
		*
		*===============================[Grid]===================================
		*/

		if ( ( 'grid' === $settings['feed_layout'] ) && $settings['grid_cols'] ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'uk-grid tk-instagram-feed-grid-' . $settings['grid_cols'] );
		}

		if ( 'grid' === $settings['feed_layout'] ) {

			$this->add_render_attribute( 'insta-feed-uk-grid', 'uk-grid', '' );

			$this->add_render_attribute(
				'insta-feed',
				array(
					'id'              => 'tk-instafeed-' . esc_attr( $this->get_id() ),
					'class'           => 'tk-instafeed-grid uk-grid-column-collapse uk-grid-row-collapse  uk-grid uk-child-width-1-' . $settings['grid_cols'] . '@l uk-child-width-1-3@m uk-child-width-1-2',
					'uk-height-match' => 'target: img',
				)
			);

		}

		/**
		 * -----------------------[ Options ]------------------------------------
		 */
		if ( 'yes' === $settings['insta_image_grayscale'] ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'tk-instagram-feed-gray' );
		}

		if ( 'yes' === $settings['insta_image_grayscale_hover'] ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'tk-instagram-feed-hover-gray' );
		}

		$this->add_render_attribute( 'insta-feed-container', 'class', 'tk-instafeed' );

		$this->add_render_attribute( 'insta-feed-inner', 'class', 'tk-insta-feed-inner' );

		/*
		*==================================================================================
		*
		*=========================== Render CONTENT =================================
		*
		*==============================================================================
		*/

		$the_seting_api = 'yes';

		if ( ! empty( $settings['insta_profile_url']['url'] ) ) {
			$this->add_link_attributes( 'instagram-profile-link', $settings['insta_profile_url'] );
		}

		$tk_widget_options = array(
			'user_id'      => '',
			'access_token' => ! empty( $settings['access_token'] ) ? $settings['access_token'] : '',
			'images_count' => ! empty( $settings['images_count']['size'] ) ? $settings['images_count']['size'] : 5,
			'target'       => 'tk-instafeed-' . esc_attr( $this->get_id() ),
			'resolution'   => ! empty( $settings['resolution'] ) ? $settings['resolution'] : '',
			'popup'        => ( 'yes' === $settings['insta_image_popup'] ) ? '1' : '0',
			'img_link'     => ( 'yes' !== $settings['insta_image_popup'] && 'yes' === $settings['insta_image_link'] ) ? '1' : '0',
		);

		if ( 'yes' === $the_seting_api ) {
			$this->render_api_images();
		} else {
			?>
			<div <?php echo $this->get_render_attribute_string( 'insta-feed-wrap' ); ?> data-settings='<?php echo wp_json_encode( $tk_widget_options ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>'>

				<div <?php echo $this->get_render_attribute_string( 'insta-feed-inner' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

					<div <?php echo $this->get_render_attribute_string( 'insta-feed-container' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

						<?php $this->get_insta_profile_link(); ?>

						<div <?php echo $this->get_render_attribute_string( 'insta-feed' ) . ' ' . $this->get_render_attribute_string( 'insta-feed-uk-grid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>

						<?php
							$edit_mode = \Elementor\Plugin::instance()->editor->is_edit_mode();
						if ( $edit_mode ) {
							if ( 'yes' === $the_seting_api ) {
								if ( '' === $settings['access_token'] ) {
									$placeholder = sprintf( 'Click here to edit the "%1$s" settings and enter Instagram account details.', esc_attr( $this->get_title() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

									echo $this->render_editor_placeholder( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										array(
											'title' => esc_html__( 'Instagram Feed not displayed!', 'taman-kit-pro' ),
											'body'  => $placeholder,
										)
									);
								}
							}
						}
						?>
					</div>

				</div>
			</div>
			<?php
		}
	}

	/**
	 * Render Image Thumbnail
	 *
	 * @since  1.0.0
	 *
	 * @param [type] $item .
	 * @param [type] $index .
	 * @return void
	 */ 
	protected function render_image_thumbnail( $item, $index ) {
		$settings        = $this->get_settings();
		$thumbnail_url   = $this->get_insta_image_url( $item, $this->get_insta_image_size() );
		$thumbnail_alt   = $item['caption'];
		$thumbnail_title = $item['caption'];
		$likes           = $item['likes'];
		$comments        = $item['comments'];
		$image_key       = $this->get_repeater_setting_key( 'image', 'insta', $index );
		$link_key        = $this->get_repeater_setting_key( 'link', 'image', $index );
		$item_link       = '';

		$this->add_render_attribute( $image_key, 'src', $thumbnail_url );

		if ( '' !== $thumbnail_alt ) {
			$this->add_render_attribute( $image_key, 'alt', $thumbnail_alt );
		}

		if ( '' !== $thumbnail_title ) {
			$this->add_render_attribute( $image_key, 'title', $thumbnail_title );
		}

		if ( 'yes' === $settings['insta_image_popup'] ) {

			$item_link = $this->get_insta_image_url( $item, 'high' );

			$this->add_render_attribute(
				$link_key,
				array(
					'data-elementor-open-lightbox'      => 'yes',
					'data-elementor-lightbox-title'     => $thumbnail_alt,
					'data-elementor-lightbox-slideshow' => 'tk-ig-' . $this->get_id(),
				)
			);

		} elseif ( 'yes' === $settings['insta_image_link'] ) {
			$item_link = $item['link'];

			$this->add_render_attribute( $link_key, 'target', '_blank' );
		}

		$this->add_render_attribute( $link_key, 'href', $item_link );

		$image_html  = '<div class="tk-if-img uk-cover-container uk-inline-clip uk-position-relative uk-transition-toggle">';
		$image_html .= '  <div class="tk-overlay-container tk-media-overlay uk-overlay-primary uk-transition-slide-bottom uk-position-cover">';

		if ( 'yes' === $settings['insta_caption'] ) {
			$image_html .= '<div class="tk-insta-caption">' . $thumbnail_alt . '</div>';
		}
		if ( 'yes' === $settings['insta_comments'] || 'yes' === $settings['insta_likes'] ) {
			$image_html .= '<div class="tk-insta-icons uk-position-center uk-position-z-index uk-transition-toggle">';
			if ( 'yes' === $settings['insta_comments'] ) {
				$image_html .= '<span class="comments uk-transition-slide-left"><i class="tk-if-icon fa fa-comment"></i> ' . $comments . '</span>';
			}
			if ( 'yes' === $settings['insta_likes'] ) {
				$image_html .= '<span class="likes uk-transition-slide-right"><i class="tk-if-icon fa fa-heart"></i> ' . $likes . '</span>';
			}
			$image_html .= '</div>';
		}
		$image_html .= '</div>';
		$image_html .= '<img class="tk-feed--image" ' . $this->get_render_attribute_string( $image_key ) . '/>';
		$image_html .= '</div>';

		if ( 'yes' === $settings['insta_image_popup'] || 'yes' === $settings['insta_image_link'] ) {

			$image_html = '<div> <a ' . $this->get_render_attribute_string( $link_key ) . '>' . $image_html . '</a> </div>';
		}

		echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}


	/**
	 * Get Insta Thumbnail Image URL
	 *
	 * @since  1.0.0
	 * @param [type] $item .
	 * @param string $size .
	 */
	protected function get_insta_image_url( $item, $size = 'high' ) {
		$thumbnail = $item['thumbnail'];

		if ( ! empty( $thumbnail[ $size ] ) ) {
			$image_url = $thumbnail[ $size ]['src'];
		} else {
			$image_url = isset( $item['image'] ) ? $item['image'] : '';
		}

		return $image_url;
	}


	/**
	 * Render widget output.
	 *
	 *
	 * @access protected
	 */
	protected function content_template() {}

}
