<!DOCTYPE html>
<html>
<head>
    <?php include(__DIR__ . '/../Global/head.php'); ?>
</head>
<body>
    <?php include(__DIR__ . '/../Global/header.php'); ?>
	<?php include(__DIR__ . '/../Global/sidebar.php'); ?>
	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Imposta la tua nuova password</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/administration">Amministrazione</a></li>
									<li class="breadcrumb-item active" aria-current="page">Imposta password</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30 row justify-content-md-center">
                    <div class="col-6">
                        <form onsubmit="setPassword(event);">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group custom">
                                        <input id="password" type="password" class="form-control" placeholder="Password" required minlength="6">
                                        <div class="input-group-append custom">
                                            <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                        </div>
                                    </div>	
                                </div>
                                <div class="col-6">
                                    <div class="input-group custom">
                                        <input id="repeat_password" type="password" class="form-control" placeholder="Ripeti la password" required minlength="6">
                                        <div class="input-group-append custom">
                                            <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                        </div>
                                    </div>	
                                </div>
                            </div>
                            <button class="btn btn-block btn-outline-primary">Imposta la password</button>		
                        </form>
                    </div>
                </div>
			</div>
			<?php include(__DIR__ . '/../Global/footer.php'); ?>
		</div>
	</div>
    <?php include(__DIR__ . '/../Global/script.php'); ?>
	<script src="<?php echo BASE_URL; ?>/assets/js/notify.js"></script>
	<script>
        function setPassword(event) {
            event.preventDefault();
            var password = $("#password").val();
            var repeat = $("#repeat_password").val();
            if(isValidPassword(password) && password == repeat) {
                fetch('<?php echo BASE_URL; ?>/users', {
                    method: "PUT",
                    body: "password=" + password
                }).then((response) => {
                    if(response.status == 200) {
                        $.notify("Password aggiornata con successo!", "success");
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    } else {
                        $.notify("Impossibile aggiornare la password.", "error");
                    }
                });
            } else {
                $.notify("Le due password non corrispondono!", "error");
            }
        }
	</script>
</body>
</html>