<?php
/*
Plugin Name: Client Side Image Resize
Plugin URI: https://github.com/WPsites/Resize-images-before-upload
Description: Resize your images before they are uploaded to the server using HTML5. Send huge images directly to wordpress interface. They will be resized before upload to server, saving time and discarting the use of additional software.
Version: 1.1
Author: Leonardo Lima de Vasconcellos
Author URI: http://www.devhouse.com.br
License: GPL2
*/
/*  Copyright 2014  Leonardo Lima de Vasconcellos  (email : leonardo@devhouse.com.br)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


class DevHouse_Client_Side_Image_Resize {

    function __construct() {
        add_filter("plupload_init", array($this, "inject_plupload_settings"), 20);
        
        load_plugin_textdomain('client-side-image-resize', false, basename( dirname( __FILE__ ) ) . '/languages' );
        
        $plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_" . $plugin, array($this, 'plugin_settings_link'));
        
        add_action('post-upload-ui', array($this,'show_note'), 10);
        
        add_action('admin_init', array($this, 'create_media_settings'), 20);
    }
    
    function inject_plupload_settings( $params ){
        $params['max_file_size'] = "200097152b";
        $params['resize'] = array(
            'enabled' => true,
            'width' => $this->get_resize_width(),
            'height' => $this->get_resize_height(),
            'quality' => $this->get_resize_jpg_quality()
        );
        
        return $params;
    }

    function plugin_settings_link($links) { 
        $settings_link = '<a href="options-media.php#DevHouse_CSIR">' . __('Settings', 'client-side-image-resize') . '</a>'; 
        array_unshift($links, $settings_link); 
        return $links; 
    }
    
    function show_note(){
        echo "<p> ";
        echo __('Client Side Image Resize Plugin is active!', 'client-side-image-resize');
        echo '<br/>';
        echo __('Large images will be resized as specified in your media settings!', 'client-side-image-resize');
        echo "</p>";
    }

    function create_media_settings(){
        add_settings_section(
            'DevHouse_CSIR_media_section',
            'Client Side Image Resize<a style="text-decoration: none;" name="DevHouse_CSIR">&nbsp;</a>',
            array($this,'media_settings_section_callback_function'),
            'media'
        );
    
        add_settings_field(
            'DevHouse_CSIR_resize_jpg_quality',
            __('JPG quality', 'client-side-image-resize'),
            array($this,'resize_jpg_quality_callback_function'),
            'media',
            'DevHouse_CSIR_media_section'
        );
        
        add_settings_field(
            'DevHouse_CSIR_resize_width',
            __('Resize width', 'client-side-image-resize'),
            array($this,'resize_width_callback_function'),
            'media',
            'DevHouse_CSIR_media_section'
        );
                
        add_settings_field(
            'DevHouse_CSIR_resize_height',
            __('Resize height', 'client-side-image-resize'),
            array($this,'resize_height_callback_function'),
            'media',
            'DevHouse_CSIR_media_section'
        );
        
        register_setting('media', 'DevHouse_CSIR_resize_jpg_quality', array($this,'resize_jpg_quality_validate_input'));
        
        register_setting('media', 'DevHouse_CSIR_resize_width');
        
        register_setting('media', 'DevHouse_CSIR_resize_height');
    }

    function media_settings_section_callback_function(){}

    function resize_jpg_quality_callback_function(){        
        echo '<input name="DevHouse_CSIR_resize_jpg_quality" id="DevHouse_CSIR_resize_jpg_quality" type="text" value="' . $this->get_resize_jpg_quality() . '" class="small-text" /> <em class="description">1 - 100</em>';
    }
    
    function resize_width_callback_function(){
	echo '<input name="DevHouse_CSIR_resize_width" id="DevHouse_CSIR_resize_width" type="text" value="' . $this->get_resize_width() . '" class="small-text" />';
    }
    
    function resize_height_callback_function(){
        echo '<input name="DevHouse_CSIR_resize_height" id="DevHouse_CSIR_resize_height" type="text" value="' . $this->get_resize_height() . '" class="small-text" />';
    }
    
    function get_resize_jpg_quality(){
        $quality = get_option('DevHouse_CSIR_resize_jpg_quality');
        
        if ($quality > 0 && $quality < 101){
            return $quality;
        }
        else{
            return 80;
        }
    }
    
    function get_resize_width(){
        $width = get_option('DevHouse_CSIR_resize_width');
        
        if($width) {
            return $width;
        }
        else {
            return get_option('large_size_w');
        }
    }
    
    function get_resize_height(){
        $height = get_option('DevHouse_CSIR_resize_height');
        
        if($height) {
            return $height;
        }
        else {
            return get_option('large_size_h');
        }
    }
    
    function resize_jpg_quality_validate_input($quality){
            
        $quality = absint($quality);
        
        if($quality > 0 && $quality < 101) {
                return $quality;
        }
        else {
            add_settings_error(
                'DevHouse_CSIR_resize_jpg_quality', 
                'DevHouse_CSIR_resize_jpg_quality_error',
                __('Invalid resize jpg quality! Value must be 1-100. A default value of 80 has been set.', 'client-side-image-resize'),
                'error'
            );
            return 80;
        }
            
    }

}

add_action('admin_init', create_function('', 'new DevHouse_Client_Side_Image_Resize();'));
