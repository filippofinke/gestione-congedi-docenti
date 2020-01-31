<!DOCTYPE html>
<html>
<head>
	<?php include(__DIR__ . '/../Global/head.php'); ?>
</head>
<body>
	<div class="login-wrap customscroll d-flex align-items-center flex-wrap justify-content-center pd-20">
		<div class="login-box bg-white box-shadow pd-30 border-radius-5">
			<h2 class="text-center mb-30">Login</h2>
			<form>
				<div class="input-group custom input-group-lg">
					<input id="username" type="text" class="form-control" placeholder="Username">
					<div class="input-group-append custom">
						<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
					</div>
				</div>
				<div class="input-group custom input-group-lg">
					<input id="password" type="password" class="form-control" placeholder="**********">
					<div class="input-group-append custom">
						<span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
					</div>
				</div>
				<div id="error" class="alert alert-danger" role="alert" style="display: none;">
					Credenziali errate.
				</div>
				<div id="success" class="alert alert-success" role="alert" style="display: none;">
					Accesso eseguito!
				</div>
				<div class="input-group">
					<button id="login" type="button" class="btn btn-outline-primary btn-lg btn-block">Accedi</button>
				</div>
				<div id="forgot-password" style="display:none;">
					<div class="forgot-password padding-top-10"><a href="/forgot-password">Password dimenticata?</a></div>
				</div>
			</form>
		</div>
	</div>
    <?php include(__DIR__ . '/../Global/script.php'); ?>
	
	<script>
	$("#login").click(doLogin);
	
	function doLogin() {
		var username = $("#username").val();
		var password = $("#password").val();
		if(username.length > 0 && password.length > 0) {
			fetch('/login', {
				method:'post',
				body: "username=" + username + "&password=" + password,
				headers:{
					"Content-Type":"application/x-www-form-urlencoded"
				}
			}).then((response) => {
				if(response.status == 200) {
					$("#error").css("display","none");
					$("#success").css("display","block");
					setTimeout(function() {
						window.location = "/";
					}, 500);
				} else {
					$("#error").css("display","block");
					$("#success").css("display","none");
					if(isEmail(username)) {
						$("#forgot-password").css("display","block");
					}
				}
			})
		}
	}

	</script>

</body>
</html>