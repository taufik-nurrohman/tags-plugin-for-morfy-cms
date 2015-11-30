<?php

/**
 * Tags Plugin for Morfy CMS
 *
 * @package Morfy
 * @subpackage Plugins
 * @author Taufik Nurrohman <http://latitudu.com>
 * @copyright 2015 Romanenko Sergey / Awilum
 * @version 2.1.0
 *
 */

// Configuration data
$tags_c = Config::get('plugins.tags');

// Add global `$config.site.offset` variable
Config::set('site.offset', (int) Arr::get($_GET, $tags_c['param']['page'], 1));

// Add global `$config.site.tag` variable
Config::set('site.tag', Arr::get($_GET, $tags_c['param']['tag'], false));

// Translate variable
$c = 'plugins.tags.labels';
Config::set($c . '.found.page', str_replace('{tag}', Config::get('site.tag'), $tags_c['labels']['found']['page']));
Config::set($c . '.not_found.page', str_replace('{tag}', Config::get('site.tag'), $tags_c['labels']['not_found']['page']));

// Initialize template
$tags_t = Template::factory(__DIR__ . '/templates');

// Action::run('tags');
Action::add('tags', function() use($tags_c, $tags_t) {

    // Get current URI segments
    $path = Url::getUriString();
    // Number of posts to display per page request
    $per_page = Arr::get($tags_c, 'limit', 5);
    // Get all posts
    $all_pages = Pages::getPages($path, 'date', 'DESC', array('404', 'index'));
    // Collect all tags
    $all_tags = array();
    // Get current page offset
    $current_page = Config::get('site.offset');
    // Get tag name from URL
    $filter = Config::get('site.tag');

    // Filtering ...
    if($filter !== false) {
        $filter = urldecode($filter);
        // Collect all of the filtered posts here
        $filtered_pages = array();
        if(is_array($all_pages)) {
            foreach($all_pages as $page) {
                if( ! isset($page['tags'])) continue;
                // Remove all spaces between commas
                $tags = preg_replace('#\s*,\s*#', ',', $page['tags']);
                if(strpos(',' . $tags . ',', ',' . $filter . ',') !== false) { // much faster than `in_array()` ;)
                    $filtered_pages[] = $page;
                }
                $all_tags = array_merge($all_tags, explode(',', $tags));
                File::setContent(__DIR__ . '/cache/tags.json', json_encode(array_unique($all_tags)));
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
                $tags_t->display('success.tpl');
                foreach($pages[$current_page - 1] as $page) {
                    $tags_t->display('post.tpl', array(
                        'page' => $page
                    ));
                }
                // Pagination
                $tags_t->display('nav.tpl', array(
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev' => $current_page > 1 ? '?' . $tags_c['param']['page'] . '=' . ($current_page - 1) . '&amp;' . $tags_c['param']['tag'] . '=' . urlencode($filter) : false,
                    'next' => $current_page < $total_pages ? '?' . $tags_c['param']['page'] . '=' . ($current_page + 1) . '&amp;' . $tags_c['param']['tag'] . '=' . urlencode($filter) : false
                ));
            } else {
                $tags_t->display('error.tpl');
            }
        } else {
            $tags_t->display('error.tpl');
        }
    }

});

// Action::run('tags.widget');
Action::add('tags.widget', function() use($tags_c, $tags_t) {

    // Get current URI segments
    $path = Url::getUriString();
    // Get post data
    $page = Pages::getPage($path);
    $tags = array();
    // Show all tags
    if(File::exists(__DIR__ . '/cache/tags.json') && File::exists(STORAGE_PATH . '/pages/' . $path . '/index.md')) {
        $all_tags = json_decode(File::getContent(__DIR__ . '/cache/tags.json'));
        foreach($all_tags as $tag) {
            $tags[$tag] = Url::getBase() . '/' . $path . '?' . $tags_c['param']['tag'] . '=' . urlencode($tag);
        }
    // Single page, normally
    } else {
        if(isset($page['tags']) && ! empty($page['tags'])) {
            foreach(explode(',', $page['tags']) as $tag) {
                $tag = trim($tag);
                $tags[$tag] = Url::getBase() . '/' . dirname($path) . '?' . $tags_c['param']['tag'] . '=' . urlencode($tag);
            }
        }
    }
    ksort($tags);
    $tags_t->display('link.tpl', array(
        'tags' => ! empty($tags) ? $tags : false
    ));

});