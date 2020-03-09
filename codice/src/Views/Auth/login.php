<!DOCTYPE html>
<html>
<head>
	<?php include(__DIR__ . '/../Global/head.php'); ?>
</head>
<body>
	<div class="login-wrap customscroll d-flex align-items-center flex-wrap justify-content-center pd-20">
		<div class="login-box bg-white box-shadow pd-30 border-radius-5">
			<h2 class="text-center mb-30">Login</h2>
			<form onsubmit="doLogin(event);">
				<div class="input-group custom input-group-lg">
					<input id="username" type="text" class="form-control" placeholder="Username" required minlength="1">
					<div class="input-group-append custom">
						<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
					</div>
				</div>
				<div class="input-group custom input-group-lg">
					<input id="password" type="password" class="form-control" placeholder="**********" required minlength="6">
					<div class="input-group-append custom">
						<span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
					</div>
				</div>
				<div id="error" class="alert alert-danger" role="alert" style="display: none;">
				</div>
				<div id="success" class="alert alert-success" role="alert" style="display: none;">
				</div>
				<div class="input-group">
					<button id="loginButton" type="submit" class="btn btn-outline-primary btn-lg btn-block" disabled>Attendi...</button>
				</div>
				<div id="forgot-password" style="display:none;">
					<div class="forgot-password padding-top-10"><a onclick="forgotPassword()">Password dimenticata?</a></div>
				</div>
			</form>
		</div>
	</div>
    <?php include(__DIR__ . '/../Global/script.php'); ?>
	
	<script>

	$( document ).ready(function() {
		$("#loginButton").removeAttr('disabled');
		$("#loginButton").text("Accedi");
	});
	
	function doLogin(event) {
		event.preventDefault();
		var username = $("#username").val();
		var password = $("#password").val();
		console.log(username, password);
		if((isValidLdapUsername(username) || isValidEmail(username)) && password.length > 0) {
			fetch('<?php echo BASE_URL; ?>/login', {
				method:'post',
				body: "username=" + username + "&password=" + password,
				headers:{
					"Content-Type":"application/x-www-form-urlencoded"
				}
			}).then((response) => {
				if(response.status == 200) {
					$("#error").css("display","none");
					$("#success").css("display","block").text("Accesso eseguito!");
					setTimeout(function() {
						window.location = "<?php echo BASE_URL; ?>/";
					}, 500);
				} else {
					$("#error").css("display","block").text("Credenziali errate!");
					$("#success").css("display","none");
					if(isValidEmail(username)) {
						$("#forgot-password").css("display","block");
					}
				}
			})
		}
	}

	function forgotPassword() {
		var username = $("#username").val();
		if(isValidEmail(username)) {
			fetch('<?php echo BASE_URL; ?>/forgot-password', {
				method:'POST',
				body: "email=" + username,
				headers:{
					"Content-Type":"application/x-www-form-urlencoded"
				}
			}).then((response) => { 
				if(response.status == 200) {
					$("#error").css("display","none");
					$("#success").css("display","block").text("Email di recupero inviata!");
				} else {
					$("#error").css("display","block").text("Impossibile inviare una email di recupero!");
					$("#success").css("display","none");
				}
			});
		} else {
			$("#error").css("display","block").text("Inserisci l'email!");
			$("#success").css("display","none");
		}
	}

	</script>

</body>
</html>