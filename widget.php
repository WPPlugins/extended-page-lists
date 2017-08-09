<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * widget.php - Code for sidebar widget.
 *
 * @package Extended Page Lists
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 */

/* Class Declaration */
class ExtendedPageListWidget extends WP_Widget {
// Settings
    var $options = array();
    var $levels = array();

    /**
     * Constructor
     */
    function ExtendedPageListWidget() {
        $widget_ops = array('description' => __('Customized page lists widget. By GrandSlambert.', 'extended-page-lists') );
        $this->WP_Widget('extended_page_list_widget', __('Extended Page List', 'extended-page-lists'), $widget_ops);

        $this->pluginPath = WP_CONTENT_DIR . '/plugins/' . plugin_basename(dirname(__FILE__));

        // Get stored settings
        $this->options = get_option('extended-page-lists-options');

        // Build page level array
        $this->get_page_levels();
    }

    /**
     * Widget code
     */
    function widget($args, $instance) {
        global $post;

        if ( isset($instance['error']) && $instance['error'] ) {
            return;
        }

        $defaults = array(
            'child_of'      => $post->ID,
            'sort_column'   => 'post_title',
            'sort_order'    => 'ASC',
            'exclude'       => false,
            'include'       => false,
            'meta_key'      => NULL,
            'meta_value'    => NULL,
            'authors'       => NULL,
        );

        $instance = wp_parse_args($instance, $defaults);

        extract($args, EXTR_SKIP);

        $params = '';

        if (!$child_of = $instance['child_of']) {
            $child_of = $post->ID;
        }

        if (!$sort_column = $instance['sort_column']) {
            $sort_column = 'post_title';
        }

        if (!$sort_order = $instance['sort_order']) {
            $sort_order = 'ASC';
        }

        if ( is_array ($instance['exclude']) ) {
            $params.= '&exclude=' . implode(',', $instance['exclude']);
        }

        if ( isset($instance['meta_key']) ) {
            $params.= '&meta_key=' . $instance['meta_key'];
        }

        if ( isset($instance['meta_value']) ) {
            $params.= '&meta_value=' . $instance['meta_value'];
        }

        if ( isset($instance['authors']) ) {
            $params.= '&authors=' . $instance['authors'];
        }

        switch ($instance['type']) {
            case 'all':
                $pre = '';
                break;
            case 'toplevel':
                $pre = 'parent=' . $parent;
                break;
            case 'subortop':
            case 'subs':
                $pre = 'child_of=' . $post->ID;
                break;
            case 'substop':
                $pre = 'child_of=' . $post->ID . '&parent=' . $post->ID;
                break;
            case 'selected':
                if ( !is_array($instance['include']) )
                    return;
                $pre = 'include=' . implode(',',$instance['include']);
                break;
        }

        // Get the pages
        $pages = get_pages($pre . '&sort_column='. $sort_column . '&sort_order=' . $sort_order . $params);

        if ($instance['type'] == 'subortop' and count($pages) < 1) {
            $pages = get_pages('parent=0&sort_column='. $sort_column . '&sort_order=' . $sort_order . $params);
        } elseif (eregi('subs', $instance['type']) and count($pages) < 1) {
            return;
        }

        if (!$title = $instance['title']) {
            $title = $instance['name'];
        }

        if (!$target = $instance['target']) {
            $target = $this->options['widget-target'];
        }

        if ($target) {
            $target = 'target="' . $target . '"';
        }

        $title = apply_filters('widget_title', $title );

        print $before_widget;
        if ( $title ) {
            print $before_title;

            if (isset($url)) {
                print $this->makelink($url, $title, $target);
            } else {
                print $title;
            }

            print $after_title;
        }
        print '<ul class="extended-page-widget-list">';
        $lastlevel = 0;
        $close = '';

        foreach ($pages as $page) {
            if ($this->levels[$page->ID] > $lastlevel) {
                print '<ul>';
                $close = '';
            }

            if ($this->levels[$page->ID] < $lastlevel)
                print '</ul>';

            if ($page->ID == $post->ID)
                $active = 'current_page_item';
            else
                $active = '';

            print $close . '<li class="extended-page-widget-item ' . $active . '"><a href="' . get_page_link($page->ID) . '" ' . $target . '>' . $page->post_title . "</a>\n";
            $close = '</li>';
            $lastlevel = $this->levels[$page->ID];
        }

        for ($ctr = $lastlevel + 1; $ctr > 0; --$ctr)
            print '</li></ul>';
        print $after_widget;
    }

    function makelink($url, $text, $target = false) {
        $output = '<a href="' . $url . '" ';
        if ($target) $output.= 'target="' . $target . '"';
        $output.= '>' . $text . '</a>';

        return $output;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        if ( count($instance) > 1 ) {
            $title      = esc_attr($instance['title']);
            $target     = esc_attr($instance['target']);
            $type       = esc_attr($instance['type']);
            $exclude    = isset($instance['exclude']) ? $instance['exclude'] : false;
            $include    = isset($instance['include']) ? $instance['include'] : false;
        } else {
            $title      = '';
            $target     = $this->options['widget-target'];
            $type       = 'all';
            $exclude    = false;
            $include    = false;
        }

        include( $this->pluginPath . '/widget-form.php');
    }

    /**
     * Build the page level array
     */
    function get_page_levels($sort_column = "menu_order", $sort_order = "ASC", $parent = 0, $level = 0) {
        global $wpdb;
        $pages = $wpdb->get_results( "SELECT ID, post_parent, post_title FROM $wpdb->posts WHERE post_parent = $parent AND post_type = 'page' AND post_status = 'publish' ORDER BY {$sort_column} {$sort_order}" );

        if ( $pages ) {
            foreach ( $pages as $page ) {
                $this->levels[$page->ID] = $level;
                $this->get_page_levels( $sort_column, $sort_order, $page->ID,  $level +1 );
            }
        }
    }

    /**
     * Get list of pages for select box
     */
    function get_page_list ($selected = array(), $sort_column = "menu_order", $sort_order = "ASC", $parent = 0, $level = 0) {
        global $wpdb;

        $pages = $wpdb->get_results( "SELECT ID, post_parent, post_title FROM $wpdb->posts WHERE post_parent = $parent AND post_type = 'page' AND post_status = 'publish' ORDER BY {$sort_column} {$sort_order}" );

        if ( $pages ) {
            foreach ( $pages as $page ) {
                $pad = str_repeat( '&nbsp;', $level * 3 );

                if ( in_array($page->ID, $selected))
                    $current = ' selected="selected"';
                else
                    $current = '';

                print "\n\t<option value='$page->ID'$current>$pad $page->post_title</option>";
                $this->get_page_list($selected, $sort_column, $sort_order, $page->ID,  $level +1 );
            }
        }
        else {
            return false;
        }
    }
}

add_action('widgets_init', create_function('', 'return register_widget("ExtendedPageListWidget");'));