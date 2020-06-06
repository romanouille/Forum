<?php
require "Pages/Layout/Start.php";
?>
<h2>Connexion</h2>

<?php
if (isset($messages)) {
?>
<div class="card blue white-text">
	<div class="card-content">
		<?=implode("<br>", $messages)?>
	</div>
</div>
<?php
}
?>

<form method="post">
	<div class="row">
		<div class="col m12 l6">
			<input type="text" name="username" placeholder="Pseudo" value="<?=isset($_POST["username"]) && is_string($_POST["username"]) ? htmlspecialchars($_POST["username"]) : ""?>">
		</div>
		<div class="col m12 l6">
			<input type="password" name="password" placeholder="Mot de passe" value="<?=isset($_POST["password"]) && is_string($_POST["password"]) ? htmlspecialchars($_POST["password"]) : ""?>">
		</div>
	</div>
	
	<div class="input-field">
		<?=Captcha::generate()?>
	</div>
	
	<input type="submit" class="btn">
</form>

<div class="input-field">
	<a href="/register" title="Créer un compte" class="btn">Créer un compte</a>
</div>
<?php
require "Pages/Layout/End.php";