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
					$("#modify-msg").addClass("alert-error").html(data.msg).show();
			}
			else
				location.href = "{$__root}";
		}, "json");
	});
});
</script>
<div class="box">
	<form action="{$__root}" method="post">
	<input type="hidden" name="doAction" value="doAjaxModifyInfo">
	<h3>정보 수정</h3>
	<hr>
	<div id="modify-msg" style="display:none;" class="alert"></div>
	<span class="help-block" style="margin-bottom:17px">{$__sess->id}</span>
	<input type="text" name="name" class="input-block-level" placeholder="Name" value="{$__sess->name}" style="margin-bottom:17px">
	<input type="password" name="oldpw" class="input-block-level" placeholder="Old password" style="margin-bottom:17px">
	<input type="password" name="newpw" class="input-block-level" placeholder="New password" style="margin-bottom:17px">
	<input type="password" name="newpw2" class="input-block-level" placeholder="Confirm" style="margin-bottom:17px">
	<button type="submit" class="btn btn-primary" style="margin-right:10px">Modify</button>
	</form>
</div>