(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-table.default', function ($scope) {

            var $target = $scope.find('.lastudio-table'),
                options = {
                    cssHeader: 'lastudio-table-header-sort',
                    cssAsc: 'lastudio-table-header-sort--up',
                    cssDesc: 'lastudio-table-header-sort--down',
                    initWidgets: false
                };

            if (!$target.length) {
                return;
            }

            if ($target.hasClass('lastudio-table--sorting')) {
                $target.tablesorter(options);
            }

        });
    });

}(jQuery));