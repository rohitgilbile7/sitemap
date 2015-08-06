<?php
/**
 * start page for webaccess
 * redirect the user to the supported page type by the users webbrowser (js available or not)
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   rohitgilbile
 * @author    Rohit Gilbile <rohitgilbile7@gmail.com>
 * @link      http://phplesson.com

************************************
HTML Sitemap   in WordPress   
************************************/

function sitemap() {
    $sitemap = '';
    $sitemap .= '<h4>Articles </h4>';
    $sitemap .= '<ul class="sitemapul">';
    $posts_array = get_posts();
    foreach ($posts_array as $spost):
        $sitemap .='<div class="blockArticle">
            <h3><a href="' . $spost->guid . '" rel="bookmark" class="linktag">' . $spost->post_title . '</a> </h3>
        </div>';
    endforeach;
    $sitemap .= '</ul>';
    $sitemap .= '<h4>Category</h4>';
    $sitemap .= '<ul class="sitemapul">';
    $args = array(
        'offset' => 0,
        'category' => '',
        'category_name' => '',
        'orderby' => 'date',
        'order' => 'DESC',
        'include' => '',
        'exclude' => '',
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'post',
        'post_mime_type' => '',
        'post_parent' => '',
        'author' => '',
        'post_status' => 'publish',
        'suppress_filters' => true
    );
    $cats = get_categories($args);
    foreach ($cats as $cat) :
        $sitemap .= '<li class="pages-list"><a href="' . get_category_link($cat->term_id) . '">' . $cat->cat_name . '</a></li>';
    endforeach;
    $sitemap .= '</ul>';
    $pages_args = array(
        'exclude' => '', /* ID of pages to be excluded, separated by comma */
        'post_type' => 'page',
        'post_status' => 'publish'
    );
    $sitemap .= '<h3>Pages</h3>';
    $sitemap .= '<ul>';
    $pages = get_pages($pages_args);
    foreach ($pages as $page) :
        $sitemap .= '<li class="pages-list"><a href="' . get_page_link($page->ID) . '" rel="bookmark">' . $page->post_title . '</a></li>';
    endforeach;
    $sitemap .= '</ul>';
    $sitemap .= '<h4>Tags</h4>';
    $sitemap .= '<ul class="sitemapul">';
    $tags = get_tags();
    foreach ($tags as $tag) {
        $tag_link = get_tag_link($tag->term_id);
        $sitemap .= "<li class='pages-list'><a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
        $sitemap .= $tag->name . '</a></li>';
    }
    return$sitemap;
}
add_shortcode('sitemap', 'sitemap');


/****************************************************
* XML Sitemap in WordPress
*****************************************************/

function xml_sitemap() {
  $postsForSitemap = get_posts(array(
    'numberposts' => -1,
    'orderby' => 'modified',
    'post_type'  => array('post','page'),
    'order'    => 'DESC'
  ));

  $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
  $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

  foreach($postsForSitemap as $post) {
    setup_postdata($post);

    $postdate = explode(" ", $post->post_modified);

    $sitemap .= '<url>'.
      '<loc>'. get_permalink($post->ID) .'</loc>'.
      '<lastmod>'. $postdate[0] .'</lastmod>'.
      '<changefreq>monthly</changefreq>'.
    '</url>';
  }

  $sitemap .= '</urlset>';

  $fp = fopen(ABSPATH . "sitemap.xml", 'w');
  fwrite($fp, $sitemap);
  fclose($fp);
}

add_action("publish_post", "xml_sitemap");
add_action("publish_page", "xml_sitemap");
?>
