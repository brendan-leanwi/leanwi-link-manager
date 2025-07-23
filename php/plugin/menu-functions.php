<?php
namespace LEANWI_Link_Manager;
/**************************************************************************************************
 * Main Menu and Main Page
 **************************************************************************************************/

function leanwi_lm_add_admin_menu() {
    // Parent menu: "LEANWI Link Manager"
    add_menu_page(
        'LEANWI Link Manager',   // Page title (for the parent menu)
        'LEANWI Link Manager',     // Menu title (for the plugin name in the dashboard)
        'manage_options',         // Capability
        'leanwi-link-manager-main', // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_main_page',       // Callback function
        'dashicons-admin-links',     // Menu icon (optional)
        9                         // Position
    );

    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Documentation and Support',  // Page title (for the actual documentation page)
        'Documentation',              // Menu title (this will be the first submenu item)
        'manage_options',             // Capability
        'leanwi-link-manager-main',    // Menu slug (reuse 'leanwi-link-manager-main' to link it to the parent page)
        __NAMESPACE__ . '\\leanwi_main_page'            // Callback function (this will now display the Documentation page)
    );

    // Sub-menu: "Manage Links"
    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Manage Links',                     // Page title
        'Manage Links',                     // Menu title
        'manage_options',             // Capability
        'leanwi-lm-manage-links',  // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_manager_links_page'          // Callback function to display link management page
    );

    // Sub-menu: "Program Areas"
    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Program Areas',              // Page title
        'Program Areas',              // Menu title
        'manage_options',             // Capability
        'leanwi-lm-program-areas',    // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_program_areas_page' // Callback function
    );

    // Sub-menu: "Add Program Area"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Add Program Area',
        'Add Program Area',
        'manage_options',
        'leanwi-lm-add-program-area',
        __NAMESPACE__ . '\\leanwi_lm_add_program_area_page'
    );

    // Sub-menu: "Edit Program Area"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Edit Program Area',
        'Edit Program Area',
        'manage_options',
        'leanwi-lm-edit-program-area',
        __NAMESPACE__ . '\\leanwi_lm_edit_program_area_page'
    );

    // Sub-menu: "Formats"
    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Formats',                   // Page title
        'Formats',                   // Menu title
        'manage_options',             // Capability
        'leanwi-lm-formats',// Menu slug
        __NAMESPACE__ . '\\leanwi_lm_formats_page'        // Callback function to display Formats
    );

    // Sub-menu: "Add Format"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Add Format',
        'Add Format',
        'manage_options',
        'leanwi-lm-add-format',
        __NAMESPACE__ . '\\leanwi_lm_add_format_page'
    );

    // Sub-menu: "Edit Format"
    add_submenu_page(
        'leanwi-link-manager-main', // Parent slug (linked to Categories submenu)
        'Edit Format',                 // Page title
        'Edit Format',                 // Menu title
        'manage_options',             // Capability
        'leanwi-lm-edit-format',          // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_edit_format_page'      // Callback function to display the edit venue form
    );

    // Sub-menu: "Tags"
    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Tags',                       // Page title
        'Tags',                       // Menu title
        'manage_options',             // Capability
        'leanwi-lm-tags',             // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_tags_page' // Callback function
    );

    // Sub-menu: "Add Tag"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Add Tag',
        'Add Tag',
        'manage_options',
        'leanwi-lm-add-tag',
        __NAMESPACE__ . '\\leanwi_lm_add_tag_page'
    );

    // Sub-menu: "Edit Tag"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Edit Tag',
        'Edit Tag',
        'manage_options',
        'leanwi-lm-edit-tag',
        __NAMESPACE__ . '\\leanwi_lm_edit_tag_page'
    );

}


// Hook to create the admin menu
add_action('admin_menu', __NAMESPACE__ . '\\leanwi_lm_add_admin_menu');

// Hide the Add and Edit pages submenus from the left-hand navigation menu using CSS
function leanwi_hide_add_edit_submenus_css() {
    echo '<style>
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-add-program-area"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-edit-program-area"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-add-format"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-edit-format"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-add-tag"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-edit-tag"] {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', __NAMESPACE__ . '\\leanwi_hide_add_edit_submenus_css');


// Function to display the main page which is our documentation page
function leanwi_lm_main_page() {
    $doc_file = plugin_dir_path(dirname(dirname(__FILE__))) . 'docs/documentation.html';

    if (!file_exists($doc_file)) {
        $content = "<h2>Documentation Not Found</h2><p>Please ensure `documentation.html` exists in the `docs/` directory.</p>";
    } else {
        $content = file_get_contents($doc_file);
    }
    ?>

    <div class="wrap">
        <h1>LEANWI Link Manager Documentation</h1>
        <div id="documentation-content" style="border: 1px solid #ddd; padding: 15px; background: #fff;"></div>
    </div>

    <script>
        // Function to load the HTML file dynamically
        function loadHtmlPage(page) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>" + "docs/" + page, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var content = xhr.responseText;
                    document.getElementById("documentation-content").innerHTML = content;
                    //attachLinkEvents();  // Reattach link events after new content is loaded
                }
            };
            xhr.send();
        }

        // Load the default content (documentation.html)
        var content = <?php echo json_encode($content); ?>;
        document.getElementById("documentation-content").innerHTML = content;
    </script>

    <?php
}


/**************************************************************************************************
 * Manage Links
 **************************************************************************************************/

// Function to display the list of venues
function leanwi_lm_manager_links_page() {
    
}

/**************************************************************************************************
 * Program Areas
 **************************************************************************************************/
function leanwi_lm_program_areas_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_program_area';

    // Handle display order update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_display_order'])) {
        if (isset($_POST['display_order']) && is_array($_POST['display_order'])) {
            foreach ($_POST['display_order'] as $area_id => $display_order) {
                $area_id = intval($area_id);
                $display_order = intval($display_order);

                $wpdb->update(
                    $table_name,
                    ['display_order' => $display_order],
                    ['area_id' => $area_id],
                    ['%d'],
                    ['%d']
                );
            }
            echo '<div class="updated notice"><p>Display order updated successfully.</p></div>';
        }
    }

    // Fetch program areas
    $areas = $wpdb->get_results("SELECT * FROM $table_name ORDER BY display_order ASC", ARRAY_A);

    echo '<div class="wrap">';
    echo '<h1>Program Areas</h1>';

    echo '<a href="' . admin_url('admin.php?page=leanwi-lm-add-program-area') . '" class="button button-primary">Add Program Area</a>';
    echo '<p></p>';

    echo '<form method="POST">';
    echo '<table class="wp-list-table widefat striped">';
    echo '<thead><tr>
        <th>Area ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Display Order</th>
        <th>Actions</th>
    </tr></thead>';
    echo '<tbody>';

    if ($areas) {
        foreach ($areas as $area) {
            echo '<tr>';
            echo '<td>' . esc_html($area['area_id']) . '</td>';
            echo '<td>' . esc_html($area['name']) . '</td>';
            echo '<td>' . esc_html($area['description']) . '</td>';
            echo '<td><input type="number" name="display_order[' . esc_attr($area['area_id']) . ']" value="' . esc_attr($area['display_order']) . '" style="width:60px;"></td>';
            echo '<td><a href="' . admin_url('admin.php?page=leanwi-lm-edit-program-area&area_id=' . $area['area_id']) . '" class="button">Edit</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">No program areas found.</td></tr>';
    }

    echo '</tbody></table>';
    echo '<p><input type="submit" name="save_display_order" value="Save Display Order" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
}

function leanwi_lm_add_program_area_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_program_area';

    // Handle form submission
    if (isset($_POST['add_program_area'])) {
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));

        // Determine new display order
        $max_order = $wpdb->get_var("SELECT MAX(display_order) FROM $table_name");
        $new_order = ($max_order !== null) ? $max_order + 1 : 1;

        $wpdb->insert(
            $table_name,
            ['name' => $name, 'description' => $description, 'display_order' => $new_order],
            ['%s', '%s', '%d']
        );

        echo '<div class="updated"><p>Program Area added successfully.</p></div>';
    }

    // Display form
    echo '<div class="wrap">';
    echo '<h1>Add Program Area</h1>';
    echo '<form method="POST">';
    echo '<p>Program Area Name: <input type="text" name="name" required style="width:300px;"></p>';
    echo '<p>Program Area Description: <input type="text" name="description" style="width:600px;"></p>';
    echo '<p><input type="submit" name="add_program_area" value="Add Program Area" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
}

function leanwi_lm_edit_program_area_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_program_area';

    // Handle form submission
    if (isset($_POST['update_program_area'])) {
        $area_id = intval($_POST['area_id']);
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));

        $wpdb->update(
            $table_name,
            ['name' => $name, 'description' => $description],
            ['area_id' => $area_id],
            ['%s', '%s'],
            ['%d']
        );

        echo '<div class="updated"><p>Program Area updated successfully.</p></div>';
    }

    // Fetch program area for editing
    if (isset($_GET['area_id'])) {
        $area_id = intval($_GET['area_id']);
        $area = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE area_id = %d", $area_id));

        if ($area) {
            echo '<div class="wrap">';
            echo '<h1>Edit Program Area</h1>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="area_id" value="' . esc_attr($area->area_id) . '">';
            echo '<p>Program Area Name: <input type="text" name="name" value="' . esc_attr($area->name) . '" style="width:300px;"></p>';
            echo '<p>Program Area Description: <input type="text" name="description" value="' . esc_attr($area->description) . '" style="width:600px;"></p>';
            echo '<p><input type="submit" name="update_program_area" value="Save Changes" class="button button-primary"></p>';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<div class="error"><p>Program Area not found.</p></div>';
        }
    } else {
        echo '<div class="error"><p>No program area ID provided.</p></div>';
    }
}


/**************************************************************************************************
 * Formats
 **************************************************************************************************/

// Function to display the list of formats
function leanwi_lm_formats_page() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_formats';

    // Process display order update if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_display_order'])) {
        if (isset($_POST['display_order']) && is_array($_POST['display_order'])) {
            foreach ($_POST['display_order'] as $format_id => $display_order) {
                $format_id = intval($format_id);
                $display_order = intval($display_order);

                $wpdb->update(
                    $table_name,
                    ['display_order' => $display_order],
                    ['format_id' => $format_id],
                    ['%d'],
                    ['%d']
                );
            }
            echo '<div class="updated notice"><p>Display order updated successfully.</p></div>';
        }
    }

    // Display format list
    echo '<div class="wrap">';
    echo '<h1>Formats</h1>';

    echo '<a href="' . admin_url('admin.php?page=leanwi-lm-add-format') . '" class="button button-primary">Add Format</a>';
    echo '<p> </p>'; // Space below the button before the format table

    echo '<form method="POST">';
    echo '<table class="wp-list-table widefat striped">';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col">Format ID</th>';
    echo '<th scope="col">Format Name</th>';
    echo '<th scope="col">Display Order</th>';
    echo '<th scope="col">Uses Icon</th>';
    echo '<th scope="col">Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Fetch formats
    $formats = fetch_formats();
    if (isset($formats['error'])) {
        echo '<tr><td colspan="5">' . esc_html($formats['error']) . '</td></tr>';
    } else {
        // Display each format in a row
        foreach ($formats['formats'] as $format) {
            echo '<tr>';
            echo '<td>' . esc_html($format['format_id']) . '</td>';
            echo '<td>' . esc_html($format['name']) . '</td>';
            echo '<td><input type="number" name="display_order[' . esc_attr($format['format_id']) . ']" value="' . esc_attr($format['display_order']) . '" style="width: 60px;"></td>';
            echo '<td>' . ($format['use_icon'] ? 'Yes' : 'No') . '</td>';
            echo '<td>';
            echo '<a href="' . esc_url(admin_url('admin.php?page=leanwi-lm-edit-format&format_id=' . esc_attr($format['format_id']))) . '" class="button">Edit</a> ';
            echo '</td>';
            echo '</tr>';
        }
    }

    echo '</tbody>';
    echo '</table>';

    echo '<p><input type="submit" name="save_display_order" value="Save Display Order" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
    
}

// Function to get formats
function fetch_formats() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_formats';

    // Fetch formats
    $formats = $wpdb->get_results("SELECT format_id, name, display_order, use_icon FROM $table_name ORDER BY display_order ASC", ARRAY_A);

    if (empty($formats)) {
        return ['error' => 'No formats found.'];
    } else {
        return ['formats' => $formats];
    }
}

function leanwi_lm_add_format_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_formats';

    // Handle form submission
    if (isset($_POST['add_format'])) {
        // Find the max display_order and increment
        $max_order = $wpdb->get_var("SELECT MAX(display_order) FROM $table_name");
        $new_order = ($max_order !== null) ? $max_order + 1 : 1;

        $name = sanitize_text_field($_POST['name']);
        $name = wp_unslash($name);

        $description = sanitize_text_field($_POST['description']);
        $description = wp_unslash($description);

        $icon_url = isset($_POST['icon_url']) ? wp_unslash($_POST['icon_url']) : '';
        $icon_url = esc_url_raw($icon_url);

        $wpdb->insert(
            $table_name,
            ['name' => $name,
             'description' => $description,
             'icon_url' => $icon_url, 
             'use_icon' => isset($_POST['use_icon']) ? 1 : 0,
             'display_order' => $new_order],
            ['%s', '%s', '%s','%d', '%d']
        );
        echo '<div class="updated"><p>Format added successfully.</p></div>';
    }

    // Display the add format form
echo '<div class="wrap">';
echo '<h1>Add Format</h1>';
echo '<form method="POST">';
echo '<p>Format Name: <input type="text" name="name" required style="width: 300px;"></p>'; 
echo '<p>Format Description: <input type="text" name="description" style="width: 600px;"></p>'; 
echo '<p>Use Icon: <input type="checkbox" name="use_icon"></p>';    
echo '<p>Icon URL: <input type="text" name="icon_url" style="width: 600px;"></p>';
echo '<p><input type="submit" name="add_format" value="Add Format" class="button button-primary"></p>';
echo '</form>';
echo '</div>';

}

// Function to handle editing of a format
function leanwi_lm_edit_format_page() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'leanwi_lm_formats';

    // Handle the form submission to update the format
    if (isset($_POST['update_format'])) {
        $format_id = intval($_POST['format_id']);
        
        $name = sanitize_text_field($_POST['name']);
        $name = wp_unslash($name);

        $description = sanitize_text_field($_POST['description']);
        $description = wp_unslash($description);

        $icon_url = isset($_POST['icon_url']) ? wp_unslash($_POST['icon_url']) : '';
        $icon_url = esc_url_raw($icon_url);

        // Update the category in the database
        $wpdb->update(
            $table_name,
            ['name' => $name,
             'description' => $description,
             'icon_url' => $icon_url, 
             'use_icon' => isset($_POST['use_icon']) ? 1 : 0],
            ['format_id' => $format_id],
            ['%s', '%s','%s','%d'],
            ['%d']
        );

        echo '<div class="updated"><p>Format updated successfully.</p></div>';
    }

    // Check if a format ID is provided for editing
    if (isset($_GET['format_id'])) {
        $format_id = intval($_GET['format_id']);
        $format = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE format_id = %d", $format_id));

        if ($format) {
            // Display form to edit the format
            echo '<div class="wrap">';
            echo '<h1>Edit Format</h1>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="format_id" value="' . esc_attr($format->format_id) . '">';

            // Display the format name input
            echo '<p>Format Name: <input type="text" name="name" value="' . esc_attr($format->name) . '" class="regular-text" style="width:300px;"></p>';

            echo '<p>Format Description: <input type="text" name="description" value="' . esc_attr($format->description) . '" style="width:600px;"></p>';
            echo '<p>';
            echo '<label><input type="checkbox" name="use_icon" ' . checked($format->use_icon, 1, false) . '> Use Icon</label>';
            echo '</p>';    
            echo '<p>Icon URL: <input type="text" name="icon_url" value="' . esc_attr($format->icon_url) . '" style="width:600px;"></p>';

            // Submit button to update the format
            echo '<p><input type="submit" name="update_format" value="Save Changes" class="button button-primary"></p>';
            echo '</form>';
            echo '</div>';
        } else {
            // Display a message if the format is not found
            echo '<div class="error"><p>Format not found.</p></div>';
        }
    } else {
        // Redirect back if no format ID is provided
        echo '<div class="error"><p>No format ID provided.</p></div>';
    }
}

/**************************************************************************************************
 * Tags
 **************************************************************************************************/
function leanwi_lm_tags_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_tags';

    // Handle display order update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_display_order'])) {
        if (isset($_POST['display_order']) && is_array($_POST['display_order'])) {
            foreach ($_POST['display_order'] as $tag_id => $display_order) {
                $tag_id = intval($tag_id);
                $display_order = intval($display_order);

                $wpdb->update(
                    $table_name,
                    ['display_order' => $display_order],
                    ['tag_id' => $tag_id],
                    ['%d'],
                    ['%d']
                );
            }
            echo '<div class="updated notice"><p>Display order updated successfully.</p></div>';
        }
    }

    // Fetch tags
    $tags = $wpdb->get_results("SELECT * FROM $table_name ORDER BY display_order ASC", ARRAY_A);

    echo '<div class="wrap">';
    echo '<h1>Tags</h1>';

    echo '<a href="' . admin_url('admin.php?page=leanwi-lm-add-tag') . '" class="button button-primary">Add Tag</a>';
    echo '<p></p>';

    echo '<form method="POST">';
    echo '<table class="wp-list-table widefat striped">';
    echo '<thead><tr>
        <th>Tag ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Display Order</th>
        <th>Actions</th>
    </tr></thead>';
    echo '<tbody>';

    if ($tags) {
        foreach ($tags as $tag) {
            echo '<tr>';
            echo '<td>' . esc_html($tag['tag_id']) . '</td>';
            echo '<td>' . esc_html($tag['name']) . '</td>';
            echo '<td>' . esc_html($tag['description']) . '</td>';
            echo '<td><input type="number" name="display_order[' . esc_attr($tag['tag_id']) . ']" value="' . esc_attr($tag['display_order']) . '" style="width:60px;"></td>';
            echo '<td><a href="' . admin_url('admin.php?page=leanwi-lm-edit-tag&tag_id=' . $tag['tag_id']) . '" class="button">Edit</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">No tags found.</td></tr>';
    }

    echo '</tbody></table>';
    echo '<p><input type="submit" name="save_display_order" value="Save Display Order" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
}

function leanwi_lm_add_tag_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_tags';

    // Handle form submission
    if (isset($_POST['add_tag'])) {
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));

        // Determine new display order
        $max_order = $wpdb->get_var("SELECT MAX(display_order) FROM $table_name");
        $new_order = ($max_order !== null) ? $max_order + 1 : 1;

        $wpdb->insert(
            $table_name,
            ['name' => $name, 'description' => $description, 'display_order' => $new_order],
            ['%s', '%s', '%d']
        );

        echo '<div class="updated"><p>Tag added successfully.</p></div>';
    }

    // Display form
    echo '<div class="wrap">';
    echo '<h1>Add Tag</h1>';
    echo '<form method="POST">';
    echo '<p>Tag Name: <input type="text" name="name" required style="width:300px;"></p>';
    echo '<p>Tag Description: <input type="text" name="description" style="width:600px;"></p>';
    echo '<p><input type="submit" name="add_tag" value="Add Tag" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
}

function leanwi_lm_edit_tag_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_tags';

    // Handle form submission
    if (isset($_POST['update_tag'])) {
        $tag_id = intval($_POST['tag_id']);
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));

        $wpdb->update(
            $table_name,
            ['name' => $name, 'description' => $description],
            ['tag_id' => $tag_id],
            ['%s', '%s'],
            ['%d']
        );

        echo '<div class="updated"><p>Tag updated successfully.</p></div>';
    }

    // Fetch tag for editing
    if (isset($_GET['tag_id'])) {
        $tag_id = intval($_GET['tag_id']);
        $tag = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE tag_id = %d", $tag_id));

        if ($tag) {
            echo '<div class="wrap">';
            echo '<h1>Edit Tag</h1>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="tag_id" value="' . esc_attr($tag->tag_id) . '">';
            echo '<p>Tag Name: <input type="text" name="name" value="' . esc_attr($tag->name) . '" style="width:300px;"></p>';
            echo '<p>Tag Description: <input type="text" name="description" value="' . esc_attr($tag->description) . '" style="width:600px;"></p>';
            echo '<p><input type="submit" name="update_tag" value="Save Changes" class="button button-primary"></p>';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<div class="error"><p>Tag not found.</p></div>';
        }
    } else {
        echo '<div class="error"><p>No tag ID provided.</p></div>';
    }
}
