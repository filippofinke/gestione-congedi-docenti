<?php

use FilippoFinke\Libs\Session;
use FilippoFinke\Models\Container;

$editing = false;
if (isset($request)) {
    $editing = true;
}?>


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
									<li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo ($editing)?"Modifica come ".$_SESSION["permission"]:"Home"; ?></li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<div class="tab">
						<ul class="nav nav-tabs customtab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#reasonTab" role="tab" aria-selected="false">Motivazione</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#calendarTab" role="tab" aria-selected="false">Orario</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade active show" id="reasonTab" role="tabpanel">	
								<div class="row mt-1">
									<?php foreach ($reasons as $reason): ?>
										<div class="col-6 mb-1">
											<div class="card reason" style="height: 100%;">
												<div class="card-body">
													<div class="custom-control custom-checkbox mb-5">
														<input type="checkbox" class="custom-control-input" id="<?php echo $reason["id"]; ?>" onchange="toggleReason(<?php echo $reason["id"]; ?>)" <?php echo ($editing && in_array($reason["id"], $request["reasons"]))?'checked':''; ?>>
														<label class="custom-control-label" for="<?php echo $reason["id"]; ?>"><?php echo $reason["name"]; ?> <?php echo $reason["description"]; ?></label>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="tab-pane fade" id="calendarTab" role="tabpanel">
								<div class="calendar-container">
									<div class="calendar" id="calendar"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-1">
						<div class="col-12">
							<h5 class="float-left">Data: 
								<?php echo ($editing)?date("d.m.Y", strtotime($request["request"]["created_at"])):date("d.m.Y"); ?>
							</h5>
							<h5 class="float-right">Firma: 
								<?php
                                    echo ($editing)?$request["user"]["name"]." ".$request["user"]["last_name"]:$_SESSION["name"]." ".$_SESSION["lastName"];
                                ?>
							</h5>
						</div>
						<?php if ((Session::isAdministration() || (isset($request) && $request['request']['can_be_forwarded'])) && $editing): ?>
						<div class="col-12 mt-1">
							<textarea class="form-control" style="height: 135px;" maxlength="255" placeholder="Osservazioni" id="observations"></textarea>
						</div>
						<div class="col-4 mt-1">
							<select class="custom-select col-12" id="status">
									<option selected="" value="0">Stato della richiesta</option>
									<option value="3">CONSTATATA</option>
									<option value="1">ACCETTATA</option>
									<option value="2">RESPINTA</option>
							</select>
						</div>
						<div class="col-4 mt-1">
							<select class="custom-select col-12" id="paid">
									<option selected="" disabled>Pagamento supplenza</option>
									<option value="0">No</option>
									<option value="1">Si</option>
							</select>
						</div>
						<div class="col-4 mt-1">
							<input class="form-control" type="number" id="hours" placeholder="Ore riconosciute" min="0">
						</div>
						<?php endif; ?>
						<div class="row text-center mt-3 col-12">
							<?php if ($editing && (Session::isAdministration() || $request['request']['can_be_forwarded']) && $request['request']['container'] != Container::SECRETARY): ?>
							<div class="col-4 ml-3">
								<div class="custom-control custom-checkbox mb-5">
									<input type="checkbox" class="custom-control-input" id="can_be_forwarded">
									<label class="custom-control-label" for="can_be_forwarded">Può essere spedito dalla segreteria.</label>
								</div>
							</div>
							<div class="col">
								<button class="btn btn-danger" onclick="returnToSecretary(<?php echo $request['request']['id']; ?>)">Rimanda in segreteria</button>
							</div>
							<?php endif; ?>
							<div class="col <?php echo (!$editing)?'-12':''; ?>">
								<button class="btn btn-primary" onclick="sendRequest(event)">
								<?php
                                    echo ($editing)?"Salva la richiesta":"Invia la richiesta";
                                ?>
								</button>
							</div>
							<?php if ($editing): ?>
							<div class="col">
								<button class="btn btn-dark" onclick="window.history.back();">Torna indietro</button>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="calendar-modal" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Modifica</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
						<div class="modal-body">
							<form onsubmit="save(event)">
								<div class="form-row">
									<div class="col-6">
										<div class="input-group custom">
											<input id="course" type="text" class="form-control" placeholder="Classe" maxlength="15" minlength="1">
											<div class="input-group-append custom">
												<span class="input-group-text"><i class="fa fa-pencil" aria-hidden="true"></i></span>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="input-group custom">
											<input id="room" type="text" class="form-control" placeholder="Aula" maxlength="5" minlength="1">
											<div class="input-group-append custom">
												<span class="input-group-text"><i class="fa fa-pencil" aria-hidden="true"></i></span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-4">
										<select id="type" class="custom-select col-12">
											<option selected value="">Tipologia</option>
											<option value="SI">Supplenza Interna</option>
											<option value="SO">Scambio d'orario</option>
											<option value="SP">Sorveglianza parallela</option>
											<option value="SE">Supplente esterno</option>
										</select>
									</div>
									<div class="col-8">
										<div class="input-group custom">
											<input id="substitute" type="text" class="form-control" placeholder="Supplente" maxlength="30" minlength="1">
											<div class="input-group-append custom">
												<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
											</div>
										</div>
									</div>
								</div>
								<button type="submit" class="btn btn-primary">Salva</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php include(__DIR__ . '/../Global/footer.php'); ?>
		</div>
	</div>
    <?php include(__DIR__ . '/../Global/script.php'); ?>
	<script src="<?php echo BASE_URL; ?>/assets/js/notify.js"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/finkeLendar.js"></script>
	<script>	

	    // Congedo da modificare.
		var toEdit = null;
	    // Lista di motivazioni
		<?php if ($editing) :?>
		var reasons = [<?php echo implode(",", $request["reasons"]); ?>];
		<?php else: ?>
		var reasons = [];
		<?php endif; ?>
		// Calendario.
		var calendar = null;

		// I nomi di ogni riga.
		var labels = [
				<?php foreach (CALENDAR_LABELS as $label): ?>
					"<?php echo $label; ?>",
				<?php endforeach; ?>
			];
		
		// Gli orari disponibili nel calendario.
		var hours = [
			<?php foreach (CALENDAR_HOURS as $hour):
                if (isset($hour["space"])) {
                    continue;
                }
            ?>
				{start:"<?php echo $hour["start"]; ?>", end:"<?php echo $hour["end"]; ?>", allow: <?php echo $hour["allow"]? 'true' : 'false'; ?>},
			<?php endforeach; ?>
		];

		/**
		 * Funzione utilizzata per selezionare una motivazione.
		 * 
		 * @param id L'id della motivazione da selezionare.
		 */
		function toggleReason(id) {
			var index = reasons.indexOf(id);
			if (index == -1) {
				reasons.push(id);
			} else {
				reasons.splice(index, 1);
			}
		}

		// Attendo il caricamento della pagina.
		window.addEventListener("load", function() {		

			// Creazione del calendario.
			calendar = new FinkeLendar(
				document.getElementById('calendar'),
				labels,
				hours
			);
			
			// Funzione chiamata al click nel calendario.
			calendar.setOnHourClick(function(event) {
				toEdit = event.target;
				$("#course").val(toEdit.dataset.course);
				$("#room").val(toEdit.dataset.room);
				$("#substitute").val(toEdit.dataset.substitute);
				$("#type").val(toEdit.dataset.type);
				$('#calendar-modal').modal('toggle');
			});

			// Funzione chiamata al select del calendario.
			calendar.setOnSelected(function(event) {
				toEdit = event.target;
				$('#calendar-modal').modal('toggle');
			});

			// Disegna il calendario.
			calendar.draw();

			// Modalità di editing.
			<?php if ($editing): ?>
				calendar.setWeek("<?php echo $request["request"]["week"]; ?>");
				var substitues = JSON.parse('<?php echo json_encode($request["substitutes"]); ?>');
				for(var substitute of substitues) {
					var start = new Date(substitute.from_date);
					var end = new Date(substitute.to_date);
					var day = start.getDay() - 1;
					var dateSelect = $("[data-index='" + day + "']");
					dateSelect.val(start.toISOString().slice(0,10));
					calendar.dates[day] = dateSelect.val();
					var startHour = start.getHours() + ":" + start.getMinutes();
					var endHour = end.getHours() + ":" + end.getMinutes();
					var currentStart = new Date("1990-01-01 " + startHour);
					var currentEnd = new Date("1990-01-01 " + endHour);
					var blocks = $("[data-day='" + day + "']");
					if(substitute.type == null) {
						substitute.type = "";
					}
					var type = "";
					if(substitute.type != "") {
						type = " (" + substitute.type + ")";
					}
					for(var block of blocks) {
						var blockStart = new Date("1990-01-01 " + block.dataset.start);
						var blockEnd = new Date("1990-01-01 " + block.dataset.end);

						if(blockStart.getTime() >= currentStart.getTime() && blockEnd.getTime() <= currentEnd.getTime()) {
							calendar.onCalendarHover({target:block}, true);
							block.setAttribute("data-course", substitute.class);
							block.setAttribute("data-room", substitute.room);
							block.setAttribute("data-substitute", substitute.substitute);
							block.setAttribute("data-type", substitute.type);
							block.innerText = substitute.class + "\n" + substitute.room + "\n" + substitute.substitute + type;
						}
					}
				}
				calendar.currentSelection = [];
				calendar.reorder();
				calendar.render();
			<?php endif; ?>
		});

		/**
		 * Funzione utilizzata per inviare una richiesta di congedo.
		 * 
		 * @param event L'evento del form.
		 */
		function sendRequest(event) {
				var errors = [];
				if(reasons.length == 0) {
					errors.push("Seleziona almeno una motivazione!");
				}
				if(calendar.week == "") {
					errors.push("Seleziona la settimana (A o B)!");
				}
				var selected = false;
				for(var i = 0; i < calendar.days.length; i++) { 
					if(calendar.days[i].length > 0) {
						selected = true;
						break;
					}
				}
				if(!selected) {
					errors.push("Seleziona almeno un periodo di assenza!");
				}

				if(calendar.dates.length == 0) {
					errors.push("Seleziona le date di assenza per i vari giorni!");
				}

				var toSave = [];
				if(errors.length == 0) {
					var exit = false;
					for(var i = 0; i < calendar.days.length && !exit; i++) {
						var date = calendar.dates[i];
						var hours = calendar.days[i];
						for(var x = 0; x < hours.length && !exit; x++) {
							var element = hours[x];
							var start = element.dataset.start;
							var end = element.dataset.end;
							var d = new Date(date);
							if(typeof date == "undefined") {
								errors.push("Seleziona una data per " + labels[i] + "!");
								exit = true;
							} else if(d.getDay() - 1 != i) {
								errors.push("La data selezionata non corrisponde con il giorno!");
								exit = true;
							} else {
								var course = element.dataset.course;
								var room = element.dataset.room;
								var substitute = element.dataset.substitute;
								var type = element.dataset.type;
								var start = date + " " + start;
								var end = date + " " + end;
								toSave.push({
									from_date: start,
									to_date: end,
									type: type,
									room: room,
									substitute: substitute,
									class: course
								});
							}
						}
					}
					if(!exit) {

						var url = "<?php echo BASE_URL; ?><?php echo ($editing)?"/requests/".$request["request"]["id"]:"/requests";?>";
						var method = "<?php echo ($editing)?"PUT":"POST";?>";
						var toUpdate = <?php echo ($editing)?"true":"false"; ?>;
						var toAdd = "";
						<?php if ((Session::isAdministration() || (isset($request) && $request['request']['can_be_forwarded'])) && $editing): ?>
							var paid = $("#paid").val();
							var hours = Number($("#hours").val());
							var status = $("#status").val();
							var observations = $("#observations").val();
							if(!isValidDescription(observations)) {
								$.notify("Le osservazioni contengono caratteri non ammessi!", "error");
								return;
							}
							if(paid == null) {
								$.notify("Seleziona lo stato del pagamento!", "error");
								return;
							}
							if(isNaN(hours) && paid == "1") {
								$.notify("Seleziona un numero di ore riconosciute!", "error");
								return;
							}
							if(status == 0) {
								$.notify("Seleziona lo stato del congedo!", "error");
								return;
							}
							if(!isNaN(hours) && paid == "0") hours = 0;
							toAdd = "&status=" + status + "&observations=" + observations + "&hours=" + hours + "&paid=" + paid;
							
						<?php endif; ?>

						fetch(url, {
							method: method,
							body: "week=" + calendar.week + "&reasons=" + reasons + "&substitutes=" + JSON.stringify(toSave) + toAdd,
							headers:{
								"Content-Type":"application/x-www-form-urlencoded"
							}
						}).then((response) => {
							if(response.status == 201 || response.status == 200) {
								event.target.disabled = true;
								if(toUpdate) {
									$.notify("La richiesta di congedo è stata aggiornata!", "success");
								} else {
									$.notify("La richiesta di congedo è stata creata!", "success");
								}
								setTimeout(function() {
									if(toUpdate) {
										window.history.back();
									} else {
										window.location.href = "<?php echo BASE_URL; ?>/";
									}
								}, 500);
							} else if(response.status == 400) {
								$.notify("Impossibile salvare la richiesta di congedo, riprova!", "error");
							}
							return response.text();
						}).then(r => console.log(r));
					}
				}
				for(var i = 0; i < errors.length; i++) {
					$.notify(errors[i], "error");
				}
				
			}

		/**
		 * Funzione utilizzata per salvare un congedo.
		 * 
		 * @param event L'evento del form.
		 */
		function save(event) {
				event.preventDefault();
				var course = $("#course").val();
				var room = $("#room").val();
				var substitute = $("#substitute").val();
				var errors = [];
				if(substitute.length > 0 && !isValidName(substitute)) {
					errors.push("Il campo supplente contiene caratteri non validi!");
				}
				if(room.length > 0 && !isValidDescription(room, 1, 5)) {
					errors.push("L'aula ha un massimo di 5 caratteri!");
				}
				if(course.length > 0 && !isValidDescription(course, 1, 15)) {
					errors.push("La classe ha un massimo di 15 caratteri!");
				}

				if(errors.length > 0) {
					for(var error of errors) {
						$.notify(error, "error");
					}
					return;
				}

				var type = $("#type").val();
				var originalType = type;
				if(type != "") {
					type = " (" + type + ")";
				} else {
					type = "";
				}
				if(calendar.currentSelection.length > 0) {
					for(var i = 0; i < calendar.currentSelection.length; i++) {
						calendar.currentSelection[i].innerText = course + "\n" + room + "\n" + substitute + type;
						calendar.currentSelection[i].setAttribute("data-course", course);
						calendar.currentSelection[i].setAttribute("data-room", room);
						calendar.currentSelection[i].setAttribute("data-substitute", substitute);
						calendar.currentSelection[i].setAttribute("data-type", originalType);
					}
				} else {
					toEdit.innerText = course + "\n" + room + "\n" + substitute + type;
					toEdit.setAttribute("data-course", course);
					toEdit.setAttribute("data-room", room);
					toEdit.setAttribute("data-substitute", substitute);
					toEdit.setAttribute("data-type", originalType);
				}
				calendar.currentSelection = [];
				calendar.reorder();
				calendar.render();
				$('#calendar-modal').modal('toggle');
			}


	<?php if (Session::isAdministration() || (isset($request) && $request['request']['can_be_forwarded'])): ?>
	
	/**
	 * Funzione utilizzata per rimandare un congedo in segreteria.
	 * 
	 * @param id L'id del congedo.
	 */
	function returnToSecretary(id) {

		let canBeForwarded = ($("#can_be_forwarded").is(":checked")?'1':'0');

		if(confirm("Sei sicuro/a di voler inoltrare il congedo nel contenitore della segreteria?")) {
			fetch('<?php echo BASE_URL; ?>/requests/' + id, {
				method: "PUT",
				body: "return=true&canBeForwarded=" + canBeForwarded
			}).then((response) => {
				if(response.status == 200) {
					$.notify("Il congedo è stato inoltrato al contenitore della segreteria!", "success");
					setTimeout(function() {
						location.reload();
						window.history.back();
					}, 500);
				} else {
					$.notify("Impossibile inoltrare il congedo!", "error");
				}
				return response.text();
			});
		}
	}
	<?php endif; ?>
	</script>
	
</body>
</html>