{inc "css/member.css"}
<div class="box">
	<form action="{$__root}{$__module}" method="post">
	<input type="hidden" name="doAction" value="doLogout">
	<input type="hidden" name="retAction" value="viewLogin">
	<h3>Logout</h3>
	<hr>
	<button type="submit" class="btn btn-danger" style="margin-right:10px">Logout</button>
	<a href="{$__root}" class="btn">Cancel</a>
	</form>
</div>