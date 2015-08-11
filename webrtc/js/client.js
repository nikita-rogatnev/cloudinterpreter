jQuery( document ).ready(function() {

    //socket = io.connect('http://78.140.138.8:1999');
//   socket = io.connect('http://call.cloudinterpreter.com');
//
//    socket.on('connect', function () {
//
//        var user_room = location.search && location.search.split('?')[1];
//        socket.emit('joinRoom',user_room);
//
//        socket.on('updatechat', function (data) {
//            document.querySelector('#log').innerHTML += data +'<br />';
//
//            $('#log').scrollTop(1E10);
//
//        });
//
//        $('#send').click(function() {
//
//            socket.emit('sendchat', $('#input').val());
//
//            $('#input').val('');
//         });
//
//        $('#input').keypress(function(event) {
//            if (event.which == '13') {
//
////                var room = location.search && location.search.split('?')[1];
////                socket.emit('joinRoom',room);
//
//                socket.emit('sendchat', $('#input').val());
//                $('#input').val('');
//            }
//        });
//    });

//
//    $('#emailForFreeMinutesButton').click(function() {
//
//        if(isEmailAddress($('#emailForFreeMinutes').val())) {
//            $.post( "action.php",
//                {
//                    email: $('#emailForFreeMinutes').val(),
//                    task: 'emailForFreeMinutes'
//                }
//            ).done(
//                function( data ) {
//                    alert(  data );
//                }
//            );
//        }
//        else {
//            alert('Введите правильный e-mail адрес');
//            return false;
//        }
//
//    });

});

function isEmailAddress(str) {
    var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    //var pattern = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;;
    if(!pattern.test(str)) {
        return false;
    }
    else {
        return true;
    }
}


function play_sound() {
    myAudio = new Audio('media/us-ring.mp3');
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

function play_tone() {
    myAudio = new Audio('media/dtmf-1.mp3')
    myAudio.play();
}

function add_video_control(id) {

    var volume_container = '<div id="'+id+'-volume-control" class="volume-control"><section><span id="'+id+'-tooltip" class="tooltip"></span><div id="'+id+'-slider" class="slider"></div><span id="'+id+'-volume" class="volume"></span></section></div>'

    //var volume_container = '<section><span id="'+id+'-tooltip" class="tooltip"></span><div id="'+id+'-slider" class="slider"></div><span id="'+id+'-volume" class="volume"></span></div>'

    //new element on the page
    jQuery('#'+id+'-container').append(volume_container);

    //alert(id)
    volume_control(id);

    add_timer();

}

function volume_control(id) {

    //Store frequently elements in variables
    var slider  = jQuery('#'+id+'-slider'),
        tooltip = jQuery('#'+id+'-tooltip');

    //Hide the Tooltip at first
    tooltip.hide();

    //Call the Slider
    slider.slider({
        //Config
        range: "min",
        min: 1,
        value: 100,

        start: function(event,ui) {
            tooltip.fadeIn('fast');
        },

        //Slider Event
        slide: function(event, ui) { //When the slider is sliding

            var value  = slider.slider('value'),
                volume = jQuery('#'+id+'-volume');


            document.getElementById(id).volume = value/100;

//            $('#'+id).volume = value/100;

            tooltip.css('left', value).text(ui.value);  //Adjust the tooltip accordingly

            if(value <= 5) {
                volume.css('background-position', '0 0');
            }
            else if (value <= 25) {
                volume.css('background-position', '0 -25px');
            }
            else if (value <= 75) {
                volume.css('background-position', '0 -50px');
            }
            else {
                volume.css('background-position', '0 -75px');
            };

        },

        stop: function(event,ui) {
            tooltip.fadeOut('fast');
            // console.log(document.getElementById(id).volume)
        }
    });

    setTimeout(function () {
        jQuery('#'+id+'-volume-control').show();
    }, 3000);

}



function publishVideo() {

    $('.OT_video-poster').toggle();

    if ( $('.OT_video-poster').is(":hidden") ) {
        webrtc.resumeLocalVideo();
        $('#pubVid').css('opacity','1');
    } else if ( $('.OT_video-poster').is(":visible") ) {
        webrtc.pauseLocalVideo();
        $('#pubVid').css('opacity','.2');
    }

}

function publishAudio() {

    if ( $('#pubAud').css("opacity") < 1 ) {
        webrtc.resumeLocalAudio();
        $('#pubAud').css('opacity','1');
    } else if ( $('#pubAud').css("opacity") == 1 ) {
        webrtc.pauseLocalAudio();
        $('#pubAud').css('opacity','.2');
    }

}


function add_timer() {

    if (!$(".countdown_row").length){

        jQuery('#defaultCountdown').countdown({
            until: +600,
            format: 'HMS',
            onTick: watchCountdown,
            compact: true,
            onExpiry: liftOff
        });

    };
}


function watchCountdown(periods) {

    if(periods[4] == 0 && periods[5] <= 10) {
        //alert('time is finishing')

        if ( !jQuery( "#defaultCountdown" ).is( "hasCountdownLight" ) ) {

            //$( "#defaultCountdown" ).show();

            jQuery( "#defaultCountdown" ).addClass( "hasCountdownLight" );

            jQuery(".timer-container, #defaultCountdown").effect("highlight", {}, 3000);

            //animateText();
        }
    }

    if(periods[4] == 0 && periods[5] <= 1) {
        //alert('time is finishing')

        if ( !jQuery( "#defaultCountdown" ).is( "hasCountdownLight" ) ) {
            animateText();
        }
    }


}

function liftOff() {
    alert('Время истекло')
}

var animateWasRun = false;

function animateText() {

    if(!animateWasRun) {

        //$('.timeIsOutText').show();
        $(".timeIsOutText").hide();
        setTimeout(function(){
            $(".timeIsOutText").show();
            setInterval(function(){
                $(".timeIsOutText").toggle();
            },500)},3000);

        animateWasRun = true;
    }

}




