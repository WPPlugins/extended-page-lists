<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * widget-form.php - View for the widget form.
 *
 * @package Extended Page Lists
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 */
?>

<p>
     <label for="<?php print $this->get_field_id('title'); ?>">
          <?php _e('Widget Title:<br /><small>Leave blank for no title.</small>', 'extended-page-lists'); ?>
     </label>
     <input class="widefat" id="<?php print $this->get_field_id('title'); ?>" name="<?php print $this->get_field_name('title'); ?>" type="text" value="<?php print $title; ?>" />
</p>
<p>
     <label for="<?php print $this->get_field_id('type'); ?>">
          <?php _e('Widget Type', 'extended-page-lists'); ?>
     </label>
     <select id="<?php print $this->get_field_id('type'); ?>" name="<?php print $this->get_field_name('type'); ?>">
          <option value="all" <?php selected($type, 'all'); ?>><?php _e('All Pages', 'extended-page-lists'); ?></option>
          <option value="toplevel" <?php selected($type, 'toplevel'); ?>><?php _e('Top Level Only', 'extended-page-lists'); ?></option>
          <option value="subs" <?php selected($type, 'subs'); ?>><?php _e('Sub Pages Only', 'extended-page-lists'); ?></option>
          <option value="substop" <?php selected($type, 'substop'); ?>><?php _e('Top Level Sub Pages Only', 'extended-page-lists'); ?></option>
          <option value="subortop" <?php selected($type, 'substop'); ?>><?php _e('Sub Pages or Top Level Pages', 'extended-page-lists'); ?></option>
          <option value="selected" <?php selected($type, 'selected'); ?>><?php _e('Selected Pages Only', 'extended-page-lists'); ?></option>
     </select>
</p>
<p>
     <label for="<?php echo $this->get_field_id('target'); ?>">
          <?php _e('Link Target:', 'extended-page-lists'); ?>
     </label>
     <select name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_id('target'); ?>">
          <option value="0">None</option>
          <option value="_blank" <?php selected($target == '_blank', 1); ?>>New Window</option>
          <option value="_top" <?php selected($target == '_top', 1); ?>>Top Window</option>
     </select>
</p>
<h3><?php _e('Page Selection', 'extended-page-lists'); ?></h3>
<p>
     <label for="<?php echo $this->get_field_id('exclude'); ?>">
          <?php _e('Exclude Pages:', 'extended-page-lists'); ?>
     </label>
     <select class="widefat" name="<?php echo $this->get_field_name('exclude'); ?>[]" size="1" multiple="multiple" style="height:100px;" id="<?php echo $this->get_field_id('exclude'); ?>">
          <option value="0">None</option>
          <?php $this->get_page_list($exclude); ?>
     </select>
</p>
<p>
     <label for="<?php echo $this->get_field_id('include'); ?>">
          <?php _e('Selected Pages:', 'extended-page-lists'); ?>
     </label>
     <select class="widefat" name="<?php print $this->get_field_name('include'); ?>[]" size="1" style="height:100px;" multiple="multiple" id="<?php print $this->get_field_id('include'); ?>">
          <option value="0">None</option>
          <?php $this->get_page_list($include); ?>
     </select>
</p>
