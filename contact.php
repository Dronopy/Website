<?php
/**
 * Process a simple contact form post and email results
 * Some validation from: http://www.freecontactform.com/email_form.php
 */

	$DEBUG = true;

	$data = $_REQUEST;

	if ($DEBUG)
	{
		$data = array(
			'formEmail'=> 'annieperrier@gmail.com',
			'formName'=> 'Annie',
			'formLocation'=>'Canada',
			'formDescription'=>'Testing the form'
		);
	}

	$ERRORS = array(
					"1" => "Email address required",
					"2" => "Name required",
					"3" => "Location required",
					"4" => "Description required",
					"5" => "Invalid email address",
					"6" => "Invalid name",
					"7" => "Invalid phone",
					"8" => "Invalid location",
					"9" => "Invalid description"
		);

	function clean_string($txt)
	{
		$bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$txt);
	}

	function valid_name($txt)
	{
		$exp = "/^[A-Za-z .',-]{2,100}$/";
		return preg_match($exp, $txt);
	}

	function valid_email($txt)
	{
		$exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
		return preg_match($exp, $txt);
	}

	function valid_phone($txt)
	{
		$exp = '/^[0-9extEXT +.()-]{10,50}$/';
		return preg_match($exp, $txt);
	}

	function valid_location($txt)
	{
		$exp = "/^[A-Za-z .',-]{2,100}+$/";
		return preg_match($exp, $txt);
	}

	function valid_description($txt)
	{
		return strlen($txt) >= 10 && strlen($txt) <= 500;
	}

	function set_error(&$error, $err_id)
	{
		global $ERRORS;
		$error = array("error" => $err_id, "message" => $ERRORS[$err_id]);
		return false;
	}

	function process_data(&$error)
	{
		global $data;

		$message ="A user would like to know more about Drones!\r\n\r\n";

		if (isset($data['formEmail']) && '' != $data['formEmail'])
		{
			$formemail = $data['formEmail'];
			$formemail = clean_string($formemail);
			if (!valid_email($formemail))
				return set_error($error, 5);

			$message .= 'Email: '.$formemail."\r\n";

			if (isset($data['formName']) && '' != $data['formName'])
			{
				$formname = $data['formName'];
				$formname = clean_string($formname);
				if (!valid_name($formname))
					return set_error($error, 6);

				$message .= 'Name: '.$formname."\r\n";
			}
			else
				return set_error($error, 2);

			if (isset($data['formPhone']) && '' != $data['formPhone'])
			{
				$formlocation = $data['formPhone'];
				$formlocation = clean_string($formlocation);
				if (!valid_phone($formlocation))
					return set_error($error, 7);

				$message .= 'Phone: '.$formphone."\r\n";

			}
			// phone is optional

			if (isset($data['formLocation']) && '' != $data['formLocation'])
			{
				$formlocation = $data['formLocation'];
				$formlocation = clean_string($formlocation);
				if (!valid_location($formlocation))
					return set_error($error, 8);

				$message .= 'Location: '.$formlocation."\r\n";
			}
			else
				return set_error($error, 3);

			if (isset($data['formDescription']) && '' != $data['formDescription'])
			{
				$formdescription = $data['formDescription'];
				$formdescription = clean_string($formdescription);
				if (!valid_location($formlocation))
					return set_error($error, 9);

				$message .= 'Description:'."\r\n".$formdescription."\r\n";
			}
			else
				return set_error($error, 4);
		}
		else
		{
			return set_error($error, 1);
		}

		$message .= "\r\n-----\r\nDronopy.com";

		return $message;
	}

	$to = "annieperrier@gmail.com";
	$subject = "Dronopy Contact Form Submission";

	$headers = 'From: "Dronopy Contact Form" <contactform@dropnopy.com>' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

	$res = false;
	$error = false;
	$message = process_data($error);

	if (!$error)
	{
		$res = mail($to, "$subject", $message, $headers);
	}

	$response = array(
					'sent' => $res,
					'error' => $error
		);

	if ($DEBUG)
		print $message;

print json_encode($response);
exit;
