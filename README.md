Tags Plugin for Morfy CMS
=========================

Configuration
-------------

Place the `tags` folder with its contents in `plugins` folder. Then update your `config.php` file:

~~~ .php
<?php

    return array(

        ...
        ...
        ...

        'plugins' => array(
            'markdown',
            'sitemap',
            'tags' // <= Activation
        ),
        'tags_config' => array( // <= Configuration
            'param' => 'tagged', // <= Page parameter name in URL for the tags filter
            'param_page' => 'page', // <= Page parameter name in URL for the page filter
            'limit' => 5, // <= Number of posts to display per page request
            'separator' => ', ', // <= Separator for each tag link
            'classes' => array( // <= List of item's HTML classes
                'page_item' => 'page',
                'nav' => 'pager',
                'nav_prev' => 'previous',
                'nav_next' => 'next',
                'nav_disabled' => 'disabled',
                'tag' => 'tag',
                'current' => 'current'
            ),
            'labels' => array( // <= List of item's readable text or labels
                'page_header' => '<div class="alert alert-info"><p>Showing posts tagged in <strong>{tag}</strong>.</p></div>',
                'nav_prev' => '&larr; Previous',
                'nav_next' => 'Next &rarr;',
                'not_found' => '<div class="alert alert-danger"><p>No more posts found tagged in <strong>{tag}</strong>.</p></div>'
            )
        )

    );
~~~

Usage
-----

Add this snippet to your `blog_post.html` that is placed in the `themes` folder to show the tag links:

~~~ .html
<div class="post-tags">
    <?php Morfy::factory()->runAction('tags_links'); ?>
</div>
~~~

Edit your `blog.html` file. You have to replace the posts loop with this:

~~~ .php
<?php

$posts = Morfy::factory()->getPages(CONTENT_PATH . '/blog/', 'date', 'DESC', array('404','index'));
$tag_filter = Morfy::$config['tags_config']['param'];

if(isset($tag_filter) && isset($_GET[$tag_filter])) { // Tags page
    Morfy::factory()->runAction('tags');
} else { // Normal posts loop
    foreach($posts as $post) {
        echo '<h3><a href="'.$config['site_url'].'/blog/'.$post['slug'].'">'.$post['title'].'</a></h3>                
            <p>Posted on '.$post['date'].'</p>    
            <div>'.$post['content_short'].'</div>';
    }
}
~~~

If you already installed my [nextprev](https://github.com/tovic/nextprev-plugin-for-morfy-cms "Next/Previous Navigation (Pagination) Plugin for Morfy CMS") plugin, use this code instead:

~~~ .php
<?php

$tag_filter = Morfy::$config['tags_config']['param'];

if(isset($tag_filter) && isset($_GET[$tag_filter])) { // Tags page
    Morfy::factory()->runAction('tags');
} else { // Normal posts loop
    Morfy::factory()->runAction('index_nextprev');
}
~~~

Done.
