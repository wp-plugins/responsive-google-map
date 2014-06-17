<?php

/*
 * Plugin Name: Responsive Google MAP
 * Plugin URI: http://webomnizz.com
 * Description: Responsive Google MAP, Anywhere.
 * Version: 1.1
 * Author: Jogesh Sharma
 * Text Domain: responsive-gmap
 * Author URI: http://webomnizz.com/blog
 */

/*
    Copyright 2014 Jogesh Sharma (email: jogesh at webomnizz.com)
 
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
 */
    
    if( !class_exists('OM_Google_MAP') ){
        
        class OM_Google_MAP{
            
            
            private $settings;


            public function __construct() {
                
                // Enqueue Script
                add_action( "wp_enqueue_scripts", array( &$this, "gmap_srcitps") );
                add_filter('widget_text', 'do_shortcode');
                
                // Ajax Handle
                add_action( "wp_ajax_om-map", array( &$this, "settings_handler" ) );
                add_action( "wp_ajax_nopriv_om-map", array( &$this, "settings_handler" ) );
                
                // Initiate commen settings
                require( sprintf( '%s/settings.php', dirname(__FILE__)) );
                $this->settings = new Google_Map_Settings();
            }
            
            
            
            public function settings_handler(){
                
                if( ! wp_verify_nonce( $_POST['gmap_nonce'], 'om_gmap_nonce' ) ){
                    die('Un-trusted Request!');
                }
                
                $d = $this->settings->str2arr( $_POST['data'] );
                $data = array_map("urldecode", $d);
                
                if( ! get_option("om_gmap_settings") ){
                    update_option( "om_gmap_settings", $data );
                }
                else { update_option( "om_gmap_settings", $data ); }
                
                echo json_encode( $data );
                
                die(0);
            }
            
            
            public function activate_map(){}
            
            
            public function deactivate_map(){}            
            
            
            public function gmap_srcitps(){
        
                wp_enqueue_script( "google-map-v2", "https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false" );
                wp_register_script( "om-custom-gmap", plugin_dir_url(__FILE__) . 'js/om-gmap.js', array('jquery') );
                
                if( is_admin() ){
                    wp_enqueue_script( "om-custom-gmap" );
                }
                
            }
            
        }
        
    }
    
    
    if( class_exists('OM_Google_MAP') ){
        
        // Install and Un-Install hook
        register_activation_hook( __FILE__, array( "OM_Google_MAP", "activate_map" ));
        register_deactivation_hook( __FILE__, array( "OM_Google_MAP", "deactivate_map" ));
        
        $gmap = new OM_Google_MAP();
        
    }
?>