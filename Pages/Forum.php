<?php
require "Pages/Layout/Start.php";
?>
<form method="get" action="/forums/<?=$match[1]?>/1" id="search-form">
	<div class="row">
		<input type="hidden" name="mode" value="search">
		<div class="col l3 m12 s12">
			<input type="text" name="content" placeholder="Rechercher" value="<?=isset($searchText) ? htmlspecialchars($searchText) : ""?>">
		</div>
		<div class="col l3 m12 s12">
			<select name="type">
				<option value="title"<?=isset($match[4]) && $match[4] == "title" ? " selected" : ""?>>Sujet</option>
				<option value="author"<?=isset($match[4]) && $match[4] == "author" ? " selected" : ""?>>Auteur</option>
				<option value="message"<?=isset($match[4]) && $match[4] == "message" ? " selected" : ""?>>Message</option>
			</select>
		</div>
		<div class="col l6 m12 s12">
			<button class="btn grey darken-1 waves-effect waves-light"><i class="material-icons">search</i></button>
			<a href="<?=$_SERVER["REQUEST_URI"]?>" class="btn grey darken-1 right waves-effect waves-light" title="Actualiser la page"><i class="material-icons">refresh</i></a>
		</div>
	</div>
</form>
<div class="row">
	<div class="col s1">
<?php
if ($page > 1) {
	if (!$isSearch) {
?>
		<a href="/forums/<?=$match[1]?>/<?=$page-1?>" title="Page précédente"><i class="material-icons">chevron_left</i></a>
<?php
	} else {
?>
		<a href="/forums/<?=$match[1]?>/<?=$page-1?>?mode=search&content=<?=htmlspecialchars($searchText)?>&type=<?=$match[4]?>" title="Page précédente"><i class="material-icons">chevron_left</i></a>
<?php
	}
}
?>
	</div>
	
	<div class="col s1 offset-s10">
<?php
if ($page < $pagesNb) {
	if (!$isSearch) {
?>
		<a href="/forums/<?=$match[1]?>/<?=$page+1?>" class="right" title="Page suivante"><i class="material-icons">chevron_right</i></a>
<?php
	} else {
?>
		<a href="/forums/<?=$match[1]?>/<?=$page+1?>?mode=search&content=<?=htmlspecialchars($searchText)?>&type=<?=$match[4]?>" title="Page suivante"><i class="material-icons">chevron_right</i></a>
<?php
	}
}
?>
	</div>
</div>

<?php
if (!empty($topics)) {
?>
<table id="topics-list">
	<thead>
		<tr>
			<th id="topic-icon">
			<th id="topic-title">Sujet
			<th id="topic-author">Auteur
			<th id="topic-nb" class="hide-on-med-and-down">Nb
			<th id="topic-last" class="hide-on-med-and-down">Dernier message
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
	} elseif ($topic["locked"]) {
?>
			<td><i class="material-icons grey-text">lock</i>
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
	
			<td><a href="/forums/<?=$match[1]?>/<?=$topic["id"]?>-1-<?=slug($topic["title"])?>" title="<?=htmlspecialchars($topic["title"])?>"><?=$topic["title"]?></a>
			<td><a href="/user/<?=$topic["topic_username"]?>" target="_blank"><?=$topic["topic_username"]?></a>
			<td class="hide-on-med-and-down"><?=$topic["replies"]?>
			<td class="hide-on-med-and-down"><?=date("d/m/y H:i:s", $topic["last_message_timestamp"])?>
<?php
	if (isset($topic["content"])) {
?>
			<tr>
				<td>
				<td><?=htmlspecialchars($topic["content"])?>
				<td><a href="/user/<?=$topic["message_username"]?>" target="_blank"><?=$topic["message_username"]?></a>
				<td class="hide-on-med-and-down">
				<td class="hide-on-med-and-down"><?=date("d/m/y H:i:s", $topic["message_timestamp"])?>
<?php
	}

}
?>
	</tbody>
</table>
<?php
} else {
?>
<div class="card blue white-text">
	<div class="card-content">
		Il n'y a pas de sujet à afficher.
	</div>
</div>
<?php
}
?>

<div class="row">
	<div class="col s1">
<?php
if ($page > 1) {
	if (!$isSearch) {
?>
		<a href="/forums/<?=$match[1]?>/<?=$page-1?>" title="Page précédente"><i class="material-icons">chevron_left</i></a>
<?php
	} else {
?>
		<a href="/forums/<?=$match[1]?>/<?=$page-1?>?mode=search&content=<?=htmlspecialchars($searchText)?>&type=<?=$match[4]?>" title="Page précédente"><i class="material-icons">chevron_left</i></a>
<?php
	}
}
?>
	</div>
	
	<div class="col s1 offset-s10">
<?php
if ($page < $pagesNb) {
	if (!$isSearch) {
?>
		<a href="/forums/<?=$match[1]?>/<?=$page+1?>" class="right" title="Page suivante"><i class="material-icons">chevron_right</i></a>
<?php
	} else {
?>
		<a href="/forums/<?=$match[1]?>/<?=$page+1?>?mode=search&content=<?=htmlspecialchars($searchText)?>&type=<?=$match[4]?>" title="Page suivante"><i class="material-icons">chevron_right</i></a>
<?php
	}
}
?>
	</div>
</div>

<form method="post">
	<input type="hidden" name="token" value="<?=$hash?>">
	
	<h5>Nouveau sujet</h5>
	
<?php
if ($userLogged) {
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
			<div style="display:none" id="poll">
				<div class="input-field">
					<button class="btn waves-effect waves-light" onclick="pollNewResponse()">Ajouter une réponse</button>
				</div>
				<div class="input-field">
					<input type="text" name="poll_question" placeholder="Saisissez la question du sondage" value="<?=isset($_POST["poll_question"]) && is_string($_POST["poll_question"]) ? htmlspecialchars($_POST["poll_question"]) : ""?>">
					<label for="poll_title">
						Question du sondage
					</label>
				</div>
				<div class="input-field">
					<input type="text" name="poll_points" placeholder="Nombre de points minimum" value="<?=isset($_POST["poll_points"]) && is_string($_POST["poll_points"]) ? htmlspecialchars($_POST["poll_points"]) : 1?>">
					<label for="poll_title">
						Nombre de points minimum
					</label>
				</div>
<?php
	if (isset($_POST["poll_question"]) && is_string($_POST["poll_question"]) && isset($_POST["poll_responses"])) {
		foreach ($_POST["poll_responses"] as $value) {
?>
				<div class="input-field">
					<input type="text" name="poll_responses[]" placeholder="Saisissez une réponse" value="<?=is_string($value) ? htmlspecialchars($value) : ""?>">
					<label for="poll_title">
						Saisissez une réponse
					</label>
				</div>
<?php
		}
	} else {
?>
				<div class="input-field">
					<input type="text" name="poll_responses[]" placeholder="Saisissez une réponse">
					<label for="poll_title">
						Saisissez une réponse
					</label>
				</div>
				<div class="input-field">
					<input type="text" name="poll_responses[]" placeholder="Saisissez une réponse">
					<label for="poll_title">
						Saisissez une réponse
					</label>
				</div>
<?php
	}
?>
			</div><br><br>
			
			<div class="input-field">
				<input type="text" name="title" id="title" placeholder="Titre du sujet" value="<?=isset($_POST["title"]) && is_string($_POST["title"]) ? htmlspecialchars($_POST["title"]) : ""?>">
				<label for="title">
					Titre du sujet
				</label>
			</div>
			<div class="input-field"><br>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[b]', '[/b]')"><i class="material-icons">format_bold</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[i]', '[/i]')"><i class="material-icons">format_italic</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[s]', '[/s]')"><i class="material-icons">indeterminate_check_box</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[u]', '[/u]')"><i class="material-icons">highlight</i></button>
				<button type="button" class="btn btn-small" onclick="addBetweenSelectedText('content', '[spoilers]', '[/spoilers]')"><i class="material-icons">remove_red_eye</i></button>
				<button type="button" class="btn btn-small modal-trigger" href="#modal1"><i class="material-icons">image</i></button>
				
				
				
				<textarea name="content" id="content" class="materialize-textarea" placeholder="Contenu de votre sujet"><?=isset($_POST["content"]) && is_string($_POST["content"]) ? htmlspecialchars($_POST["content"]) : ""?></textarea>
				<label for="content">Contenu de votre sujet</label>
			</div>
			<div class="input-field">
				<?=Captcha::generate()?>
			</div>
			<div class="input-field">
				<button type="submit" class="btn green waves-effect waves-light">Valider</button>
				<button type="button" class="btn waves-effect waves-light">Prévisualiser</button>
				<button type="button" class="btn waves-effect waves-light" onclick="document.getElementById('poll').style.display = ''">Ajouter un sondage</button>
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
			Vous devez être connecté afin de créer un nouveau sujet.
		</div>
	</div>
<?php
}
?>
</form>

<?php
require "Pages/Layout/End.php";