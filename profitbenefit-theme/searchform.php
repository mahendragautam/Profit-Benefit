<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" class="search-input" placeholder="<?php echo esc_attr_x( 'Search articles...', 'placeholder', 'profitbenefit-theme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-btn">🔍</button>
</form>
