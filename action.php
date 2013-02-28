<?php
require_once("_sms_class.php");

$wp = new WordpressAction();

if(isset($_POST['phone']) && isset($_POST['name']) && isset($_POST['action']))
{
	$phoneNumber = $_POST['phone'];
	$userName 	 = $_POST['name'];
	$action		 = $_POST['action'];
	
	if($action == "add")
	{
		$tb = $wp->getPrefix() . 'smslistnumbers';
		$isFound = $wp->getVariable("SELECT id FROM $tb WHERE phones=$phoneNumber");

		if($isFound != "")
		{
			echo '0';
		}
		else
		{
			$getValidationCode = rand(1111, 99999);
			
			$isSended = $wp->SendValidateMessage($phoneNumber, $getValidationCode); // Sending validation code to phone number
			if(strpos($isSended , '#') !== false) // Checking is message sended successfully
			{
				
			$returnVal = $wp->runQuery("INSERT INTO $tb (`phones`, `adsoyad`, `code`) VALUES ('$phoneNumber','$userName','$getValidationCode')");
				
				echo '1'; // Return 1 if message sended successfully
			} else {
				//echo $isSended;
				echo '2'; // Return 2 if message not sended successfully
			}
		}
		
	}else if($action == "remove")
	{
		$tb = $wp->getPrefix() . 'smslistnumbers';
		$isFound = $wp->getVariable("SELECT id FROM $tb WHERE phones=$phoneNumber");
		
		if($isFound != "")
		{
			$wp->runQuery("DELETE FROM $tb WHERE id=$isFound");
			echo '1';
		}
		else
		{
			echo '0';
		}
		
	}
}else if(isset($_POST['phone']) && isset($_POST['validationCode']))
{
	$phone = $_POST['phone'];
	$code  = $_POST['validationCode'];
	
	$tb = $wp->getPrefix(). 'smslistnumbers';
	
	$getId = $wp->getVariable("SELECT id FROM $tb WHERE code=$code");
	if($getId != "")
	{
		$wp->runQuery("UPDATE `$tb` SET `validate`=1 WHERE id=$getId");
		
		echo '1';
	} else {
		echo '0';
		}
}


?>