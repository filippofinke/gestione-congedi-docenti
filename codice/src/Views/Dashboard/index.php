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
										<div class="col-6">
											<div class="card reason">
												<div class="card-body">
													<div class="custom-control custom-checkbox mb-5">
														<input type="checkbox" class="custom-control-input" id="<?php echo $reason["id"]; ?>" onchange="toggleReason(<?php echo $reason["id"]; ?>)">
														<label class="custom-control-label" for="<?php echo $reason["id"]; ?>"><?php echo $reason["name"]; ?> <?php echo $reason["description"]; ?></label>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="tab-pane fade" id="calendarTab" role="tabpanel">
								<div class="calendar" id="calendar"></div>
							</div>
						</div>
					</div>
					<div class="row mt-1">
						<div class="col-12">
							<h5 class="float-left">Data: <?php echo date("d.m.Y"); ?></h5>
							<h5 class="float-right">Firma: <?php echo $_SESSION["username"]; ?></h5>
						</div>
						<div class="col-12 text-center">
							<button class="btn btn-outline-primary" onclick="sendRequest(event)">Invia la richiesta</button>
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
											<option selected value="">Supplenza</option>
											<option>SI</option>
											<option>SO</option>
											<option>SP</option>
											<option>SE</option>
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
	<script src="/assets/js/notify.js"></script>
	<script src="/assets/js/finkeLendar.js"></script>
	<script>	

		var toEdit = null;
		var reasons = [];
		var calendar = null;

		function toggleReason(id) {
			var index = reasons.indexOf(id);
			if (index == -1) {
				reasons.push(id);
			} else {
				reasons.splice(index, 1);
			}
		}

		window.addEventListener("load", function() {		
			console.log("loaded!");			

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

			calendar = new FinkeLendar(
				document.getElementById('calendar'),
				labels,
				hours
			);
			
			calendar.setOnHourClick(function(event) {
				toEdit = event.target;
				$("#course").val(toEdit.dataset.course);
				$("#room").val(toEdit.dataset.room);
				$("#substitute").val(toEdit.dataset.substitute);
				$("#type").val(toEdit.dataset.type);
				$('#calendar-modal').modal('toggle');
			});

			calendar.setOnSelected(function(event) {
				toEdit = event.target;
				$('#calendar-modal').modal('toggle');
			});

			calendar.draw();
		});

		function sendRequest(event) {
				console.log("reasons", reasons);
				console.log("week", calendar.week);
				console.log("days", calendar.days);
				console.log("dates", calendar.dates);

				var errors = [];
				if(reasons.length == 0) {
					errors.push("Seleziona una motivazione!");
				}
				if(calendar.week == "") {
					errors.push("Seleziona una settimana!");
				}
				var selected = false;
				for(var i = 0; i < calendar.days.length; i++) { 
					if(calendar.days[i].length > 0) {
						selected = true;
						break;
					}
				}
				if(!selected) {
					errors.push("Seleziona un periodo!");
				}

				if(calendar.dates.length == 0) {
					errors.push("Seleziona le date!");
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
								errors.push("Data mancante!");
								exit = true;
							} else if(d.getDay() - 1 != i) {
								errors.push("La data non corrisponde con il giorno!");
								exit = true;
							} else {
								var course = element.dataset.course;
								var room = toEdit.dataset.room;
								var substitute = toEdit.dataset.substitute;
								var type = toEdit.dataset.type;
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
						fetch('/requests', {
							method: "POST",
							body: "week=" + calendar.week + "&reasons=" + reasons + "&substitutes=" + JSON.stringify(toSave),
							headers:{
								"Content-Type":"application/x-www-form-urlencoded"
							}
						}).then((response) => {
							if(response.status == 201) {
								event.target.disabled = true;
								$.notify("Congedo creato!", "success");
								setTimeout(function() {
									window.location.href = "/";
								}, 500);
							} else if(response.status == 400) {
								$.notify("Richiesta malformata.", "error");
							}
							return response.text();
						});
					}
				}
				for(var i = 0; i < errors.length; i++) {
					$.notify(errors[i], "error");
				}
				
			}

		function save(event) {
				event.preventDefault();
				var course = $("#course").val();
				var room = $("#room").val();
				var substitute = $("#substitute").val();

				if((substitute.length > 0 && !isValidName(substitute))
				|| (room.length > 0 && !isValidDescription(room, 1, 5))
				|| (course.length > 0 && !isValidDescription(course, 1, 15))) {
					$.notify("I valori inseriti non sono validi!", "error");
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
	</script>
	
</body>
</html>