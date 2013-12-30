<table class="table table-hover">
<colgroup>
	<col style="width:14%">
	<col>
	<col style="width:25%">
</colgroup>
<thead>
	<tr>
		<th>Rank</th>
		<th>Team</th>
		<th>Score</th>
	</tr>
</thead>
<tbody>
	{if $teamList === null}
		<tr><td colspan="3" style="text-align:center">팀이 없습니다</td></tr>
	{else}
		{foreach $teamList as $idx => $team}
		{$rank = 1 + $idx}
		<tr>
			<td>{$rank}</td>
			<td>{$team->name}</td>
			<td>{$team->score}</td>
		</tr>
		{/foreach}
	{/if}
</tbody>
</table>