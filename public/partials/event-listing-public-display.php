<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://https://github.com/1995kruti
 * @since      1.0.0
 *
 * @package    Event_Listing
 * @subpackage Event_Listing/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
// prepare postdata array
$event_object              = new Event_Listing();
$text_domain             = $event_object->get_plugin_name();
$plugin_version          = $event_object->get_version();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$get_posts_data = get_posts(
    array(
        'post_type'      => 'event_listing',
        'posts_per_page' => 5,
        'order'          => 'DESC',
        'post_status'    => array('publish'),
        'paged'          => $paged
    )
);

$term_obj_list = get_terms( 'event_type', array(
    'hide_empty' => false,
) 
);
?>
<input type="hidden" name="current_page" class="current_event_page" value="<?php _e($paged); ?>">

<?php
if(!empty($term_obj_list) && isset($term_obj_list)):
    ?>
<div class="btn-wap">
    <?php
    foreach ($term_obj_list as $key => $value) :
    ?>
    <button class="btn success event_type_btn" data-event_type="<?php _e($value->slug); ?>"><?php _e($value->name); ?></button>
    <?php
    endforeach;
    ?>
</div>
<?php endif;

if(isset($get_posts_data) && !is_admin() && !empty($get_posts_data)):    
    ?>
    <div class="event_listing_block">
    <?php
    foreach($get_posts_data as $post_item_key => $post_item_val):
        ?>
        <div class="item">
            <div class="aps_main">
                <?php 
                
                if(has_post_thumbnail($post_item_val->ID)): 
                     _e(get_the_post_thumbnail( $post_item_val->ID, 'large' )); 
                else: ?>
                    <img src="<?php echo esc_url(plugins_url($text_domain).'/public/images/place_holder.jpg'); ?>" class=event-placeholder-img" alt="event-placeholder" style="height: auto; width:300px;"/>
                <?php endif; ?>
                <div class="aps_desc">
                    <a href="<?php echo esc_url(get_the_permalink($post_item_val->ID)); ?>">
                        <h3><?php esc_attr_e( $post_item_val->post_title); ?></h3>
                    </a>            
                    
                </div>
            </div>
        </div>
        <?php
    endforeach;
    ?>
    </div>
    <div class="pagination">
    <?php 
        $query = new WP_Query( array(
            'post_type'      => 'event_listing',
            'posts_per_page' => 5,
            'paged' => $paged
        ) );
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $query->max_num_pages,
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'text-domain' ) ),
            'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'text-domain' ) ),
            'add_args'     => false,
            'add_fragment' => '',
        ) );
    ?>
</div>
<?php
    wp_reset_postdata();
else:
    
    require(dirname(__FILE__) . '/event-no_content.php');
    
endif;
