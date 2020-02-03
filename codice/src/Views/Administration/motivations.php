<!DOCTYPE html>
<html>
<head>
    <?php include(__DIR__ . '/../Global/head.php'); ?>
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/responsive.dataTables.css">
</head>
<body>
    <?php include(__DIR__ . '/../Global/header.php'); ?>
	<?php include(__DIR__ . '/../Global/sidebar.php'); ?>
	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row float-right text-right">
						<div class="col-12">
							<button class="btn btn-outline-primary" data-toggle="modal" data-target="#new-motivation-modal">Aggiungi motivazione</button>
						</div>
					</div>
					
					<div class="modal fade" id="new-motivation-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-body">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 class="text-center mb-30">Nuova motivazione</h3>
									<form onsubmit="createMotivation(event);">
										<div class="row">
											<div class="col-6">
												<div class="input-group custom">
													<input id="name" type="text" class="form-control" placeholder="Nome" required maxlength="20" minlength="1">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
													</div>
												</div>
											</div>
											<div class="col-6">
												<div class="input-group custom">
													<input id="lastName" type="text" class="form-control" placeholder="Cognome" required maxlength="20" minlength="1">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
													</div>
												</div>
											</div>
										</div>
										<div class="input-group custom">
											<input id="email" type="email" class="form-control" placeholder="Email" required>
											<div class="input-group-append custom">
												<span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="input-group">
													<button class="btn btn-outline-primary btn-block">Crea!</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Gestione Motivazioni</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="/administration">Amministrazione</a></li>
									<li class="breadcrumb-item active" aria-current="page">Motivazioni</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<div class="row">
						<table class="data-table stripe hover nowrap">
							<thead>
								<tr>
									<th>Test</th>
								</tr>
							</thead>
							<tbody>
                                <tr>
                                    <td>test</td>
                                </tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php include(__DIR__ . '/../Global/footer.php'); ?>
		</div>
	</div>
    <?php include(__DIR__ . '/../Global/script.php'); ?>
	<script src="/assets/js/jquery.dataTables.min.js"></script>
	<script src="/assets/js/dataTables.bootstrap4.js"></script>
	<script src="/assets/js/dataTables.responsive.js"></script>
	<script src="/assets/js/responsive.bootstrap4.js"></script>
	<script src="/assets/js/notify.js"></script>
	<script>
		$('document').ready(function(){
			$('.data-table').DataTable({
				scrollCollapse: true,
				autoWidth: false,
				responsive: true,
				columnDefs: [{
					targets: "datatable-nosort",
					orderable: false,
				}],
				"lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Tutti"]],
				"language": {
					"lengthMenu": "Mostrando _MENU_ righe per pagina",
					"info": "_START_-_END_ di _TOTAL_ righe",
					searchPlaceholder: "Cerca"
				},
			});
		});
	</script>
</body>
</html>