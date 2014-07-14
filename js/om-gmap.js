(function($){
    tinymce.create('tinymce.plugins.omgmap', {
        init: function(ed, url){
            ed.addButton('omgmap', {
                title: 'OM Gmap', 
                image: url + '/map_16.png', 
                onclick: function(){
                    tb_show( 'GMAP Shortcode', '#TB_inline?width=640&inlineId=gmap-options' );
                }
            });
        }, 
        createControl: function(n, cm){
            return null;
        }, 
        getInfo: function(){
            return {
                longname : "OM Gmap",
                author : 'Jogesh Sharma',
                authorurl : 'http://webomnizz.com',
                infourl : 'http://webomnizz.com',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add( "omgmap", tinymce.plugins.omgmap);
    
    
    $(document).ready(function(){
        
        var styles = {'_retro':'Retro', '_blue_water':'Blue Water', '_subtle_gray':'Subtle Gray', 
                        '_midnigh_commander':'Midnight Commander', '_light_monochrome':'Light Monochrome', 
                        '_maps_esque':'Maps Esque', '_flat_map':'Flat Map', '_map_box':'Map Box', 
                        '_bright_bubbly':'Bright Bubbly', '_icy_blue':'Icy Blue', '_hopper':'Hopper', '_cobalt':'Cobalt', 
                        '_red_hues':'Red Hue', '_nature':'Nature', '_red_alert':'Red Alert', '_bluish':'Bluish', 
                        '_blue_essence':'Blue Essence', '_souldisco':'Souldisco', '_vintage_blue':'Vintage Blue', 
                        '_hot_pink':'Hot Pink', '_vitamin_c':'Vitamin C', '_mixed':'Mixed', '_neon_world':'Neon World', 
                        '_muted_blue':'Muted Blue'}, 
            options = '';
              
        for( var k in styles ) {
            options += '<option value="'+k+'">'+styles[k]+'</option>';
        }
        
        var mapForm = '<div id="gmap-options">\n\
            <ul class="nav nav-tabs">\n\
                <li class="active"><a href="#address" data-toggle="tab">Address</a></li>\n\
                <li><a href="#latlng" data-toggle="tab">LatLng</a></li>\n\
                <li><a href="#custSettings" data-toggle="tab">Custom Settings</a></li>';
        /*if( WPCF === "y" ) {
            mapForm += '<li><a href="#wpcf" data-toggle="tab">Contact Form</a></li>';
        }*/
        mapForm += '</ul>\n\
            <table style="position:absolute;top:35px;right:30px;height:40px;>\n\
                <tr>\n\
                    <td align="left">&nbsp;</td>\n\
                    <td align="right">\n\
                        <input type="button" class="button button-primary button-large" value="Insert" name="map_insert" /></td>\n\
                </tr>\n\
            </table>\n\
            <div class="tab-content">\n\
                <div class="tab-pane" id="custSettings">\n\
                    <table id="gmap-table" cellpadding="5" cellspacing="2">\n\
                        <tr>\n\
                            <td valign="top" width="100"><label>Marker URL:</label></td>\n\
                            <td>\n\
                                <input type="text" style="width:300px;" id="map_cMarker" name="map_cMarker" />\n\
                                <p class="description" style="color:#ff0000;">Full Image URL, Best Size 22x30</p></td>\n\
                        </tr>\n\
                    </table>\n\
                </div>\n\
                <div class="tab-pane" id="latlng">\n\
                    <table id="gmap-table" cellpadding="5" cellspacing="2">\n\
                        <tr>\n\
                            <td width="100"><label>Zoom:</label></td>\n\
                            <td><input type="text" size="5" id="map_zoom" name="map_zoom" value="9" /></td>\n\
                        </tr>\n\
                        <tr>\n\
                            <td><label>Lat:</label></td>\n\
                            <td><input type="text" size="20" id="map_lat" name="map_lat" value="" /></td>\n\
                        </tr>\n\
                        <tr>\n\
                            <td><label>Lng:</label></td>\n\
                            <td><input type="text" size="20" id="map_lng" name="map_lng" value="" /></td>\n\
                        </tr>\n\
                    </table>\n\
                </div>\n\
                <div class="tab-pane active" id="address">\n\
                    <table id="gmap-address" cellpadding="5" cellspacing="2">\n\
                        <tr>\n\
                            <td width="160"><label>Enter your address:</label></td>\n\
                            <td>\n\
                                <input size="30" type="text" name="map-address" id="map-address" />\n\
                                <input type="button" class="button" value="Get Lat Lng" id="get_latlng" name="get_latlng" />\n\
                            </td>\n\
                        </tr>\n\
                        <tr>\n\
                            <td width="160" valign="top"><label>Info Window</label></td>\n\
                            <td>\n\
                                <textarea style="resize:none;" row="6" cols="40" name="info-window-address" id="info-window-address"></textarea>\n\
                            </td>\n\
                        </tr>\n\
                    </table>\n\
                </div>\n\
                <div class="tab-pane" id="wpcf">\n\
                    <table id="gmap-address" cellpadding="5" cellspacing="2">\n\
                        <tr>\n\
                            <td width="160" valign="top"><label>Contact form Shortcode ID:</label></td>\n\
                            <td>\n\
                                <input type="text" size="5" name="wpcf_shortcode" id="wpcf_shortcode"> <span style="position: absolute; left: 270px; overflow: hidden; width: 75px;"><img src="//'+document.location.hostname+'/wp-content/plugins/responsive-google-map/images/contact_form_id.png"></span>\n\
                            </td>\n\
                        </tr>\n\
                    </table>\n\
                </div>\n\
                <div style="position:absolute;left:40%;bottom:5%;"><h4 style="width:150px;margin:0 auto;position:relative;top:10px;text-align:center;">Please consider to making a small amount of donation.</h4><a href="http://bit.ly/1paGVHd" target="_blank"><p><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif"></p></a>\n\</div>\n\
            </div>\n\
        </div>';
        
        $(mapForm).appendTo('body').hide();
        
        $('#get_latlng').click(function(e){
            e.preventDefault();
            
            var $this = $(this);
            var GMap = 'http://maps.googleapis.com/maps/api/geocode/json';
            var User_Add = $.trim($('#map-address').val());
            
            $this.attr('disabled', true);
            
            if( User_Add.length > 0 ){
                $.getJSON( GMap, {
                    address: User_Add, 
                    sensor: true
                }).done(function(response){
                    $this.removeAttr('disabled');
                    
                    if( response.status == "OK" ){
                        $.each( response.results, function(i, data){
                            $('#map_lat').val( data.geometry.location.lat );
                            $('#map_lng').val( data.geometry.location.lng );
                        } );
                    }
                });
            } 
        });
        
        $('input[name="map_insert"]').click(function(){
            var zoom = $('#map_zoom').val(), 
                lat = $.trim( $('#map_lat').val() ).length > 0 ? $.trim( $('#map_lat').val() ) : "28.9285745", 
                lng = $.trim( $('#map_lng').val() ).length > 0 ? $.trim( $('#map_lng').val() ) : "77.09149350000007", 
                infowindow = $.trim( $('#info-window-address').val() ), 
                customMarker = $.trim( $('#map_cMarker').val() ), 
                styles = $('#map_styles').val(); 
                //wpcf = $.trim($('#wpcf_shortcode').val());
                
            /*if( ! (/^[0-9]+$/).test(wpcf) ){
                wpcf = "";
            }*/
            
            var omCMD = '[om_gmap zoom="'+zoom+'" lat="'+lat+'" lng="'+lng+'" ';
                omCMD += infowindow == "" ? "" : 'infowindow="'+infowindow+'" ';
                omCMD += customMarker == "" ? "" : 'marker="'+customMarker+'" ';
                omCMD += styles == "" || styles == undefined ? "" : 'styles="'+styles+'"'
                omCMD += ']';
                
            tinyMCE.activeEditor.execCommand('mceInsertContent', false, omCMD);
            tb_remove();
        });
        
        $('#myTab a').click(function(e){
            e.preventDefault();
            $(this).tab("show");
        });
        
    });
    
})(jQuery);