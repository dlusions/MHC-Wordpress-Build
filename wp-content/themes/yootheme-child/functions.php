<?php
/**
 * Enqueue Custom Accessibility Script (Accessitree)
 */
function jcmh_enqueue_accessibility_scripts() {
    
    wp_enqueue_script(
        'jcmh-accessitree', // Unique handle for the script
        get_stylesheet_directory_uri() . '/js/accessitree.js', // Path to child theme /js/ folder
        array('jquery'), // Dependency: Ensures jQuery loads BEFORE this script
        '1.0.0', // Version number (update this if you make changes to bust cache)
        true // Load in footer (true) for better performance and DOM access
    );
    
}
add_action('wp_enqueue_scripts', 'jcmh_enqueue_accessibility_scripts');

// --------------------------
// Disable Gutenberg Editor
// --------------------------
add_filter('use_block_editor_for_post', '__return_false');

// --------------------------
// Disable Block Styles (Frontend)
// --------------------------
function disable_block_editor_assets() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');

    // WooCommerce and theme-specific blocks (disable only if not used)
    wp_dequeue_style('wc-block-style'); // WooCommerce
    wp_dequeue_style('storefront-gutenberg-blocks'); // Storefront theme
}
add_action('wp_enqueue_scripts', 'disable_block_editor_assets', 100);

// --------------------------
// Disable Block Styles (Admin)
// --------------------------
function remove_block_editor_assets_from_admin() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}
if (is_admin()) {
    add_action('admin_enqueue_scripts', 'remove_block_editor_assets_from_admin', 100);
}

// --------------------------
// Disable Tags
// --------------------------
add_action('init', function () {
    register_taxonomy('post_tag', []); // Unregister from all post types
});

// Remove tag metabox from post editor
add_action('admin_menu', function () {
    remove_meta_box('tagsdiv-post_tag', 'post', 'side');
}, 999);

// --------------------------
// Disable Comments Completely
// --------------------------
add_action('admin_init', function () {
    global $pagenow;

    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove dashboard comment widget
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable comments and trackbacks for public post types
    foreach (get_post_types(['public' => true]) as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Frontend: force comments/pings closed
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments menu from admin
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments icon from admin bar
add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
});

// --------------------------
// Add x-default hreflang
// --------------------------
function add_hreflang_xdefault_only() {
    $base_url = home_url('/');
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($base_url) . '" />' . "\n";
}
add_action('wp_head', 'add_hreflang_xdefault_only', 1); // Priority 1 ensures early output

add_filter('widget_text', 'do_shortcode');
add_filter('yootheme_builder_content', 'do_shortcode');


add_action('restrict_manage_posts', 'filter_team_by_select_field');
function filter_team_by_select_field() {
    global $typenow;

    if ($typenow === 'team') {
        $field = get_field_object('Select'); // ACF field name: "Select"

        if ($field && isset($field['choices'])) {
            $choices = $field['choices'];
            $selected = isset($_GET['select_filter']) ? $_GET['select_filter'] : '';

            echo '<select name="select_filter">';
            echo '<option value="">All Types</option>';

            foreach ($choices as $value => $label) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr($value),
                    selected($selected, $value, false),
                    esc_html($label)
                );
            }

            echo '</select>';
        }
    }
}

add_action('pre_get_posts', 'filter_team_query_by_select_field');
function filter_team_query_by_select_field($query) {
    global $pagenow;

    if (
        is_admin() &&
        $pagenow === 'edit.php' &&
        isset($_GET['select_filter']) &&
        $_GET['select_filter'] !== '' &&
        $query->get('post_type') === 'team'
    ) {
        $query->set('meta_query', [
            [
                'key'     => 'Select',
                'value'   => sanitize_text_field($_GET['select_filter']),
                'compare' => '='
            ]
        ]);
    }
}
// Enqueue ACF admin script
function child_theme_acf_icon_autofill_script() {
    global $post;
    if ($post && get_post_type($post) === 'media-item') {
        wp_enqueue_script(
            'acf-icon-autofill',
            get_stylesheet_directory_uri() . '/js/acf-icon-autofill.js', // use get_stylesheet_directory_uri() for child theme
            ['jquery'],
            null,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'child_theme_acf_icon_autofill_script');


// Enqueue ACF admin script and localize AJAX variables
function enqueue_acf_supporter_levels_script() {
    wp_enqueue_script(
        'acf-supporter-levels',
        get_stylesheet_directory_uri() . '/js/acf-supporter-levels.js',
        ['acf-input'],
        null,
        true
    );

    wp_localize_script('acf-supporter-levels', 'acfSupporterLevels', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('get_supporter_levels_nonce')
    ]);
}
add_action('acf/input/admin_enqueue_scripts', 'enqueue_acf_supporter_levels_script');

// Handle AJAX request to get supporter levels from selected Event
add_action('wp_ajax_get_supporter_levels', 'get_supporter_levels_callback');
function get_supporter_levels_callback() {
    check_ajax_referer('get_supporter_levels_nonce', 'nonce');

    $event_id = intval($_POST['event_id']);
    $levels = [];

    if ($event_id && have_rows('sponsorship_levels', $event_id)) {
        while (have_rows('sponsorship_levels', $event_id)) {
            the_row();
            $level = get_sub_field('sponsor_level');
            if ($level) {
                $levels[] = $level;
            }
        }
    } else {
        wp_send_json_error([
            'message' => 'No rows found or invalid event_id',
            'event_id' => $event_id,
            'has_rows' => have_rows('sponsorship_levels', $event_id)
        ]);
    }

    wp_send_json_success($levels);
}


// Filter dropdown for Year (custom field)
add_action('restrict_manage_posts', 'filter_bills_by_year');
function filter_bills_by_year() {
    global $typenow;

    if ($typenow !== 'bill') {
        return;
    }

    // Get all "year" values from published bills
    $bills = get_posts(array(
        'post_type' => 'bill',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
    ));

    $years = array_unique(array_filter(array_map(function($post_id) {
        return get_field('year', $post_id); // 'year' is the field name
    }, $bills)));

    if (empty($years)) {
        return;
    }

    sort($years); // Optional: sort ascending

    $selected = $_GET['filter_year'] ?? '';

    echo '<select name="filter_year">';
    echo '<option value="">All Years</option>';
    foreach ($years as $year) {
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr($year),
            selected($selected, $year, false),
            esc_html($year)
        );
    }
    echo '</select>';
}

// Apply filter for Year
add_action('pre_get_posts', 'apply_year_filter_to_bills');
function apply_year_filter_to_bills($query) {
    if (
        is_admin() &&
        $query->is_main_query() &&
        ($query->get('post_type') === 'bill') &&
        isset($_GET['filter_year']) &&
        $_GET['filter_year'] !== ''
    ) {
        $query->set('meta_query', array(
            array(
                'key' => 'year',
                'value' => sanitize_text_field($_GET['filter_year']),
                'compare' => '='
            )
        ));
    }
}

// Filter dropdown for Legislation Category (taxonomy)
add_action('restrict_manage_posts', 'filter_bills_by_custom_category');
function filter_bills_by_custom_category() {
    global $typenow;

    if ($typenow !== 'bill') {
        return;
    }

    $terms = get_terms(array(
        'taxonomy' => 'legislation-category',
        'hide_empty' => false,
    ));

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    $selected = $_GET['filter_bill_category'] ?? '';

    echo '<select name="filter_bill_category">';
    echo '<option value="">All Categories</option>';
    foreach ($terms as $term) {
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr($term->slug),
            selected($selected, $term->slug, false),
            esc_html($term->name)
        );
    }
    echo '</select>';
}

// Apply filter for Legislation Category
add_action('pre_get_posts', 'apply_bill_category_filter');
function apply_bill_category_filter($query) {
    if (
        is_admin() &&
        $query->is_main_query() &&
        ($query->get('post_type') === 'bill') &&
        isset($_GET['filter_bill_category']) &&
        $_GET['filter_bill_category'] !== ''
    ) {
        $query->set('tax_query', array(
            array(
                'taxonomy' => 'legislation-category',
                'field' => 'slug',
                'terms' => sanitize_text_field($_GET['filter_bill_category']),
            )
        ));
    }
}
// Filter dropdown for Media Categories in Media Articles admin view
add_action('restrict_manage_posts', 'filter_media_articles_by_category');
function filter_media_articles_by_category() {
    global $typenow;

    if ($typenow !== 'media-item') {
        return;
    }

    $taxonomy = 'media'; // Taxonomy slug for Media Categories
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ));

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    $selected = $_GET['filter_media_category'] ?? '';

    echo '<select name="filter_media_category">';
    echo '<option value="">All Media Categories</option>';
    foreach ($terms as $term) {
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr($term->slug),
            selected($selected, $term->slug, false),
            esc_html($term->name)
        );
    }
    echo '</select>';
}

// Apply taxonomy query for Media Category filter
add_action('pre_get_posts', 'apply_media_category_filter');
function apply_media_category_filter($query) {
    if (
        is_admin() &&
        $query->is_main_query() &&
        $query->get('post_type') === 'media-item' &&
        isset($_GET['filter_media_category']) &&
        $_GET['filter_media_category'] !== ''
    ) {
        $query->set('tax_query', array(
            array(
                'taxonomy' => 'media',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET['filter_media_category']),
            )
        ));
    }
}
// Filter dropdown for Resource Categories in Resources admin view
add_action('restrict_manage_posts', 'filter_resources_by_category');
function filter_resources_by_category() {
    global $typenow;

    if ($typenow !== 'resource') {
        return;
    }

    $taxonomy = 'resource-category'; // Taxonomy slug for Resource Categories
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ));

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    $selected = $_GET['filter_resource_category'] ?? '';

    echo '<select name="filter_resource_category">';
    echo '<option value="">All Resource Categories</option>';
    foreach ($terms as $term) {
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr($term->slug),
            selected($selected, $term->slug, false),
            esc_html($term->name)
        );
    }
    echo '</select>';
}

// Apply taxonomy query for Resource Category filter
add_action('pre_get_posts', 'apply_resource_category_filter');
function apply_resource_category_filter($query) {
    if (
        is_admin() &&
        $query->is_main_query() &&
        $query->get('post_type') === 'resource' &&
        isset($_GET['filter_resource_category']) &&
        $_GET['filter_resource_category'] !== ''
    ) {
        $query->set('tax_query', array(
            array(
                'taxonomy' => 'resource-category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET['filter_resource_category']),
            )
        ));
    }
}


/**
 * Limit search results to the media category
 */
add_action('pre_get_posts', function ($query) {
  if (
    $query->is_main_query() &&
    !is_admin() &&
    $query->is_search()
  ) {
    // Always limit to media-item post type
    $query->set('post_type', 'media-item');

    // Remove pagination limit (show all matching results)
    $query->set('posts_per_page', -1); // -1 means unlimited

    // Optional taxonomy filtering
    if (!empty($_GET['media_term'])) {
      $query->set('tax_query', [[
        'taxonomy' => 'media',
        'field'    => 'slug',
        'terms'    => sanitize_text_field($_GET['media_term']),
      ]]);
    }
  }
});
add_action('acf/input/admin_head', function() {
    global $post;
    if ($post && get_post_type($post) === 'media-item') {
        echo '<style>
            .acf-field-688d36a575cfd {
                display: none !important;
            }
        </style>';
    }
});

/**
 * Enqueue headline title-case script from child theme.
 */
function accessitree_enqueue_titlecase_script() {
    if ( is_admin() ) {
        return; // Only load on the front end
    }

    $handle   = 'title-case-headlines';
    $filename = 'title-case-headlines.js';
    $rel_path = '/js/' . $filename;

    $src  = get_stylesheet_directory_uri() . $rel_path;      // URL to child theme js file
    $file = get_stylesheet_directory() . $rel_path;          // File path in child theme

    // Cache-busting: use file modification time if available
    $ver = file_exists( $file ) ? filemtime( $file ) : wp_get_theme()->get( 'Version' );

    wp_enqueue_script(
        $handle,
        $src,
        array(),   // Add dependencies if needed, e.g. array('jquery')
        $ver,
        true       // Load in footer
    );
}
add_action( 'wp_enqueue_scripts', 'accessitree_enqueue_titlecase_script' );



/**
 * Order Media archives by ACF "Release Date" (field name: release_date)
 * Works for:
 *  - /media/ CPT archive
 *  - Media taxonomy archives
 * Preserves pagination and includes posts missing the field, which fall back to post_date.
 */
add_action('pre_get_posts', function (WP_Query $q) {
    if (is_admin() || !$q->is_main_query()) {
        return;
    }

    // Adjust to your CPT and taxonomy slugs from your ACF export
    $is_media_archive = $q->is_post_type_archive('media-item');
    $is_media_term    = $q->is_tax('media');

    if ($is_media_archive || $is_media_term) {
        // Named meta query clause so we can order by it
        $meta_query = (array) $q->get('meta_query');
        $meta_query['release_date_clause'] = [
            'key'     => 'release_date',
            'compare' => 'EXISTS',
            'type'    => 'NUMERIC', // ACF stores date_picker as YYYYMMDD in postmeta
        ];
        $q->set('meta_query', $meta_query);

        // Tell WP which meta_key to use for meta_value_num ordering
        $q->set('meta_key', 'release_date');

        // Order: first by Release Date desc, then by post_date desc for items missing the field
        $q->set('orderby', [
            'release_date_clause' => 'DESC', // uses the named meta_query clause
            'meta_value_num'      => 'DESC', // explicit numeric ordering of the same key
            'date'                => 'DESC', // fallback for posts without the field
        ]);

        // Optional: ensure we see everything unless you are filtering by status elsewhere
        // $q->set('post_status', ['publish']);
    }
});
/**
 * Make adjacent post navigation on single Media Items use ACF "Release Date".
 */
add_filter('get_previous_post_where', 'media_adjacent_where_release_date', 10, 5);
add_filter('get_next_post_where',      'media_adjacent_where_release_date', 10, 5);
add_filter('get_previous_post_sort',   'media_adjacent_sort_release_date');
add_filter('get_next_post_sort',       'media_adjacent_sort_release_date');

function media_adjacent_where_release_date($where, $in_same_term, $excluded_terms, $taxonomy, $post) {
    if ($post->post_type !== 'media-item') {
        return $where;
    }
    global $wpdb;

    // Pull current post's Release Date, fall back to its post_date if empty
    $release = get_post_meta($post->ID, 'release_date', true);
    $current = $release ? (int) $release : (int) gmdate('Ymd', strtotime($post->post_date_gmt));

    // For prev: find less than current. For next: greater than current.
    // We cannot know which filter we are on from here, so handle both in SORT filter instead.
    // Narrow to posts that either have a release_date or compare on post_date as YYYYMMDD
    $where  = "WHERE p.post_type = 'media-item' AND p.post_status = 'publish' ";
    $where .= $in_same_term && $taxonomy ? get_tax_sql_for_adjacent($post->ID, $taxonomy) : '';

    // Compare using COALESCE of release_date and formatted post_date
    $where .= $wpdb->prepare(
        " AND CAST(COALESCE(pm.meta_value, DATE_FORMAT(p.post_date, '%%Y%%m%%d')) AS UNSIGNED) %s %d ",
        current_filter() === 'get_previous_post_where' ? '<' : '>',
        $current
    );

    return $where;
}

function media_adjacent_sort_release_date($sort) {
    // Order by Release Date numeric, fallback to post_date formatted as YYYYMMDD
    return " ORDER BY CAST(COALESCE(pm.meta_value, DATE_FORMAT(p.post_date, '%Y%m%d')) AS UNSIGNED) " .
           (current_filter() === 'get_previous_post_sort' ? 'DESC' : 'ASC') . " LIMIT 1";
}

/**
 * Helper to join postmeta and taxonomy for the adjacent filters.
 */
add_filter('get_previous_post_join', 'media_adjacent_join_release_date');
add_filter('get_next_post_join',      'media_adjacent_join_release_date');
function media_adjacent_join_release_date($join) {
    global $wpdb;
    // Join the release_date meta as alias pm
    if (strpos($join, "{$wpdb->postmeta} pm") === false) {
        $join .= " LEFT JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID AND pm.meta_key = 'release_date' ";
    }
    return $join;
}

if (!function_exists('get_tax_sql_for_adjacent')) {
    /**
     * Build taxonomy constraint for adjacent post where-clause.
     */
    function get_tax_sql_for_adjacent($post_id, $taxonomy) {
        $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
        if (is_wp_error($terms) || empty($terms)) {
            return '';
        }
        global $wpdb;
        $term_ids_in = implode(',', array_map('intval', $terms));
        return " AND p.ID IN (
            SELECT tr.object_id FROM {$wpdb->term_relationships} tr
            INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tt.taxonomy = '{$taxonomy}' AND tt.term_id IN ({$term_ids_in})
        )";
    }
}


/**
 * Search results: order Media Items by ACF "Release Date" (YYYYMMDD), fallback to post_date.
 */
add_action('pre_get_posts', function (WP_Query $q) {
    if (is_admin() || !$q->is_main_query() || !$q->is_search()) {
        return;
    }

    // Limit search to your CPT, or remove this line to include all post types
    $q->set('post_type', ['media-item']);

    // Add SQL-level ordering using COALESCE(release_date, post_date)
    add_filter('posts_clauses', 'accessitree_sort_search_by_release_date', 10, 2);
});

function accessitree_sort_search_by_release_date($clauses, $query) {
    if (! $query->is_main_query() || ! $query->is_search()) {
        return $clauses;
    }

    global $wpdb;
    $meta_key = 'release_date'; // ACF date_picker stores as YYYYMMDD in postmeta

    // Join once, safely
    if (strpos($clauses['join'], ' relpm ') === false) {
        $clauses['join'] .= $wpdb->prepare(
            " LEFT JOIN {$wpdb->postmeta} AS relpm
              ON relpm.post_id = {$wpdb->posts}.ID
             AND relpm.meta_key = %s ",
            $meta_key
        );
    }

    // Build a numeric key that uses release_date when present, else post_date formatted as YYYYMMDD
    $coalesce = "CAST(COALESCE(relpm.meta_value, DATE_FORMAT({$wpdb->posts}.post_date, '%Y%m%d')) AS UNSIGNED)";

    // Respect any requested ORDER=ASC, default to DESC
    $order = strtoupper($query->get('order')) === 'ASC' ? 'ASC' : 'DESC';

    // Replace ORDER BY to sort by Release Date first, then ID for stability
    $clauses['orderby'] = "{$coalesce} {$order}, {$wpdb->posts}.ID DESC";

    return $clauses;
}

/**
 * Show ALL "bill" posts on archives (and related tax archives).
 */
add_action('pre_get_posts', function (WP_Query $q) {
    if (is_admin() || !$q->is_main_query()) {
        return;
    }

    // CPT archive for "bill"
    if ($q->is_post_type_archive('bill')) {
        $q->set('posts_per_page', -1);
        $q->set('no_found_rows', true); // skip COUNT(*) since there is no pagination
        return;
    }

    // Taxonomy archives attached to the "bill" CPT
    // Replace 'legislation-category' with your actual taxonomy slug(s) if different
    if ($q->is_tax(['legislation-category'])) {
        $q->set('posts_per_page', -1);
        $q->set('no_found_rows', true);
        return;
    }
});

