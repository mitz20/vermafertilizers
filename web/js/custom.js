/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function (event) {
    $('#add-product-form').on('beforeSubmit',function(e) {
        e.preventDefault();
        if ($(this).find('.has-error').length > 0) {
            alert('error');
            return false;
        }
        var form = $(this);
        $.ajax({
            type: "POST",
            async: false,
            url: form.attr('ajaxUrl'),
            data: form.serialize(),
            success: function (response) {
                window.response = response;
            }
        });
        if (window.response == 0) {
            prodObj = $('.field-addproductform-product_id');
            proClass = prodObj.attr('class');
            newclass = proClass.replace('has-success', 'has-error');
            prodObj.attr('class', newclass);
            $('.field-addproductform-product_id .help-block').html('Product Id must be unique.');
            return false;
        }
        return true;
    });
    
    $('#product-edit-button').click(function(){
//        alert();
        $('#productform-name').removeAttr('disabled');
        $('#productform-units').removeAttr('disabled');
        $('#productform-price').removeAttr('disabled');
        $('#product-edit-button').css('display','none');
        $('#product-submit-button').removeAttr('style');
    });
    
});

