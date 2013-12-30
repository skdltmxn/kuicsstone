$(function(){
	$("div.chal").bind("click", function(e){
		$("#chal-auth-result").removeClass("alert-error").removeClass("alert-success").hide();
		$("input[name=flag]").val("");
		var _idx = $(this).attr("idx");
		$.post("?module=challenge",
		{ doAction: "doAjaxGetChallenge", idx: _idx},
		function(data){
			if (data.code === 0)
			{
				$("#chal-title").html(data.title + " (" + data.score + "pt)");
				$("#chal-desc > p").html(data.desc);
				$("div.auth > form > input[name=idx]").val(_idx);

				$("div.breakthrough").html("<h6>Breakthrough</h6>");
				data.brk.forEach(function(x){
					$("div.breakthrough").append("<span>" + x + "</span>");
				});
			}
		}, "json");
	});

	$("div.auth > form").bind("submit" ,function(e){
		e.preventDefault();
		$("#chal-auth-result").removeClass("alert-error").removeClass("alert-success").hide();

		$.post("?module=challenge",
		$(this).serialize(),
		function(data){
			if (data.code === 0)
				$("#chal-auth-result").addClass("alert-success").html(data.msg).show();
			else if (data.code === 1)
				$("input[name=" + data.tag + "]").focus();
			else if (data.code === 2)
				$("#chal-auth-result").addClass("alert-error").html(data.msg).show();
		}, "json");
	});
});