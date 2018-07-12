<?php
/**
 * Plugin Name: WooCommerce RitmoSaúde
 * Plugin URI:  https://www.devhouse.com.br
 * Description: Adaptações para o site RitmoSaúde.
 * Author:      Leonardo Lima de Vasconcellos
 * Author URI:  https://www.devhouse.com.br
 * Version:     1.0
 * License:     GPLv2 or later
 * Text Domain: woocommerce
 * Domain Path: /languages
 *
 *
 * @package WC_RitmoSaude
 */
 
 /**
 * Reordenação de produtos. Mensagem e link para venda em atacado.
 */
class WC_RitmoSaude
{

    public function __construct()
    {
        // Check if WooCommerce is active
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_filter('posts_clauses', array($this, 'order_by_stock_status'), 2000);
            
            add_action('woocommerce_after_single_variation', array($this, 'woocommerce_after_single_variation_custom'), 2000);
            
            add_action('shop_isle_before_footer', array($this, 'shop_isle_before_footer_custom'), 2000);
        }
    }

    public function order_by_stock_status($posts_clauses)
    {
        global $wpdb;
        // only change query on WooCommerce loops
        if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())) {
            $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
            $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
            $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
        }
        return $posts_clauses;
    }
    
    public function woocommerce_after_single_variation_custom()
    {
        $post = get_post();
        
        if($post->post_name != "barra-de-cereal-rip-fibras") return;
        
        echo '<div class="alert alert-warning atacado-warning" role="alert">
                 <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> <a href="https://www.ritmosaude.com.br/vendas-no-atacado/">Consulte condições especiais para venda por atacado.</a>
            </div>';
    }
    
     public function shop_isle_before_footer_custom()
    {
        echo '<div class="parallax">';
        echo do_shortcode('[mc4wp_form id="1134"]');
        echo '</div>';
    }

    
}

new WC_RitmoSaude;

?>