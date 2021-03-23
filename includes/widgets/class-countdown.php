<?php
/**
 * Elementor Countdown Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

namespace TamanKitPro\Widgets;

// Elementor Classes.
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
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Undocumented class
 */
class CountDown extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Countdown widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tk-countdown';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Countdown widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Countdown', 'taman-kit-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Countdown widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'taman-kit-editor-icon eicon-countdown';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Countdown widget belongs to.
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
			'jquery-countdown',
			'taman-kit-pro',
		);
	}

	/**
	 * Register Countdown widget controls.
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
		*==================================== Setting controls ============================
		*
		*==================================================================================
		*/

		$this->start_controls_section(
			'tk_countdown_global_settings',
			array(
				'label' => esc_html__( 'Countdown', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_countdown_style',
			array(
				'label'   => esc_html__( 'Style', 'taman-kit-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'd-u-s' => esc_html__( 'Inline', 'taman-kit-pro' ),
					'd-u-u' => esc_html__( 'Block', 'taman-kit-pro' ),
				),
				'default' => 'd-u-u',
			)
		);

		$this->add_control(
			'tk_countdown_date_time',
			array(
				'label'          => esc_html__( 'Due Date', 'taman-kit-pro' ),
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => array(
					'format' => 'Ym/d H:m:s',
				),
				'default'        => gmdate( 'Y/m/d H:m:s', strtotime( '+ 1 Day' ) ),
				'description'    => esc_html__( 'Date format is (yyyy/mm/dd). Time format is (hh:mm:ss). Example: 2020-01-01 09:30.', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_countdown_s_u_time',
			array(
				'label'       => esc_html__( 'Time Zone', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'wp-time'   => esc_html__( 'WordPress Default', 'taman-kit-pro' ),
					'user-time' => esc_html__( 'User Local Time', 'taman-kit-pro' ),
				),
				'default'     => 'wp-time',
				'description' => esc_html__( 'This will set the current time of the option that you will choose.', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_countdown_units',
			array(
				'label'       => esc_html__( 'Time Units', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'description' => esc_html__( 'Select the time units that you want to display in countdown timer.', 'taman-kit-pro' ),
				'options'     => array(
					'Y' => esc_html__( 'Years', 'taman-kit-pro' ),
					'O' => esc_html__( 'Month', 'taman-kit-pro' ),
					'W' => esc_html__( 'Week', 'taman-kit-pro' ),
					'D' => esc_html__( 'Day', 'taman-kit-pro' ),
					'H' => esc_html__( 'Hours', 'taman-kit-pro' ),
					'M' => esc_html__( 'Minutes', 'taman-kit-pro' ),
					'S' => esc_html__( 'Second', 'taman-kit-pro' ),
				),
				'default'     => array( 'O', 'D', 'H', 'M', 'S' ),
				'multiple'    => true,
				'separator'   => 'after',
			)
		);

		$this->add_control(
			'tk_countdown_separator',
			array(
				'label'       => esc_html__( 'Digits Separator', 'taman-kit-pro' ),
				'description' => esc_html__( 'Enable or disable digits separator', 'taman-kit-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array(
					'tk_countdown_style' => 'd-u-u',
				),
			)
		);

		$this->add_control(
			'tk_countdown_separator_text',
			array(
				'label'     => esc_html__( 'Separator Text', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'tk_countdown_style'     => 'd-u-u',
					'tk_countdown_separator' => 'yes',
				),
				'default'   => ':',
			)
		);

		$this->add_responsive_control(
			'tk_countdown_align',
			array(
				'label'     => esc_html__( 'Alignment', 'taman-kit-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'toggle'    => false,
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .tk-countdown' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_countdown_on_expire_settings',
			array(
				'label' => esc_html__( 'Expire', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_countdown_expire_text_url',
			array(
				'label'       => esc_html__( 'Expire Type', 'taman-kit-pro' ),
				'label_block' => false,
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Choose whether if you want to set a message or a redirect link', 'taman-kit-pro' ),
				'options'     => array(
					'text' => esc_html__( 'Message', 'taman-kit-pro' ),
					'url'  => esc_html__( 'Redirection Link', 'taman-kit-pro' ),
				),
				'default'     => 'text',
			)
		);

		$this->add_control(
			'tk_countdown_expiry_text_',
			array(
				'label'     => esc_html__( 'On expiry Text', 'taman-kit-pro' ),
				'type'      => Controls_Manager::WYSIWYG,
				'dynamic'   => array( 'active' => true ),
				'default'   => esc_html__( 'Countdown is finished!', 'prmeium_elementor' ),
				'condition' => array(
					'tk_countdown_expire_text_url' => 'text',
				),
			)
		);

		$this->add_control(
			'tk_countdown_expiry_redirection_',
			array(
				'label'     => esc_html__( 'Redirect To', 'taman-kit-pro' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'condition' => array(
					'tk_countdown_expire_text_url' => 'url',
				),
				'default'   => get_permalink( 1 ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_countdown_transaltion',
			array(
				'label' => esc_html__( 'Strings Translation', 'taman-kit-pro' ),
			)
		);

		$this->add_control(
			'tk_countdown_day_singular',
			array(
				'label'   => esc_html__( 'Day (Singular)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Day',
			)
		);

		$this->add_control(
			'tk_countdown_day_plural',
			array(
				'label'   => esc_html__( 'Day (Plural)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Days',
			)
		);

		$this->add_control(
			'tk_countdown_week_singular',
			array(
				'label'   => esc_html__( 'Week (Singular)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Week',
			)
		);

		$this->add_control(
			'tk_countdown_week_plural',
			array(
				'label'   => esc_html__( 'Weeks (Plural)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Weeks',
			)
		);

		$this->add_control(
			'tk_countdown_month_singular',
			array(
				'label'   => esc_html__( 'Month (Singular)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Month',
			)
		);

		$this->add_control(
			'tk_countdown_month_plural',
			array(
				'label'   => esc_html__( 'Months (Plural)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Months',
			)
		);

		$this->add_control(
			'tk_countdown_year_singular',
			array(
				'label'   => esc_html__( 'Year (Singular)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Year',
			)
		);

		$this->add_control(
			'tk_countdown_year_plural',
			array(
				'label'   => esc_html__( 'Years (Plural)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Years',
			)
		);

		$this->add_control(
			'tk_countdown_hour_singular',
			array(
				'label'   => esc_html__( 'Hour (Singular)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Hour',
			)
		);

		$this->add_control(
			'tk_countdown_hour_plural',
			array(
				'label'   => esc_html__( 'Hours (Plural)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Hours',
			)
		);

		$this->add_control(
			'tk_countdown_minute_singular',
			array(
				'label'   => esc_html__( 'Minute (Singular)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Minute',
			)
		);

		$this->add_control(
			'tk_countdown_minute_plural',
			array(
				'label'   => esc_html__( 'Minutes (Plural)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Minutes',
			)
		);

		$this->add_control(
			'tk_countdown_second_singular',
			array(
				'label'   => esc_html__( 'Second (Singular)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Second',
			)
		);

		$this->add_control(
			'tk_countdown_second_plural',
			array(
				'label'   => esc_html__( 'Seconds (Plural)', 'taman-kit-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => 'Seconds',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_countdown_typhography',
			array(
				'label' => esc_html__( 'Digits', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tk_countdown_digit_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'tk_countdown_digit_typo',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'selector'  => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'tk_countdown_timer_digit_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tk_countdown_units_shadow',
				'selector' => '{{WRAPPER}} .countdown .pre_countdown-section',
			)
		);

		$this->add_responsive_control(
			'tk_countdown_digit_bg_size',
			array(
				'label'     => esc_html__( 'Background Size', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 400,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'padding: {{SIZE}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tk_countdown_digits_border',
				'selector' => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount',
			)
		);

		$this->add_control(
			'tk_countdown_digit_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_countdown_unit_style',
			array(
				'label' => esc_html__( 'Units', 'taman-kit-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tk_countdown_unit_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-period' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tk_countdown_unit_typo',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-period',
			)
		);

		$this->add_control(
			'tk_countdown_unit_backcolor',
			array(
				'label'     => esc_html__( 'Background Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-period' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_countdown_separator_width',
			array(
				'label'     => esc_html__( 'Spacing in Between', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 40,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .countdown .pre_countdown-section' => 'margin-right: calc( {{SIZE}}{{UNIT}} / 2 ); margin-left: calc( {{SIZE}}{{UNIT}} / 2 );',
				),
				'condition' => array(
					'tk_countdown_separator!' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tk_countdown_separator_style',
			array(
				'label'     => esc_html__( 'Separator', 'taman-kit-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tk_countdown_style'     => 'd-u-u',
					'tk_countdown_separator' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'tk_countdown_separator_size',
			array(
				'label'     => esc_html__( 'Size', 'taman-kit-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pre-countdown_separator' => 'font-size: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'tk_countdown_separator_color',
			array(
				'label'     => esc_html__( 'Color', 'taman-kit-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .pre-countdown_separator' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tk_countdown_separator_margin',
			array(
				'label'      => esc_html__( 'Margin', 'taman-kit-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pre-countdown_separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * Render Countdown widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$target_date = str_replace( '-', '/', $settings['tk_countdown_date_time'] );

		$formats = $settings['tk_countdown_units'];
		$format  = implode( '', $formats );
		$time    = str_replace( '-', '/', current_time( 'mysql' ) );

		if ( 'wp-time' === $settings['tk_countdown_s_u_time'] ) :
			$sent_time = $time;
		else :
			$sent_time = '';
		endif;

		$redirect = ! empty( $settings['tk_countdown_expiry_redirection_'] ) ? esc_url( $settings['tk_countdown_expiry_redirection_'] ) : '';

		// Singular labels set up.
		$y     = ! empty( $settings['tk_countdown_year_singular'] ) ? $settings['tk_countdown_year_singular'] : 'Year';
		$m     = ! empty( $settings['tk_countdown_month_singular'] ) ? $settings['tk_countdown_month_singular'] : 'Month';
		$w     = ! empty( $settings['tk_countdown_week_singular'] ) ? $settings['tk_countdown_week_singular'] : 'Week';
		$d     = ! empty( $settings['tk_countdown_day_singular'] ) ? $settings['tk_countdown_day_singular'] : 'Day';
		$h     = ! empty( $settings['tk_countdown_hour_singular'] ) ? $settings['tk_countdown_hour_singular'] : 'Hour';
		$mi    = ! empty( $settings['tk_countdown_minute_singular'] ) ? $settings['tk_countdown_minute_singular'] : 'Minute';
		$s     = ! empty( $settings['tk_countdown_second_singular'] ) ? $settings['tk_countdown_second_singular'] : 'Second';
		$label = $y . ',' . $m . ',' . $w . ',' . $d . ',' . $h . ',' . $mi . ',' . $s;

		// Plural labels set up.
		$ys      = ! empty( $settings['tk_countdown_year_plural'] ) ? $settings['tk_countdown_year_plural'] : 'Years';
		$ms      = ! empty( $settings['tk_countdown_month_plural'] ) ? $settings['tk_countdown_month_plural'] : 'Months';
		$ws      = ! empty( $settings['tk_countdown_week_plural'] ) ? $settings['tk_countdown_week_plural'] : 'Weeks';
		$ds      = ! empty( $settings['tk_countdown_day_plural'] ) ? $settings['tk_countdown_day_plural'] : 'Days';
		$hs      = ! empty( $settings['tk_countdown_hour_plural'] ) ? $settings['tk_countdown_hour_plural'] : 'Hours';
		$mis     = ! empty( $settings['tk_countdown_minute_plural'] ) ? $settings['tk_countdown_minute_plural'] : 'Minutes';
		$ss      = ! empty( $settings['tk_countdown_second_plural'] ) ? $settings['tk_countdown_second_plural'] : 'Seconds';
		$labels1 = $ys . ',' . $ms . ',' . $ws . ',' . $ds . ',' . $hs . ',' . $mis . ',' . $ss;

		$expire_text = $settings['tk_countdown_expiry_text_'];

		$pcdt_style = 'd-u-s' === $settings['tk_countdown_style'] ? ' side' : ' down';

		if ( 'text' === $settings['tk_countdown_expire_text_url'] ) {
			$event = 'onExpiry';
			$text  = $expire_text;
		}

		if ( 'url' === $settings['tk_countdown_expire_text_url'] ) {
			$event = 'expiryUrl';
			$text  = $redirect;
		}

		$separator_text = ! empty( $settings['tk_countdown_separator_text'] ) ? $settings['tk_countdown_separator_text'] : '';

		$countdown_settings = array(
			'label1'     => $label,
			'label2'     => $labels1,
			'until'      => $target_date,
			'format'     => $format,
			'event'      => $event,
			'text'       => $text,
			'serverSync' => $sent_time,
			'separator'  => $separator_text,
		);

		?>
<div id="countDownContiner-<?php echo esc_attr( $this->get_id() ); ?>"
	class="uk-countdown  tk-countdown tk-countdown-separator-<?php echo esc_attr( $settings['tk_countdown_separator'] ); ?>"
	data-settings='<?php echo wp_json_encode( $countdown_settings ); ?>'>
	<div id="countdown-<?php echo esc_attr( $this->get_id() ); ?>"
		class="tk-countdown-init countdown<?php echo esc_attr( $pcdt_style ); ?>"></div>
</div>

		<?php
	}

}
