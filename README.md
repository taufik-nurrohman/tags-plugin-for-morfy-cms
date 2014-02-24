Tags Plugin for Morfy CMS
=========================

Configuration
-------------

Place the `tags` folder with its contents in `plugins` folder. Then update your `config.php` file:

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
                'param' => 'tagged', // <= Page parameter name in URL for tag filtering
                'separator' => ', ', // <= Separator for each generated tag link
                'classes' => array( // <= List of item's HTML classes
                    'page_item' => 'page-post',
                    'tag' => 'tag',
                    'current' => 'current'
                ),
                'labels' => array( // <= List of item's readable text or labels
                    'page_header' => '<p>Showing posts tagged in &ldquo;{tag}&rdquo;</p>',
                    'not_found' => '<p>Not found.</p>'
                )
            )
        );

Usage
-----

Add this snippet to your `blog_post.html` that is placed in the `themes` folder to show the tag links:

    <div class="post-tags">
        <?php Morfy::factory()->runAction('tags_links'); ?>
    </div>

Edit your `blog.html` file. You have to replace the posts loop with this:

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
    
    ?>

If you already installed my [nextprev](https://github.com/tovic/nextprev-plugin-for-morfy-cms "Next/Previous Navigation (Pagination) Plugin for Morfy CMS") plugin, use this code instead:

    <?php
    
    $posts = Morfy::factory()->getPages(CONTENT_PATH . '/blog/', 'date', 'DESC', array('404','index'));
    $tag_filter = Morfy::$config['tags_config']['param'];
    
    if(isset($tag_filter) && isset($_GET[$tag_filter])) { // Tags page
    	Morfy::factory()->runAction('tags');
    } else { // Normal posts loop
        Morfy::factory()->runAction('index_nextprev');
    }
    
    ?>

Done.
