<!DOCTYPE html>
<html>
<head>
	<?php
use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\RequestStatus;
use FilippoFinke\Models\Substitutes;
use FilippoFinke\Models\LdapUsers;

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
									<li class="breadcrumb-item active"><?php echo ($personal)?"Storico personale":"Storico generale"; ?></li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <table class="data-table stripe hover nowrap">
						<thead>
							<tr>
								<?php if (!$personal): ?>
								<th>Docente</th>
								<?php endif; ?>
								<th>Data</th>
								<th>Motivi/o</th>
								<th>Assenze</th>
                                <th>Stato</th>
								<?php if (!$personal): ?>
								<th>Revisionato da</th>
								<?php endif; ?>
								<th>Azione</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($history as $request):
                                $reasons = Reasons::getByRequestId($request["id"]);
                                $substitutes = Substitutes::getByRequestId($request["id"]);
                                if (!$personal) {
                                    $user = LdapUsers::getByUsername($request["username"]);
                                }
                            ?>
							<tr>
								<?php if (!$personal): ?>
								<td><?php echo $user["last_name"]." ".$user["name"]; ?></td>
								<?php endif; ?>
								<td><?php echo date("d.m.Y", strtotime($request["updated_at"])); ?></td>
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
									<?php echo RequestStatus::get($request["status"]); ?>
                                </td>
								<?php if (!$personal): ?>
								<td><?php echo $request["auditor"]; ?></td>
								<?php endif; ?>
                                <td>
                                    <button class="btn btn-primary" onclick="showPdf(<?php echo $request["id"]; ?>);">Visualizza PDF</button>
                                </td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal fade" id="pdf-modal" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-full-screen">
					<div class="modal-content modal-content-full-screen">
						<div class="modal-header">
							<h4 class="modal-title" >Visualizza PDF</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
						<div class="modal-body">
							<div style="margin:0px;padding:0px;overflow:hidden">
								<iframe id="iframe" frameborder="0" style="overflow:hidden;overflow-x:hidden;overflow-y:hidden;height:100%;width:100%;position:absolute;top:0px;left:0px;right:0px;bottom:0px" height="100%" width="100%"></iframe>					
							</div>
						</div>
						<div class="modal-footer">
							<a id="download-button" class="btn btn-secondary text-white" download>Scarica</a>
							<button type="button" class="btn btn-secondary" onclick="printPdf()">Stampa</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
						</div>
					</div>
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

	function showPdf(id) {
		var url = "<?php echo BASE_URL; ?>/requests/" + id + "/pdf";
		$('#iframe').attr("src", url);
		$('#download-button').attr("href", url);
		$('#pdf-modal').modal('toggle');
	}

	function printPdf() {
		$('#iframe').get(0).contentWindow.print();
	}
	
	$('document').ready(function(){
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