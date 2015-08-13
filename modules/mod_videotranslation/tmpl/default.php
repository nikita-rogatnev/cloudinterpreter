<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>



<div class="custom promo nomargintop nopaddingtop nomarginbottom nopaddingbottom new-module-videotranslation">

<!--    <input onclick="javascrript: document.location.href='index.php?option=com_videotranslation&view=ondemand';" type="button" class="readon" value="--><?php //echo JText::_('MOD_VIDEOTRANSLATION_ON_DEMAND');?><!--" style="left: 20px; top: 20px; z-index: 1; position: absolute;">-->

<!--    <input onclick="javascrript: alert('aaa');" type="button" class="readon" value="--><?php //echo JText::_('MOD_VIDEOTRANSLATION_ADD_MONEY');?><!--" style="right:33px; top: 20px; z-index: 1; position: absolute;">-->


    <div class="rt-center largemargintop largemarginbottom smallpaddingbottom"><span style="display: none;">&nbsp;</span>
        <h1 class="largemargintop largemarginbottom medpaddingbottom nopaddingbottom" style="padding-bottom: 0px !important;"><span style="font-family: arial,helvetica,sans-serif;"><span style="font-size: 36px;"><span style="display: none;" id="cke_bm_98S">&nbsp;</span><?php echo JText::_('MOD_VIDEOTRANSLATION_MI_SNIMAEM_IAZIKOVOI_BARIER');?><span style="display: none;" id="cke_bm_98E">&nbsp;</span></span></span></h1>
        <span style="display: none;">&nbsp;</span>
        <h4 class="nobold" style="line-height:25px;"><em><?php echo JText::_('MOD_VIDEOTRANSLATION_PEREGOVORI_LICOM_K_LICU_IZ_LUBOI_TOCHKI');?></em></h4>


        <span style="font-size: 20px;"><?php //echo JText::_('MOD_VIDEOTRANSLATION_JUST_SELECT_DATA_AND_CALL_TO_TRANSLATOR');?></span>
    </div>

</div>
<div id='allElements' style="margin-left: 15%">
        <div class="gantry-width-33 gantry-width-block hidden-phone videotranslation-block" style="width: auto; margin-right: 5px;">
            <div class="gantry-width-70 gantry-width-block" style="margin-left: 0px; width: 164px;">
                <div class="you_speak">
                    <div class="styled-select24">
                    <select name="sortTable" id="you_speak_select" class="input-medium">
                            <?php echo JHtml::_('select.options', $languages, 'value', 'text',$selected);?>
                        </select>
                        </div>
                </div>
            </div>
        </div>
    <div class="gantry-width-33 gantry-width-block hidden-phone videotranslation-block" style="margin-left: 0px; width: auto; margin-right: 5px;">
        <div class="gantry-width-70 gantry-width-block">
            <div class="your_partner_speak" >
                <div class="styled-select24">
                    <select name="sortTable" id="your_partner_speak_select" class="input-medium">
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="gantry-width-33 gantry-width-block hidden-phone videotranslation-block" style="width: auto; margin-right: 5px;">
        <div class="gantry-width-70 gantry-width-block" style="margin-left: 0px; width: auto;">
            <div class="nowBut" style="padding-top: 20px;">
                <button class="btn newButton" id='nowBtn' onclick="showCalend();" ><?php echo JText::_('MOD_VIDEOTRANSLATION_NOW_BUTTON');?>   <i class="icon-white icon-calendar"></i></button>
               
                <div id="datepicker" style="width: 248px; display:none;" ></div>
                <div id="startTime">00:00:00</div>
                <div id="endTime">23:59:59</div>

            </div>
        </div>
    </div>
    <div class="gantry-width-33 gantry-width-block hidden-phone videotranslation-block" id='videocall-block' style="width: auto; margin-right: 5px;">
        <div class="gantry-width-70 gantry-width-block" style="margin-left: 0px; width: auto;">
           <div class="gantry-width-70 gantry-width-block">
            <div class="callBut" style="padding-top: 20px;">
                <div class="styled-select24">
                    <select name="sortTable" id="call_mode_select" class="input-medium">
                        <option value='2'><?php echo JText::_('MOD_VIDEOTRANSLATION_VIDEOCALL_MODE_SELECT');?></option>
                        <option value='1'><?php echo JText::_('MOD_VIDEOTRANSLATION_VIDEOCONFERENCE_MODE_SELECT');?></option>
                   </select>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="gantry-width-33 gantry-width-block hidden-phone videotranslation-block" style="width: auto; margin-right: 5px;">
            <div class="gantry-width-70 gantry-width-block" style="margin-left: 0px; width: auto;">
                <div class="nowBut" style="padding-top: 20px;">
                    <button class="btn newButton call-btn callTranslatorNow" id='call_interpreter_now'><?php echo JText::_('MOD_VIDEOTRANSLATION_CALL_TO_INTERPRETER_BUTTON');?> </button>
                </div>

                <div class="nowBut" style="padding-top: 20px;">
                    <button class="btn newButton call-btn callTranslatorNow" id='call_sign_language_now'><?php echo JText::_('MOD_VIDEOTRANSLATION_CALL_TO_SIGN_LANGUAGE_BUTTON');?> </button>
                </div>

            </div>
    </div>
    <div class="gantry-width-33 gantry-width-block hidden-phone videotranslation-block" style="margin-left: 0px; auto; display:none;">
        <div class="gantry-width-spacer">
            <div class="gantry-width-30 gantry-width-block" style="text-align: left; width: 87px;">
                <div class="largemarginright">
                    <div class="rt-num">2</div>
                </div>
            </div>
            <div class="gantry-width-70 gantry-width-block">
                <h5 style="text-transform: none;"><?php echo JText::_('MOD_VIDEOTRANSLATION_SELECT_WHEN');?></h5>

                
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>
    <div class="gantry-width-33 gantry-width-block hidden-phone videotranslation-block" style="position: relative; margin-left: 0px; width: auto; display:none;" id='planBlock'>
        <div class="gantry-width-spacer">
            <div class="gantry-width-70 gantry-width-block" style="margin-left: 10px; width: 213px;" >
                <h5 style="text-transform: none;"><?php echo JText::_('MOD_VIDEOTRANSLATION_PAY');?></h5>

                <div id="cart">
                    <?php
                    $lang = JRequest::getVar('lang');

                    if(count($items['orders'])) {

                        ?><div><?php echo JText::_('MOD_VIDEOTRANSLATION_YOU_HAVE_SELECTED');?></div>

                        <?php for($i=0;$i<count($items['orders']);$i++) {?>
                            <div style="width: 200px;">
                                <div style="float: left; margin-right: 20px;"><?php echo maxsite_the_russian_time(date('j F H:i',$items['orders'][$i]->start),$lang);?> - <?php echo date('H:i',$items['orders'][$i]->end);?></div>
                                <div style="float: left; color: #B1231F; cursor: pointer;" class="removeTimeFromSession" time="<?php echo $items['orders'][$i]->start; if(isset($items['orders'][$i]->time)) echo ',',implode(',',$items['orders'][$i]->time);?>">x</div>
                            </div>
                            <div style="clear: both;"></div>
                        <?php }?>
                        <div class="cart-amount">
                            <?php echo JText::_('MOD_VIDEOTRANSLATION_YOUR_AMOUNT');?>
                            <?php echo $items['amount'];?> RUB</div>
                    <?php }?>

                    <?php if(!count($items['orders'])) {
                        echo JText::_('MOD_VIDEOTRANSLATION_CART_IS_EMPTY');
                    }?>

                </div>

                <div id="divbuttonOrder">
                    <input type="button" value="<?php echo JText::_('MOD_VIDEOTRANSLATION_NEXT');?>" id="buttonOrder" class="readon">
                </div>

            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>
    <div class="clear">&nbsp;</div>
</div>


<div id="currentLanguage" style="display: none;"><?php echo $currentLanguage;?></div>
<div id="currentLanguageTag" style="display: none;"><?php echo $currentLanguageTag;?></div>

<div class="timeline-window-container">
    <div id="timeline-window"></div>
</div>

            <!-- Modal -->
            <div class="modal fade bs-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
<!--                        <div class="modal-header">-->
<!--                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
<!--						    <div class="timeIsOutText" style="display: none; position: fixed; right: 130px;">-->
<!--						                       Время бесплатного тестирования истекает. У вас осталось меньше 1 минуты!-->
<!--						                   </div>-->
<!--                            <div id="defaultCountdown1"></div>-->
<!--                            <div class="modal-title" id="myModalLabel"></div>-->
<!---->
<!--                        </div>-->
                        <div class="modal-body " style="padding: 10px; position: relative;" >

                            <div id="remotes"></div>
                            <div style="clear: both;"></div>

                            <div style="float: left;">
                                <div>
                                    <video id="localVideo"></video>
                                    <div class="OT_video-poster"></div>
                                </div>

                                <div id="vidCtrls" style="opacity: 1;">
                                    <img src="<?php echo JURI::base(); ?>modules/mod_videotranslation/img/vid0.png" onclick="publishVideo(this);" id="pubVid" style="width: 20px;" alt="Toggle video" title="Toggle video" class="active">
                                    <img src="<?php echo JURI::base(); ?>modules/mod_videotranslation/img/voice0.png" id="pubAud" onclick="publishAudio(this);" style="width: 10px;margin-left: 12px;" alt="Toggle audio" title="Toggle audio" class="active">
                                </div>
                            </div>
							
<!--				            <div style="float: left;" class="chat">-->
<!--                                <div class="emailForFreeMinutes" style="color: white; width: 250px; margin-bottom: 20px; text-align: left;">-->
<!--                                    Оставьте свой e-mail и получите дополнительные бесплатные минуты-->
<!--                                    <div>-->
<!--                                        <input type="text" name="emailForFreeMinutes" id="emailForFreeMinutes" style="width: 150px; min-width: 150px;">-->
<!--                                    </div>-->
<!--                                    <div style="float: left;">-->
<!--                                        <input type="button" value="send" id="emailForFreeMinutesButton">-->
<!--                                    </div>-->
<!--                                    <div style="clear: both;"></div>-->
<!--                                </div>-->
<!--								-->
<!--								-->
<!--				                  <div id="log"></div>-->
<!--                                  <div style="float: left;">-->
<!--                                      <input type="text" id="input" autofocus>-->
<!--                                  </div>-->
<!--				                  <div style="float: left;">-->
<!--				                    <input type="submit" id="send" value="Send">-->
<!--                                  </div>-->
<!--				              </div>-->


                            <div style="clear: both;"></div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="infoAboutMic" style="display: none;">
                <div id="infoAboutMicContent">
                    <p>
                        Нажмите «Разрешить» в сплывающем окне вашего браузера. Если вы не видите подсказку, пожалуйста обновите страницу.
                    </p>
                    <img class="bt-space" src="<?php echo JURI::base(); ?>modules/mod_videotranslation/img/prompt_ifdenied.png" alt="chrome webrtc approve" width="100%;">
                    <br />
                    <br />
                    <br />
                    <p>
                        Если вы нажали «Запретить», вы можете изменить эту настройку, нажав на иконку, как указано на картинке.
                    </p>
                    <img src="<?php echo JURI::base(); ?>modules/mod_videotranslation/img/prompt.png" alt="webrtc mic if denied" width="100%;">
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade bs-example-modal-sm" id="busyOperators" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="busyOperatorsLabel">Переводчики заняты</h4>
                        </div>
                        <div class="modal-body" style="height: 165px;">
                            Переводчик освободится в течение 3 минут. Подождите или
                            оставьте ваш e-mail  и мы организуем для вас видеовстречу с переводчиком»

                            <div style="float: left; padding:10px 10px 0px 0px; height: 20px; margin-left: 30px;">
                                <input id="emailAddress" type="text" class="newsletter-text" name="email" value="" style="border: 1px solid #e5e5e5;background: none; color: #333;" placeholder="ваш e-mail адрес">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                            <button type="button" class="btn btn-primary" id="queueEmail">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
<div style="visibility: hidden;">Свободные переводчики:
                <span id="interpretersInfo"></span>
                <span id="signInterpretersInfo"></span>
                <span id="noMediaInfo"><?php echo JText::_('MOD_VIDEOTRANSLATION_NO_MEDIA');?></span>
</div>
<script type="text/javascript">
    var base_url = '<?php echo JURI::base(); ?>';
</script>