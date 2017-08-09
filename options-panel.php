<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * options-panel.php - View for the options panel.
 *
 * @package Extended Page Lists
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 */
?>

<div class="wrap">
     <div class="icon32" id="icon-edit-pages"><br/>
     </div>
     <h2><?php print $this->pluginName; ?> &raquo; <?php _e('Default Settings', 'extended-page-lists'); ?></h2>
     <form method="post" action="options.php">
          <?php settings_fields($this->optionsName); ?>
          <div  style="width:49%; float:left">
               <div class="postbox">
                    <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                         <?php _e('Default Settings', 'extended-page-lists'); ?>
                    </h3>
                    <div class="table">
                         <table class="form-table">
                              <tr align="top">
                                   <th valign="top" scope="row"><?php _e('Default Link Target', 'extended-page-lists'); ?></th>
                                   <td valign="top">
                                        <select name="<?php print $this->optionsName; ?>[target]" id="epl_target">
                                             <option value="0">No Target</option>
                                             <option value="_blank" <?php selected($this->options['target'], '_blank'); ?>>New Window</option>
                                             <option value="_top" <?php selected($this->options['target'], '_top'); ?>>Top Window</option>
                                        </select>
                                   </td>
                              </tr>
                              <tr align="top">
                                   <th valign="top" scope="row"><?php _e('Default Widget Target', 'extended-page-lists'); ?></th>
                                   <td valign="top"><select name="<?php print $this->optionsName; ?>[widget-target]" id="epl_widget_target">
                                             <option value="0">No Target</option>
                                             <option value="_blank" <?php selected($this->options['widget-target'], '_blank'); ?>>New Window</option>
                                             <option value="_top" <?php selected($this->options['widget-target'], '_top'); ?>>Top Window</option>
                                        </select></td>
                              </tr>
                              <tr align="top">
                                   <th valign="top" scope="row"><?php _e('Default Excerpt Length', 'extended-page-lists'); ?></th>
                                   <td valign="top"><input name="<?php print $this->optionsName; ?>[excerpt-length]" type="text" id="epl_excerpt-length" value="<?php print $this->options['excerpt-length']; ?>" /></td>
                              </tr>
                              <tr align="top">
                                   <th valign="top" scope="row"><?php _e('Default More Text', 'extended-page-lists'); ?></th>
                                   <td valign="top"><input name="<?php print $this->optionsName; ?>[excerpt-more]" type="text" id="epl_excerpt_more" value="<?php print $this->options['excerpt-more']; ?>" />
                                        <br />
                                        <input name="<?php print $this->optionsName; ?>[more-link]" type="checkbox" id="epl_excerpt_more_link" value="1" <?php checked($this->options['more-link'], 1); ?> />
                                        <label for="epl_excerpt_more_link">
                                             <?php _e('Link to Page?', 'extended-page-lists'); ?>
                                        </label></td>
                              </tr>
                         </table>
                         <input type="hidden" name="action" value="update" />
                         <?php if ( function_exists('wpmu_create_blog') ) : ?>
                                                  <input type="hidden" name="option_page" value="<?php print $this->optionsName; ?>" />
                         <?php else : ?>
                                                       <input type="hidden" name="page_options" value="<?php print $this->optionsName; ?>" />
                         <?php endif; ?>
                                                       <p class="submit" align="center">
                                                            <input type="submit" name="Submit" value="<?php _e('Save Settings', 'extended-page-lists'); ?>" />
                                                       </p>
                                                  </div>
                                             </div>
                                        </div>
                                   </form>
                                   <div  style="width:49%; float:right">
                                        <div class="postbox">
                                             <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('About', 'extended-page-lists'); ?></h3>
                                             <div style="padding:5px;">
                                                  <p><?php _e('This plugin allows you to build customized page lists on your posts and pages using simple short codes. A widget is included to display page navigation links in your sidebars with a variety of options.', 'extended-page-lists'); ?></p>
                                                  <p><span><?php _e('You are using', 'extended-page-lists'); ?> <strong> <a href="http://plugins.grandslambert.com/plugins/extended-page-lists.html" target="_blank"><?php print $this->pluginName; ?> <?php print $this->version; ?></a></strong> by <a href="http://grandslambert.com" target="_blank">GrandSlambert</a>.</span> </p>
                                             </div>
                                        </div>
                                        <div class="postbox">
                                             <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Shortcode Usage', 'extended-page-lists'); ?></h3>
                                             <div style="padding:5px;">
                                                  <p><strong>Shortcode: </strong>To list all subpages of an active page, add the shortcode <strong>[epl]</strong> where you want the list to appear on the page. By default, this will create an unordered &lt;ul&gt; list of all subpages. This shortcode supports a number of parameters as listed on the <a href="http://docs.grandslambert.com/wiki/Extended_Page_Lists#Shortcode_Usage" target="_blank">Shortcode Instructions</a> page.</p>
                                             </div>
                                        </div>
                                        <div class="postbox">
                                             <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Recent Contributors', 'extended-page-lists'); ?></h3>
                                             <div style="padding:5px;">
                                                  <p><?php _e('GrandSlambert would like to thank these wonderful contributors to this plugin!', 'extended-page-lists'); ?></p>
                    <?php $this->contributor_list(); ?>
                                                  </div>
                                             </div>
                                        </div>
                                        <div style="clear:both; margin-top:10px;"></div>
                                        <div class="postbox" style="width:49%; height: 175px; float:left;">
                                             <h3 class="handl" style="margin:0; padding:3px"><?php _e('Credits', 'extended-page-lists'); ?></h3>
                                             <div style="padding:8px;">
                                                  <p><?php
                                                       printf(__('Thank you for trying the %1$s plugin - I hope you find it useful. For the latest updates on this plugin, visit the %2$s. If you have any questions or problems with this plugin, please use our %3$s. As always, any comments or suggestions for improvements are welcome!', 'extended-page-lists'),
                                                               $this->pluginName,
                                                               '<a href="http://plugins.grandslambert.com/plugins/extended-page-lists.html" target="_blank">' . __('official site', 'extended-page-lists') . '</a>',
                                                               '<a href="http://support.grandslambert.com/forum/extended-page-lists" target="_blank">' . __('Support Forum', 'extended-page-lists') . '</a>'
                                                       );
                    ?>
                                                  </p>
                                                  <p>
                    <?php
                                                       printf(__('This plugin is &copy;2009-%1$d by %2$s and is released under the %3$s.'),
                                                               date('Y'),
                                                               '<a href="http://grandslambert.com" target="_blank">GrandSlambert, Inc.</a>',
                                                               '<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">' . __('GNU General Public License', 'extended-page-lists') . '</a>'
                                                       );
                    ?>
                                                  </p>
                                             </div>
                                        </div>
                                        <div class="postbox" style="width:49%; height: 175px; float:right;">
                                             <h3 class="handl" style="margin:0; padding:3px"><?php _e('Donate', 'extended-page-lists'); ?></h3>
          <div style="padding:8px">
               <p>If you find this plugin useful, please consider supporting our work and the development of  other great <a href="http://plugins.grandslambert.com/" target="_blank">plugins</a>. <a href="http://plugins.grandslambert.com/extended-page-lists-donate" target="_blank">Donate</a> a few bucks and see what else we can come up with!</p>
               <p style="text-align: center;"><a target="_blank" href="http://plugins.grandslambert.com/extended-page-lists-donate"><img width="122" height="47" alt="paypal_btn_donateCC_LG" src="http://grandslambert.com/files/2010/06/btn_donateCC_LG.gif" title="paypal_btn_donateCC_LG" class="aligncenter size-full wp-image-174"/></a></p>
          </div>
     </div>
</div>
