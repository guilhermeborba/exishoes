(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-dropbar.default', function ($scope) {

            var $dropbar = $scope.find('.lastudio-dropbar'),
                $dropbar_inner = $dropbar.find('.lastudio-dropbar__inner'),
                $btn = $dropbar.find('.lastudio-dropbar__button'),
                $content = $dropbar.find('.lastudio-dropbar__content'),
                $close = $dropbar.find('.btn-close-dropbar'),
                settings = $dropbar.data('settings') || {},
                mode = settings['mode'] || 'hover',
                hide_delay = +settings['hide_delay'] || 0,
                activeClass = 'lastudio-dropbar-open',
                scrollOffset,
                timer;

            if ('click' === mode) {
                $btn.on('click.lastudioDropbar', function (e) {
                    $dropbar.toggleClass(activeClass);
                });
                $close.on('click.lastudioDropbar', function (e){
                    $dropbar.removeClass(activeClass);
                });
            }
            else {
                if ('ontouchstart' in window || 'ontouchend' in window) {
                    $btn.on('touchend.lastudioDropbar', function (e) {
                        if ($(window).scrollTop() !== scrollOffset) {
                            return;
                        }

                        $dropbar.toggleClass(activeClass);
                    });
                } else {
                    $dropbar_inner.on('mouseenter.lastudioDropbar', function (e) {
                        clearTimeout(timer);
                        $dropbar.addClass(activeClass);
                    });

                    $dropbar_inner.on('mouseleave.lastudioDropbar', function (e) {
                        timer = setTimeout(function () {
                            $dropbar.removeClass(activeClass);
                        }, hide_delay);
                    });
                }
            }

            $(document).on('touchstart.lastudioDropbar', function (e) {
                scrollOffset = $(window).scrollTop();
            });

            $(document).on('click.lastudioDropbar touchend.lastudioDropbar', function (event) {

                if ('touchend' === event.type && $(window).scrollTop() !== scrollOffset) {
                    return;
                }

                if ($(event.target).closest($btn).length || $(event.target).closest($content).length) {
                    return;
                }

                if (!$dropbar.hasClass(activeClass)) {
                    return;
                }

                $dropbar.removeClass(activeClass);
            });
        });
    });

}(jQuery));