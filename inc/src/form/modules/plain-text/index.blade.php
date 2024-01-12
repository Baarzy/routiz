@include('label.index')

{!! do_shortcode( wpautop( wp_kses_post( html_entity_decode( $content ) ) ) ) !!}
