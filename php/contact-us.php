<?php
	// Subscriber details
	$type = strip_tags($_POST['TYPE'])
	$fname = strip_tags($_POST['FNAME']);
	$email = htmlentities($_POST['EMAIL']);
	$phone = strip_tags($_POST['PHONE']);
	$message = strip_tags($_POST['MESSAGE']);

	// ----------------------------------

	//$chimp_apikey = 'd0d2ad1d871b39732832fc36afde5c97-us2';
	$chimp_apikey = getenv('MAILCHIMP_API_KEY');
	//$chimp_list_id = '1880eec8a4';
	$chimp_list_id = getenv('MAILCHIMP_LIST_ID');

	$chimp_node = substr($chimp_apikey,strpos($chimp_apikey,'-')+1);
	$chimp_member_id = md5(strtolower($email));
	$chimp_status = 'subscribed'; // "subscribed","unsubscribed","cleaned","pending"

  $chimp_params = array(
		'email_address' => $email,
		'status' => $chimp_status,
		'merge_fields'  => array(
			'TYPE' => $type,
			'FNAME' => $fname,
			'EMAIL' => $email,
			'PHONE' => $phone,
			'MESSAGE' => $message,
			'SOURCE' => 'CONTACT_US'
		)
	);

	// ----------------------------------

	$url = 'https://' . $chimp_node . '.api.mailchimp.com/3.0/lists/' . $chimp_list_id . '/members/' . $chimp_member_id;
	$json = json_encode($chimp_params);

	$curl = curl_init($url);

	curl_setopt($curl, CURLOPT_USERPWD, 'user:'.$chimp_apikey);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

	error_log ("ABOUT TO MAKE MAILCHIMP REQUEST ... ".$url);

	$response = curl_exec($curl);
	$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	//error_log (print_r($json, true));
	error_log ("RESULT OF REQUEST = ".$http_code);
	//error_log (print_r($response, true));

	curl_close($curl);

	// ----------------------------------

	//$mail_apikey = 'key-1260d070bf9565b4e57ab09e4a3675d4';
	$mail_apikey = getenv('MAILGUN_API_KEY');
	error_log($mail_apikey);
	//$mail_domain = 'app30c959afd6434086bdfec47190d5029b.mailgun.org';
	$mail_domain = getenv('MAILGUN_DOMAIN');
	error_log($mail_domain);

	//$mail_mailbox = 'simon@younggunsskateschool.co.nz';
	$mail_mailbox = getenv('CONTACT_US_MAILBOX');
	$mail_subject = 'Get in Touch ('.$fname.' '.$lname.')';

	$mail_message .= "Enquiry Type: ".$type."\n";
	$mail_message .= "Name: ".$fname."\n";
	$mail_message .= "Email: ".$email."\n";
	$mail_message .= "Phone: ".$phone."\n";
	$mail_message .= "Message: "."\n".$message."\n";

	$mail_params = array(
		'from' => 'admin@'.$mail_domain,
		'to' => $mail_mailbox,
		'subject' => $mail_subject,
		'text' => $mail_message
	);

	// ----------------------------------

	$url = 'https://' . 'api.mailgun.net/v3/' . $mail_domain . '/messages';
	$json = json_encode($mail_params);

	$curl = curl_init($url);

	curl_setopt($curl, CURLOPT_USERPWD, 'api:'.$mail_apikey);
	//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $mail_params);

	error_log ("ABOUT TO MAKE MAILGUN REQUEST ... ".$url);

	$response = curl_exec($curl);
	$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	//error_log (print_r($json, true));
	error_log ("RESULT OF REQUEST = ".$http_code);
	//error_log (print_r($response, true));

	curl_close($curl);
?>
