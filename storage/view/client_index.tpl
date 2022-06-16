{extends file="layout.tpl"}
{block name="main"}
  <div>{$title}</div>
  <div>
      {if $username eq ""}
        您尚未登录
      {else}
        您好, {$username}!
        <br>
        <a href="logout">点击退出登录</a>
      {/if}
  </div>
  <a href="auth_page">此操作需要先授权</a>
{/block}
