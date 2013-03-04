/*
Proje: 	  SMSList Wordpress Modülü
Versiyon: 1.0
İsim :    numberAction.js
Yazar:    © 2013 GayretSoft
Tarih:    23/02/2013
*/

function addNumber(dir)
{
	var phone    = $('#phoneField').val();
	var username = $('#usernameField').val();
	
	if(isNaN(phone) == true)
	{
		alert("Lütfen bir telefon numarası giriniz.");
		return;
	}
	
	if(phone == "" || username == "")
	{
		alert('Lütfen gerekli değerleri giriniz.');
		return;
	}
	
	$('#error').fadeOut('slow',function(){
		$('#front').fadeIn('slow');
	});

	$.post(dir+"action.php", {phone:phone, name:username, action:"add"},function(isSuccess){

		if(isSuccess == 1)
		{
			$('#front').fadeOut('slow', function(){
			$('#animate').fadeOut('slow', function(){
			$('#VArea').fadeIn('slow');
			});});
			
		} else if(isSuccess == 0) {
			$('#front').fadeOut('slow',function(){
			$('#error').fadeIn('slow');		
			});
		} else if(isSuccess == 2) {
			$('#front').fadeOut('slow',function(){
			$('#messageError').fadeIn('slow');		
			});
			}
		
		});
}

function phoneValidate(dir)
{
	var phone    		  = $('#phoneField').val();
	var validationCode    = $('#pValidationField').val();
	
	if(isNaN(validationCode) == true || validationCode == "")
	{
		alert("Lütfen doğrulama kodunu giriniz.");
		return;
	}
	
	$('#validationCodeError').fadeOut('slow',function(){
	$('#error').fadeOut('slow',function(){
	$('#errorRemove').fadeOut('slow',function(){
	$('#successRemove').fadeOut('slow',function(){
	$('#front').fadeIn('slow');
	});});});});
	
	$.post(dir+"action.php", {phone:phone, validationCode:validationCode}, function(validationIsSuccess){
		
		if(validationIsSuccess == 1)
		{
					$('#VArea').fadeOut('slow', function(){
					$('#tyMessage').fadeIn('slow');		
					setInterval(deleteThis(), 5000);				
					});
					
			
		} else {
					$('#validationCodeError').fadeIn('slow');
			   }
		
		});
}

function deleteThis()
{
	if($('#tymessage').is(':visible'))
	{
						$('#tyMessage').fadeOut('slow', function(){
						$('#VArea').fadeIn('slow');						
						});
	}
	if($('#successRemove').is(':visible'))
	{
						$('#successRemove').fadeOut('slow');
	}
	if($('#errorRemove').is(':visible'))
	{
						$('#errorRemove').fadeOut('slow');
	}
}

function removeNumber(dir)
{
	var phone    = $('#phoneField').val();
	var username = $('#usernameField').val();

	if(phone == "" || isNaN(phone) == true)
	{
		alert('Lütfen bir telefon numaranızı giriniz.');
	}else{
		
	$('#validationCodeError').fadeOut('slow',function(){
	$('#error').fadeOut('slow',function(){
	$('#errorRemove').fadeOut('slow',function(){
	$('#successRemove').fadeOut('slow',function(){
	$('#front').fadeIn('slow');
	});});});});
		
		$.post(dir+"action.php", {phone:phone, name:name, action:"remove"}, function(removeIsSuccess){
			
			if(removeIsSuccess == 0)
			{
					$('#validationCodeError').fadeOut('slow',function(){
					$('#error').fadeOut('slow',function(){
					$('#successRemove').fadeOut('slow',function(){
					$('#front').fadeOut('slow',function(){
					$('#errorRemove').fadeIn('slow');
					setInterval(deleteThis(), 5000);
					});});});});
						
			}else {
					$('#validationCodeError').fadeOut('slow',function(){
					$('#error').fadeOut('slow',function(){
					$('#front').fadeOut('slow',function(){
					$('#errorRemove').fadeOut('slow',function(){
					$('#successRemove').fadeIn('slow');
					setInterval(deleteThis(), 5000);
					});});});});
				  }
			});		
	}
}