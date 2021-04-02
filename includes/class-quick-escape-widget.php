<?php

namespace QuickEscapeWidget\Widget;

class Quick_Escape_Widget extends \WP_Widget {
	/**
	 * Initialize the Quick Escape Widget.
	 */
	public function __construct() {
		$options = array(
			'classname'   => 'widget_quick_escape',
			'description' => __( 'A quick escape link.' ),
		);
		parent::__construct( 'quick-escape', __( 'Quick Escape', 'quick-escape-widget' ), $options );
	}

	/**
	 * Display Quick Escape widget content on the front-end.
	 *
	 * @param array $args     Display arguments.
	 * @param array $instance Widget instance settings.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title      = isset( $instance['title'] ) ? $instance['title'] : 'Quick Escape';
		$url        = isset( $instance['url'] ) ? $instance['url'] : 'https://google.com';
		$page_title = isset( $instance['page_title'] ) ? $instance['page_title'] : 'Google';

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<a href="<?php echo esc_url( $url ); ?>" class="js-quick-escape" data-page-title="<?php echo esc_attr( $page_title ); ?>"><?php echo esc_html( $title ); ?></a>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handle widget setting updates.
	 *
	 * @param array $new_instance New settings for this instance.
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated and sanitized settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']      = sanitize_text_field( $new_instance['title'] );
		$instance['url']        = esc_url( $new_instance['url'] );
		$instance['page_title'] = sanitize_text_field( $new_instance['page_title'] );

		// Store a _transient_ prefixed option to avoid complaints by the customizer that a non-widget
		// option is being stored. There's probably a nicer way to do this, but here we are.
		update_option( '_transient_qew_redirect_url', esc_url_raw( $new_instance['url'] ) );

		return $instance;
	}

	/**
	 * Output widget settings form.
	 *
	 * @param array $instance Current instance settings.
	 */
	public function form( $instance ) {
		$title      = isset( $instance['title'] ) ? $instance['title'] : 'Quick Escape';
		$url        = isset( $instance['url'] ) ? $instance['url'] : 'https://google.com';
		$page_title = isset( $instance['page_title'] ) ? $instance['page_title'] : 'Google';

		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_html_e( 'URL' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'page_title' ) ); ?>"><?php esc_html_e( 'Page Title', 'quick-escape-widget' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'page_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_title' ) ); ?>" type="text" value="<?php echo esc_attr( $page_title ); ?>" /></p>
		<?php
	}
}
