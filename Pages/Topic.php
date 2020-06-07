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

<div class="card light-blue lighten-5 message">
	<div class="card-content">
		<div class="row">
			<div class="col l2 s4">
				<img src="<?=$staticServer?>/img/Avatar.png" alt="" title="Avatar">
				<p>
					Rang : <b>Rubis</b><br>
					Messages : <b>1234</b><br>
					Points : <b>1234</b>
				</p>
			</div>
			<div class="col l10 s8">
				<b class="username">edwado</b>
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
				<a href="#" class="permalink" title="Lien permanent">Posté le 19/01/2019 à 17:49:00</a>
				<hr>
					:noel:				
			</div>
		</div>
	</div>
</div>
<?php
require "Pages/Layout/End.php";