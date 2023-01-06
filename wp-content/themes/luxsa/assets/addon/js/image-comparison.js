(function ($) {

    "use strict";

    function initCarousel($target, options) {

        var laptopSlides, tabletPortraitSlides, tabletSlides, mobileSlides, mobilePortraitSlides, defaultOptions,
            slickOptions;

        laptopSlides = parseInt(options.slidesToShow.laptop) || 1;
        tabletSlides = parseInt(options.slidesToShow.tablet) || laptopSlides;
        tabletPortraitSlides = parseInt(options.slidesToShow.tabletportrait) || tabletSlides;
        mobileSlides = parseInt(options.slidesToShow.mobile) || tabletPortraitSlides;
        mobilePortraitSlides = parseInt(options.slidesToShow.mobileportrait) || mobileSlides;

        options.slidesToShow = parseInt(options.slidesToShow.desktop) || 1;

        defaultOptions = {
            customPaging: function (slider, i) {
                return $('<span />').text(i + 1);
            },
            dotsClass: 'lastudio-slick-dots',
            responsive: [
                {
                    breakpoint: 1600,
                    settings: {
                        slidesToShow: laptopSlides,
                        slidesToScroll: laptopSlides
                    }
                },
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: tabletSlides,
                        slidesToScroll: tabletSlides
                    }
                },
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: tabletPortraitSlides,
                        slidesToScroll: tabletPortraitSlides
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: mobileSlides,
                        slidesToScroll: mobileSlides
                    }
                },
                {
                    breakpoint: 577,
                    settings: {
                        slidesToShow: mobilePortraitSlides,
                        slidesToScroll: mobilePortraitSlides
                    }
                }
            ]
        };

        slickOptions = $.extend({}, defaultOptions, options);

        var setup_slick = function (){
            $target.slick(slickOptions);
            if ($target.closest('.slick-allow-scroll').length > 0 && $(window).width() > 1200) {
                $target.on('wheel', (function (e) {
                    e.preventDefault();
                    if (e.originalEvent.deltaY < 0) {
                        $(this).slick('slickNext');
                    }
                    else {
                        $(this).slick('slickPrev');
                    }
                }));

            }
        }

        if("function" === typeof $.fn.slick){
            setup_slick();
        }
        else{
            LaStudio.global.loadDependencies([ LaStudio.global.loadJsFile('slick') ], setup_slick );
        }

        $(window).on('load', function () {
            setTimeout(function () {
                $('.la-lazyload-image', $target).each(function () {
                    LaStudioElementTools.makeImageAsLoaded(this);
                });
            }, 500)
        })

    }

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-image-comparison.default', function ($scope) {

            var $target = $scope.find('.lastudio-image-comparison__instance'),
                instance = null,
                imageComparisonItems = $('.lastudio-image-comparison__container', $target),
                settings = $target.data('settings'),
                elementId = $scope.data('id');

            if (!$target.length) {
                return;
            }

            window.juxtapose.scanPage('.lastudio-juxtapose');

            settings.draggable = false;
            settings.infinite = false;
            //settings.adaptiveHeight = true;
            initCarousel($target, settings);

        });
    });

}(jQuery));