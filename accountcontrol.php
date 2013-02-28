<?php
require_once("_sms_class.php");

$getvalues = new WordpressAction();

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['save']))
{
	if($_POST['save'] == 0){
		
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$CreateXmlData = '<?xml version="1.0" encoding="utf-8"?><smskredi ka="'.$username.'" pwd="'.$password.'" />';
	
 	$ch 	= curl_init($getvalues->actionUrl());
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST,			 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml'));
	curl_setopt($ch, CURLOPT_POSTFIELDS,     "$CreateXmlData");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);	
	curl_close($ch);
	
	echo $result;
	
	} else if($_POST['save'] == 1) {	

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$table_name = $getvalues->getPrefixAndTable(); // Get table with prefix
	$table_numbers = $getvalues->getPrefix() . "smslistnumbers";
	
	//Create table id not exits
	/*$isok = $getvalues->getVariable("show tables like '$table_name'");
	if( $isok != $table_name){
		
		$sql = 'CREATE TABLE '.$table_name.' (
  					`id` int(1) NOT NULL DEFAULT \'1\',
  					`username` varchar(20) CHARACTER SET utf8 NOT NULL,
  					`password` varchar(20) CHARACTER SET utf8 NOT NULL,
					 `theme` int(1) NOT NULL DEFAULT \'0\',
  					PRIMARY KEY (`id`));
					
				CREATE TABLE '.$table_numbers.' (
  					`id` int(1) NOT NULL AUTO_INCREMENT,
  					`phones` varchar(11) CHARACTER SET utf8 NOT NULL,
					`code` varchar(5) NOT NULL,
 					`validate` varchar(1) NOT NULL DEFAULT \'0\',
  					PRIMARY KEY (`id`));';

	$getvalues->createDb($sql); // Create table query action
	
	// Inserting some values to created table
	$sql_insert = "INSERT INTO ".$table_name." (`id`, `username`, `password`, `theme`, `themetitle`, `themedesc`) VALUES (1, '$username', '$password', 0, 'SMSList', 'Bizden SMS almak istermisiniz?')";
	
	$getvalues->runQuery($sql_insert); // Insert values query action
	
	} else { // Insert values to table if table already exits*/
	
	$sql_insert = "UPDATE $table_name SET username='$username', password='$password' WHERE id=1";
	$isSaved = $getvalues->runQuery($sql_insert); // Insert values query action		
		//}
	}
}else if(isset($_POST['demotitle']) && isset($_POST['demodesc']) && isset($_POST['settheme']))
{
	$title		 = $_POST['demotitle'];
	$description = $_POST['demodesc'];
	$theme		 = $_POST['settheme'];
	
	$table_name = $getvalues->getPrefixAndTable();
	$sql_insert = "UPDATE ".$table_name." SET theme='$theme', themetitle='$title', themedesc='$description' WHERE id=1";
	$result = $getvalues->runQuery($sql_insert); // Insert values query action
	echo $result; 
} else if(isset($_POST['orginator']) && isset($_POST['method']) && isset($_POST['message']) && isset($_POST['customNumber']))
{
	$who 	 = $_POST['orginator'];
	$message = $_POST['message'];
	$method  = $_POST['method'];
	$custom  = $_POST['customNumber'];
	
 if($method == "1"){
	
	$xml_data = '<?xml version="1.0" encoding="utf-8"?>'.
	'<smspack ka="'.$getvalues->getUsername().'" pwd="'.$getvalues->getPassword().'" org="'.$who.'">
	<mesaj>
	<metin>'.$message.'</metin>
	<nums>'. $getvalues->getNumbers() .'</nums>
	</mesaj>	
	</smspack>';
	
	$curl = curl_init($getvalues->sendMessageUrl());
	//curl_setopt($curl, CURLOPT_MUTE,           1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  0);
	curl_setopt($curl, CURLOPT_POST, 		   1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, 	   array('Content-Type: text/xml'));
	curl_setopt($curl, CURLOPT_POSTFIELDS,     "$xml_data");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$xmlResult = curl_exec($curl);
	curl_close($curl);
	
	echo $xmlResult,$xml_data;
 } else if($method == 0) {
	
	$xml_data = '<?xml version="1.0" encoding="utf-8"?>'.
	'<smspack ka="'.$getvalues->getUsername().'" pwd="'.$getvalues->getPassword().'" org="'.$who.'">
	<mesaj>
	<metin>'.$message.'</metin>
	<nums>'. $getvalues->__ExgetNumbers(0,$custom) .'</nums>
	</mesaj>	
	</smspack>';
	
	$curl = curl_init($getvalues->sendMessageUrl());
	//curl_setopt($curl, CURLOPT_MUTE,           1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  0);
	curl_setopt($curl, CURLOPT_POST, 		   1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, 	   array('Content-Type: text/xml'));
	curl_setopt($curl, CURLOPT_POSTFIELDS,     "$xml_data");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$xmlResult = curl_exec($curl);
	curl_close($curl);
	
	echo $xmlResult;
	}
}// Send Message End
/*else if (isset($_POST['DATABASECONTROL'])){
	
$username = $getvalues->getUsername();
$password = $getvalues->getPassword();
if($username == 'ACCOUNTNOTFOUND')
{
	echo 'account_error';
}
	
	}*/
?>