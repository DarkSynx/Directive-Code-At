<?php session_start(); ?>
<html><head><title>page de login</title>
	<meta charset="UTF-8">
	<style>body{background-color:grey;}
		#login{
			border: 1px solid blue;
			margin: auto;
		}</style></head>
<body style="background-color:grey; color:white;">
	<?php $id = session_id();
		$uservar[$id] = false; /*<!-- sup -->*/ 
			$l = filter_input(INPUT_POST, 'login',  FILTER_SANITIZE_STRING);
			$p = filter_input(INPUT_POST, 'password',  FILTER_SANITIZE_STRING);
		
			if($l == 'logme' and $p == 'passme') {
				$uservar[$id] = TRUE;
			} else {
				$uservar[$id] = FALSE;
			}
/*<!-- sup -->*/ if($uservar[$id]): ?>
<!-- start import:segment loaduserpage to file : 'loginsegment.cat' -->
	<div>
	<h1>page utilisateur</h1>
	</div>
<!-- END import:segment loaduserpage to file : 'loginsegment.cat' -->
		<?php else: ?>
<!-- start import:segment loadlogin to file : 'loginsegment.cat' -->
	<div id='login'>
	<form action="login.php" method="post">
		<input type="text" name="login" value=""/>
		<input type="password" name="password" value=""/>
		<input type="submit" value="connexion">
	</form>
	</div>
<!-- END import:segment loadlogin to file : 'loginsegment.cat' -->
		<?php endif; ?>
	<script>$( document ).ready(function() {
			console.log( "ready!" );
		});</script>
</body></html>
