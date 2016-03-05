(function ($) {
    "use strict";

    window.CalendarControl = function (id) {
        var exposed = {};

        function change(is_multi_day, has_times) {
            var options = {
                locale: {
                    format: 'DD/MM/YYYY'
                }
            };

            if (! is_multi_day) {
                $.extend(options, {
                    singleDatePicker: true,
                    showDropdowns: true
                });
            }

            if (has_times) {
                $.extend(options, {
                    timePicker: true,
                    timePickerIncrement: 5,
                    locale: {
                        format: 'DD/MM/YYYY h:mm A'
                    }
                });
            }

            var $input = $('input[name="akID['+id+'][value]"]'),
                picker = $input.data('daterangepicker');

            if (picker) {
                picker.remove();
            }

            if ($('#ccm-panel-detail-page-composer').length > 0) {
                $.extend(options, {
                    'parentEl': '#ccm-panel-detail-page-composer',
                });
            }

            $input.daterangepicker(options);
            $input.data('daterangepicker').show();
            $input.data('daterangepicker').hide();
        }

        (function init() {
            $('input[name="akID['+id+'][is_multi_day]"], input[name="akID['+id+'][is_all_day]"]').change(function (e) {

                var is_multi_day = false,
                    is_all_day = true;

                if ($('input[name="akID['+id+'][is_all_day]"]:checked').val() > 0) {
                    is_all_day = false;
                }

                if ($('input[name="akID['+id+'][is_multi_day]"]:checked').val() > 0) {
                    is_multi_day = true;
                }

                change(is_multi_day, is_all_day);
            });

            $('input[name="akID['+id+'][is_all_day]"]:checked').each(function (i, ele) {
                $(ele).trigger('change');
            });
        }());

        return exposed;
    };

}(jQuery));