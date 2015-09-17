<?php
/**
 * Thanks Mentor!
 * Half of the code is copied from the following URL
 * https://github.com/actuallymentor/oop-pdo-login
 */
require_once __DIR__ . '/inc/bootstrap.php';
require_once __DIR__ . '/inc/Usermanagement.class.php';

if(isset($_POST['action'])){
	switch ($_POST['action']){
		case 'register':
			$user = new User($_POST['username'], $_POST['password'], $_POST['form_token']);
			break;

		case 'login':
			$session = new Session($_POST['username'],$_POST['password']);
			break;

		case 'logout':
			Logout();
			break;
	}
}

$form_token = md5(uniqid('auth', true));
$_SESSION['form_token'] = $form_token;

require_once __DIR__ . '/inc/tpl/header.php';
?>
	<div id="main" class="container">
		<?php if (!SessionStatus()) { ?>
		<h1>Log in / Sign up</h1>
		<form method="post" role="form">
			<fieldset>
				<p>
					<input type="radio" id="login" name="action" value="login" checked="checked" /><label for="login">Login</label>
					<input type="radio" id="register" name="action" value="register" /><label for="register">Register</label>
				</p>
				<p>
					<input type="text" id="username" name="username" value="" maxlength="20" placeholder="Username (max 20 char)" />
				</p>
				<p>
					<input type="password" id="password" name="password" value="" maxlength="100" placeholder="Password (max 100 char)" />
				</p>
				<p>
					<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
					<input class="btn btn-primary" type="submit" value="&rarr; Login / register" />
				</p>
			</fieldset>
		</form>
	<?php }else{ ?>
			<div class="hidden-print">
				<h1>Hotel California - Logged in!</h1>
				<form method="post" role="form">
					<input type="hidden" id="logout" name="action" value="logout"/>
					<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />

					<a href="?view=categories" class="btn btn-primary">Room categories</a>
					<a href="?view=plans" class="btn btn-primary">Room plans</a>
					<a href="?view=rooms" class="btn btn-primary">Rooms</a>
					<a href="?view=reservations" class="btn btn-primary">Reservations</a>

					<div class="pull-right">
						<a href="javascript: window.print()" class="btn btn-default">Print this page</a>
						<input class="btn btn-default" type="submit" value="Sign out" />
					</div>
				</form>
			</div>

			<?php
			$view = strtolower(isset($_GET['view']) ? $_GET['view'] : '');

			if($view == 'categories') {
				if(isset($_POST['add-cat-name'])){
					$q = $db->prepare('INSERT INTO room_categories (name) VALUES (?)');
					$q->bindParam(1, $_POST['add-cat-name']);

					$q->execute();
				}

				$q = $db->prepare('SELECT * FROM room_categories');
				$q->execute();

				$first = true;
				echo '<form method="post"><table class="table table-striped">';
				foreach($q->fetchAll(2) as $item){
					if($first){
						echo '<thead><tr>';
						foreach($item as $col => $val){
							echo '<th>' . htmlentities($col) . '</th>';
						}

						echo '</tr></thead><tbody>';
						$first = false;
					}

					echo '<tr>';
					foreach($item as $col => $val){
						echo '<td>' . htmlentities($val) . '</td>';
					}
					echo '</tr>';
				}

				?>
				<tfooter>
					<tr>
						<td>
							<input class="btn btn-primary btn-block" type="submit" value="Add category" />
						</td>
						<td>
							<input class="form-control" name="add-cat-name" placeholder="Category name" />
						</td>
					</tr>
				</tfooter>
				<?php

				echo '</tbody></table></form>';
			}elseif($view == 'plans') {
				$q = $db->prepare('SELECT * FROM room_types');
				$q->execute();

				$first = true;
				echo '<table class="table table-striped">';
				foreach($q->fetchAll(2) as $item){
					if($first){
						echo '<thead><tr>';
						foreach($item as $col => $val){
							echo '<th>' . htmlentities($col) . '</th>';
						}

						echo '</tr></thead><tbody>';
						$first = false;
					}

					echo '<tr>';
					foreach($item as $col => $val){
						echo '<td>' . htmlentities($val) . '</td>';
					}
					echo '</tr>';
				}
				echo '</tbody></table>';
			}elseif($view == 'rooms') {
				$q = $db->prepare('SELECT * FROM rooms');
				$q->execute();

				$first = true;
				echo '<table class="table table-striped">';
				foreach($q->fetchAll(2) as $item){
					if($first){
						echo '<thead><tr>';
						foreach($item as $col => $val){
							echo '<th>' . htmlentities($col) . '</th>';
						}

						echo '</tr></thead><tbody>';
						$first = false;
					}

					echo '<tr>';
					foreach($item as $col => $val){
						echo '<td>' . htmlentities($val) . '</td>';
					}
					echo '</tr>';
				}
				echo '</tbody></table>';
			}elseif($view == 'reservations') {
				$q = $db->prepare('SELECT * FROM reservations');
				$q->execute();

				$first = true;
				echo '<table class="table table-striped">';
				foreach($q->fetchAll(2) as $item){
					if($first){
						echo '<thead><tr>';
						foreach($item as $col => $val){
							echo '<th>' . htmlentities($col) . '</th>';
						}

						echo '</tr></thead><tbody>';
						$first = false;
					}

					echo '<tr>';
					foreach($item as $col => $val){
						echo '<td>' . htmlentities($val) . '</td>';
					}
					echo '</tr>';
				}
				echo '</tbody></table>';
			}else{
				echo 'Please select an option above this text.';
			}
			?>
	<?php } ?>
	</div>
<?php
require_once __DIR__ . '/inc/tpl/footer.php';