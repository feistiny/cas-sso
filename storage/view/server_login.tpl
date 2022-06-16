{extends file="layout.tpl"}
{block name="main"}
  <div>cas的登录页面</div>
  <div>{$msg}</div>
  <form action="/server/cas_auth" method="post">
    <div>
      用户名<input type="text" name="username">
    </div>
    <div>
      密码<input type="text" name="password">
    </div>
    <input type="hidden" name="is_cas_login" value="1">
    <input type="hidden" name="redirect_url" value="{$redirect_url}">
    <input type="hidden" name="service_id" value="{$service_id}">
    <button type="submit">注册/登录</button>
  </form>
{/block}
