{inc "css/bs.css"}
{inc "css/bsr.css"}
{inc "css/common.css"}
{inc "js/jquery.js"}
{inc "js/bs.js"}
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<div class="nav-collapse collapse">
				<a href="{$__root}" class="brand">KUICS</a>
				<ul class="nav">
					<li {if $__action == "viewList"}class="active"{/if}><a href="{$__root}notice/viewList">공지사항</a></li>
					<li {if $__action == "viewRule"}class="active"{/if}><a href="{$__root}notice/viewRule">규칙</a></li>
					<li {if $__action == "viewChalList"}class="active"{/if}><a href="{$__root}challenge/viewChalList">전장터</a></li>
					<li {if $__action == "viewRank"}class="active"{/if}><a href="{$__root}team/viewRank">순위</a></li>
					
				</ul>
				<ul class="nav pull-right">
				{if $__sess->logged === true}
					{if $__sess->admin === 'Y'}
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">관리 <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="{$__root}challenge/viewAdminChallenge">문제</a></li>
								<li><a href="{$__root}team/viewAdminTeam">팀</a></li>
								<li><a href="{$__root}member/viewAdminMember">멤버</a></li>
								<li><a href="{$__root}logger/viewLog">로그</a></li>
							</ul>
						</li>
					{/if}
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$__sess->t_name}({$__sess->score}) <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="{$__root}team/viewTeamInfo">팀 정보</a></li>
							<li><a href="{$__root}member/viewMemberModify">정보 수정</a></li>
						</ul>
					</li>	
					<li class="divider-vertical"></li>
					<li><a href="{$__root}member/viewLogout">로그아웃</a></li>
				{else}
					<li {if $__action == "viewLogin"}class="active"{/if}><a href="{$__root}member/viewLogin">로그인</a></li>
				{/if}
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="container">
{content}
</div>
<div class="footer">
	<div class="container">
	<p class="text-center">This website is best viewed with <a href="https://www.google.com/chrome" target="_blank"><img src="img/chrome.png" style="width:32px"></a></p>
	<p class="text-center">Copyright &copy; 2013 KUICS</p>
	</div>
</div>
<script>
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
 ga('create', 'UA-39740393-3', 'tar.to');
 ga('send', 'pageview');
</script>