<?php
class Payment
{
	function response($buffer) {
		$_ = new stdClass();
		$r = array();
		$pairs = explode("&",$buffer);
		foreach($pairs as $pair) {
			list($key,$value) = explode("=",$pair);
			
			if (preg_match("/(\w*?)(\d+)/",$key,$matches)) {
				if (!isset($r[$matches[1]])) $r[$matches[1]] = array();
				$r[$matches[1]][$matches[2]] = urldecode($value);
			} else $r[$key] = urldecode($value);
		}
		
		$_->ack = $r['ACK'];
		$_->errorcodes = $r['L_ERRORCODE'];
		$_->shorterror = $r['L_SHORTMESSAGE'];
		$_->longerror = $r['L_LONGMESSAGE'];
		$_->severity = $r['L_SEVERITYCODE'];
		$_->timestamp = $r['TIMESTAMP'];
		$_->correlationid = $r['CORRELATIONID'];
		$_->version = $r['VERSION'];
		$_->build = $r['BUILD'];
		
		$_->transactionid = $r['TRANSACTIONID'];
		$_->amt = $r['AMT'];
		$_->avscode = $r['AVSCODE'];
		$_->cvv2match = $r['CVV2MATCH'];
	
		return $_;
	}
	function send ($transaction) {
	//echo $transaction;
		ob_start();
		$connection = curl_init();
		if (PAYPAL_PRO_TESTMODE == true){
			curl_setopt($connection,CURLOPT_URL,"https://api-3t.sandbox.paypal.com/nvp"); // Sandbox testing
		}else{
			curl_setopt($connection,CURLOPT_URL,"https://api-3t.paypal.com/nvp"); // Live
		}
		
		//$useragent = 'WP e-Commerce plugin';
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($connection, CURLOPT_NOPROGRESS, 1); 
		curl_setopt($connection, CURLOPT_VERBOSE, 1); 
		curl_setopt($connection, CURLOPT_FOLLOWLOCATION,0); 
		curl_setopt($connection, CURLOPT_POST, 1); 
		curl_setopt($connection, CURLOPT_POSTFIELDS, $transaction); 
		curl_setopt($connection, CURLOPT_TIMEOUT, 30); 
		//curl_setopt($connection, CURLOPT_USERAGENT, $useragent); 
		curl_setopt($connection, CURLOPT_REFERER, "https://".$_SERVER['SERVER_NAME']); 
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		$buffer = curl_exec($connection);
		curl_close($connection);
		//echo $buffer;
		$response = $this->response($buffer);
		//ob_end_flush();
		ob_end_clean();
		return $response;
		
	}
	function gateway_paypal_pro($rqstdata ){
		//BUILD DATA TO SEND TO PayPal 
		
		$data = array();
	
		$data['USER'] 					= PAYPAL_PRO_USERNAME;
		$data['PWD'] 					= PAYPAL_PRO_PASSWORD;
		$data['SIGNATURE']				= PAYPAL_PRO_SIGNATURE;
		//$data['VERSION']				= "52.0"; //$data['METHOD']					= "DoDirectPayment";
		//$data['VERSION']				= "65.1"; //$data['METHOD']					= "CreateRecurringPaymentsProfile";
		$data['VERSION']				= $rqstdata['version'];
		$data['METHOD']					= $rqstdata['payment_method'];
		$data['PAYMENTACTION']			= "Sale";
		$data['IPADDRESS']				= $_SERVER["REMOTE_ADDR"];
		$data['RETURNFMFDETAILS']		= "1"; // optional - return fraud management filter data
	
		if(isset($rqstdata['firstname']) && $rqstdata['firstname']!= '')
		{	
			$data['FIRSTNAME']	= $rqstdata['firstname'];
		}
		if(isset($rqstdata['lastname']) && $rqstdata['lastname']!= '')
		{	
			$data['LASTNAME'] = $rqstdata['lastname'];
		}
		if(isset($rqstdata['email']) && $rqstdata['email']!= '')
		{	
			$data['EMAIL']	= $rqstdata['email'];
		}
		if(isset($rqstdata['mobile']) && $rqstdata['mobile']!= '')
		{
			$data['PHONENUM']	= $rqstdata['mobile'];
		}
		if(isset($rqstdata['street']) && $rqstdata['street']!= '')
		{
			$data['STREET'] = $rqstdata['street'];
		}
		if(isset($rqstdata['city']) && $rqstdata['city']!= '')
		{
			$data['CITY'] = $rqstdata['city'];
		}
		if(isset($rqstdata['state']) && $rqstdata['state']!= '')
		{
			$data['STATE'] = $rqstdata['state'];
		}
		if(isset($rqstdata['country']) && $rqstdata['country']!= '')
		{
			$data['COUNTRYCODE'] = $rqstdata['country'];
		}
		if(isset($rqstdata['zipcode']) && $rqstdata['zipcode']!= '')
		{
			$data['ZIP'] = $rqstdata['zipcode'];
		}
		
		
		
		$data['CREDITCARDTYPE'] = $rqstdata['cc_type'];
		$data['ACCT']			= $rqstdata['credit_card'];
		$data['EXPDATE']		= $rqstdata['expiry_month'].$rqstdata['expiry_year'];
		$data['CVV2']			= $rqstdata['card_code'];
	
	
		$total_price = $rqstdata['amount'];
		$data['AMT'] = $total_price;
		
		if($rqstdata['currency']!='')
		{
			$data['CURRENCYCODE'] = $rqstdata['currency'];
		}
		
		
		
		if($rqstdata['is_recurring'] == 1)
		{
			if($rqstdata['startdate']!= '')
			{
				$data['PROFILESTARTDATE'] = $rqstdata['startdate'];
			}
			if($rqstdata['billingperiod']!= '')
			{
				$data['BILLINGPERIOD'] = $rqstdata['billingperiod'];
			}
			if($rqstdata['billingfreq']!= '')
			{
				$data['BILLINGFREQUENCY'] = $rqstdata['billingfreq'];
			}
			if($rqstdata['billingcycles']!= '')
			{
				$data['TOTALBILLINGCYCLES'] = $rqstdata['billingcycles'];
			}
			if($rqstdata['profile_desc']!= '')
			{
				$data['DESC'] = $rqstdata['profile_desc'];
			}
		
		}
		
		$transaction = "";
		foreach($data as $key => $value) {
			if (is_array($value)) {
				foreach($value as $item) {
					if (strlen($transaction) > 0) $transaction .= "&";
					$transaction .= "$key=".urlencode(trim($item));
				}
			} else {
				if (strlen($transaction) > 0) $transaction .= "&";
				
				$transaction .= "$key=".urlencode(trim($value));
			}
		}
		$response = $this->send($transaction);
		
		//echo '--'.$response->ack;exit;
		
		/*echo 'response-<pre>';
			print_r($response);
		echo '</pre>';*/
	
		if($response->ack == 'Success' || $response->ack == 'SuccessWithWarning'){
			//redirect to  transaction page and store in DB as a order with accepted payment
				 
			$return->paypalpro = 'success';
			$return->transactionid = $response->transactionid ;
			$return->amt = $response->amt ;
			
		}else{
            //echo "=========".$response->errorcodes;exit;
            
			$paypal_account_error = false;
			$paypal_error_codes = array('10500','10501','10507','10548','10549','10550','10552','10758','10760','15003');
			foreach($paypal_error_codes as $error_code) {
					if(in_array($error_code, $response->errorcodes)) {
						$paypal_account_error = true;
						break;
					}
			}
			if($paypal_account_error == true) {
				$return->checkout_misc_error_messages[] = 'There is a problem with your PayPal account configuration, please contact PayPal for further information';
				foreach($response->longerror as $paypal_error) {
					$return['checkout_misc_error_messages'][] = $paypal_error;
				}
			} else {
				$return->checkout_misc_error_messages[] ='Sorry your transaction did not go through to Paypal successfully, please correct any errors and try again';
				foreach($response->longerror as $paypal_error) {
					$return->checkout_misc_error_messages[] = $paypal_error;
				}
			}
			$return->paypalpro = 'fail';
		}
		//$response->sessionid = $sessionid;
		return $return;
	
	}
	
}
?>