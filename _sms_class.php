<?php

require_once( dirname(dirname(dirname(dirname(__FILE__)))).'/wp-load.php'); // for wordpress normal functions
require_once( dirname(dirname(dirname(dirname(__FILE__)))).'/wp-admin/includes/upgrade.php'); // for dbDelta() function

global $wpdb;

class WordpressAction {
	
	public function Count() {
		global $wpdb;
		
		$tb = $this->getPrefix() .'smslistnumbers';
		$counter = $wpdb->get_var("SELECT COUNT(*) FROM $tb WHERE validate=1");
		return $counter;
	}
	
	public function getVariables($action) {
	
	global $wpdb;
	
	$table_name = $wpdb->prefix . "smslisttb";
	
	if($action == "miktar"){
		
	$count = $wpdb->query("SELECT id FROM $table_name");
	if($count >= 1){
		
	$username = $wpdb->get_var("SELECT username FROM $table_name");
	$password = $wpdb->get_var("SELECT password FROM $table_name");
	if($username == "" && $password == "")
	{
		return 'ACCOUNTNOTFOUND';
	}
		
	$xmldata = '<?xml version="1.0" encoding="UTF-8"?>
<smskredi ka="'.$username.'" pwd="'.$password.'" />';

	$ch = curl_init($this->actionUrl());
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmldata");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
	
	$res = strpos($output, '$');
	if($res === false)
	{
		return 'error';
	}
	
    $output = str_replace('$', '',$output);
	$output = str_replace('.0', '',$output);
	
	return $output;
	
	} else { return 'ACCOUNTNOTFOUND'; }
	
	} else if($action == "origin"){

    $count = $wpdb->query("SELECT id FROM $table_name");
	if($count >= 1){
		
	$username = $wpdb->get_var("SELECT username FROM $table_name");
	$password = $wpdb->get_var("SELECT password FROM $table_name");
	
	if($username == "" && $password == "")
	{
		return 'ACCOUNTNOTFOUND';
	}
		
	$xmldata = '<?xml version="1.0" encoding="UTF-8"?>
<smsorig ka="'.$username.'" pwd="'.$password.'" />';

	$ch = curl_init($this->orginatorUrl());
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmldata");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
	
	
	$arrayElements = preg_split('/\t+/', $output);
	
	return $arrayElements;
	
	} else 
			{
    			return "error";
			}
	}
}

public function getTheme($var1, $var2)
{
	global $wpdb;
	
	$tb_name = $wpdb->prefix. 'smslisttb';
	
	if($var1 == 0){
	
	$what_is_the_theme = $wpdb->get_var("SELECT `theme` FROM `$tb_name` WHERE 1");
	
	if($var2 == 1){
		return $what_is_the_theme;
		}else if($var2 == 0){
	
			switch($what_is_the_theme)
			{
				case 0:
				return 'BlueW.png';
				break;
				case 1:
				return 'GreenW.png';
				break;
				case 2:
				return 'OrangeW.png';
				break;
				case 3:
				return 'BlackW.png';
				break;
			}
			}
	}else if($var1 == 1)
	{
		if($var2 == 0)
		{
			$what_is_the_theme_title = $wpdb->get_var("SELECT `themetitle` FROM `$tb_name` WHERE 1");
			return $what_is_the_theme_title;
		}else if($var2 == 1)
		{
			$what_is_the_theme_desc  = $wpdb->get_var("SELECT `themedesc` FROM `$tb_name` WHERE 1");
			return $what_is_the_theme_desc;
		}
		
	}
}

public function getPrefix()
{
	global $wpdb;
	return $wpdb->prefix;
}
	
public function getPrefixAndTable()
{
	global $wpdb;
	return $wpdb->prefix.'smslisttb';
}	

public function getVariable($sqlquery)
{
	global $wpdb;
	$returned = $wpdb->get_var($sqlquery);
	return $returned;
}

public function createDb($sqlquery)
{
	global $wpdb;
	dbDelta($sqlquery);
}

public function runQuery($sqlquery)
{
	global $wpdb;
	$isSuccess = $wpdb->query($sqlquery);
}

public function getUsername()
{
	global $wpdb;
	$table = $this->getPrefixAndTable();
	$username = $wpdb->get_var("SELECT username FROM $table WHERE 1");
	if($username == "")
	{
		return 'ACCOUNTNOTFOUND';
	}
	
	return $username;
}

public function getPassword()
{
	global $wpdb;
	$table = $this->getPrefixAndTable();
	$password = $wpdb->get_var("SELECT password FROM $table WHERE 1");
	if($password == "")
	{
		return 'ACCOUNTNOTFOUND';
	}
	return $password;
}

public function getNumbers()
{
	global $wpdb;
	
	$table = $this->getPrefix().'smslistnumbers';
	$results = $wpdb->get_results("SELECT phones FROM $table WHERE validate=1");
	
foreach($results as $newRe)
	{
		$numberPool .= $newRe->phones.',';
	}
	
	$numberPool = substr($numberPool, 0, -1);
	return $numberPool;
}


public function __ExgetNumbers($val_ , $val__)
{
	global $wpdb;
	
	$table = $this->getPrefix().'smslistnumbers';
	$results = $wpdb->get_results("SELECT phones FROM $table WHERE validate=1 Limit $val_,$val__");
	
foreach($results as $newRe)
	{
		$numberPool .= $newRe->phones.',';
	}
	
	$numberPool = substr($numberPool, 0, -1);
	return $numberPool;
}

public function getWidgetTitle()
{
	$table_name = $this->getPrefixAndTable();
	$widgetTitle = $this->getVariable("SELECT themetitle FROM $table_name");
	return $widgetTitle;
}

public function getWidgetDescription()
{
	$table_name = $this->getPrefixAndTable();
	$widgetTitle = $this->getVariable("SELECT themedesc FROM $table_name");
	return $widgetTitle;
}

public function actionUrl()
{
	return 'https://smsgw.mutlucell.com/smsgw-ws/gtcrdtex';
}

public function orginatorUrl()
{
	return 'https://smsgw.mutlucell.com/smsgw-ws/gtorgex';
}

public function sendMessageUrl()
{
	return 'https://smsgw.mutlucell.com/smsgw-ws/sndblkex';
}

public function SendValidateMessage($phoneNumber, $validationCode)
{
	$origin = $this->getVariables('origin');
	
	//$orRe = preg_split('/\s+/', $origin);
	/*foreach($origin as $parse)
		{
			if($parse != ""){
			$ak = $parse;
			break;
		}
         }*/
	
	$xml_data = '<?xml version="1.0" encoding="utf-8"?>'.
	'<smspack ka="'.$this->getUsername().'" pwd="'.$this->getPassword().'" org="'.$origin[0].'">
	<mesaj>
	<metin>Dogrulama Kodunuz : '.$validationCode.'</metin>
	<nums>'. $phoneNumber .'</nums>
	</mesaj>	
	</smspack>';
	
	$curl = curl_init($this->sendMessageUrl());
	//curl_setopt($curl, CURLOPT_MUTE,           1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  0);
	curl_setopt($curl, CURLOPT_POST, 		   1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, 	   array('Content-Type: text/xml'));
	curl_setopt($curl, CURLOPT_POSTFIELDS,     "$xml_data");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$xmlResult = curl_exec($curl);
	curl_close($curl);
	
	return $xmlResult;
}

function createFirstDB()
{
	$table_name = $this->getPrefixAndTable();
	$table_name_numbers = $this->getPrefix().'smslistnumbers';
	$isok = $this->getVariable("show tables like '$table_name'");
	if( $isok != $table_name){
	$sql = 'CREATE TABLE '.$table_name.' (
  					`id` int(1) NOT NULL AUTO_INCREMENT,
  					`username` varchar(20) CHARACTER SET utf8 NOT NULL,
  					`password` varchar(20) CHARACTER SET utf8 NOT NULL,
  					`theme` int(1) NOT NULL DEFAULT \'0\',
  					`themetitle` varchar(17) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  					`themedesc` varchar(59) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  					PRIMARY KEY (`id`));
					
				CREATE TABLE '.$table_name_numbers.' (
  					`id` int(1) NOT NULL AUTO_INCREMENT,
  					`phones` varchar(11) CHARACTER SET utf8 NOT NULL,
					`adsoyad` varchar(30) NOT NULL,
					`code` varchar(5) NOT NULL,
 					`validate` varchar(1) NOT NULL DEFAULT \'0\',
  					PRIMARY KEY (`id`));';

	$this->createDb($sql); // Create table query action
	
	// Inserting some values to created table
	$sql_insert = "INSERT INTO ".$table_name." (`id`, `username`, `password`, `theme`, `themetitle`, `themedesc`) VALUES (1, '$username', '$password', 0, 'SMSList', 'Bizden SMS almak istermisiniz?')";
	
	$this->runQuery($sql_insert); // Insert values query action
	}
}

}// Class End
?>