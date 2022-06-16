{extends file="layout.tpl"}
{block name="main"}
  <div>{$title}</div>
  <div>
      {if $username eq ""}
          您尚未登录
      {else}
          您好, {$username}!
      {/if}
  </div>
  <a href="auth_page?service_id={$service_id}&redirect_url={$redirect_url}">此操作需要先授权</a>
{/block}
