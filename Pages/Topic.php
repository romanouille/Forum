<?php
require "Pages/Layout/Start.php";
?>
<h4 class="center">Sujet : « lol mdr xd »</h4>

<a href="#" title="Répondre" class="btn blue waves-effect waves-light">Répondre</a>
<a href="#" title="Liste des sujets" class="btn blue waves-effect waves-light">Liste des sujets</a>
<a href="#" title="Suivre" class="btn blue waves-effect waves-light">Suivre</a>

<div class="right">
	<a href="#" class="btn orange darken-3 waves-effect waves-light dropdown-trigger" data-target="mod">Modération</a>
</div>

<ul class="pagination center">
	<li class="disabled"><a href="#" title="Page précédente"><i class="material-icons">chevron_left</i></a></li>
	<li class="active"><a href="#" title="Page 1">1</a></li>
	<li class="waves-effect"><a href="#" title="Page 2">2</a></li>
	<li class="waves-effect"><a href="#" title="Page suivante"><i class="material-icons">chevron_right</i></a></li>
</ul>


<?php
foreach ($topicMessages as $topicMessage) {
	$userMessage = new User($topicMessage["author"]);
	$userData = $userMessage->getData();
?>
<div class="card light-blue lighten-5 message" id="message_<?=$topicMessage["id"]?>">
	<div class="card-content">
		<div class="row">
			<div class="col l2 s4">
				<img src="<?=$staticServer?>/img/Avatar.png" alt="" title="Avatar">
				<p>
					Rang : <b>Rubis</b><br>
					Messages : <b><?=$userData["messages"]?></b><br>
					Points : <b><?=$userData["points"]?></b>
				</p>
			</div>
			<div class="col l10 s8">
				<b class="username"><?=$topicMessage["username"]?></b>
				<div class="right">
					<a href="#" class="btn-floating waves-effect waves-light green" title="Citer le message"><i class="material-icons">format_quote</i></a>
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
					<?=htmlspecialchars($topicMessage["message"])?>
				</div>
		</div>
	</div>
</div>

<?php
}
?>


<form method="post">
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