<?php
/**
 * Plugin Name: WooCommerce - Embed Videos To Product Image Gallery
 * Plugin URL: http://wordpress.org/plugins/woocommerce-embed-videos-product-image-gallery
 * Description:  Embed videos to product gallery alongwith images on product page of WooCommerce.
 * Version: 1.2
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
        <tr valign="top">
            <th scope="row">Allow keyboard controls:</th>
            <td>
                <input type="checkbox" name="embed_videos_disablekb" value="1" <?php echo (get_option( 'embed_videos_disablekb' ) == 1) ? 'checked': '';?>/>
            </td>
        </tr> 
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
        <tr valign="top">
            <th scope="row">Play video in HD mode:</th>
            <td>
                <input type="checkbox" name="embed_videos_hd" value="1" <?php echo (get_option( 'embed_videos_hd' ) == 1) ? 'checked': '';?>/>              
            </td>
        </tr>
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

add_action( 'wp_enqueue_scripts', 'embedvideos_scripts' );
function embedvideos_scripts() {
    wp_dequeue_style( 'woocommerce_prettyPhoto_css');
    wp_enqueue_script( 'EmbedVideosPrettyPhoto', plugins_url().'/woocommerce-embed-videos-to-product-image-gallery/assets/PrettyPhoto/js/jquery.prettyPhoto.js', array( 'jquery' ), '20150330', false);
    wp_enqueue_style( 'EmbedVideosPrettyPhoto', plugins_url().'/woocommerce-embed-videos-to-product-image-gallery/assets/PrettyPhoto/css/prettyPhoto.css' );
}

/**
  * WooCommerce - Embed Videos To Product Image Gallery styles and scripts. 
  */
add_action( 'wp_head', 'woo_scripts_styles' );
function woo_scripts_styles() {    
    $enable_lightbox = get_option( 'woocommerce_enable_lightbox' );   ?>
    <style>
    .play-overlay{
        background: url('<?php echo plugins_url();?>/woocommerce-embed-videos-to-product-image-gallery/assets/play.png') center center no-repeat;
        height: 61px;
        margin: -80px -2px 0 0;
        position: relative;
        z-index: 10;
    }
    </style>
    <script>
        jQuery(document).ready(function(){
            var enable_lightbox = '<?php echo $enable_lightbox;?>';
            if(enable_lightbox == 'no'){  
                jQuery('.thumbnails .zoom').click(function(e){                
                    e.preventDefault();                
                    var photo_fullsize =  jQuery(this).attr('href');                     
                    if (jQuery('.images iframe').length > 0) 
                    {                     
                        if(photo_fullsize.indexOf('youtube') > (-1) || photo_fullsize.indexOf('vimeo') > (-1)){            
                            jQuery('.images iframe:first').attr('src', photo_fullsize);
                        } else {
                            jQuery('.images iframe:first').replaceWith('<img src="'+photo_fullsize+'" alt="Placeholder">');
                        }                    
                    } else {                
                       if(photo_fullsize.indexOf('youtube') > (-1) || photo_fullsize.indexOf('vimeo') > (-1)){            
                            jQuery('.images img:first').replaceWith( '<iframe src="'+photo_fullsize+'" frameborder="0" allowfullscreen></iframe>' );
                        } else {
                            jQuery('.images img:first').attr('src', photo_fullsize);
                        }
                    }             
                });
            }
            else{                 
                    jQuery("a[rel^='prettyPhoto[product-gallery]']").prettyPhoto();                        
            }
        });
    </script>        
<?php }
/**
  * Replace the single product thumbnail html with blank content 
  */
add_filter('woocommerce_single_product_image_thumbnail_html', 'remove_thumbnail_html');
function remove_thumbnail_html($html){
    $html = '';
    return $html;
}

/* * @global type $woocommerce
 * @global type $product
 * Add new html layout of single product thumbnails
 */
add_action( 'woocommerce_product_thumbnails', 'woo_display_embed_video', 20 );
function woo_display_embed_video( $html ) {
    // Get WooCommerce Global
    global $woocommerce;
    global $product;    
     
    $attachment_ids = $product->get_gallery_attachment_ids();
    $enable_lightbox = get_option( 'woocommerce_enable_lightbox' );
    
    if ( $attachment_ids ) {
        $newhtml = "";
        $loop       = 0;
        $columns    = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
        $newhtml = '<div class="thumbnails columns-' . $columns.'">';
        foreach ( $attachment_ids as $attachment_id ) {
            $classes = array( 'zoom' );
            if ( $loop == 0 || $loop % $columns == 0 )
                $classes[] = 'first';
            if ( ( $loop + 1 ) % $columns == 0 )
               $classes[] = 'last';
            $image_link = wp_get_attachment_url( $attachment_id );
            if ( ! $image_link )
                continue;
            $video_link = '';
            $image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
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

                        $video_link = ($enable_lightbox == 'yes') ? 'https://www.youtube.com/watch?v='.$videolink_id.$parameters : 'https://www.youtube.com/embed/'.$videolink_id.$parameters;
                        break;  
                    case 'vimeo':
                        $video_link = ($enable_lightbox == 'yes') ? 'https://vimeo.com/'.$videolink_id : 'https://player.vimeo.com/video/'.$videolink_id;
                        break;  
                }
            }
            $video = '';
            if(!empty($video_link)){
                $video = '<div class="play-overlay"></div>';
            }
            $link = (empty($video_link)) ? $image_link : $video_link;            
            $newhtml .= '<a href="'.$link.'" class="'. $image_class.'" title="'. $image_title.'" rel="prettyPhoto[product-gallery]">'.$image.$video.'</a>';
            $loop++;
        }
        $newhtml .= '</div>'; 
    }
    echo $newhtml;     
}