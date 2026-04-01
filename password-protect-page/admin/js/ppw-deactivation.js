jQuery(function ($) {

    if (typeof PPWDeactivate === 'undefined') {
        return;
    }

    let deactivateUrl = '';
    let pluginType = ''; // 'free' or 'pro'
    var slug = ''; // 'free' or 'pro'

    /* ===============================
       Capture deactivate click
       =============================== */

    $(document).on(
        'click',
        '.deactivate a[id^="deactivate-"]',
        function (e) {

            const id = this.id;
            
            deactivateUrl = $(this).attr('href');

             if (id === 'deactivate-password-protect-page') {
                pluginType = 'free';
                slug = 'password-protect-page';
            } else if (id === 'deactivate-password-protect-wordpress-pro') {
                pluginType = 'pro';
                slug = 'password-protect-wordpress-pro';
            } else {
                return; // Not our plugin
            }

            if (!this.id.includes(slug)) {
                return;
            }else{
                e.preventDefault();
            }

            // Open modal (flex-safe)
            $('#ppw-feedback-overlay').data('plugin', pluginType).addClass('is-open');
        }
    );

    /* ===============================
       Submit feedback
       =============================== */

    jQuery(document).ready(function ($) {

        $('.ppw-submit').on('click', function (e) {
            e.preventDefault();

            const $btn = $(this);

            if ($btn.hasClass('loading')) {
                return;
            }

            $btn.addClass('loading');
            $btn.prop('disabled', true);
            $btn.find('.spinner').addClass('is-active');

            $btn.find('.ppw-btn-text').hide();
            $btn.find('.ppw-loader').show();

            var pluginType = $('#ppw-feedback-overlay').data('plugin');

            $.ajax({
                url: PPWDeactivate.ajax,
                type: 'POST',
                data: {
                    action: 'ppw_store_deactivation_feedback',
                    nonce: PPWDeactivate.nonce,
                    reason: $('input[name="reason"]:checked').val() || '',
                    not_working_reason: $('#not_working_reason').val() || '',
                    optional_detail: $('#optional_detail').val() || '',
                    pluginType: pluginType,
                    better_plugin_name : $('#better_plugin_name').val() || '',
                },
                success: function (response) {
                    if (response && response.success === true) {
                        // Continue deactivation
                        window.location.href = deactivateUrl;
                    } else {
                        resetButton($btn);
                    }
                },
                error: function () {
                    resetButton($btn);
                }
            });
        });

        function resetButton($btn) {
            $btn.removeClass('loading');
            $btn.prop('disabled', false);
            $btn.find('.ppw-loader').hide();
            $btn.find('.ppw-btn-text').show();
        }

    });


    /* ===============================
       Skip feedback
       =============================== */

    $('.ppw-skip').on('click', function () {
        window.location.href = deactivateUrl;
    });

    /* ===============================
       Close modal interactions
       =============================== */

    // Close on outside click
    $('#ppw-feedback-overlay').on('click', function () {
        $(this).removeClass('is-open');
    });

    // Prevent close when clicking inside modal
    $('#ppw-feedback-modal').on('click', function (e) {
        e.stopPropagation();
    });

    // Close on ESC
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            $('#ppw-feedback-overlay').removeClass('is-open');
        }
    });

    /* ===============================
    Conditional extra field
    =============================== */

    $('input[name="reason"]').on('change', function () {

        // Hide all extra fields first
        $('.ppw-reason-extra').hide('slow');

        // Show extra field only for "better_plugin"
        if ($(this).val() === 'not_working') {
            $(this)
                .closest('label')
                .next('.ppw-reason-extra')
                .show('slow');
        }
    });


    $('input[name="reason"]').on('change', function () {

        // Hide all extra fields first
        $('.better_plugin_name').hide('slow');

        // Show extra field only for "better_plugin"
        if ($(this).val() === 'better_plugin') {
            $(this)
                .closest('label')
                .next('.better_plugin_name')
                .show('slow');
        }
    });

});
