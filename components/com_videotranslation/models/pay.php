<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelitem');

class VideoTranslationModelPay extends JModelForm
{

    public function getPaykeeperForm($data = array(), $loadData = true)
    {

        $jinput = new JInput;
        $post = $jinput->get('jform','','array');
        $order_sum = $post['order_sum'];

        $user = JFactory::getUser();
        $user_id = $user->id;

        $orderid = $this->getOrderId();


        // Get the form.
        $payment_parameters = http_build_query(array( "clientid"=>$user_id,"orderid"=>$orderid,"sum"=>$order_sum));
        $options = array("http"=>array(
        "method"=>"POST",
        "header"=>"Content-type: application/x-www-form-urlencoded", "content"=>$payment_parameters
        ));
        $context = stream_context_create($options);
        return file_get_contents("http://62.76.184.86/order/inline", false, $context);
    }

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_videotranslation.pay', 'pay', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }

    protected function loadFormData()
    {

    }

    public function getOrderId() {

        $user = JFactory::getUser();
        $user_id = $user->id;

        $jinput = new JInput;
        $post = $jinput->get('jform','','array');
        $order_sum = $post['order_sum'];


        $row = array();

        $row['user_id'] = $user_id;
        $row['sum'] = $order_sum;

        $payments =& JTable::getInstance('payments', 'VideoTranslationTable');




        if (!$payments->bind( $row )) {
            return JError::raiseWarning( 500, $row->getError() );
        }

        if (!$payments->store()) {
            JError::raiseError(500, $row->getError() );
        }


        return $payments->id;
    }

    public function getResponsePost() {

        $secret_seed = '78a5550430521d64d9fb5d8cd3f9b690';

        $payment_id = JRequest::getInt('id');
        $sum = JRequest::getFloat('sum');
        $clientid = JRequest::getInt('clientid');
        $order_id = JRequest::getInt('orderid');
        $key = JRequest::getString('key');

        if ($key != md5 ($payment_id . sprintf ("%.2lf", $sum).
                $clientid.$order_id.$secret_seed))
        {
            echo "Error! Hash mismatch";
            exit;
        }

        $payments =& JTable::getInstance('payments', 'VideoTranslationTable');

        $payments->load($order_id);


        if($payments->status == 0 && $payments->payment_id ==0 && $payments->user_id == $clientid) {

            $row = array();

            $row['status']=1;
            $row['sum']=$sum;
            $row['payment_id']=$payment_id;

            if (!$payments->bind($row)) {
                return JError::raiseWarning( 500, $row->getError() );
            }

            if (!$payments->store()) {
                JError::raiseError(500, $row->getError() );
            }

            $credits =& JTable::getInstance('credit', 'VideoTranslationTable');

            $creditId = $credits->getIdByUserId($clientid);

            $row = array();

            if($clientid) {
                $credits->load($creditId);
            }
            else {
                $row['id'] = '';
            }

            $row['user_id'] = $clientid;
            $row['amount'] = $credits->amount + $sum;

            if (!$credits->bind($row)) {
                return JError::raiseWarning( 500, $row->getError() );
            }

            if (!$credits->store()) {
                JError::raiseError(500, $row->getError() );
            }

        }


        $hash = md5($payment_id.$secret_seed);
        echo "OK $hash";

//        echo "<pre>";
//        print_r($payments->id); die;

        // multiple recipients
//        $to  = 'kosmos.by@gmail.com'; // note the comma
//
//
//        // subject
//        $subject = 'post response';
//
//        //$string = print_r($_REQUEST);
//
//        // message
//        //$message= "<pre>";
//        $message = http_build_query($_REQUEST);
//
//        // Mail it
//        mail($to, $subject, $message);
    }

}
