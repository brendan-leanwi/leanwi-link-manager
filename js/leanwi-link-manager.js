jQuery(document).ready(function($) {
    function loadLinks(formData) {
        $('#leanwi-link-manager-results').html('Loading...');
        $.post(LEANWI_LINK_MANAGER_AJAX.ajax_url, formData, function(response) {
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
    };

    loadLinks(data);

    // If you want to keep the filter form for later, keep this too:
    $('#leanwi-link-manager-form').on('submit', function(e) {
        e.preventDefault();
        let data = $(this).serialize();
        loadLinks(data);
    });
});

