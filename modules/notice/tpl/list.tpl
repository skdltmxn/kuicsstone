{if $__sess->admin === 'Y'}
<script>
	$(function(){
		$("button.close").bind("click", function(e) {
			var _idx = $(this).attr("idx");
			var me = $(this).parent().parent();

			$.post("{$__root}{$__module}",
			{doAction: "doAjaxDelNotice", idx: _idx},
			function(data) {
				if (data == "1")
					me.remove();
			});
		});
	});
</script>
{/if}
<div style="width:100%;text-align:center;margin-bottom:50px;"><img src="img/logo3.png" alt="KuicsStone" style="margin-top:-20px;width:450px;"></div>
<table class="table">
	{if $noticeList != null}
		{foreach $noticeList as $n}
		<tr>
			<td style="text-align:center;">
				{if $__sess->admin === 'Y'}
				<button class="close pull-left" idx="{$n->idx}">&times;</button>
				{/if}
				{$n->notice}<!--<div class="pull-right">{date("Y-m-d H:i:s", $n->regdate)}</div>-->
			</td>
		</tr>
		{/foreach}
	{else}
		<tr>
			<td style="text-align:center;" colspan="2">No notice</td>
		</tr>
	{/if}
</table>
{if $__sess->admin === 'Y'}
<form action="{$__root}{$__module}" method="post">
<input type="hidden" name="doAction" value="doAddNotice">
<div class="input-append pull-right">
	<input type="text" name="notice" class="input-xxlarge" placeholder="New notice">
	<button type="submit" class="btn btn-primary">Add</button>
</div>
</form>
{/if}