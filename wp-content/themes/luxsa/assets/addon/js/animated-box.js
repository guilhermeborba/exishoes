(function ($) {

    "use strict";

    function onAnimatedBoxSectionActivated( $scope ) {
        if (!window.elementor) {
            return;
        }

        if (!window.LaStudioElementEditor) {
            return;
        }

        if (!window.LaStudioElementEditor.activeSection) {
            return;
        }

        var section = window.LaStudioElementEditor.activeSection;
        var isBackSide = -1 !== ['section_back_content', 'section_action_button_style'].indexOf(section);

        if (isBackSide) {
            $scope.find('.lastudio-animated-box').addClass('flipped');
            $scope.find('.lastudio-animated-box').addClass('flipped-stop');
        } else {
            $scope.find('.lastudio-animated-box').removeClass('flipped');
            $scope.find('.lastudio-animated-box').removeClass('flipped-stop');
        }
    }

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-animated-box.default', function ($scope) {
            onAnimatedBoxSectionActivated($scope);

            var $target = $scope.find('.lastudio-animated-box'),
                toogleEvents = 'mouseenter mouseleave',
                scrollOffset = $(window).scrollTop(),
                firstMouseEvent = true;

            if (!$target.length) {
                return;
            }

            if ('ontouchend' in window || 'ontouchstart' in window) {
                $target.on('touchstart', function (event) {
                    scrollOffset = $(window).scrollTop();
                });

                $target.on('touchend', function (event) {

                    if (scrollOffset !== $(window).scrollTop()) {
                        return false;
                    }

                    if (!$(this).hasClass('flipped-stop')) {
                        $(this).toggleClass('flipped');
                    }
                });

            } else {
                $target.on(toogleEvents, function (event) {

                    if (firstMouseEvent && 'mouseleave' === event.type) {
                        return;
                    }

                    if (firstMouseEvent && 'mouseenter' === event.type) {
                        firstMouseEvent = false;
                    }

                    if (!$(this).hasClass('flipped-stop')) {
                        $(this).toggleClass('flipped');
                    }
                });
            }
        });
    });

}(jQuery));