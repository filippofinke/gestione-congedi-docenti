<?php
use FilippoFinke\Libs\Session;

?>
	<div class="left-side-bar">
		<div style="text-align: center; margin-top: 20px;">
			<h3>Congedi CPT</h3>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">
					<?php if (Session::isAdministrator()) :?> 
						<li>
							<a href="/administration" class="dropdown-toggle no-arrow <?php echo ($_SERVER["REQUEST_URI"] == "/administration")?"active":""; ?>">
								<span class="fa fa-users"></span><span class="mtext">Utenti</span>
							</a>
						</li>
						<li>
							<a href="/administration/reasons" class="dropdown-toggle no-arrow <?php echo ($_SERVER["REQUEST_URI"] == "/administration/reasons")?"active":""; ?>">
								<span class="fa fa-pencil"></span><span class="mtext">Motivazioni</span>
							</a>
						</li>
					<?php endif; ?>
					<?php if (Session::isTeacher()): ?>
					<li>
						<a href="/dashboard" class="dropdown-toggle no-arrow <?php echo ($_SERVER["REQUEST_URI"] == "/dashboard")?"active":""; ?>">
							<span class="fa fa-home"></span><span class="mtext">Home</span>
						</a>
					</li>
					<hr>
					<li class="dropdown">
						<a class="dropdown-toggle">
							<span class="fa fa-inbox"></span><span class="mtext">Personale <span class="badge badge-primary">0</span></span>
						</a>
						<ul class="submenu">
							<li><a href="/dashboard/sent">In uscita</a></li>
							<li><a href="/dashboard/history">Storico</a></li>
						</ul>
					</li>
					<hr>
					<?php endif; ?>
					<?php if (Session::isSecretary()): ?>
					<li class="dropdown">
						<a class="dropdown-toggle">
							<span class="fa fa-inbox"></span><span class="mtext">Segreteria <span class="badge badge-primary">0</span></span>
						</a>
						<ul class="submenu">
							<li><a href="/dashboard/secretariat">In entrata</a></li>
						</ul>
					</li>
					<hr>
					<?php endif; ?>
					<?php if (Session::isAdministration()): ?>
					<li class="dropdown">
						<a class="dropdown-toggle">
							<span class="fa fa-inbox"></span><span class="mtext">Direzione <span class="badge badge-primary">0</span></span>
						</a>
						<ul class="submenu">
							<li><a href="/dashboard/administration">In entrata</a></li>
						</ul>
					</li>
					<hr>
					<li>
						<a href="/dashboard/administration/history" class="dropdown-toggle no-arrow">
							<span class="fa fa-history"></span><span class="mtext">Storico</span>
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>