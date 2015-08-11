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


function volume_control(id) {
console.log('Start volume control');
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


function add_video_control(id) {
console.log('Start  add_video_control');

    var volume_container = '<div id="'+id+'-volume-control" class="volume-control"><section><span id="'+id+'-tooltip" class="tooltip"></span><div id="'+id+'-slider" class="slider"></div><span id="'+id+'-volume" class="volume"></span></section></div>'

    //var volume_container = '<section><span id="'+id+'-tooltip" class="tooltip"></span><div id="'+id+'-slider" class="slider"></div><span id="'+id+'-volume" class="volume"></span></div>'

    //new element on the page
    jQuery('#'+id+'-container').append(volume_container);

    //alert(id)
    volume_control(id);

}