{inc "css/challenge.css"}
{inc "js/challenge.js"}
<div class="row-fluid">
	<div class="span8">
		{if $chalList === null}
			<h3 class="text-center">No challenge is open yet</h3>
		{else}
			{$i = 0}
			{foreach $chalList as $chal}
			{$solved = preg_split("@/@", $chal->solver, 0, PREG_SPLIT_NO_EMPTY)}
			{$solved_count = count($solved)}
			{$t_idx = $__sess->t_idx}
			{if (in_array($t_idx, $solved)) === true}
				{$class = "chal solved"}
			{else}
				{if $solved_count > 0}
					{$class = "chal taken"}
				{else}
					{$class = "chal"}
				{/if}
			{/if}
			{if ($i++ % 4) == 0}
			<div class="row-fluid row-chal">
			{/if}
			<div class="span3">
				<div class="{$class}" idx="{$chal->idx}">
				<div class="type"><span>{$chalType[$chal->type]}</span></div>
				<div class="score"><span>{$chal->score}</span></div>
				<div class="status"><span>{$solved_count}/{$teamCount}</span></div>
				</div>
			</div>
			{if ($i % 4) == 0}
			</div>
			{/if}
			{/foreach}
			{if ($i % 4) != 0}
			</div>
			{/if}
		{/if}
	</div>
	<div class="span4">
		<div class="well">
			<h4 id="chal-title">KUICS CTF 2013</h4>
			<hr>
			<div id="chal-desc"><p style="word-wrap:break-word">Choose a challenge</p></div>
			<div class="auth">
				<form action="" method="post">
				<input type="hidden" name="doAction" value="doAjaxAuth">
				<input type="hidden" name="idx" value="">
				<div class="breakthrough text-center">
				<h6>Breakthrough</h6>
				</div>
				<div class="input-append">
					<input type="text" name="flag" placeholder="Flag here">
					<button class="btn btn-primary">Auth</button>
				</div>
				</form>
				<div class="alert hide" id="chal-auth-result"></div>
			</div>
		</div>
	</div>
</div>
