<script>
	$(function(){
		$("select").bind("change", function(e){
			location.href = "{$__root}{$__module}/{$__action}?type=" + $(this).val();
		});
	});
</script>
<div>
<select name="type" class="input-medium">
	<option value="">------</option>
	<option value="0">All</option>
	{foreach $logType as $key => $val}
	<option value="{$key}">{$val}</option>
	{/foreach}
</select>
<form action="{$__root}{$__module}/{$__action}" method="post" style="display:inline">
<input type="hidden" name="doAction" value="doClearCache">
<button type="submit" class="btn btn-primary pull-right">Clear Cache</button>
</form>
</div>
<table class="table table-hover">
<colgroup>
	<col style="width:100px">
	<col>
	<col style="width:270px">
	<col style="width:180px">
</colgroup>
<thead>
	<tr>
		<th>Type</th>
		<th>Log</th>
		<th>Owner</th>
		<th>Regdate</th>
	</tr>
</thead>
<tbody>
	{if $logList === null}
		<td colspan="4">No log found</td>
	{else}
		{foreach $logList as $log}
		<tr>
			<td>{$logType[$log->type]}</td>
			<td>{$log->content}</td>
			<td>{$log->owner}</td>
			<td>{date("Y-m-d H:i:s", $log->regdate)}</td>
		</tr>
		{/foreach}
	{/if}
</tbody>
</table>
<div class="pagination pagination-centered">
	<ul>
	{$i = $pageStart}
	{$end = $pageEnd}
	{while($i <= $end)}
		<li{if $page == $i} class="active"{/if}><a href="{$__root}{$__module}/{$__action}?type={$type}&page={$i}">{$i}</a></li>
		{$i = $i + 1}
	{/while}
	</ul>
</div>