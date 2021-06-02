$(document).ready(function() {
    'use strict';
    //to show login form or signup form
    $('.login-page h4 span').click(function(){

        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);

    });

    //dashboard
    $('.toggle-info').click(function(){

            $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
            if($(this).hasClass('selected')){

                $(this).html('<i class="fa fa-minus"></i>');
            }else{
                $(this).html('<i class="fa fa-plus"></i>');
            }
    });

    //hide placeholder in focus
    $('[placeholder]').focus(function(){

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    //for delete button
    $('.confirm').click(function(){
        return confirm('Are you sure?');

    });


    
});