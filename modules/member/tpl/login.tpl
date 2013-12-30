{inc "css/member.css"}
<script>
$(function(){
	$("form").submit(function(e){
		e.preventDefault();
		$.post("{$__root}{$__module}",
		$(this).serialize(),
		function (data){
			if (data.code !== 0)
			{
				$("input[name=" + data.tag + "]").focus();
				if (data.msg)
					$("#login-msg").addClass("alert-error").html(data.msg).show();
			}
			else
				location.href = "{$__root}";
		}, "json");
	});
});
</script>
<div class="box">
	<form action="{$__root}{$__module}" method="post">
	<input type="hidden" name="doAction" value="doAjaxLogin">
	<h3>Login</h3>
	<hr>
	<div id="login-msg" style="display:none;" class="alert">
	</div>
	<input type="text" class="input-block-level" name="userid" placeholder="User ID" style="margin-bottom:17px">
	<input type="password" class="input-block-level" name="userpw" placeholder="Password" style="margin-bottom:17px">
	<button type="submit" class="btn btn-success" style="margin-right:10px">Go</button>
	<a href="{$__root}member/viewJoin" class="btn btn-primary">Join</a>
	</form>
</div>