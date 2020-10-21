//Check input from zipcode without submit in JS
function checkzipcode() {
	var zipinput = document.forms["users.php"]["zipcode"].value.trim();

	var regex = new RegExp('^([0-9]){4}$');
	var result = regex.test(zipinput); 

	if (result) {
		document.getElementById("usercreation-error").innerHTML = '';
		document.getElementById("createuser").disabled = false; 
		return true;
	} else {
		document.getElementById("usercreation-error").innerHTML = 'Geben Sie eine valide Postleitzahl in Österreich an';
		document.getElementById("createuser").disabled = true; 
		return false;
	}
}

// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});