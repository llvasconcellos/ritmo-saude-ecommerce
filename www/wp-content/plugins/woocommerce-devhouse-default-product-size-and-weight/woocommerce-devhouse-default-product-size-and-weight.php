<?php
/**
 * Plugin Name:          WooCommerce DevHouse Default Product Size And Weight
 * Plugin URI:           https://github.com/DevHouse-BR/woocommerce-devhouse-default-product-size-and-weight
 * Description:          WordPress WooCommerce Plugin to set deafult size and weight values to products so that shipping calculation plugins won't fail.
 * Author:               Leonardo Lima de Vasconcellos
 * Author URI:           https://www.devhouse.com.br
 * Version:              1.0.0
 * License:              GPLv2 or later
 * Text Domain:          woocommerce-devhouse-default-product-size-and-weight
 * Domain Path:          /languages/
 * WC requires at least: 3.0.0
 * WC tested up to:      3.4.0
 *
 * WooCommerce DevHouse Default Product Size And Weight is free software: you can 
 * redistribute it and/or modify it under the terms of the GNU General Public 
 * License as published by the Free Software Foundation, either version 2 of the 
 * License, or any later version.
 *
 * WooCommerce DevHouse Default Product Size And Weight is distributed in the hope
 * that it will be useful, but WITHOUT ANY WARRANTY; without even the implied 
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WooCommerce DevHouse Default Product Size And Weight. If not, see
 * <https://www.gnu.org/licenses/gpl-2.0.txt>.
 *
 * @package WooCommerce_DevHouse_Default_Product_Size_And_Weight
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_DevHouse_Default_PSAW' ) ) :

	/**
	 * WooCommerce PagSeguro main class.
	 */
	class WC_DevHouse_Default_PSAW {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		const VERSION = '1.0.0';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin public actions.
		 */
		private function __construct() {
			// Load plugin text domain.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			// Checks if WooCommerce is installed.
			if ( class_exists( 'WooCommerce' ) ) {
				// To set Default Length
				add_filter( 'woocommerce_correios_package_length',  array( $this, 'product_default_value_length' ) );

				// To set Default Width
				add_filter( 'woocommerce_correios_package_width',  array( $this, 'product_default_value_width' ) );

				// To set Default Height
				add_filter( 'woocommerce_correios_package_height', array( $this, 'product_default_value_height' ) );

				// To set Default Weight
				add_filter( 'woocommerce_correios_package_weight', array( $this, 'product_default_value_weight' ) );

				// Create the section beneath the products tab
				add_filter( 'woocommerce_get_sections_products', array( $this, 'default_product_size_weight_add_section' ) );

				// Add settings to the specific section
				add_filter( 'woocommerce_get_settings_products', array( $this, 'default_product_size_weight_all_settings' ), 10, 2 );

			} else {
				add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
			}
		}

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Get templates path.
		 *
		 * @return string
		 */
		public static function get_templates_path() {
			return plugin_dir_path( __FILE__ ) . 'templates/';
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'woocommerce-devhouse-default-product-size-and-weight', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * WooCommerce missing notice.
		 */
		public function woocommerce_missing_notice() {
			include dirname( __FILE__ ) . '/includes/html-notice-missing-woocommerce.php';
		}

		/**
		 * Set Default Values.
		 *
		 * @param integer $value the default size/weight value to set.
		 *
		 * @return integer
		 */
		public function product_default_value_length( $value ) {
			// Provide default value
			$default_value = get_option( 'default_product_size_weight_length', 16 );

			if( empty($value) ) {
				return $default_value;
			}
			else {
				return $value;
			}
		}

		/**
		 * Set Default Values.
		 *
		 * @param integer $value the default size/weight value to set.
		 *
		 * @return integer
		 */
		public function product_default_value_width( $value ) {
			// Provide default value
			$default_value = get_option( 'default_product_size_weight_width', 11 );


			if( empty($value) ) {
				return $default_value;
			}
			else {
				return $value;
			}
		}
		
		/**
		 * Set Default Values.
		 *
		 * @param integer $value the default size/weight value to set.
		 *
		 * @return integer
		 */
		public function product_default_value_height( $value ) {
			// Provide default value
			$default_value = get_option( 'default_product_size_weight_height', 2 );

			if( empty($value) ) {
				return $default_value;
			}
			else {
				return $value;
			}
		}

		/**
		 * Set Default Values.
		 *
		 * @param integer $value the default size/weight value to set.
		 *
		 * @return integer
		 */
		public function product_default_value_weight( $value ) {
			// Provide default value
			$default_value = get_option( 'default_product_size_weight_weight', 1 );

			if( empty($value) ) {
				return $default_value;
			}
			else {
				return $value;
			}
		}

		/**
		* Create the section beneath the products tab
		**/
		function default_product_size_weight_add_section( $sections ) {
			$sections['default_product_size_weight'] = __( 'Default Product Size And Weight', 'woocommerce-devhouse-default-product-size-and-weight' );
			return $sections;
		}

		/**
		* Add settings to the specific section
		*/
		function default_product_size_weight_all_settings( $settings, $current_section ) {
			if ( $current_section == 'default_product_size_weight' ) {
				$new_settings = array();

				$new_settings[] = array( 
					'name' => __( 'Default Product Size And Weight Settings', 'woocommerce-devhouse-default-product-size-and-weight' ), 
					'type' => 'title', 
					'desc' => __( "Set deafult size and weight values to products so that shipping calculation plugins won't fail.", 'woocommerce-devhouse-default-product-size-and-weight' ), 
					'id' => 'default_product_size_weight_title' 
				);
				
				$new_settings[] = array(
					'name'     => __( 'Default Length', 'woocommerce-devhouse-default-product-size-and-weight' ),
					'id'       => 'default_product_size_weight_length',
					'type'     => 'text',
				);

				$new_settings[] = array(
					'name'     => __( 'Default Width', 'woocommerce-devhouse-default-product-size-and-weight' ),
					'id'       => 'default_product_size_weight_width',
					'type'     => 'text',
				);

				$new_settings[] = array(
					'name'     => __( 'Default Height', 'woocommerce-devhouse-default-product-size-and-weight' ),
					'id'       => 'default_product_size_weight_height',
					'type'     => 'text',
				);

				$new_settings[] = array(
					'name'     => __( 'Default Weight', 'woocommerce-devhouse-default-product-size-and-weight' ),
					'id'       => 'default_product_size_weight_weight',
					'type'     => 'text',
				);

				$new_settings[] = array( 
					'type' => 'sectionend', 
					'id' => 'default_product_size_weight' 
				);
				return $new_settings;
			} else {
				return $settings;
			}
		}
	}

	add_action( 'plugins_loaded', array( 'WC_DevHouse_Default_PSAW', 'get_instance' ) );

endif;