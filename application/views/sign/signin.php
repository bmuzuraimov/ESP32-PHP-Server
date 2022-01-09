<?php
if(isset($_SESSION['session_key'])){
	header('location: /admin');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Sign In</title>
		<link rel="stylesheet" href="/public/css/style.css">
	</head>
	<body>
		<header>
			<div class="logo">
				<div class="logo-text"><a href="/">Dashboard</a></div>
			</div>
		</header>
		<div class="container">
			<div class="form-container">
				<form id="signin-form" action="/authenticate" method="POST">
					<h3 class="message">Sign In</h3>
					<div class="text-input">
						<label for="">Username</label>
						<input type="text" name="username">
					</div>
					<div class="text-input">
						<label for="">Password</label>
						<input type="password" name="password">
					</div>
					<div class="text-input">
						<input type="submit" id="submit-btn" value="signin">
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript" src="/public/js/jquery.js"></script>
		<script type="text/javascript">
			$('#submit-btn').click(function () {
		$.post($('#signin-form').attr('action'), $('#signin-form :input').serializeArray(), function(response) {
			console.log(response);
				responseObj = JSON.parse(response);
				if (responseObj.success==true) {
						$('.form-container').addClass('success');
					window.setTimeout(function () {
				location.href = '/'+responseObj.link;
				}, 2000);
				}else {
					$('.form-container').addClass('error');
				}
				});
			});
		$('.text-input input').change(function() {
		$('.form-container').removeClass( "error" )
				});
		$('#signin-form').submit(function() {
		return false;
		});
		</script>
	</body>
</html>