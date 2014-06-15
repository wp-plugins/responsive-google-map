<?php

class Google_Map_Settings{
    
        
    public $style_string;
    
    public function __construct() {
              
        
        add_action( "admin_menu", array( &$this, "map_options" ));
        add_action( "admin_head", array( &$this, "gmap_integration_callback" ) );
        
        add_action( "admin_init", array( &$this, "gmap_button" ) );
        add_shortcode( "om_gmap", array( &$this, 'gmap_init' ), 20 );
        add_action( "wp_enqueue_scripts", array( &$this, 'map_settings' ) );
        
        add_action( "admin_enqueue_scripts", array( &$this, 'admin_scripts_styles' ) );
        add_action( "wp_footer", array(&$this, "om_wpcf_styles") );
        
        
    }
    
    
    public function gmap_init( $atts ){
        
        $options = get_option('om_gmap_settings');
        
        $Map_Type = isset($options['map_types']) ? "google.maps.MapTypeId." . strtoupper($options['map_types']) : "google.maps.MapTypeId.TERRAIN";
        $Scroll_Wheel = isset( $options['scrollzoom'] ) ? "false" : "true"; 
        $Draggable = isset( $options['draggable'] ) ? "true" : "false";
        $Marker_Status = isset( $options['marker'] ) ? true : false;
        $Circle = isset($options['draw_circle']) ? true : false;
        $Marker = isset( $options['om_marker'] ) ? plugin_dir_url(__FILE__) . "images/marker_" . $options['om_marker'] . ".png" : "";
        
        
        // Contact Form Stylesheet
        $this->style_string = $options['wpcf_css'];
                
        
        extract( shortcode_atts( array(
            'zoom'=>'9', 
            'lat'=>'28.9285745', 
            'lng'=>'77.09149350000007', 
            'mapHeight'=>'300', 
            'infowindow'=>'', 
            'wpcf'=>''
        ), $atts));
        
        if( !empty($infowindow) ) {
            $infowindow = str_replace("/\n/g", "<br>", $infowindow);
        }
        
        if( !empty($wpcf) ) {
            $form = '[contact-form-7 id="'.$wpcf.'" title="Contact form"]';
        }
        else {
            $form = "";
        }
        
        ?>
        <script>
            function initialize() {
                var mapOptions = {
                        zoom: <?php echo $zoom; ?>,
                        center: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),  
                        mapTypeId: <?php echo $Map_Type; ?>, 
                        mapTypeControl: false, 
                        streetViewControl: false, 
                        panControl: false,
                        scrollwheel: <?php echo $Scroll_Wheel; ?>, 
                        draggable: <?php echo $Draggable; ?>
                    };
                    
                var contentString = '<div><?php echo $infowindow; ?></div>';

                var map = new google.maps.Map(document.getElementById('location-canvas'), mapOptions);
                
                <?php if( $Marker_Status ): ?>
                var marker = new google.maps.Marker({
                                map: map,
                                draggable: false, 
                                <?php if( ! empty($Marker) ) { echo "icon: '{$Marker}', "; } ?>
                                position: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>)
                    });
                if( contentString !== "" && contentString.length > 0 ) {
                    var infoWindow = new google.maps.InfoWindow({ content: contentString, maxWidth: 200 });
                    google.maps.event.addListener(marker, 'click', function(){
                        infoWindow.open(map, marker);
                    });
                }
                <?php endif; ?>
                
                <?php if( $Circle ): ?>
                var metter = 1609.34;
                var circleBG = '<?php echo isset($options['circle_bg_color']) ? $options['circle_bg_color'] : '#ff0000' ?>';
                var stroke = '<?php echo isset($options['circle_border_color']) ? $options['circle_border_color'] : '#ff0000' ?>';
                var miles = <?php echo isset($options['circle_range']) && !empty($options['circle_range']) ? (float)$options['circle_range'] : 20; ?>;
                var mapCircle = {
                    strokeColor: stroke, 
                    strokeOpacity: 0.8, 
                    strokeWeight: 1, 
                    fillColor: circleBG, 
                    fillOpacity: 0.35, 
                    map: map, 
                    center: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
                    radius: (miles * metter)
                }
                
                // Add the circle for this city to the map.
                cityCircle = new google.maps.Circle(mapCircle);
                <?php endif; ?>
                
             }

             google.maps.event.addDomListener(window, 'resize', initialize);
             google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <?php
        
        $map = "<div id='om_container'><div id='location-canvas' style='".(empty($form) ? "height:{$mapHeight}px;" : "")."width:100%;margin-bottom:30px;'></div>";
        $map .= do_shortcode($form);
        $map .= "</div>";
        return $map;
        
    }
    
    
    
    public function om_wpcf_styles() {
        
        if( ! empty( $this->style_string ) ) :
        ?>
        <style type="text/css">
            <?php echo $this->style_string; ?>
        </style>
        <?php
        endif;
    }
    
    
    
    public function map_options(){
        add_options_page( "Google Map Options", "GMap Settings", "manage_options", "gmap_settings", array( &$this, "map_page_settings" ));
    }
    
    
    
    public function map_page_settings(){
        
        $options = get_option('om_gmap_settings');
        $GMAP_DIR = plugin_dir_url(__FILE__);
        
        
        include_once( sprintf( "%s/templates/map_options.php", dirname(__FILE__)) );
    }
    
    
    
    public function str2arr( $str ){
        
        $return = array();
        $exp = explode( "&", $str );
        
        foreach( $exp as $e ){
            $ex = explode( "=", $e );
            $return[$ex[0]] = $ex[1];
        }
        
        return $return;
        
    }
    
    
    
    public function register_map_button( $buttons ){
        array_push( $buttons, "|", "omgmap" );
        return $buttons;
    }


    public function add_map_plugin( $plugin_array ){
        
        global $pagenow;
        
        if( is_admin() && $pagenow == "post.php" || $pagenow == "post-new.php" ){
            $plugin_array['omgmap'] = plugin_dir_url(__FILE__) . '/js/om-gmap.js';
            return $plugin_array;
        }
        
    }
    

    /**
     * Load CSS and JS file
     * for particular section in 
     * admin panel
     * 
     * @global object $current_screen
     */
    public function admin_scripts_styles(){
        
        global $current_screen;
        $Settings_Params = array(
            'ajaxurl'=>admin_url('admin-ajax.php'), 
            'gmap_nonce'=>wp_create_nonce('om_gmap_nonce')
        );
        
        wp_register_script( 'om-admin-ui-js', plugin_dir_url(__FILE__) . 'js/tabs.min.js');
        wp_register_script( "om-settings", plugin_dir_url(__FILE__) . 'js/settings.js');
        
        if( $current_screen && ( $current_screen->base == "edit" || $current_screen->base == "post" ) ){
            wp_enqueue_script("om-admin-ui-js");
            wp_enqueue_style( "om-tabs-ui", plugin_dir_url(__FILE__) . 'style/map-ui.css' );
        }
        
        if( $current_screen && $current_screen->base == "settings_page_gmap_settings" ){
            wp_enqueue_style( "om-map-settings", plugin_dir_url(__FILE__) . 'style/map_settings.css' );
            wp_enqueue_script( "om-settings" );
            wp_localize_script( "om-settings", 'OM', $Settings_Params);
        }
    }




    /**
     * Attach GMAP button 
     * on Editor
     * 
     * @return void
     */
    public function gmap_button(){
        
        if( !current_user_can("edit_posts") && !current_user_can("edit_pages") ){
            return;
        }
        
        
        if( get_user_option("rich_editing") == true ){
            
            global $pagenow;
            if( $pagenow == "post.php" || $pagenow == "post-new.php" ){
                add_filter( "mce_external_plugins", array( &$this, 'add_map_plugin' ) );
                add_filter( "mce_buttons", array( &$this, 'register_map_button' ) );
            }
            
        }
    }


    public function map_settings(){
        wp_enqueue_style("om-gmap", plugins_url("/style/om-map.css", __FILE__));
    }
    
    public function gmap_integration_callback() {
        global $current_screen;
        
        $type = $current_screen->post_type;
        $options = get_option('om_gmap_settings');
        
        if( is_admin && ( $type == "post" || $type == "page" ) ) {
            $status = is_plugin_active("contact-form-7/wp-contact-form-7.php") ? "y" : "n";
            ?>
            <script>
                var WPCF = '<?php echo $status; ?>';
            </script>
            <?php
        }
    }
      
}

?>