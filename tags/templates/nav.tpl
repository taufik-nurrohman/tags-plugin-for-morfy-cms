<ul class="pager">
  {if $prev}
    <li class="previous">
      <a href="{$prev}">{$config.labels.nav_prev}</a>
    </li>
  {else}
    <li class="previous disabled">
      <span>{$config.labels.nav_prev}</span>
    </li>
  {/if}
  {if $next}
    <li class="next">
      <a href="{$next}">{$config.labels.nav_next}</a>
    </li>
  {else}
    <li class="next disabled">
      <span>{$config.labels.nav_next}</span>
    </li>
  {/if}
</ul>