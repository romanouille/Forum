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
			<td><a href="/pm/<?=$pm["id"]?>-1-<?=slug($pm["title"])?>" title="<?=htmlspecialchars($pm["title"])?>"><?=htmlspecialchars($pm["title"])?></a>
			<td><?=$pm["username"]?>
			<td><?=date("d/m/Y à H:i:s", $pm["timestamp"])?>
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
	<input type="hidden" name="hash" value="<?=$hash?>">
	
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
				<input type="text" name="title" id="title" placeholder="Titre du message" value="<?=isset($_POST["title"]) && is_string($_POST["title"]) ? htmlspecialchars($_POST["title"]) : ""?>">
				<label for="title">
					Titre du message
				</label>
			</div>

			<div class="input-field">
				<input type="text" name="receivers" id="receivers" placeholder="pseudo1,pseudo2,pseudo3,..." value="<?=isset($_POST["receivers"]) && is_string($_POST["receivers"]) ? htmlspecialchars($_POST["receivers"]) : ""?>">
				<label for="receivers">
					Destinataires
				</label>
			</div>


			<div class="input-field">
				<textarea name="message" id="message" class="materialize-textarea" placeholder="Contenu de votre message"><?=isset($_POST["message"]) && is_string($_POST["message"]) ? htmlspecialchars($_POST["message"]) : ""?></textarea>
				<label for="message">Contenu de votre message</label>
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
</form>

<?php
require "Pages/Layout/End.php";