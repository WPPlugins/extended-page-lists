<?php

/*
  Plugin Name: Extended Page Lists
  Plugin URI: http://plugins.grandslambert.com/plugins/extended-page-lists.html
  Description: Add custom configured page lists to your posts, pages and sidebars.
  Version: 1.0
  Author: grandslambert
  Author URI: http://wordpress.grandslambert.com/

 * *************************************************************************

  Copyright (C) 2009-2011 GrandSlambert

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 * *************************************************************************

 */

class ExtendedPageLists {
     /* Plugin Settings */

     var $version = '1.0';
     var $optionsName = "extended-page-lists-options";
     var $menuName = "extended-page-lists-options";
     var $pluginName = 'Extended Page Lists';
     var $options = array();
     var $in_short_code = false;

     /**
      * Class Constructor
      */
     function ExtendedPageLists() {
          /* Load Langague Files */
          $langDir = dirname(plugin_basename(__FILE__)) . '/lang';
          load_plugin_textdomain('extended-page-lists', false, $langDir, $langDir);

          $this->pluginPath = WP_CONTENT_DIR . "/plugins/" . basename(dirname(__FILE__));
          $this->pluginName = __('Extended Page Lists', 'extended-page-lists');
          $this->load_settings();

          /* Add Shortcodes */
          add_shortcode('epl', array($this, 'list_pages_shortcode'));

          /* Add Options Pages and Links */
          add_action('admin_menu', array(&$this, "admin_menu"));
          add_filter('plugin_action_links', array(&$this, "plugin_action_links"), 10, 2);
          add_action('admin_init', array(&$this, 'admin_init'));
     }

     /**
      * Load plugin settings
      */
     function load_settings() {
          $defaults = array(
               'target' => false,
               'widget-target' => false,
               'excerpt-length' => 55,
               'excerpt-more' => '...',
               'more-link' => false,
               'post-types' => array('page'),
          );

          $this->options = wp_parse_args(get_option($this->optionsName), $defaults);
     }

     /**
      * Outputs the options sub panel
      */
     function settings() {
          include($this->pluginPath . "/options-panel.php");
     }

     /**
      * Adds Disclaimer options tab to admin menu
      */
     function admin_menu() {
          global $wp_version;

          add_options_page($this->pluginName, $this->pluginName, 'activate_plugins', $this->menuName, array(&$this, 'settings'));

          // Use the bundled jquery library if we are running WP 2.5 or above
          if ( version_compare($wp_version, "2.5", ">=") ) {
               wp_enqueue_script("jquery", false, false, "1.2.3");
          }
     }

     /**
      * Adds a settings link next to Login Configurator on the plugins page
      */
     function plugin_action_links($links, $file) {
          static $this_plugin;

          if ( !$this_plugin ) {
               $this_plugin = plugin_basename(__FILE__);
          }

          if ( $file == $this_plugin ) {
               $settings_link = '<a href="' . get_option('siteurl') . '/wp-admin/options-general.php?page=' . $this->menuName . '">' . __('Settings', 'extended-page-lists') . '</a>';
               array_unshift($links, $settings_link);
          }

          return $links;
     }

     /**
      * Get shortcode defaults.
      *
      * @global <object> $post
      * @param <array> $atts
      * @return <array>
      */
     function get_defaults($atts) {
          global $post;

          $new_atts = shortcode_atts(array(
               'post_type' => 'page',
               'post_capability' => 'page',
               // Page Query Settings
               'depth' => 0,
               'show_date' => false,
               'date_format' => get_option('date_format'),
               'child_of' => $post->ID,
               'exclude' => NULL,
               'include' => NULL,
               'title_li' => __('Pages', 'extended-page-lists'),
               'echo' => 1,
               'authors' => NULL,
               'sort_column' => 'menu_order, post_title',
               'link_before' => NULL,
               'link_after' => NULL,
               'walker' => NULL,
               // Post Query Settings
               'numberposts' => 5,
               'offset' => 0,
               'category' => NULL,
               'orderby' => 'post_date',
               'order' => 'DESC',
               'include' => NULL,
               'exclude' => NULL,
               'meta_key' => NULL,
               'meta_value' => NULL,
               'post_mime_type' => NULL,
               'post_parent' => NULL,
               'post_status' => 'publish',
               // Display Settings
               'block_tag' => 'ul',
               'block_class' => 'page-list',
               'item_tag' => 'li',
               'item_class' => 'page-list-item',
               'title_tag' => 'span',
               'title_class' => 'page-list-title',
               'target' => $this->options['target'],
               'show_content' => false,
               'content_tag' => 'span',
               'content_class' => 'page-list-content',
               'show_excerpt' => false,
               'excerpt_tag' => 'span',
               'excerpt_class' => 'page-list-excerpt',
               'excerpt_length' => $this->options['excerpt-length'],
               'excerpt_more' => $this->options['excerpt-more'],
               'before_link' => NULL,
               'more_link' => $this->options['more-link'],
               'show_thumbnail' => false,
               'thumbnail_class' => 'alignleft',
               'thumbnail_size' => 'thumbnail',
                  ), $atts);

          foreach ($new_atts as $key=>$att) {
               $this->$key = $att;
          }

          return $new_atts;
     }

     /**
      * List Pages Short Code
      */
     function list_pages_shortcode($atts) {
          global $post;

          if ( $this->in_short_code ) {
               return;
          }

          $this->in_short_code = true;

          $this->shortcode_atts = $atts;
          $output = '';

          extract($this->get_defaults($atts));

          if ( is_int(intval($thumbnail_size)) ) {
               $size = $thumbnail_size;
               $thumbnail_size = array($size, $size);
          }

          switch ($post_capability) {
               case 'post':
                    $args = array(
                         'numberposts' => $numberposts,
                         'offset' => $offset,
                         'category' => $category,
                         'orderby' => $orderby,
                         'order' => $order,
                         'include' => $include,
                         'exclude' => $exclude,
                         'meta_key' => $meta_key,
                         'meta_value' => $meta_value,
                         'post_type' => $post_type,
                         'post_mime_type' => $post_mime_type,
                         'post_parent' => $post_parent,
                         'post_status' => $post_status
                    );
                    $pages = get_posts($args);
                    break;
               default:
                    add_filter('wp_list_pages', array(&$this, 'wp_list_pages'));

                    $args = array(
                         'depth' => $depth,
                         'show_date' => $show_date,
                         'date_format' => get_option('date_format'),
                         'child_of' => $child_of,
                         'exclude' => $exclude,
                         'include' => $include,
                         'title_li' => $title_li,
                         'echo' => false,
                         'authors' => $authors,
                         'sort_column' => $sort_column,
                         'meta_key' => $meta_key,
                         'meta_value' => $meta_value,
                         'link_before' => $link_before,
                         'link_after' => $link_after,
                         'walker' => $walker,
                    );

                    if ( $title_li == '' ) {
                         $output.= '<' . $block_tag . ' class="' . $block_class . '">';
                         $output.= wp_list_pages($args, $atts);
                         $output.= '</' . $block_tag . '>';
                    } else {
                         $output = wp_list_pages($args);
                    }

                    $this->in_short_code = false;
                    return $output;
                    break;
          }

          /* Display the posts */
          if ( $target ) {
               $target = ' target="' . $target . '"';
          } else {
               $target = '';
          }

          if ( $block_tag ) {
               $output.= '<' . $block_tag . ' class="' . $block_class . '">';
          }

          foreach ( $pages as $page ) {
               $pageLink = get_permalink($page->ID);

               if ( $item_tag )
                    $output.= '<' . $item_tag . ' id="' . $item_class . '-' . $page->ID . '" class="' . $item_class . ' ' . $item_class . '-' . $page->ID . '">';

               if ( $title_tag )
                    $output.= '<' . $title_tag . ' class="' . $title_class . ' ' . $title_class . '-' . $page->ID . '">';

               $output.= '<a href="' . $pageLink . '" ' . $target . '>' . $page->post_title . '</a>';

               if ( $title_tag ) {
                    $output.= '</' . $title_tag . '>';
               }

               if ( $show_content ) {
                    $output.= $this->show_content($page);
               }

               if ( $show_excerpt and !$show_content ) {
                    $output.= $this->show_excerpt($page);
               }

               if ( $item_tag ) {
                    $output.= '</' . $item_tag . '>';
               }
          }

          if ( $block_tag ) {
               $output.= '</' . $block_tag . '>';
          }

          $this->in_short_code = false;

          return $output;
     }

     /**
      * Filter the wp_list_pages output to add content or excerpt.
      *
      * @param <string> $output    The HTML output from the function.
      * @return <string>
      */
     function wp_list_pages($output) {

          //$this->debug($output);
          extract($this->get_defaults($this->shortcode_atts));
          $newoutput = '';
          $lines = explode("\n", $output);
          $postIDPattern = '/page-item-(?P<digit>\d+)/';

          foreach ( $lines as $line ) {
               $post_id = preg_match($postIDPattern, $line, $matches);
               if ( isset($matches['digit']) ) {
                    $postID = $matches['digit'];
                    $post = get_post($postID);

                    if ( $show_content ) {
                         $content = $this->show_content($post);
                    } elseif ( $show_excerpt ) {
                         $content = $this->show_excerpt($post);
                    }

                    if ( isset($content) ) {
                         if ( preg_match('/\<\/li\>/', $line) ) {
                              $line = preg_replace('/\<\/li\>/', $content . '</li>', $line);
                         } else {
                              $line.= $content;
                         }
                    }

                    /* Fix item tags */
                    $line = preg_replace('/\<ul\>/', '<' . $this->block_tag . ' class="' . $this->block_class . '">', $line);
                    $line = preg_replace('/\<\/ul\>/', '</' . $this->block_tag . '>', $line);
                    $line = preg_replace('/\<li/', '<' . $this->item_tag , $line);
                    $line = preg_replace('/\<\/li/', '</' . $this->item_tag, $line);
                    $line = preg_replace('/class="([A-Za-z0-9_-\s]+)"/', 'class="${1} ' . $this->item_class . '"', $line);
               }

               $newoutput.= $line;
          }

          return $newoutput;
     }

     /**
      * Function to show content in a list.
      *
      * @param <object> $post      The post obbject
      * @return string
      */
     function show_content($post) {
          extract($this->get_defaults($this->shortcode_atts));
          $output = '';

          if ( $content_tag ) {
               $output.= '<' . $content_tag . ' class="' . $content_class . '">';
          }

          if ( $show_thumbnail and has_post_thumbnail($post->ID) ) {
               $output.= '<span class="' . $thumbnail_class . '"><a href="' . get_permalink($post->ID) . '" ' . $target . '>' . get_the_post_thumbnail($post->ID, $thumbnail_size) . '</a></span>';
          }

          $content = $post->post_content;

          $output.= apply_filters('the_content', $content);

          if ( $content_tag )
               $output.= '</' . $content_tag . '>';

          return $output;
     }

     /**
      * Function to display the post excerpt.
      *
      * @param <object> $post      The post object.
      * @return string
      */
     function show_excerpt($post) {
          extract($this->get_defaults($this->shortcode_atts));
          $output = '';

          if ( $more_link ) {
               $excerptlink = $before_link . '<a href="' . $more_link . '" ' . $target . '>' . $excerpt_more . '</a>';
          } else {
               $excerptlink = $excerpt_more;
          }

          if ( $show_thumbnail and has_post_thumbnail($post->ID) ) {
               $output.= '<span class="' . $thumbnail_class . '"><a href="' . get_permalink($post->ID) . '" ' . $target . '>' . get_the_post_thumbnail($post->ID, $thumbnail_size) . '</a></span>';
          }

          if ( $excerpt_tag ) {
               $output.= '<' . $excerpt_tag . ' class="' . $excerpt_class . '">';
          }

          if ( !$excerpt = get_post_meta($post->ID, 'epl-text', true) ) {
               $excerpt = $this->trim_excerpt($post->post_content, $excerpt_length, $excerptlink);
          }

          $output.= apply_filters('the_excerpt', $excerpt);

          if ( $excerpt_tag ) {
               $output.= '</' . $excerpt_tag . '>';
          }

          return $output;
     }

     /**
      * Trim the excerpt
      */
     function trim_excerpt($text, $length = NULL, $more = NULL) {
          global $post;

          if ( !$length ) {
               $length = $this->options['excerpt-length'];
          }

          if ( !$more ) {
               $more = $this->options['excerpt-more'];
          }

          $text = apply_filters('the_content', $text);
          $text = str_replace(']]>', ']]&gt;
                  ', $text);
          $text = strip_tags($text, '<p>');
          $text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
          $words = explode(' ', $text, $length + 1);

          if ( count($words) > $length ) {
               array_pop($words);
               array_push($words, $more);
               $text = implode(' ', $words);
          }

          return $text;
     }

     /**
      * Register the options for Wordpress MU Support
      */
     function admin_init() {
          register_setting($this->optionsName, $this->optionsName);
     }

     /**
      * Display the list of contributors.
      * @return boolean
      */
     function contributor_list() {
          $this->showFields = array('NAME', 'LOCATION', 'COUNTRY');
          print '<ul>';

          $xml_parser = xml_parser_create();
          xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
          xml_set_element_handler($xml_parser, array($this, "start_element"), array($this, "end_element"));
          xml_set_character_data_handler($xml_parser, array($this, "character_data"));

          if ( !(@$fp = fopen('http://wordpress.grandslambert.com/xml/extended-page-lists/contributors.xml', "r")) ) {
               print __('There was an error getting the list. Try again later.', 'extended-page-lists');
               return;
          }

          while ($data = fread($fp, 4096)) {
               if ( !xml_parse($xml_parser, $data, feof($fp)) ) {
                    die(sprintf("XML error: %s at line %d",
                                    xml_error_string(xml_get_error_code($xml_parser)),
                                    xml_get_current_line_number($xml_parser)));
               }
          }

          xml_parser_free($xml_parser);
          print '</ul>';
     }

     /**
      * XML Start Element Procedure.
      */
     function start_element($parser, $name, $attrs) {
          if ( $name == 'NAME' ) {
               print '<li class="bbw-contributor">';
          } elseif ( $name == 'ITEM' ) {
               print '<br><span class="bbw-contributor_notes">' . __('Contributed', 'extended-page-lists') . ': ';
          }

          if ( $name == 'URL' ) {
               $this->make_link = true;
          }
     }

     /**
      * XML End Element Procedure.
      */
     function end_element($parser, $name) {
          if ( $name == 'ITEM' ) {
               print '</li>';
          } elseif ( $name == 'ITEM' ) {
               print '</span>';
          } elseif ( in_array($name, $this->showFields) ) {
               print ', ';
          }

          $this->make_link = false;
     }

     /**
      * XML Character Data Procedure.
      */
     function character_data($parser, $data) {
          if ( isset($this->make_link) ) {
               print '<a href="http://' . $data . '" target="_blank">' . $data . '</a>';
               $this->make_link = false;
          } else {
               print $data;
          }
     }

     function activate() {

          /* Compile old options into new options Array */
          $options = array(
               'epl_default_target' => 'target',
               'epl_widget_target' => 'widget-target',
               'epl_custom_css' => 'custom-css',
               'epl_excerpt_length' => 'excerpt-length',
               'epl_excerpt_more' => 'excerpt-more',
               'epl_excerpt_more_link' => 'more-link'
          );

          foreach ( $options as $old => $new ) {
               if ( $old_option = get_option($old) ) {
                    $this->options[$new] = $old_option;
                    delete_option($old);
               }
          }

          if ( !add_option($this->optionsName, $this->options) ) {
               update_option($this->optionsName, $this->options);
          }
     }

     /**
      * Displays any data sent in textareas.
      *
      * @param <type> $input
      */
     function debug($input) {
          $contents = func_get_args();

          foreach ( $contents as $content ) {
               print '<textarea style="width:49%; height:250px; float: left;">';
               print_r($content);
               print '</textarea>';
          }

          echo '<div style="clear: both"></div>';
     }
}

// Pre 2.6 Compatibility
if ( !defined('WP_CONTENT_DIR') ) {
     define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}

$EPLOBJ = new ExtendedPageLists;
require_once($EPLOBJ->pluginPath . '/widget.php');
register_activation_hook(__FILE__, array($EPLOBJ, 'activate'));
