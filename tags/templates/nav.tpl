<ul class="pager">
  {if $prev}
    <li class="previous">
      <a href="{$prev}">{$config.plugins.tags.labels.nav.prev}</a>
    </li>
  {else}
    <li class="previous disabled">
      <span>{$config.plugins.tags.labels.nav.prev}</span>
    </li>
  {/if}
  {if $next}
    <li class="next">
      <a href="{$next}">{$config.plugins.tags.labels.nav.next}</a>
    </li>
  {else}
    <li class="next disabled">
      <span>{$config.plugins.tags.labels.nav.next}</span>
    </li>
  {/if}
</ul>