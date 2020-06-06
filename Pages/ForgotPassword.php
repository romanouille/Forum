<?php
require "Pages/Layout/Start.php";
?>
<h2>Mot de passe oublié</h2>

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