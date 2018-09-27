(function ($) {
    "use strict";

    function initInputmask() {
        $('.amount').inputmask("integer", {removeMaskOnSubmit: false});
    }

    function initConfirmationLink() {
        $('body').on('click', '.confirmation-btn', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var msg = $(this).attr('data-title') || 'Are you sure?';

            if ($(this).is('input') && $(this).parents().is("form")) {
                var form = $(this).parents('form');

                bootbox.confirm(msg, function(result) {
                    if (result) {
                        form.submit();
                    }
                });

            } else {
                bootbox.confirm(msg, function(result) {
                    if (result) {
                        document.location.href = url;
                    }
                });
            }

            return false;
        });
    }

    function initDateField() {
        if (jQuery().datepicker) {
            $('.date-picker, .input-daterange').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayBtn: "linked"
            });
        }
    }

    function initBootstrapSelect() {
        $('.bs-select').selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        });
    }

    $(document).ready(function(){
        initInputmask();
        initConfirmationLink();
        initDateField();
        initBootstrapSelect();
    });

}(window.jQuery));