{inc "css/challenge_4dm1n.css"}
<script>
$(function(){
	$("#btn-submit").bind("click", function(e){
		$("form").submit();
	});

	$(".chal-add").bind("click", function(e){
		$("input[name=doAction]").val("doAddChallenge");
		$("#btn-submit").html("Add");
		$(".modal-header > h3").html("New Challenge");

		$("input[name=title]").val("");
		$("textarea[name=desc]").html("");
		$("input[name=score]").val("");
		$("select[name=type]").val(0);
	});

	$(".chal-update").bind("click", function(e){
		var chal = $(this).parent().parent();
		var _idx = chal.attr("idx");

		$.post("{$__root}{$__module}",
		{ doAction: "doAjaxAdminGetChallenge", idx: _idx },
		function(data){
			if (data.code === 0)
			{
				$("input[name=title]").val(data.chal.title);
				$("textarea[name=desc]").html(data.chal.desc);
				$("input[name=score]").val(data.chal.score);
				$("select[name=type]").val(data.chal.type);
			}
		}, "json");
		
		$("input[name=doAction]").val("doUpdateChallenge");
		$("input[name=idx]").val(_idx);
		$("#btn-submit").html("Update");
		$(".modal-header > h3").html("Update Challenge");
		$("#new-chal").modal();

		//$("input[name=doAction]").val(action);
		//$("#btn-submit").html(btn_caption);
	});

	$(".chal-toggle").bind("click", function(e){
		var chal = $(this).parent().parent();
		var _idx = chal.attr("idx");
		var reqMode = chal.children("td[name=open]").children().hasClass("label-success") ? "close" : "open";
		$.post("{$__root}{$__module}",
		{ doAction: "doAjaxToggleOpen", idx: _idx, mode: reqMode },
		function(data){
			if (data.code === 0)
				location.href = "{$__root}{$__module}/{$__action}";
		}, "json");
	});

	
});
</script>
<table class="table">
<colgroup>
	<col style="width:30px">
	<col style="width:50px">
	<col style="width:100px">
	<col>
	<col style="width:90px">
	<col style="width:100px">
	<col style="width:100px">
</colgroup>
<thead>
	<tr>
		<th></th>
		<th>No</th>
		<th>Type</th>
		<th>Title</th>
		<th>Score</th>
		<th>Status</th>
		<th>Manage</th>
	</tr>
</thead>
<tbody>
	{if $chalList === null}
		<tr>
			<td colspan="7" style="text-align:center">No challenge</td>
		</tr>
	{else}
		{foreach $chalList as $chal}
		<tr idx="{$chal->idx}">
			<td><button class="close pull-left">&times;</button></td>
			<td>{$chal->idx}</td>
			<td>{$chalType[$chal->type]}</td>
			<td>{$chal->title}</td>
			<td>{$chal->score}</td>
			<td name="open">
			{if $chal->open === 'Y'}
			<span class="label label-success">Open</span>
			{else}
			<span class="label">Closed</span>
			{/if}
			</td>
			<td>
			<button class="btn btn-mini btn-info chal-update"><i class="icon-wrench icon-white"></i></button>&nbsp;
			{if $chal->open === 'Y'}
			<button class="btn btn-mini btn-danger chal-toggle"><i class="icon-off icon-white"></i></button>
			{else}
			<button class="btn btn-mini btn-success chal-toggle"><i class="icon-off icon-white"></i></button>
			{/if}
			</td>
		</tr>
		{/foreach}
	{/if}
</tbody>
</table>

<div class="pull-left">
	<a href="#new-chal" class="btn btn-primary chal-add" data-toggle="modal" data-backdrop="false">Add Challenge</a>
</div>
<div class="pagination pagination-right">
	<ul>
	{$i = $pageStart}
	{$end = $pageEnd}
	{while($i <= $end)}
		<li{if $page == $i} class="active"{/if}><a href="{$__root}{$__module}/{$__action}?page={$i}">{$i}</a></li>
		{$i = $i + 1}
	{/while}
	</ul>
</div>
<div id="new-chal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>New Challenge</h3>
	</div>
	<div class="modal-body">
			<form action="{$__root}{$__module}/{$__action}" method="post">
			<input type="hidden" name="doAction" value="doAddChallenge">
			<input type="hidden" name="idx" value="">
			<input type="text" class="input-block-level" name="title" placeholder="Title">
			<textarea name="desc" class="input-block-level" style="height:200px" placeholder="Description (HTML allowed)"></textarea>
			<div class="row-fluid">
			<div class="span6">
			<input type="text" class="input-block-level" name="score" placeholder="Score">
			</div>
			<div class="span6">
			<select name="type" class="input-block-level">
			{foreach $chalType as $key => $val}
			<option value="{$key}">{$val}</option>
			{/foreach}
			</select>
			</div>
			</div>
			<input type="text" class="input-block-level" name="flag" placeholder="Answer">
			</form>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btn-submit">Add</button>
	</div>
</div>
