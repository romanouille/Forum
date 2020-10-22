<?php
require "Pages/Layout/Start.php";
?>
<h4 class="center">Sujet : « <?=htmlspecialchars($topicTitle)?> »</h4>

<form method="post">
	<input type="hidden" name="token" value="<?=$hash?>">
	
	<h5>Éditer le message #<?=$messageId?></h5>
<?php
if ($userLogged) {
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
?>
	
	<div class="row">
		<div class="col s12 m10">
			<div class="input-field">
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[b]', '[/b]')"><i class="material-icons">format_bold</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[i]', '[/i]')"><i class="material-icons">format_italic</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[s]', '[/s]')"><i class="material-icons">indeterminate_check_box</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[u]', '[/u]')"><i class="material-icons">highlight</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[spoilers]', '[/spoilers]')"><i class="material-icons">remove_red_eye</i></button>
				<button type="button" class="btn btn-small modal-trigger" href="#modal1"><i class="material-icons">image</i></button>
				<textarea name="content" id="content" class="materialize-textarea" placeholder="Contenu de votre message"><?=isset($_POST["content"]) && is_string($_POST["content"]) ? htmlspecialchars($_POST["content"]) : htmlspecialchars($messageData["content"])?></textarea>
			</div>
			<div class="input-field">
				<?=Captcha::generate()?>
			</div>
			<div class="input-field">
				<button type="submit" class="btn green waves-effect waves-light">Valider</button>
				<button type="button" class="btn waves-effect waves-light">Prévisualiser</button>
			</div>
		</div>
	</div>
	
	<div id="modal1" class="modal">
		<div class="modal-content">
			<h4>Rechercher un sticker</h4>
			<input type="text" placeholder="Tags" onkeyup="searchSticker(this.value)"><br><br>
			
			<div class="row" id="stickers">
			</div>
		</div>
		
		<div class="modal-footer">
			<a class="modal-close waves-effect waves-green btn-flat">Fermer</a>
		</div>
	</div>
<?php
} else {
?>
	<div class="card orange white-text">
		<div class="card-content">
			Vous devez être connecté afin de pouvoir répondre à un sujet.
		</div>
	</div>
<?php
}
?>

</form>
<?php
require "Pages/Layout/End.php";