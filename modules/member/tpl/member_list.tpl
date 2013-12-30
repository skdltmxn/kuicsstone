<table class="table">
<colgroup>
	<col style="width:60px">
	<col style="width:150px">
	<col style="width:150px">
	<col>
	<col style="width:190px">
</colgroup>
<thead>
	<tr>
		<th>No</th>
		<th>ID</th>
		<th>Name</th>
		<th>Team</th>
		<th>Regdate</th>
	</tr>
</thead>
<tbody>
	{foreach $memberList as $member}
	<tr>
		<td>{$member->idx}</td>
		<td>{$member->id}</td>
		<td>{$member->name}</td>
		{if $member->admin === 'Y'}
		<td><span class="label label-important">Admin</span></td>
		{else}
		<td>{$member->t_name}</td>
		{/if}
		<td>{date("Y-m-d H:i:s", $member->regdate)}</td>
	</tr>
	{/foreach}
</tbody>
</table>
<form action="{$__root}{$__module}/{$__action}" method="post" class="form-inline">
	<input type="hidden" name="doAction" value="doAddAdmin">
	<input type="text" name="userid" placeholder="User ID">&nbsp;
	<input type="password" name="userpw" placeholder="Password">&nbsp;
	<input type="text" name="name" placeholder="Name">&nbsp;
	<button type="submit" class="btn btn-primary">Add Admin</button>
</form>