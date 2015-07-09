<html>
<head>
<title>Champ API Test</title>
 
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.11.3.min.js"></script>
<script src="<?=base_url()?>js/jquery.form.js"></script>
 
</head>
 
<body>
 
<script type="text/javascript">
 $(document).ready(function() { 
	var options = { 
		target:     '#divToUpdate', 
		beforeSubmit: function(arr, $form, options) {
			$('#divToUpdate').html('Please wait...');
		},
		dataType: 'json',
		success: processJson 
	}; 
	
	function processJson(data) { 
		$('#divToUpdate').html('');
        	$('#divToUpdate').html(JSON.stringify(data, null, 2));
	}
	
	// pass options to ajaxForm 
	$('#myForm_create').ajaxForm(options);
	$('#myForm_login').ajaxForm(options);
	$('#myForm_logout').ajaxForm(options);
	$('#myForm_getTrades').ajaxForm(options);
	$('#myForm_jobTypes').ajaxForm(options);
	$('#myForm_update_profile').ajaxForm(options);
	$('#myForm_update_address').ajaxForm(options);
	
	});
</script>
 
<div id="divToUpdate" style="top:0; left:0; margin:10px; padding:10px; background-color:#000; color:#fff; position:fixed; z-index:99; width:100%;"></div>
<br>
<br>
<h3>General Tasks</h3>
<div class="box">
<strong>Trades</strong>
<br><br>
Url: /general/get_trades/<br>
Data: <br>

<form id="myForm_getTrades" action="<?=base_url()?>general/get_trades" method="post"> 
     <input type="submit" value="Submit" /> 
</form>
</div>


<strong>Job Types</strong>
<br><br>
Url: /general/get_jobTypes/<br>
Data: <br>

<form id="myForm_jobTypes" action="<?=base_url()?>general/get_jobTypes" method="post"> 
     <input type="submit" value="Submit" /> 
</form>
</div>


<hr>
<h3>Users Access</h3>
<p>
<strong>levels_and_roles</strong><br>
	'1' => 'customer',
	'6' => 'manager',
	'9' => 'admin'
</p>
<div class="box">
<strong>Create User</strong>
<br><br>
Url: /user/create_user/<br>
Data: user_name, user_pass, user_email<br>

<form id="myForm_create" action="<?=base_url()?>user/create_user" method="post"> 
    Name: <input type="text" name="user_name" /><br>
    Pass: <input type="text" name="user_pass" />Must have 8 char + special char + Uppercase + numbers<br>
    Email: <input type="text" name="user_email" /><br>
     <input type="submit" value="Submit" /> 
</form>
</div>
 

<div class="box">
<strong>Login</strong>
<br><br>
Url: /login/<br>
Data: login_string, login_pass, remember_me<br>

<?php echo form_open( '/login/', array( 'id' => 'myForm_login' ) ); ?> 
    Name: <input type="text" name="login_string" id="login_string" class="form_input" autocomplete="off" maxlength="255" /><br>
    Pass: <input type="password" name="login_pass" id="login_pass" class="form_input password" autocomplete="off" maxlength="<?php echo config_item('max_chars_for_password'); ?>" />
<br>
    Remember me: <input type="checkbox" id="remember_me" name="remember_me" value="yes" /><br>
     <input type="submit" value="Submit" /> 
</form>
</div>

 
<div class="box">
<strong>LogOut</strong>
<br><br>
Url: /user/logout/<br>
Data: <br>

<?php echo form_open( '/user/logout/', array( 'id' => 'myForm_logout' ) ); ?> 
     <input type="submit" value="Submit" /> 
</form>
</div>
 

<div class="box">
<strong>Recover</strong>
<br><br>
Url: /user/logout/<br>
Data: user_email <br>

		 <?php echo form_open( '/user/recover/' ); ?>
			<div>
				email address:<input type="text" maxlength="255" class="form_input" id="user_email" value="" name="user_email">
                <br>
     <input type="submit" value="Submit" /> 

		</form>
</div>

<hr>
 
<h3>Users Profiles</h3>


<div class="box">
<strong>Update Profile</strong>
<br><br>
Url: /user/update_profile/<br>
Data: email, first_name, last_name, phone<br>

<?php echo form_open( '/user/update_profile/', array( 'id' => 'myForm_update_profile' ) ); ?> 
    Email: <input type="text" name="email" /><br>
    First Name: <input type="text" name="first_name" /><br>
    Last Name: <input type="text" name="last_name" /><br>
    Phone: <input type="text" name="phone" /><br>

		</form>
</div>


<div class="box">
<strong>Update Address</strong>
<br><br>
Url: /user/update_address/<br>
Data: email, address, last_name, phone<br>

<?php echo form_open( '/user/update_address/', array( 'id' => 'myForm_update_address' ) ); ?> 
    Address: <input type="text" name="address" /><br>
    State/Province: <input type="text" name="province" /><br>
    City: <input type="text" name="city" /><br>
    Postal Code: <input type="text" name="postal_code" /><br>
	</form>
</div>

<hr>









</body>
</html>