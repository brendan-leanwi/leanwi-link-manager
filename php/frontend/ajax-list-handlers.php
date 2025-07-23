<?php
namespace LEANWI_Link_Manager;

add_action('wp_ajax_leanwi_filter_links', __NAMESPACE__ . '\\leanwi_filter_links');
add_action('wp_ajax_nopriv_leanwi_filter_links', __NAMESPACE__ . '\\leanwi_filter_links');

function leanwi_filter_links() {
    check_ajax_referer('leanwi_filter_links', 'nonce');

    global $wpdb;
    $links_table = $wpdb->prefix . 'leanwi_lm_links';
    $areas_table = $wpdb->prefix . 'leanwi_lm_program_area';
    $formats_table = $wpdb->prefix . 'leanwi_lm_formats';
    $tags_table = $wpdb->prefix . 'leanwi_lm_tags';
    $linktags_table = $wpdb->prefix . 'leanwi_lm_linktags';

    error_log('POST tags: ' . print_r($_POST['tags'], true));

    $area_id = isset($_POST['area_id']) ? $_POST['area_id'] : [];
    if (!is_array($area_id)) {
        $area_id = array_map('intval', explode(',', $area_id));
    } else {
        $area_id = array_map('intval', $area_id);
    }

    $format_id = isset($_POST['format_id']) ? $_POST['format_id'] : [];
    if (!is_array($format_id)) {
        $format_id = array_map('intval', explode(',', $format_id));
    } else {
        $format_id = array_map('intval', $format_id);
    }

    $search = sanitize_text_field($_POST['search'] ?? '');
    $featured_only = (isset($_POST['featured_only']) && ($_POST['featured_only'] === '1' || $_POST['featured_only'] === 1)) ? 1 : 0;

    $tags = isset($_POST['tags']) ? array_map('intval', $_POST['tags']) : [];

    // Build base query
    $query = "
        SELECT l.*, a.name AS area_name, f.name AS format_name
        FROM $links_table l
        LEFT JOIN $areas_table a ON l.area_id = a.area_id
        LEFT JOIN $formats_table f ON l.format_id = f.format_id
    ";

    $where = [];
    $params = [];

    if (!empty($area_id)) {
        $placeholders = implode(',', array_fill(0, count($area_id), '%d'));
        $where[] = "l.area_id IN ($placeholders)";
        $params = array_merge($params, $area_id);
    }
    
    if (!empty($format_id)) {
        $placeholders = implode(',', array_fill(0, count($format_id), '%d'));
        $where[] = "l.format_id IN ($placeholders)";
        $params = array_merge($params, $format_id);
    }

    if ($search) {
        $where[] = "(l.title LIKE %s OR l.description LIKE %s)";
        $like = '%' . $wpdb->esc_like($search) . '%';
        $params[] = $like;
        $params[] = $like;
    }
    if ($featured_only) {
        $where[] = "l.is_featured_link = 1";
    }

    /*if (!empty($tags)) {
        $tag_placeholders = implode(',', array_fill(0, count($tags), '%d'));
        $query .= " INNER JOIN $linktags_table lt ON l.link_id = lt.link_id AND lt.tag_id IN ($tag_placeholders)";
        $params = array_merge($params, $tags);
    }*/
    if (!empty($tags)) {
        $tag_placeholders = implode(',', array_fill(0, count($tags), '%d'));
        $query .= " INNER JOIN $linktags_table lt ON l.link_id = lt.link_id";
        $where[] = "lt.tag_id IN ($tag_placeholders)";
        $params = array_merge($params, $tags);
    }

    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    $query .= " GROUP BY l.link_id ORDER BY l.creation_date DESC";

    //Send query to debug.log
    $prepared_query = $wpdb->prepare($query, $params);
    error_log($prepared_query);

    $results = $wpdb->get_results($wpdb->prepare($query, $params), ARRAY_A);

    // Build HTML output
    if (empty($results)) {
        echo '<p>No links found.</p>';
    } else {
        echo '<table><thead><tr>';
        echo '<th>Title</th><th>URL</th><th>Program Area</th><th>Format</th><th>Featured</th>';
        echo '</tr></thead><tbody>';

        foreach ($results as $link) {
            echo '<tr>';
            echo '<td>' . esc_html($link['title']) . '</td>';
            echo '<td><a href="' . esc_url($link['link_url']) . '" target="_blank">Visit</a></td>';
            echo '<td>' . esc_html($link['area_name']) . '</td>';
            echo '<td>' . esc_html($link['format_name']) . '</td>';
            echo '<td>' . ($link['is_featured_link'] ? 'Yes' : 'No') . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }

    wp_die();
}
