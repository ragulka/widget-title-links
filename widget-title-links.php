<?php
/*
Plugin Name: Widget Title Links
Plugin URI: https://github.com/ragulka/widget-title-links
Description: Add links to Wordpress widget titles.
Version: 1.4.1
Author: Illimar Tambek
Author URI: https://github.com/ragulka
License: GPL2

    Copyright 2012  Illimar Tambek  (email : illimar.tambek@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Widget_Title_Links {

  public function __construct() {
    load_plugin_textdomain( 'widget-title-links', false, basename( dirname( __FILE__ ) ) . '/languages' );

    add_action( 'in_widget_form', array( $this, 'add_title_link_fields_to_widget_form' ), 1, 3 );
    
    add_filter( 'widget_form_callback', array( $this, 'register_widget_title_link_field'), 10, 2 );
    add_filter( 'widget_update_callback', array( $this, 'widget_update_extend'), 10, 2 );
    add_filter( 'dynamic_sidebar_params', array( $this, 'add_link_to_widget_title'), 99, 2 );
    
  }

  /**
   * Add Title Link field to widget form
   *
   * @since 1.0
   * @uses add_action() 'in_widget_form'
   */
  public function add_title_link_fields_to_widget_form( $widget, $args, $instance ) {
  ?>
    <fieldset class="title-link-options">
      <p><label for="<?php echo $widget->get_field_id('title_link'); ?>"><?php _e('Title link <small class="description">(Example: http://google.com)</small>', 'widget-title-links'); ?></label>
      <input type="text" name="<?php echo $widget->get_field_name('title_link'); ?>" id="<?php echo $widget->get_field_id('title_link'); ?>"" class="widefat" value="<?php echo $instance['title_link']; ?>"" /></p>
      
      <p>
        <input type="checkbox" class="checkbox" id="<?php echo $widget->get_field_id('title_link_target_blank'); ?>" name="<?php echo $widget->get_field_name('title_link_target_blank'); ?>"<?php checked( $instance['title_link_target_blank'] ); ?> />
        <label for="<?php echo $widget->get_field_id('title_link_target_blank'); ?>"><?php _e( 'Open link in new window/tab', 'widget-title-links' ); ?></label>
      </p>
      
      <p>
        <input type="checkbox" class="checkbox" id="<?php echo $widget->get_field_id('title_link_wrap'); ?>" name="<?php echo $widget->get_field_name('title_link_wrap'); ?>"<?php checked( $instance['title_link_wrap'] ); ?> />
        <label for="<?php echo $widget->get_field_id('title_link_wrap'); ?>"><?php _e( 'Make the entire title bar clickable', 'widget-title-links' ); ?></label>
      </p>
    </fieldset>
  <?php
  }

  /**
   * Register the additional widget field
   *
   * @since 1.0
   * @uses add_filter() 'widget_form_callback'
   */
  public function register_widget_title_link_field ( $instance, $widget ) {
    if ( !isset($instance['title_link']) )
      $instance['title_link'] = null;
    if ( !isset($instance['title_link_target_blank']) )
      $instance['title_link_target_blank'] = null;    
    if ( !isset($instance['title_link_wrap']) )
      $instance['title_link_wrap'] = null;    
    return $instance;
  }

  /**
   * Add the additional field to widget update callback
   *
   * @since 1.0
   * @uses add_filter() 'widget_update_callback'
   */
  public function widget_update_extend ( $instance, $new_instance ) {
    $instance['title_link'] = esc_url( $new_instance['title_link'] );
    $instance['title_link_target_blank'] = !empty($new_instance['title_link_target_blank']) ? 1 : 0;
    $instance['title_link_wrap'] = !empty($new_instance['title_link_wrap']) ? 1 : 0;
    return $instance;
  }

  /**
   * Add link to widget title on output
   *
   * Title link should be set by editors 
   * in widget settings in Appearance->Widgets
   *
   * @since 1.o
   * @uses add_filter() 'dynamic_sidebar_params'
   */
  public function add_link_to_widget_title( $params ) {
    if (is_admin())
      return $params;

    global $wp_registered_widgets;
    $id = $params[0]['widget_id'];

    if (isset($wp_registered_widgets[$id]['callback'][0]) && is_object($wp_registered_widgets[$id]['callback'][0])) {
      // Get settings for all widgets of this type
      $settings = $wp_registered_widgets[$id]['callback'][0]->get_settings();

      // Get settings for this instance of the widget
      $instance = $settings[substr( $id, strrpos( $id, '-' ) + 1 )];

      // Allow overriding the title link programmatically via filters
      $link = isset($instance['title_link']) ? $instance['title_link'] : null;
      $link = apply_filters('widget_title_link', $link, $instance);

      if ( $link ) {
        $target = $instance['title_link_target_blank'] ? ' target="_blank"' : '';

        // Wrap everything before_title inside the link, if wrap mode is on
        if ( isset($instance['title_link_wrap']) && $instance['title_link_wrap'] ) {
          $params[0]['before_title'] = '<a href="' . $link . '"' . $target . '>' . $params[0]['before_title'];
          $params[0]['after_title']  = $params[0]['after_title'] . '</a>';
        }

        // Otherwise, only wrap the actual title
        else {
          $params[0]['before_title'] = $params[0]['before_title'] . '<a href="' . $link . '"' . $target . '>';
          $params[0]['after_title']  = '</a>' . $params[0]['after_title'];
        }

      }
    }

    return $params;
  }
}

new Widget_Title_Links();
