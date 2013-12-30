{inc "css/team.css"}
<div class="box">
<h4 class="text-center" style="margin-bottom:30px">팀 정보</h4>
<table class="table table-bordered table-hover">
<colgroup>
	<col style="width:20%">
</colgroup>
<tbody>
<tr>
	<td>Team</td>
	<td>{$__sess->t_name}</td>
</tr>
<tr>
	<td>Score</td>
	<td>{$__sess->score}</td>
</tr>
<tr>
	<td>Member</td>
	<td>{$__sess->name}</td>
</tr>
<tr>
	<td>Code</td>
	<td>{$__sess->code}</td>
</tr>
</tbody>
</table>
</div>