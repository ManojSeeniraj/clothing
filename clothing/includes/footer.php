</div>
<footer class="text-center" id="footer">&copy; copyrights 2014-2018 Mano's clothing</footer>












<script>
    jQuery(window).scroll(function(){
        var vscroll=jQuery(this).scrollTop();
        jQuery('#logoText').css({
            "transform" : "translate(0px,"+vscroll/2+"px)"
        });

        var vscroll=jQuery(this).scrollTop();
        jQuery('#background-flower').css({
            "transform" : "translate(-"+vscroll/5+"px, -"+vscroll/12+"px)"
        });

        var vscroll=jQuery(this).scrollTop();
        jQuery('#front-flower').css({
            "transform" : "translate(0px,-"+vscroll/2+"px)"
        });
    });



    function detailsmodal(id){
     var data = { "id" : id};
     jQuery.ajax({        
     url :  '/clothing/includes/modalDetails.php',
     method : "post",
     data : data,
     success:function(data){
         jQuery('body').append(data);
         jQuery('#details-modal').modal('toggle');//the area where the data is poped out get displayed :)
     },
     error :function(){

         alert("something went wrong!");
     }
    });
    }



</script>


</body>
    </html>