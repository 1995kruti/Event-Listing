<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://https://github.com/1995kruti
 * @since      1.0.0
 *
 * @package    Event_Listing
 * @subpackage Event_Listing/admin/partials
 */

/** This file should primarily consist of HTML with a little bit of PHP. **/
 $event_object            = new Event_Listing();
 $text_domain           = $event_object->get_plugin_name();
 $events_options           = $event_object->event_get_options(); 
 $all_post_type = $event_object->events_get_all_post_type();
 
 $default_shortcode     = '[event_listing]';

 if(empty($events_options['event_date'])){ $events_options['event_date'] = ''; }
 if(empty($events_options['event_location'])){ $events_options['event_location'] = ''; }
 if(empty($events_options['event_fees'])){ $events_options['event_fees'] = '0.0'; }

 ?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="event_listing_admin" class="wrap">
        <?php wp_nonce_field('eventlisting_cpt_nonce','eventlisting_cpt_nonce' ); ?>
        <?php if(isset($_GET["update-status"])): ?>
            <div class="notice notice-success is-dismissible"><p><?php _e('Settings save successfully!'); ?>.</p></div>
        <?php endif; ?>
        <div class="aps_row">
            <div class="aps_row_name">Event Fees</div>
            <div class="aps_row_desc"><input type="number" maxlength="2" min="-1" name="event_fees" id="event_fees" value="<?php esc_attr_e($events_options['event_fees'],$text_domain); ?>" /></div>
        </div>
        <div class="aps_row">
            <div class="aps_row_name">Event Date</div>
            <div class="aps_row_desc"><input name="event_date" type="date" id="event_date" value="<?php esc_attr_e($events_options['event_date'],$text_domain); ?>"/></div>
        </div>
        <div class="aps_row">
            <div class="aps_row_name">Event Location</div>
            <div class="aps_row_desc"><input name="event_location" type="name" id="event_location" value="<?php esc_attr_e($events_options['event_location'],$text_domain); ?>"/></div>
        </div>
</div>
