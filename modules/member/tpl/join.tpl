{inc "css/member.css"}
<script>
$(function(){
	$("input[name=team]").bind("focusout", function(e){
		var _code = $(this).val();
		if (_code == "")
		{
			$("#team-help").html("");
			return;
		}

		$.post("{$__root}team",
		{ doAction: "doAjaxGetTeam", code: _code },
		function (data){
			if (data.code == -1)
				$("#team-help").html(data.msg);
			else if (data.code == 1)
				$("#team-help").html("가입할 팀은 '" + data.team_name + "' 입니다.");
			else
				$("#team-help").html("새로운 팀: '" + data.team_name + "'");
		}, "json");
	});

	$("form").submit(function(e){
		e.preventDefault();
		$.post("{$__root}{$__module}",
		$(this).serialize(),
		function (data){
			if (data.code !== 0)
			{
				$("input[name=" + data.tag + "]").focus();
				if (data.msg)
					$("#join-msg").addClass("alert-error").html(data.msg).show();
			}
			else
				location.href = "{$__root}{$__module}";
		}, "json");
	});
});
</script>
<div class="box">
	<form action="{$__root}{$__module}" method="post">
	<input type="hidden" name="doAction" value="doAjaxJoin">
	<h3>Join</h3>
	<hr>
	<div id="join-msg" style="display:none;" class="alert">
	</div>
	<input type="text" class="input-block-level" name="userid" placeholder="User ID" style="margin-bottom:17px">
	<input type="password" class="input-block-level" name="userpw" placeholder="Password" style="margin-bottom:17px">
	<input type="password" class="input-block-level" name="userpw2" placeholder="Password Confirm" style="margin-bottom:17px">
	<input type="text" class="input-block-level" name="name" placeholder="Real Name" style="margin-bottom:17px">
	<input type="text" class="input-block-level" name="team" placeholder="Team Code or New Team Name">
	<span class="help-block" id="team-help" style="font-size:9pt;"></span>
	<input type="submit" class="btn btn-success" value="Go">
	</form>
</div>