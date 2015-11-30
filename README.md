Tags Plugin for Morfy CMS
=========================

Configuration
-------------

1. Put the `tags` folder to the `plugins` folder
2. Go to `config\system.yml` and add `tags` to the plugins section:
3. Save your changes.

~~~ .yml
plugins:
  tags
~~~

Usage
-----

Replace your posts loop in `blog.tpl` and/or `index.tpl` with this:

~~~ .no-highlight
{if $config.site.tag}
  {Action::run('tags')}
{else}
  {* normal posts loop goes here... *}
{/if}
~~~

If you have installed the [`nextprev`](https://github.com/tovic/nextprev-plugin-for-morfy-cms "Next/Previous Navigation (Pagination) Plugin for Morfy CMS") plugin, you can use this snippet:

~~~ .no-highlight
{if $config.site.tag}
  {Action::run('tags')}
{else}
  {Action::run('nextprev')}
{/if}
~~~

Add a tag widget in `blog_post.tpl` like this:

~~~ .no-highlight
<div class="widget">
  <h4>Tags</h4>
  <p>{Action::run('tags.widget')}</p>
</div>
~~~

Done.

New Global Variable
-------------------

`$config.site.tag` will return the current page tag.

This is basically equal to `$.get.tag` and `$_GET['tag']`. But since the `tag` parameter URL is dynamic, you cannot use the `$.get.tag` and `$_GET['tag']` variable safely. Because if you replace the `param.tag` configuration value with `foo` for example, then you have to replace `$.get.tag` with `$.get.foo` and `$_GET['tag']` with `$_GET['foo']`.