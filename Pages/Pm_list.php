<?php
require "Pages/Layout/Start.php";
?>
<h2>Messages privés</h2>

<div class="row">
	<div class="col s1">
<?php
if ($page > 1) {
?>
		<a href="/pm/<?=$page-1?>" title="Page précédente"><i class="material-icons">chevron_left</i></a>
<?php
}
?>
	</div>
	
	<div class="col s1 offset-s10">
<?php
if ($page < $pagesNb) {
?>
		<a href="/pm/<?=$page+1?>" class="right" title="Page suivante"><i class="material-icons">chevron_right</i></a>
<?php
}
?>
	</div>
</div>

<table id="topics-list">
	<thead>
		<tr>
			<th id="topic-title">Sujet
			<th id="topic-author">Auteur
			<th id="topic-last" class="hide-on-med-and-down">Dernier message
	</thead>
	
	<tbody>
<?php
foreach ($pmList as $pm) {
?>
		<tr>
			<td><a href="/pm/<?=$pm["id"]?>-1" title="<?=htmlspecialchars($pm["title"])?>"><?=htmlspecialchars($pm["title"])?></a>
			<td><?=$pm["username"]?>
			<td class="hide-on-med-and-down"><?=date("d/m/Y à H:i:s", $pm["timestamp"])?>
<?php
}
?>
	</tbody>
</table>
<br>


<div class="row">
	<div class="col s1">
<?php
if ($page > 1) {
?>
		<a href="/pm/<?=$page-1?>" title="Page précédente"><i class="material-icons">chevron_left</i></a>
<?php
}
?>
	</div>
	
	<div class="col s1 offset-s10">
<?php
if ($page < $pagesNb) {
?>
		<a href="/pm/<?=$page+1?>" class="right" title="Page suivante"><i class="material-icons">chevron_right</i></a>
<?php
}
?>
	</div>
</div>

<form method="post">
	<input type="hidden" name="token" value="<?=$hash?>">
	
	<h5>Nouveau message privé</h5>
	
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
				<input type="text" name="title" id="title" placeholder="Titre du MP" value="<?=isset($_POST["title"]) && is_string($_POST["title"]) ? htmlspecialchars($_POST["title"]) : ""?>">
				<label for="title">
					Titre du MP
				</label>
			</div>

			<div class="input-field">
				<input type="text" name="receivers" id="receivers" placeholder="pseudo1,pseudo2,pseudo3,..." value="<?=isset($_POST["receivers"]) && is_string($_POST["receivers"]) ? htmlspecialchars($_POST["receivers"]) : ""?>">
				<label for="receivers">
					Destinataires
				</label>
			</div>


			<div class="input-field"><br>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[b]', '[/b]')"><i class="material-icons">format_bold</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[i]', '[/i]')"><i class="material-icons">format_italic</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[s]', '[/s]')"><i class="material-icons">indeterminate_check_box</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[u]', '[/u]')"><i class="material-icons">highlight</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[spoilers]', '[/spoilers]')"><i class="material-icons">remove_red_eye</i></button>
				<button type="button" class="btn btn-small modal-trigger" href="#modal1"><i class="material-icons">image</i></button>
				<textarea name="message" id="message" class="materialize-textarea" placeholder="Contenu de votre MP"><?=isset($_POST["message"]) && is_string($_POST["message"]) ? htmlspecialchars($_POST["message"]) : ""?></textarea>
				<label for="message">Contenu de votre MP</label>
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
</form>

<?php
require "Pages/Layout/End.php";