{extends file="layout.tpl"}
{block name="main"}
    {foreach from=$infos key=k item=v}
        {$v}<br> 
    {/foreach}
{/block}
