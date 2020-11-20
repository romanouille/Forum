<?php
require "Pages/Layout/Start.php";
?>
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

if (!isset($success)) {
?>
<h1>Accès étendu</h1>

<form method="post">
	<input type="hidden" name="token" value="<?=$hash?>">
	Mot de passe : <input type="password" name="password" placeholder="Mot de passe" required><br><br>
	
	<input type="submit" class="btn">
</form>
<?php
}

require "Pages/Layout/End.php";