jQuery(document).ready(function($) {
    const $form = $('#leanwi-link-manager-form');
    const $results = $('#leanwi-link-manager-results');
    let searchTimer = null;
    let currentRequest = null;

    function showLoading() {
        $results.html(`
            <div class="leanwi-lm-loading" role="status" aria-live="polite">
                <div class="leanwi-lm-loading__spinner" aria-hidden="true"></div>
                <p>Loading links...</p>
            </div>
        `);
    }

    function loadLinks(formData) {
        showLoading();

        // Abort any in-progress request so only the latest filter/search matters
        if (currentRequest && currentRequest.readyState !== 4) {
            currentRequest.abort();
        }

        currentRequest = $.ajax({
            type: 'POST',
            url: LEANWI_LINK_MANAGER_AJAX.ajax_url,
            data: formData,
            dataType: 'html'
        }).done(function(response) {
            $results.html(response);
        }).fail(function(xhr, status) {
            if (status !== 'abort') {
                $results.html(`
                    <div class="leanwi-lm-error" role="alert">
                        Sorry, something went wrong while loading the links.
                    </div>
                `);
            }
        });
    }

    function submitFilters() {
        loadLinks($form.serialize());
    }

    $form.on('submit', function(e) {
        e.preventDefault();
        submitFilters();
    });

    // Initial load
    submitFilters();

    // Auto-submit for dropdowns and dates
    $form.on('change', 'select, input[type="date"]', function() {
        submitFilters();
    });

    // Debounced keyword search
    $form.on('input', 'input[name="search"]', function() {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(function() {
            submitFilters();
        }, 400);
    });

    // Clear filters and auto-submit
    $('#leanwi-clear-filters').on('click', function() {
        $form.find('select[name="area_id"]').val('');
        $form.find('select[name="format_id"]').val('');
        $form.find('select[name="tag_id"]').val('');
        $form.find('input[name="start_date"]').val('');
        $form.find('input[name="end_date"]').val('');
        $form.find('input[name="search"]').val('');

        clearTimeout(searchTimer);
        submitFilters();
    });
});