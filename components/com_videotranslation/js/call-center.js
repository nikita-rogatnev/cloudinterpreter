jQuery( document ).ready(function() {

    var user_id = '';
	var is_calling = false;
     var isIOS = false;

 show_table();

    //socket = io.connect('http://78.140.138.8:1999');
    socket = io.connect('http://call.cloudinterpreter.com');

    socket.on('connect', function () {

        socket.emit('switchRoom',$("#ifUserOnline option:selected").text()+' '+$("#interpreterLanguage option:selected").text());

        $('#ifOnline').css('color','green').html('connected');
        $('#socketId').html(socket.socket.sessionid);

        // send socket id to server
        $.ajax({
          url: 'index.php?option=com_videotranslation&task=setSocketId',
          data: {set_socket_id: socket.socket.sessionid},
          dataType: 'POST',
          success: function(data) {
        }
        });


        socket.on('userIsCalling', function (data) {

        isIOS = data.is_ios;
			if(!is_calling) {
			 	is_calling = true;

             	user_id = data.user_id;
				var operator_id = socket.id;
				
             	jQuery( "#dialog" ).dialog( "open" );

             	play_sound();
				
				socket.emit('switchRoom','answering '+$("#interpreterLanguage option:selected").text());
				
				// socket.emit('joinRoom',user_id); //add operator to new group
				// console.log('operator '+socket.socket.sessionid+' was added in the room '+user_id);
				
				$('#ifUserOnline').val(4);
			}
        });

        socket.on('OperatorAnswered', function(data) {
            jQuery( "#dialog" ).dialog( "close" );
            myAudio.pause();
			is_calling = false;

        })

        socket.on('userCancelled', function(data) {
        get_email(data.user_id);
			if(data) {
	 			var user_id = data.user_id;
				socket.emit('leaveRoom',user_id); //leave operator from room
				console.log('operator '+socket.socket.sessionid+' was left from room '+user_id);		
			}
 			
            jQuery( "#dialog" ).dialog( "close" );
            myAudio.pause();
			is_calling = false;
			socket.emit('switchRoom','online '+$("#interpreterLanguage option:selected").text());
			
			$('#ifUserOnline').val(1);
			socket.emit('GET_CALLING_USERS');
        });
		
	    socket.emit('GET_CALLING_USERS');

	    socket.on('callingUsers', function(data){
	    	console.log('calling users: ',data);

			var string = '';
			for(var k in data) {
				string += k+"<br />";        
	    	}
			
	        $('#waitingUsers').html(string);
	    });

        socket.emit('GET_VISITORS');

        socket.on('visitors', function(data){

            console.log('visitors on site: ',data);

            var string = '';
            for(var k in data) {
                string += k+"<br />";
            }

            $('#visitors').html(string);
        });
		
		socket.on('changeOperatorStatusBack', function() {
	        socket.emit('switchRoom','online '+$("#interpreterLanguage option:selected").text());
			$('#ifUserOnline').val(1);
        sendStat_end(socket.socket.sessionid);
		});



        window.onbeforeunload = function() {
            socket.emit('forceDisconnect');
            return true; //here also can be string, that will be shown to the user
        }

    });


//    socket.disconnect(function() {
//        alert('aa')
//    });

    socket.on('disconnect', function() {
        $('#ifOnline').css('color','red').html('isn\'t connected');
        sendStat_end(socket.socket.sessionid);
    });


    $('#ifUserOnline').change(function() {
        socket.emit('switchRoom',$("#ifUserOnline option:selected").text() +' '+$("#interpreterLanguage option:selected").text());
    });


    $('#interpreterLanguage').change(function() {
        socket.emit('switchRoom',$("#interpreterLanguage").val());
    });


    jQuery( "#dialog" ).dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Get a call": function() {

                socket.emit('switchRoom','busy');
                $('#ifUserOnline').val(2);

                socket.emit('answering', { user_id: user_id }, 'answering '+$("#interpreterLanguage option:selected").text());
                sendStat_answering(user_id, isIOS);
                if(isIOS) {
                    var win=window.open('https://apprtc.appspot.com/?r='+user_id, '_blank');
                }
                else {
                    var win=window.open('http://cloudinterpreter.com/room?'+user_id, '_blank');
                }
                win.focus();

                console.log('my new win property: ',Window.location);

                jQuery( this ).dialog( "close" );

                myAudio.pause();
                is_calling = false;
               isIOS = false;


            },
            Cancel: function() {

                jQuery( this ).dialog( "close" );
                myAudio.pause();
                is_calling = false;
				socket.emit('switchRoom',$("#interpreterLanguage").val());
				$('#ifUserOnline').val(1);
            }
        }
    });



});

function sendStat_answering(sessionid, isIOS1) {
    ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=sendStat';
    data = {
        status: 'interpreter_answer',
        sessionid: sessionid,
        isIOS: isIOS1
    };
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: data,
    async: true,
    success: function()
    {
       console.log('ok, sendStat_answering ajax success');
    }
});
}

function getJustIds(inputData) {
    var array = {};
    for(var k in inputData) {
         array[inputData[k].id] = {};
    }
    return array;
}


function play_sound() {
    myAudio = new Audio(base_url + 'components/com_videotranslation/media/applering_osa62b88.ogg');
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
function show_table () {
    var tt = '';

    ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=getHistory';
    data = {
        history: '1'
    };
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        async: true,
        success: function(data)
        {
            jQuery('#history_block').html(data);
            $('img.call_to').attr('src', base_url + 'components/com_videotranslation/img/arrow_callcenter.png');
            $('img.mobile').attr('src', base_url + 'components/com_videotranslation/img/mobile.png');
            $('img.desktop').attr('src', base_url + 'components/com_videotranslation/img/desktop.png');
        }
 });

    
}
function get_email(socket_id) {
    var tt = '';

    ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=getEmail';
    data = {
        socket_id: socket_id
    };
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        async: true,
        success: function(data)
        {
           jQuery('#missed_call').html('You missed a call from a user with this email: <a href="mailto:' + data + '">' + data + '</a>');
        }
 });

}


function sendStat_end(sessionid) {
    ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=sendStat';
    data = {
        status: 'end',
        sessionid: sessionid
    };
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: data,
    async: true,
    success: function()
    {
       console.log('ok, sendStat_end ajax success');
    }
});
}