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
								<h4>Gestione congedi</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active" aria-current="page">Home</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<div class="row">
						<?php foreach ($reasons as $reason): ?>
							<div class="col-12">
								<div class="custom-control custom-checkbox mb-5">
									<input type="checkbox" class="custom-control-input" id="<?php echo $reason["id"]; ?>">
									<label class="custom-control-label" for="<?php echo $reason["id"]; ?>"><?php echo $reason["name"]; ?> <?php echo $reason["description"]; ?></label>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php include(__DIR__ . '/../Global/footer.php'); ?>
		</div>
	</div>
    <?php include(__DIR__ . '/../Global/script.php'); ?>
	<script src="assets/js/notify.js"></script>
</body>
</html>