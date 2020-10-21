
<?php

$post_error = '';
if (isset($_POST['BUTTONNAME'])) {

	print_r($_POST);

	if (!isset($_POST['input1'])) {
		$post_error = 'Füllen Sie das Feld 1 aus!';
	} else {
		echo $_POST['input1'];
	}
}

?>




<form action="form.php" method="post">
	<div class="form-row">
		<div class="form-group col-md-4">
			<input type="text" name="input1" placeholder="XXX" id="XXX" required value="">
		</div>

		<div class="form-group col-md-4">
			<input type="text" name="input2" placeholder="XXX" id="XXX" required value="">
		</div>

		<div class="form-group col-md-4">
			<input type="text" name="input3" placeholder="XXX" id="XXX" required value="">
		</div>
	</div>

	<div class="form-row">
		<button type="submit" name="BUTTONNAME" class="btn btn-success btn-block">BUTTON</button>
	</div>
	<p><?php echo $post_error;?></p>
<hr>
</form>