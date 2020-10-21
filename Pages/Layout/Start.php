<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?=implode(" - ", array_reverse($breadcrumb))?> - Avenoel</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<style>
#right img,
hr {
	display: block
}

* {
	font-family: "Roboto Condensed";
	overflow-wrap: break-word
}

body,
html {
	height: 100%;
	margin: 0
}

main {
	min-height: 100%
}

body {
	background: grey
}

.nav-logo img {
	margin-top:10px
}

@media (max-width:992px) {
	main {
		width: 100%!important;
		left: 0
	}
	#left,
	#right {
		padding: 0!important;
		margin-bottom: 30px
	}
	#left .block {
		padding: 10px
	}
}

header nav {
	height: 70px;
	border-bottom: 4px solid #3653A3
}

header .nav-wrapper li {
	text-align: center;
	text-transform: uppercase;
	font-weight: 700
}

header .nav-wrapper li a {
	font-size: 17px
}

header .nav-logo {
	width: auto!important;
	height:67px
}

header .mobile-logo {
	margin-top:10px
}

.blocks {
	margin-top: 30px
}

.block {
	background: #F5F5F5;
	border-radius: 3px;
	min-height: 200px
}

#right-links {
	font-size: 15px
}

#right-links td:before {
	content: "> ";
	color: orange
}

#right img {
	width: 80%;
	margin: 0 auto
}

#right .block {
	padding-top: 10px
}

#left .block {
	padding: 15px
}

#breadcrumb,
input[type=text] {
	margin: 0
}

#breadcrumb li {
	display: inline-block;
	color: #777;
	font-size: 12px
}

#breadcrumb li+li:before {
	font-family: "Material Icons";
	content: "\E5CC";
	color: #CCC;
	font-size: 12px
}

hr {
	height: 1px;
	border: 0;
	border-top: 1px solid #D3D3D3;
	padding: 0
}

.container {
	width:100%
}

#search-form a,
#search-form button {
	margin-top: 7px
}

.topics-list-pagination a {
	color: inherit
}

#topics-list #topic-title {
	width: 40%
}

#topics-list th {
	background: #E8E8E8;
	font-size: 13px
}

#topics-list td,
#topics-list th {
	height: 30px;
	padding: 0!important
}

#topics-list #topic-icon,
#topics-list #topic-mod {
	width: 5%
}

#topics-list #topics-title {
	width: 60%
}

#topics-list #topic-author,
#topics-list #topic-last,
#topics-list #topic-nb {
	width: 10%
}

#topics-list tr:nth-of-type(odd) {
	background: #F9F9F9
}

#topics-list tr {
	border-bottom: none
}

footer {
	text-align: center;
	border-top: 4px solid #3653A3;
	padding: 20px;
	color: #fff
}

img {
	max-width: 100%
}

.message {
	margin-top: 20px
}

.message .username {
	font-size: 17px
}

.message .permalink {
	font-size: 13px
}

textarea {
	height:150px !important
}
		</style>
	</head>
	
	<body>
		<header>
			<nav class="nav-wrapper grey darken-4">
				<a href="#" data-target="mobile" class="sidenav-trigger">
					<i class="material-icons">menu</i>
				</a>
				
				<a href="/" title="Accueil" class="sidenav-trigger mobile-logo"><img src="<?=$staticServer?>/img/Logo.png" alt="Avenoel" title="Logo d'Avenoel">

				<div class="hide-on-med-and-down container">
					<ul>
						<li class="nav-logo"><a href="/" title="Accueil"><img src="<?=$staticServer?>/img/Logo.png" alt="Avenoel" title="Logo d'Avenoel"></a>
						<li><a href="/" title="Accueil">Accueil</a>
						<li><a href="/forums/blabla/1" title="Blabla général">Forum</a>
						<li><a href="/articles" title="Articles">Articles</a>
					</ul>
					
					<ul class="right">
<?php
if ($_SESSION["logged"]) {
?>
						<li><a href="/account/<?=$_SESSION["username"]?>" title="Profil de <?=$_SESSION["username"]?>"><?=$_SESSION["username"]?></a>
						<li><a href="/account/logout?hash=<?=$hash?>" title="Déconnexion">Déconnexion</a>
<?php
} else {
?>
						<li><a href="/account/login" title="Connexion">Connexion</a>
						<li><a href="/account/register" title="Inscription">Inscription</a>
<?php
}
?>
					</ul>
				</div>
				
				<ul class="sidenav" id="mobile">
					<li><a href="/" title="Accueil">Accueil</a>
					<li><a href="/forum" title="Blabla général">Forum</a>
					<li><a href="/account/login" title="Connexion">Connexion</a>
					<li><a href="/account/register" title="Inscription">Inscription</a>
				</ul>
			</nav>
		</header>
		
		<main class="container">
			<div class="section blocks">
				<div class="row">
					<div class="col l9 m12 s12" id="left">
						<div class="block">
						
							<ul id="breadcrumb">
								<li><a href="/" title="Accueil">Avenoel</a>
<?php
foreach ($breadcrumb as $value) {
?>
								<li><?=$value?>
<?php
}
?>
							</ul>
							
							<hr>
