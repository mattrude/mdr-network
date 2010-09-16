<?php
/**
 * XML Sitemap Feed Template for displaying XML Sitemap Posts feed.
 */

//header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>
<urlset	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
	xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:image="http://www.sitemaps.org/schemas/sitemap-image/1.1"
        xmlns:video="http://www.sitemaps.org/schemas/sitemap-video/1.1"
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
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC'
	);
	$post_ids = get_posts($args);

	if ($post_ids) {
		foreach ($post_ids as $post) { ?>
	<url>
		<loc><?php the_permalink_rss() ?></loc>
<?php
	$args2 = array(
                'post_type' => 'attachment',
                'numberposts' => 100,
		'post_parent' => $post->ID,
                'orderby' => 'date',
                'order' => 'DESC'
        );
	$attachments = get_posts($args2);
	if ($attachments) {
		foreach ($attachments as $post) { ?>
		<image:image>
			<image:image><?php echo wp_get_attachment_url(); ?></image:loc>
<?php if ( !empty($post->post_excerpt) ) echo '			<image:caption>' . $post->post_excerpt . '</image:caption>
'; ?>
			<image:title><?php echo the_title(); ?></image:title>
		</image:image>
<?php } } ?>
		<lastmod><?php echo mysql2date('Y-m-d\TH:i:s\Z', get_post_time('Y-m-d H:i:s', true), false); ?></lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.5</priority>
	</url>
<?php } } ?>
</urlset>
