<?php
require_once __DIR__ . '/inc/bootstrap.php';
require_once __DIR__ . '/inc/tpl/header.php';
$roomStatement = $db->prepare('SELECT room_types.id, room_types.category, room_types.name, room_categories.name AS cat_name FROM room_types INNER JOIN room_categories ON room_categories.id = room_types.category ORDER BY room_types.category, room_types.name');
$roomStatement->execute();
$roomTypes = $roomStatement->fetchAll();

$selectSize = $roomStatement->rowCount();

$lastCategory = -1;

foreach($roomTypes as $type){
	if($type['category'] != $lastCategory){
		$lastCategory = $type['category'];
		$selectSize++;
	}
}
?>
	<nav class="navbar navbar-fixed-top nav-transp" id="top-nav">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav pull-right">
					<li><a href="#" onclick="$('body').scrollTo(0, 'slow');">Reserve now</a></li>
					<li><a href="#" onclick="$('body').scrollTo('#part-about', 'slow', {offset: {top: -$('#top-nav').height()}});">Hotel Information</a></li>
					<li><a href="#" onclick="$('body').scrollTo('#part-rooms', 'slow', {offset: {top: -$('#top-nav').height()}});">Suites & Rooms</a></li>
					<li><a href="#" onclick="$('body').scrollTo('#part-contact', 'slow', {offset: {top: -$('#top-nav').height()}});">Contact</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="jumbotron" id="jumbo-reservation">
		<div class="container" id="jumbo-reservation-content">
			<form class="form-horizontal" id="reservation-form">
				<div class="col-sm-12 text-center">
					<h1>Reserve now!</h1>
					<hr />
				</div>

				<div class="form-group collapse" id="reservation-rooms-showcase">
					<div class="col-sm-5"><img class="img-responsive img-rounded" src="assets/img/transp.png" alt="Room image" /></div>
					<div class="col-sm-7">
						<h2>Room title</h2>
						<p>Room description</p>
					</div>
					<div class="col-sm-12">
						<hr />
					</div>
				</div>

				<div class="part part-1">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="reservation-room-types">Room type</label>
						<div class="col-sm-10">
							<select class="form-control" name="room" multiple="multiple" size="<?php echo $selectSize; ?>" id="reservation-room-types">
								<?php
								$lastCategory = -1;
								foreach($roomTypes as $type){
									if($lastCategory != $type['category']) {
										if($lastCategory != -1)
											echo '</optgroup>';

										echo '<optgroup label="' . htmlentities($type['cat_name']) . '">';

										$lastCategory = $type['category'];
									}

									echo '<option value="' . $type['id'] . '">' . htmlentities($type['name']) . '</option>';
								}

								echo '</optgroup>';
								?>
							</select>
						</div>
					</div>
				</div>

				<div class="part part-2">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="reservation-rooms">Room</label>
						<div class="col-sm-10">
							<select class="form-control" name="room" multiple="multiple" size="<?php echo $selectSize; ?>" id="reservation-rooms"></select>
						</div>
					</div>
				</div>

				<div class="part part-3">
					<div class="form-group">
						<div class="col-sm-offset-1 col-sm-5">
							<label for="reservation-checkin">Check in date</label>
							<input class="form-control" name="reservation-checkin" id="reservation-checkin" type="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" />
						</div>
						<div class="col-sm-5">
							<label for="reservation-checkout">Check out date</label>
							<input class="form-control" name="reservation-checkout" id="reservation-checkout" type="date" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" />
						</div>
					</div>
					<div class="form-group" id="reservation-people-group">
						<div class="col-sm-offset-1 col-sm-5">
							<label for="reservation-people">Amount of people</label>

							<div>
								<div class="col-sm-2">
									<output id="reservation-people-output" for="reservation-people" class="text-center">1</output>
								</div>
								<div class="col-sm-10">
									<input id="reservation-people" name="reservation-people" type="range" min="1" max="1" value="1" onchange="document.getElementById('reservation-people-output').value = this.value;" />
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="part part-4">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">Name</label>
						</div>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-user"></i></span>

								<input id="reservation-firstname" type="text" placeholder="First Name" class="form-control" />
								<input id="reservation-lastname" type="text" placeholder="Last name" class="form-control" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">Living place</label>
						</div>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>

								<input id="reservation-address" type="text" placeholder="Address" class="form-control" />
								<input id="reservation-city" type="text" placeholder="City" class="form-control" />
								<input id="reservation-country" type="text" placeholder="Country" class="form-control" value="The Netherlands" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">E-mail</label>
						</div>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>

								<input id="reservation-email" type="email" placeholder="anita123@mail.com" class="form-control" />
								<input id="reservation-tel" type="tel" placeholder="+31 0644555663" class="form-control" />
							</div>
						</div>
					</div>
				</div>
				<div class="part part-5">
					<div class="alert"></div>
				</div>

				<div class="form-group" id="reservation-buttons">
					<hr />
					<div class="col-sm-4">
						<button type="submit" class="btn btn-block" disabled="disabled">&laquo; Previous</button>
					</div>
					<div class="col-sm-offset-4 col-sm-4">
						<button type="submit" class="btn btn-primary btn-block" onclick="revervationPartNext(event);">Next &raquo;</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div id="main">
		<div class="container">
			<div class="row" id="part-about">
				<div class="col-lg-5">
					<img class="img-responsive img-rounded" src="assets/img/hotel2.jpg" alt="Hotel California" />
				</div>
				<div class="col-lg-7">
					<h1>Hotel California</h1>
					<p>For 46 years, Hotel California has been the most populair music hotel in the state of California.</p>
					<p>Located in the heart of Los Angeles, our hotel provides the ultimate music oasis in the heart of the central
						district. Hotel California has been the premier choice for celebrities, presidents and dignitaries in downtown
						LA for 46 years.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-8">
					<h1>History</h1>
					<p>Founded in the wonder year 1969, Hotel California was the idea of a young entrepreneur Jonathan Smith. He wanted to
						establish a hotel where music enthousiasts could come together and share the musical experiance.</p>
					<p>It's a passion that we share wholeheartedly and try to orchestrate into our hotel as harmoniously as
						possible. That's why we have, for example, our own music manager on staff. It's also why we offer
						guitar and keyboard room service and make it possible to have music events and live sessions in
						a silent rehearsal room.</p>
				</div>
				<div class="col-lg-4">
					<img class="img-responsive img-rounded" src="assets/img/hotel.jpg" alt="Hotel California" />
				</div>
			</div>
			<div class="row row-rooms">
				<div class="col-lg-12 row-rooms-title" id="part-rooms">
					<h1>Our rooms</h1>
				</div>
				<?php
				$roomStatement = $db->prepare('SELECT * FROM rooms');
				$roomStatement->execute();

				foreach($roomStatement->fetchAll() as $room){
					?>
					<div class="col-lg-4" onclick="selectRoom(<?php echo $room['id']; ?>);">
						<div class="col-room" style="background-image: url('<?php echo htmlentities($room['image']); ?>');">
							<div class="overlay">
								<h2><?php echo htmlentities($room['name']); ?></h2>
								<p><?php echo htmlentities($room['description']); ?></p>
							</div>
						</div>
					</div>
					<?php
				}

				if($roomStatement->rowCount() == 0) {
					?>
					<div class="col-lg-12 alert alert-danger">
						<strong>Error!</strong> There are no rooms found in the database.
					</div>
					<?php
				}
				?>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<h1>Contact</h1>
					<div id="contact-notif" class="hidden alert" role="alert"></div>
				</div>
				<div class="col-lg-8" id="part-contact">
					<form class="form-horizontal" id="contact-form">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="contact-name">Name</label>
							<div class="col-sm-10">
								<div class="error-label"></div>
								<input class="form-control" name="contact-name" id="contact-name" type="text" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="contact-email">Email</label>
							<div class="col-sm-10">
								<div class="error-label"></div>
								<input class="form-control" name="contact-email" id="contact-email" type="email" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="contact-message">Message</label>
							<div class="col-sm-10">
								<div class="error-label"></div>
								<textarea class="form-control" name="contact-message" id="contact-message" rows="6"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary">Send</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-lg-4">
					<!-- plaatje voor contact -->
				</div>
			</div>
		</div>
	</div>
<?php
require_once __DIR__ . '/inc/tpl/footer.php';