<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class VideoTranslationController extends JControllerLegacy
{

    function grabCountries() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //$text = '<select class="b-time-targeting-timezone__timezone-id"><option value="278">Австралия, Брисбен (GMT +10:00)</option><option value="93">Австралия, Остров Рождества (GMT +07:00)</option><option value="94">Австралия, Перт (GMT +08:00)</option><option value="91">Австралия, Сидней (GMT +10:00)</option><option value="157">Австрия (GMT +02:00)</option><option value="31">Албания (GMT +02:00)</option><option value="22">Алжир (GMT +01:00)</option><option value="247">Американские Виргинские острова (GMT -04:00)</option><option value="211">Ангилья (GMT -04:00)</option><option value="165">Ангола (GMT +01:00)</option><option value="54">Андорра (GMT +02:00)</option><option value="10">Антигуа и Барбуда (GMT -04:00)</option><option value="184">Аргентина (GMT -03:00)</option><option value="115">Аруба (GMT -04:00)</option><option value="241">Багамские острова (GMT -04:00)</option><option value="36">Бангладеш (GMT +06:00)</option><option value="173">Барбадос (GMT -04:00)</option><option value="181">Бахрейн (GMT +03:00)</option><option value="53">Белиз (GMT -06:00)</option><option value="244">Бельгия (GMT +02:00)</option><option value="146">Бенин (GMT +01:00)</option><option value="228">Бермудские Острова (GMT -03:00)</option><option value="103">Болгария (GMT +03:00)</option><option value="102">Боливия (GMT -04:00)</option><option value="239">Босния и Герцеговина (GMT +02:00)</option><option value="217">Ботсвана (GMT +02:00)</option><option value="79">Бразилия, Атибая (GMT -03:00)</option><option value="80">Бразилия, Манаус (GMT -04:00)</option><option value="277">Бразилия, Мосоро (GMT -03:00)</option><option value="156">Британские Виргинские острова (GMT -04:00)</option><option value="51">Бруней (GMT +08:00)</option><option value="200">Буркина-Фасо (GMT +00:00)</option><option value="109">Бурунди (GMT +02:00)</option><option value="60">Бутан (GMT +06:00)</option><option value="161">Вануату (GMT +11:00)</option><option value="78">Ватикан (GMT +02:00)</option><option value="170">Великобритания (GMT +01:00)</option><option value="197">Венгрия (GMT +02:00)</option><option value="65">Восточный Тимор (GMT +09:00)</option><option value="21">Вьетнам (GMT +07:00)</option><option value="160">Габон (GMT +01:00)</option><option value="29">Гаити (GMT -05:00)</option><option value="105">Гайана (GMT -04:00)</option><option value="205">Гамбия (GMT +00:00)</option><option value="210">Гана (GMT +00:00)</option><option value="150">Гватемала (GMT -06:00)</option><option value="48">Гвинея (GMT +00:00)</option><option value="11">Гвинея-Бисау (GMT +00:00)</option><option value="56">Германия (GMT +02:00)</option><option value="175">Гибралтар (GMT +02:00)</option><option value="226">Гондурас (GMT -06:00)</option><option value="90">Гренада (GMT -04:00)</option><option value="76">Гренландия (GMT -02:00)</option><option value="195">Греция (GMT +03:00)</option><option value="121">Грузия (GMT +04:00)</option><option value="245">Гуам (GMT +10:00)</option><option value="225">Дания, Копенгаген (GMT +02:00)</option><option value="224">Дания, Хусар (GMT +01:00)</option><option value="37">Демократическая Республика Конго, Киншаса (GMT +01:00)</option><option value="250">Демократическая Республика Конго, Колвези (GMT +02:00)</option><option value="117">Джибути (GMT +03:00)</option><option value="58">Доминика (GMT -04:00)</option><option value="88">Доминиканская Республика (GMT -04:00)</option><option value="16">Египет (GMT +02:00)</option><option value="164">Замбия (GMT +02:00)</option><option value="176">Зимбабве (GMT +02:00)</option><option value="6">Израиль (GMT +03:00)</option><option value="113">Индонезия, Джакарта (GMT +07:00)</option><option value="114">Индонезия, Джаяпура (GMT +09:00)</option><option value="279">Индонезия, Макассар (GMT +08:00)</option><option value="112">Индонезия, Семиньяк (GMT +08:00)</option><option value="232">Иордания (GMT +03:00)</option><option value="111">Ирак (GMT +03:00)</option><option value="168">Ирландия (GMT +01:00)</option><option value="12">Исландия (GMT +00:00)</option><option value="162">Испания, Мадрид (GMT +02:00)</option><option value="163">Испания, Санта-Крус-де-Тенерифе (GMT +01:00)</option><option value="28">Италия (GMT +02:00)</option><option value="215">Йемен (GMT +03:00)</option><option value="99">Кабо-Верде (GMT -01:00)</option><option value="216">Каймановы острова (GMT -05:00)</option><option value="145">Камбоджа (GMT +07:00)</option><option value="45">Камерун (GMT +01:00)</option><option value="222">Канада, Ванкувер (GMT -07:00)</option><option value="221">Канада, Виннипег (GMT -05:00)</option><option value="276">Канада, Галифакс (GMT -03:00)</option><option value="219">Канада, Йеллоунайф (GMT -06:00)</option><option value="282">Канада, Принс-Альберт (GMT -06:00)</option><option value="283">Канада, Сен-Пьер и Микелон (GMT -02:00)</option><option value="218">Канада, Торонто (GMT -04:00)</option><option value="229">Катар (GMT +03:00)</option><option value="1">Кения (GMT +03:00)</option><option value="83">Кипр (GMT +03:00)</option><option value="190">Кирибати (GMT +12:00)</option><option value="189">Китай (GMT +08:00)</option><option value="212">Колумбия (GMT -05:00)</option><option value="214">Коморские острова (GMT +03:00)</option><option value="24">Коста-Рика (GMT -06:00)</option><option value="25">Кот-д’Ивуар (GMT +00:00)</option><option value="85">Куба (GMT -04:00)</option><option value="141">Кувейт (GMT +03:00)</option><option value="77">Кюрасао (GMT -04:00)</option><option value="193">Лаос (GMT +07:00)</option><option value="110">Латвия (GMT +03:00)</option><option value="8">Лесото (GMT +02:00)</option><option value="86">Либерия (GMT +00:00)</option><option value="116">Ливан (GMT +03:00)</option><option value="68">Ливия (GMT +02:00)</option><option value="71">Литва (GMT +03:00)</option><option value="84">Лихтенштейн (GMT +02:00)</option><option value="223">Люксембург (GMT +02:00)</option><option value="235">Маврикий (GMT +04:00)</option><option value="188">Мавритания (GMT +00:00)</option><option value="35">Мадагаскар (GMT +03:00)</option><option value="202">Македония (GMT +02:00)</option><option value="18">Малави (GMT +02:00)</option><option value="124">Малайзия (GMT +08:00)</option><option value="191">Мали (GMT +00:00)</option><option value="20">Мальдивы (GMT +05:00)</option><option value="50">Мальта (GMT +02:00)</option><option value="158">Марокко (GMT +00:00)</option><option value="143">Мартиника (GMT -04:00)</option><option value="63">Маршалловы острова (GMT +12:00)</option><option value="280">Мексика, Мехикали (GMT -07:00)</option><option value="127">Мексика, Тласкала-де-Хикотенкатль (GMT -05:00)</option><option value="128">Мексика, Чиуауа (GMT -06:00)</option><option value="253">Мексика, Эрмосильо (GMT -07:00)</option><option value="155">Мозамбик (GMT +02:00)</option><option value="187">Монако (GMT +02:00)</option><option value="233">Монголия (GMT +08:00)</option><option value="234">Монголия, Ховд (GMT +07:00)</option><option value="198">Монтсеррат (GMT -04:00)</option><option value="32">Намибия (GMT +01:00)</option><option value="194">Науру (GMT +12:00)</option><option value="199">Нигер (GMT +01:00)</option><option value="66">Нигерия (GMT +01:00)</option><option value="174">Нидерланды (GMT +02:00)</option><option value="179">Никарагуа (GMT -06:00)</option><option value="196">Ниуэ (GMT -11:00)</option><option value="154">Новая Зеландия (GMT +12:00)</option><option value="213">Новая Каледония (GMT +11:00)</option><option value="23">Норвегия (GMT +02:00)</option><option value="262">Норфолк (GMT +10:00)</option><option value="203">Объединенные Арабские Эмираты (GMT +04:00)</option><option value="30">Оман (GMT +04:00)</option><option value="96">Острова Кука (GMT -10:00)</option><option value="238">Пакистан (GMT +05:00)</option><option value="204">Палау (GMT +09:00)</option><option value="33">Палестина (GMT +02:00)</option><option value="47">Панама (GMT -05:00)</option><option value="259">Папуа-Новая Гвинея (GMT +10:00)</option><option value="185">Парагвай (GMT -04:00)</option><option value="74">Перу (GMT -05:00)</option><option value="208">Польша (GMT +02:00)</option><option value="275">Польша, Бобова (GMT +04:00)</option><option value="152">Португалия, Азорские острова (GMT +00:00)</option><option value="151">Португалия, Лиссабон (GMT +01:00)</option><option value="254">Португалия, Фигейра-де-Фош (GMT +02:00)</option><option value="46">Пуэрто-Рико (GMT -04:00)</option><option value="186">Республика Конго (GMT +01:00)</option><option value="201">Руанда (GMT +02:00)</option><option value="206">Румыния (GMT +03:00)</option><option value="42">США, Бедфорд (GMT -05:00)</option><option value="40">США, Гонолулу (GMT -10:00)</option><option value="41">США, Денвер (GMT -06:00)</option><option value="44">США, Джуно (GMT -08:00)</option><option value="39">США, Дэйли-Сити (GMT -07:00)</option><option value="38">США, Луисвилл (GMT -04:00)</option><option value="240">Сальвадор (GMT -06:00)</option><option value="7">Самоа (GMT +13:00)</option><option value="142">Сан-Марино (GMT +02:00)</option><option value="64">Сан-Томе и Принсипи (GMT +00:00)</option><option value="72">Саудовская Аравия (GMT +03:00)</option><option value="167">Сахарская Арабская Демократическая Республика (GMT +00:00)</option><option value="97">Свазиленд (GMT +02:00)</option><option value="209">Северная Корея (GMT +09:00)</option><option value="242">Сейшельские острова (GMT +04:00)</option><option value="123">Сенегал (GMT +00:00)</option><option value="169">Сент-Винсент и Гренадины (GMT -04:00)</option><option value="101">Сент-Китс и Невис (GMT -04:00)</option><option value="153">Сент-Люсия (GMT -04:00)</option><option value="57">Сербия (GMT +02:00)</option><option value="62">Сингапур (GMT +08:00)</option><option value="268">Синт-Мартен (GMT -04:00)</option><option value="27">Сирия (GMT +03:00)</option><option value="87">Словакия (GMT +02:00)</option><option value="236">Словения (GMT +02:00)</option><option value="52">Соломоновы острова (GMT +11:00)</option><option value="183">Сомали (GMT +03:00)</option><option value="107">Судан (GMT +03:00)</option><option value="207">Суринам (GMT -03:00)</option><option value="67">Сьерра-Леоне (GMT +00:00)</option><option value="148">Таиланд (GMT +07:00)</option><option value="125">Танзания (GMT +03:00)</option><option value="5">Того (GMT +00:00)</option><option value="70">Тонга (GMT +13:00)</option><option value="182">Тринидад и Тобаго (GMT -04:00)</option><option value="231">Тувалу (GMT +12:00)</option><option value="227">Тунис (GMT +01:00)</option><option value="180">Турция (GMT +03:00)</option><option value="34">Тёркс и Кайкос (GMT -04:00)</option><option value="69">Уганда (GMT +03:00)</option><option value="126">Уругвай (GMT -03:00)</option><option value="230">Федеративные Штаты Микронезии (GMT +10:00)</option><option value="14">Фиджи (GMT +12:00)</option><option value="26">Филиппины (GMT +08:00)</option><option value="122">Финляндия (GMT +03:00)</option><option value="108">Фолклендские острова (GMT -04:00)</option><option value="15">Франция (GMT +02:00)</option><option value="106">Французская Гвиана (GMT -03:00)</option><option value="171">Французская Полинезия (GMT -10:00)</option><option value="2">Хорватия (GMT +02:00)</option><option value="73">Центрально-Африканская Республика (GMT +01:00)</option><option value="55">Чад (GMT +01:00)</option><option value="9">Черногория (GMT +02:00)</option><option value="172">Чехия (GMT +02:00)</option><option value="281">Чили, Остров Пасхи (GMT -05:00)</option><option value="237">Чили, Сантьяго (GMT -04:00)</option><option value="49">Швейцария (GMT +02:00)</option><option value="192">Швеция (GMT +02:00)</option><option value="100">Эквадор (GMT -05:00)</option><option value="159">Экваториальная Гвинея (GMT +01:00)</option><option value="129">Эритрея (GMT +03:00)</option><option value="19">Эстония (GMT +03:00)</option><option value="104">Эфиопия (GMT +03:00)</option><option value="82">ЮАР (GMT +02:00)</option><option value="75">Южная Корея (GMT +09:00)</option><option value="264">Южный Судан (GMT +03:00)</option><option value="17">Ямайка (GMT -05:00)</option><option value="59">Япония (GMT +09:00)</option></select>';
        //$text = '<option value="166">Абхазия (GMT +04:00)</option><option value="98">Азербайджан (GMT +05:00)</option><option value="13">Армения (GMT +05:00)</option><option value="149">Беларусь (GMT +03:00)</option><option value="120">Казахстан, Актобе (GMT +05:00)</option><option value="119">Казахстан, Алматы (GMT +06:00)</option><option value="4">Киргизия (GMT +06:00)</option><option value="243">Молдова (GMT +03:00)</option><option value="147">Таджикистан (GMT +05:00)</option><option value="3">Туркмения (GMT +05:00)</option><option value="178">Узбекистан (GMT +05:00)</option><option value="61">Украина (GMT +03:00)</option><option value="81">Южная Осетия (GMT +04:00)</option></select>';
        //$text = '<option value="131">Калининград (MSK -01:00)</option><option value="130">Москва</option><option value="133">Екатеринбург (MSK +02:00)</option><option value="270">Омск (MSK +03:00)</option><option value="134">Красноярск (MSK +04:00)</option><option value="135">Иркутск (MSK +05:00)</option><option value="137">Якутск (MSK +06:00)</option><option value="138">Владивосток (MSK +07:00)</option><option value="140">Магадан (MSK +08:00)</option></select>';

        $pattern = '#<option\s+value="([^"]+)">([^<]+)</option>#';
        preg_match_all($pattern,$text,$arr);
        for($i=0;$i<count($arr[2]);$i++) {
            $text = $arr[2][$i];
            $pattern = '#(.+)\(GMT (.+)\)#';
            preg_match($pattern,$text,$arr1);
            $arr[3][$i] = $arr1[1];
            $arr[4][$i] = $arr1[2];
        }
        for($i=0;$i<count($arr[0]);$i++) {
            $query = "INSERT INTO #__vt_countries (`id`,`name`,`time`,`prefix`) VALUES ('".$arr[1][$i]."',".$db->quote($arr[3][$i]).",".$db->quote($arr[4][$i]).",'GMT')";
            $db->setQuery( $query );
            $db->query();

        }
    }

    function orderApprove() {
        $model = $this->getModel ( 'approve' );
        $view =& $this->getView( 'approve', 'html' );

        $view->setModel( $model, true );

        $view->orderApprove();
    }

    function orderrefuse() {


        $model = $this->getModel ( 'reasonsrefuse' );
        $view =& $this->getView( 'reasonsrefuse', 'html' );

        $view->setModel( $model, true );

        $view->orderRefuse();

    }

    function paymentForm() {

        $model = $this->getModel ( 'pay' );
        $view =& $this->getView( 'pay', 'html' );

        $view->setModel( $model, true );

        $view->showForm('paymentForm');

    }


    function paymentSuccess() {
        $model = $this->getModel ( 'pay' );
        $view =& $this->getView( 'pay', 'html' );

        $view->setModel( $model, true );

        $view->showTemplate('paymentsuccess');


    }

    function paymentFalse() {
        $model = $this->getModel ( 'pay' );
        $view =& $this->getView( 'pay', 'html' );

        $view->setModel( $model, true );

        $view->showTemplate('paymentfalse');
    }

    function paymentResponse() {

        $model = $this->getModel ( 'pay' );

        $model->getResponsePost();
        jexit();

    }


    function getRemainingTime() {

        $model = $this->getModel ( 'session' );
        $view =& $this->getView( 'session', 'html' );

        $view->setModel( $model, true );

        echo $view->getRemainingTime();
        jexit();

    }

    function getTimeBeforeSession() {

        $model = $this->getModel ( 'session' );
        $view =& $this->getView( 'session', 'html' );

        $view->setModel( $model, true );

        echo $view->getTimeBeforeSession();
        jexit();

    }


    function getTimezones() {

        $model = $this->getModel ( 'frontpage' );
        $view =& $this->getView( 'frontpage', 'html' );

        $view->setModel( $model, true );

        $view->getTimezones();
        jexit();
    }

    function getMyTimezone() {

        $model = $this->getModel ( 'frontpage' );
        $view =& $this->getView( 'frontpage', 'html' );

        $view->setModel( $model, true );

        echo $view->getMyTimezone();
        jexit();
    }

    function getTranslatorTime() {
        $model = $this->getModel ( 'frontpage' );
        $view =& $this->getView( 'frontpage', 'html' );

        $view->setModel( $model, true );

        echo $view->getTranslatorTime();
        jexit();
    }

    function setBusyTime() {

        $model = $this->getModel ( 'frontpage' );
        $view =& $this->getView( 'frontpage', 'html' );

        $view->setModel( $model, true );

        $view->getBusyTime();
        jexit();
    }

    function addTimeToSession() {
        $model = $this->getModel ( 'cart' );
        $view =& $this->getView( 'cart', 'html' );

        $view->setModel( $model, true );

        $view->display();

        jexit();
    }

    function getUnixTime() {
        $model = $this->getModel ( 'frontpage' );
        $view =& $this->getView( 'frontpage', 'html' );

        $view->setModel( $model, true );

        echo $view->getUnixTime();
        jexit();
    }

    function checkIfSomethingInSession() {
        $model = $this->getModel ( 'cart' );
        $view =& $this->getView( 'cart', 'html' );

        $view->setModel( $model, true );

        echo $view->checkIfSomethingInSession();
        jexit();

    }

    function getPartnerLanguageSelect() {

        $model = $this->getModel ( 'frontpage' );
        $view =& $this->getView( 'frontpage', 'html' );

        $view->setModel( $model, true );

        echo $view->getLanguages();
        jexit();

    }

    function getPartnerSessionLanguage() {
        $model = $this->getModel ( 'frontpage' );
        $view =& $this->getView( 'frontpage', 'html' );

        $view->setModel( $model, true );

        echo $view->getPartnerSessionLanguage();
        jexit();
    }

    function countPriceDependsOnSubject() {

        $model = $this->getModel ( 'userdetails' );
        $view =& $this->getView( 'userdetails', 'html' );

        $view->setModel( $model, true );


        echo $view->countPriceDependsOnSubject();
        jexit();
    }

    function getlanguagepairsinfo() {



        $model = $this->getModel ( 'languagepairs' );
        $view =& $this->getView( 'languagepairs', 'html');

        $view->setModel( $model, true );


        echo $view->get('languagepairsinfo');
        jexit(); die;
    }

    function addCallModeToSession() {
        $model = $this->getModel ( 'cart' );
        $view =& $this->getView( 'cart', 'html' );
        $view->setModel( $model, true );
        $view->display();
        jexit();
    }

    function getCallMode() {
        $model = $this->getModel ( 'frontpage' );
        $view =& $this->getView( 'frontpage', 'html' );
        $view->setModel( $model, true );
        echo $view->getCallMode();
        jexit();
    }

    function sendStat() {
        $model = $this->getModel ( 'cart' );
        $view =& $this->getView( 'cart', 'html' );
        $view->setModel( $model, true );
        $view->display();
        jexit();
    }

    function loginFromApp() {
        $model = $this->getModel ( 'userdetails' );
        $view =& $this->getView( 'userdetails', 'html' );

        $view->setModel( $model, true );

        echo $view->login();
        jexit();

    }

    function chargeUser() {

        $model = $this->getModel ( 'userdetails' );
        $view =& $this->getView( 'userdetails', 'html' );

        $view->setModel( $model, true );

        echo $view->charge();
        jexit();


    }

    function getHistory() {
        $model = $this->getModel ( 'callcenter' );
        $view =& $this->getView( 'cart', 'html' );
        $model->get_history();
        $view->setModel( $model, true );
        //$view->display();
        jexit();
    }
    function getEmail() {
        $model = $this->getModel ( 'callcenter' );
        $view =& $this->getView( 'cart', 'html' );
        $model->get_email();
        $view->setModel( $model, true );
        jexit();
    }

//    function getMyLocation() {
//
//        // Author: www.easyjquery.com
//        $ip = $_SERVER['REMOTE_ADDR'];
//        $file = "./cache/".$ip;
//        if(!file_exists($file)) {
//            $json = file_get_contents("http://api.easyjquery.com/ips/?ip=".$ip."");
//            $f = fopen($file,"w+");
//            fwrite($f,$json);
//            fclose($f);
//        } else {
//            $json = file_get_contents($file);
//        }
//
////        $json = json_decode($json,true);
////        echo "<pre>";
//        echo $json;
//
//        jexit();
//    }
}
