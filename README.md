Tags Plugin for Morfy CMS
=========================

Configuration
-------------

1. Put the `tags` folder to the `plugins` folder
2. Go to `config\site.yml` and add `tags` to the plugins section:
3. Save your changes.

~~~ .yml
# Site Plugins
plugins:
  tags
~~~

Usage
-----

Replace your posts loop in `blog.tpl` and/or `index.tpl` with this:

~~~ .no-highlight
{if $.site.tag}
  {Morfy::runAction('tags')}
{else}
  {* normal posts loop goes here... *}
{/if}
~~~

If you have installed the [`nextprev`](https://github.com/tovic/nextprev-plugin-for-morfy-cms "Next/Previous Navigation (Pagination) Plugin for Morfy CMS") plugin, you can use this snippet:

~~~ .no-highlight
{if $.site.tag}
  {Morfy::runAction('tags')}
{else}
  {Morfy::runAction('nextprev')}
{/if}
~~~

Add a tag widget in `blog_post.tpl` like this:

~~~ .no-highlight
<div class="widget">
  <h4>Tags</h4>
  <p>{Morfy::runAction('tags.widget')}</p>
</div>
~~~

Done.

### Added New Global Variable

`$.site.tag` will return the current page tag.

This is basically equal to `$.get.tag`. But since the `tag` parameter URL is dynamic, you cannot use the `$.get.tag` variable safely. Because if you change the `param.tag` configuration value to `foo` for example, then you have to replace `$.get.tag` with `$.get.foo`.