(function($){
    $(document).ready(function(){
        
        // Submit Settings
        $('#gmapSettings').submit(function(e){
            e.stopPropagation();
            
            var $this = $(this);
            $('#submit').attr('disabled', true);
            
            $.ajax({
                url: OM.ajaxurl, 
                type: 'POST', 
                data: { action: "om-map", gmap_nonce: OM.gmap_nonce, data: $this.serialize() }, 
                dataType: 'json', 
                success: function(response){
                    $('#submit').removeAttr('disabled');
                }
            });
            
            return false;
        });
        
        
        
        // Tab Settings
        $('.om_tab a').click(function(e){
            e.preventDefault();
            
            $('#gmapSettings > div').hide();
            $('.om_tab a').removeClass('nav-tab-active');
            
            var ID = $(this).attr('href');
            $(this).addClass('nav-tab-active');
            $(ID).show();
        });
        
        
        
        // Hover Effect
        $('.map_markers li').hover(
            function(){
                $('.marker', this).addClass('hover');
            }, 
            function(){
                $('.marker', this).removeClass('hover');
            }
        ).click(function(e){
            e.preventDefault();
            
            $('.map_markers li .marker').removeClass('active');
            $('.map_markers li input[type="radio"]').removeAttr('checked');
            $('.marker', this).addClass('active');
            $('.marker input[type="radio"]', this).attr("checked", true);
        });
        
    });
})(jQuery);