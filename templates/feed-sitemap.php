<?php
/**
 * XML Sitemap Feed Template for displaying XML Sitemap Posts feed.
 */

//header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>
<urlset	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
	xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
>
	<url>
		<loc><?php bloginfo_rss('url') ?></loc>
		<lastmod><?php echo mysql2date('Y-m-d\TH:i:s\Z', get_lastpostmodified('GMT'), false); ?></lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>

<?php
        $args = array(
                'post_type' => 'page',
                'numberposts' => 100,
                'status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
        );
        $post_ids = get_posts($args);
	
        if ($post_ids) {
                foreach ($post_ids as $post) { ?>
	<url>
                <loc><?php the_permalink_rss() ?></loc>
                <lastmod><?php echo mysql2date('Y-m-d\TH:i:s\Z', get_post_time('Y-m-d H:i:s', true), false); ?></lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.7</priority>
        </url>

<?php } }
	$args = array(
		'post_type' => 'post',
		'numberposts' => 100,
		'status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC'
	);
	$post_ids = get_posts($args);

	if ($post_ids) {
		foreach ($post_ids as $post) { ?>
	<url>
		<loc><?php the_permalink_rss() ?></loc>
		<lastmod><?php echo mysql2date('Y-m-d\TH:i:s\Z', get_post_time('Y-m-d H:i:s', true), false); ?></lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.5</priority>
	</url>

<?php } } ?>
</urlset>
