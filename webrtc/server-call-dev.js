var yetify = require('yetify'),
    io = require('socket.io').listen(1999);
io.set("transports",["xhr-polling", "jsonp-polling"]);

//io.set("close timeout", 10);

//var connectedUserNames = {};
var rooms = ['online','busy','away','answering','calling'];
//var callingUsers = {};

var isIOS = false;

io.sockets.on('connection', function (socket) {

//    socket.on('SET_STATUS', function(room){
//
//
//        socket.room = room;
//        socket.join(room)

    //connectedUserNames[socket.id] = {"status": data};

//        switch(data) {
//            case 1:
//                socket.room = 'online';
//                socket.join('online');
//             break;
//            case 2:
//                socket.room = 'busy';
//                socket.join('busy');
//            break;
//            case 3:
//                socket.room = 'away';
//                socket.join('away');
//            break;
//            case 4:
//                socket.room = 'answering';
//                socket.join('answering');
//            break;
//            case 5:
//                socket.room = 'calling';
//                socket.join('calling');
//            break;
//            case 6:
//                socket.room = 'visitor';
//                socket.join('visitor');
//            break;
//
//        }



    //console.log('--------sockets info -------',io.sockets.clients('online'));
//        var allOnline = io.sockets.clients('online');
//
//        //socket.broadcast.json.emit('CONNECTED_USERS', allOnline);
//        io.sockets.in('visitor').emit('CONNECTED_USERS',io.sockets.clients('online'));
//    });

	socket.on('sendchat', function (data) {
     // we tell the client to execute 'updatechat' with 2 parameters
     io.sockets.in(socket.room).emit('updatechat', data);
 	});

    socket.on('GET_CONNECTED_USERS', function(data) {
        //io.sockets.socket(socket.id).json.emit('CONNECTED_USERS', connectedUserNames);
    });

    socket.on('GET_CALLING_USERS', function(data) {
        io.sockets.socket(socket.id).json.emit('callingUsers', getJustIds(io.sockets.clients('calling')));
    });

    socket.on('GET_VISITORS', function(data) {
        io.sockets.socket(socket.id).json.emit('visitors', getJustIds(io.sockets.clients('visitor')));
    });


    socket.on('call', function (languageGroup) {
        var user_id = socket.id;
        isIOS = false;


        socket.leave('visitor');
        socket.room = 'calling';
        socket.join('calling');

        socket.join(socket.id);  //adding user in a room id group for a chat


        switch(languageGroup) {
            case 'online english ios':
                languageGroup = 'online english';
                isIOS = true;
            break;
        }

        io.sockets.in(languageGroup).emit('userIsCalling',{user_id: user_id, is_ios: isIOS});

        socket.broadcast.json.emit('callingUsers',getJustIds(io.sockets.clients('calling')));
        socket.broadcast.json.emit('visitors',getJustIds(io.sockets.clients('visitor')));
    });

    socket.on('answering', function (data, languageGroup) {
        io.sockets.socket(data.user_id).json.emit('gotAnswer',{event: 'OperatorHasAnswered',user_id: data.user_id})
        io.sockets.in(languageGroup).emit('OperatorAnswered');

        socket.join(data.user_id);
        socket.room = data.user_id;

        //delete callingUsers[data.user_id];
        socket.broadcast.json.emit('callingUsers', getJustIds(io.sockets.clients('calling')));
    });

    socket.on('changeStatusBack', function() {

        io.sockets.in(socket.id).emit('changeOperatorStatusBack');
        io.sockets.socket(socket.id).json.emit('reloadPage');

    });

    socket.on('userCancelled', function (languageGroup) {
        var user_id = socket.id;
        //delete callingUsers[socket.id];

        socket.leave('calling');
        socket.join('visitor');
        socket.room = 'visitor';

        io.sockets.in(languageGroup).emit('userCancelled',{user_id: user_id},languageGroup);

        socket.broadcast.json.emit('callingUsers', getJustIds(io.sockets.clients('calling')));
        socket.broadcast.json.emit('visitors',getJustIds(io.sockets.clients('visitor')));

    });

    socket.on('disconnect', function() {
        var user_id = socket.id;
        io.sockets.in(user_id).emit('userCancelled');
//        delete connectedUserNames[socket.id];
//        socket.broadcast.json.emit('CONNECTED_USERS', connectedUserNames);
        //delete callingUsers[socket.id];
        //socket.broadcast.json.emit('callingUsers', callingUsers);

        socket.leave(socket.room);
        io.sockets.in('visitor').emit('CONNECTED_ENGLISH_USERS',getJustIds(io.sockets.clients('online english')));
        io.sockets.in('visitor').emit('CONNECTED_GERMAN_USERS',getJustIds(io.sockets.clients('online german')));
        socket.broadcast.json.emit('visitors',getJustIds(io.sockets.clients('visitor')));

    });

    socket.on('forceDisconnect', function(reload){
        if(reload) {
            io.sockets.socket(socket.id).json.emit('reloadPage');
        }
        socket.disconnect();
    });

    socket.on('joinRoom', function(newroom) {
        socket.join(newroom);
        socket.room = newroom;

//        console.log('-----------users in this group ('+newroom+')-------------: ',getJustIds(io.sockets.clients(newroom)));

        io.sockets.in('visitor').emit('CONNECTED_ENGLISH_USERS',getJustIds(io.sockets.clients('online english')));
        io.sockets.in('visitor').emit('CONNECTED_GERMAN_USERS',getJustIds(io.sockets.clients('online german')));

    });

    socket.on('leaveRoom', function(newroom) {
        socket.leave(newroom);
    });

    socket.on('switchRoom', function(newroom){

        socket.leave(socket.room);
        socket.join(newroom);
        socket.room = newroom;

        //console.log('----------users in '+newroom+' room: ',getJustIds(io.sockets.clients(newroom)));

        if(newroom == 'online') {
            var data = {};

//            for(var k in callingUsers) {
//                data.user_id = k;
//                io.sockets.in('online').emit('userIsCalling',data);
//                return;
//            }
        }

        if(newroom == 'answering') {
//            console.log('!!!!!!!!an attempt to change room on busy');
            //console.log('$$$$$$$$$$$$$$$$$$$add user to the answering room:  ',getJustIds(io.sockets.clients('answering')));
//            console.log('+++++++++busy users:  ',getJustIds(io.sockets.clients('busy')));
        }


        //console.log('---------visitors: ',getJustIds(io.sockets.clients('visitor')));

        io.sockets.in('visitor').emit('CONNECTED_ENGLISH_USERS',getJustIds(io.sockets.clients('online english')));
        io.sockets.in('visitor').emit('CONNECTED_GERMAN_USERS',getJustIds(io.sockets.clients('online german')));


        socket.broadcast.json.emit('callingUsers', getJustIds(io.sockets.clients('calling')));
        socket.broadcast.json.emit('visitors',getJustIds(io.sockets.clients('visitor')));


        //io.sockets.in('visitor').emit('CONNECTED_USERS',getJustIds(io.sockets.clients('answering')));
    });

});


function getJustIds(inputData) {
    var array = {};
    for(var k in inputData) {
        array[inputData[k].id] = {};
    }
    return array;
}
