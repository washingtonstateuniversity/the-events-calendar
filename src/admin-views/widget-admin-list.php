<?php
/**
 * Widget admin for the event list widget.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

?>
<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'the-events-calendar' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Show:', 'the-events-calendar' ); ?></label>
	<select id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" class="widefat">
		<?php for ( $i = 1; $i <= 10; $i ++ ) {
			?>
			<option <?php if ( $i == $instance['limit'] ) {
				echo 'selected="selected"';
			} ?> > <?php echo $i; ?> </option>
		<?php } ?>
	</select>
</p>
<label for="<?php echo esc_attr( $this->get_field_id( 'no_upcoming_events' ) ); ?>"><?php esc_html_e( 'Show widget only if there are upcoming events:', 'the-events-calendar' ); ?></label>
<input id="<?php echo esc_attr( $this->get_field_id( 'no_upcoming_events' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'no_upcoming_events' ) ); ?>" type="checkbox" <?php checked( $instance['no_upcoming_events'], 1 ); ?> value="1" />
<p>
	<?php $jsonld_enable = ( isset( $instance['jsonld_enable'] ) && $instance['jsonld_enable'] ) || false === $this->updated; ?>
	<input class="checkbox" type="checkbox" value="1" <?php checked( $jsonld_enable, '1' ); ?>
	       id="<?php echo esc_attr( $this->get_field_id( 'jsonld_enabled' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'jsonld_enable' ) ); ?>"/>
	<label for="<?php echo esc_attr( $this->get_field_id( 'jsonld_enabled' ) ); ?>"><?php esc_html_e( 'Generate JSON-LD data', 'the-events-calendar' ); ?></label>
	<br />
</p>

<p>

</p>
