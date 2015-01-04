<?php

/**
 * Tags Plugin for Morfy CMS
 *
 * @package Morfy
 * @subpackage Plugins
 * @author Taufik Nurrohman <http://latitudu.com>
 * @copyright 2014 Romanenko Sergey / Awilum
 * @version 1.0.1
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

    sort($tags);

    echo implode($config['separator'], $tags);

});

Morfy::factory()->addAction('tags', function() {

    // Configuration data
    $config = Morfy::$config['tags_config'];
    // Get current URI segments
    $path = Morfy::factory()->getUrl();
    // Number of posts to display per page request
    $per_page = isset($config['limit']) ? $config['limit'] : 5;
    // Get all posts
    $all_posts = Morfy::factory()->getPages(CONTENT_PATH . '/' . $path, 'date', 'DESC', array('404', 'index'));
    // Get current page offset
    $current_page = isset($_GET[$config['param_page']]) ? (int) $_GET[$config['param_page']] : 1;
    // Get tag name from URL
    $filter = $_GET[$config['param']];

    // Collect all of the filtered posts here
    $filtered_posts = array();
    $results = "";

    // Filtering ...
    if(isset($filter)) {
        $filter = Morfy::factory()->cleanString(urldecode($filter));
        if(is_array($all_posts)) {
            foreach($all_posts as $post) {
                // Remove all spaces between commas, then wrap them with `<` and `>`
                $tags = '<' . preg_replace('/\s*,\s*/', ',', $post['tags']) . '>';
                if(
                    strpos($tags, '<' . $filter . ',') !== false ||
                    strpos($tags, ',' . $filter . ',') !== false ||
                    strpos($tags, ',' . $filter . '>') !== false ||
                    '<' . $filter . '>' === $tags
                ) {
                    $filtered_posts[] = $post;
                }
            }
        }

        // Split all of the filtered posts into chunks
        $posts = array_chunk($filtered_posts, $per_page);
        // Calculate total pages
        $total_pages = ceil(count($filtered_posts) / $per_page);

        if(isset($posts[$current_page - 1]) && ! empty($posts[$current_page - 1])) {
            // Build the posts list
            foreach($posts[$current_page - 1] as $post) {
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
            // Build the pagination
            $results .= '<ul class="' . $config['classes']['nav'] . '">';
            $results .= $current_page > 1 ? '<li class="' . $config['classes']['nav_prev'] . '"><a href="?' . $config['param'] . '=' . $filter . '&amp;' . $config['param_page'] . '=' . ($current_page - 1) . '">' . $config['labels']['nav_prev'] . '</a></li>' : '<li class="' . $config['classes']['nav_prev'] . ' ' . $config['classes']['nav_disabled'] . '"><span>' . $config['labels']['nav_prev'] . '</span></li>';
            $results .= $current_page < $total_pages ? ' <li class="' . $config['classes']['nav_next'] . '"><a href="?' . $config['param'] . '=' . $filter . '&amp;' . $config['param_page'] . '=' . ($current_page + 1) . '">' . $config['labels']['nav_next'] . '</a></li>' : ' <li class="' . $config['classes']['nav_next'] . ' ' . $config['classes']['nav_disabled'] . '"><span>' . $config['labels']['nav_next'] . '</span></li>';
            $results .= '</ul>';
        }
    }

    echo $results === "" ? '<div class="' . $config['classes']['page_item'] . '">' . str_replace('{tag}', $filter, $config['labels']['not_found']) . '</div>' : str_replace('{tag}', $filter, $config['labels']['page_header']) . $results;

});
