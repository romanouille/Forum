<?php
require "Pages/Layout/Start.php";
?>
<h4 class="center">Sujet : « <?=htmlspecialchars($topicTitle)?> »</h4>

<a href="#post" title="Répondre" class="btn blue waves-effect waves-light">Répondre</a>
<a href="/forums/blabla/1" title="Liste des sujets" class="btn blue waves-effect waves-light">Liste des sujets</a>
<a href="#" title="Suivre" class="btn blue waves-effect waves-light">Suivre</a>

<div class="right">
	<a href="#" class="btn orange darken-3 waves-effect waves-light dropdown-trigger">Modération</a>
</div>

<ul class="pagination center">
	<li class="waves-effect<?=$page < 2 ? " disabled" : ""?>"><a href="<?=$page > 1 ? "/forums/$forumId-$topicId-".($page-1)."-$topicSlug" : "#"?>"><i class="material-icons">chevron_left</i></a></li>
<?php

$cond = $page > 6 ? $page-5 : 1;

if ($cond > 1) {
?>
	<li class="waves-effect"><a href="/forums/<?=$forumId?>-<?=$topicId?>-1-<?=$topicSlug?>">1</a></li> ...
<?php
}

for ($i = $cond; $i <= $page+5; $i++) {
	if ($i > $totalPages) {
		break;
	}

	if ($i == $page) {
?>
	<li class="active"><a href="#"><?=$i?></a></li>
<?php
	} else {
?>
	<li class="waves-effect"><a href="/forums/<?=$forumId?>-<?=$topicId?>-<?=$i?>-<?=$topicSlug?>"><?=$i?></a></li>
<?php
	}
}

if ($i <= $totalPages) {
?>
... <li class="waves-effect"><a href="/forums/<?=$forumId?>-<?=$topicId?>-<?=$totalPages?>-<?=$topicSlug?>"><?=$totalPages?></a></li>
<?php
}
?>
	<li class="waves-effect<?=$page >= $totalPages ? " disabled" : ""?>"><a href="<?=$page < $totalPages ? "/forums/$forumId-$topicId-".($page+1)."-$topicSlug" : "#"?>"><i class="material-icons">chevron_right</i></a></li>
</ul>


<?php
$i = 1;
foreach ($topicMessages as $topicMessage) {
	$userMessage = new User($topicMessage["author"]);
	$userData = $userMessage->getData();
?>
<div class="card light-<?=$i == 1 ? "blue" : "green"?> lighten-5 message" id="message_<?=$topicMessage["id"]?>">
	<div class="card-content">
		<div class="row">
			<div class="col l2 s4">
				<img src="http://127.0.0.2/img/Avatar.png" alt="" title="Avatar">
				<p>
					Rang : <b>Indéterminé</b><br>
					Messages : <b><?=$userData["messages"]?></b><br>
					Points : <b><?=$userData["points"]?></b>
				</p>
			</div>
			<div class="col l10 s8">
				<b class="username"><?=$topicMessage["username"]?></b>
				<div class="right">
					<a href="#message" class="btn-floating waves-effect waves-light green" title="Citer le message" onclick="quoteMessage(<?=$topicMessage["id"]?>)"><i class="material-icons">format_quote</i></a>
					<a href="#" class="btn-floating waves-effect waves-light blue" title="Envoyer un MP"><i class="material-icons">message</i></a>
					<a href="#" class="btn-floating waves-effect waves-light grey" title="Éditer le message"><i class="material-icons">edit</i></a>
					<a href="#" class="btn-floating waves-effect waves-light red" title="Kicker le membre"><i class="material-icons">gavel</i></a>
					<a href="#" class="btn-floating waves-effect waves-light black" title="Bannir le membre"><i class="material-icons">gavel</i></a>
					<a href="#" class="btn-floating waves-effect waves-light red" title="Supprimer le message"><i class="material-icons">delete</i></a>
					<a href="#" class="btn-floating waves-effect waves-light red" title="Signaler le message"><i class="material-icons">report_problem</i></a>
				</div>
				<br>
				<a href="#" class="permalink" title="Lien permanent">Posté le <?=date("d/m/Y à H:i:s", $topicMessage["timestamp"])?></a>
				<hr>
					<?=parseMessage($topicMessage["message"])?>
				</div>
		</div>
	</div>
</div>

<?php
	$i = $i == 1 ? 2 : 1;
}
?>

<ul class="pagination center">
	<li class="waves-effect<?=$page < 2 ? " disabled" : ""?>"><a href="<?=$page > 1 ? "/forums/$forumId-$topicId-".($page-1)."-$topicSlug" : "#"?>"><i class="material-icons">chevron_left</i></a></li>
<?php

$cond = $page > 6 ? $page-5 : 1;

if ($cond > 1) {
?>
	<li class="waves-effect"><a href="/forums/<?=$forumId?>-<?=$topicId?>-1-<?=$topicSlug?>">1</a></li> ...
<?php
}

for ($i = $cond; $i <= $page+5; $i++) {
	if ($i > $totalPages) {
		break;
	}

	if ($i == $page) {
?>
	<li class="active"><a href="#"><?=$i?></a></li>
<?php
	} else {
?>
	<li class="waves-effect"><a href="/forums/<?=$forumId?>-<?=$topicId?>-<?=$i?>-<?=$topicSlug?>"><?=$i?></a></li>
<?php
	}
}

if ($i <= $totalPages) {
?>
... <li class="waves-effect"><a href="/forums/<?=$forumId?>-<?=$topicId?>-<?=$totalPages?>-<?=$topicSlug?>"><?=$totalPages?></a></li>
<?php
}
?>
	<li class="waves-effect<?=$page >= $totalPages ? " disabled" : ""?>"><a href="<?=$page < $totalPages ? "/forums/$forumId-$topicId-".($page+1)."-$topicSlug" : "#"?>"><i class="material-icons">chevron_right</i></a></li>
</ul>


<form method="post" id="post">
	<input type="hidden" name="hash" value="<?=$hash?>">
	
	<h5>Nouveau message</h5>
	
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
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('message', '[b]', '[/b]')"><i class="material-icons">format_bold</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('message', '[i]', '[/i]')"><i class="material-icons">format_italic</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('message', '[s]', '[/s]')"><i class="material-icons">indeterminate_check_box</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('message', '[u]', '[/u]')"><i class="material-icons">highlight</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('message', '[spoilers]', '[/spoilers]')"><i class="material-icons">remove_red_eye</i></button>
				<button type="button" class="btn btn-small" onclick="alert('Indisponible')"><i class="material-icons">poll</i></button>
				<button type="button" class="btn btn-small" onclick="alert('Indisponible')"><i class="material-icons">insert_emoticon</i></button>
				<textarea name="message" id="message" class="materialize-textarea" placeholder="Contenu de votre message"><?=isset($_POST["message"]) && is_string($_POST["message"]) ? htmlspecialchars($_POST["message"]) : ""?></textarea>
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