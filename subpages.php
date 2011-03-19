<?php
/*
Plugin Name: Sub Pages widget
Description: Show only the sub pages, if the current page has sub pages
Author: Alper Haytabay
Version: 1.1
Author URI: http://www.haytabay.de
*/

/*  
	Copyright 2007  Alper Haytabay  (email : alper@haytabay.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

/*
Change log:
  Version 1.1:
  - Add additional options to change the displayed content of the sub pages widget.
    The additional options are:
    + You can now select that not the root page is used for displaing the hierarchy
      or the from the current page
    + You can now select that the hierarchy is not displyed only the first 
      level child pages are displyed
    + When the root page is not used the parent page is added to the pages collection.
      You can select the position (top or bottom) and the icon which should be displayed
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_subpages_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_subpages($args) {
		global $post;
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);
    if (is_page())
    {
  		// Each widget can store its own options. We keep strings here.
  		$options = get_option('widget_subpages');
  		$title = $options['title'];
  		
  		// default values for the items added in version 1.1
  		$useRoot = 1;  // we use the root page per default
      $onlyFirstLevel = 0; // display all children
      $parentIcon = '<='; // the default parent icon is "<="
      $parentPosition = 'top'; // the parent row is added at the top
      $addParent = 1; // add the parent row the list
  		
  		// If an old version is used where this config is not defined
  		// don't try to load the config value use the default instead
      if (isset($options['useRoot']))
      {		
  		   $useRoot = $options['useRoot'];
  		}
  		
  		if (isset($options['onlyFirstLevel']))
      {		
  		   $onlyFirstLevel = $options['onlyFirstLevel'];
  		}  	
  		
  		if (isset($options['parentIcon']))
      {		
  		   $parentIcon = $options['parentIcon'];
  		}
  		
      if (isset($options['parentPosition']))
      {		
  		   $parentPosition = $options['parentPosition'];
  		}
  		
  		if (isset($options['addParent']))
      {		
  		   $addParent = $options['addParent'];
  		}
  
      $rootPost = $post;
      
      // find out the root page only when needed
      if ($useRoot)
      {
        // find the root page and use it
        while ($rootPost->post_parent != 0)
        {
          $rootPost = &get_post($rootPost->post_parent);
        }
      }      
      
      // If only the first level should be used set the
      // depthStr to the correct value.
      // see http://codex.wordpress.org/Template_Tags/wp_list_pages for more information
      $depthStr = '';
      if ($onlyFirstLevel && !$useRoot)
      {
        $depthStr='&depth=1';
      }
    
      // the title 
      $title = $title.$rootPost->post_title;
            
      $output = wp_list_pages('sort_column=menu_order'.$depthStr.'&title_li=&echo=0&child_of='.$rootPost->ID);
      
      // add the parent page if the parent page should not be used
      // and the parent page should be added
      // and the page has an parent page.  
      if (!$useRoot and $addParent and $rootPost->post_parent != 0)
    		{
    		   $parentPage = &get_post($rootPost->post_parent);
    		   $parentTitle = $parentPage->post_title;
    		   // do we add the parent page at the top or the bottom.
    		   if ($parentPosition=='top')
    		   {
    		    $output = '<li><a href="'.get_permalink($rootPost->post_parent).'">'.$parentIcon.'&nbsp;'.$parentTitle.'</a></li>'.$output;
    		   }
    		   else
    		   {
    		    $output = $output.'<li><a href="'.get_permalink($rootPost->post_parent).'">'.$parentIcon.'&nbsp;'.$parentTitle.'</a></li>';
           }    		  
        }  
            
      if (!empty($output))
      {
    		// These lines generate our output. Widgets can be very complex
    		// but as you can see here, they can also be very, very simple.
    		echo $before_widget . $before_title;    		
        echo '<a href="'.get_permalink($rootPost->ID).'">'.$title.'</a>'.$after_title;            		
    		echo '<ul>';
    		
        echo $output;
        echo '</ul>';		
    		echo $after_widget;
  		}
		}
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_subpages_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_subpages');
		if ( !is_array($options) )
		{
			$options = array('title'=>'', 'useRoot'=>1, 'onlyFirstLevel'=>0, 'parentIcon'=>'<=', 'parentPosition'=>'top');
		}
		
		if ( $_POST['subpages-submit'] ) {
			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['subpages-title']));
      $options['useRoot'] = isset($_POST['subpages-useRoot']);
      $options['onlyFirstLevel'] = isset($_POST['subpages-onlyFirstLevel']);
      $options['parentIcon'] = stripslashes($_POST['subpages-parentIcon']);
      $options['parentPosition'] = strip_tags(stripslashes($_POST['subpages-parentPosition']));
      $options['addParent'] = isset($_POST['subpages-addParent']);
			update_option('widget_subpages', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$useRoot = 'checked="checked"';
		$onlyFirstLevel='';
		$parentIcon = "<=";
		$parentPosition = 'top';
		$addParent = 'checked="checked"';
		
		if (isset($options['useRoot']))
    {
      $useRoot = $options['useRoot'] ? 'checked="checked"' : '';
    }
    
    if (isset($options['onlyFirstLevel']))
    {
      $onlyFirstLevel = $options['onlyFirstLevel'] ? 'checked="checked"' : '';
    }
    
    if (isset($options['parentIcon']))
    {
      $parentIcon = htmlspecialchars($options['parentIcon'], ENT_QUOTES);
    }
    
    if (isset($options['parentPosition']))
    {
      $parentPosition = htmlspecialchars($options['parentPosition'], ENT_QUOTES);
    }
    
    if (isset($options['addParent']))
    {
      $addParent = $options['addParent'] ? 'checked="checked"' : '';
    }
    
    $topSet = $parentPosition == 'top' ? 'checked="checked"' : '';
		$bottomSet = $parentPosition == 'bottom' ? 'checked="checked"' : '';
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="subpages-title">' . __('Title:') . ' <input style="width: 200px;" id="subpages-title" name="subpages-title" type="text" value="'.$title.'" /></label></p>';
    echo '<p style="text-align:right;margin-right:40px;"><label for="subpages-useRoot" style="text-align:right;">'.__('Use Root:').'<input class="checkbox" type="checkbox" '.$useRoot.' id="subpages-useRoot" name="subpages-useRoot" /></label></p>';		
    echo '<p style="text-align:right;margin-right:40px;"><label for="subpages-onlyFirstLevel" style="text-align:right;">'.__('First level only:').'<input class="checkbox" type="checkbox" '.$onlyFirstLevel.' id="subpages-onlyFirstLevel" name="subpages-onlyFirstLevel" /></label></p>';
    echo '<p style="text-align:right;margin-right:40px;"><label for="subpages-addParent" style="text-align:right;">'.__('Add Parent Page:').'<input class="checkbox" type="checkbox" '.$addParent.' id="subpages-addParent" name="subpages-addParent" /></label></p>';
    echo '<p style="text-align:right;"><label for="subpages-parentIcon">' . __('Parent Icon:') . ' <input style="width: 200px;" id="subpages-parentIcon" name="subpages-parentIcon" type="text" value="'.$parentIcon.'" /></label></p>';    
    echo '<p style="text-align:right;margin-right:40px;"><label for="subpages-parentPosition" style="text-align:right;">'.__('Position:').'<input type="radio" name="subpages-parentPosition" id="subpages-parentPosition-top" value="top"'.$topSet.'>&nbsp;Top&nbsp;&nbsp;<input type="radio" name="subpages-parentPosition" id="subpages-color-bottom" value="bottom"'.$bottomSet.'>&nbsp;Bottom&nbsp;</label></p>';
		echo '<input type="hidden" id="subpages-submit" name="subpages-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Sub Page Menu', 'widgets'), 'widget_subpages');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Sub Page Menu', 'widgets'), 'widget_subpages_control', 300, 190);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_subpages_init');

?>
