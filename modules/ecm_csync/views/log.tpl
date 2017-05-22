<div class="alert alert-info">
{foreach from=$log item=log_string}
	{$log_string|escape:'htmlall':'UTF-8'}<br>
{/foreach}
</div>