<div class="wrap">
    <h2 id="gmap_title" class="gmap_title">Google Map Settings</h2>
    
    <h2 class="om_tab nav-tab-wrapper">
        <a class="nav-tab nav-tab-active" href="#settings">Settings</a>
        <a class="nav-tab" href="#icons">Icons</a>
    </h2>
    <form id="gmapSettings" method="post" action="">
        <div id="settings">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="map_types">MAP Types</label></th>
                    <td>
                        <select name="map_types" id="map_types">
                            <?php
                                $Map_Types = array(
                                    'Roadmap', 'Satellite', 'Hybrid', 'Terrain' 
                                );
                                
                                foreach( $Map_Types as $map_type ){
                                    if( isset( $options ) && $options['map_types'] == $map_type ){
                                        echo "<option value='{$map_type}' selected=''>{$map_type}</option>";
                                    }
                                    else{
                                        echo "<option value='{$map_type}'>{$map_type}</option>";
                                    }
                                }
                            ?>
                        </select>
                        <span>Select Map Type</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="scrollzoom">Scroll Zoom</label></th>
                    <td>
                        <label for="users_can_register">
                            <?php if( isset($options) && $options['scrollzoom'] == "1" ): ?>
                            <input type="checkbox" value="1" checked="" id="scrollzoom" name="scrollzoom">
                            <?php else: ?>
                            <input type="checkbox" value="1" id="scrollzoom" name="scrollzoom">
                            <?php endif; ?>
                            Disable zoom in/out over map.
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="scrollzoom">Drag Map</label></th>
                    <td>
                        <label for="users_can_register">
                            <?php if( isset( $options['draggable'] ) ): ?>
                            <input type="checkbox" value="1" checked="" id="draggable" name="draggable">
                            <?php else: ?>
                            <input type="checkbox" value="1" id="draggable" name="draggable">
                            <?php endif; ?>
                            Enable Draggable MAP.
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="marker">Enable Marker</label></th>
                    <td>
                        <label for="enable_marker">
                            <?php if( isset( $options['marker'] ) ): ?>
                            <input type="checkbox" value="1" checked="" id="marker" name="marker">
                            <?php else: ?>
                            <input type="checkbox" value="1" id="marker" name="marker">
                            <?php endif; ?>
                            Enable Marker on Map
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="marker">Draw Circle</label></th>
                    <td>
                        <label for="enable_marker">
                            <?php if( isset( $options['draw_circle'] ) ): ?>
                            <input type="checkbox" value="1" checked="" id="draw_circle" name="draw_circle">
                            <?php else: ?>
                            <input type="checkbox" value="1" id="draw_circle" name="draw_circle">
                            <?php endif; ?>
                            Draw Circle around Lat,Lng point
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="circlerange">Circle Range</label></th>
                    <td>
                        <input type="text" size="5" value="<?php echo isset($options['circle_range']) ? $options['circle_range'] : "10" ?>" name="circle_range" id="circle_range">
                        <select name="range_unit" id="range_unit">
                            <option value="miles">Miles</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="circlerange">Circle Background Color</label></th>
                    <td>
                        <input type="text" size="10" value="<?php echo isset($options['circle_bg_color']) ? $options['circle_bg_color'] : "#FF0000" ?>" name="circle_bg_color" id="circle_bg_color">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="circlerange">Circle Border Color</label></th>
                    <td>
                        <input type="text" size="10" value="<?php echo isset($options['circle_border_color']) ? $options['circle_border_color'] : "#FF0000" ?>" name="circle_border_color" id="circle_border_color">
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
        
        <div id="icons">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="scrollzoom">Marker List(s)</label></th>
                    <td>
                        <div class="map_markers">
                            <ul>
                                <li>
                                    <div class="marker <?php echo isset( $options['om_marker'] ) && $options['om_marker'] == "1" ? "active" : ""; ?>">
                                        <img src="<?php echo $GMAP_DIR; ?>images/marker_1.png" />
                                        <?php if( isset( $options['om_marker'] ) && $options['om_marker'] == "1" ): ?>
                                        <input type="radio" style="display:none;" checked="" value="1" name="om_marker"/>
                                        <?php else: ?>
                                        <input type="radio" style="display:none;" value="1" name="om_marker"/>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
                
        <p class="submit" style="width:150px;">
            <input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
            <span class="spinner"></span>
        </p>
    </form>
</div>