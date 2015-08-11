(function( $ ){

    var methods = {
        site_url:'',
        selectOptions:{},
        tempOptions:{},
        difference: 0,
        partnerDifference: 0,
        elem_id: '',
        active: 0,
        flag_clicked: false,
        languageTag:'',

        init : function( options ) {

           return this.each(function(){


               if(!methods.active) {

                   $('#'+this.id).empty();

                   methods.site_url=$('#live_site').html();
                   methods.elem_id = this.id;
                   methods.languageTag= $('#currentLanguageTag').html();

                   methods.tempOptions = options;
                   methods.tempOptions.startTime = $('#startTime').html();
                   methods.tempOptions.endTime = $('#endTime').html();
//                   methods.tempOptions.startTime = '00:00:00';
//                   methods.tempOptions.endTime = '24:00:00';
//debugger;

                   methods.getTimezones();
                   methods.getMyTimezone();

                   methods.getTranslatorTime();

                   methods.setUpTimeForTimeLine(0);

                   methods.setUpTimeForTimeLineForPartner(1);

//                   methods.tempOptions.minPartner = methods.tempOptions.min;
//                   methods.tempOptions.maxPartner = methods.tempOptions.max;

                   methods.selectOptions  = methods.getScaleOptions(methods.tempOptions.min, methods.tempOptions.max, methods.tempOptions.minPartner, methods.tempOptions.maxPartner);

                   methods.create();

                   methods.addTimeToSession(0,0);

               }
               else {
                   methods.setDatepickerDate(0);
               }

               methods.addEvents();

               //unblock module
               $('.new-module-videotranslation').unblock();

               methods.calculateDivOnTheCenter();

               methods.scrollsForWindow();

               $('#'+methods.elem_id).show({duration: 1500});

               methods.active = 1;


               methods.scrollInFreeTimePosition();

           });
        },

        calculateDivOnTheCenter : function () {

            var timeLineWindowContainer = $( '.timeline-window-container' );
            var offset = timeLineWindowContainer.offset();

            var width = timeLineWindowContainer.width();

            if(offset.left > 0) {
            timeLineWindowContainer.css('left',-offset.left+'px');
            timeLineWindowContainer.css('width',$( document ).width()+'px');
            }

            if(width < 1092) {
                timeLineWindowContainer.css('width',(timeLineWindowContainer.width() + 80)+'px');
            }


            //alert(offset.left);
        },


        scrollsForWindow : function() {
//            $(".parent-time-container").mCustomScrollbar({
//               horizontalScroll:true
//            });
            $('html, body').animate({scrollTop: 320}, 1000);

        },

        scrollInFreeTimePosition : function() {

            if ($(".timeline-inner").not(".disabled").length > 0) {
                $('.parent-time-container').animate({
                    scrollLeft: $(".timeline-inner").not(".disabled").first().offset().left-290
                }, 1000);
            }
        },

        //get 2 selects
        getTimezones : function () {

            $('#'+methods.elem_id).append('<div class="my-time"></div>');
            $('#'+methods.elem_id).append('<div class="my-partner-time"></div>');


            var ajaxurl = methods.site_url + 'index.php?option=com_videotranslation&task=getTimezones';
            var data = {
                lang: methods.languageTag
            };
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                async: false,
                success: function(response)
                {
                    $('.my-time').html(response);
                    $('.my-partner-time').html(response);

                    $('.my-time #jform_my_timezone-lbl').html(Joomla.JText._('MOD_VIDEOTRANSLATION_YOURS_TIMEZONE'));
                    $('.my-partner-time #jform_my_timezone-lbl').html(Joomla.JText._('MOD_VIDEOTRANSLATION_YOUR_PARTNERS_TIMEZONE'))

                }
            });
        },

        //get user location
        getMyTimezone : function () {
            var ajaxurl = methods.site_url + 'index.php?option=com_videotranslation&task=getMyTimezone';
            var data = {
                 lang: methods.languageTag
            };
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                async: false,
                dataType: "json",
                success: function(response)
                {
                    if(response!=null) {
                        $(".my-time option[value='"+response.my_timezone+"']").attr("selected", "selected");
                        $(".my-partner-time option[value='"+response.my_partner_timezone+"']").attr("selected", "selected");
                    }

                    $('.my-time select').addClass('chosen-select').chosen({width:'200px'});
                    $('.my-partner-time select').addClass('chosen-select').chosen({width: '200px'});

                }
            });
        },

        //get translator timezone
        getTranslatorTime : function () {

            var ajaxurl = methods.site_url + 'index.php?option=com_videotranslation&task=getTranslatorTime';
            var data = {
                my_timezone: $('.my-time select').val(),
                my_partner_timezone: $('.my-partner-time select').val()
            };
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                async: false,
                dataType: "json",
                success: function(response){
                    methods.difference = parseInt(response.my_timezone) - parseInt(response.translator_timezone);
                    //methods.partnerDifference = parseInt(response.my_partner_timezone) - parseInt(response.translator_timezone); //commented because a new version
                    methods.partnerDifference = parseInt(response.my_partner_timezone) - parseInt(response.my_timezone);
                }
            });
        },

        setBusyTime : function () {
            var ajaxurl = methods.site_url + 'index.php?option=com_videotranslation&task=setBusyTime';


            var data = {
                my_timezone: $('.my-time select').val(),
                my_partner_timezone: $('.my-partner-time select').val(),
                min: methods.tempOptions.min,
                max: methods.tempOptions.max,
                difference: methods.difference,
                langId1: $('#you_speak_select').val(),
                langId2: $('#your_partner_speak_select').val()

            };

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                async: false,
                dataType: "json",
                success: function(response){

                    response.forEach(function(entry) {
                        for(var i = parseInt(entry.dtstart); i < parseInt(entry.dtend); i = i + parseInt(methods.tempOptions.step)) {
                            var sel = i.toString();
                            if(entry.free_for_select != 1) {
                                $('#'+sel).addClass('disabled');
                            }
                        }
                    });

                }
            });

        },

        addCallModeToSession : function () {
            var ajaxurl = methods.site_url + 'index.php?option=com_videotranslation&task=addCallModeToSession';
            var data = {
                callMode: jQuery('#call_mode_select').val()
            };
            $.ajaxQueue({
                type: "POST",
                url: ajaxurl,
                data: data,
                async: true,
                success: function(response){
                    console.log('success ajax addCallModeToSession');
                }
            });
        },

        setUpTimeForTimeLine : function (needToAddTimezone) {

            if(needToAddTimezone) {

               var startTime = new Date(methods.getTimeFormat(methods.options)+' '+$('#startTime').html());
                    startTime.setHours(parseInt(startTime.getHours())+parseInt(methods.difference));

                var endTime = new Date(methods.getTimeFormat(methods.options)+' '+$('#endTime').html());
                    endTime.setHours(endTime.getHours()+methods.difference);
            }
            else {
                var startTime = new Date(methods.getTimeFormat(methods.tempOptions)+' '+methods.tempOptions.startTime);
                var endTime = new Date(methods.getTimeFormat(methods.tempOptions)+' '+methods.tempOptions.endTime);
            }



            var startTime =  startTime.getDate()+' '+ methods.getMonthName(startTime.getMonth())+' '+startTime.getFullYear()+', '+startTime.getHours()+':'+startTime.getMinutes()+':'+startTime.getSeconds();
            var endTime =  endTime.getDate()+' '+ methods.getMonthName(endTime.getMonth())+' '+endTime.getFullYear()+', '+endTime.getHours()+':'+endTime.getMinutes()+':'+endTime.getSeconds();


            //alert(startTime.getFullYear()+' '+ methods.getMonthName(startTime.getMonth())+' '+startTime.getDate()+' '+startTime.getHours()+':'+startTime.getMinutes()+':'+startTime.getSeconds())
            //console.log('startTime: ',startTime);

            methods.tempOptions.min = methods.getUnixTime( startTime );

            methods.tempOptions.max = methods.getUnixTime( endTime );



        },

        setUpTimeForTimeLineForPartner : function (needToAddTimezone) {

            if(needToAddTimezone) {
                var startTime = new Date(methods.getTimeFormat(methods.options)+' '+$('#startTime').html());
                startTime.setHours(parseInt(startTime.getHours())+parseInt(methods.partnerDifference));

                var endTime = new Date(methods.getTimeFormat(methods.options)+' '+$('#endTime').html());
                endTime.setHours(endTime.getHours()+methods.partnerDifference);
            }
            else {
                var startTime = new Date(methods.getTimeFormat(methods.tempOptions)+' '+methods.tempOptions.startTime);
                var endTime = new Date(methods.getTimeFormat(methods.tempOptions)+' '+methods.tempOptions.endTime);
            }

            var startTime =  startTime.getDate()+' '+ methods.getMonthName(startTime.getMonth())+' '+startTime.getFullYear()+', '+startTime.getHours()+':'+startTime.getMinutes()+':'+startTime.getSeconds();
            var endTime =  endTime.getDate()+' '+ methods.getMonthName(endTime.getMonth())+' '+endTime.getFullYear()+', '+endTime.getHours()+':'+endTime.getMinutes()+':'+endTime.getSeconds();

            methods.tempOptions.minPartner = methods.getUnixTime( startTime );
            methods.tempOptions.maxPartner = methods.getUnixTime( endTime );

        },

        addEvents : function () {

            methods.removeEventns();

            methods.flag_clicked = false;
            var flag_selected = false;

            $('.timeline-inner').bind('mousedown',function() {


                if(!$(this).hasClass('disabled')) {

                    methods.flag_clicked = true;

                    if($(this).hasClass('timeline-inner-selected')) {
                        //$(this).html('-');
                        flag_selected = true;

                        methods.addTimeToSession($(this).attr('id'),0);

                        var tmp = methods.checkIfSomethingInSession();
                        if (tmp.count_items == 1) {
                            //jQuery('#planBlock').hide();
                            jQuery('#nowBtn').html(methods.nowButtonLabel());
                            jQuery('.callTranslatorNow').show();
                        }
                    }
                    else {
                        jQuery('#planBlock').show();
                        //jQuery('#allElements').css('margin-left', '1%');
//                      jQuery('#nowBtn').delay(1000).html(jQuery('.removeTimeFromSession').prev().text().split(" -")[0] + "   <i class='icon-white icon-calendar'></i>");
                        jQuery('.callTranslatorNow').hide();
                        //$(this).html('+');
                        flag_selected = false;

                        methods.addTimeToSession($(this).attr('id'),1)
                    }

                    $(this).toggleClass("timeline-inner-selected");

                }
                else {
                    //alert(Joomla.JText._('MOD_VIDEOTRANSLATION_THIS_TIME_CURRENTLY_NOT_AVAILABLE'))
                }

            });

            $('#timeline-window').bind('mouseup',function() {
                methods.flag_clicked = false;
            });


            $('.previdous-date').bind('click', function () {
                methods.setDatepickerDate(-1);
            });

            $('.next-date').bind('click', function () {

                methods.setDatepickerDate(+1);
            });


            $('.timeline-inner').bind('mouseover',function() {


               if(!$(this).hasClass('disabled')) {

                    if(methods.flag_clicked) {
                        if(!flag_selected) {
                            if(!$(this).hasClass('timeline-inner-selected')) {
                                $(this).toggleClass("timeline-inner-selected");
                               // $(this).hasClass('timeline-inner-selected')?$(this).html('+'):$(this).html('-');
                                methods.addTimeToSession($(this).attr('id'),1)
                            }
                        }
                        if(flag_selected) {
                            if($(this).hasClass('timeline-inner-selected')) {
                                $(this).toggleClass("timeline-inner-selected");
                                //$(this).hasClass('timeline-inner-selected')?$(this).html('+'):$(this).html('-');
                                methods.addTimeToSession($(this).attr('id'),0)
                            }
                        }
                    }
               }

            });

            $('.b-popupa__close').bind('click',function() {
                $(this).parent().hide();
                methods.active = 0;
            });

            $('.b-popupa__controls').live('click',function() {
                $(this).parent().hide();
                methods.active = 0;
                $(this).parent().parent().removeAttr("style");
            });


            $('.my-time select,.my-partner-time select').bind('change',function() {

                methods.getTranslatorTime();
                methods.setUpTimeForTimeLine(0);
                methods.setUpTimeForTimeLineForPartner(1);

                methods.selectOptions  = methods.getScaleOptions(methods.tempOptions.min, methods.tempOptions.max,methods.tempOptions.minPartner, methods.tempOptions.maxPartner);

                methods.createTimelinePart();
                methods.addEvents();
            });

            $('.removeTimeFromSession').live('click',function () {
                console.log('remove time from session');

                if (jQuery('.removeTimeFromSession').size() == 1) {
                    jQuery('#nowBtn').html(methods.nowButtonLabel());
                    jQuery('.callTranslatorNow').show();
                    jQuery('#planBlock').hide();
                    jQuery('#allElements').css('margin-left', '15%');
                };

                var timeForRemoveString = $(this).attr('time');

                timeForRemoveArray = timeForRemoveString.split(',');

                timeForRemoveArray.forEach(function(entry) {
                    methods.addTimeToSession(entry,0);
                    //$('#'+entry).removeClass('timeline-inner-selected').html('-');
                    $('#'+entry).removeClass('timeline-inner-selected');
                });

            });

            $('#buttonOrder').bind('click',function () {


                methods.addCallModeToSession();

                    var countItemsInCart = methods.checkIfSomethingInSession();


                    if(parseInt(countItemsInCart.user_id) == 0) {
                        methods.showDialogWindow('registration');
                        return false;
                    }

                    if(parseInt(countItemsInCart.balance) == 0 || (parseInt(countItemsInCart.amount) > 0 && (parseInt(countItemsInCart.amount) > parseInt(countItemsInCart.balance)))) {
                        //alert(Joomla.JText._('MOD_VIDEOTRANSLATION_DONT_HAVE_ENOUGH_MONEY'));

                        methods.showDialogWindow('donthavemoney');

                        return false;
                    }

                    if(parseInt(countItemsInCart.count_items) > 0 ) {
                    	methods.addCallModeToSession();
                        document.location.href='index.php?option=com_videotranslation&view=userdetails&Itemid=493';
                        return false;
                    }
                    else {
                        //alert(Joomla.JText._('MOD_VIDEOTRANSLATION_CLICK_ON_DATE_AND_SELECT_TIME'))

                        methods.showDialogWindow('selectdate');

                        return false;
                    }


            });

            $('.timeline-outer .disabled').tooltipsy({
                css: {
                'padding': '10px',
                    'max-width': '200px',
                    'color': '#303030',
                    'background-color': '#f5f5b5',
                    'text-shadow': 'none',
                    'border': '1px solid #deca7e',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'z-index': '10'},
                content: Joomla.JText._('MOD_VIDEOTRANSLATION_THIS_TIME_IS_NOT_AVAILABLE'),
                offset: [10, 10]
        });




        },

        showDialogWindow : function (type) {

            $("#"+methods.elem_id).hide();
            methods.active = 0;

            switch(type) {
                case 'donthavemoney':
                $("#dialog-dont-have-enough-money").dialog({
                    height: 130,
                    modal: true,
                    dialogClass: "DialogWindowAlert"
                });
                break;
                case 'selectdate':
                    $("#dialog-select-date").dialog({
                        height: 130,
                        modal: true,
                        dialogClass: "DialogWindowAlert"
                    });
                break;
                case 'registration':
                    $("#dialog-modal-registration").dialog({
                        height: 390,
                        modal: true,
                        dialogClass: "DialogWindowAlert"
                    });
                break;

                default:
                    $("#dialog-modal-registration").dialog({
                        height: 390,
                        modal: true,
                        dialogClass: "DialogWindowAlert"
                    });
                break;
            }
        },

        checkIfSomethingInSession : function () {

            var item = '';

            var ajaxurl = methods.site_url + 'index.php?option=com_videotranslation&task=checkIfSomethingInSession';
            var data = {
            }

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                async: false,
                dataType: "json",
                success: function(response)
                {
                    item  = response;
                }
            });

            return item;
        },

        addTimeToSession : function (timeId, add) {

            if (add == 0) {
                if (jQuery('.removeTimeFromSession').size() == 1) {
                    jQuery('#nowBtn').html(methods.nowButtonLabel());
                    jQuery('.callTranslatorNow').show();
                };
            };

            var ajaxurl = methods.site_url + 'index.php?option=com_videotranslation&task=addTimeToSession&add='+add;
            var data = {
                timeId: timeId,
                my_timezone: $('.my-time select').val(),
                my_partner_timezone: $('.my-partner-time select').val(),
                your_language: $('#you_speak_select').val(),
                your_partner_language: $('#your_partner_speak_select').val(),
                lang: $('#currentLanguageTag').html()
            };
            $.ajaxQueue({
                type: "POST",
                url: ajaxurl,
                data: data,
                async: true,
                //contentType: 'text/html; charset=UTF-8',
                //contentType:"application/json; charset=utf-8",
//                dataType: "json",
                success: function(response){
                    methods.showCart(response);
                    console.log('success ajax');
                    if (jQuery('.removeTimeFromSession').size() != 0) {
                       jQuery('#nowBtn').html(jQuery('.removeTimeFromSession').prev().text().split(" -")[0] + "   <i class='icon-white icon-calendar'></i>");
                }
                }
            });
        },


        showCart : function (response) {

                $('#cart').html(response);

                $('#divbuttonOrder').show();
                $('#cart').css('overflow','auto');

        },

        setDatepickerDate : function (value) {

            var $picker = $("#datepicker");
            var date=new Date($picker.datepicker('getDate'));
            date.setDate(date.getDate()+value);
            $picker.datepicker('setDate', date);

            var new_date=new Date($picker.datepicker('getDate'));

            methods.tempOptions.selectedDay = new_date.getDate();
            methods.tempOptions.selectedMonth = new_date.getMonth();
            methods.tempOptions.selectedYear = new_date.getFullYear();

            //methods.selectOptions  = methods.getScaleOptions(methods.tempOptions.min, methods.tempOptions.max, methods.tempOptions.minPartner, methods.tempOptions.maxPartner);

            methods.setUpTimeForTimeLine(0);

            methods.selectOptions  = methods.getScaleOptions(methods.tempOptions.min, methods.tempOptions.max, methods.tempOptions.minPartner, methods.tempOptions.maxPartner);


            methods.createTimelinePart();
            methods.createContainerDate();
            methods.addEvents();

//            $('#timeline-window').timeline({
//                selectedDay : new_date.getDate(),
//                selectedMonth : new_date.getMonth(),
//                selectedYear: new_date.getFullYear(),
//                startTime: methods.options.startTime,
//                endTime: methods.options.endTime,
//                step: methods.optionsStep
//            });


            methods.scrollInFreeTimePosition();

            return false;
        },


        removeEventns : function () {
            $('.timeline-inner').unbind();
            $('.b-popupa__close').unbind();
            $('.previdous-date').unbind();
            $('.next-date').unbind();
            $('.my-time select').unbind();
            $('.my-partner-time select').unbind();
            $('#buttonOrder').unbind();
        },

        create : function() {

           $('#'+methods.elem_id).append('<i class="b-popupa__tail"><i class="b-popupa__tail-i"></i></i><i class="b-popupa__close"></i><i class="b-popupa__shadow"></i>');

           methods.createTimelinePart();
           methods.createCurrentDatePart();
        },

        createCurrentDatePart : function () {
            //$("#"+methods.elem_id).append('<div class="b-popupa__controls"><input type="button" value="Ok" class="readon"></div><div class="b-popupa__controls"><input type="button" value="Ok" class="readon"></div>');
            $("#"+methods.elem_id).append('<div class="b-popupa__controls"><input type="button" value="Ok" class="readon"></div>');


            methods.createContainerDate();
        },

        createContainerDate : function () {
            $('.container-date').remove();
            $("#"+methods.elem_id).append('<div class="container-date"><div class="previdous-date"><<</div><div class="current-date">'+methods.getMonthNameLocalisation(methods.tempOptions.selectedMonth)+' '+methods.tempOptions.selectedDay+'</div><div class="next-date">>></div></div>');
        },

        createTimelinePart : function () {

            $('.parent-time-container').remove();

            $("#"+methods.elem_id).append('<div class="parent-time-container"><div class="time-container"></div></div>');

            var selectOptions = methods.selectOptions;

            //console.log('selectOptions: ', methods.selectOptions)

            var inc = 0;
            jQuery(selectOptions).each(function(i){
                var style = (inc == selectOptions.length || inc == 0) ? 'style="display: none;"' : '' ;
                var labelText = selectOptions[i].text;

                var labelText1 = selectOptions[i].text1;

                var date = new Date(selectOptions[i].value*1000);

                var minutes = date.getMinutes();

                var scaleInner = $("#"+methods.elem_id+" .time-container");

                var timeId = selectOptions[i].value;

                if(minutes == 0) {
                    scaleInner.append('<div class="timeline-outer hour-stick"><div class="timeline-inner" id="'+timeId+'"></div></div>');
                    scaleInner.append('<div class="timeline-label-div" style="left:'+ methods.leftVal(selectOptions,inc) +'"><span class="timeline-label">'+ labelText +'</span></div>');

                    scaleInner.append('<div class="timeline-label-div-partner" style="left:'+ methods.leftVal(selectOptions,inc) +'"><span class="timeline-label">'+ labelText1 +'</span></div>');
                }
                else {
                    scaleInner.append('<div class="timeline-outer"><div class="timeline-inner" id="'+timeId+'"></div></div>');
                    scaleInner.append('<div class="timeline-label-div" style="left:'+ methods.leftVal(selectOptions,inc) +'"><span class="timeline-label-minutes">'+ labelText +'</span></div>');
                }
                inc++;
            });

            $("#"+methods.elem_id+" .time-container").append('<div class="timeline-label-div" style="left:100%;"><span class="timeline-label">' + methods.getNormalTime(methods.tempOptions.max) +'</span></div>');
            $("#"+methods.elem_id+" .time-container").append('<div class="timeline-label-div-partner" style="left:100%;"><span class="timeline-label">' + methods.getNormalTime(methods.tempOptions.maxPartner) +'</span></div>');

            $('.timeline-outer:last').css('border-right', '1px solid #444444');
            $('.timeline-label-div:last,.timeline-label-div-partner:last').hide();

            methods.setBusyTime();
        },

        leftVal : function (selectOptions,i) {
            return (i/(selectOptions.length) * 100).toFixed(2)  +'%';
        },

        getScaleOptions : function(min, max, min1, max1) {

            var count_hours = (max - min)/3600;
            var count_points = count_hours*4;

            var opts = [];
            var temp_value = min;
            var temp_value1 = min1;
            for(var i=0; i<count_points; i++) {
                opts.push({
                    value: temp_value,
                    text: methods.getNormalTime(temp_value),
                    value1: temp_value1,
                    text1: methods.getNormalTime(temp_value1)
                });
                temp_value = temp_value + methods.tempOptions.step;
                temp_value1 = temp_value1 + methods.tempOptions.step;
            }
            return opts;
        },

        getUnixTime : function( time ) {

            var ajaxurl = methods.site_url + 'index.php?option=com_videotranslation&task=getUnixTime';
            var data = {
                time: time
            }

            var unixTime = 0;

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                async: false,
                success: function(response)
                {
                   unixTime = parseInt(response);
                }
            });

            return unixTime;
            //return Math.round(+new Date(time)/1000);
        },

        getTimeFormat : function () {
            return methods.getMonthName(methods.tempOptions.selectedMonth)+" "+methods.tempOptions.selectedDay+", "+methods.tempOptions.selectedYear;
        },

        getMonthName : function (monthNumber) {
            var month=new Array(12);
            month[0]="January";
            month[1]="February";
            month[2]="March";
            month[3]="April";
            month[4]="May";
            month[5]="June";
            month[6]="July";
            month[7]="August";
            month[8]="September";
            month[9]="October";
            month[10]="November";
            month[11]="December";


            return month[monthNumber];
        },

        getMonthNameLocalisation : function (monthNumber) {
            var month=new Array(12);
            month[0]=Joomla.JText._('MOD_VIDEOTRANSLATION_JANUARY');
            month[1]=Joomla.JText._('MOD_VIDEOTRANSLATION_FEBRUARY');
            month[2]=Joomla.JText._('MOD_VIDEOTRANSLATION_MARCH');
            month[3]=Joomla.JText._('MOD_VIDEOTRANSLATION_APRIL');
            month[4]=Joomla.JText._('MOD_VIDEOTRANSLATION_MAY');
            month[5]=Joomla.JText._('MOD_VIDEOTRANSLATION_JUNE');
            month[6]=Joomla.JText._('MOD_VIDEOTRANSLATION_JULY');
            month[7]=Joomla.JText._('MOD_VIDEOTRANSLATION_AUGUST');
            month[8]=Joomla.JText._('MOD_VIDEOTRANSLATION_SEPTEMBER');
            month[9]=Joomla.JText._('MOD_VIDEOTRANSLATION_OCTOBER');
            month[10]=Joomla.JText._('MOD_VIDEOTRANSLATION_NOVEMBER');
            month[11]=Joomla.JText._('MOD_VIDEOTRANSLATION_DECEMBER');

            return month[monthNumber];
        },

        getNormalTime : function (unix_time) {

            var date = new Date(unix_time*1000);

            var hours = date.getUTCHours();
            var minutes = date.getUTCMinutes();

            if(minutes == 0) {
                return methods.twoDigits(hours) + ':' + methods.twoDigits(minutes);
            }
            else {
                return methods.twoDigits(minutes);
            }
        },

        twoDigits : function (value) {
            if(value < 10) {
                return '0' + value;
            }
            return value;
        },

        nowButtonLabel: function() {
            var langTag = jQuery('#currentLanguageTag').text();
            var label ='';
            var suffixLabel = "   <i class='icon-white icon-calendar'></i>";
            if (langTag == 'en') {
                label = "Now";
            };
            if (langTag == 'ru') {
                label = "Сейчас";
            };
        return label + suffixLabel;
    }

//        getJustMinutes : function (unix_time) {
//            var date = new Date(unix_time*1000);
//            var hours = date.getHours();
//            var minutes = date.getMinutes();
//            return methods.twoDigits(minutes);
//        }
    };


    $.fn.timeline = function( method ) {

        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.tooltip' );
        }
    };

})( jQuery );




//Date.prototype.addHours= function(h){
//    this.setHours(this.getHours()+h);
//    return this;
//}
