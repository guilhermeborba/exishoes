(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-horizontal-timeline.default', function ($scope) {

            var $timeline = $scope.find('.lastudio-hor-timeline'),
                $timelineTrack = $scope.find('.lastudio-hor-timeline-track'),
                $items = $scope.find('.lastudio-hor-timeline-item'),
                $arrows = $scope.find('.lastudio-arrow'),
                $nextArrow = $scope.find('.lastudio-next-arrow'),
                $prevArrow = $scope.find('.lastudio-prev-arrow'),
                columns = $timeline.data('columns') || {},
                desktopColumns = columns.desktop || 3,
                tabletColumns = columns.tablet || desktopColumns,
                mobileColumns = columns.mobile || tabletColumns,
                firstMouseEvent = true,
                currentDeviceMode = LaStudioElementTools.getCurrentDevice(),
                prevDeviceMode = currentDeviceMode,
                itemsCount = $scope.find('.lastudio-hor-timeline-list--middle .lastudio-hor-timeline-item').length,
                currentTransform = 0,
                currentPosition = 0,
                transform = {
                    desktop: 100 / desktopColumns,
                    tablet: 100 / tabletColumns,
                    mobile: 100 / mobileColumns
                },
                maxPosition = {
                    desktop: Math.max(0, (itemsCount - desktopColumns)),
                    tablet: Math.max(0, (itemsCount - tabletColumns)),
                    mobile: Math.max(0, (itemsCount - mobileColumns))
                };

            if ('ontouchstart' in window || 'ontouchend' in window) {
                $items.on('touchend.lastudioHorTimeline', function (event) {
                    var itemId = $(this).data('item-id');

                    $scope.find('.elementor-repeater-item-' + itemId).toggleClass('is-hover');
                });
            }
            else {
                $items.on('mouseenter.lastudioHorTimeline mouseleave.lastudioHorTimeline', function (event) {
                    if (firstMouseEvent && 'mouseleave' === event.type) {
                        return;
                    }
                    if (firstMouseEvent && 'mouseenter' === event.type) {
                        firstMouseEvent = false;
                    }
                    var itemId = $(this).data('item-id');
                    $scope.find('.elementor-repeater-item-' + itemId).toggleClass('is-hover');
                });
            }

            // Set Line Position
            setLinePosition();
            $(window).on('resize.lastudioHorTimeline orientationchange.lastudioHorTimeline', setLinePosition);

            function setLinePosition() {
                var $line = $scope.find('.lastudio-hor-timeline__line'),
                    $firstPoint = $scope.find('.lastudio-hor-timeline-item__point-content:first'),
                    $lastPoint = $scope.find('.lastudio-hor-timeline-item__point-content:last'),
                    firstPointLeftPos = $firstPoint.position().left + parseInt($firstPoint.css('marginLeft')),
                    lastPointLeftPos = $lastPoint.position().left + parseInt($lastPoint.css('marginLeft')),
                    pointWidth = $firstPoint.outerWidth();

                $line.css({
                    'left': firstPointLeftPos + pointWidth / 2,
                    'width': lastPointLeftPos - firstPointLeftPos
                });

                var $progressLine   = $scope.find( '.lastudio-hor-timeline__line-progress' ),
                    $lastActiveItem = $scope.find( '.lastudio-hor-timeline-list--middle .lastudio-hor-timeline-item.is-active:last' );

                if ( $lastActiveItem[0] ) {
                    var $lastActiveItemPointWrap = $lastActiveItem.find( '.lastudio-hor-timeline-item__point' ),
                        progressLineWidth        = $lastActiveItemPointWrap.position().left + $lastActiveItemPointWrap.outerWidth() - firstPointLeftPos - pointWidth / 2;

                    $progressLine.css( {
                        'width': progressLineWidth
                    } );
                }
            }

            // Arrows Navigation Type
            if ($nextArrow[0] && maxPosition[currentDeviceMode] === 0) {
                $nextArrow.addClass('lastudio-arrow-disabled');
            }

            if ($arrows[0]) {
                $arrows.on('click.lastudioHorTimeline', function (event) {
                    var $this = $(this),
                        direction = $this.hasClass('lastudio-next-arrow') ? 'next' : 'prev',
                        currentDeviceMode = LaStudioElementTools.getCurrentDevice();

                    if ('next' === direction && currentPosition < maxPosition[currentDeviceMode]) {
                        currentTransform -= transform[currentDeviceMode];
                        currentPosition += 1;
                    }

                    if ('prev' === direction && currentPosition > 0) {
                        currentTransform += transform[currentDeviceMode];
                        currentPosition -= 1;
                    }

                    if (currentPosition > 0) {
                        $prevArrow.removeClass('lastudio-arrow-disabled');
                    } else {
                        $prevArrow.addClass('lastudio-arrow-disabled');
                    }

                    if (currentPosition === maxPosition[currentDeviceMode]) {
                        $nextArrow.addClass('lastudio-arrow-disabled');
                    } else {
                        $nextArrow.removeClass('lastudio-arrow-disabled');
                    }

                    if (currentPosition === 0) {
                        currentTransform = 0;
                    }

                    $timelineTrack.css({
                        'transform': 'translateX(' + currentTransform + '%)'
                    });

                });
            }

            setArrowPosition();
            $(window).on('resize.lastudioHorTimeline orientationchange.lastudioHorTimeline', setArrowPosition);
            $(window).on('resize.lastudioHorTimeline orientationchange.lastudioHorTimeline', timelineSliderResizeHandler);

            function setArrowPosition() {
                if (!$arrows[0]) {
                    return;
                }

                var $middleList = $scope.find('.lastudio-hor-timeline-list--middle'),
                    middleListTopPosition = $middleList.position().top,
                    middleListHeight = $middleList.outerHeight();

                $arrows.css({
                    'top': middleListTopPosition + middleListHeight / 2
                });
            }

            function timelineSliderResizeHandler(event) {
                if (!$timeline.hasClass('lastudio-hor-timeline--arrows-nav')) {
                    return;
                }

                var currentDeviceMode = LaStudioElementTools.getCurrentDevice(),
                    resetSlider = function () {
                        $prevArrow.addClass('lastudio-arrow-disabled');

                        if ($nextArrow.hasClass('lastudio-arrow-disabled')) {
                            $nextArrow.removeClass('lastudio-arrow-disabled');
                        }

                        if (maxPosition[currentDeviceMode] === 0) {
                            $nextArrow.addClass('lastudio-arrow-disabled');
                        }

                        currentTransform = 0;
                        currentPosition = 0;

                        $timelineTrack.css({
                            'transform': 'translateX(0%)'
                        });
                    };

                switch (currentDeviceMode) {
                    case 'desktop':
                        if ('desktop' !== prevDeviceMode) {
                            resetSlider();
                            prevDeviceMode = 'desktop';
                        }
                        break;

                    case 'tablet':
                        if ('tablet' !== prevDeviceMode) {
                            resetSlider();
                            prevDeviceMode = 'tablet';
                        }
                        break;

                    case 'mobile':
                        if ('mobile' !== prevDeviceMode) {
                            resetSlider();
                            prevDeviceMode = 'mobile';
                        }
                        break;
                }
            }

        });
    });

}(jQuery));