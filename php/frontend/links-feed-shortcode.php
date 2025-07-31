<?php
namespace LEANWI_Link_Manager;

add_shortcode('link_manager_feed', __NAMESPACE__ . '\\leanwi_link_feed_shortcode');

function leanwi_link_feed_shortcode($atts) {
    global $wpdb;

    $atts = shortcode_atts([
        'area_id'   => '',
        'format_id' => '',
        'tag_id'    => '',
        'featured'  => '', // true, false, or empty
    ], $atts, 'link_manager_feed');

    $area_ids   = array_filter(array_map('intval', explode(',', $atts['area_id'])));
    $format_ids = array_filter(array_map('intval', explode(',', $atts['format_id'])));
    $tag_ids    = array_filter(array_map('intval', explode(',', $atts['tag_id'])));
    $featured = strtolower(trim($atts['featured']));

    $links_table     = $wpdb->prefix . 'leanwi_lm_links';
    $areas_table     = $wpdb->prefix . 'leanwi_lm_program_area';
    $formats_table   = $wpdb->prefix . 'leanwi_lm_formats';
    $linktags_table  = $wpdb->prefix . 'leanwi_lm_linktags';
    $tags_table      = $wpdb->prefix . 'leanwi_lm_tags';

    $query = "
        SELECT DISTINCT l.*, a.name AS area_name, f.name AS format_name
        FROM $links_table l
        LEFT JOIN $areas_table a ON l.area_id = a.area_id
        LEFT JOIN $formats_table f ON l.format_id = f.format_id
        LEFT JOIN $linktags_table lt ON l.link_id = lt.link_id
    ";

    $where = [];
    $params = [];

    if (!empty($area_ids)) {
        $placeholders = implode(',', array_fill(0, count($area_ids), '%d'));
        $where[] = "l.area_id IN ($placeholders)";
        $params = array_merge($params, $area_ids);
    }

    if (!empty($format_ids)) {
        $placeholders = implode(',', array_fill(0, count($format_ids), '%d'));
        $where[] = "l.format_id IN ($placeholders)";
        $params = array_merge($params, $format_ids);
    }

    if (!empty($tag_ids)) {
        $placeholders = implode(',', array_fill(0, count($tag_ids), '%d'));
        $where[] = "lt.tag_id IN ($placeholders)";
        $params = array_merge($params, $tag_ids);
    }

    if ($featured === 'true') {
        $where[] = "l.is_featured_link = 1";
    } elseif ($featured === 'false') {
        $where[] = "l.is_featured_link = 0";
    }

    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    $query .= " ORDER BY l.creation_date DESC";
    $prepared = $wpdb->prepare($query, $params);

    error_log('Applied Filters (Feed): area_id=' . implode(',', $area_ids) . ' format_id=' . implode(',', $format_ids) . ' tags=' . implode(',', $tag_ids));
    error_log($prepared);

    $results = $wpdb->get_results($prepared, ARRAY_A);

    if (empty($results)) {
        return '<p>No links found.</p>';
    }

    ob_start();
    echo '<div class="leanwi-link-feed">';

    foreach ($results as $link) {
        echo '<div class="leanwi-feed-item">';
        echo '<h3><a href="' . esc_url($link['link_url']) . '" target="_blank">' . esc_html($link['title']) . '</a></h3>';
        echo '<p>' . esc_html(wp_trim_words($link['description'], 25)) . '</p>';
        echo '<p><strong>Program Area:</strong> ' . esc_html($link['area_name']) . '</p>';
        echo '<p><strong>Format:</strong> ' . esc_html($link['format_name']) . '</p>';

        // Fetch tags for this link
        $tags = $wpdb->get_col($wpdb->prepare("
            SELECT t.name
            FROM $tags_table t
            INNER JOIN $linktags_table lt ON t.tag_id = lt.tag_id
            WHERE lt.link_id = %d
            ORDER BY t.display_order ASC
        ", $link['link_id']));

        if (!empty($tags)) {
            echo '<p><strong>Tags:</strong> ' . esc_html(implode(', ', $tags)) . '</p>';
        }

        $related_links = leanwi_lm_get_related_links($link['link_id']);

        if (!empty($related_links)) {
            echo '<div class="leanwi-lm-related-links"><strong>Related Links:</strong><ul>';
            foreach ($related_links as $related) {
                echo sprintf(
                    '<li><a href="%s" target="_blank" rel="noopener">%s</a></li>',
                    esc_url($related->link_url),
                    esc_html($related->title)
                );
            }
            echo '</ul></div>';
        }

        echo '</div>';
    }

    echo '</div>';
    return ob_get_clean();
}

function leanwi_lm_get_related_links($link_id) {
    global $wpdb;

    // Get the relationship ID for this link
    $relationship_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT relationship_id FROM {$wpdb->prefix}leanwi_lm_related_links WHERE link_id = %d",
            $link_id
        )
    );

    if (!$relationship_id) return [];

    // Get other links in the same relationship group
    $related_links = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT l.link_id, l.title, l.link_url 
             FROM {$wpdb->prefix}leanwi_lm_related_links rl
             INNER JOIN {$wpdb->prefix}leanwi_lm_links l ON rl.link_id = l.link_id
             WHERE rl.relationship_id = %d AND rl.link_id != %d
             ORDER BY l.creation_date DESC",
            $relationship_id, $link_id
        )
    );

    return $related_links;
}
