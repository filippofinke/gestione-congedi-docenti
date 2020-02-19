<!DOCTYPE html>
<html>
<head>
	<?php

use FilippoFinke\Models\LdapUsers;
use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\Substitutes;
use FilippoFinke\Models\RequestStatus;

include(__DIR__ . '/../Global/head.php'); ?>
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
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Gestione congedi</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active" aria-current="page">Direzione</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<table class="data-table stripe hover nowrap">
						<thead>
							<tr>
                                <th>Docente</th>
								<th>Data di creazione</th>
								<th>Motivi/o</th>
								<th>Assenze</th>
                                <th>Osservazioni</th>
                                <th>Stato</th>
								<th>Azioni</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($administration as $request):
                                $reasons = Reasons::getByRequestId($request["id"]);
                                $substitutes = Substitutes::getByRequestId($request["id"]);
                                $user = LdapUsers::getByUsername($request["username"]);
                            ?>
							<tr>
                                <td><?php echo $user["last_name"]." ".$user["name"]; ?></td>
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
                                <td><?php echo $request["observations"]; ?></td>
                                <td><?php echo RequestStatus::get($request["status"]); ?></td>
								<td>
                                    <a class="btn btn-outline-primary text-primary" href="/dashboard/<?php echo $request["id"]; ?>">Visualizza</a>
                                    <br>
                                    <button class="btn btn-outline-success mt-1" onclick="approve(<?php echo $request["id"]; ?>)">Conferma</button>
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
	<script src="/assets/js/jquery.dataTables.min.js"></script>
	<script src="/assets/js/dataTables.bootstrap4.js"></script>
	<script src="/assets/js/dataTables.responsive.js"></script>
	<script src="/assets/js/responsive.bootstrap4.js"></script>
	<script src="/assets/js/notify.js"></script>
	<script>
	$('document').ready(function(){
		$('.data-table').DataTable({
			autoWidth: false,
			responsive: false,
			ordering: false,
			"lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Tutti"]],
			"language": {
				"lengthMenu": "_MENU_ righe per pagina",
				"info": "_START_-_END_ di _TOTAL_ righe",
				"infoEmpty": "Non sono presenti congedi da mostrare",
                "emptyTable": "Non sono presenti congedi in attesa!",
                searchPlaceholder: "Cerca",
                "paginate": {
                    "previous": "Prima",
                    "next": "Prossima"
                }
			},
		});
    });
    
    function approve(id) {
        if(confirm("Sei sicuro/a di voler mandare il congedo in direzione?")) {
            console.log("Approving " + id);
            fetch('/requests/' + id, {
				method: "PUT",
				body: "approve=true"
			}).then((response) => {
				if(response.status == 200) {
                    $.notify("Congedo mandato in direzione!", "success");
                    setTimeout(function() {
						location.reload();
					}, 500);
				} else {
					$.notify("Impossibile approvare il congedo!", "error");
                }
			});
        }
    }

	</script>
</body>
</html>