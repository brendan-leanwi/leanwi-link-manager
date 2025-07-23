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
        'featured_only' => '',      // example: featured_only="true"
    ], $atts, 'link_manager_list');

    // Prepare initial filter data for JS
    $initial_filters = [
        'area_id' => array_filter(array_map('intval', explode(',', $atts['area_id']))),
        'tag_id' => array_filter(array_map('intval', explode(',', $atts['tag_id']))),
        'format_id' => array_filter(array_map('intval', explode(',', $atts['format_id']))),
        'featured_only' => ($atts['featured_only'] === '1' || strtolower($atts['featured_only']) === 'true') ? 1 : 0,
    ];

    ob_start();
    ?>
    <div id="leanwi-link-manager-container">
        <div id="leanwi-link-manager-filters">
            <h3>Refine Links Search</h3>
            <form id="leanwi-link-manager-form">
                <!-- Program Area Filter -->
                <label>Program Area:
                    <select name="area_id">
                        <option value="">All</option>
                    </select>
                </label>

                <!-- Format Filter -->
                <label>Format:
                    <select name="format_id">
                        <option value="">All</option>
                    </select>
                </label>

                <!-- Tags Filter -->
                <div id="leanwi-link-manager-tags"></div>

                <!-- Keyword Search -->
                <label>Keyword:
                    <input type="text" name="search">
                </label>

                <!-- Featured Only -->
                <label>
                    <input type="checkbox" name="featured_only"> Featured Only
                </label>

                <button type="submit">Refine List</button>

                <input type="hidden" name="action" value="leanwi_filter_links">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('leanwi_filter_links')); ?>">
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
