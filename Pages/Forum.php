<?php
require "Pages/Layout/Start.php";
?>
<form method="get" id="search-form">
	<div class="row">
		<div class="col l3 m12 s12">
			<input type="text" name="text" placeholder="Rechercher">
		</div>
		<div class="col l3 m12 s12">
			<select name="type">
				<option value="title">Sujet</option>
				<option value="author">Auteur</option>
				<option value="message">Message</option>
			</select>
		</div>
		<div class="col l6 m12 s12">
			<button class="btn grey darken-1 waves-effect waves-light"><i class="material-icons">search</i></button>
			<a href="/forums/1-1-blabla-general" class="btn grey darken-1 right waves-effect waves-light" title="Actualiser la page"><i class="material-icons">refresh</i></a>
		</div>
	</div>
</form>
<div class="row">
	<div class="col s1">
<?php
if ($page > 1) {
?>
		<a href="/forum/<?=$forumId?>-<?=$page-1?>" title="Page précédente"><i class="material-icons">chevron_left</i></a>
<?php
}
?>
	</div>
	
	<div class="col s1 offset-s10">
<?php
if ($page < $pagesNb) {
?>
		<a href="/forum/<?=$forumId?>-<?=$page+1?>" class="right" title="Page suivante"><i class="material-icons">chevron_right</i></a>
<?php
}
?>
	</div>
</div>
<table id="topics-list">
	<thead>
		<tr>
			<th id="topic-icon">
			<th id="topic-title">Sujet
			<th id="topic-author">Auteur
			<th id="topic-nb" class="hide-on-med-and-down">Nb
			<th id="topic-last" class="hide-on-med-and-down">Dernier message
			<th id="topic-mod">
	</thead>
	<tbody>
<?php
foreach ($topics as $topic) {
?>
		<tr>
<?php
	if ($topic["deleted"]) {
?>
			<td><i class="material-icons blue-text">delete</i>
<?php
	} elseif ($topic["pinned"] && !$topic["locked"]) {
?>
			<td><i class="material-icons green-text">push_pin</i>
<?php
	} elseif ($topic["pinned"] && $topic["locked"]) {
?>
			<td><i class="material-icons red-text">push_pin</i>
<?php
	} elseif ($topic["replies"] >= 20) {
?>
			<td><i class="material-icons red-text">folder</i>
<?php
	} else {
?>
			<td><i class="material-icons yellow-text">folder</i>
<?php
	}
?>
	
			<td><a href="/forums/<?=$forumId?>-<?=$topic["id"]?>-1-lol" title="lol"><?=$topic["title"]?></a>
			<td><a href="/user/<?=$topic["username"]?>"><?=$topic["username"]?></a>
			<td class="hide-on-med-and-down"><?=$topic["replies"]?>
			<td class="hide-on-med-and-down"><?=date("d/m H:i:s", $topic["last_message_timestamp"])?>
			<td>
<?php
}
?>
	</tbody>
</table>

<div class="row">
	<div class="col s1">
<?php
if ($page > 1) {
?>
		<a href="/forum/<?=$forumId?>-<?=$page-1?>" title="Page précédente"><i class="material-icons">chevron_left</i></a>
<?php
}
?>
	</div>
	
	<div class="col s1 offset-s10">
<?php
if ($page < $pagesNb) {
?>
		<a href="/forum/<?=$forumId?>-<?=$page+1?>" class="right" title="Page suivante"><i class="material-icons">chevron_right</i></a>
<?php
}
?>
	</div>
</div>

<form method="post">
	<input type="hidden" name="hash" value="<?=$hash?>">
	
	<h5>Nouveau sujet</h5>
	
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
				<input type="text" name="title" id="title" placeholder="Titre du sujet" value="<?=isset($_POST["title"]) && is_string($_POST["title"]) ? htmlspecialchars($_POST["title"]) : ""?>">
				<label for="title">
					Titre du sujet
				</label>
			</div>
			<div class="input-field">
				<textarea name="message" id="message" class="materialize-textarea" placeholder="Contenu de votre sujet"><?=isset($_POST["message"]) && is_string($_POST["message"]) ? htmlspecialchars($_POST["message"]) : ""?></textarea>
				<label for="message">Contenu de votre sujet</label>
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