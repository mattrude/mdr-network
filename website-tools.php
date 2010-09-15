<?php

/*
Plugin Name: Webmaster Tools
Plugin URI: http://github.com/mattrude/mdr-network
Description: Provides Webmaster site verification scripts for Google, Yahoo, & Bing. Plugin also provides Google Analytics Tracking Script for registered sites.
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/


function add_mdr_webmaster_tools() {
   add_submenu_page( 'tools.php', 'Site Verification Sittings', 'Webmaster Tools', 'administrator', 'site_webmaster_tools', 'mdr_webmaster_tools_page' );
}

function register_mdr_webmaster_tools() {
  add_option('site_verification_google_id');
  add_option('site_verification_yahoo_id');
  add_option('site_verification_bing_id');
}


add_action( 'admin_menu', 'add_mdr_webmaster_tools' );
add_action( 'admin_init', 'register_mdr_webmaster_tools' );

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
    
  // Set Options
  $site_verification_google_id = get_option( 'site_verification_google_id' );
  $site_verification_yahoo_id = get_option( 'site_verification_yahoo_id' );
  $site_verification_bing_id = get_option( 'site_verification_bing_id' );
  $site_google_analytics_id = get_option( 'site_google_analytics_id' );

  // And Display the Admin Page ?>
  <div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
     <h2>Webmaster Tools</h2>
     <h3>Site Verification <small><a href="http://support.wordpress.com/webmaster-tools/" target="_blank" >(?)</a></small></h3>
     <p>All three major search engines provide webmaster tools that give you detailed information and statistics about how they see and crawl your website. In order to access most of the features, you will have to verify your sites.</p>
     <p>Enter your meta key "content" value to verify your blog with <a href="https://www.google.com/webmasters/tools/" target="_blank" >Google Webmaster Tools</a>, <a href="https://siteexplorer.search.yahoo.com/" target="_blank" >Yahoo! Site Explorer</a>, and <a href="http://www.bing.com/webmaster" target="_blank" >Bing Webmaster Center</a></p> 

     <form method="post" action="tools.php?page=site_webmaster_tools"> 
     <table class="form-table"> 
       <tr valign='top'> 
	 <th scope='row'>Google Webmaster Tools:</th> 
	 <td> 
	   <input value='<?php echo $site_verification_google_id ?>' size='70' name='site_verification_google_id' type='text' /> 
           <?php if ( $site_verification_google_id == NULL ) { echo "<span style='color: red'><strong>Disabled</strong></span>"; } else { echo "<span style='color: green'><strong>Enabled</strong></span>"; }?>
	 </td> 
       </tr><tr> 
	 <td colspan='2'> 
	   <label for='site_verification_google'>Example: <code>&lt;meta name='google-site-verification' content='<strong>dBw5CvburAxi537Rp9qi5uG2174Vb6JwHwIRwPSLIK8</strong>'&gt;</code></label> 
	 </td> 
       </tr><tr valign='top'> 
	 <th scope='row'>Yahoo! Site Explorer:</th> 
	 <td> 
	   <input value='<?php echo $site_verification_yahoo_id ?>' size='50' name='site_verification_yahoo_id' type='text' /> 
           <?php if ( $site_verification_yahoo_id == NULL ) { echo "<span style='color: red'><strong>Disabled</strong></span>"; } else { echo "<span style='color: green'><strong>Enabled</strong></span>"; }?>
	 </td> 
       </tr><tr> 
	 <td colspan='2'> 
	   <label for='site_verification_yahoo'>Example: <code>&lt;meta name='y_key' content='<strong>3236dee82aabe064</strong>'&gt;</code></label> 
	 </td> 
       </tr><tr valign='top'> 
	 <th scope='row'>Bing Webmaster Center:</th> 
	 <td> 
	   <input value='<?php echo $site_verification_bing_id ?>' size='50' name='site_verification_bing_id' type='text' /> 
           <?php if ( $site_verification_bing_id == NULL ) { echo "<span style='color: red'><strong>Disabled</strong></span>"; } else { echo "<span style='color: green'><strong>Enabled</strong></span>"; }?>
	 </td> 
       </tr><tr> 
	 <td colspan='2'> 
	   <label for='site_verification_bing'>Example: <code>&lt;meta name='msvalidate.01' content='<strong>12C1203B5086AECE94EB3A3D9830B2E</strong>'&gt;</code></label> 
	 </td> 
       </tr>
     </table>
     <br />
     
     <h3>Google Analytics Tracking Script</h3>
     <p><a href="http://www.google.com/analytics/" target="_blank" >Google Analytics</a> is a web analytics solution that gives you rich insights into your website traffic and marketing effectiveness. Powerful, flexible and easy-to-use features now let you see and analyze your traffic data in an entirely new way. With Google Analytics, you're more prepared to write better-targeted ads, strengthen your marketing initiatives and create higher converting websites.</p>
     <p>Enter your "<strong>Account ID</strong>" for <strong>this</strong> site, to allow <a href="http://www.google.com/analytics/" target="_blank" >Google Analytics</a> to track you page views.
     <table class="form-table">
      <tr valign='top'> 
	 <th scope='row'>Google Analytics Tracking ID:</th> 
	 <td> 
	   <input value='<?php echo $site_google_analytics_id ?>' size='20' name='site_google_analytics_id' type='text' /> 
           <?php if ( $site_google_analytics_id == NULL ) { echo "<span style='color: red'><strong>Disabled</strong></span>"; } else { echo "<span style='color: green'><strong>Enabled</strong></span>"; }?>
	 </td> 
       </tr><tr> 
	 <td colspan='2'> 
	   <label for='site_google_analytics_id'>Example: <code><strong>UA-9527634-1</strong></code></label> 
	 </td> 
       </tr>
     </table> 
     <br />

     <p class="submit"> 
       <input type="submit" name="submit" class="button-primary" value="Save Changes" /> 
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
