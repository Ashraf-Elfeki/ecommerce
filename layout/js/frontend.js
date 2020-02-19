/*global $*/
$(function () {
    'use strict';

    /* Live Preview For Adding Item*/
    $(".live").keyup(function(){
        $($(this).data('class')).text($(this).val());
    });

    /*
     $(".live-name").keyup(function(){
            $(".live-preview .caption h3").text($(this).val());
    });

    $(".live-desc").keyup(function(){
            $(".live-preview .caption p").text($(this).val());
    });

    $(".live-price").keyup(function(){
            $(".live-preview .price-tag").text('$'+$(this).val());
    });
  
  */
 
    /* Switch Between Login And SignUp Form */
    $(".login-page h1 span").click(function(){
        $(this).addClass('selected').siblings('span').removeClass('selected');
        $('.login-page form').hide();
        $($(this).data('class')).fadeIn(100);
    });


    /* Fire The Select Box Plugin*/	
    $("select").selectBoxIt({
        autoWidth: false,
        theme: "jqueryui"
    });


    /* Place Holder*/
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function() {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });
    
    /* Set Asteric Class for required Fields*/
    $('input').each(function () {
        if ($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });


    /* Confirm message*/
    $('.confirm').click(function (){
        return confirm("Are You Sure");
    });
    
    
});



	
