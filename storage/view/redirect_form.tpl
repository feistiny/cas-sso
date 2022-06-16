{extends file="layout.tpl"}
{block name="main"}
  <form name="submitForm" action="{$url}" method="post">
      {foreach from=$data key=k item=v}
          <input type="hidden" name="{$k}" value="{$v}">
      {/foreach}
  </form>
  <script>window.document.submitForm.submit();</script>
{/block}
