<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?><form method="get" class="search-form" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<input autocomplete="off" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search here&hellip;', 'front-view', 'luxsa' ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'front-view', 'luxsa' ); ?>" />
	<button class="search-button" type="submit"><i class="lastudioicon-zoom-1"></i></button>
</form>
<!-- .search-form -->