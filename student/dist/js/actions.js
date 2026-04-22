jQuery(document).ready( function($){

 $("#login-form").submit( function(e) { loginProcess(e);  });
 $("#getDept").change( function(e) { getDept(e);  });
 $("#getVoteResult").change( function(e) { getResult(e);  });

//alert(12345);



 //discount_fetcher
function loginProcess(evt){
var _this = $(evt.target);
evt.preventDefault();
var username = $(_this).find('#username').val();
var password = $(_this).find('#password').val();
var formdata = $(_this).serialize();
$(_this).find(':input').attr('disabled',true);
$(_this).find(':button').attr('disabled',false);
$(_this).find(':button').html('Loading..');
if( username === "" || password === "" ){
$("#login-bottom").removeClass('hide').addClass('alert-danger').html('<p><i class="fa fa-exclamation-triangle"></i> Check your login credentials! Something is empty!</p>');
$(_this).find(':input').attr('disabled',false);
$(_this).find(':button').attr('disabled',false);
$(_this).find(':button').html('Login');
}
else {
$.ajax({
	url:'reducer.php',
	type: 'POST',
	data: formdata,
	success: function( result ){
		 //alert(result);
		if(result == 1) {
			$("#login-bottom").removeClass('hide').addClass('alert-success').html('<p>Logging in......</p>');
	 location.href='payment.php';


		 }
		 else if(result == 'pending') {
			$("#login-bottom").removeClass('hide').addClass('alert-warning').html('<p><i class="fa fa-clock-o"></i> Your registration is pending admin approval. Please wait for approval before you can login.</p>');
			$(_this).find(':input').attr('disabled',false);
			$(_this).find(':button').attr('disabled',false);
			$(_this).find(':button').html('<i class="icon-signin icon-large"></i>Login');
		 }
		 else if(result == 'rejected') {
			$("#login-bottom").removeClass('hide').addClass('alert-danger').html('<p><i class="fa fa-times-circle"></i> Your registration has been rejected by the admin. Please contact the administrator for more information.</p>');
			$(_this).find(':input').attr('disabled',false);
			$(_this).find(':button').attr('disabled',false);
			$(_this).find(':button').html('<i class="icon-signin icon-large"></i>Login');
		 }
		 else{
			$("#login-bottom").removeClass('hide').addClass('alert-danger').html('<p>No user is registered using this credentials.</p>');
			$(_this).find(':input').attr('disabled',false);
			$(_this).find(':button').attr('disabled',false);
			$(_this).find(':button').html('<i class="icon-signin icon-large"></i>Login');
		}
	}
});
}
return this;
}


$(document).on('submit','.addPayment', function(evt)
{
  evt.preventDefault();

   $.ajax({
            url:'reducer.php',
            type: "POST",
            dataType: "json",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
             success:function(result)
             {
                     if(result == 1) {
                  $('.alert_message_mod').html('<div class="alert alert-success" role="alert">Saved successfully</div>');
                     location.href='payment.php';
                 }
                 else if(result == 2) {
                  $('.alert_message_mod').html('<div class="alert alert-danger" role="alert">Error:: This faculty already exists!!!</div>');

                 }

                 else  {
                $('.alert_message_mod').html('<div class="alert alert-danger" role="alert">Error adding this faculty!!! Please try again later.</div>');

                  }
             }


          });
});

	/**
	 *
	 *Editd code ends up
	 *
	 */

 });