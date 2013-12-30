<table class="table table-striped">
<colgroup>
	<col style="width:70px">
	<col style="width:250px">
	<col>
	<col style="width:100px">
	<col style="width:100px">
</colgroup>
<thead>
	<tr>
		<th>No</th>
		<th>Team</th>
		<th>Team Member</th>
		<th>Score</th>
	</tr>
</thead>
<tbody>
	{if $teamList === null}
		<tr>
			<td colspan="4" style="text-align:center">No team found</p></td>
		</tr>
	{else}
		{foreach $teamList as $team}
		<tr idx="{$team->idx}">
			<td>{$team->idx}</td>
			<td>{$team->name}</td>
			<td>
			{foreach $team->members as $m}
				{$m->name}({$m->id}), 
			{/foreach}
			</td>
			<td>{$team->score}</td>
		</tr>
		{/foreach}
	{/if}
</tbody>
</table>