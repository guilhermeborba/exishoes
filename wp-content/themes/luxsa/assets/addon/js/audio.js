(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-audio.default', function ($scope) {
            var $wrapper = $scope.find('.lastudio-audio'),
                $player = $scope.find('.lastudio-audio-player'),
                settings = $wrapper.data('settings');

            if (!$player[0]) {
                return;
            }

            $player.mediaelementplayer({
                features: settings['controls'] || ['playpause', 'current', 'progress', 'duration', 'volume'],
                audioVolume: settings['audioVolume'] || 'horizontal',
                startVolume: settings['startVolume'] || 0.8,
                hideVolumeOnTouchDevices: settings['hideVolumeOnTouchDevices'],
                enableProgressTooltip: false,
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
        });
    });

}(jQuery));