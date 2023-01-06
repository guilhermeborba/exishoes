(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        var SlidesHandler = elementorModules.frontend.handlers.Base.extend({
            getDefaultSettings: function getDefaultSettings() {
                return {
                    selectors: {
                        slider: '.elementor-slides',
                        slideContent: '.elementor-slide-content'
                    },
                    classes: {
                        animated: 'animated'
                    },
                    attributes: {
                        dataSliderOptions: 'slider_options',
                        dataAnimation: 'animation'
                    }
                };
            },

            getDefaultElements: function getDefaultElements() {
                var selectors = this.getSettings('selectors');

                return {
                    $slider: this.$element.find(selectors.slider)
                };
            },

            initSlider: function initSlider() {
                var $slider = this.elements.$slider,
                    _setting = this.getSettings('attributes.dataSliderOptions');

                if (!$slider.length) {
                    return;
                }

                var setup_slick = function (){
                    if(!$slider.hasClass('slick-initialized')){

                        var data_setting = $slider.data(_setting);
                        data_setting.customPaging = function(slider, i) {
                            return $( '<span />' ).text( i + 1 );
                        };
                        data_setting.dotsClass = 'lastudio-slick-dots';
                        $slider.on('init', function (e, slick){
                            var _arrow = $('<div/>').addClass('fake-controls').append($('.slick-arrow', $slider).clone());
                            _arrow.appendTo($slider.find('.elementor-slide-content'));
                        });
                        $slider.on('click', '.fake-controls .prev-arrow', function (e){
                            $slider.slick('slickPrev');
                        });
                        $slider.on('click', '.fake-controls .next-arrow', function (e){
                            $slider.slick('slickNext');
                        });
                        if( typeof LaStudio ==="undefined" || (typeof LaStudio !=="undefined" && !LaStudio.global.isPageSpeed()) ){
                            $slider.slick(data_setting);
                        }
                    }
                }

                if("function" === typeof $.fn.slick){
                    setup_slick();
                }
                else{
                    LaStudio.global.loadDependencies([ LaStudio.global.loadJsFile('slick') ], setup_slick );
                }
            },

            goToActiveSlide: function goToActiveSlide() {
                try{
                    var _settings = this.elements.$slider.data('slider_options');
                    if(_settings.slidesToShow == 1){
                        this.elements.$slider.slick('slickGoTo', this.getEditSettings('activeItemIndex') - 1);
                    }
                    else{
                        this.elements.$slider.find('.slick-track').css('transform', 'none');
                    }
                }catch (ex){}
            },

            onPanelShow: function onPanelShow() {
                var $slider = this.elements.$slider;
                try {
                    $slider.slick('slickPause');
                }catch (ex){}

                // On switch between slides while editing. stop again.
                $slider.on('afterChange', function () {
                    $slider.slick('slickPause');
                });
            },

            bindEvents: function bindEvents() {
                var $slider = this.elements.$slider,
                    settings = this.getSettings(),
                    animation = $slider.data(settings.attributes.dataAnimation);

                if (!animation) {
                    return;
                }

                if (elementorFrontend.isEditMode()) {
                    elementor.hooks.addAction('panel/open_editor/widget/slides', this.onPanelShow);
                }

                $slider.on({
                    beforeChange: function beforeChange() {
                        var $sliderContent = $slider.find(settings.selectors.slideContent);
                        $sliderContent.removeClass(settings.classes.animated + ' ' + animation).hide();
                    },
                    afterChange: function afterChange(event, slick, currentSlide) {
                        var $currentSlide = slick.$slider.find( '.slick-active ' + settings.selectors.slideContent);
                        $currentSlide.show().addClass(settings.classes.animated + ' ' + animation);
                    }
                });
            },

            onInit: function onInit() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

                this.initSlider();

                if (this.isEdit) {
                    this.goToActiveSlide();
                }
            },

            onEditSettingsChange: function onEditSettingsChange(propertyName) {
                if ('activeItemIndex' === propertyName) {
                    this.goToActiveSlide();
                }
            }
        });

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lastudio-slides.default', function ($scope) {
            new SlidesHandler({ $element: $scope });
        });
    });

}(jQuery));