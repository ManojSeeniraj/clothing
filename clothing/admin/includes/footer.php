</div>
<footer class="col-md-12 text-center" id="footer">&copy; copyrights 2014-2018 Mano's clothing</footer>


<script>


function updateSize(){
 
 
 var sizeString= '';

 for(var i=1;i<=12;i++){

     if(jQuery('#size'+i).val()!= ''){
         sizeString += jQuery('#size'+i).val()+':'+ jQuery('#qty'+i).val()+',';
     } 
 }
 jQuery('#sizes').val(sizeString);
}






function get_child_option(selected){

    if(typeof selected==='undefined'){
        var selected='';
    }

    var parentID=jQuery('#parent').val();

    jQuery.ajax({

        url:'/clothing/admin/parsers/child_categories.php',
        type:'post',
        data:{parentID:parentID,selected:selected},
        success:function(data){
            jQuery('#child').html(data);
        },
        error:function(){
            alert("something went wrong when collecting the information");
        },
    });

}

jQuery('select[name="parent"]').change(function(){

get_child_option();
});

</script>

</body>
</html>


