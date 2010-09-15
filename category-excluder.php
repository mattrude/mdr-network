<?php

/*
Plugin Name: Category Excluder
Plugin URI: http://github.com/mattrude/mdr-network
Description: Allows a user to select the categories they wish to exclude and where they would like to excluded the categories from (main page, feed, archives). See: Tools -> Webmaster Tools
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/


function add_ce_page() {
  add_submenu_page( 'tools.php', 'Category_Excluder', 'Category Excluder', 'administrator', 'category_excluder', 'ce_admin_menu' );
}

add_action('admin_menu', 'add_ce_page');

add_filter('pre_get_posts','ce_exclude_categories');

function ce_process() {
	//echo '<pre>'; print_r( $_POST );
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
	update_option('ceExcludes', $options);
	
	$message = "<div class='updated'><p>Excludes successfully updated</p></div>";
	return $message;
}

function ce_get_options(){
	$defaults = array();
	$defaults['exclude_main'] = array();
	$defaults['exclude_feed'] = array();
	$defaults['exclude_archives'] = array();

	$options = get_option('ceExcludes');
	if (!is_array($options)){
		$options = $defaults;
		update_option('ceExcludes', $options);
	}

	return $options;
}

function ce_exclude_categories($query) {
	$options = ce_get_options();
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

function ce_admin_menu() {

        if( $_POST[ 'ce' ] ) {
                $message = ce_process();
        }
        $options = ce_get_options();
        ?>
        <div class="wrap">
		<div id="icon-themes" class="icon32"><br></div>
                <h2>Category Excluder Options</h2>
                <?php echo $message ?>
                <p>Use this section allows you to select the categories you wish to exclude and where you would like to exclude them from.</p>
                <p>Note: If a post is in more the one category, it will be excluded if it matches any of the excluded categories</p>
        <form action="tools.php?page=category_excluder" method="post">
        <table class="widefat">
                <thead>
                        <tr>
                                <th scope="col">Category</th>
                                <th scope="col">Exclude from Main Page?</th>
                                <th scope="col">Exclude from Feeds?</th>
                                <th scope="col">Exclude from Archives?</th>
                        </tr>
                </thead>
                <tbody id="the-list">
        <?php
                //print_r( get_categories() );
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
	<p class="submit">
            <input type="submit" name="submit" class="button-primary" value="Save Changes" />
            <input type="hidden" name="ce" value="true" />
        </p>
        </form>
        </div><?php
}

?>
