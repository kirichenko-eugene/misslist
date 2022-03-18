<?php 
	include 'config.php';
	include 'functions.php';

	if (isset($_SESSION['auth'])) {
		header("Location: $siteroot");
		die();
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Логин</title>
	<link rel="stylesheet" href="<?=$cssroot?>">
</head>
<body>
	<div class="container d-flex h-100 justify-content-center">
		<div class="row align-self-center">
			<div class="col-xl-10 col-lg-10 mx-auto mt-5">
				<div class="jumbotron text-center">
					<h1>GoodCity <span class="badge badge-warning">CallCenter</span></h1>
					<h3>Войдите в учетную запись</h3>
					<form method="post">
						<div class="form-group">
							<label for="username">Ваша почта или логин</label>
							<input type="text" class="form-control" id="username" aria-describedby="loginHelp" placeholder="username@goodcity.com.ru" name="login" required>
						</div>
						<div class="form-group">
							<label for="userpass">Пароль</label>
							<input type="password" class="form-control" id="userpass" placeholder="Введите пароль" name="password" required>
						</div>
						<button type="submit" class="btn btn-primary btn-lg" name="submit">Войти</button>
					</form>

					<?php getUser($domain, $ldaprdn, $ldappass, $userDn, $siteroot); ?>

				</div>
			</div>
		</div>
	</div>
</body>
</html>
		

	
