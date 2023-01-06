(function ($) {

    "use strict";

    function init_video( $scope ) {
        var $video = $scope.find('.lastudio-video'),
            $iframe = $scope.find('.lastudio-video-iframe'),
            $videoPlaer = $scope.find('.lastudio-video-player'),
            $mejsPlaer = $scope.find('.lastudio-video-mejs-player'),
            mejsPlaerControls = $mejsPlaer.data('controls') || ['playpause', 'current', 'progress', 'duration', 'volume', 'fullscreen'],
            $overlay = $scope.find('.lastudio-video__overlay'),
            hasOverlay = $overlay.length > 0,
            settings = $video.data('settings') || {},
            autoplay = settings.autoplay || false;

        if ($overlay[0]) {
            $overlay.on('click.lastudioVideo', function (event) {

                if ($videoPlaer[0]) {
                    $videoPlaer[0].play();

                    $overlay.remove();
                    hasOverlay = false;

                    return;
                }

                if ($iframe[0]) {
                    iframeStartPlay();
                }
            });
        }

        if (autoplay && $iframe[0] && $overlay[0]) {
            iframeStartPlay();
        }

        function iframeStartPlay() {
            var lazyLoad = $iframe.data('lazy-load');

            if (lazyLoad) {
                $iframe.attr('src', lazyLoad);
            }

            if (!autoplay) {
                $iframe[0].src = $iframe[0].src.replace('&autoplay=0', '&autoplay=1');
            }

            $overlay.remove();
            hasOverlay = false;
        }

        if ($videoPlaer[0]) {
            $videoPlaer.on('play.lastudioVideo', function (event) {
                if (hasOverlay) {
                    $overlay.remove();
                    hasOverlay = false;
                }
            });
        }

        if ($mejsPlaer[0]) {
            $mejsPlaer.mediaelementplayer({
                videoVolume: 'horizontal',
                hideVolumeOnTouchDevices: false,
                enableProgressTooltip: false,
                features: mejsPlaerControls,
                success: function (media) {
                    media.addEventListener('timeupdate', function (event) {
                        var $currentTime = $scope.find('.mejs-time-current'),
                            inlineStyle = $currentTime.attr('style');

                        if (inlineStyle) {
                            var scaleX = inlineStyle.match(/scaleX\([0-9.]*\)/gi)[0].replace('scaleX(', '').replace(')', '');

                            if (scaleX) {
                                $currentTime.css('width', scaleX * 100 + '%');
                            }
                        }
                    }, false);
                }
            });
        }
    }

    $(window).on('elementor/frontend/init', function () {

        if(window.elementorFrontend && window.elementorFrontend.hooks){
            window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-video.default', init_video );
        }

    });

}(jQuery));