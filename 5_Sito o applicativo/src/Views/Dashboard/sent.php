<!DOCTYPE html>
<html>
<head>
	<?php

use FilippoFinke\Models\Container;
use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\Substitutes;

include(__DIR__ . '/../Global/head.php'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/responsive.dataTables.css">
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
									<li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item">In uscita</li>
									<li class="breadcrumb-item active" aria-current="page">Personale</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<table class="data-table stripe hover nowrap">
						<thead>
							<tr>
								<th>Data di creazione</th>
								<th>Motivi/o</th>
								<th>Assenze</th>
								<th>Stato</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($requests as $request):
                                $reasons = Reasons::getByRequestId($request["id"]);
                                $substitutes = Substitutes::getByRequestId($request["id"]);
                            ?>
							<tr>
								<td><?php echo date("d.m.Y", strtotime($request["created_at"])); ?></td>
								<td>
									<ul>
										<?php foreach ($reasons as $reason): ?>
											<li><?php echo $reason["name"]; ?></li>
										<?php endforeach; ?>
									</ul>
								</td>
								<td>
									Settimana <?php echo $request["week"]; ?>
									<ul>
										<?php
                                        $lastDate = null;
                                        foreach ($substitutes as $substitute):
                                            $currentDate = date("d.m.Y", strtotime($substitute["from_date"]));
                                        ?>
										<?php if ($lastDate != $currentDate): ?>
											<li>
												<?php echo $currentDate; ?>
												<ul>
										<?php endif; ?>
											<li class="ml-5">
												-
												<?php echo date("H:i", strtotime($substitute["from_date"])); ?>-<?php echo date("H:i", strtotime($substitute["to_date"])); ?> 
												<?php echo $substitute["class"]; ?> <?php echo $substitute["room"]; ?>
												<?php echo $substitute["substitute"]; ?>
												<?php echo $substitute["type"]; ?>
											</li>
										<?php if ($lastDate != $currentDate):
                                            $lastDate = $currentDate;
                                        ?>
												</ul>
											</li>
										<?php endif; ?>
										<?php endforeach; ?>
									</ul>
								</td>
								<td>
									In attesa in <?php echo Container::get($request["container"]); ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php include(__DIR__ . '/../Global/footer.php'); ?>
		</div>
	</div>
	<?php include(__DIR__ . '/../Global/script.php'); ?>
	<script src="<?php echo BASE_URL; ?>/assets/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/dataTables.bootstrap4.js"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/dataTables.responsive.js"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/responsive.bootstrap4.js"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/notify.js"></script>
	<script>
	// Attendo che il documento sia caricato.
	$('document').ready(function(){
		// Creazione dataTable.
		var table = $('.data-table').DataTable({
			scrollCollapse: true,
			autoWidth: false,
			responsive: true,
			ordering: false,
			"lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Tutti"]],
			"language": {
				"lengthMenu": "Mostra _MENU_ congedi per pagina",
				"info": "_START_-_END_ di _TOTAL_ congedi",
                "infoEmpty": "",
                "zeroRecords": "Nessun risultato corrispondente alla ricerca.",
				searchPlaceholder: "Cerca",
                "emptyTable": "Nessun dato da mostrare.",
                "infoFiltered": "(filtrate da _MAX_ congedi totali)",
                "paginate": {
                    "previous": "Prima",
                    "next": "Dopo"
                }
			},
		});
        table.on( 'draw', function (e) {
            var body = $( table.table().body() );
            body.unhighlight();
            body.highlight( table.search() );  
        });
	});
	</script>
</body>
</html>