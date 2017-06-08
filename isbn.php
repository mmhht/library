<?php
include_once ("/view/partial/_header");
?>
<div class="container-fluid margin-b-50">
	<div class="row">
		<div class="col-md-6">
			<form method="POST" action="result_table.php" class="form-group">
				<legend>
					ISBN
				</legend>
				<div class="col-sm-8">
					<input type="text" name="isbn" class="form-control">
				</div>
				<div class="col-sm-4">
					<button type="submit" class="btn btn-primary btn-sm">
						検索
					</button>
				</div>
			</form>
		</div>
		<div class="col-md-6">
			<form method="POST" action="result_table.php" class="form-group">
				<legend>
					キーワード
				</legend>
				<div class="col-sm-8">
					<input type="text" name="keyword" class="form-control">
				</div>
				<div class="col-sm-4">
					<button type="submit" class="btn btn-primary btn-sm">
						検索
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php
include_once ("/view/partial/_footer");
?>