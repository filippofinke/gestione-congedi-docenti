<!DOCTYPE html>
<html>
<head>
    <?php include(__DIR__ . '/../Global/head.php'); ?>
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
					<div class="row float-right text-right">
						<div class="col-12">
							<button class="btn btn-outline-primary" data-toggle="modal" data-target="#new-user-modal">Aggiungi amministratore</button>
						</div>
						<div class="col-12 mt-1">
							<button class="btn btn-outline-primary" data-toggle="modal" data-target="#new-ldap-user-modal">Aggiungi utente LDAP</button>
						</div>
					</div>

					<div class="modal fade" id="new-ldap-user-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-body">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 class="text-center mb-30">Nuovo utente LDAP</h3>
									<form onsubmit="createLdapUser(event);">
										<div class="row">
											<div class="col-6">
												<div class="input-group custom">
													<input id="ldapName" type="text" class="form-control" placeholder="Nome" required maxlength="20" minlength="1">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
													</div>
												</div>
											</div>
											<div class="col-6">
												<div class="input-group custom">
													<input id="ldapLastName" type="text" class="form-control" placeholder="Cognome" required maxlength="20" minlength="1">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-6">
												<div class="input-group custom">
													<input id="ldapUsername" type="text" class="form-control" placeholder="Username" required maxlength="20" minlength="1">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
													</div>
												</div>
											</div>
											<div class="col-6">
												<select id="ldapPermission" class="custom-select" required>
													<option disabled selected value="">Seleziona un permesso</option>
													<?php foreach ($permissions as $permission):?>
													<option>
														<?php echo $permission["name"]; ?>
													</option>
													<?php endforeach; ?>
												</select>
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
					
					<div class="modal fade" id="new-user-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-body">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 class="text-center mb-30">Nuovo amministratore</h3>
									<form onsubmit="createAdministrator(event);">
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
								<h4>Gestione utenti</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/administration">Amministrazione</a></li>
									<li class="breadcrumb-item active" aria-current="page">Utenti</li>
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
									<th>Username</th>
									<th>Nome</th>
									<th>Cognome</th>
									<th>Permesso</th>
									<th>Tipo</th>
									<th>Ultimo accesso</th>
									<th>Azioni</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($ldapUsers as $ldapUser) :?>
								<tr>
									<td><?php echo $ldapUser["username"]; ?></td>
									<td><?php echo $ldapUser["name"]; ?></td>
									<td><?php echo $ldapUser["last_name"]; ?></td>
									<td>
										<select class="custom-select" onchange="updatePermission('<?php echo $ldapUser["username"]; ?>', this.value)">
											<?php foreach ($permissions as $permission):?>
											<option <?php echo ($ldapUser["permission"] == $permission["name"])?"selected":""; ?>>
												<?php echo $permission["name"]; ?>
											</option>
											<?php endforeach; ?>
										</select>
									</td>
									<td>LDAP</td>
									<td><?php echo ($ldapUser["last_login"])?date("H:i d.m.Y", strtotime($ldapUser["last_login"])):"Mai"; ?></td>
									<td></td>
								</tr>
							<?php endforeach; ?>
							<?php foreach ($administrators as $administrator) :?>
								<tr>
									<td><?php echo $administrator["email"]; ?></td>
									<td><?php echo $administrator["name"]; ?></td>
									<td><?php echo $administrator["last_name"]; ?></td>
									<td>Amministratore</td>
									<td>Locale</td>
									<td><?php echo ($administrator["last_login"])?date("H:i d.m.Y", strtotime($administrator["last_login"])):"Mai"; ?></td>
									<td>
										<?php if ($_SESSION["username"] != $administrator["email"]): ?>
										<button class="btn btn-outline-danger" onclick="deleteAdministrator(this, '<?php echo $administrator["email"]; ?>')">Elimina</button>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
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

		function updatePermission(username, permission) {
			console.log(username);
			fetch('<?php echo BASE_URL; ?>/users/' + username, {
				method: "PUT",
				body: "permission=" + permission
			}).then((response) => {
				if(response.status == 200) {
					$.notify("Permesso di " + username + " aggiornato!", "success");
				} else {
					$.notify("Impossibile aggiornare il permesso.", "error");
				}
			});
		}

		function createAdministrator(event) {
			event.preventDefault();
			var name = $("#name").val();
			var lastName = $("#lastName").val();
			var email = $("#email").val();

			if(isValidEmail(email) && name.length > 0 && lastName.length > 0) {
				fetch('<?php echo BASE_URL; ?>/users', {
					method: "POST",
					body: "name=" + name + "&lastName=" + lastName + "&email=" + email + "&type=administrator",
					headers:{
						"Content-Type":"application/x-www-form-urlencoded"
					}
				}).then((response) => {
					if(response.status == 201) {
						$.notify("Utente creato, password inviata per email!", "success");
						setTimeout(function() {
							location.reload();
						}, 500);
					} else if(response.status == 400) {
						$.notify("Richiesta malformata.", "error");
					} else {
						$.notify("Impossibile creare l'utente, già esistente.", "error");
					}
				});
			} else {
				$.notify("Completa tutti i campi.","error");
			}
		}

		function deleteAdministrator(row, email) {
			if(confirm("Sei sicuro di voler eliminare " + email + "?")) {
				fetch('<?php echo BASE_URL; ?>/users', {
					method: "DELETE",
					body: "email=" + email,
				}).then((response) => {
					if(response.status == 200) {
						$.notify("Amministratore eliminato!", "success");
						row.parentElement.parentElement.remove();
					} else if(response.status == 400) {
						$.notify("Richiesta malformata.", "error");
					} else {
						$.notify("Non puoi eliminare te stesso!", "error");
					}
				});
			}
		}

		function createLdapUser(event) {
			event.preventDefault();
			var name = $("#ldapName").val();
			var lastName = $("#ldapLastName").val();
			var username = $("#ldapUsername").val();
			var permission = $("#ldapPermission").val();
			console.log(name, lastName, username, permission);
			if(name.length > 0 && lastName.length > 0 && username.length > 0 && permission.length > 0) {
				fetch('<?php echo BASE_URL; ?>/users', {
					method: "POST",
					body: "name=" + name + "&lastName=" + lastName + "&username=" + username + "&permission="  + permission + "&type=ldap",
					headers:{
						"Content-Type":"application/x-www-form-urlencoded"
					}
				}).then((response) => {
					if(response.status == 201) {
						$.notify("Utente LDAP creato!", "success");
						setTimeout(function() {
							location.reload();
						}, 500);
					} else if(response.status == 400) {
						$.notify("Richiesta malformata.", "error");
					} else {
						$.notify("Impossibile creare l'utente, già esistente.", "error");
					}
				});
			} else {
				$.notify("Compila tutti i campi!", "error");
			}
		}

	</script>
</body>
</html>