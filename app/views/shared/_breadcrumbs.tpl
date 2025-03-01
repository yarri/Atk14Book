{if sizeof($breadcrumbs)>=2}
<ol class="breadcrumb hidden-print">
	{foreach $breadcrumbs as $breadcrumb}
			{if $breadcrumb->getUrl() && !$breadcrumb@last}
			<li class="breadcrumb-item">
				<a href="{$breadcrumb->getUrl()}">{$breadcrumb->getTitle()}</a>
			</li>
			{else}
			<li class="breadcrumb-item active">
				{$breadcrumb->getTitle()}
			</li>
			{/if}
	{/foreach}
</ol>
{/if}
