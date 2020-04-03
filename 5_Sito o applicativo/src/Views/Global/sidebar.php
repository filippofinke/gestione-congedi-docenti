<?php
use FilippoFinke\Libs\Session;
use FilippoFinke\Models\Requests;
use FilippoFinke\Models\RequestStatus;
use FilippoFinke\Models\Container;

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
							<a href="<?php echo BASE_URL; ?>/administration" class="dropdown-toggle no-arrow <?php echo ($_SERVER["REQUEST_URI"] == "/administration")?"active":""; ?>">
								<span class="fa fa-users"></span><span class="mtext">Utenti</span>
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL; ?>/administration/reasons" class="dropdown-toggle no-arrow <?php echo ($_SERVER["REQUEST_URI"] == "/administration/reasons")?"active":""; ?>">
								<span class="fa fa-pencil"></span><span class="mtext">Motivazioni</span>
							</a>
						</li>
					<?php endif; ?>
					<?php if (Session::isTeacher()): ?>
					<li>
						<a href="<?php echo BASE_URL; ?>/dashboard" class="dropdown-toggle no-arrow <?php echo ($_SERVER["REQUEST_URI"] == "/dashboard")?"active":""; ?>">
							<span class="fa fa-home"></span><span class="mtext">Home</span>
						</a>
					</li>
					<hr>
					<li class="dropdown">
						<a class="dropdown-toggle <?php echo ($_SERVER["REQUEST_URI"] == "/dashboard/sent" || $_SERVER["REQUEST_URI"] == "/dashboard/history")?"active":""; ?>">
							<?php
                            $requests = Requests::getWaitingByUsername($_SESSION["username"]);
                            ?>
							<span class="fa fa-inbox"></span><span class="mtext">Personale <span class="badge badge-primary"><?php echo count($requests); ?></span></span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo BASE_URL; ?>/dashboard/sent">In uscita</a></li>
							<li><a href="<?php echo BASE_URL; ?>/dashboard/history">Storico</a></li>
						</ul>
					</li>
					<hr>
					<?php endif; ?>
					<?php if (Session::isSecretary()):
                            $secretariat = Requests::getByStatusAndContainer(RequestStatus::WAITING, Container::SECRETARY);
                    ?>
					<li class="dropdown">
						<a class="dropdown-toggle <?php echo ($_SERVER["REQUEST_URI"] == "/dashboard/secretariat")?"active":""; ?>">
							<span class="fa fa-inbox"></span><span class="mtext">Segreteria <span class="badge badge-primary"><?php echo count($secretariat); ?></span></span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo BASE_URL; ?>/dashboard/secretariat">In entrata</a></li>
						</ul>
					</li>
					<hr>
					<?php endif; ?>
					<?php if (Session::isAdministration()):
                            $administration = Requests::getByStatusAndContainer(RequestStatus::WAITING, Container::ADMINISTRATION);
                    ?>
					<li class="dropdown">
						<a class="dropdown-toggle <?php echo ($_SERVER["REQUEST_URI"] == "/dashboard/administration")?"active":""; ?>">
							<span class="fa fa-inbox"></span><span class="mtext">Direzione <span class="badge badge-primary"><?php echo count($administration); ?></span></span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo BASE_URL; ?>/dashboard/administration">In entrata</a></li>
						</ul>
					</li>
					<hr>
					<li>
						<a href="<?php echo BASE_URL; ?>/dashboard/administration/history" class="dropdown-toggle no-arrow <?php echo ($_SERVER["REQUEST_URI"] == "/dashboard/administration/history")?"active":""; ?>">
							<span class="fa fa-history"></span><span class="mtext">Storico</span>
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>