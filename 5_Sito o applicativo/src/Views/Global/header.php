	<div class="pre-loader"></div>
	<div class="header clearfix">
		<div class="header-right">
			<div class="menu-icon">
				<span></span>
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon"><i class="fa fa-user-o"></i></span>
						<span class="user-name"><?php echo $_SESSION["name"]." ".$_SESSION["lastName"]; ?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Esci</a>
					</div>
				</div>
			</div>
			<!--
			<div class="user-notification" style="margin-right: 0px;">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
						<i class="fa fa-bell" aria-hidden="true"></i>
						<span class="badge notification-active"></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<div class="notification-list mx-h-350 customscroll">
							<ul>
								<li>
									<a href="#">
										<h3 class="clearfix">Sistema <span>3 minuti fa</span></h3>
										<p>Notifica di prova</p>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div> 
			-->
		</div>
	</div>