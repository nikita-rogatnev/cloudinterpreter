var stat_marker = false;
jQuery( document ).ready(function() {

    //block module
    jQuery('body').block({
        message: 'Подключение к серверу',
        css: { border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff',
            font_size: '10px'
        },
        onBlock: function() {}
    });

    //socket = io.connect('http://call.cloudinterpreter.com');
	socket = io.connect('http://78.140.138.8:1999');

    socket.on('connect', function () {

        socket.emit('switchRoom','visitor'); //visitor by default

        jQuery('body').unblock();

        socket.on('gotAnswer', function (data) {

            jQuery('#busyOperators').modal('hide');
            console.log('gotAnswer: ',data);

            room = data.user_id;


            jQuery('#myModal').modal({
                backdrop: 'static'
            });
        });
		
		//socket.emit('GET_CONNECTED_USERS');
		
		socket.on('CONNECTED_ENGLISH_USERS', function(data){
		    console.log('connected english operators: ',data);
			
            var countOnline = 0;
			//var countBusy = 0;

            for(var k in data) {
              countOnline++;
            }

            jQuery('#interpretersInfo').html(countOnline);
			//jQuery('#interpretersBysyInfo').html(countBusy);
		});

        socket.on('CONNECTED_SIGN_USERS', function(data){
            console.log('connected sign operators: ',data);

            var countOnline = 0;
            //var countBusy = 0;

            for(var k in data) {
                countOnline++;
            }

            jQuery('#signInterpretersInfo').html(countOnline);
            //jQuery('#interpretersBysyInfo').html(countBusy);
        });

        socket.on('reloadPage', function() {
            location.reload();
        });


        socket.on('updatechat', function (data) {
             document.querySelector('#log').innerHTML += data +'<br />';
         });

         jQuery('#send').click(function() {

             user_room = socket.socket.sessionid;
             socket.emit('joinRoom',user_room);
             socket.emit('sendchat', jQuery('#input').val());

             jQuery('#input').val('');
         });

        jQuery('#input').keypress(function(event) {
            if (event.which == '13') {
                user_room = socket.socket.sessionid;
                socket.emit('joinRoom',user_room);
                socket.emit('sendchat', jQuery('#input').val());
                jQuery('#input').val('');
            }
        });
//        window.onbeforeunload = function() {
//            socket.emit('forceDisconnect');
//            return ''; //here also can be string, that will be shown to the user
//        }
jQuery('#emailForFreeMinutesButton').click(function() {

        if(isEmailAddress(jQuery('#emailForFreeMinutes').val())) {
            jQuery.post( "action.php",
                {
                    email: jQuery('#emailForFreeMinutes').val(),
                    task: 'emailForFreeMinutes'
                }
            ).done(
                function( data ) {
                    alert(  data );
                }
            );
        }
        else {
            alert('Введите правильный e-mail адрес');
            return false;
        }

    });

		
    });

jQuery('.call-btn,.cancel-call-btn').click(function() {
    var infoAboutCart = jQuery('#timeline-window').timeline('checkIfSomethingInSession');

    if (!not_login(infoAboutCart)) {
            jQuery("#dialog-modal-registration").dialog({
                height: 390,
                modal: true,
                dialogClass: "DialogWindowAlert"
           }); 
    } else if (!balance_zero(infoAboutCart)) {
        alert('У Вас должен быть положительный баланс на счету');
    } else {

        bowser = jQuery.browser;
        if(bowser.chrome == true || bowser.firefox == true || bowser.opera == true) {

        }
        else {
            alert('Сервис стабильно работает в браузере Google Chrome. Скачать его можно здесь: https://www.google.com/chrome');
            return false
        }

        //jQuery(".call-btn").toggle();
        //jQuery(".cancel-call-btn").toggle();

        var clickedButtonId = $(this).id;



        if(jQuery(this).hasClass('call-btn') ) {

            sendStat_start(socket.socket.sessionid);

            if(clickedButtonId == 'call_interpreter_now') {
                if(parseInt(jQuery("#interpretersInfo").html()) == 0) {
                    //alert("переводчик занят или рабочий день закончен");
                    //return false;

                    jQuery('.modal-body #infoAboutMicContent').hide();
                    jQuery('.modal-footer #newRoomLink').hide();
                    jQuery('#busyOperators').modal();


                    sendStat_busy(socket.socket.sessionid);
                }
                else  socket.emit('call','online english');
            }

            if(clickedButtonId == 'call_sign_language_now') {
                if(parseInt(jQuery("#signInterpretersInfo").html())== 0 ) {
                    //alert("переводчик занят или рабочий день закончен");
                    //return false;

                    jQuery('.modal-body #infoAboutMicContent').hide();
                    jQuery('.modal-footer #newRoomLink').hide();
                    jQuery('#busyOperators').modal();

                    sendStat_busy(socket.socket.sessionid);
                }
                else {
                    console.log("id clicked button is: ",$(this).id);
                    socket.emit('call','online sign');
                }
            }

            play_sound();
            jQuery(this).removeClass('call-btn').addClass('cancel-call-btn');
        }
        else if(jQuery(this).hasClass('cancel-call-btn')) {
                myAudio.pause();

                switch (clickedButtonId) {
                    case 'call_interpreter_now':
                        socket.emit('userCancelled','answering english');
                    break;
                    case 'call_sign_language_now':
                        socket.emit('userCancelled','answering sign');
                        break;
                }

                jQuery(this).removeClass('cancel-call-btn').addClass('call-btn');
        }

        return false;
    }
    });

    jQuery('#queueEmail').on("click",function() {

        if(!isEmailAddress(jQuery('#emailAddress').val())) {
            alert('Введите правильный e-mail адрес');
            return false;
        }
        else {
            jQuery.post( "action.php", {
                email: jQuery('#emailAddress').val(),
                task: 'queueEmail'
            })
            .done(function( data ) {
                alert( "Заявка отправлена");
                jQuery('#busyOperators').modal('hide');
            });
        }
    });

    jQuery('#busyOperators').on('hide.bs.modal', function(e) {
        myAudio.pause();
        //socket.emit('userCancelled');
        jQuery('.cancel-call-btn').removeClass('cancel-call-btn').addClass('call-btn');

		socket.emit('switchRoom','visitor');
    });


    jQuery('#myModal').on('show.bs.modal', function (e) {
        jQuery('#myModalLabel').html('<b>Разрешите браузеру использовать микрофон и камеру.</b>');
        myAudio.pause();
        createWebRtcObject();
        play_tone();

    });

    jQuery('#myModal').on('hidden.bs.modal', function (e) {

        jQuery("#call-btn").toggle();
        jQuery("#cancel-call-btn").toggle();

        removeWebRtcObject();
    });

    jQuery('#myModal').on('hide.bs.modal', function (e) {
        removeWebRtcObject();
    });

//    jQuery('.clipboardimg' ).on('click', function() {
//
//
//
//        if(clipWasAdded == false) {
//            jQuery('.clipboardimg').zclip({
//                path:'js/ZeroClipboard.swf',
//                copy:jQuery('#room_link').attr('href'),
//                afterCopy:function(){
//                    alert('ссылка на комнату была скопирована в буфер');
//                    clipWasAdded = true;
//                }
//            });
//        }
//    });

});





var room = '';
var webrtcGlobal = '';
var linkWasAdded = false;
var clipWasAdded = false;

function isEmailAddress(str) {
    var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+jQuery/;
    if(!pattern.test(str)) {
        return false;
    }
    else {
        return true;
    }
}

function createWebRtcObject() {

    if(webrtcGlobal.length == 0) {

        jQuery('.modal-body').prepend(jQuery('#infoAboutMic').html());
        jQuery('#myModal .close, #myModal .modal-footer').hide();
		
		jQuery('#infoAboutMicContent').show();
		jQuery('#newRoomLink').show();


        // create our webrtc connection
        webrtcGlobal = new SimpleWebRTC({
            // the id/element dom element that will hold "our" video
            localVideoEl: 'localVideo',
            // the id/element dom element that will hold remote videos
            remoteVideosEl: 'remotes',
            // immediately ask for camera access
            autoRequestMedia: true,
            debug: false,
            detectSpeakingEvents: true,
            autoAdjustMic: false
        });
		webrtcGlobal.on('localMediaError', function(){
		            alert( jQuery("#noMediaInfo").text() ); 
		       });
    }
    else {
        //webrtc = webrtcGlobal;
    }

    // when it's ready, join if we got a room from the URL
    webrtcGlobal.on('readyToCall', function () {
        // you can name it anything

        jQuery('#myModalLabel').html('');
        //jQuery('#myModalLabel').html('Разрешите браузеру использовать микрофон и камеру.');

        jQuery('#infoAboutMicContent').remove();
        jQuery('#myModal .close, #myModal .modal-footer').show();

        jQuery('#myModal .modal-content').addClass('videoConferenceWindow')


         


        if (room) webrtcGlobal.joinRoom(room);

        showJoinRoomLink(room);
    });
}



function showJoinRoomLink(room) {

    if(linkWasAdded == false) {

        jQuery('.modal-footer').append('<div style="float:left;" id="newRoomLink"><a href="http://cloudinterpreter.com/room/?'+room+'" target="_blank" id="room_link">Ссылка на комнату</a><img src="' + base_url + 'modules/mod_videotranslation/img/copy.png" class="clipboardimg"></div>');

        //jQuery('#remotes').css('min-height',337+'px');


        setTimeout(function() {
            jQuery('.clipboardimg').zclip({
                path:'js/ZeroClipboard.swf',
                copy:jQuery('#room_link').attr('href'),
                afterCopy:function(){
                    alert('ссылка на комнату была скопирована в буфер');
                }
            });
        }, 1000);

//        jQuery('#defaultCountdown1').show();
//        jQuery('#defaultCountdown1').countdown({
//            until: +600,
//            format: 'HMS',
//            onTick: watchCountdown,
//            compact: true,
//            onExpiry: liftOff
//        });


        linkWasAdded = true;
    }

}


function liftOff() {
   	//jQuery('#myModal').modal('hide');
	//removeWebRtcObject();
sendStat_end(socket.socket.sessionid);
}

function watchCountdown(periods) {

    if(periods[4] == 0 && periods[5] <= 5) {
        //alert('time is finishing')

        if ( !jQuery( "#defaultCountdown" ).is( "hasCountdownLight" ) ) {

            //jQuery( "#defaultCountdown" ).show();

            jQuery( "#defaultCountdown" ).addClass( "hasCountdownLight" );

            jQuery(".timer-container, #defaultCountdown").effect("highlight", {}, 3000);
        }
		
	    if(periods[4] == 0 && periods[5] <= 1) {
	        //alert('time is finishing')

	        if ( !jQuery( "#defaultCountdown" ).is( "hasCountdownLight" ) ) {
	            animateText();
	        }
	    }

//        if(jQuery("#session-time-out").length) {
//            jQuery("#session-time-out").show();
//        }
    }
}

var animateWasRun = false;

function animateText() {

    if(!animateWasRun) {

        //jQuery('.timeIsOutText').show();
        jQuery(".timeIsOutText").hide();
        setTimeout(function(){
            jQuery(".timeIsOutText").show();
            setInterval(function(){
                jQuery(".timeIsOutText").toggle();
            },500)},3000);

        animateWasRun = true;
    }

}


function removeWebRtcObject() {
    webrtcGlobal.leaveRoom(room);
//
    webrtcGlobal.stopLocalMedia();
//    jQuery('#localVideo').removeAttr("src");
//    jQuery('#localVideo').removeAttr("style");

    //delete webrtc;
    //delete webrtcGlobal;
//    webrtcGlobal = ''

    //io.sockets.in(languageGroup).emit('OperatorAnswered');

    socket.emit('changeStatusBack');
    //socket.emit('forceDisconnect',1);
}


function play_sound() {
    myAudio = new Audio(base_url + 'modules/mod_videotranslation/media/us-ring.mp3');
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
    myAudio = new Audio(base_url + 'modules/mod_videotranslation/media/dtmf-1.mp3')
    myAudio.play();
}

function add_video_control(id) {

    var volume_container = '<div id="'+id+'-volume-control" class="volume-control"><section><span id="'+id+'-tooltip" class="tooltip"></span><div id="'+id+'-slider" class="slider"></div><span id="'+id+'-volume" class="volume"></span></section></div>'

    //var volume_container = '<section><span id="'+id+'-tooltip" class="tooltip"></span><div id="'+id+'-slider" class="slider"></div><span id="'+id+'-volume" class="volume"></span></div>'

    //new element on the page
    jQuery('#'+id+'-container').append(volume_container);

    //alert(id)
    volume_control(id);

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

//            jQuery('#'+id).volume = value/100;

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

    jQuery('.OT_video-poster').toggle();

    if ( jQuery('.OT_video-poster').is(":hidden") ) {
        webrtcGlobal.resumeLocalVideo();
        jQuery('#pubVid').css('opacity','1');
    } else if ( jQuery('.OT_video-poster').is(":visible") ) {
        webrtcGlobal.pauseLocalVideo();
        jQuery('#pubVid').css('opacity','.2');
    }

}

function publishAudio() {

    if ( jQuery('#pubAud').css("opacity") < 1 ) {
        webrtcGlobal.resumeLocalAudio();
        jQuery('#pubAud').css('opacity','1');
    } else if ( jQuery('#pubAud').css("opacity") == 1 ) {
        webrtcGlobal.pauseLocalAudio();
        jQuery('#pubAud').css('opacity','.2');
    }

}

function isEmailAddress(str) {
    var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+jQuery/;
    //var pattern = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;;
    if(!pattern.test(str)) {
        return false;
    }
    else {
        return true;
    }
}




function not_login(info) {
   if(parseInt(info.user_id) == 0) {
       return true;
   } else {

        //TODO fix this if you need checking for logged users
        return true;
   }
}

function balance_zero(info) {
    if(parseInt(info.balance) == 0) {
       return true;
    } else {
        //return false;
        //TODO fix this if you need checking for logged users
        return true;
   }
}



