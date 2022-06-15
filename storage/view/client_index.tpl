{extends file="layout.tpl"}
{block name="main"}
  <div>{$title}</div>
  <a href="auth_page?service_id={$service_id}&redirect_url={$redirect_url}">点此授权</a>
{/block}
