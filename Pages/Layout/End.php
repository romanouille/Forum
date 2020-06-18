
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
			SiteInternet.org 2020<br>Version 1.0
		</footer>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
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
	document.getElementsByName("message")[0].value += "[quote:"+messageId+"]\n"
}

function getSelectionText() {
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    return text;
}

function addBetweenSelectedText(el, start, end) {
	let text = getSelectionText();
	
	document.getElementById(el).value += start + text + end;
}

function pollAddOption() {
	document.getElementById("pollOptions").innerHTML += '							<div class="input-field">\
								<input type="text" class="validate" name="pollResponse[]" placeholder="Option">\
							</div>';
}

		</script>
	</body>
</html>