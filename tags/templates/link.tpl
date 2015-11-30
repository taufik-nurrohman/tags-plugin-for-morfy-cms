{if $tags}
  {foreach $tags as $tag => $url}
    <a class="btn btn-info btn-xs" href="{$url}" rel="tag">{$tag}</a>
  {/foreach}
{else}
  {$config.plugins.tags.labels.not_found.tag}
{/if}