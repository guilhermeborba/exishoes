(function ($) {

    "use strict";


    window.lastudioScrollNavigation = function ($selector, settings) {
        var self = this,
            $window = $(window),
            $document = $(document),
            $instance = $selector,
            $htmlBody = $('html, body'),
            $itemsList = $('.lastudio-scroll-navigation__item', $instance),
            sectionList = [],
            defaultSettings = {
                speed: 500,
                blockSpeed: 500,
                offset: 200,
                sectionSwitch: false
            },
            settings = $.extend({}, defaultSettings, settings),
            sections = {},
            currentSection = null,
            isScrolling = false,
            isSwipe = false,
            hash = window.location.hash.slice(1),
            timeout = null,
            timeStamp = 0,
            platform = navigator.platform;

        $.extend($.easing, {
            easeInOutCirc: function (x, t, b, c, d) {
                if ((t /= d / 2) < 1) return -c / 2 * (Math.sqrt(1 - t * t) - 1) + b;
                return c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
            }
        });

        /**
         * [init description]
         * @return {[type]} [description]
         */
        self.init = function () {
            self.setSectionsData();

            // Add Events
            $itemsList.on('click.lastudioScrollNavigation', self.onAnchorChange);

            $window.on('scroll.lastudioScrollNavigation', self.onScroll);
            $window.on('resize.lastudioScrollNavigation orientationchange.lastudioScrollNavigation', LaStudioElementTools.debounce(50, self.onResize));
            $window.on('load', function () {
                self.setSectionsData();
            });

            $document.keydown(function (event) {

                if (38 == event.keyCode) {
                    self.directionSwitch(event, 'up');
                }

                if (40 == event.keyCode) {
                    self.directionSwitch(event, 'down');
                }
            });

            if (settings.sectionSwitch) {

                if ('onwheel' in window) {
                    // onwheel check handler
                }

                $document.on('mousewheel.lastudioScrollNavigation DOMMouseScroll.lastudioScrollNavigation', self.onWheel);

                if (self.mobileAndTabletcheck()) {
                    var touchstartY = 0,
                        touchendY = 0;

                    $document.on('touchstart', function (event) {
                        var originalEvent = event.originalEvent;

                        isSwipe = true;

                        touchstartY = originalEvent.changedTouches[0].screenY;

                    });

                    $document.on('touchend', function (event) {
                        var originalEvent = event.originalEvent;

                        isSwipe = false;

                        touchendY = originalEvent.changedTouches[0].screenY;

                        if (touchendY < touchstartY) {
                            self.directionSwitch(event, 'down');
                        }

                        if (touchendY > touchstartY) {
                            self.directionSwitch(event, 'up');
                        }
                    });

                }
            }

            if (hash && sections.hasOwnProperty(hash)) {
                $itemsList.addClass('invert');
            }

            for (var section in sections) {
                var $section = sections[section].selector;

                elementorFrontend.waypoint($section, function (direction) {
                    var $this = $(this),
                        sectionId = $this.attr('id');

                    if ('down' === direction && !isScrolling && !isSwipe) {
                        window.history.pushState(null, null, '#' + sectionId);
                        currentSection = sectionId;
                        $itemsList.removeClass('active');
                        $('[data-anchor=' + sectionId + ']', $instance).addClass('active');

                        $itemsList.removeClass('invert');

                        if (sections[sectionId].invert) {
                            $itemsList.addClass('invert');
                        }
                    }
                }, {
                    offset: '95%',
                    triggerOnce: false
                });

                elementorFrontend.waypoint($section, function (direction) {
                    var $this = $(this),
                        sectionId = $this.attr('id');

                    if ('up' === direction && !isScrolling && !isSwipe) {
                        window.history.pushState(null, null, '#' + sectionId);
                        currentSection = sectionId;
                        $itemsList.removeClass('active');
                        $('[data-anchor=' + sectionId + ']', $instance).addClass('active');

                        $itemsList.removeClass('invert');

                        if (sections[sectionId].invert) {
                            $itemsList.addClass('invert');
                        }
                    }
                }, {
                    offset: '0%',
                    triggerOnce: false
                });
            }
        };

        /**
         * [onAnchorChange description]
         * @param  {[type]} event [description]
         * @return {[type]}       [description]
         */
        self.onAnchorChange = function (event) {
            var $this = $(this),
                sectionId = $this.data('anchor'),
                offset = null;

            if (!sections.hasOwnProperty(sectionId)) {
                return false;
            }

            offset = sections[sectionId].offset - settings.offset;

            if (!isScrolling) {
                isScrolling = true;
                window.history.pushState(null, null, '#' + sectionId);
                currentSection = sectionId;

                $itemsList.removeClass('active');
                $this.addClass('active');

                $itemsList.removeClass('invert');

                if (sections[sectionId].invert) {
                    $itemsList.addClass('invert');
                }

                $htmlBody.stop().clearQueue().animate({'scrollTop': offset}, settings.speed, 'easeInOutCirc', function () {
                    isScrolling = false;
                });
            }
        };

        /**
         * [directionSwitch description]
         * @param  {[type]} event     [description]
         * @param  {[type]} direction [description]
         * @return {[type]}           [description]
         */
        self.directionSwitch = function (event, direction) {
            var direction = direction || 'up',
                sectionId,
                nextItem = $('[data-anchor=' + currentSection + ']', $instance).next(),
                prevItem = $('[data-anchor=' + currentSection + ']', $instance).prev();

            //event.preventDefault();

            if (isScrolling) {
                return false;
            }

            if ('up' === direction) {
                if (prevItem[0]) {
                    prevItem.trigger('click.lastudioScrollNavigation');
                }
            }

            if ('down' === direction) {
                if (nextItem[0]) {
                    nextItem.trigger('click.lastudioScrollNavigation');
                }
            }
        };

        /**
         * [onScroll description]
         * @param  {[type]} event [description]
         * @return {[type]}       [description]
         */
        self.onScroll = function (event) {
            /* On Scroll Event */
            if (isScrolling || isSwipe) {
                event.preventDefault();
            }
        };

        /**
         * [onWheel description]
         * @param  {[type]} event [description]
         * @return {[type]}       [description]
         */
        self.onWheel = function (event) {
            if (isScrolling || isSwipe) {
                event.preventDefault();
                return false;
            }

            var $target = $(event.target),
                $section = $target.closest('.elementor-top-section'),
                sectionId = $section.attr('id'),
                offset = 0,
                newSectionId = false,
                prevSectionId = false,
                nextSectionId = false,
                delta = event.originalEvent.wheelDelta || -event.originalEvent.detail,
                direction = (0 < delta) ? 'up' : 'down',
                windowScrollTop = $window.scrollTop();

            if (self.beforeCheck()) {
                sectionId = LaStudioElementTools.getObjectFirstKey(sections);
            }

            if (self.afterCheck()) {
                sectionId = LaStudioElementTools.getObjectLastKey(sections);
            }

            if (sectionId && sections.hasOwnProperty(sectionId)) {

                prevSectionId = LaStudioElementTools.getObjectPrevKey(sections, sectionId);
                nextSectionId = LaStudioElementTools.getObjectNextKey(sections, sectionId);

                if ('up' === direction) {
                    if (!nextSectionId && sections[sectionId].offset < windowScrollTop) {
                        newSectionId = sectionId;
                    } else {
                        newSectionId = prevSectionId;
                    }
                }

                if ('down' === direction) {
                    if (!prevSectionId && sections[sectionId].offset > windowScrollTop + 5) {
                        newSectionId = sectionId;
                    } else {
                        newSectionId = nextSectionId;
                    }
                }

                if (newSectionId) {

                    if (event.timeStamp - timeStamp > 10 && 'MacIntel' == platform) {
                        timeStamp = event.timeStamp;
                        event.preventDefault();
                        return false;
                    }

                    event.preventDefault();

                    offset = sections[newSectionId].offset - settings.offset;
                    window.history.pushState(null, null, '#' + newSectionId);
                    currentSection = newSectionId;

                    $itemsList.removeClass('active');
                    $('[data-anchor=' + newSectionId + ']', $instance).addClass('active');

                    $itemsList.removeClass('invert');

                    if (sections[newSectionId].invert) {
                        $itemsList.addClass('invert');
                    }

                    isScrolling = true;
                    self.scrollStop();
                    $htmlBody.animate({'scrollTop': offset}, settings.blockSpeed, 'easeInOutCirc', function () {
                        isScrolling = false;
                    });
                }
            }

        };

        /**
         * [setSectionsData description]
         */
        self.setSectionsData = function () {
            $itemsList.each(function () {
                var $this = $(this),
                    sectionId = $this.data('anchor'),
                    sectionInvert = 'yes' === $this.data('invert') ? true : false,
                    $section = $('#' + sectionId);

                $section.addClass('lastudio-scroll-navigation-section');
                $section.attr({'touch-action': 'none'});

                if ($section[0]) {
                    sections[sectionId] = {
                        selector: $section,
                        offset: Math.round($section.offset().top),
                        height: $section.outerHeight(),
                        invert: sectionInvert
                    };
                }
            });
        };


        /**
         * [beforeCheck description]
         * @param  {[type]} event [description]
         * @return {[type]}       [description]
         */
        self.beforeCheck = function (event) {
            var windowScrollTop = $window.scrollTop(),
                firstSectionId = LaStudioElementTools.getObjectFirstKey(sections),
                offset = sections[firstSectionId].offset,
                topBorder = windowScrollTop + $window.outerHeight();

            if (topBorder > offset) {
                return false;
            }

            return true;
        };

        /**
         * [afterCheck description]
         * @param  {[type]} event [description]
         * @return {[type]}       [description]
         */
        self.afterCheck = function (event) {
            var windowScrollTop = $window.scrollTop(),
                lastSectionId = LaStudioElementTools.getObjectLastKey(sections),
                offset = sections[lastSectionId].offset,
                bottomBorder = sections[lastSectionId].offset + sections[lastSectionId].height;

            if (windowScrollTop < bottomBorder) {
                return false;
            }

            return true;
        };

        /**
         * [onResize description]
         * @param  {[type]} event [description]
         * @return {[type]}       [description]
         */
        self.onResize = function (event) {
            self.setSectionsData();
        };

        /**
         * [scrollStop description]
         * @return {[type]} [description]
         */
        self.scrollStop = function () {
            $htmlBody.stop(true);
        };

        /**
         * Mobile and tablet check funcion.
         *
         * @return {boolean} Mobile Status
         */
        self.mobileAndTabletcheck = function () {
            var check = false;

            (function (a) {
                if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
            })(navigator.userAgent || navigator.vendor || window.opera);

            return check;
        };

    }

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-scroll-navigation.default', function ($scope) {

            var $target = $scope.find('.lastudio-scroll-navigation'),
                instance = null,
                settings = $target.data('settings');

            instance = new lastudioScrollNavigation($target, settings);
            instance.init();

        });
    });

}(jQuery));