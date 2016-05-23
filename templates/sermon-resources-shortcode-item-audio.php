<?php if ( $audio_player = wp_audio_shortcode( array( 'src' => esc_url( $this->get( 'file' ) ) ) ) ) : ?>
	<div class="audio-wrap"><?php echo $audio_player; ?></div><!-- .audio-wrap -->
<?php endif; ?>

