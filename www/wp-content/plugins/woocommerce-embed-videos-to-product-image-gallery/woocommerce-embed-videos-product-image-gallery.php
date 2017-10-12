<?php
/**
 * Plugin Name: WooCommerce - Embed Videos To Product Image Gallery
 * Plugin URL: http://wordpress.org/plugins/woocommerce-embed-videos-product-image-gallery
 * Description:  Embed videos to product gallery alongwith images on product page of WooCommerce.
 * Version: 2.2
 * Author: ZealousWeb Technologies
 * Author URI: http://zealousweb.com
 * Developer: The Zealousweb Team
 * Developer E-Mail: info@opensource.zealousweb.com
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 * 
 * Copyright: © 2009-2015 ZealousWeb Technologies.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
/**
 * 
 * @access      public
 * @since       1.0 
 * @return      $content
*/
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/
require_once (dirname(__FILE__) . '/woocommerce-embed-videos-product-image-gallery.php');
global $post;
register_activation_hook (__FILE__, 'woo_activation_check');
function woo_activation_check()
{
    if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        wp_die( __( '<b>Warning</b> : Install/Activate Woocommerce to activate "Embed Videos To Product Image Gallery" plugin', 'woocommerce' ) );
    }
}
//Add settings link to plugins page
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links_embed_video' );

function add_action_links_embed_video ( $links ) {
     $settingslinks = array(
     '<a href="' . admin_url( 'admin.php?page=embed-videos-product-image-gallery' ) . '">Settings</a>',
     );
    return array_merge( $settingslinks, $links );
}

//Set up menu under woocommerce
add_action('admin_menu', 'embed_videos_setup_menu');
function embed_videos_setup_menu(){
        add_submenu_page( 'woocommerce', 'Embed Videos To Product Image Gallery', 'Embed Videos Settings', 'manage_options', 'embed-videos-settings', 'embed_videos_init'); 
}

//Register options of this plugin
add_action( 'admin_init', 'register_embed_videos_settings' );
function register_embed_videos_settings() {
        register_setting( 'embed-videos-settings', 'embed_videos_autoplay' );
        register_setting( 'embed-videos-settings', 'embed_videos_rel' );
        register_setting( 'embed-videos-settings', 'embed_videos_showinfo' );
        register_setting( 'embed-videos-settings', 'embed_videos_disablekb' );
        register_setting( 'embed-videos-settings', 'embed_videos_fs' );
        register_setting( 'embed-videos-settings', 'embed_videos_controls' );
        register_setting( 'embed-videos-settings', 'embed_videos_hd' );
 } 


 /**
 * Initialize the plugin and display all options at admin side
 */
function embed_videos_init(){
?>
  <h1>Youtube Video Settings</h1>  
  <form method="post" action="options.php">
    <?php settings_fields( 'embed-videos-settings' ); ?>
    <?php do_settings_sections( 'embed-videos-settings' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Autoplay videos:</th>
            <td><input type="checkbox" name="embed_videos_autoplay" value="1" <?php echo (get_option( 'embed_videos_autoplay' ) == 1) ? 'checked': '';?>/></td>      
        </tr>
        <tr valign="top">
            <th scope="row">Show relative videos:</th>
            <td><input type="checkbox" name="embed_videos_rel" value="1" <?php echo (get_option( 'embed_videos_rel' ) == 1) ? 'checked': '';?>/></td>
       </tr> 
        <tr valign="top">
            <th scope="row">Show video information:</th>
            <td>
                <input type="checkbox" name="embed_videos_showinfo" value="1" <?php echo (get_option( 'embed_videos_showinfo' ) == 1) ? 'checked': '';?>/>                
            </td>
        </tr> 
        <?php /*
        <tr valign="top">
            <th scope="row">Allow keyboard controls:</th>
            <td>
                <input type="checkbox" name="embed_videos_disablekb" value="1" <?php echo (get_option( 'embed_videos_disablekb' ) == 1) ? 'checked': '';?>/>
            </td>
        </tr> */ ?>
        <tr valign="top">
            <th scope="row">Show fullscreen button:</th>
            <td>
                <input type="checkbox" name="embed_videos_fs" value="1" <?php echo (get_option( 'embed_videos_fs' ) == 1) ? 'checked': '';?>/>
            </td>
        </tr> 
         <tr valign="top">
            <th scope="row">Show video player controls:</th>
            <td>
                <input type="checkbox" name="embed_videos_controls" value="1" <?php echo (get_option( 'embed_videos_controls' ) == 1) ? 'checked': '';?>/>
            </td>
        </tr>
        <?php /*
        <tr valign="top">
            <th scope="row">Play video in HD mode:</th>
            <td>
                <input type="checkbox" name="embed_videos_hd" value="1" <?php echo (get_option( 'embed_videos_hd' ) == 1) ? 'checked': '';?>/>              
            </td>
        </tr>*/ ?>
    </table>
    <?php submit_button(); ?>
    </div>
  </form>

<?php
}

/**
  * Add form field to get video link id for product image
  **/
add_filter( 'attachment_fields_to_edit', 'woo_embed_video', 20, 2);
function woo_embed_video( $form_fields, $attachment ) { 
   
    $post_id = (int) $_GET[ 'post' ];   
    $nonce = wp_create_nonce( 'bdn-attach_' . $attachment->ID );
    $attach_image_action_url = admin_url( "media-upload.php?tab=library&post_id=$post_id" );

    $field_value = get_post_meta( $attachment->ID, 'videolink_id', true );
    $video_site = get_post_meta( $attachment->ID, 'video_site', true );
    $youtube = ($video_site == 'youtube') ? 'checked' : '';
    $vimeo = ($video_site == 'vimeo') ? 'checked' : '';
    $checked = '';
    if(empty($youtube) && empty($vimeo))
    {
        $checked = 'checked';
    }
    $form_fields['videolink_id'] = array(
        'value' => $field_value ? $field_value : '',
        'input' => "text",
        'label' => __( 'Video Link ID' )        
    );
    $form_fields['video_site'] = array(
        'input' => 'html',
        'value' => $video_site,
        'html' => "<input type='radio' name='attachments[{$attachment->ID}][video_site]' value='youtube' $youtube $checked> Youtube
                   <input type='radio' name='attachments[{$attachment->ID}][video_site]' value='vimeo' $vimeo> Vimeo",                   
        'helps' => __( '<b>For Eg.:</b> <br>"112233445" for URL - https://vimeo.com/112233445 <br>
                     <br>"n93gYncUD" for URL - https://www.youtube.com/watch?v=n93gYncUD' )
    );
    return $form_fields;
}

/**
  * Save form field of video link to display video on product image
  **/
add_action( 'edit_attachment', 'woo_save_embed_video' );
function woo_save_embed_video( $attachment_id ) {
    if ( isset( $_REQUEST['attachments'][$attachment_id]['videolink_id'] ) ) {
        $videolink_id = $_REQUEST['attachments'][$attachment_id]['videolink_id'];
        update_post_meta( $attachment_id, 'videolink_id', $videolink_id );
    }
    if ( isset( $_REQUEST['attachments'][$attachment_id]['video_site'] ) ) {
        $video_site = $_REQUEST['attachments'][$attachment_id]['video_site'];
        update_post_meta( $attachment_id, 'video_site', $video_site );
    }
}
add_action( 'wp_print_scripts', 'my_deregister_javascript', 100 );

function my_deregister_javascript() {
    wp_deregister_script( 'prettyPhoto' );
    wp_deregister_script( 'prettyPhoto-init' );   
}

add_action( 'wp_enqueue_scripts', 'embedvideos_scripts',999 );
function embedvideos_scripts() {
    wp_enqueue_script( 'custom-photoswipe', plugins_url().'/woocommerce-embed-videos-to-product-image-gallery/js/photoswipe.js',array(), '', true  );
}

/**
  * WooCommerce - Embed Videos To Product Image Gallery styles and scripts. 
  */
add_action( 'wp_head', 'woo_scripts_styles' );
function woo_scripts_styles() {    
    $enable_lightbox = get_option( 'woocommerce_enable_lightbox' );   ?>    
<?php }
/**
  * Replace the single product thumbnail html with blank content 
  */
//add_filter('woocommerce_single_product_image_thumbnail_html', 'remove_thumbnail_html');
function remove_thumbnail_html($html){
    $html = '';
    return $html;
}

function remove_gallery_thumbnail_images() {
if ( is_product() ) {
    remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
    }
}
add_action('template_redirect', 'remove_gallery_thumbnail_images');

/* * @global type $woocommerce
 * @global type $product
 * Add new html layout of single product thumbnails
 */
add_action( 'woocommerce_product_thumbnails', 'woo_display_embed_video', 20 );
function woo_display_embed_video( $html ) {
    ?>

    <script type="text/javascript">
        jQuery(window).load(function(){
            jQuery('.woocommerce-product-gallery .flex-viewport').prepend('<div class="emoji-search-icon"></div>');
            jQuery('a.woocommerce-product-gallery__trigger').hide();
        });
    </script>
    <?php
    global $wpdb;
 	$post_id_arr = $wpdb->get_results("SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key = 'videolink_id' " );
 	foreach ($post_id_arr as $key => $value) {
 		$new_post_id_arr[$value->meta_value] = $value->post_id;
 	}

 	$product_thum_id = get_post_meta(get_the_ID(),'_thumbnail_id',true);
        if(in_array($product_thum_id, $new_post_id_arr)){
 		$videolink_id_value = get_post_meta($product_thum_id,'videolink_id',true);
 		if(!empty($videolink_id_value)){
	 		$video_link_name = get_post_meta($product_thum_id,'video_site',true);
	 		?>
	 			<script type="text/javascript">
	 			var video_links = '<?php echo video_site_name($video_link_name,$videolink_id_value); ?>';
                    jQuery(window).load(function(){
                        var id = '.woocommerce-product-gallery__wrapper';
	 					jQuery('.woocommerce-product-gallery__wrapper').find('div a').first().attr('href','#');
	 					jQuery('.woocommerce-product-gallery__wrapper').find('div a').first().attr('data-type','video');
	 					jQuery('.woocommerce-product-gallery__wrapper').find('div a').first().attr('data-video','<div class="wrapper"><div class="video-wrapper"><iframe width="1000" height="640" src="'+video_links+'" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe></div></div>');
                        jQuery(id+' div:first-child a img').remove();
                        jQuery(id+' div:first-child img').remove();
                        jQuery(id+' div:first-child a').html('<iframe height="" src="'+video_links+'" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>');
	 				});
	 			</script>
	 		<?php
 		}
 	}
   	global $woocommerce;
    global $product;    
     
    $attachment_ids = $product->get_gallery_image_ids();
    $enable_lightbox = get_option( 'woocommerce_enable_lightbox' );
    if ( $attachment_ids ) {
        $newhtml = "";
        $loop       = 0;
        $columns    = apply_filters( 'woocommerce_product_thumbnails_columns', 3 ); 
        foreach ( $attachment_ids as $attachment_id ) {
        $newhtml .= '<div data-thumb="'.wp_get_attachment_url( $attachment_id ).'" class="woocommerce-product-gallery__image" >';
            $classes = array( 'zoom' );
            if ( $loop == 0 || $loop % $columns == 0 )
                $classes[] = 'first';
            if ( ( $loop + 1 ) % $columns == 0 )
               $classes[] = 'last';
            $image_link = wp_get_attachment_url( $attachment_id );
            if ( ! $image_link )
                continue;
            $video_link = '';
            $full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
            $thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
            $attributes      = array(
              'title'                   => get_post_field( 'post_title', $attachment_id ),
              'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
              'data-src'                => $full_size_image[0],
              'data-large_image'        => $full_size_image[0],
              'data-large_image_width'  => $full_size_image[1],
              'data-large_image_height' => $full_size_image[2],
            );
            $image = wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
            $image_class = esc_attr( implode( ' ', $classes ) );
            $image_title = esc_attr( get_the_title( $attachment_id ) );
            $videolink_id = get_post_meta( $attachment_id, 'videolink_id', true );            
            $video_site = get_post_meta( $attachment_id, 'video_site', true );            
            if(!empty($videolink_id) && !empty($video_site)){
                switch ($video_site) {
                    case 'youtube':                     

                        $autoplay = get_option( 'embed_videos_autoplay' );
                        $autoplay = (empty($autoplay)) ? 0 : 1;
                        $rel = get_option( 'embed_videos_rel' );
                        $rel = (empty($rel)) ? 0 : 1;
                        $showinfo = get_option( 'embed_videos_showinfo' );
                        $showinfo = (empty($showinfo)) ? 0 : 1;
                        $disablekb = get_option( 'embed_videos_disablekb' );
                        $disablekb = (empty($disablekb)) ? 0 : 1;
                        $fs = get_option( 'embed_videos_fs' );
                        $fs = (empty($fs)) ? 0 : 1;
                        $controls = get_option( 'embed_videos_controls' );
                        $controls = (empty($controls)) ? 0 : 1;
                        $hd = get_option( 'embed_videos_hd' );
                        $hd = (empty($hd)) ? 0 : 1;

                        $parameters = "?autoplay=".$autoplay."&rel=".$rel."&fs=".$fs."&showinfo=".$showinfo."&disablekb=".$disablekb."&controls=".$controls."&hd=".$hd;

                        $video_link = 'https://www.youtube.com/embed/'.$videolink_id.$parameters;
                        break;  
                    case 'vimeo':
                        $video_link = 'https://player.vimeo.com/video/'.$videolink_id;
                        break;  
                }
            }
            $video = '';
            if(!empty($video_link)){

            $newhtml .= '<a href="#"  data-type="video" data-video="<div class=&quot;wrapper&quot;><div class=&quot;video-wrapper&quot;><iframe width=&quot;1000&quot; height=&quot;640&quot; src=&quot;'.$video_link.'&quot; frameborder=&quot;0&quot; allowfullscreen=&quot;true&quot; webkitallowfullscreen=&quot;true&quot; mozallowfullscreen=&quot;true&quot;></iframe></div></div>" ><iframe class="iframelist"  height="" src="'.$video_link.'" frameborder="0" allowfullscreen=
            "true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe></a>';
            ?>
            <?php
            }else{
               $link = (empty($video_link)) ? $image_link : $video_link;       
            $newhtml .= '<a href="'.$link.'" class="'. $image_class.'" title="'. $image_title.'" rel="prettyPhoto[product-gallery]" data-type="image"  >'.$image.' </a>';   
            }
          
            $loop++;
        $newhtml .= '</div>'; 
        }
        echo $newhtml;     
    }
?>
        <link rel='stylesheet prefetch' href='<?php echo plugins_url().'/woocommerce-embed-videos-to-product-image-gallery/css/photoswipe.css'; ?>'>
        <?php 
}
function video_site_name($video_site,$videolink_id){
	switch ($video_site) {
	    case 'youtube':                     

	        $autoplay = get_option( 'embed_videos_autoplay' );
	        $autoplay = (empty($autoplay)) ? 0 : 1;
	        $rel = get_option( 'embed_videos_rel' );
	        $rel = (empty($rel)) ? 0 : 1;
	        $showinfo = get_option( 'embed_videos_showinfo' );
	        $showinfo = (empty($showinfo)) ? 0 : 1;
	        $disablekb = get_option( 'embed_videos_disablekb' );
	        $disablekb = (empty($disablekb)) ? 0 : 1;
	        $fs = get_option( 'embed_videos_fs' );
	        $fs = (empty($fs)) ? 0 : 1;
	        $controls = get_option( 'embed_videos_controls' );
	        $controls = (empty($controls)) ? 0 : 1;
	        $hd = get_option( 'embed_videos_hd' );
	        $hd = (empty($hd)) ? 0 : 1;

	        $parameters = "?autoplay=".$autoplay."&rel=".$rel."&fs=".$fs."&showinfo=".$showinfo."&disablekb=".$disablekb."&controls=".$controls."&hd=".$hd;

	        $video_link = 'https://www.youtube.com/embed/'.$videolink_id.$parameters;
	        break;  
	    case 'vimeo':
	        $video_link = 'https://player.vimeo.com/video/'.$videolink_id;
	        break;  
	}
	echo $video_link;
}