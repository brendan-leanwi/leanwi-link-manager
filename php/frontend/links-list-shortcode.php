<?php
namespace LEANWI_Link_Manager;

add_shortcode('link_manager_list', __NAMESPACE__ . '\\leanwi_link_manager_shortcode');

function leanwi_link_manager_shortcode($atts) {
    wp_enqueue_script('leanwi-link-manager-ajax');
    wp_enqueue_style('leanwi-link-manager-style');

    // Parse shortcode attributes
    $atts = shortcode_atts([
        'area_id' => '',            // example: area_id="1,2"
        'tag_id' => '',             // example: tag_id="1,2"
        'format_id' => '',          // example: format_id="1,2"
        'max_listings' => '0',      // Default to 0 = no limit
    ], $atts, 'link_manager_list');

    //error_log('max-listings as passed: ' . intval($atts['max_listings']));
    
    // Prepare initial filter data for JS
    $initial_filters = [
        'area_id' => array_filter(array_map('intval', explode(',', $atts['area_id']))),
        'tag_id' => array_filter(array_map('intval', explode(',', $atts['tag_id']))),
        'format_id' => array_filter(array_map('intval', explode(',', $atts['format_id']))),
        'max_listings' => intval($atts['max_listings']),
    ];

    ob_start();

    global $wpdb;

    // Fetch matching program areas from DB
    $areas_table = $wpdb->prefix . 'leanwi_lm_program_area';
    $area_filter_ids = $initial_filters['area_id'];

    $area_options = '';
    if (!empty($area_filter_ids)) {
        $placeholders = implode(',', array_fill(0, count($area_filter_ids), '%d'));
        $query = "SELECT area_id, name FROM $areas_table WHERE area_id IN ($placeholders) ORDER BY display_order ASC";
        $prepared = $wpdb->prepare($query, $area_filter_ids);
        $area_results = $wpdb->get_results($prepared, ARRAY_A);

        foreach ($area_results as $area) {
            $area_id = (int) $area['area_id'];
            $area_name = esc_html($area['name']);
            $area_options .= "<option value=\"$area_id\">$area_name</option>";
        }
    } else {
        // If no filter passed, show all areas
        $area_results = $wpdb->get_results("SELECT area_id, name FROM $areas_table ORDER BY display_order ASC", ARRAY_A);
        foreach ($area_results as $area) {
            $area_id = (int) $area['area_id'];
            $area_name = esc_html($area['name']);
            $area_options .= "<option value=\"$area_id\">$area_name</option>";
        }
    }

    // Fetch matching formats from DB
    $formats_table = $wpdb->prefix . 'leanwi_lm_formats';
    $format_filter_ids = $initial_filters['format_id'];

    $format_options = '';
    if (!empty($format_filter_ids)) {
        $placeholders = implode(',', array_fill(0, count($format_filter_ids), '%d'));
        $query = "SELECT format_id, name FROM $formats_table WHERE format_id IN ($placeholders) ORDER BY display_order ASC";
        $prepared = $wpdb->prepare($query, $format_filter_ids);
        $format_results = $wpdb->get_results($prepared, ARRAY_A);

        foreach ($format_results as $format) {
            $format_id = (int) $format['format_id'];
            $format_name = esc_html($format['name']);
            $format_options .= "<option value=\"$format_id\">$format_name</option>";
        }
    } else {
        // If no format filters passed, show all formats
        $format_results = $wpdb->get_results("SELECT format_id, name FROM $formats_table ORDER BY display_order ASC", ARRAY_A);
        foreach ($format_results as $format) {
            $format_id = (int) $format['format_id'];
            $format_name = esc_html($format['name']);
            $format_options .= "<option value=\"$format_id\">$format_name</option>";
        }
    }

    // Fetch matching tags from DB
    $tags_table = $wpdb->prefix . 'leanwi_lm_tags';
    $tag_filter_ids = $initial_filters['tag_id'];

    $tag_options = '';
    if (!empty($tag_filter_ids)) {
        $placeholders = implode(',', array_fill(0, count($tag_filter_ids), '%d'));
        $query = "SELECT tag_id, name FROM $tags_table WHERE tag_id IN ($placeholders) ORDER BY display_order ASC";
        $prepared = $wpdb->prepare($query, $tag_filter_ids);
        $tag_results = $wpdb->get_results($prepared, ARRAY_A);

        foreach ($tag_results as $tag) {
            $tag_id = (int) $tag['tag_id'];
            $tag_name = esc_html($tag['name']);
            $tag_options .= "<option value=\"$tag_id\">$tag_name</option>";
        }
    } else {
        // If no tag filters passed, show all tags
        $tag_results = $wpdb->get_results("SELECT tag_id, name FROM $tags_table ORDER BY display_order ASC", ARRAY_A);
        foreach ($tag_results as $tag) {
            $tag_id = (int) $tag['tag_id'];
            $tag_name = esc_html($tag['name']);
            $tag_options .= "<option value=\"$tag_id\">$tag_name</option>";
        }
    }

    ?>
    <div id="leanwi-link-manager-container">
        <div id="leanwi-link-manager-filters">
            <h3>Refine Links Search</h3>
            <form id="leanwi-link-manager-form">
                <!-- Program Area Filter -->
                <label>Program Area:
                    <select name="area_id" id="related-links-filter">
                        <option value="" <?php echo empty($area_filter_ids) ? 'selected' : ''; ?>>All</option>
                        <?php echo $area_options; ?>
                    </select>
                </label>

                <!-- Format Filter -->
                <label>Format:
                    <select name="format_id" id="leanwi-format-filter">
                        <option value="" <?php echo empty($format_filter_ids) ? 'selected' : ''; ?>>All</option>
                        <?php echo $format_options; ?>
                    </select>
                </label>

                <!-- Date Range Filters -->
                <label>Start Date:
                    <input type="date" name="start_date">
                </label>
                <label>End Date:
                    <input type="date" name="end_date">
                </label>

                <!-- Tags Filter -->
                <label>Tags:
                    <select name="tag_id" id="leanwi-tag-filter">
                        <option value="" <?php echo empty($tag_filter_ids) ? 'selected' : ''; ?>>All</option>
                        <?php echo $tag_options; ?>
                    </select>
                </label>

                <!-- Keyword Search -->
                <label>Keyword:
                    <input type="text" name="search">
                </label>

                <button type="submit">Refine List</button>

                <input type="hidden" name="action" value="leanwi_filter_links">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('leanwi_filter_links')); ?>">
                <input type="hidden" name="initial_area_id" value="<?php echo esc_attr(implode(',', $initial_filters['area_id'])); ?>">
                <input type="hidden" name="initial_tag_id" value="<?php echo esc_attr(implode(',', $initial_filters['tag_id'])); ?>">
                <input type="hidden" name="initial_format_id" value="<?php echo esc_attr(implode(',', $initial_filters['format_id'])); ?>">
                <input type="hidden" name="max_listings" value="<?php echo esc_attr((int)$atts['max_listings']); ?>">
            </form>
        </div>

        <div id="leanwi-link-manager-results">Loading...</div>
    </div>

    <script>
        const LEANWI_LINK_MANAGER_INITIAL = <?php echo json_encode($initial_filters); ?>;
    </script>

    <?php
    return ob_get_clean();
}
