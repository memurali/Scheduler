<?php
App::build(array('Vendor' => array(APP . 'Vendor' . DS . 'stripe-php' . DS)));
error_reporting(E_ALL ^ E_NOTICE);
class PaymentsController extends AppController
{   
    var $helpers = array('Html', 'Form', 'Js', 'Paginator');    
    public $components = array('Paginator', 'RequestHandler', 'Stripe');
    public $uses = array('Tblemployee_availability','Tblusers','Tblschedule','Tblbusinesshours','Tblholidays','Tblorganization','Tblpaymentauth','Tblpaymentgateway','Tblpayment','Tblpricing');
	function beforeFilter()
    {
        $this->Auth->allow('shopping_cart');
    }
	function shopping_cart()
	{
		$this->Session->write('orgid', 1);
		$this->Session->write('userid', 1);
		$this->layout = false;
		
		$orgid = $this->Session->read('orgid');
		//$userid = $this->Session->read('userid');
		$this->set('orgid',$orgid);
		/*$userid   = $this->Auth->user('Userid');
		$this->set('userid',$userid);
		$action=$this->request->params['action'];
		$this->set('action',$action);
		$currentdate = date('Y-m-d H:i:s');
		$totprice = $this->Session->read('totprice_arr');
		$price = array_sum($totprice);*/
		$price = 10;		
		$Secret_Key = Configure::read('Stripe.TestSecret');
		$Public_Key = Configure::read('Stripe.TestPublic');
		$this->set('publickey',$Public_Key);
		
		if($this->request->is('ajax'))
		{
			
			if($this->request->data('mode')=='formsubmit')
			{
				parse_str( $_POST[ 'formvals' ], $formdata );
				$token  = $formdata['stripeToken'];
				
				//set api key
				$stripe = array(
					"secret_key"      => $Secret_Key,
					"publishable_key" => $Public_Key
				);
				\Stripe\Stripe::setApiKey($stripe['secret_key']);
				
				if($formdata['customerid']!='')
				{
					$customerid = $formdata['customerid'];
				}
				else
				{
					//get token, card and user info from the form
					$email   = 'lakshmi@progressive-solution.com';
					$card_num = $formdata['card_num'];
					$card_cvc = $formdata['cvc'];
					$data = array(
							'email' => $email,
							'source'  => $token
					);
					$create_cus = $this->createCustomer($data);
					if($create_cus['message']!='Success')
					{
						echo $create_cus['message'];	
						exit;
					}
					else
					{
						$customerid = $create_cus['response']['id'];
					}
				}
				
				/*if($formdata['update_card']!='')
				{
					$fields=array(
						'source' => $token
					);
					$update_card = $this->updateCard($customerid, $fields);
					if($update_card['status']=='error')
					{
						echo 'Update card status  '.$update_card['status'];
					}
					
				}*/
				if($price!='')
				{
					/*** convert cent for stripe payment ****/
					$stripe_price = round($price*100);
					$orderid = 1;
					$data_charge = array(
						'customer' => $customerid,
						'amount'   => $stripe_price,
						'currency' => 'usd',
						'description' => $orderid
					);
					$charge = $this->createCharge($data_charge,$customerid,$price);
					if($charge['status']=='success')
					{
						echo 'Payment status '.$charge['status'];
					}
					else
					{
						echo 'Payment status '.$charge['status'];	
					}
					
				}
				exit;
			}
		}
		if($orgid!='')
		{
			
			// check customer is already exist
			$select_user = "SELECT `Authkey` FROM `tblpaymentauth` WHERE Orgid='".$orgid."' LIMIT 1";
			$customerid_arr = $this->Tblpaymentauth->query($select_user);
			if(count($customerid_arr)==0)
			{
				$this->set('exist','no');
			}
			else
			{
				$customerid = $customerid_arr[0]['tblpaymentauth']['Authkey'];
				$this->set('customerid',$customerid);
				$this->set('exist','yes');
			}
		}
		
		/**** cart deatils    *******/
		/*$all_desc = $this->Session->read('description_arr');
		$all_count = $this->Session->read('totcount_arr');
		$all_price = $this->Session->read('totprice_arr');		
		$this->set("cart_desc",$all_desc);
		$this->set("cart_count",$all_count);
		$this->set("cart_price",$all_price);*/
	}
	function createCustomer($data)
	{
		$orgid = $this->Session->read('orgid');
		$currentdate = date('Y-m-d H:i:s');
		$customer = $this->Stripe->createCustomer($data);
		$customerid = 	$customer['response']['id']	;
		if($customerid!='')
		{
			$gateway_id = $this->get_gatewayid();
			$data_save = array(
					"Gatewayid" => $gateway_id,
					"Orgid" => $orgid,
					"Authkey" => $customerid,
					"Dateupdated"=>$currentdate
				);
				$this->Tblpaymentauth->save($data_save);
		}
		return $customer;
		
	}
	function createCharge($data_charge,$customerid,$price)
	{
		$orgid = $this->Session->read('orgid');
		$charge = $this->Stripe->charge($data_charge,$customerid);
		if($charge['status']=='success')
		{
			$currentdate = date('Y-m-d H:i:s');
			$gateway_id = $this->get_gatewayid();
			$data_save = array(
					"Orgid" => $orgid,
					"Gatewayid" => $gateway_id,
					"Amount" => $price,
					"Payment_status" => 'Success',
					"Dateupdated"=>$currentdate
				);
			$this->Tblpayment->save($data_save);
		}
		return $charge;
	}
	function updateCard($customerid, $fields)
	{
		$updateCard = $this->Stripe->updateCustomer($customerid, $fields);
		return $updateCard;
	}
	function get_gatewayid()
	{
		$sel_gateway = "SELECT `Gatewayid` FROM `tblpaymentgateway` WHERE `Active` = 'Y'";
		$gateway_arr = $this->Tblpaymentgateway->query($sel_gateway);
		$gateway_id = $gateway_arr[0]['tblpaymentgateway']['Gatewayid'];
		return $gateway_id;
	}
	
	
	
	
	
	
	
}
