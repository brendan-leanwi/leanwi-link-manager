<?php
namespace LEANWI_Link_Manager;

add_action('wp_ajax_leanwi_filter_links', __NAMESPACE__ . '\\leanwi_filter_links');
add_action('wp_ajax_nopriv_leanwi_filter_links', __NAMESPACE__ . '\\leanwi_filter_links');

function leanwi_filter_links() {
    check_ajax_referer('leanwi_filter_links', 'nonce');

    //error_log('$_POST: ' . print_r($_POST, true));

    global $wpdb;
    $links_table = $wpdb->prefix . 'leanwi_lm_links';
    $areas_table = $wpdb->prefix . 'leanwi_lm_program_area';
    $formats_table = $wpdb->prefix . 'leanwi_lm_formats';
    $tags_table = $wpdb->prefix . 'leanwi_lm_tags';
    $linktags_table = $wpdb->prefix . 'leanwi_lm_linktags';
    $related_table = $wpdb->prefix . 'leanwi_lm_related_links';

    // Get and sanitize filter parameters
    $initial_area_id = !empty($_POST['initial_area_id']) ? array_filter(array_map('intval', explode(',', $_POST['initial_area_id']))) : [];
    $initial_format_id = !empty($_POST['initial_format_id']) ? array_filter(array_map('intval', explode(',', $_POST['initial_format_id']))) : [];
    $initial_tag_id = !empty($_POST['initial_tag_id']) ? array_filter(array_map('intval', explode(',', $_POST['initial_tag_id']))) : [];

    $current_area_id = isset($_POST['area_id']) ? array_filter(array_map('intval', (array) $_POST['area_id'])) : [];
    $current_format_id = isset($_POST['format_id']) ? array_filter(array_map('intval', (array) $_POST['format_id'])) : [];
    $current_tag_id = isset($_POST['tag_id']) ? array_filter(array_map('intval', (array) $_POST['tag_id'])) : [];

    $start_date = !empty($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
    $end_date = !empty($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

    // Final filter sets used in query
    $area_id = !empty($current_area_id) ? $current_area_id : $initial_area_id;
    $format_id = !empty($current_format_id) ? $current_format_id : $initial_format_id;
    $tag_id = !empty($current_tag_id) ? $current_tag_id : $initial_tag_id;

    $search = sanitize_text_field($_POST['search'] ?? '');
    $featured_only = (isset($_POST['featured_only']) && ($_POST['featured_only'] === '1' || $_POST['featured_only'] === 1)) ? 1 : 0;

    $max_listings = isset($_POST['max_listings']) ? intval($_POST['max_listings']) : 0;

    //error_log('Max listings received: ' . print_r($_POST['max_listings'], true));

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

    if (!empty($tag_id)) {
        $tag_placeholders = implode(',', array_fill(0, count($tag_id), '%d'));
        $query .= " INNER JOIN $linktags_table lt ON l.link_id = lt.link_id";
        $where[] = "lt.tag_id IN ($tag_placeholders)";
        $params = array_merge($params, $tag_id);
    }

    if ($start_date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date)) {
        $where[] = "l.creation_date >= %s";
        $params[] = $start_date . ' 00:00:00';
    }

    if ($end_date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
        $where[] = "l.creation_date <= %s";
        $params[] = $end_date . ' 23:59:59';
    }

    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    $query .= " GROUP BY l.link_id ORDER BY l.creation_date DESC";
    if ($max_listings > 0) {
        $query .= " LIMIT %d";
        $params[] = $max_listings;
    }

    //Send query to debug.log
    /*
    $debug_query = $wpdb->prepare($query, $params);
    error_log('Max listings: ' . $max_listings);
    error_log('Applied Filters: area_id=' . implode(',', $area_id) . ' format_id=' . implode(',', $format_id) . ' tags=' . implode(',', $tag_id));
    error_log($debug_query);
    */
    $results = $wpdb->get_results($wpdb->prepare($query, $params), ARRAY_A);

    // Build HTML output
    if (empty($results)) {
        echo '<p>No links found.</p>';
    } else {
        echo '<table><thead><tr>';
        echo '<th>Date</th><th>Title</th><th>Format</th><th>Description</th><th>Program Area</th><th>Tags</th><th>Related Links</th>';
        echo '</tr></thead><tbody>';

        foreach ($results as $link) {
            // Get related link titles
            $current_link_id = (int)$link['link_id'];
            
            echo '<tr>';
            $display_date = new \DateTime($link['creation_date']);
            echo '<td>' . esc_html($display_date->format('F j, Y')) . '</td>';
            echo '<td><a href="' . esc_url($link['link_url']) . '" target="_blank" title="' . esc_attr($link['description']) . '">' . esc_html($link['title']) . '</a></td>';
            
            echo '<td>' . esc_html($link['format_name']) . '</td>';
            echo '<td>' . esc_html($link['description']) . '</td>';
            echo '<td>' . esc_html($link['area_name']) . '</td>';
            
            // Get tags for this link
            $tag_names = $wpdb->get_col(
                $wpdb->prepare("
                    SELECT t.name 
                    FROM $tags_table t
                    INNER JOIN $linktags_table lt ON t.tag_id = lt.tag_id
                    WHERE lt.link_id = %d
                    ORDER BY t.display_order ASC
                ", $current_link_id)
            );

            $tag_output = '';
            if (!empty($tag_names)) {
                $tag_output = implode(', ', array_map('esc_html', $tag_names));
            }

            echo '<td>' . $tag_output . '</td>';

            // First, get the relationship_id for this link
            $relationship_id = $wpdb->get_var(
                $wpdb->prepare("SELECT relationship_id FROM $related_table WHERE link_id = %d", $current_link_id)
            );

            $related_titles = '';

            if ($relationship_id) {
                $related_links = $wpdb->get_col(
                    $wpdb->prepare("
                        SELECT link_id FROM $related_table 
                        WHERE relationship_id = %d AND link_id != %d
                    ", $relationship_id, $current_link_id)
                );

                if (!empty($related_links)) {
                    $placeholders = implode(',', array_fill(0, count($related_links), '%d'));
                    $query = "SELECT title, link_url, description FROM $links_table WHERE link_id IN ($placeholders)";
                    $prepared = $wpdb->prepare($query, $related_links);
                    $related_rows = $wpdb->get_results($prepared, ARRAY_A);

                    // Build hidden modal content
                    $list_items = '';
                    foreach ($related_rows as $row) {
                        $title = esc_html($row['title']);
                        $url   = esc_url($row['link_url']);
                        $desc  = esc_attr($row['description']);
                        $list_items .= "<li><a href=\"$url\" target=\"_blank\" rel=\"noopener\" title=\"$desc\">$title</a></li>";
                    }

                    $count = count($related_rows);
                    $count = count($related_rows);

                    $related_titles = 
                        "<a href=\"javascript:void(0);\" 
                            class=\"related-resources-link\" 
                            data-related='" . json_encode($related_rows, JSON_HEX_APOS | JSON_HEX_QUOT) . "'>
                            $count related resource" . ($count > 1 ? 's' : '') . "
                        </a>";
                }
            }

            echo '<td>' . $related_titles . '</td>';
            echo '</tr>';

        }

        echo '</tbody></table>';

    }

    wp_die();
}
?>

