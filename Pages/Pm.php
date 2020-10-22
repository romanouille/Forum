<?php
require "Pages/Layout/Start.php";
?>
<h4 class="center">Sujet : « <?=htmlspecialchars($pmTitle)?> »</h4>

<a href="#post" title="Répondre" class="btn blue waves-effect waves-light">Répondre</a>
<a href="/pm/" title="Liste des MP" class="btn blue waves-effect waves-light">Liste des MP</a>

<ul class="pagination center">
	<li class="waves-effect<?=$page < 2 ? " disabled" : ""?>"><a href="<?=$page > 1 ? "/pm/$pmId-".($page-1) : "#"?>"><i class="material-icons">chevron_left</i></a></li>
<?php

$cond = $page > 6 ? $page-5 : 1;

if ($cond > 1) {
?>
	<li class="waves-effect"><a href="/pm/<?=$pmId?>-1">1</a></li> ...
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
	<li class="waves-effect"><a href="/pm/<?=$pmId?>-<?=$i?>"><?=$i?></a></li>
<?php
	}
}

if ($i <= $totalPages) {
?>
... <li class="waves-effect"><a href="/pm/<?=$pmId?>-<?=$totalPages?>"><?=$totalPages?></a></li>
<?php
}
?>
	<li class="waves-effect<?=$page >= $totalPages ? " disabled" : ""?>"><a href="<?=$page < $totalPages ? "/pm/$pmId".($page+1) : "#"?>"><i class="material-icons">chevron_right</i></a></li>
</ul>


<?php
$i = 1;
foreach ($pmMessages as $pmMessage) {
	$userMessage = new User($pmMessage["author"]);
	$userData = $userMessage->getData();
?>
<div class="card light-<?=$i == 1 ? "blue" : "green"?> lighten-5 message" id="message_<?=$pmMessage["id"]?>">
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
				<b class="username"><?=$pmMessage["username"]?></b>
				<br>
				<a href="#message_<?=$pmMessage["id"]?>" class="permalink" title="Lien permanent">Posté le <?=date("d/m/Y à H:i:s", $pmMessage["timestamp"])?></a>
				<hr>
				<div class="message-content">
					<?=parseMessage($pmMessage["content"], $pmMessage["id"])?>
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
	<li class="waves-effect<?=$page < 2 ? " disabled" : ""?>"><a href="<?=$page > 1 ? "/pm/$pmId-".($page-1) : "#"?>"><i class="material-icons">chevron_left</i></a></li>
<?php

$cond = $page > 6 ? $page-5 : 1;

if ($cond > 1) {
?>
	<li class="waves-effect"><a href="/pm/<?=$pmId?>-1">1</a></li> ...
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
	<li class="waves-effect"><a href="/pm/<?=$pmId?>-<?=$i?>"><?=$i?></a></li>
<?php
	}
}

if ($i <= $totalPages) {
?>
... <li class="waves-effect"><a href="/pm/<?=$pmId?>-<?=$totalPages?>"><?=$totalPages?></a></li>
<?php
}
?>
	<li class="waves-effect<?=$page >= $totalPages ? " disabled" : ""?>"><a href="<?=$page < $totalPages ? "/pm/$pmId".($page+1) : "#"?>"><i class="material-icons">chevron_right</i></a></li>
</ul>


<form method="post" id="post">
	<input type="hidden" name="token" value="<?=$hash?>">
	
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
</form>

<?php
require "Pages/Layout/End.php";