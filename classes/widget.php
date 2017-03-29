<?php
/**
 * Class Widget
 *
 * Widget for Twitter
 */
namespace whatsontap;

class Widget extends \WP_Widget {
    function __construct() {
        parent::__construct( 'Widget', __( 'What\'s On Tap?', FP_PLUGIN_PREFIX ),
            array( 'description' => __( 'Display your taplist', FP_PLUGIN_PREFIX ), )
        );

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
    }
    public function widget( $args, $instance ) {
        $taplist = apply_filters( 'widget_title', $instance['taplist'] );
        $beer_color = apply_filters( 'widget_title', $instance['beer_color'] );
        $brewery_color = apply_filters( 'widget_title', $instance['brewery_color'] );
        echo do_shortcode( '[whatsontap taplist="' . intval( $taplist ) .
            '" beer_color="' . esc_attr( $beer_color ) .
            '" brewery_color="' . esc_attr( $brewery_color ) . '"]' );
    }
    public function form( $instance ) {
        $taplist = ( isset( $instance['taplist'] ) ? $instance['taplist'] : '' );
        $beer_color = ( isset( $instance['beer_color'] ) ? $instance['beer_color'] : '' );
        $brewery_color = ( isset( $instance['brewery_color'] ) ? $instance['brewery_color'] : '' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'taplist' ); ?>"><?php _e( 'Taplist', WIC_TEXT_DOMAIN ); ?>:</label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'feed' ); ?>"
                   name="<?php echo $this->get_field_name( 'taplist' ); ?>">
                <?php
                $lists = get_taplists();
                foreach ( $lists as $list ) : ?>
                    <option value="<?php echo $list->id; ?>"<?php
                    if ( $taplist == $list->id ) : ?>
                        selected="selected"
                    <?php endif; ?>><?php echo $list->title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'beer_color' ); ?>"><?php _e( 'Beer Name Color', WIC_TEXT_DOMAIN ); ?>:</label>
            <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'beer_color' ); ?>"
                   name="<?php echo $this->get_field_name( 'beer_color' ); ?>" type="text" value="<?php echo esc_attr( $beer_color ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'brewery_color' ); ?>"><?php _e( 'Brewery Color', WIC_TEXT_DOMAIN ); ?>:</label>
            <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'brewery_color' ); ?>"
                   name="<?php echo $this->get_field_name( 'brewery_color' ); ?>" type="text" value="<?php echo esc_attr( $brewery_color ); ?>" />
        </p>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                jQuery('.color-picker').on('focus', function(){
                    var parent = jQuery(this).parent();
                    jQuery(this).wpColorPicker();
                    parent.find('.wp-color-result').click();
                });
            });
        </script>
        <?php
    }
    public function update( $new_instance, $old_instance ) {
        $instance          = array();
        $instance['taplist'] = ( ! empty( $new_instance['taplist'] ) ) ? strip_tags( $new_instance['taplist'] ) : '';
        $instance['beer_color'] = ( ! empty( $new_instance['beer_color'] ) ) ? strip_tags( $new_instance['beer_color'] ) : '';
        $instance['brewery_color'] = ( ! empty( $new_instance['brewery_color'] ) ) ? strip_tags( $new_instance['brewery_color'] ) : '';
        return $instance;
    }
}