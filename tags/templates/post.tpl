<div class="page">
  {if $page.title}
    <h3><a href="{$page.url}">{$page.title}</a></h3>
  {/if}
  {if $page.date}
    <p><em><strong>Published on:</strong> {$page.date}</em></p>
  {/if}
  {if $page.description! && $page.description?}
    <p>{$page.description}</p>
  {else}
    {if $page.summary! && $page.summary?}
      {$page.summary}
    {else}
      {$page.content}
    {/if}
  {/if}
</div>