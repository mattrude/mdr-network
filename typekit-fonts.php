<?php
/*
Plugin Name: Typekit Fonts
Plugin URI: http://github.com/mattrude/mdr-network
Description: Provides Typekit fonts to a website by adding the needed java code to the pages. See Appearance -> Fonts
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/

function add_fonts_page() {
  global $typekit_font_hook;
  $typekit_font_hook = add_submenu_page( 'themes.php', 'Typekit Fonts Options', __('Fonts', 'mdr-network'), 'administrator', 'typekitfonts', 'fonts_page' );
}

function register_typekit() {
  add_option('typekit_id');
}

function typekit_font_help($contextual_help, $screen_id, $screen) {
	global $typekit_font_hook;
	if ($screen_id == $typekit_font_hook) {
		$contextual_help = '<p>' . __('Typekit&rsquo;s fonts isn&rsquo;t the easist thing to setup. It will require that you know something about the way your page is displayed to the public. See the following resources for a overview of what your trying to do.', 'mdr-network') . '</p><ul>
		<li><a href="http://typekit.zendesk.com/entries/130054-using-typekit-the-basics" target="_blank">' . __('Using Typekit – The Basics', 'mdr-network') . '</a></li>
		<li><a href="http://typekit.zendesk.com/entries/121731-using-typekit-with-wordpress" target="_blank">' . __('Using Typekit with WordPress', 'mdr-network') . '</a></li>
		<li><a href="http://typekit.zendesk.com/entries/130283-finding-and-using-selectors" target="_blank">' . __('Finding and Using Selectors', 'mdr-network') . '</a></li>
		</ul>'; }
	return $contextual_help;
}

add_action( 'contextual_help', 'typekit_font_help', 10, 3 );
add_action( 'admin_menu', 'add_fonts_page' );
add_action( 'admin_init', 'register_typekit' );

function fonts_page() {
  settings_fields( 'mdr-network' ); 

  // Update Settings
  if ( isset($_POST['submit']) ) {
    if (!current_user_can('manage_options')) die(__('You cannot edit this screen.', 'mdr-network'));
    $typekit_id = $_POST['typekit_id'];
    update_option("typekit_id", $typekit_id);
  }

?>
  <style type="text/css"> 
    div.typekit_box_one {border: 1px solid #CCC;clear: left;float: left;height: 220px;margin-right: 25px;padding: 0px 20px;width: 260px;}
    div.typekit_box_two {border: 1px solid #CCC;float: left;height: 220px;margin-right: 25px;padding: 0px 20px;width: 260px;}
  </style>
  <div class="wrap"> 
    <div id="icon-themes" class="icon32"><br></div>
    <h2><?php _e('Typekit Fonts', 'mdr-network') ?></h2> 
    <p><a href="http://typekit.com/"><strong><?php _e('Typekit', 'mdr-network'); ?></strong></a> <?php _e('is the easiest way to use real fonts on the web. It&rsquo;s a subscription-based service for linking to high-quality Open Type fonts from some of the worlds best type foundries. Typekit fonts are served from a global network on redundant servers, offering bulletproof service and incredible speed. And it couldn&rsquo;t be easier to use.', 'mdr-network'); ?></p> 
 
    <div class="typekit_box_one"> 
      <form action="themes.php?page=typekitfonts" method="post"> 
		<h3><?php _e('Your Typekit ID', 'mdr-network'); ?></h3> 
		<p><?php _e('You can find your Typekit ID on typekit.com under Kit Editor -> Embed Code.', 'mdr-network'); ?></p> 
		<?php $typekit_id = get_option( 'typekit_id' );?>
		<p><input type="text" size="25" name="typekit_id" value="<?php echo $typekit_id; ?>" /></p> 
		<p class="submit"><input type="submit" name="submit" value="<?php _e('Update ID', 'mdr-network'); ?>" /></p> 
      </form> 
    </div> 
 
    <div class="typekit_box_two"> 
	<form action="http://typekit.com/ref/wordpress" method="post"> 
		<h3><?php _e('Sign up for Typekit', 'mdr-network'); ?></h3> 
		<p><?php _e('If you don&#8217;t have a Typekit ID yet you can sign up on their site for free and enable it on your site with just a few clicks.', 'mdr-network'); ?></p> 
		<p class="submit"> 
			<input type="submit" name="submit" value="<?php _e('Sign up in seconds', 'mdr-network'); ?>" /> 
		</p> 
	</form> 
  </div> <?php 
}

function typekit_header() {
  $typekit_id = get_option( 'typekit_id' );
  if ( $typekit_id != NULL ) {
    echo '    <!-- Typekit Font download script -->
    <script type="text/javascript" src="http://use.typekit.com/'. $typekit_id .'.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>';
  }
}

add_action('wp_head','typekit_header');
?>
