{extends file="layout.tpl"}
{block name="main"}
  <div>cas的登录页面</div>
  <form action="/server/cas_auth" method="post">
    <div>
      用户名<input type="text" name="username">
    </div>
    <div>
      密码<input type="text" name="password">
    </div>
    <button type="submit">注册/登录</button>
  </form>
{/block}
