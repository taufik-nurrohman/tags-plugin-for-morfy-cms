<?php

/**
 * Tags Plugin for Morfy CMS
 *
 * @package Morfy
 * @subpackage Plugins
 * @author Taufik Nurrohman <http://latitudu.com>
 * @copyright 2014 Romanenko Sergey / Awilum
 * @version 1.0.0
 *
 */

Morfy::factory()->addAction('tags_links', function() {

    // Configuration data
    $config = Morfy::$config['tags_config'];
    // Get current URI segments
    $path = Morfy::factory()->getUriSegments();
    array_pop($path);
    // Get post data
    $post = Morfy::factory()->getPage(Morfy::factory()->getUrl());

    $tags = array();

    if( ! empty($post['tags'])) {
        foreach(explode(',', $post['tags']) as $tag) {
            $tags[] = '<a class="' . $config['classes']['tag'] . '" href="' . rtrim(Morfy::$config['site_url'], '/') . '/' . implode('/', $path) . '?' . $config['param'] . '=' . preg_replace('/\s+/', '+', trim($tag)) . '">' . trim($tag) . '</a>';
        }
    }

    echo implode($config['separator'], $tags);

});

Morfy::factory()->addAction('tags', function() {

    // Configuration data
    $config = Morfy::$config['tags_config'];
    // Get current URI segments
    $path = Morfy::factory()->getUrl();
    // Get all posts
    $all_posts = Morfy::factory()->getPages(CONTENT_PATH . '/' . $path . '/', 'date', 'DESC', array('404', 'index'));

    $results = "";

    // Get tag name from URL
    $filter = $_GET[$config['param']];

    if(isset($filter)) {

        $filter = Morfy::factory()->cleanString(urldecode($filter));

        // Posts loop
        foreach($all_posts as $post) {

            // Strip all spaces between commas, then explode
            $tags = preg_replace('/(\s+)?\,(\s+)?/', ',', $post['tags']);
            $tags = explode(',', $tags);

            if(in_array($filter, $tags)) {
                $results .= '<div class="' . $config['classes']['page_item'] . '">';
                $results .= $post['title'] ? '<h3><a href="' . $post['url'] . '">' . $post['title'] . '</a></h3>' : "";
                $results .= $post['date'] ? '<p><em><strong>Published on:</strong> ' . $post['date'] . '</em></p>' : "";
                if(strlen($post['description']) > 0) {
                    $results .= '<p>' . $post['description'] . '</p>';
                } elseif(strlen($post['content_short']) > 0) {
                    $results .= '<p>' . $post['content_short'] . '</p>';
                }
                $results .= '</div>';
            }

        }

    }

    echo str_replace('{tag}', $filter, $config['labels']['page_header']);
    echo $results === "" ? '<div class="' . $config['classes']['page_item'] . '">' . $config['labels']['not_found'] . '</div>' : $results;

});
