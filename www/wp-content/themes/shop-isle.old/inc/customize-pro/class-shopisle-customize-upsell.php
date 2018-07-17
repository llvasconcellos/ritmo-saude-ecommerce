<?php

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Shopisle_Customizer_Upsell {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		require_once( trailingslashit( get_template_directory() ) . 'inc/customize-pro/class-shopisle-customize-upsell-pro.php' );
		require_once( trailingslashit( get_template_directory() ) . 'inc/customize-pro/class-shopisle-customize-upsell-frontpage-sections.php' );

		// Register custom section types.
		$manager->register_section_type( 'Shopisle_Customizer_Upsell_Pro' );
		$manager->register_section_type( 'Shopisle_Customizer_Upsell_Frontpage_Sections' );


		// Register sections.
		$manager->add_section( new Shopisle_Customizer_Upsell_Pro( $manager, 'shopisle-upsell',
				array(
					'upsell_title' => __('View PRO version', 'shop-isle'),
					'label_url' => 'http://themeisle.com/themes/shop-isle-pro/',
					'label_text' => __('Get it', 'shop-isle'),
				)
			)
		);

		// Register sections.
		$manager->add_section( new Shopisle_Customizer_Upsell_Frontpage_Sections( $manager, 'shopisle-upsell-frontpage-sections',
				array(
					'upsell_link_url_before'    => esc_url('http://themeisle.com/themes/shop-isle-pro'),
					'upsell_link_text_before'   => __('View PRO version', 'shop-isle') ,
					'upsell_text'               => __('It adds 4 new sections, the ability to re-order existing ones and easily add custom content to frontpage.', 'shop-isle'),
					'panel'                     => 'shop_isle_front_page_sections',
					'priority'                  => 500,
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'shopisle-upsell-js', trailingslashit( get_template_directory_uri() ) . 'inc/customize-pro/shopisle-upsell-customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'shopisle-upsell-style', trailingslashit( get_template_directory_uri() ) . 'inc/customize-pro/shopisle-upsell-customize-controls.css' );
	}
}

Shopisle_Customizer_Upsell::get_instance();
