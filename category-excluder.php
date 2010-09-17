<?php

/*
Plugin Name: Category Excluder
Plugin URI: http://github.com/mattrude/mdr-network
Description: Allows a user to select the categories they wish to exclude and where they would like to excluded the categories from (main page, feed, archives). See: Tools -> Webmaster Tools
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/

define('CATEGORY_EXCLUDER_TEXTDOMAIN', 'mdr-network');

if (function_exists('load_plugin_textdomain')) {
        load_plugin_textdomain(CATEGORY_EXCLUDER_TEXTDOMAIN, false, dirname(__FILE__).'/languages' );
}

function add_category_excluder_page() {
  global $category_excluder_hook;
  $category_excluder_hook = add_submenu_page( 'tools.php', 'Category_Excluder', __('Category Excluder', CATEGORY_EXCLUDER_TEXTDOMAIN), 'administrator', 'category_excluder', 'category_excluder_admin_menu' );
}

function category_excluder_help($contextual_help, $screen_id, $screen) {
	global $category_excluder_hook;
	if ($screen_id == $category_excluder_hook) {
		$contextual_help = '<h4>' . __('Category Excluder', CATEGORY_EXCLUDER_TEXTDOMAIN) . '</h4><p>' . __('On this screen you may disable any category from displaying on your main page, feeds, and/or archives. If a post is in more then one category, it will be excluded if it&rsquo;s in any exclued category.', CATEGORY_EXCLUDER_TEXTDOMAIN) . '</p>'; }
	return $contextual_help;
}

add_action('contextual_help', 'category_excluder_help', 10, 3);
add_action('admin_menu', 'add_category_excluder_page');

add_filter('pre_get_posts','category_excluder_exclude_categories');

function category_excluder_process() {
	if( !$_POST[ 'exclude_main' ] ) {
		$_POST[ 'exclude_main' ] = array();
	}
	if( !$_POST[ 'exclude_feed' ] ) {
		$_POST[ 'exclude_feed' ] = array();
	}
	if( !$_POST[ 'exclude_archives' ] ) {
		$_POST[ 'exclude_archives' ] = array();
	}
	$options['exclude_main'] = $_POST[ 'exclude_main' ];
	$options['exclude_feed'] = $_POST[ 'exclude_feed' ];
	$options['exclude_archives'] = $_POST[ 'exclude_archives' ];
	update_option('site_category_excluder', $options);
	
	$message = "<div class='updated'><p>" . __('Excludes successfully updated', CATEGORY_EXCLUDER_TEXTDOMAIN) . "</p></div>";
	return $message;
}

function category_excluder_get_options(){
	$defaults = array();
	$defaults['exclude_main'] = array();
	$defaults['exclude_feed'] = array();
	$defaults['exclude_archives'] = array();

	$options = get_option('site_category_excluder');
	if (!is_array($options)){
		$options = $defaults;
		update_option('site_category_excluder', $options);
	}

	return $options;
}

function category_excluder_exclude_categories($query) {
	$options = category_excluder_get_options();
	if ($query->is_home) {
		$query->set('cat', implode( ', ', $options[ 'exclude_main' ] ) );
	}
	if ($query->is_feed) {
		$query->set('cat', implode(', ', $options[ 'exclude_feed' ] ) );
	}
	if ($query->is_archive) {
		$query->set('cat', implode(', ', $options[ 'exclude_archives' ] ) );
	}

	return $query;
}

function category_excluder_admin_menu() {

        if( $_POST[ 'ce' ] ) {
                $message = category_excluder_process();
        }
        $options = category_excluder_get_options();
        ?>
        <div class="wrap">
		<div id="icon-themes" class="icon32"><br></div>
                <h2><?php _e('Category Excluder', CATEGORY_EXCLUDER_TEXTDOMAIN) ?></h2>
                <?php echo $message ?>
		<p><?php _e('This page will allow you to exclude or disable individual categories from displaying on the main page, in your feed, and/or in your archives.', CATEGORY_EXCLUDER_TEXTDOMAIN) ?></p>
        <form action="tools.php?page=category_excluder" method="post">
        <table class="widefat">
                <thead>
                        <tr>
                                <th scope="col"><?php _e('Category', CATEGORY_EXCLUDER_TEXTDOMAIN) ?></th>
                                <th scope="col"><?php _e('Exclude from Main Page?', CATEGORY_EXCLUDER_TEXTDOMAIN) ?></th>
                                <th scope="col"><?php _e('Exclude from Feeds?', CATEGORY_EXCLUDER_TEXTDOMAIN) ?></th>
                                <th scope="col"><?php _e('Exclude from Archives?', CATEGORY_EXCLUDER_TEXTDOMAIN) ?></th>
                        </tr>
                </thead>
                <tbody id="the-list">
        <?php
                $cats = get_categories();
                $alt = 0;
                foreach( $cats as $cat ) {
                        ?>
                        <tr<?php if ( $alt == 1 ) { echo ' class="alternate"'; $alt = 0; } else { $alt = 1; } ?>>
                                <th scope="row"><?php echo $cat->cat_name; //. ' (' . $cat->cat_ID . ')'; ?></th>
                                <td>
                                        <input type="checkbox" name="exclude_main[]" value="-<?php echo $cat->cat_ID ?>" <?php if ( in_array( '-' . $cat->cat_ID, $options['exclude_main'] ) ) { echo 'checked="true" '; } ?>/>
                                </td>
                                <td><input type="checkbox" name="exclude_feed[]" value="-<?php echo $cat->cat_ID ?>" <?php if ( in_array( '-' . $cat->cat_ID, $options['exclude_feed'] ) ) { echo 'checked="true" '; } ?>/></td>
                                <td><input type="checkbox" name="exclude_archives[]" value="-<?php echo $cat->cat_ID ?>" <?php if ( in_array( '-' . $cat->cat_ID, $options['exclude_archives'] ) ) { echo 'checked="true" '; } ?>/></td>
                        </tr>
                <?php
                }
        ?>
        </table>
                <p><i><?php _e('Note: If a post is in more the one category, it will be excluded if it matches <strong>any</strong> of the excluded categories.', CATEGORY_EXCLUDER_TEXTDOMAIN) ?></i></p>
	<p class="submit">
            <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes', CATEGORY_EXCLUDER_TEXTDOMAIN) ?>" />
            <input type="hidden" name="ce" value="true" />
        </p>
        </form>
        </div><?php
}

?>
