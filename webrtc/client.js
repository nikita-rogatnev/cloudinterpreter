jQuery( document ).ready(function() {
   // jQuery('#block-new84').prop("disabled",false);


        jQuery( "#dialog" ).dialog({
            autoOpen: false,
            close: function( event, ui ) {
                socket.emit('userCancelled');
                //myAudio.pause();
                //jQuery('#block-new84').prop("disabled",false);
            },
            open: function(event, ui) {
                jQuery('.ui-dialog').css('z-index',1000);
                jQuery('.ui-dialog-titlebar .ui-icon-closethick').css('margin', -8 + 'px');
            }
        });


        socket = io.connect('http://78.140.138.8:8999');

        socket.on('connect', function () {
            socket.on('gotAnswer', function (data) {
                 document.location = 'http://dev.cloudinterpreter.tst/ru/?option=com_videotranslation&view=session&ses_id='+data.user_id+'&order_id=0&usertype=4&Itemid=155';
            });
            jQuery('#block-new84').click(function() {
                //jQuery('#render_new84').prop("disabled",true);

                socket.emit('call');

                jQuery( "#dialog" ).dialog( "open" );

                //play_sound();
                return false;
            });

        });
		
        

});


function play_sound() {
    myAudio = new Audio('reloadvery_a2tjcpsv.ogg');
    if (typeof myAudio.loop == 'boolean')
    {
        myAudio.loop = true;
    }
    else
    {
        myAudio.addEventListener('ended', function() {
            this.currentTime = 0;
            this.play();
        }, false);
    }
    myAudio.play();
}



