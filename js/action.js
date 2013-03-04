/*
Proje: 	  SMSList Wordpress Modülü
Versiyon: 1.0
İsim :    action.js
Yazar:    © 2013 GayretSoft
Tarih:    23/02/2013
*/


function changeTab(tabindex)
{
	var div = document.getElementsByClassName('settingsDiv');
	var tab = document.getElementsByClassName('tabstyle');
	for(var index = 0; index <= div.length; index++)
	if(index.toString() == tabindex)
	{
		div[index].style.display = "block";
		tab[index].style.textDecoration = "underline";
		tab[index].style.backgroundColor = "#696969";
		tab[index].style.color = "#F8F8F8";
	}else{
		div[index].style.display = "none";
		tab[index].style.textDecoration = "none";
		tab[index].style.backgroundColor = "#fff";	
		tab[index].style.color = "#696969";	
		}
}

function getAccount(dir)
{
		var username = $('#slun').val();
		var password = $('#slpw').val();
		
		if(username.length == 0 || password.length == 0)
		{
			alert('Lütfen gerekli kullanıcı bilgilerini giriniz.');
			return;
		}		
		$("#loading").fadeIn(1000);
		
		var _username = username;
		var _password = password;
		
		$.post(dir+"accountcontrol.php", {username:username, password:password, save:"0"}, function(returnCode){	
		var copy = returnCode;
		
		if(returnCode == "23"){
			alert('Oturum bilgileri uyuşmadı.');
			$("#loading").fadeOut(1000);
			return;
		} else {
			
			$.post(dir+"accountcontrol.php", {username:_username, password:_password, save:"1"}, function(re){
				
				alert('Hesap kayıt edildi.');
				
				});
			
			$("#loading").fadeOut(1000);
			
			var kontor = copy.replace('$','');
			var kontor = kontor.replace('.0','');
			
			
			$('#kon').text(kontor);
		}
		});		
}

function setImage(index)
{
	$('#setTheme').val(index);
	switch(index){
		case "0":
		$('#widgettheme g').text("Mavi");
		break;
		case "1":
		$('#widgettheme g').text("Yeşil");
		break;
		case "2":
		$('#widgettheme g').text("Turuncu");
		break;
		case "3":
		$('#widgettheme g').text("Siyah");
		break;
	}
}
function saveOptions(dir)
{
	var demt = $('#demoTitle').val();
    var demd = $('#demoDesc').val();
	var sett = $('#setTheme').val();
	
	var demoTitle = demt.length;
	var demoDesc  = demd.length;
	if(demoTitle > 17 || demoDesc > 59)
	{
		alert("Başlık karakter uzunluk kuralı : 17 Karakter\nAçıklama karakter uzunluk kuralı : 59 Karakter");
	}

	$.post(dir+"accountcontrol.php", {demotitle:demt, demodesc:demd, settheme:sett}, function(returned){
		$('#message').css({display: 'block'});
		});
}

function SendMessage(dir)
{
	var mans	 	 = $("input[name='sel']:checked").val();
	var orginator	 = $("#org").val();
	var message		 = $('#writemessage').val();
	var customNumber = $('#custom').val();
	
	strlen = message.length;
	
	if(strlen < 5 || strlen > 150)
	{
		alert("Mesajınız 5 karakterden küçük veya 140 karakterden fazla olmamalıdır.");
		return;
	}
	
	var databaseIsReady = $('#currentNumbers').text();
	if(databaseIsReady == '0')
	{
		alert('Şuan sisteme kayıtlı numara bulunmamaktadır.');
		return;
	}
	
	
	
		$("#loading").fadeIn(1000);
		
	// Id 1 All Users
	if(mans == "1")
	{		
		$.post(dir+"accountcontrol.php" , {orginator:orginator, method:mans, customNumber:"0", message:message}, function(returnedValue){
			if(returnedValue.indexOf('$') != -1)
			{
				var amount = parseInt(/#(\d+)./.exec(returnedValue)[1]);
				var money = $('#getmoney').text();
				var realTime = money - amount;
			    $('#getmoney').text(realTime);
				
				$('#message').css({display: 'block'});
				$("#loading").fadeOut(1000);
			}		
		});
	}
	else if(mans == "0") // Id 0 to custom user list
	{			
	
	if(isNaN(customNumber) == true || customNumber == "")
	{
		alert("1 ila 4 basamaklı bir *sayı* giriniz.");
		return;
	}
	
	            $.post(dir+"accountcontrol.php" , {orginator:orginator, method:mans, customNumber:customNumber, message:message}, function(returnedValue){
					
			if(returnedValue.indexOf('$') != -1)
			{
				var amount = parseInt(/#(\d+)./.exec(returnedValue)[1]);
				var money = $('#getmoney').text();
				var realTime = money - amount;
			    $('#getmoney').text(realTime);
				
				$('#message').css({display: 'block'});
				$("#loading").fadeOut(1000);
			}		
		});
	}
}