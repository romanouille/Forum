<?php
require "Pages/Layout/Start.php";
?>
<h4 class="center">Sujet : « <?=htmlspecialchars($topicTitle)?> »</h4>

<?php
if (!empty($poll)) {
?>
<div class="card blue-grey darken-1">
	<div class="card-content white-text">
		<span class="card-title">Sondage (minimum <?=$poll["points"]?> point<?=$poll["points"] > 1 ? "s" : ""?>)</span>
		<h6><?=htmlspecialchars($poll["question"])?></h6><br>
		
<?php
	foreach ($poll["responses"] as $value) {
		if (!$votedOnPoll && $userData["points"] >= $poll["points"]) {
?>
		<a href="<?=$_SERVER["REQUEST_URI"]?>?poll_response=<?=$value["id"]?>&token=<?=$hash?>" style="color:white"><?=htmlspecialchars($value["response"])?></a> (<?=$value["votes"]?> vote<?=$value["votes"] > 1 ? "s" : ""?>)<br>
<?php
		} else {
?>
		<?=htmlspecialchars($value["response"])?></a> (<?=$value["votes"]?> vote<?=$value["votes"] > 1 ? "s" : ""?>)<br>
<?php
		}
	}
?>
	</div>
</div>
<?php
}
?>
<a href="#post" title="Répondre" class="btn blue waves-effect waves-light">Répondre</a>
<a href="/forums/blabla-general/1" title="Liste des sujets" class="btn blue waves-effect waves-light">Liste des sujets</a>
<a href="#" title="Suivre" class="btn blue waves-effect waves-light">Suivre</a>

<div class="right">
	<a href="#" class="btn orange darken-3 waves-effect waves-light">Modération</a>
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
foreach ($topicMessages as $topicNb=>$topicMessage) {
	$userMessage = new User($topicMessage["author"]);
	$userData = $userMessage->getData();
?>
<div class="card light-<?=$i == 1 ? "blue" : "green"?> lighten-5 message" id="message_<?=$topicMessage["id"]?>">
	<div class="card-content">
		<div class="row">
			<div class="col l2 s4">
				<img src="<?=$staticServer?>/img/Avatar.png" alt="" title="Avatar">
				<p>
					Rang : <b>Indéterminé</b><br>
					Messages : <b><?=$userData["messages"]?></b><br>
					Points : <b><?=$userData["points"]?></b>
				</p>
			</div>
			<div class="col l10 s8">
				<b class="username"><?=$topicMessage["username"]?></b>
				<div class="right">
					<a href="#content" class="btn-floating waves-effect waves-light green" title="Citer le message" onclick="quoteMessage(<?=$topicMessage["id"]?>)"><i class="material-icons">format_quote</i></a>
					<a href="#" class="btn-floating waves-effect waves-light blue" title="Envoyer un MP"><i class="material-icons">message</i></a>
<?php
if ($topicMessage["author"] == $sessionData["user_id"] && time()-$topicMessage["timestamp"] <= 600) {
?>
					<a href="<?=$_SERVER["REQUEST_URI"]?>?edit_message=<?=$topicMessage["id"]?>" class="btn-floating waves-effect waves-light grey" title="Éditer le message" target="_blank"><i class="material-icons">edit</i></a>
<?php
}

if ($sessionData["admin"] > 0) {
	if (!$userMessage->isKicked($forumId)) {
?>
					<a href="#" class="btn-floating waves-effect waves-light red" title="Kicker le membre" onclick="kickUser(<?=$topicMessage["id"]?>)"><i class="material-icons">gavel</i></a>
<?php
	} else {
?>
					<a href="#" class="btn-floating waves-effect waves-light green" title="Dékicker le membre" onclick="unkickUser(<?=$topicMessage["author"]?>, <?=$forumId?>)"><i class="material-icons">gavel</i></a>
<?php
	}
	
	if (!$topicMessage["deleted"]) {
		if ($topicNb == 0 && $page == 1) {
?>
					<a href="#" class="btn-floating waves-effect waves-light red" title="Supprimer le sujet" onclick="deleteTopic(<?=$forumId?>, <?=$topicId?>)"><i class="material-icons">delete</i></a>
<?php
		} else {
?>
					<a href="#" class="btn-floating waves-effect waves-light red" title="Supprimer le message" onclick="deleteMessage(<?=$topicMessage["id"]?>)"><i class="material-icons">delete</i></a>
<?php
		}
	} else {
		if ($topicNb == 0 && $page == 1) {
?>
					<a href="#" class="btn-floating waves-effect waves-light green" title="Restaurer le sujet" onclick="restoreMessage(<?=$forumId?>, <?=$topicId?>)"><i class="material-icons">delete</i></a>
<?php
		} else {
?>
					<a href="#" class="btn-floating waves-effect waves-light green" title="Restaurer le message" onclick="restoreMessage(<?=$topicMessage["id"]?>)"><i class="material-icons">delete</i></a>
<?php
		}
	}
} else {
?>
					<a href="#" class="btn-floating waves-effect waves-light red" title="Signaler le message"><i class="material-icons">report_problem</i></a>
<?php
}
?>
				</div>
				<br>
				<a href="#" class="permalink" title="Lien permanent">Posté le <?=date("d/m/Y à H:i:s", $topicMessage["timestamp"])?></a>
				<hr>
				<div class="message-content">
					<?=parseMessage($topicMessage["content"], $topicMessage["id"])?>
				</div>
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
	<input type="hidden" name="token" value="<?=$hash?>">
	
	<h5>Nouveau message</h5>
	
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
				<textarea name="content" id="content" class="materialize-textarea" placeholder="Contenu de votre message"><?=isset($_POST["content"]) && is_string($_POST["content"]) ? htmlspecialchars($_POST["content"]) : ""?></textarea>
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