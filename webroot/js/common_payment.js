$(function(){ 
	$("#payBtn").click(function(event) {
		//create single-use token to charge the user
		alert('complete');
		if($('.card-number').val()=='')
		{
			alert('card number is empty');
			return false;
		}
		if($('.card-expiry').val()=='')
		{	
			alert('card expiry date month is empty');
			return false;
		}
		if($('.card-cvc').val()=='')
		{
			alert('card cvc is empty');
			return false;
		}
		$('#payBtn').css("display", "none");
		var expiry_val = $('.card-expiry').val();
		var ind = expiry_val.indexOf('/');
		if(ind!='-1')
		{
			$('#loading').css("display", "block");
			var expiry_arr = expiry_val.split('/');
			var expmonth = expiry_arr[0];
			var expyear = expiry_arr[1];
			Stripe.createToken({
				number: $('.card-number').val(),
				cvc: $('.card-cvc').val(),
				exp_month: expmonth,
				exp_year: expyear
			}, stripeResponseHandler);
						
			//submit from callback
			//return false;
		}
		else
		{
			alert('Expiry month year must be in MM/YYYY format');
			return false;
		}
	});
});

function stripeResponseHandler(status, response) {
	if (response.error)
	{
		alert(response.error.message);
	
	} 
	else 
	{		
		var form$ = $("#paymentFrm");
		var token = response['id'];
		form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
		cartformsubmit();
	}
}		
function cartformsubmit()
{
	var formdata = $('#paymentFrm').serialize();
	alert(formdata);
	$.ajax
	({
		url : 'shopping_cart',
		type: 'POST',
		async: false,
		data:{mode:'formsubmit',formvals:formdata},
		success : function(response)
		{
			alert(response);
			$('#pay_status').html(response);
		}
	});
}
