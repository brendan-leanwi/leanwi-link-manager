jQuery(document).ready(function($) {
    const $form = $('#leanwi-link-manager-form');
    const $results = $('#leanwi-link-manager-results');
    const $maxListings = $form.find('input[name="max_listings"]');

    let searchTimer = null;
    let currentRequest = null;

    const initialConfig = typeof window.LEANWI_LINK_MANAGER_INITIAL !== 'undefined'
        ? window.LEANWI_LINK_MANAGER_INITIAL
        : {};

    const filterMode = initialConfig.filters || 'full';
    const defaultMaxListings = parseInt(initialConfig.max_listings ?? 0, 10);
    const openingMaxItems = parseInt(initialConfig.opening_max_items ?? -1, 10);
    const initialMaxListings = openingMaxItems >= 0 ? openingMaxItems : defaultMaxListings;

    function showLoading() {
        $results.html(`
            <div class="leanwi-lm-loading" role="status" aria-live="polite">
                <div class="leanwi-lm-loading__spinner" aria-hidden="true"></div>
                <p>Loading links...</p>
            </div>
        `);
    }

    function showOpeningEmptyState() {
        $results.html(`
            <div class="leanwi-lm-empty" role="status" aria-live="polite">
                <p>Enter a keyword to search links.</p>
            </div>
        `);
    }

    function loadLinks(useOpeningLimit) {
        console.trace('loadLinks called', useOpeningLimit);
        if (useOpeningLimit && initialMaxListings === 0) {
            showOpeningEmptyState();
            return;
        }

        $maxListings.val(useOpeningLimit ? initialMaxListings : defaultMaxListings);

        showLoading();

        if (currentRequest && currentRequest.readyState !== 4) {
            currentRequest.abort();
        }

        let requestData = $form.serialize();

        if (useOpeningLimit) {
            requestData += '&opening_request=1';
        }

        console.log('LEANWI_LINK_MANAGER_INITIAL', initialConfig);
        console.log('defaultMaxListings', defaultMaxListings);
        console.log('openingMaxItems', openingMaxItems);
        console.log('initialMaxListings', initialMaxListings);
        console.log('requestData', requestData);

        currentRequest = $.ajax({
            type: 'POST',
            url: LEANWI_LINK_MANAGER_AJAX.ajax_url,
            data: requestData,
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
        const searchValue = $.trim($form.find('input[name="search"]').val());

        if (filterMode === 'simple' && searchValue === '') {
            if (currentRequest && currentRequest.readyState !== 4) {
                currentRequest.abort();
            }

            showOpeningEmptyState();
            return;
        }

        loadLinks(false);
    }

    $form.on('submit', function(e) {
        e.preventDefault();
        submitFilters();
    });

    // Initial load
    console.log('initialMaxListings:', initialMaxListings);

    if (initialMaxListings === 0) {
        console.log('showOpeningEmptyState()');
        showOpeningEmptyState();
    } else {
        console.log('loadLinks(true)');
        loadLinks(true);
    }

    // Auto-submit keyword search in both full and simple modes
    $form.on('input', 'input[name="search"]', function() {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(function() {
            submitFilters();
        }, 400);
    });

    if (filterMode !== 'simple') {
        $form.on('change', 'select, input[type="date"]', function() {
            submitFilters();
        });

        $('#leanwi-clear-filters').on('click', function() {
            $form.find('select[name="area_id"]').val('');
            $form.find('select[name="format_id"]').val('');
            $form.find('select[name="tag_id"]').val('');
            $form.find('select[name="audience_id"]').val('');
            $form.find('input[name="start_date"]').val('');
            $form.find('input[name="end_date"]').val('');
            $form.find('input[name="search"]').val('');

            clearTimeout(searchTimer);
            submitFilters();
        });
    }
});