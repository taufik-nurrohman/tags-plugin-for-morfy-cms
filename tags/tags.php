<?php

/**
 * Tags Plugin for Morfy CMS
 *
 * @package Morfy
 * @subpackage Plugins
 * @author Taufik Nurrohman <http://latitudu.com>
 * @copyright 2015 Romanenko Sergey / Awilum
 * @version 2.0.0
 *
 */

// Configuration data
$tags_config = Morfy::$plugins['tags'];

// Initialize Fenom
$tags_template = Fenom::factory(
    PLUGINS_PATH . '/' . basename(__DIR__) . '/templates',
    CACHE_PATH . '/fenom',
    Morfy::$fenom
);

// Add global `$.site.offset` variable
Morfy::$site['offset'] = isset($_GET[$tags_config['param']['page']]) ? (int) $_GET[$tags_config['param']['page']] : 1;

// Add global `$.site.tag` variable
Morfy::$site['tag'] = isset($_GET[$tags_config['param']['tag']]) ? $_GET[$tags_config['param']['tag']] : false;

// Morfy::runAction('tags');
Morfy::addAction('tags', function() use($tags_config, $tags_template) {

    // Get current URI segments
    $path = trim(Url::getUriString(), '/');
    // Number of posts to display per page request
    $per_page = isset($tags_config['limit']) ? $tags_config['limit'] : 5;
    // Get all posts
    $all_pages = Morfy::getPages($path, 'date', 'DESC', array('404', 'index'));
    // Get current page offset
    $current_page = Morfy::$site['offset'];
    // Get tag name from URL
    $filter = Morfy::$site['tag'];

    // Filtering ...
    if($filter !== false) {
        $filter = urldecode($filter);
        $tags_config['labels']['found'] = str_replace('{tag}', $filter, $tags_config['labels']['found']);
        $tags_config['labels']['not_found'] = str_replace('{tag}', $filter, $tags_config['labels']['not_found']);
        // Collect all of the filtered posts here
        $filtered_pages = array();
        if(is_array($all_pages)) {
            foreach($all_pages as $page) {
                if( ! isset($page['tags'])) continue;
                // Remove all spaces between commas, then wrap them with `<` and `>`
                $tags = ',' . preg_replace('/\s*,\s*/', ',', $page['tags']) . ',';
                if(strpos($tags, ',' . $filter . ',') !== false) {
                    $filtered_pages[] = $page;
                }
            }
            unset($all_pages);
        }
        if( ! empty($filtered_pages)) {
            // Split all of the filtered posts into chunks
            $pages = array_chunk($filtered_pages, $per_page);
            // Calculate total pages
            $total_pages = ceil(count($filtered_pages) / $per_page);
            // Posts loop
            if(isset($pages[$current_page - 1]) && ! empty($pages[$current_page - 1])) {
                $tags_template->display('success.tpl', array(
                    'config' => $tags_config
                ));
                foreach($pages[$current_page - 1] as $page) {
                    $tags_template->display('post.tpl', array(
                        'config' => $tags_config,
                        'page' => $page
                    ));
                }
                // Pagination
                $tags_template->display('nav.tpl', array(
                    'config' => $tags_config,
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev' => $current_page > 1 ? '?' . $tags_config['param']['page'] . '=' . ($current_page - 1) . '&amp;' . $tags_config['param']['tag'] . '=' . urlencode($filter) : false,
                    'next' => $current_page < $total_pages ? '?' . $tags_config['param']['page'] . '=' . ($current_page + 1) . '&amp;' . $tags_config['param']['tag'] . '=' . urlencode($filter) : false
                ));
            } else {
                $tags_template->display('error.tpl', array(
                    'config' => $tags_config
                ));
            }
        } else {
            $tags_template->display('error.tpl', array(
                'config' => $tags_config
            ));
        }
    }

});

// Morfy::runAction('tags.widget');
Morfy::addAction('tags.widget', function() use($tags_config, $tags_template) {

    // Get current URI segments
    $path = Url::getUriSegments();
    $path_x = array_pop($path);
    $path = implode('/', $path);
    // Get post data
    $page = Morfy::getPage($path . '/' . $path_x);
    $tags = array();
    if(isset($page['tags']) && ! empty($page['tags'])) {
        foreach(explode(',', $page['tags']) as $tag) {
            $tag = trim($tag);
            $tags[$tag] = rtrim(Morfy::$site['url'], '/') . '/' . $path . '?' . $tags_config['param']['tag'] . '=' . urlencode($tag);
        }
    }
    ksort($tags);
    $tags_template->display('link.tpl', array(
        'tags' => $tags
    ));

});