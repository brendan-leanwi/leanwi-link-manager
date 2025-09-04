jQuery(document).ready(function($) {

    function loadLinks(formData) {
        $('#leanwi-link-manager-results').html('Loading...');

        $.ajax({
            type: 'POST',
            url: LEANWI_LINK_MANAGER_AJAX.ajax_url,
            data: formData, // now a string
            dataType: 'html'
        }).done(function(response) {
            $('#leanwi-link-manager-results').html(response);
        });
    }

    // Initial load with all area_ids from shortcode
    let initial = LEANWI_LINK_MANAGER_INITIAL;
    let data = {
        action: 'leanwi_filter_links',
        nonce: $('input[name="nonce"]').val(),
        'area_id[]': initial.area_id, 
        'format_id[]': initial.format_id,
        'tag_id[]': initial.tag_id,
        initial_area_id: initial.area_id.join(','),
        initial_format_id: initial.format_id.join(','),
        initial_tag_id: initial.tag_id.join(','),
        max_listings: initial.max_listings,
    };

    loadLinks(data);

    // If you want to keep the filter form for later, keep this too:
    $('#leanwi-link-manager-form').on('submit', function(e) {
        e.preventDefault();

        // Use native FormData instead of serializeArray
        let formData = $(this).serialize();

        // Optionally, log to check
        //console.log('Submitting with data:', formData);

        loadLinks(formData);
    });
});
