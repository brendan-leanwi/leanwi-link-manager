<?php
namespace LEANWI_Link_Manager;

add_shortcode('link_manager_links_list', __NAMESPACE__ . '\\leanwi_link_manager_links_list_shortcode');

/**
 * Simple list shortcode for Link Manager.
 *
 * Usage:
 * [link_manager_links_list area_id="4" tag_id="2" max_listings="10"]
 * [link_manager_links_list area_id="1,2" tag_id="3,4" format_id="1"]
 */
function leanwi_link_manager_links_list_shortcode($atts) {
    global $wpdb;

    $atts = shortcode_atts([
        'area_id'      => '',
        'tag_id'       => '',
        'format_id'    => '',
        'max_listings' => '0',
    ], $atts, 'link_manager_links_list');

    $area_ids   = leanwi_lm_parse_csv_ids($atts['area_id']);
    $tag_ids    = leanwi_lm_parse_csv_ids($atts['tag_id']);
    $format_ids = leanwi_lm_parse_csv_ids($atts['format_id']);
    $limit      = max(0, intval($atts['max_listings']));

    $links_table    = $wpdb->prefix . 'leanwi_lm_links';
    $linktags_table = $wpdb->prefix . 'leanwi_lm_linktags';
    $formats_table  = $wpdb->prefix . 'leanwi_lm_formats';

    $sql = "
        SELECT DISTINCT
            l.link_id,
            l.link_url,
            l.title,
            l.format_id,
            f.icon_url,
            f.use_icon
        FROM {$links_table} l
        LEFT JOIN {$formats_table} f ON l.format_id = f.format_id
    ";

    $where  = [];
    $params = [];

    if (!empty($tag_ids)) {
        $sql .= " INNER JOIN {$linktags_table} lt ON l.link_id = lt.link_id ";
    }

    if (!empty($area_ids)) {
        $placeholders = implode(',', array_fill(0, count($area_ids), '%d'));
        $where[] = "l.area_id IN ({$placeholders})";
        $params = array_merge($params, $area_ids);
    }

    if (!empty($format_ids)) {
        $placeholders = implode(',', array_fill(0, count($format_ids), '%d'));
        $where[] = "l.format_id IN ({$placeholders})";
        $params = array_merge($params, $format_ids);
    }

    if (!empty($tag_ids)) {
        $placeholders = implode(',', array_fill(0, count($tag_ids), '%d'));
        $where[] = "lt.tag_id IN ({$placeholders})";
        $params = array_merge($params, $tag_ids);
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    $sql .= " ORDER BY l.title ASC ";

    if ($limit > 0) {
        $sql .= " LIMIT %d ";
        $params[] = $limit;
    }

    $results = !empty($params)
        ? $wpdb->get_results($wpdb->prepare($sql, $params), ARRAY_A)
        : $wpdb->get_results($sql, ARRAY_A);

    if (empty($results)) {
        return '<p class="leanwi-lm-links-list-empty">No links found.</p>';
    }

    ob_start();
    ?>
    <ul class="dsm_icon_list_items dsm_icon_list_ltr_direction dsm_icon_list_layout_vertical leanwi-lm-links-list">
        <?php foreach ($results as $index => $row) : ?>
            <li class="dsm_icon_list_child dsm_icon_list_child_<?php echo (int) $index; ?> leanwi-lm-links-list__item">
                <a
                    href="<?php echo esc_url($row['link_url']); ?>"
                    class="leanwi-lm-links-list__link"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="dsm_icon_list_wrapper">
                        <span class="dsm_icon_list_icon" aria-hidden="true">
                            <?php if (!empty($row['use_icon']) && !empty($row['icon_url'])) : ?>
                                <img
                                    src="<?php echo esc_url($row['icon_url']); ?>"
                                    alt=""
                                    class="leanwi-lm-links-list__icon-image"
                                    loading="lazy"
                                    decoding="async"
                                >
                            <?php else : ?>
                                <span class="dashicons dashicons-admin-links leanwi-lm-default-icon" aria-hidden="true"></span>
                            <?php endif; ?>
                        </span>
                    </span>
                    <span class="dsm_icon_list_text">
                        <?php echo esc_html($row['title']); ?>
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php

return ob_get_clean();
}

/**
 * Parse shortcode CSV id strings into a clean integer array.
 */
function leanwi_lm_parse_csv_ids($value) {
    if (!is_string($value) || trim($value) === '') {
        return [];
    }

    $ids = array_map('trim', explode(',', $value));
    $ids = array_map('intval', $ids);
    $ids = array_filter($ids, function ($id) {
        return $id > 0;
    });

    return array_values(array_unique($ids));
}