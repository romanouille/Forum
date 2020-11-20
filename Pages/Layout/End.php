
						</div>
					</div>
					
					<div class="col l3 m12 s12" id="right">
						<div class="block">
							<table class="container" id="right-links">
								<tbody>
									<tr>
										<td>Blabla
										<td>Modération
									
									<tr>
										<td>Avenoel Radio
										<td>Statistiques
									
									<tr>
										<td>API
										<td>Faire un don
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</main>
		
		<footer class="grey darken-4">
			&copy; Avenoel<br>
		</footer>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
var token = "<?=$hash?>";
document.addEventListener("DOMContentLoaded", function() {
	var elems = document.querySelectorAll("select");
	var instantes = M.FormSelect.init(elems);
	
	var elems = document.querySelectorAll(".sidenav");
	var instances = M.Sidenav.init(elems);
});

document.addEventListener("DOMContentLoaded", function() {
	var elems = document.querySelectorAll(".modal");
	var instances = M.Modal.init(elems);
});

document.addEventListener("DOMContentLoaded", function() {
	var elems = document.querySelectorAll(".dropdown-trigger");
	var instances = M.Dropdown.init(elems);
});

function quoteMessage(messageId) {
	document.getElementsByTagName("textarea")[0].value += "[quote:"+messageId+"]\n"
}

function addBetweenSelectedText(elementID, openTag, closeTag) {
    var textArea = $('#' + elementID);
    var len = textArea.val().length;
    var start = textArea[0].selectionStart;
    var end = textArea[0].selectionEnd;
    var selectedText = textArea.val().substring(start, end);
    var replacement = openTag + selectedText + closeTag;
    textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
}

function pollNewResponse() {
	document.getElementById("poll").innerHTML += '<div class="input-field">\
					<input type="text" name="poll_responses[]" placeholder="Saisissez une réponse">\
				</div>';
}

function searchSticker(tags) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "/api/stickers/search");
	
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
			let data = JSON.parse(xhr.responseText);
			
			document.getElementById("stickers").innerHTML = "";
			for (i = 0; i <= data.length-1; i++) {
				document.getElementById("stickers").innerHTML += '<div class="col s2 modal-close" onclick="document.getElementsByTagName(\'textarea\')[0].value += \'[sticker:'+data[i]["id"]+']\'">\
					<img src="<?=$staticServer?>/img/stickers/'+data[i]["id"]+'.'+data[i]["ext"]+'">\
				</div>';
			}
		}
	}
	xhr.send("tags="+tags);
}

function kickUser(messageId) {
	let duration = prompt("Durée du kick en minutes ?");
	let reason = prompt("Motif du kick ?");
	
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "/api/moderation/kick");
	
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if (this.readyState == XMLHttpRequest.DONE) {
			if (this.status == 200) {
				alert("L'utilisateur a été kické.");
			} else {
				alert("Une erreur est survenue durant le kick de l'utilisateur.");
			}
		}
	}
	
	xhr.send("messageId="+messageId+"&duration="+duration+"&reason="+reason+"&token="+token);
}

function unkickUser(userId, forumId) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "/api/moderation/unkick");
	
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if (this.readyState == XMLHttpRequest.DONE) {
			if (this.status == 200) {
				alert("L'utilisateur a été dékické.");
			} else {
				alert("Une erreur est survenue durant le dékickage de l'utilisateur.");
			}
		}
	}
	
	xhr.send("userId="+userId+"&forumId="+forumId+"&token="+token);
}

function deleteMessage(messageId) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "/api/moderation/messages/delete");
	
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if (this.readyState == XMLHttpRequest.DONE) {
			if (this.status == 200) {
				alert("Le message a été supprimé.");
			} else {
				alert("Une erreur est survenue durant la suppression du message.");
			}
		}
	}
	
	xhr.send("messageId="+messageId+"&token="+token);
}

function restoreMessage(messageId) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "/api/moderation/messages/restore");
	
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if (this.readyState == XMLHttpRequest.DONE) {
			if (this.status == 200) {
				alert("Le message a été restauré.");
			} else {
				alert("Une erreur est survenue durant la restauration du message.");
			}
		}
	}
	
	xhr.send("messageId="+messageId+"&token="+token);
}

function deleteTopic(forumId, topicId) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "/api/moderation/topics/delete");
	
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if (this.readyState == XMLHttpRequest.DONE) {
			if (this.status == 200) {
				alert("Le sujet a été supprimé.");
			} else {
				alert("Une erreur est survenue durant la suppression du sujet.");
			}
		}
	}
	
	xhr.send("forumId="+forumId+"&topicId="+topicId+"&token="+token);
}
		</script>
	</body>
</html>