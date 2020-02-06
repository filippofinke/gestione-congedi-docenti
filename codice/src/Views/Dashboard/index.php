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
								<?php

                                    $days = ["Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"];

                                    $hours = array(
                                        array( "start" => "08:20", "end" => "09:05"),
                                        array( "start" => "09:05", "end" => "09:50"),
                                        array( "start" => "10:05", "end" => "10:50"),
                                        array( "start" => "10:50", "end" => "11:35"),
                                        array( "start" => "13:15", "end" => "14:00"),
                                        array( "start" => "14:00", "end" => "14:45"),
                                        array( "start" => "15:00", "end" => "15:45"),
                                        array( "start" => "15:45", "end" => "16:30"),
                                        array( "start" => "16:30", "end" => "17:45")
                                    );
                                ?>
								<div class="row mt-2">
									
									<div class="calendar-day col">

									</div>
									<?php foreach ($hours as $hour): ?>
										<div class="calendar-hour col-1">
											<?php echo $hour["start"]; ?>
											<br>
											<?php echo $hour["end"]; ?>
										</div>
									<?php endforeach; ?>
								</div>
								<?php foreach ($days as $index => $day): ?> 
									<div class="row">
										<div class="col calendar-day">
											<b><?php echo $day; ?></b>
										</div>
										<?php foreach ($hours as $hour): ?>
											<div onmouseover="onCalendarOver(event, this)" onmouseup="onCalendarRelease(event, this)" onmousedown="onCalendarPress(event, this)" class="calendar-box col-1" data-day="<?php echo $index;?>" data-start="<?php echo $hour["start"];?>" data-end="<?php echo $hour["end"];?>">
												
											</div>
										<?php endforeach; ?>
									</div>
								<?php endforeach; ?>
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
	<script>

	var selecting = false;
	var days = [
		[],
		[],
		[],
		[],
		[],
		[]
	];

	function isSelected(element) {
		for(var day = 0; day < days.length; day++) {
			if(days[day].indexOf(element) != -1) {
				return true;
			}
		}
		return false;
	}

	function onCalendarPress(event, e) {
		selecting = true;
		onCalendarOver(null,e);
	}

	function onCalendarRelease(event, e) {
		selecting = false;
		onCalendarOver(null,e);
	}

	function onCalendarOver(event, e) {
		var start = e.dataset.start;
		var end = e.dataset.end;
		var day = e.dataset.day;
		if(
			selecting 
			&& !isSelected(e)
		) {
			e.style.background = "orange";
			days[day].push(e);
		}
	}

	function render() {
		for(var day = 0; day < days.length; day++) {
			console.log("render", day);
			var lastStart = null;
			var lastEnd = null;
			var lastElement = null;
			var elements = 0;
			for(var i = 0; i < days[day].length; i++) {
				var element = days[day][i];
				var start = element.dataset.start;
				var end = element.dataset.end;
				if(lastEnd == start) {
					lastEnd = end;
					lastElement.style.background = "green";
					elements += 1;
					lastElement.className = "col-" + elements + " calendar-box";
					element.remove();
				} else {
					lastStart = start;
					lastEnd = end;
					lastElement = element;
					elements = 1;
				}
			}
		}
	}

	</script>
	
</body>
</html>