<?php
/*
Plugin Name:	SMSList
License:		GPL3
Plugin URI:		http://gayretteknoloji.com/
Description:	İletilerinizi, sms olarak kitlelere ulaştırın!.
Author:			GayretSoft
Author URI:		http://gayretteknoloji.com/
Version:		1.0.0
*/

/*  
	Copyright 2013  GayretSoft  (email : software@gayretsoft.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('VERSION', '1.0.0');
require_once("accountcontrol.php");
require_once("_sms_class.php");

global $wpdb;

$createDB = new WordpressAction();
$createDB->createFirstDB();


function SMSListMenu()
{
add_menu_page('', __('SmsList'), 'manage_options', 'SmsList', 'SMSListAbout', plugin_dir_url(__FILE__).'logo.png'); // Main Page
	
add_submenu_page('SmsList', __('SmsList Ayarlar'), __('Ayarlar'), 'manage_options', 'SMSListOptions', 'SMSListOptions'); // Options Page
	
add_submenu_page('SmsList',__('Sms Gönder'), __('Sms Gönder'), 'manage_options', 'SendMessage', 'SMSListSend'); // Message Send Page
}

/// Boot ///

function boot()
{
add_action('admin_head', 'javaScript');
add_action('admin_enqueue_scripts', 'registerScripts');
add_action('wp_enqueue_scripts', 'register_main_styles');
}

/// StyleSheet ///

function registerScripts()
{
wp_enqueue_style('style-css', plugin_dir_url(__FILE__). 'css/style.css', array(), VERSION);
wp_enqueue_script('action-js', plugin_dir_url(__FILE__) . 'js/action.js', array(), VERSION);
}

function register_main_styles()
{
if(!is_admin()){
	javaScript();
	wp_enqueue_script('action-number-js', plugin_dir_url(__FILE__) . 'js/numberAction.js', array(), VERSION);
	wp_register_style('smslist-style-css', plugin_dir_url(__FILE__).'css/style.css', array(), VERSION, all);
	wp_enqueue_style('smslist-style-css');
}
}

/// JavaScript ///

function javaScript() 
{
echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>';
}

/// Adding widget to wordpress blog main page ///

function addWidget()
{
	$getvar = new WordpressAction();
	
	echo '<div id="smsListWidget" style="background:url('.plugin_dir_url(__FILE__).'themes/';
	echo $getvar->getTheme(0,0);
	echo ') no-repeat; 	width:230px; height:280px; background-size:auto;">
	<div align="center">
	<div id="widgetTitle" >'.$getvar->getWidgetTitle().'</div>
	
	<div id="animate">
	<div id="widgetDescription">'.$getvar->getWidgetDescription().'</div>
	
	<div id="usernameD">Adınız ve Soyadınız</div>
	<div id="inputField"><input type="text" id="usernameField" placeholder="Adınız ve Soyadınız..."></div>
	<div id="phoneD">Telefon Numaranız</div>
	<div id="inputField"><input type="text" id="phoneField" placeholder="Telefon numaranız..."></div>
	
	<div id="error">Bu numara kayıtlı!.</div>
	<div id="errorRemove">Numara bulunamadı!.</div>
	<div id="successRemove">Numara kaldırıldı.</div>
	<div id="messageError">Doğrulama kodu gönderilemedi!.</div>
	<div id="front"></div>
	
	';
	$username = $getvar->getUsername();
	if($username == 'ACCOUNTNOTFOUND')
	{
		echo '<div class="systemerror">Sistem hazır değil.</div>';
	}else{
	echo '<input type="button" id="spacialbuttonEx" value="Ekle" onclick="addNumber(\''.plugin_dir_url(__FILE__).'\')">
	<input type="button" id="spacialbuttonEx" value="Çıkar" onclick="removeNumber(\''.plugin_dir_url(__FILE__).'\')">';
	}
	
			
	echo'</div>
	
	<div id="VArea">
	<div id="phoneValidate">Lütfen, sms olarak gönderilen<br /> doğrulama kodunu giriniz.</div>
	<div id="inputField"><input type="text" id="pValidationField" placeholder="Doğrulama kodu..."></div>
	<div id="validationCodeError">Yalnış bir kod girdiniz!.</div>
	<div id="front"></div>
	<input type="button" id="spacialbuttonEx" value="Doğrula" onclick="phoneValidate(\''.plugin_dir_url(__FILE__).'\')">
	</div>
	
	<div id="tyMessage">
	<img src="'.plugin_dir_url(__FILE__).'themes/ok.png"><br />
	Tebrikler!	
	</div>
	
	<img name="mapImg" id="mapImg" usemap="#mapImg" src="'.plugin_dir_url(__FILE__).'themes/link.png" border="0">
	<map name="mapImg">
	<area shape="rect" coords="0,0,230,25" title="GayretSoft" href="http://gayretsoft.com" target="_new">
	</map>

	</div>
	</div>';
}

function SMSListAbout()
{
	echo '<div class="wrap">
	<div id="custom-icon" class="icon32"></div>
	<style type="text/css"> #custom-icon { background: url('.plugin_dir_url(__FILE__).'i.png)  no-repeat right top !important; }</style>
	<h2>Hakkında</h2><br />';


?>

<div id="infoarea">
<div id="img">
<div id="desc">Bu wordpress eklentisi ile listenizdeki kişilere toplu sms gönderebilirsiniz.<br /><br />Sisteme üyelik ücretsizdir. Soru, sorun ve görüşlerinizi lütfen <a href="mailto:software@gayretsoft.com">software@gayretsoft.com</a> adresimize email gönderiniz.
<br />Bize ulaşabileceğiniz telefon numaralarımız;<br />
# 0462 329 96 60<br /> 
# 0532 674 56 08
</div>

<div id="version">
<center><input id="spacialbutton" type="button" value="Eğer sisteme kayıtlı değil iseniz, kayıt olmak için tıklayınız." onclick="window.open('https://login.mutlucell.com/register?idbayi=gayrettek', 'register', 'width=510,height=310,left=200,top=200')"></center>
<br /><span>SMSList Sürüm Versiyon : <?=VERSION?></span><br />
<span>&copy; 2013 <a href="http://gayretsoft.com">GayretSOFT</a> Yazılım Hizmetleri. <br />Lisans koşulları için <a href="lisans.txt">buraya tıklayınız</a>.</span>
</div>

</div>
</div>

<?php 

} // Hakkında son


function SMSListSend()
{
	$getvar = new WordpressAction();
	
	echo '<div class="wrap">
	<div id="icon-send-custom" class="icon32"></div>
	<style type="text/css"> #icon-send-custom { background: url('.plugin_dir_url(__FILE__).'l.png)  no-repeat right top !important; }</style>
	<h2>Sms Gönder</h2><br />
	<div id="message" class="updated">
        <p><strong>Sms Gönderildi.</strong></p>
    </div>
';
?>

<div id="messagearea">
<table>
<tr>
<td><span>Kontör Miktarı:</span> </td>
<td><span id="getmoney"><?php $result = $getvar->getVariables("miktar");
if($result == 'ACCOUNTNOTFOUND') {
	echo 'Kayıtlı bir hesap bulunamadı.';
}else if($result == 'error'){ 
echo 'Bir hata oluştu.';
}else{
	echo $result;
	} ?></span></td>
</tr>
<tr>
<td><span>Orginatör Seçimi: </span></td>
<td><span><select id="org">
<?php
$orginatorResults = $getvar->getVariables("origin");
if($orginatorResults == 'ACCOUNTNOTFOUND'){
	echo "<option>Kayıtlı bir hesap bulunamadı.</option>";
}else if($result == 'error'){
echo "<option>Origin bulunamadı.</option>";
}else{
	//$orRe = preg_split('/\s+/', $orginatorResults);
	foreach($orginatorResults as $parse)
	{
			if($parse != "")
			echo '<option value="'.$parse.'">'.$parse.'</option>';
	}
	}
?>
</select></span></td>
</tr>
</table>
<table>
<tr>
<td><span>Şuan kayıtlı 
<?php
echo '<g id="currentNumbers">'.$getvar->Count().'</g>';
?>
 numara bulunmakta.</span></td>
</tr>
<tr>
<td><span><input type="radio" id="customselect" name="sel" value="0" title="Özel gönderim"/><input id="custom" type="text" maxlength="4" size="4" placeholder="Sayı" title="4 Basamaklı bir sayı"/> Kadar kişiye gönder</span></td>
</tr>
<tr>
<td><span><input type="radio" id="customselect" name="sel" value="1" title="Tümüne gönder"  checked="checked"/>Tümüne gönder</span></td>
</tr>
<tr>
<td><span><textarea cols="49" rows="8" id="writemessage" placeholder="Mesajınız..." title="Mesajınız"></textarea></span></td>
</tr>
</table>
<table id="sender">
<tr>
<td>
<input type="button" id="spacialbutton" value="Mesajı Gönder" <?php

$username = $getvar->getUsername();
if($username != 'ACCOUNTNOTFOUND'){
	echo 'onclick="SendMessage(\''.plugin_dir_url(__FILE__).'\')">';
}else{
	echo 'onclick="javascript:alert(\'Kayıtlı bir hesap bulunamadı.\')"';
	}


?>
</td>
</tr>
</table>
</div>
<div id="loading"><img  src="<?=plugin_dir_url(__FILE__)?>loading.gif" border="0" title="Lütfen bekleyin..." alt="" /><span>Lütfen bekleyin...</span></div>
<?php
} // End of the send sms code blog

/// Options Admin Page ///

function SMSListOptions()
{
	global $wpdb;
	$getvar = new WordpressAction();
	if(!current_user_can('manage_options'))
	{
		wp_die( __('Bu sayfaya erişmek için yeterli izniniz yok.') );
	}
	
	echo '<div class="wrap">
	<div id="icon-options-custom" class="icon32"></div>
		<style type="text/css"> #icon-options-custom { background: url('.plugin_dir_url(__FILE__).'o.png) no-repeat right top !important; }</style>
	<h2>Ayarlar</h2><br />
	    <div id="message" class="updated">
        <p><strong>Ayarlar Kaydedildi.</strong></p>
    </div>
	';	
	
	$tb = $wpdb->prefix."smslisttb";
	
	$val = $wpdb->get_var("show tables like '$tb'");

	if($val == $tb){
	$un = $wpdb->get_var("select username from ".$tb);
	$pw = $wpdb->get_var("select password from ".$tb);
	}
	/*else {
		echo '<b>Sistemde bir hesap bulunamadı. Lütfen ücretsiz bir hesap oluşturun.</b>';
		}*/
	
?>
<!-- SMS Options -->
<table cellpadding="0" cellspacing="0">
<tr class="settingsTabs">
<td>
<a href="#" class="tabstyle" style="text-decoration:underline; background-color:#696969; color:#F8F8F8" onClick="changeTab(0)">SMSList Ayarları</a></td>
<td>
<a href="#" class="tabstyle" style="text-decoration:none" onClick="changeTab(1)">Bileşen Ayarları</a>
</td>
</tr>

<tr class="settingsContentStyle">
<td class="fr">
<div class="settingsDiv" style="display:block; height:100%">
<table class="settingsTable">
<tr>
<td>Kullanıcı Adı:</td>
<td><input type="text" id="slun" autocomplete="off" title="SMSList kullanıcı adınız." value="<?=$un?>"/></td>
</tr>

<tr>
<td>Şifre:</td>
<td><input type="password" id="slpw" autocomplete="off" title="SMSList şifreniz." value="<?=$pw?>"/></td>
</tr>
</table>

<div id="buttonarea">
<input class="checkstyle" id="spacialbutton" type="button" value="Kontrol Et ve Kaydet" title="Giriş bilgilerini kontrol 
edin veya kayıt edin." onclick="getAccount('<?=plugin_dir_url(__FILE__)?>')" />
</div>

<div id="moneyinfo">
<g>Kontör Durumu: <span id="kon">
<?php
$miktar = $getvar->getVariables('miktar');
if($miktar == 'ACCOUNTNOTFOUND')
{
	echo 'Kayıtlı bir hesap bulunamadı.';
	
}else if($miktar == 'error')
{
	echo 'Bir hata oluştu.';
	
}else {
	echo  $miktar;
	}
?>
</span><g>
</div>

</div>

<!-- Widget settings -->
<div class="settingsDiv" style="display:none; height:100%">
<table id="widgettheme">

<tr>
<td><label for="demoTitle">Bileşen </label><input
<?php
$themeTitle = $getvar->getTheme(1, 0);
echo 'value="'.$themeTitle.'"';
?>

type="text" id="demoTitle" title="Bileşen başlığını giriniz." placeholder="Başlığı..."/></td>
<td><label for="demoTitle">Bileşen </label><input
<?php
$themeDescription = $getvar->getTheme(1, 1);
echo 'value="'.$themeDescription.'"';

?>

type="text" id="demoDesc" title="Bileşen açıklamasını giriniz." placeholder="Açıklaması..."/></td>
</tr>

<tr>
<!--<td>Tema Ayarları</td>-->
<td>Şuanki Tema: <span>
<?php
switch($getvar->getTheme(0,1))
{
	case 0:
	print 'Mavi';
	break;
	case 1:
	print 'Yeşil';
	break;
	case 2:
	print 'Turuncu';
	break;
	case 3:
	print 'Siyah';
	break;
}
?>
</span></td>
<td>Seçilen Tema : <g>-</g></td>
</tr>

<tr>
<td><input type="radio" name="setTheme" id="setTheme" value="<?=$getvar->getTheme(0,1)?>" style="display:none;" /><label for="setTheme">
<img border="0" src="<?=plugin_dir_url(__FILE__)?>themes/BlueW.png" title="Mavi Tema" alt"" onclick="setImage('0')"/>
</label></td>
<td><label for="setTheme">
<img border="0" src="<?=plugin_dir_url(__FILE__)?>themes/GreenW.png" title="Yeşil Tema" alt""  onclick="setImage('1')"/>
</label></td>
<td><label for="setTheme">
<img border="0" src="<?=plugin_dir_url(__FILE__)?>themes/OrangeW.png" title="Turuncu Tema" alt"" onclick="setImage('2')"/>
</label></td>
<td><label for="setTheme">
<img border="0" src="<?=plugin_dir_url(__FILE__)?>themes/BlackW.png" title="Siyah Tema" alt"" onclick="setImage('3')"/>
</label></td>
</tr>

<tr>
<td><input class="checkstyle" id="spacialbutton" type="button" value="Ayarları Kaydet" title="Ayarlarınızı keydedin." onclick="saveOptions('<?=plugin_dir_url(__FILE__)?>')" /></td>
</tr>

</table>
</div><!-- End of the modul div -->
</td>
</tr>
</table>

<div id="loading"><img  src="<?=plugin_dir_url(__FILE__)?>loading.gif" border="0" title="Lütfen bekleyin..." alt="" /><span>Lütfen bekleyin...</span></div>


<?php
}
wp_register_sidebar_widget('addWidget1', 'SMSList', 'addWidget', array());
add_action('admin_menu', 'SMSListMenu');
add_action('init', 'boot');

?>