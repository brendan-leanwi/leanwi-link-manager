<?php
// Include WordPress functions
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// Verify the nonce before processing the form
if (!isset($_POST['leanwi_generate_report_nonce']) || !wp_verify_nonce($_POST['leanwi_generate_report_nonce'], 'leanwi_generate_report')) {
    wp_die('Nonce verification failed. Please reload the page and try again.');
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch data from the database
    global $wpdb;
    $links_table = $wpdb->prefix . 'leanwi_lm_links';  

    // Create a CSV file
    $upload_dir = wp_upload_dir();
    $csv_file_path = $upload_dir['basedir'] . '/leanwi_lm_reports/';
    $csv_file_url = $upload_dir['baseurl'] . '/leanwi_lm_reports/';

    // Ensure the reports folder exists
    if (!file_exists($csv_file_path)) {
        wp_mkdir_p($csv_file_path);
    }

    // Check the number of existing reports
    $report_files = glob($csv_file_path . '*.csv');
    if (count($report_files) > 100) {
        wp_die('You have reached the maximum number of saved reports (100). You will need to delete old reports before creating any new ones.');
    }

    $csv_filename = 'revise_links_report_' . time() . '.csv';

    $csv_file_path .= $csv_filename;
    $csv_file_url .= $csv_filename;

    // Open the file for writing
    $file = fopen($csv_file_path, 'w');

    if ($file === false) {
        die('Could not open the file for writing.');
    }

    // Add UTF-8 BOM so Excel opens it correctly
    fwrite($file, "\xEF\xBB\xBF");

    if ($file === false) {
        die('Could not open the file for writing.');
    }

    // Add summary data regardless of category or audience
    $sql = "
        SELECT
            l.title AS 'Link Title',
            Date(l.creation_date) AS 'Date Created',
            l.description AS 'Link Description',
            CASE 
                WHEN l.is_featured_link = 1 THEN 'Yes' 
                ELSE 'No' 
            END AS 'Featured Link?',
            Date(l.revise_date) AS 'Revise By Date',
            f.name AS 'Format',
            a.name AS 'Program Area'
        FROM 
            $links_table l
        LEFT JOIN {$wpdb->prefix}leanwi_lm_formats f 
            ON l.format_id = f.format_id
        LEFT JOIN {$wpdb->prefix}leanwi_lm_program_area a 
            ON l.area_id = a.area_id
        WHERE 
            revise_date < CURRENT_TIMESTAMP
        ORDER BY revise_date ASC
    ";
    
    $results = $wpdb->get_results(
        $wpdb->prepare($sql),
        ARRAY_A
    );

    if (!empty($results)) {
        // Add heading for the summary section
        fputcsv($file, [' ']);

        fputcsv($file, ['~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~']);
        fputcsv($file, ["Current Links Requiring Revise Date Revisions or Deletion:"]);
         fputcsv($file, [' ']);

        // Add column headers to the CSV file
        fputcsv($file, array_keys($results[0]));

        // Add data rows to the CSV file
        foreach ($results as $row) {
            fputcsv($file, $row);
        }
    }
    
    fputcsv($file, [' ']);

    fclose($file);

    // Redirect to the CSV file for download
    header('Location: ' . $csv_file_url);
    exit;
}
?>
