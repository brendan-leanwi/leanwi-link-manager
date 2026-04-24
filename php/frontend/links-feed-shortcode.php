<?php
namespace LEANWI_Link_Manager;

add_shortcode('link_manager_feed', __NAMESPACE__ . '\\leanwi_link_feed_shortcode');

function leanwi_link_feed_shortcode($atts) {
    global $wpdb;

    $atts = shortcode_atts([
        'area_id'      => '',
        'format_id'    => '',
        'tag_id'       => '',
        'audience_id'  => '',
        'featured'     => '',
        'max_listings' => '0',
    ], $atts, 'link_manager_feed');

    $area_ids     = array_filter(array_map('intval', explode(',', $atts['area_id'])));
    $format_ids   = array_filter(array_map('intval', explode(',', $atts['format_id'])));
    $tag_ids      = array_filter(array_map('intval', explode(',', $atts['tag_id'])));
    $audience_ids = array_filter(array_map('intval', explode(',', $atts['audience_id'])));
    $featured     = strtolower(trim($atts['featured']));
    $max_listings = max(0, intval($atts['max_listings']));

    $links_table              = $wpdb->prefix . 'leanwi_lm_links';
    $areas_table              = $wpdb->prefix . 'leanwi_lm_program_area';
    $formats_table            = $wpdb->prefix . 'leanwi_lm_formats';
    $linktags_table           = $wpdb->prefix . 'leanwi_lm_linktags';
    $tags_table               = $wpdb->prefix . 'leanwi_lm_tags';
    $linkaudience_table       = $wpdb->prefix . 'leanwi_lm_linkaudience';
    $linkprogram_area_table   = $wpdb->prefix . 'leanwi_lm_linkprogram_area';

    $query = "
        SELECT
            l.*,
            f.name AS format_name,
            GROUP_CONCAT(DISTINCT a.name ORDER BY a.display_order ASC SEPARATOR ', ') AS area_name
        FROM $links_table l
        LEFT JOIN $formats_table f ON l.format_id = f.format_id
        LEFT JOIN $linkprogram_area_table lpa_display ON l.link_id = lpa_display.link_id
        LEFT JOIN $areas_table a ON lpa_display.area_id = a.area_id
    ";

    $where = [];
    $params = [];

    if (!empty($area_ids)) {
        $query .= " INNER JOIN $linkprogram_area_table lpa_filter ON l.link_id = lpa_filter.link_id ";
        $placeholders = implode(',', array_fill(0, count($area_ids), '%d'));
        $where[] = "lpa_filter.area_id IN ($placeholders)";
        $params = array_merge($params, $area_ids);
    }

    if (!empty($audience_ids)) {
        $query .= " INNER JOIN $linkaudience_table la_filter ON l.link_id = la_filter.link_id ";
        $placeholders = implode(',', array_fill(0, count($audience_ids), '%d'));
        $where[] = "la_filter.audience_id IN ($placeholders)";
        $params = array_merge($params, $audience_ids);
    }

    if (!empty($format_ids)) {
        $placeholders = implode(',', array_fill(0, count($format_ids), '%d'));
        $where[] = "l.format_id IN ($placeholders)";
        $params = array_merge($params, $format_ids);
    }

    if (!empty($tag_ids)) {
        $query .= " INNER JOIN $linktags_table lt_filter ON l.link_id = lt_filter.link_id ";
        $placeholders = implode(',', array_fill(0, count($tag_ids), '%d'));
        $where[] = "lt_filter.tag_id IN ($placeholders)";
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

    $query .= " GROUP BY l.link_id ";
    $query .= " ORDER BY l.creation_date DESC";

    if ($max_listings > 0) {
        $query .= " LIMIT %d";
        $params[] = $max_listings;
    }

    $prepared = !empty($params)
        ? $wpdb->prepare($query, $params)
        : $query;

    $results = $wpdb->get_results($prepared, ARRAY_A);

    if (empty($results)) {
        return '<p>No links found.</p>';
    }

    ob_start();

    echo '<div class="leanwi-link-feed">';

    foreach ($results as $link) {
        echo '<div class="leanwi-feed-item">';
        echo '<h3><a href="' . esc_url($link['link_url']) . '" target="_blank" rel="noopener">' . esc_html($link['title']) . '</a></h3>';
        echo '<p>' . esc_html(wp_trim_words($link['description'], 25)) . '</p>';

        if (!empty($link['area_name'])) {
            echo '<p><strong>Program Area:</strong> ' . esc_html($link['area_name']) . '</p>';
        }

        if (!empty($link['format_name'])) {
            echo '<p><strong>Format:</strong> ' . esc_html($link['format_name']) . '</p>';
        }

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

if (!function_exists(__NAMESPACE__ . '\\leanwi_lm_get_related_links')) {
    function leanwi_lm_get_related_links($link_id) {
        global $wpdb;

        $relationship_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT relationship_id
                 FROM {$wpdb->prefix}leanwi_lm_related_links
                 WHERE link_id = %d
                 LIMIT 1",
                $link_id
            )
        );

        if (!$relationship_id) {
            return [];
        }

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT l.link_id, l.title, l.link_url, l.description
                 FROM {$wpdb->prefix}leanwi_lm_related_links rl
                 INNER JOIN {$wpdb->prefix}leanwi_lm_links l ON rl.link_id = l.link_id
                 WHERE rl.relationship_id = %d
                   AND rl.link_id != %d
                 ORDER BY l.creation_date DESC",
                $relationship_id,
                $link_id
            )
        );
    }
}
