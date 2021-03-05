<?php
require "Pages/Layout/Start.php";
?>
<h2>Réinitialiser le mot de passe</h2>

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
			<input type="password" name="password" placeholder="Mot de passe" value="<?=isset($_POST["password"]) && is_string($_POST["password"]) ? htmlspecialchars($_POST["password"]) : ""?>">
		</div>
		<div class="col m12 l6">
			<input type="password" name="password2" placeholder="Confirmez le mot de passe" value="<?=isset($_POST["password2"]) && is_string($_POST["password2"]) ? htmlspecialchars($_POST["password2"]) : ""?>">
		</div>
	</div>
	
	<div class="input-field">
		<?=Captcha::generate()?>
	</div>
	
	<input type="submit" class="btn">
</form>

<div class="input-field">
	<a href="/login" title="J'ai déjà un compte" class="btn">J'ai déjà un compte</a>
</div>
<?php
require "Pages/Layout/End.php";