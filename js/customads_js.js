
var regexp = /^\d{0,4}(\.\d{0,2})?$/;
//Code for Employee
//Send subscribe email listing and send notification mail.
function leaveChange() {
    var id= document.getElementById("zipcode").value;
    jQuery('#sendZipcode').val(id);
    jQuery.ajax({
        url: jQuery('#pluginPath').val()+'?id='+id,
        dataType: '',
        type: 'POST',
        success: function(e) {
            var value = JSON.parse(e);
            jQuery("#emailaddress").val(value);
        }
    });
}
jQuery(document).ready(function() { 
    //Subscribe Form validation.//
    jQuery('#submit_subscribe').click(function(){       
        var error = validateAll();
        // var email = jQuery('#emailaddress').val();
        // var sbjct = jQuery('#subject').val();
        // var  msg= jQuery('#message').val();

        if(error==0){

            jQuery('#subform1').submit();
            //     var dataString = 'email='+email+'&subject='+sbjct+'&message='+msg;
            //      jQuery.ajax({
            //        url: jQuery('#pluginPath').val()+'?email='+email+'&subject='+sbjct+'&message='+msg,
            //        dataType: '',
            //        type: 'POST',
            //        success: function(e) {
            //             if(e==1){
            //                 alert("Email send failed.");
            //             }
            //             else{
            //                 alert("Email send successfully.");
            //             }
            //         } 
            //     });
        }

    });

    /* Display send subscribe form*/
    jQuery("#sendSubscribe").click(function(){
        jQuery('.subscribeForm').show();
    });


    jQuery('#publish_employee').click(function(){
        error = validateAll();
        if(error==0){
            if(jQuery('#uid').val()!=''){
                //Update code
                //ADD Code
                var dataString = 'checkEmailValidate=1&uid='+jQuery('#uid').val()+'&emailaddress='+jQuery("#employee_emailaddress").val();
            }
            else{
                //ADD Code
                var dataString = 'checkEmailValidate=1&emailaddress='+jQuery("#employee_emailaddress").val();
            }
            jQuery.ajax({
                type: "POST",
                url: jQuery('#siteurl').val(),
                data: dataString,
                cache: true,
                success: function(html){
                    if(html==1){
                        alert("Email address already exists. Please try again with different email address");
                        return false;
                    }
                    else{
                        jQuery('#post').submit();                
                    }
                } 
            });
            return false;
        }
    });
    //Global Code//
    jQuery('#publish').click(function(){       
        var error = validateAll();
        if(error==0){
            jQuery('#post').submit();    
        }
    });

    jQuery('#updatestatus').click(function(){
        var index = 0;
        var files = jQuery("#order_imagesdata")[0].files;
        if(jQuery('#order_select').val()=='' || jQuery('#order_select').val()==null){
            index = 1;
            alert("Please Select Order Status.");
            return false;
        }
        else if(jQuery('#order_comments').val()==''){
            index = 1;
            alert("Please Add Order Comments.");
            return false;
        }

        if(files.length>0){
            for (var i = 0; i < files.length; i++){

                var img = files[i].name.split(".");
                if(img[img.length-1]!='jpg' && img[img.length-1]!='JPG'){
                    index = 1;
                    alert("Please Upload Image type with JPG only.");
                    return false;
                }
                else if(parseInt(files[i].size)>parseInt(5000000)){
                    index = 1;
                    alert("Please Upload Image less than 5 MB only.");
                    return false;    
                }
            }

        }
        if(index == 0){
            jQuery('#orderstat').submit();
        }

    });
    jQuery('#getorder').click(function(){
        if(jQuery('#order_id').val()=='')
            {
            alert("Please Enter Order Id.");
        }
        else
            {
            jQuery('#getorderdata').submit();
        }
    });
    jQuery('.cancleorder').click(function(){  
        jQuery('#cancleorder').submit();
    });
   

    jQuery('.numberbox').keydown(function(event) {
        // Allow special chars + arrows 
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 
        || event.keyCode == 27 || event.keyCode == 13 
        || (event.keyCode == 65 && event.ctrlKey === true) 
        || (event.keyCode >= 35 && event.keyCode <= 39)){
            return;
        }else {
            // If it's not a number stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });
    function validateAll()
    {              
        var error = 0;   
        jQuery( ".boxclass" ).each(function() {
            jQuery( this).children('.errormessage').text('');
        }); 

        jQuery( ".selectclass" ).each(function() {
            jQuery( this).children('.errormessage').text('');
        });
        var v2 = jQuery.trim(jQuery( ".boxclass").children('.length').val());
        if(v2=='' ){
            jQuery( ".boxclass").children('.errortitle').hide();   
            jQuery( ".boxclass").children('.length').after("<p class='errortitle' style='color: red; font-weight: bold;'>This Field is Required. </p>");                        
            error=1;
        }else if(v2.length<3 || v2.length>50){
            jQuery( ".boxclass").children('.errortitle').hide();
            jQuery( ".boxclass").children('.length').after("<p class='errortitle' style='color: red; font-weight: bold;'>Please Enter Between 3 to 50 Characters.</p>");                        
            error=1;
        }
        else{
            jQuery( ".boxclass").children('.errortitle').hide();                        
        }   


        jQuery( ".boxclass" ).each(function() {
            var vl = jQuery.trim(jQuery( this).children('.requiredbox').val());
            jQuery( this).children('.requiredbox').val(vl);
            if(jQuery( this).children('.requiredbox').val()==''){
                jQuery( this).children('.errormessage').text("This Field is Required.");
                jQuery( this).children('.errormessage').show();                        
                error=1;
            }
            else{
                jQuery( this).children('.errormessage').hide();                        
            }
        });

        jQuery( ".currencybox" ).each(function() {
            if(jQuery( this).siblings('.errormessage').text()==''){
                if (!regexp.test(jQuery( this).val())) {
                    jQuery( this).siblings('.errormessage').text("Invalid Currency Format.");
                    jQuery( this).siblings('.errormessage').show();                            
                    error=1;
                }
                else{
                    jQuery( this).children('.errormessage').hide();                        
                }
            }
        });

        jQuery( ".gridcurrencybox" ).each(function() {   

            if (!regexp.test(jQuery( this).children('.pricebox').val()) && jQuery( this).children('.pricebox').val()!=0) {
                jQuery( this).children('.pricebox').addClass("borderpricegrid");
                error=1;
            }
            else{
                jQuery( this).children('.pricebox').removeClass("borderpricegrid");
            }
        });

        jQuery( ".emailbox" ).each(function() {

            if(jQuery( this).siblings('.errormessage').text()==''){
                if (!validateEmail(jQuery( this).val())){
                    jQuery( this).siblings('.errormessage').text("Invalid Email Address Format.");
                    jQuery( this).siblings('.errormessage').show();                            
                    error=1;
                }
                else{
                    jQuery( this).children('.errormessage').hide();                        
                }
            }
        });


        jQuery( ".selectclass" ).each(function() { 
            //if(jQuery( this).children('.errormessage').text()==''){
            if (jQuery( this).children('.errormessage').text()=='' && jQuery( this).children('select').val()==''){
                jQuery( this).children('.errormessage').text("Please Select Option.");
                jQuery( this).children('.errormessage').show();                            
                error=1;
            }
            //}
        });  
        return error;
    }
});
// Function that validates email address through a regular expression.
function validateEmail(sEmail) {
    var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}
