<?php

class milly_keep_in_touch_widget extends WP_Widget {
  function milly_keep_in_touch_widget() {
    $milly_keep_in_touch_widget_name = __('Keep In Touch Widget');
    $milly_keep_in_touch_widget_description = __('Displays Methods of Keeping in touch such as via Twitter, Facebook, RSS & Atom.');
    $widget_ops = array('classname' => 'milly_keep_in_touch_widget', 'description' => $milly_keep_in_touch_widget_description );
    $this->WP_Widget('milly_keep_in_touch_widget', $milly_keep_in_touch_widget_name, $widget_ops);
  }

  function widget($args, $instance) {
    extract($args);
    $widget_title = strip_tags($instance['widget_title']);
    $twitter_id = strip_tags($instance['twitter_id']);
    $facebook_id = strip_tags($instance['facebook_id']);
    echo "{$before_widget}{$before_title}$widget_title{$after_title}<ul class='keep-in-touch'>";
    echo "<li id='twitter'><a href='http://twitter.com/$twitter_id'>Twitter</a></li>";
    echo "<li id='facebook'><a href='http://facebook.com/$facebook_id'>Facebook</a></li>";
    echo "<li id=feed><a href='" .get_bloginfo_rss('atom_url'). "'>ATOM</a> |<a href='" .get_bloginfo_rss('rss2_url'). "'>RSS</a> Post Feeds</li>";
    echo "<li id=feed><a href='" .get_bloginfo_rss('comments_atom_url'). "'>ATOM</a> |<a href='" .get_bloginfo_rss('comments_rss2_url'). "'>RSS</a> Comments</li>";
    echo "</ul>";
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['widget_title'] = strip_tags($new_instance['widget_title']);
    $instance['twitter_id'] = strip_tags($new_instance['twitter_id']);
    $instance['facebook_id'] = strip_tags($new_instance['facebook_id']);
    return $instance;
  }

  function form($instance) {
    $widget_title = strip_tags($instance['widget_title']);
    $twitter_id = strip_tags($instance['twitter_id']);
    $facebook_id = strip_tags($instance['facebook_id']);
    ?><p><label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php _e('Widget Title')?>:<input class="widefat" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo esc_attr($widget_title); ?>" /></label></p><?php
    ?><p><label for="<?php echo $this->get_field_id('twitter_id'); ?>"><?php _e('Twitter ID')?>:<input class="widefat" id="<?php echo $this->get_field_id('twitter_id'); ?>" name="<?php echo $this->get_field_name('twitter_id'); ?>" type="text" value="<?php echo esc_attr($twitter_id); ?>" /></label></p><?php
    ?><p><label for="<?php echo $this->get_field_id('facebook_id'); ?>"><?php _e('Facebook ID')?>:<input class="widefat" id="<?php echo $this->get_field_id('facebook_id'); ?>" name="<?php echo $this->get_field_name('facebook_id'); ?>" type="text" value="<?php echo esc_attr($facebook_id); ?>" /></label></p><?php
  }
}

add_action('widgets_init', 'milly_keep_in_touch_widget_init');
function milly_keep_in_touch_widget_init() {
        register_widget('milly_keep_in_touch_widget');
}

?>

