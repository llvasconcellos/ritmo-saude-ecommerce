<?php
/**
 * Pro customizer section.
 *
 * @since  1.0.0
 * @access public
 */
class Shopisle_Customizer_Upsell_Frontpage_Sections extends WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'shopisle-upsell-frontpage-sections';

	/**
	 * Upsell text to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $upsell_text = '';

	/**
	 * Link before URL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $upsell_link_url_before = '';

	/**
	 * Link before text.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $upsell_link_text_before = '';

	/**
	 * Link after text.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $upsell_link_text_after = '';

	/**
	 * Link after URL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $upsell_link_url_after = '';

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();

		$json['upsell_text']                = esc_html( $this->upsell_text );
		$json['upsell_link_text_before']    = esc_html( $this->upsell_link_text_before );
		$json['upsell_link_text_after']     = esc_html( $this->upsell_link_text_after );
		$json['upsell_link_url_before']     = esc_url( $this->upsell_link_url_before );
		$json['upsell_link_url_after']      = esc_url( $this->upsell_link_url_after );

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>

		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
			<p class="frontpage-sections-upsell">
				<# 	if ( data.upsell_link_url_before && data.upsell_link_text_before ) { #>
					<a href="{{data.upsell_link_url_before}}" target="_blank">{{data.upsell_link_text_before}}</a>
				<# }

				if ( data.upsell_text ) { #>
					{{data.upsell_text}}
				<# }

				if ( data.upsell_link_url_after && data.upsell_link_text_after ) { #>
					<a href="{{data.upsell_link_url_after}}" target="_blank">{{data.upsell_link_text_after}}</a>
				<# } #>
			</p>
		</li>
		<?php
	}
}