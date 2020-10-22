
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
		</script>
	</body>
</html>