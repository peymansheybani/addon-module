<ul class="nav nav-tabs admin-tabs" role="tablist">
    {foreach from=$data item=item}
            <li><a href="{$link}&action={$item[1]}" role="tab"><i class=""></i> <i class=""></i>{$item[0]}</a></li>
    {/foreach}
    {if $showPerms}
    <li><a href="{$link}&action=admin/permission" role="tab"><i class=""></i> <i class=""></i>دسترسی کاربران</a></li>
    {/if}
</ul>
