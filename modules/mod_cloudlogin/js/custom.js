jQuery(document).ready(function() {

    //showDialogWindow : function (type) {
    jQuery('.login-link-header').click(function() {
        jQuery('#timeline-window').timeline('showDialogWindow');

    });

    //for disable links
    jQuery('.help-link-header').bind('click', false);

    jQuery('.userprofile ul').removeClass( "nav menu" );
    jQuery('.userprofile ul li a').addClass( "item" );
    jQuery('.languageslist ul li a').addClass( "item" );


    menuResize();
    jQuery(window).resize(function() {
        menuResize();
    });

});

function menuResize() {
    var width = jQuery(window).width();

    //alert(width)

    if(width < 960) {
        jQuery('.gf-menu.l1 > li > .item').css('padding','0 4px');
        jQuery('.logo-block').parent().css('width',253);
        jQuery('#rt-top .rt-container .rt-omega').css('width',193);
        jQuery('#rt-top .rt-container').css('width',1019);
    }

    if(width < 1202 && width > 960) {
        jQuery('.gf-menu.l1 > li > .item').css('padding','0 14px');
        jQuery('.logo-block').parent().css('width',253);
        jQuery('#rt-top .rt-container').css('width',1019);
        jQuery('#rt-top .rt-container .rt-omega').css('width',266);
    }

    if(width > 1202 ) {
        jQuery('#rt-top .rt-container .rt-omega').css('width',300);
        jQuery('#rt-top .rt-container').css('width','100%');
        jQuery('.gf-menu.l1 > li > .item').css('padding','0 25px');
    }

}

