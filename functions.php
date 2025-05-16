/**
 * Return the ACF 'avatar' image instead of Gravatar—
 * for both get_avatar() and get_avatar_url(), plus handling post objects.
 */

// 1) Filter the full <img> tag
add_filter( 'get_avatar', 'my_acf_user_avatar', 10, 5 );
function my_acf_user_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    // Resolve a WP_User object no matter what was passed in
    $user = _my_acf_resolve_user( $id_or_email );
    if ( ! $user ) {
        return $avatar;
    }

    // Only proceed if ACF is active
    if ( ! function_exists( 'get_field' ) ) {
        return $avatar;
    }

    // Grab your ACF image ID
    $image_id = get_field( 'avatar', 'user_' . $user->ID );
    if ( $image_id ) {
        $custom_img = wp_get_attachment_image(
            $image_id,
            [ (int)$size, (int)$size ],
            false,
            [
                'alt'     => $alt,
                'class'   => "avatar avatar-{$size} photo",
                'loading' => 'lazy',
            ]
        );
        if ( $custom_img ) {
            return $custom_img;
        }
    }

    return $avatar;
}

// 2) Filter the raw URL
add_filter( 'get_avatar_url', 'my_acf_user_avatar_url', 10, 3 );
function my_acf_user_avatar_url( $url, $id_or_email, $args ) {
    $user = _my_acf_resolve_user( $id_or_email );
    if ( ! $user || ! function_exists( 'get_field' ) ) {
        return $url;
    }

    $image_id = get_field( 'avatar', 'user_' . $user->ID );
    if ( $image_id ) {
        // Use the requested size if set, or fall back
        $size = ! empty( $args['size'] ) ? $args['size'] : 96;
        return wp_get_attachment_image_url( $image_id, [ $size, $size ] );
    }

    return $url;
}

/**
 * Helper to turn whatever get_avatar()/get_avatar_url() gets passed
 * into a WP_User object (or return false).
 */
function _my_acf_resolve_user( $id_or_email ) {
    // Direct user ID
    if ( is_numeric( $id_or_email ) ) {
        return get_user_by( 'ID', absint( $id_or_email ) );
    }

    // WP_User object
    if ( $id_or_email instanceof WP_User ) {
        return $id_or_email;
    }

    // Comment object
    if ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        return get_user_by( 'ID', absint( $id_or_email->user_id ) );
    }

    // Post object (Elementor dynamic tags sometimes pass a post)
    if ( is_object( $id_or_email ) && ! empty( $id_or_email->post_author ) ) {
        return get_user_by( 'ID', absint( $id_or_email->post_author ) );
    }

    // Email string
    if ( is_string( $id_or_email ) && is_email( $id_or_email ) ) {
        return get_user_by( 'email', $id_or_email );
    }

    return false;
}
