<?php

/*
Plugin Name: Webmaster Tools
Plugin URI: http://github.com/mattrude/mdr-network
Description: Provides Webmaster site verification scripts for Google, Yahoo, & Bing. Plugin also provides Google Analytics Tracking Script for registered sites. See Tools -> Webmaster Tools
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/


function add_mdr_webmaster_tools() {
   global $mdr_webmaster_tools_hook;
   $mdr_webmaster_tools_hook = add_submenu_page( 'tools.php', 'Site Verification Sittings', 'Webmaster Tools', 'administrator', 'site_webmaster_tools', 'mdr_webmaster_tools_page' );
}

function register_mdr_webmaster_tools() {
  add_option('site_verification_google_id');
  add_option('site_verification_yahoo_id');
  add_option('site_verification_bing_id');
  add_option('site_robots_txt', $site_robots_txt_default, 'Contents of robots.txt', 'no');
}

function my_plugin_help($contextual_help, $screen_id, $screen) {
	global $mdr_webmaster_tools_hook;
	if ($screen_id == $mdr_webmaster_tools_hook) {
		$contextual_help = '
<p>Here’s how you optain and setup each search engines key’s:</p>
<h4 id="google-webmaster-tools">Google Webmaster Tools</h4> 
<ol> 
<li>Log in to <a href="https://www.google.com/webmasters/tools/">https://www.google.com/webmasters/tools/</a> with your Google account.</li> 
<li>Enter your blog URL and click <code>Add Site</code>.</li> 
<li>You will be presented with several verification methods. Choose <code>Meta Tag</code>.</li> 
<li>Copy the meta tag, which looks something like<br /> 
<code>&lt;meta name="google-site-verification"  content="dBw5CvburAxi537Rp9qi5uG2174Vb6JwHwIRwPSLIK8"&gt;</code></li> 
<li>Leave the verification page open and go to your blog dashboard.</li> 
<li>Open the Tools Page and paste the code in the appropriate field.</li> 
<li>Click on <code>Save Changes</code>.</li> 
<li>Go back to the verification page and click <code>Verify</code>.</li> 
</ol> 

<h4 id="yahoo-site-explorer">Yahoo Site Explorer</h4> 
<ol> 
<li>Log in to <a href="https://siteexplorer.search.yahoo.com/">https://siteexplorer.search.yahoo.com/</a> with your Yahoo account.</li> 
<li>Enter your blog URL and click <code>Add My Site</code>.</li> 
<li>You will be presented with several authentication methods. Choose <code>By adding a META tag to my home page.</code>.</li> 
<li>Copy the meta tag, which looks something like<br /> 
<code>&lt;meta name="y_key" content="3236dee82aabe064"&gt;</code></li> 
<li>Leave the verification page open and go to your blog dashboard.</li> 
<li>Open the Tools Page and paste the code in the appropriate field.</li> 
<li>Click on <code>Save Changes</code>.</li> 
<li>Go back to the verification page and click <code>Ready to Authenticate</code>.</li> 
</ol> 
<p><i>Note: It may take up to 24 hours for your site to be authenticated.</i></p> 

<h4 id="bing-webmaster-center">Bing Webmaster Center</h4> 
<ol> 
<li>Log in to <a href="http://www.bing.com/webmaster">http://www.bing.com/webmaster</a> with your Live! account.</li> 
<li>Click <code>Add a Site</code>.</li> 
<li>Enter your blog URL and click <code>Submit</code>.</li> 
<li>Copy the meta tag from the text area at the bottom. It looks something like<br /> 
<code>&lt;meta name="msvalidate.01" content="12C1203B5086AECE94EB3A3D9830B2E"&gt;</code></li> 
<li>Leave the verification page open and go to your blog dashboard.</li> 
<li>Open the Tools Page and paste the code in the appropriate field.</li> 
<li>Click on <code>Save Changes</code>.</li> 
<li>Go back to the verification page and click <code>Return to the Site list</code>.</li> 
</ol> 
<h3>The Robots.txt File</h3>
<p>The <strong>robots.txt</strong> file is a way to prevent cooperating web spiders and other web robots from accessing all or part of a website which is otherwise publicly viewable. Robots are often used by search engines to categorize and archive web sites, or by webmasters to proofread source code.</p>
<h4>To Ban all robots</h4> 
<blockquote><pre>User-agent: *<br />Disallow: /</pre></blockquote>
<h4>To Allow all robots</h4>
<p>To allow any robot to access your entire site, you can simply leave the robots.txt file blank, or you could use this:</p>
<blockquote><pre>User-agent: *<br />Allow: /</pre></blockquote>
		';
	}
	return $contextual_help;
}

add_action( 'contextual_help', 'my_plugin_help', 10, 3 );
add_action( 'admin_menu', 'add_mdr_webmaster_tools' );
add_action( 'admin_init', 'register_mdr_webmaster_tools' );

// Adds default robots.txt file
global $site_robots_txt_default;
$site_robots_txt_default = "# This is the default robots.txt file
User-agent: *
Disallow: /";


$request = str_replace( get_bloginfo('url'), '', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] );
if ( (get_bloginfo('url').'/robots.txt' != 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) && ('/robots.txt' != $_SERVER['REQUEST_URI']) && ('robots.txt' != $_SERVER['REQUEST_URI']) )
  return;         // checking whether they're requesting robots.txt
  $site_robots_txt_out = get_option('site_robots_txt');
  if ( !$site_robots_txt_out)
  return;
    header('Content-type: text/plain');
    print $site_robots_txt_out;
die;




function mdr_webmaster_tools_page() {

  // Update Settings
  if ( isset($_POST['submit']) ) {
    if (!current_user_can('manage_options')) die(__('You cannot edit the search-by-category options.'));
    $site_verification_google_set = $_POST['site_verification_google_id'];
    $site_verification_yahoo_set = $_POST['site_verification_yahoo_id'];
    $site_verification_bing_set = $_POST['site_verification_bing_id'];
    $site_google_analytics_set = $_POST['site_google_analytics_id'];
    update_option("site_verification_google_id", $site_verification_google_set);
    update_option("site_verification_yahoo_id", $site_verification_yahoo_set);
    update_option("site_verification_bing_id", $site_verification_bing_set);
    update_option("site_google_analytics_id", $site_google_analytics_set);
  }
    
  if ( $_POST['site_robots_txt'] ){
    update_option( 'site_robots_txt', $_POST['site_robots_txt'] );
    $urlwarning = str_replace('http://', '', get_bloginfo('url') );
    $urlwarning = substr( $urlwarning, 0, -1 );     // in case there is a trailing slash--don't want it so set off our warning
    if ( strpos( $urlwarning, '/' ) )                       // this is our warning checker
      $urlwarning = '<p>It appears that your blog is installed in a subdirectory, not in a subdomain or at your domain\'s root. Be aware that search engines do not look for robots.txt files in subdirectories. <a href="http://www.robotstxt.org/wc/exclusion-admin.html">Read more</a>.</p>';
  }

  // Set Options
  $site_verification_google_id = get_option( 'site_verification_google_id' );
  $site_verification_yahoo_id = get_option( 'site_verification_yahoo_id' );
  $site_verification_bing_id = get_option( 'site_verification_bing_id' );
  $site_google_analytics_id = get_option( 'site_google_analytics_id' );
  $site_robots_txt_out = get_option('site_robots_txt');


  // And Display the Admin Page ?>
  <style type="text/css"> 
    div.robots_txt_in {border: 1px solid #CCC;clear: left;float: left;height: 200px;margin-right: 25px;margin: 0px 5px 10px;padding: 10px;width: 45%;}
    div.robots_txt_out {border: 1px solid #CCC;float: left;height: 200px;margin-right: 25px;margin: 0px 5px 10px;padding: 10px;width: 45%;}
    div.robots_txt_in_lable {clear: left;float: left;margin-right: 25px;margin: 0px 5px;width: 45%;}
    div.robots_txt_out_lable {float: left;margin-right: 25px;margin: 0px 10px;padding: 0 20px;width: 45%;}
  </style>
  <div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
     <h2><?php _e('Webmaster Tools', 'mdr-network'); ?></h2>
     <h3><?php _e('Site Verification', 'mdr-network'); ?></h3>
     <p><?php _e('All three major search engines provide webmaster tools that give you detailed information and statistics about how they see and crawl your website. In order to access most of the features, you will have to verify your sites.', 'mdr-network'); ?></p>
     <p><?php _e('Enter your meta key "content" value to verify your blog with', 'mdr-network'); ?> 
        <a href="https://www.google.com/webmasters/tools/" target="_blank" ><?php _e('Google Webmaster Tools', 'mdr-network'); ?></a>, 
        <a href="https://siteexplorer.search.yahoo.com/" target="_blank" ><?php _e('Yahoo! Site Explorer', 'mdr-network'); ?></a>, 
        <?php _e('and', 'mdr-network'); ?> 
        <a href="http://www.bing.com/webmaster" target="_blank" ><?php _e('Bing Webmaster Center', 'mdr-network'); ?></a>
     </p> 

     <form method="post" action="tools.php?page=site_webmaster_tools"> 
     <table class="form-table"> 
       <tr valign='top'> 
	 <th scope='row'><?php _e('Google Webmaster Tools', 'mdr-network'); ?>:</th> 
	 <td> 
	   <input value='<?php echo $site_verification_google_id ?>' size='70' name='site_verification_google_id' type='text' /> 
           <?php if ( $site_verification_google_id == NULL ) { echo "<span style='color: red'><strong>Disabled</strong></span>"; } else { echo "<span style='color: green'><strong>Enabled</strong></span>"; }?>
	 </td> 
       </tr><tr> 
	 <td colspan='2'> 
	   <label for='site_verification_google'><?php _e('Example', 'mdr-network'); ?>: <code>&lt;meta name='google-site-verification' content='<strong>dBw5CvburAxi537Rp9qi5uG2174Vb6JwHwIRwPSLIK8</strong>'&gt;</code></label> 
	 </td> 
       </tr><tr valign='top'> 
	 <th scope='row'><?php _e('Yahoo! Site Explorer', 'mdr-network'); ?>:</th> 
	 <td> 
	   <input value='<?php echo $site_verification_yahoo_id ?>' size='50' name='site_verification_yahoo_id' type='text' /> 
           <?php if ( $site_verification_yahoo_id == NULL ) { echo "<span style='color: red'><strong>Disabled</strong></span>"; } else { echo "<span style='color: green'><strong>Enabled</strong></span>"; }?>
	 </td> 
       </tr><tr> 
	 <td colspan='2'> 
	   <label for='site_verification_yahoo'><?php _e('Example', 'mdr-network'); ?>: <code>&lt;meta name='y_key' content='<strong>3236dee82aabe064</strong>'&gt;</code></label> 
	 </td> 
       </tr><tr valign='top'> 
	 <th scope='row'><?php _e('Bing Webmaster Center', 'mdr-network'); ?>:</th> 
	 <td> 
	   <input value='<?php echo $site_verification_bing_id ?>' size='50' name='site_verification_bing_id' type='text' /> 
           <?php if ( $site_verification_bing_id == NULL ) { echo "<span style='color: red'><strong>Disabled</strong></span>"; } else { echo "<span style='color: green'><strong>Enabled</strong></span>"; }?>
	 </td> 
       </tr><tr> 
	 <td colspan='2'> 
	   <label for='site_verification_bing'><?php _e('Example', 'mdr-network'); ?>: <code>&lt;meta name='msvalidate.01' content='<strong>12C1203B5086AECE94EB3A3D9830B2E</strong>'&gt;</code></label> 
	 </td> 
       </tr>
     </table>
     <br />
     
     <h3><?php _e('Google Analytics Tracking Script', 'mdr-network'); ?></h3>
     <p><a href="http://www.google.com/analytics/" target="_blank" ><?php _e('Google Analytics', 'mdr-network'); ?></a> <?php _e('is a web analytics solution that gives you rich insights into your website traffic and marketing effectiveness. Powerful, flexible and easy-to-use features now let you see and analyze your traffic data in an entirely new way. With Google Analytics, you\'re more prepared to write better-targeted ads, strengthen your marketing initiatives and create higher converting websites.', 'mdr-network'); ?></p>
     <p><?php _e('Enter your', 'mdr-network'); ?> "<strong><?php _e('Account ID', 'mdr-network'); ?></strong>" <?php _e('for this site, to allow', 'mdr-network'); ?> <a href="http://www.google.com/analytics/" target="_blank" ><?php _e('Google Analytics', 'mdr-network'); ?></a> <?php _e('to track you page views.', 'mdr-network'); ?></p>
     <table class="form-table">
      <tr valign='top'> 
	 <th scope='row'><?php _e('Google Analytics Tracking ID', 'mdr-network'); ?>:</th> 
	 <td> 
	   <input value='<?php echo $site_google_analytics_id ?>' size='20' name='site_google_analytics_id' type='text' /> 
           <?php if ( $site_google_analytics_id == NULL ) { echo "<span style='color: red'><strong>Disabled</strong></span>"; } else { echo "<span style='color: green'><strong>Enabled</strong></span>"; }?>
	 </td> 
       </tr><tr> 
	 <td colspan='2'> 
	   <label for='site_google_analytics_id'><?php _e('Example', 'mdr-network'); ?>: <code><strong>UA-9527634-1</strong></code></label> 
	 </td> 
       </tr>
     </table> 
     <br />


      <h3><?php _e('Robots.txt File', 'mdr-network'); ?></h3>
      <div class="inside">
        <div class="wrap">
          <p><?php _e('You may edit your robots.txt file in the space below. Lines beginning with <code>#</code> are treated as comments. If you don\'t understand what your doing, most likly you don\'t need to do anything.', 'mdr-network'); ?></p>
          <p><?php _e('Using your robots.txt file, you can ban specific robots, ban all robots, or block robot access to specific pages or areas of your site. If you are not sure what to type, look at the bottom of this page for examples.', 'mdr-network'); ?></p>
	  <div class="robots_txt_in_lable"><strong><?php _e('Modify Your Robots.txt file', 'mdr-network'); ?>:</strong</div>
	  <div class="robots_txt_out_lable"><strong><?php _e('Your Current Robots.txt file', 'mdr-network'); ?>:</strong></div>
	  <div class="robots_txt_in">
            <form method="post" action="http:// <?php echo $_SERVER['HTTP_HOST']; echo $_SERVER['REQUEST_URI']; ?>">
              <textarea id="site_robots_txt" name="site_robots_txt" rows="10" cols="45" class="widefat"><?php echo $site_robots_txt_out; ?></textarea>
            </form>
	  </div>
	  <div class="robots_txt_out">
	    <pre><?php echo $site_robots_txt_out; ?></pre>
	  </div>
        </div>
      </div>

	  <br />
     <p class="submit"> 
       <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes', 'mdr-network'); ?>" /> 
     </p> 
     </form> 
  </div>
</div>
<?php

}

function head_mdr_webmaster_tools() {
  $site_verification_google_id = get_option( 'site_verification_google_id' );
  $site_verification_yahoo_id = get_option( 'site_verification_yahoo_id' );
  $site_verification_bing_id = get_option( 'site_verification_bing_id' );
  echo "
    <!-- Website Verification scripts -->";
  if ( $site_verification_google_id != NULL ) {
    echo "
    <meta name='google-site-verification' content='$site_verification_google_id' />
    ";
  }

  if ( $site_verification_yahoo_id != NULL ) {
    echo "<meta name='y_key' content='$site_verification_yahoo_id'>
    "; 
  }

  if ( $site_verification_bing_id != NULL ) {
    echo "<meta name='msvalidate.01' content='$site_verification_bing_id'>
    ";
  }
}

function footer_mdr_webmaster_tools() {
  $site_google_analytics_id = get_option( 'site_google_analytics_id' );
  if ( is_user_logged_in() ) {
    echo "<!--User is logged in, so this request will NOT be tracked by Google Analytics-->
        ";
  } else {
      if ($site_google_analytics_id == NULL) { ?>
<!--Begin Google Analytics tracking script-->
        <script type="text/javascript">
        
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', '<?php echo $GAID; ?>']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
          })();
           
        </script>
        <!--End Google Analytics tracking script-->
       <?php }
  }
}

add_action('wp_head','head_mdr_webmaster_tools');
add_action('wp_footer','footer_mdr_webmaster_tools');

?>
