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
									<form onsubmit="createReason(event);">
										<div class="input-group custom">
											<input id="name" type="text" class="form-control" placeholder="Motivazione" maxlength="255" minlength="1" required>
											<div class="input-group-append custom">
												<span class="input-group-text"><i class="fa fa-pencil" aria-hidden="true"></i></span>
											</div>
										</div>
										<div class="input-group custom">
											<textarea class="form-control" name="description" id="description" rows="1" maxlength="255" minlength="1" placeholder="Descrizione" required></textarea>
											<div class="input-group-append custom">
												<span class="input-group-text"><i class="fa fa-info" aria-hidden="true"></i></span>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="input-group">
													<button type="submit" class="btn btn-outline-primary btn-block">Crea</button>
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
									<th>Nome</th>
									<th>Descrizione</th>
									<th>Azioni</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($reasons as $reason): ?>
                                <tr>
                                    <td><?php echo $reason["name"]; ?></td>
									<td><?php echo $reason["description"]; ?></td>
									<td class="float-right">
										<button class="btn btn-outline-warning" onclick="showUpdateDialog(this, <?php echo $reason["id"]; ?>)">Aggiorna</button>
										<button class="btn btn-outline-danger" onclick="deleteReason(this, <?php echo $reason["id"]; ?>)">Elimina</button>
									</td>
                                </tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal fade" id="update-motivation-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 class="text-center mb-30">Aggiorna motivazione</h3>
							<form onsubmit="updateReason(event);">
								<input type="hidden" id="update_id">
								<div class="input-group custom">
									<input id="update_name" type="text" class="form-control" placeholder="Motivazione" maxlength="255" minlength="1" required>
									<div class="input-group-append custom">
										<span class="input-group-text"><i class="fa fa-pencil" aria-hidden="true"></i></span>
									</div>
								</div>
								<div class="input-group custom">
									<textarea class="form-control" name="description" id="update_description" rows="1" maxlength="255" minlength="1" placeholder="Descrizione" required></textarea>
									<div class="input-group-append custom">
										<span class="input-group-text"><i class="fa fa-info" aria-hidden="true"></i></span>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="input-group">
											<button type="submit" class="btn btn-outline-primary btn-block">Aggiorna</button>
										</div>
									</div>
								</div>
							</form>
						</div>
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
					"lengthMenu": "_MENU_ righe per pagina",
					"info": "_START_-_END_ di _TOTAL_ righe",
					searchPlaceholder: "Cerca",
					"paginate": {
						"previous": "Prima",
						"next": "Prossima"
					}
				},
			});
		});

		function updateReason(event) {
			event.preventDefault();
			var name = $("#update_name").val();
			var description = $("#update_description").val();
			var id = $("#update_id").val();
			fetch('/reasons/' + id, {
				method: "PUT",
				body: "name=" + name + "&description=" + description
			}).then((response) => {
				if(response.status == 200) {
					$.notify("Motivazione aggiornata!", "success");
					setTimeout(function() {
						location.reload();
					}, 500);
				} else {
					$.notify("Richiesta malformata.", "error");
				}
			});
		}

		function showUpdateDialog(element, id) {
			var tds = element.parentElement.parentElement.getElementsByTagName("td");
			var name = tds[0].innerText;
			var description = tds[1].innerText;
			$("#update_name").val(name);
			$("#update_description").val(description);
			$("#update_id").val(id);
			$("#update-motivation-modal").modal("toggle");
		}
		
		function deleteReason(element, id) {
			if(confirm("Sei sicuro di voler eliminare la motivazione? Potrà essere eliminata solamente se non ci saranno più congedi correlati.")) {
				fetch('/reasons/' + id, {
					method: "DELETE",
				}).then((response) => {
					if(response.status == 200) {
						element.parentElement.parentElement.remove();
						$.notify("Motivazione rimossa!", "success");
					} else {
						$.notify("Richiesta malformata.", "error");
					}
				 });
			}
		}

		function createReason(event) {
			event.preventDefault();
			var name = $("#name").val();
			var description = $("#description").val();
			if(isValidAlphabetAndAccents(name, 255) && isValidDescription(description)) {
				console.log(name, description);
				fetch('/reasons', {
					method: "POST",
					body: "name=" + name + "&description=" + description,
					headers:{
						"Content-Type":"application/x-www-form-urlencoded"
					}
				}).then((response) => {
					console.log(response);
					if(response.status == 201) {
						$.notify("Motivazione creata!", "success");
						setTimeout(function() {
							location.reload();
						}, 500);
					} else if(response.status == 400) {
						$.notify("Alcuni campi contengono caratteri non validi!", "error");
					} else if(response.status == 500) {
						$.notify("Impossibile inserire la motivazione!", "error");
					}
				 });
			} else {
				$.notify("Compila entrambi i campi!", "error");
			}
		}
	</script>
</body>
</html>