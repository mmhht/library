<?php
include_once ("/view/partial/_header");
?>
<table class="table table-condensed table-bordered table-striped table-hover table-responsive">
<div class="container-fluid margin-b-40">
	<div class="row">
		<div class="col-xs-6">
			<form method="POST" action="result.php" class="form-group">
				<legend>
					ISBN
				</legend>
				<div class="col-xs-8">
					<input type="text" name="isbn" class="form-control">
				</div>
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-sm">
						検索
					</button>
				</div>
			</form>
		</div>
		<div class="col-xs-6">
			<form method="POST" action="result.php" class="form-group">
				<legend>
					キーワード
				</legend>
				<div class="col-xs-8">
					<input type="text" name="keyword" class="form-control">
				</div>
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-sm">
						検索
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
</table>
<?php
include_once ("/view/partial/_footer");
?>