<?php
namespace LEANWI_Link_Manager;

add_shortcode('link_manager_list', __NAMESPACE__ . '\leanwi_link_manager_shortcode');


function leanwi_lm_build_filter_options($table, $id_column, $name_column, $selected_ids = []) {
    global $wpdb;

    $selected_ids = array_filter(array_map('intval', (array) $selected_ids));
    $options = '';

    if (!empty($selected_ids)) {
        $placeholders = implode(',', array_fill(0, count($selected_ids), '%d'));
        $query = "SELECT $id_column, $name_column FROM $table WHERE $id_column IN ($placeholders) ORDER BY display_order ASC";
        $results = $wpdb->get_results($wpdb->prepare($query, $selected_ids), ARRAY_A);
    } else {
        $query = "SELECT $id_column, $name_column FROM $table ORDER BY display_order ASC";
        $results = $wpdb->get_results($query, ARRAY_A);
    }

    foreach ($results as $row) {
        $id = (int) $row[$id_column];
        $name = esc_html($row[$name_column]);
        $options .= "<option value=\"$id\">$name</option>";
    }

    return $options;
}

function leanwi_link_manager_shortcode($atts) {
    wp_enqueue_script('leanwi-link-manager-ajax');
    wp_enqueue_style('leanwi-link-manager-style');

    // Parse shortcode attributes
    $atts = shortcode_atts([
        'area_id' => '',              // example: area_id="1,2"
        'tag_id' => '',               // example: tag_id="1,2"
        'format_id' => '',            // example: format_id="1,2"
        'audience_id' => '',          // example: audience_id="1,2"
        'max_listings' => '0',        // Default to 0 = no limit after filters/search are applied
        'opening_max_items' => '-1',    // Optional initial-load limit only; use "0" to show no opening items
        'filters' => 'full',          // Use filters="simple" for keyword search only
    ], $atts, 'link_manager_list');

    $filter_mode = strtolower(trim((string) $atts['filters']));
    $is_simple_filter_mode = ($filter_mode === 'simple');

    $max_listings = max(0, intval($atts['max_listings']));
    $opening_max_items = intval($atts['opening_max_items']);
    $initial_max_listings = ($opening_max_items >= 0) ? $opening_max_items : $max_listings;

    // Prepare initial filter data for JS
    $initial_filters = [
        'area_id' => array_filter(array_map('intval', explode(',', $atts['area_id']))),
        'tag_id' => array_filter(array_map('intval', explode(',', $atts['tag_id']))),
        'format_id' => array_filter(array_map('intval', explode(',', $atts['format_id']))),
        'audience_id' => array_filter(array_map('intval', explode(',', $atts['audience_id']))),
        'max_listings' => $max_listings,
        'opening_max_items' => $opening_max_items,
        'initial_max_listings' => $initial_max_listings,
        'filters' => $is_simple_filter_mode ? 'simple' : 'full',
    ];

    ob_start();

    global $wpdb;

    // Fetch matching program areas from DB only when the full filter UI is needed.
    $area_filter_ids = $initial_filters['area_id'];
    $format_filter_ids = $initial_filters['format_id'];
    $tag_filter_ids = $initial_filters['tag_id'];
    $audience_filter_ids = $initial_filters['audience_id'];

    $area_options = '';
    $format_options = '';
    $tag_options = '';
    $audience_options = '';

    if (!$is_simple_filter_mode) {
        $areas_table = $wpdb->prefix . 'leanwi_lm_program_area';
        $formats_table = $wpdb->prefix . 'leanwi_lm_formats';
        $tags_table = $wpdb->prefix . 'leanwi_lm_tags';
        $audience_table = $wpdb->prefix . 'leanwi_lm_audience';

        $area_options = leanwi_lm_build_filter_options($areas_table, 'area_id', 'name', $area_filter_ids);
        $format_options = leanwi_lm_build_filter_options($formats_table, 'format_id', 'name', $format_filter_ids);
        $tag_options = leanwi_lm_build_filter_options($tags_table, 'tag_id', 'name', $tag_filter_ids);
        $audience_options = leanwi_lm_build_filter_options($audience_table, 'audience_id', 'name', $audience_filter_ids);
    }

    ?>
    <div id="leanwi-link-manager-container">
        <div id="leanwi-link-manager-filters" class="leanwi-lm-filter-panel">
            <?php if (!$is_simple_filter_mode) : ?>
                <div class="leanwi-lm-filter-panel__header">
                    <h3>Refine Links</h3>
                    <p>Use the filters below to narrow the list by area, audience, format, date, tag, or keyword.</p>
                </div>
            <?php endif; ?>

            <form id="leanwi-link-manager-form" class="leanwi-lm-filter-form">
                <div class="leanwi-lm-filter-grid">
                    <?php if (!$is_simple_filter_mode) : ?>
                        <div class="leanwi-lm-field">
                            <label for="leanwi-area-filter">Program Area</label>
                            <select name="area_id" id="leanwi-area-filter">
                                <option value="" <?php selected(empty($area_filter_ids)); ?>>All program areas</option>
                                <?php echo $area_options; ?>
                            </select>
                            <small>Filter links by program area.</small>
                        </div>

                        <div class="leanwi-lm-field">
                            <label for="leanwi-audience-filter">Audience</label>
                            <select name="audience_id" id="leanwi-audience-filter">
                                <option value="" <?php selected(empty($audience_filter_ids)); ?>>All audiences</option>
                                <?php echo $audience_options; ?>
                            </select>
                            <small>Show items matching a specific audience.</small>
                        </div>

                        <div class="leanwi-lm-field">
                            <label for="leanwi-format-filter">Format</label>
                            <select name="format_id" id="leanwi-format-filter">
                                <option value="" <?php selected(empty($format_filter_ids)); ?>>All formats</option>
                                <?php echo $format_options; ?>
                            </select>
                            <small>Filter by resource type or format.</small>
                        </div>

                        <div class="leanwi-lm-field">
                            <label for="leanwi-tag-filter">Tag</label>
                            <select name="tag_id" id="leanwi-tag-filter">
                                <option value="" <?php selected(empty($tag_filter_ids)); ?>>All tags</option>
                                <?php echo $tag_options; ?>
                            </select>
                            <small>Show items matching a specific tag.</small>
                        </div>

                        <div class="leanwi-lm-field">
                            <label for="leanwi-start-date">Start Date</label>
                            <input type="date" name="start_date" id="leanwi-start-date">
                            <small>Only show links on or after this date.</small>
                        </div>

                        <div class="leanwi-lm-field">
                            <label for="leanwi-end-date">End Date</label>
                            <input type="date" name="end_date" id="leanwi-end-date">
                            <small>Only show links on or before this date.</small>
                        </div>
                    <?php else : ?>
                        <input type="hidden" name="area_id" value="<?php echo esc_attr(implode(',', $initial_filters['area_id'])); ?>">
                        <input type="hidden" name="audience_id" value="<?php echo esc_attr(implode(',', $initial_filters['audience_id'])); ?>">
                        <input type="hidden" name="tag_id" value="<?php echo esc_attr(implode(',', $initial_filters['tag_id'])); ?>">
                        <input type="hidden" name="format_id" value="<?php echo esc_attr(implode(',', $initial_filters['format_id'])); ?>">
                    <?php endif; ?>

                    <div class="leanwi-lm-field leanwi-lm-field--wide">
                        <label for="leanwi-search-filter">Keyword Search</label>
                        <input type="text" name="search" id="leanwi-search-filter" placeholder="Search titles, descriptions, or keywords">
                        <small>Search within the available links.</small>
                    </div>
                </div>

                <?php if (!$is_simple_filter_mode) : ?>
                    <div class="leanwi-lm-actions">
                        <button type="submit" class="leanwi-lm-button leanwi-lm-button--primary">Apply Filters</button>
                        <button type="button" id="leanwi-clear-filters" class="leanwi-lm-button leanwi-lm-button--secondary">Clear Filters</button>
                    </div>
                <?php endif; ?>

                <input type="hidden" name="action" value="leanwi_filter_links">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('leanwi_filter_links')); ?>">
                <input type="hidden" name="initial_area_id" value="<?php echo esc_attr(implode(',', $initial_filters['area_id'])); ?>">
                <input type="hidden" name="initial_audience_id" value="<?php echo esc_attr(implode(',', $initial_filters['audience_id'])); ?>">
                <input type="hidden" name="initial_tag_id" value="<?php echo esc_attr(implode(',', $initial_filters['tag_id'])); ?>">
                <input type="hidden" name="initial_format_id" value="<?php echo esc_attr(implode(',', $initial_filters['format_id'])); ?>">
                <input type="hidden" name="max_listings" value="<?php echo esc_attr($initial_max_listings); ?>">
                <input type="hidden" name="filters" value="<?php echo esc_attr($initial_filters['filters']); ?>">
            </form>
        </div>

        <div id="leanwi-link-manager-results" aria-live="polite">Loading...</div>
    </div>

    <script>
        window.LEANWI_LINK_MANAGER_INITIAL = <?php echo wp_json_encode($initial_filters); ?>;
    </script>

    <?php
    return ob_get_clean();
}
