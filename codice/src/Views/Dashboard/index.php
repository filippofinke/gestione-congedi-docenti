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
					<div class="tab">
						<ul class="nav nav-tabs customtab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#reason" role="tab" aria-selected="false">Motivazione</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#calendar" role="tab" aria-selected="false">Orario</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade" id="reason" role="tabpanel">	
								<div class="row mt-1">
									<?php foreach ($reasons as $reason): ?>
										<div class="col-6">
											<div class="card reason">
												<div class="card-body">
													<div class="custom-control custom-checkbox mb-5">
														<input type="checkbox" class="custom-control-input" id="<?php echo $reason["id"]; ?>">
														<label class="custom-control-label" for="<?php echo $reason["id"]; ?>"><?php echo $reason["name"]; ?> <?php echo $reason["description"]; ?></label>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="tab-pane fade active show calendar" id="calendar" role="tabpanel">

							</div>
						</div>
					</div>
					<div class="row mt-1">
						<div class="col-12">
							<h5 class="float-left">Data: <?php echo date("d.m.Y"); ?></h5>
							<h5 class="float-right">Firma: <?php echo $_SESSION["username"]; ?></h5>
						</div>
					</div>
				</div>
			</div>
			<?php include(__DIR__ . '/../Global/footer.php'); ?>
		</div>
	</div>
    <?php include(__DIR__ . '/../Global/script.php'); ?>
	<script src="/assets/js/notify.js"></script>
	<script src="/assets/js/finkeLendar.js"></script>
	<script>
		var labels = ["Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"];
		var hours = [
			{start:"08:20", end:"09:05", allow:true},
			{start:"09:05", end:"09:50", allow:true},
			{start:"10:05", end:"10:50", allow:true},
			{start:"10:50", end:"11:35", allow:true},
			{start:"13:15", end:"14:00", allow:true},
			{start:"14:00", end:"14:45", allow:true},
			{start:"15:00", end:"15:45", allow:true},
			{start:"15:45", end:"16:30", allow:true},
			{start:"16:30", end:"17:45", allow:true},
		];

		var calendar = new FinkeLendar(
			document.getElementById('calendar'),
			labels,
			hours
		);
		calendar.draw();
	</script>
	
</body>
</html>