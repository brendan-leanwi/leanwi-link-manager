<?php
namespace LEANWI_Link_Manager;

add_shortcode('link_manager_list', __NAMESPACE__ . '\\leanwi_link_manager_shortcode');


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
        'area_id' => '',            // example: area_id="1,2"
        'tag_id' => '',             // example: tag_id="1,2"
        'format_id' => '',          // example: format_id="1,2"
        'audience_id' => '',        // example: audience_id="1,2"
        'max_listings' => '0',      // Default to 0 = no limit
    ], $atts, 'link_manager_list');

    //error_log('max-listings as passed: ' . intval($atts['max_listings']));
    
    // Prepare initial filter data for JS
    $initial_filters = [
        'area_id' => array_filter(array_map('intval', explode(',', $atts['area_id']))),
        'tag_id' => array_filter(array_map('intval', explode(',', $atts['tag_id']))),
        'format_id' => array_filter(array_map('intval', explode(',', $atts['format_id']))),
        'audience_id' => array_filter(array_map('intval', explode(',', $atts['audience_id']))),
        'max_listings' => intval($atts['max_listings']),
    ];

    ob_start();

    global $wpdb;

    // Fetch matching program areas from DB
    $areas_table = $wpdb->prefix . 'leanwi_lm_program_area';
    $formats_table = $wpdb->prefix . 'leanwi_lm_formats';
    $tags_table = $wpdb->prefix . 'leanwi_lm_tags';
    $audience_table = $wpdb->prefix . 'leanwi_lm_audience';

    $area_filter_ids = $initial_filters['area_id'];
    $format_filter_ids = $initial_filters['format_id'];
    $tag_filter_ids = $initial_filters['tag_id'];
    $audience_filter_ids = $initial_filters['audience_id'];

    $area_options = leanwi_lm_build_filter_options($areas_table, 'area_id', 'name', $area_filter_ids);
    $format_options = leanwi_lm_build_filter_options($formats_table, 'format_id', 'name', $format_filter_ids);
    $tag_options = leanwi_lm_build_filter_options($tags_table, 'tag_id', 'name', $tag_filter_ids);
    $audience_options = leanwi_lm_build_filter_options($audience_table, 'audience_id', 'name', $audience_filter_ids);

    ?>
    <div id="leanwi-link-manager-container">
        <div id="leanwi-link-manager-filters" class="leanwi-lm-filter-panel">
            <div class="leanwi-lm-filter-panel__header">
                <h3>Refine Links</h3>
                <p>Use the filters below to narrow the list by area, audience, format, date, tag, or keyword.</p>
            </div>

            <form id="leanwi-link-manager-form" class="leanwi-lm-filter-form">
                <div class="leanwi-lm-filter-grid">
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

                    <div class="leanwi-lm-field leanwi-lm-field--wide">
                        <label for="leanwi-search-filter">Keyword Search</label>
                        <input type="text" name="search" id="leanwi-search-filter" placeholder="Search titles, descriptions, or keywords">
                        <small>Search within the available links.</small>
                    </div>
                </div>

                <div class="leanwi-lm-actions">
                    <button type="submit" class="leanwi-lm-button leanwi-lm-button--primary">Apply Filters</button>
                    <button type="button" id="leanwi-clear-filters" class="leanwi-lm-button leanwi-lm-button--secondary">Clear Filters</button>
                </div>

                <input type="hidden" name="action" value="leanwi_filter_links">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('leanwi_filter_links')); ?>">
                <input type="hidden" name="initial_area_id" value="<?php echo esc_attr(implode(',', $initial_filters['area_id'])); ?>">
                <input type="hidden" name="initial_audience_id" value="<?php echo esc_attr(implode(',', $initial_filters['audience_id'])); ?>">
                <input type="hidden" name="initial_tag_id" value="<?php echo esc_attr(implode(',', $initial_filters['tag_id'])); ?>">
                <input type="hidden" name="initial_format_id" value="<?php echo esc_attr(implode(',', $initial_filters['format_id'])); ?>">
                <input type="hidden" name="max_listings" value="<?php echo esc_attr((int) $atts['max_listings']); ?>">
            </form>
        </div>

        <div id="leanwi-link-manager-results" aria-live="polite">Loading...</div>
    </div>

    <script>
        const LEANWI_LINK_MANAGER_INITIAL = <?php echo wp_json_encode($initial_filters); ?>;
    </script>

    <script>
        document.getElementById('leanwi-clear-filters').addEventListener('click', function() {
            const form = document.getElementById('leanwi-link-manager-form');

            // Reset select dropdowns
            const areaSelect = form.querySelector('select[name="area_id"]');
            const formatSelect = form.querySelector('select[name="format_id"]');
            const tagSelect = form.querySelector('select[name="tag_id"]');
            const audienceSelect = form.querySelector('select[name="audience_id"]');

            areaSelect.value = form.querySelector('input[name="initial_area_id"]').value || '';
            formatSelect.value = form.querySelector('input[name="initial_format_id"]').value || '';
            tagSelect.value = form.querySelector('input[name="initial_tag_id"]').value || '';
            audienceSelect.value = form.querySelector('input[name="initial_audience_id"]').value || '';

            // Reset date inputs
            form.querySelector('input[name="start_date"]').value = '';
            form.querySelector('input[name="end_date"]').value = '';

            // Reset keyword search
            form.querySelector('input[name="search"]').value = '';

            // Submit the form automatically to refresh results
            form.submit();
        });
    </script>


    <?php
    return ob_get_clean();
}
