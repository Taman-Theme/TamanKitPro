(function ($) {
    var getElementSettings = function ($element) {
        var elementSettings = {};

        elementSettings = $element.data("settings") || {};

        return elementSettings;
    };

    var isEditMode = false;


    // Image Comparison Handler
    var TamanKitImageComparisonHandler = function ($scope, $) {

        var $imgCompareElem = $scope.find(".tk-images-compare-container"),
            settings = $imgCompareElem.data("settings");

        $imgCompareElem.imagesLoaded(function () {
            $imgCompareElem.twentytwenty({
                orientation: settings.orientation,
                default_offset_pct: settings.visibleRatio,
                switch_before_label: settings.switchBefore,
                before_label: settings.beforeLabel,
                switch_after_label: settings.switchAfter,
                after_label: settings.afterLabel,
                move_slider_on_hover: settings.mouseMove,
                click_to_move: settings.clickMove,
                show_drag: settings.showDrag,
                show_sep: settings.showSep,
                no_overlay: settings.overlay,
                horbeforePos: settings.beforePos,
                horafterPos: settings.afterPos,
                verbeforePos: settings.verbeforePos,
                verafterPos: settings.verafterPos
            });
        });
    };


    var TamanKitPricingTableHandler = function ($scope, $) {
        var id = $scope.data('id'),
            toolTopElm = $scope.find('.tk-pricing-table-tooptip[data-tooltip]'),
            elementSettings = getElementSettings($scope),
            ttArrow = elementSettings.tooltip_arrow,
            ttTrigger = elementSettings.tooltip_trigger,
            elementorBreakpoints = elementorFrontend.config.breakpoints;

        toolTopElm.each(function () {
            var ttPosition = $(this).data('tooltip-position'),
                ttTemplate = '',
                ttSize = $(this).data('tooltip-size'),
                animationIn = $(this).data('tooltip-animation-in'),
                animationOut = $(this).data('tooltip-animation-out');

            // tablet
            if (window.innerWidth <= elementorBreakpoints.lg && window.innerWidth >= elementorBreakpoints.md) {
                ttPosition = $scope.find('.tk-pricing-table-tooptip[data-tooltip]').data('tooltip-position-tablet');
            }

            // mobile
            if (window.innerWidth < elementorBreakpoints.md) {
                ttPosition = $scope.find('.tk-pricing-table-tooptip[data-tooltip]').data('tooltip-position-mobile');
            }

            if (ttArrow === 'yes') {
                ttTemplate = '<div class="tk-tooltip tk-tooltip-' + id + ' tk-tooltip-' + ttSize + '"><div class="tk-tooltip-body"><div class="tk-tooltip-content"></div><div class="tk-tooltip-callout"></div></div></div>';
            } else {
                ttTemplate = '<div class="tk-tooltip tk-tooltip-' + id + ' tk-tooltip-' + ttSize + '"><div class="tk-tooltip-body"><div class="tk-tooltip-content"></div></div></div>';
            }

            var tooltipConfig = {
                template: ttTemplate,
                position: ttPosition,
                animationIn: animationIn,
                animationOut: animationOut,
                animDuration: 400,
                alwaysOpen: false,
                toggleable: (ttTrigger === 'click') ? true : false
            };

            $(this)._tooltip(tooltipConfig);
        });
    }

    var TamanKitProgressHandler = function ($scope) {
        var $target = $scope.find(".tk-progress-bar"),
            percent = $target.data("percent"),
            type = $target.data("type"),
            deltaPercent = percent * 0.01;

        elementorFrontend.waypoint($target, function (direction) {
            var $this = $(this),
                animeObject = { charged: 0 },
                $statusBar = $(".tk-progress-bar__status-bar", $this),
                $percent = $(".tk-progress-bar__percent-value", $this),
                animeProgress,
                animePercent;

            if ("type-7" == type) {
                $statusBar.css({
                    height: percent + "%",
                });
            } else {
                $statusBar.css({
                    width: percent + "%",
                });
            }

            animePercent = anime({
                targets: animeObject,
                charged: percent,
                round: 1,
                duration: 1000,
                easing: "easeInOutQuad",
                update: function () {
                    $percent.html(animeObject.charged);
                },
            });
        });
    };

    /**=================================================== */
    var TamanKitCounterHandler = function ($scope, $) {
        var $counterElement = $scope.find(".tk-counter");

        elementorFrontend.waypoint($counterElement, function () {
            var counterSettings = $counterElement.data(),
                incrementElement = $counterElement.find(".tk-counter-init"),
                iconElement = $counterElement.find(".icon");

            $(incrementElement).numerator(counterSettings);

            $(iconElement).addClass("animated " + iconElement.data("animation"));
        });
    };
    /**=================================================== */
    var TamanKitCountDownHandler = function ($scope, $) {
        var countDownElement = $scope.find(".tk-countdown").each(function () {
            var countDownSettings = $(this).data("settings");
            var label1 = countDownSettings["label1"],
                label2 = countDownSettings["label2"],
                newLabe1 = label1.split(","),
                newLabe2 = label2.split(",");
            if (countDownSettings["event"] === "onExpiry") {
                $(this)
                    .find(".tk-countdown-init")
                    .pre_countdown({
                        labels: newLabe2,
                        labels1: newLabe1,
                        until: new Date(countDownSettings["until"]),
                        format: countDownSettings["format"],
                        padZeroes: true,
                        timeSeparator: countDownSettings["separator"],
                        onExpiry: function () {
                            $(this).html(countDownSettings["text"]);
                        },
                        serverSync: function () {
                            return new Date(countDownSettings["serverSync"]);
                        },
                    });
            } else if (countDownSettings["event"] === "expiryUrl") {
                $(this)
                    .find(".tk-countdown-init")
                    .pre_countdown({
                        labels: newLabe2,
                        labels1: newLabe1,
                        until: new Date(countDownSettings["until"]),
                        format: countDownSettings["format"],
                        padZeroes: true,
                        timeSeparator: countDownSettings["separator"],
                        expiryUrl: countDownSettings["text"],
                        serverSync: function () {
                            return new Date(countDownSettings["serverSync"]);
                        },
                    });
            }

            times = $(this).find(".tk-countdown-init").pre_countdown("getTimes");

            function runTimer(el) {
                return el == 0;
            }
            if (times.every(runTimer)) {
                if (countDownSettings["event"] === "onExpiry") {
                    $(this).find(".tk-countdown-init").html(countDownSettings["text"]);
                }
                if (countDownSettings["event"] === "expiryUrl") {
                    var editMode = $("body").find("#elementor").length;
                    if (editMode > 0) {
                        $(this)
                            .find(".tk-countdown-init")
                            .html(
                                "<h1>You can not redirect url from elementor Editor!!</h1>"
                            );
                    } else {
                        window.location.href = countDownSettings["text"];
                    }
                }
            }
        });
    };

    /*======================================================== */
    var TamanKitCircleProgressHandler = function ($scope) {
        var $progress = $scope.find(".circle-progress");

        if (!$progress.length) {
            return;
        }

        var $value = $progress.find(".circle-progress__value"),
            $meter = $progress.find(".circle-progress__meter"),
            percent = parseInt($value.data("value")),
            progress = percent / 100,
            duration = $scope.find(".circle-progress-wrap").data("duration"),
            responsiveSizes = $progress.data("responsive-sizes"),
            desktopSizes = responsiveSizes.desktop,
            tabletSizes = responsiveSizes.tablet,
            mobileSizes = responsiveSizes.mobile,
            currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
            prevDeviceMode = currentDeviceMode,
            isAnimatedCircle = false;

        if ("tablet" === currentDeviceMode) {
            updateSvgSizes(
                tabletSizes.size,
                tabletSizes.viewBox,
                tabletSizes.center,
                tabletSizes.radius,
                tabletSizes.valStroke,
                tabletSizes.bgStroke,
                tabletSizes.circumference
            );
        }

        if ("mobile" === currentDeviceMode) {
            updateSvgSizes(
                mobileSizes.size,
                mobileSizes.viewBox,
                mobileSizes.center,
                mobileSizes.radius,
                mobileSizes.valStroke,
                mobileSizes.bgStroke,
                mobileSizes.circumference
            );
        }

        elementorFrontend.waypoint($scope, function () {
            // animate counter
            var $number = $scope.find(".circle-counter__number"),
                data = $number.data();

            var decimalDigits = data.toValue.toString().match(/\.(.*)/);

            if (decimalDigits) {
                data.rounding = decimalDigits[1].length;
            }

            data.duration = duration;

            $number.numerator(data);

            // animate progress
            var circumference = parseInt($progress.data("circumference")),
                dashoffset = circumference * (1 - progress);

            $value.css({
                transitionDuration: duration + "ms",
                strokeDashoffset: dashoffset,
            });

            isAnimatedCircle = true;
        });

        $(window).on(
            "resize.tkCircleProgress orientationchange.tkCircleProgress",
            circleResizeHandler
        );

        function circleResizeHandler(event) {
            currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

            if ("desktop" === currentDeviceMode && "desktop" !== prevDeviceMode) {
                updateSvgSizes(
                    desktopSizes.size,
                    desktopSizes.viewBox,
                    desktopSizes.center,
                    desktopSizes.radius,
                    desktopSizes.valStroke,
                    desktopSizes.bgStroke,
                    desktopSizes.circumference
                );
                prevDeviceMode = "desktop";
            }

            if ("tablet" === currentDeviceMode && "tablet" !== prevDeviceMode) {
                updateSvgSizes(
                    tabletSizes.size,
                    tabletSizes.viewBox,
                    tabletSizes.center,
                    tabletSizes.radius,
                    tabletSizes.valStroke,
                    tabletSizes.bgStroke,
                    tabletSizes.circumference
                );
                prevDeviceMode = "tablet";
            }

            if ("mobile" === currentDeviceMode && "mobile" !== prevDeviceMode) {
                updateSvgSizes(
                    mobileSizes.size,
                    mobileSizes.viewBox,
                    mobileSizes.center,
                    mobileSizes.radius,
                    mobileSizes.valStroke,
                    mobileSizes.bgStroke,
                    mobileSizes.circumference
                );
                prevDeviceMode = "mobile";
            }
        }

        function updateSvgSizes(
            size,
            viewBox,
            center,
            radius,
            valStroke,
            bgStroke,
            circumference
        ) {
            var dashoffset = circumference * (1 - progress);

            $progress.attr({
                width: size,
                height: size,
                "data-radius": radius,
                "data-circumference": circumference,
            });

            $progress[0].setAttribute("viewBox", viewBox);

            $meter.attr({
                cx: center,
                cy: center,
                r: radius,
                "stroke-width": bgStroke,
            });

            if (isAnimatedCircle) {
                $value.css({
                    transitionDuration: "",
                });
            }

            $value.attr({
                cx: center,
                cy: center,
                r: radius,
                "stroke-width": valStroke,
            });

            $value.css({
                strokeDasharray: circumference,
                strokeDashoffset: isAnimatedCircle ? dashoffset : circumference,
            });
        }
    };
    /*========================================================*/
    var PPWidgetUpdate = function (slider, selector, type) {
        if ('undefined' === typeof type) {
            type = 'swiper';
        }

        var $triggers = [
            'ppe-tabs-switched',
            'ppe-toggle-switched',
            'ppe-accordion-switched',
            'ppe-popup-opened',
        ];

        $triggers.forEach(function (trigger) {
            if ('undefined' !== typeof trigger) {
                $(document).on(trigger, function (e, wrap) {
                    if (trigger == 'ppe-popup-opened') {
                        wrap = $('.tk-modal-popup-' + wrap);
                    }
                    if (wrap.find(selector).length > 0) {
                        setTimeout(function () {
                            if ('slick' === type) {
                                slider.slick('setPosition');
                            } else if ('swiper' === type) {
                                slider.update();
                            } else if ('gallery' === type) {
                                var $gallery = wrap.find('.tk-image-gallery').eq(0);
                                $gallery.isotope('layout');
                            }
                        }, 100);
                    }
                });
            }
        });
    };

    var TamanKitTestimonialsHandler = function ($scope, $) {
        var $testimonials = $scope.find('.tk-testimonials').eq(0),
            $testimonials_wrap = $scope.find('.tk-testimonials-wrap'),
            $testimonials_layout = $testimonials.data('layout');

        if ($testimonials_layout === 'carousel' || $testimonials_layout === 'slideshow') {
            var $slider_options = JSON.parse($testimonials.attr('data-slider-settings')),
                $thumbs_nav = $scope.find('.tk-testimonials-thumb-item-wrap'),
                elementSettings = getElementSettings($scope);

            $testimonials.slick($slider_options);

            if ($testimonials_layout === 'slideshow' && elementSettings.thumbnail_nav === 'yes') {
                $thumbs_nav.removeClass('tk-active-slide');
                $thumbs_nav.eq(0).addClass('tk-active-slide');

                $testimonials.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                    currentSlide = nextSlide;
                    $thumbs_nav.removeClass('tk-active-slide');
                    $thumbs_nav.eq(currentSlide).addClass('tk-active-slide');
                });

                $thumbs_nav.each(function (currentSlide) {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        $testimonials.slick('slickGoTo', currentSlide);
                    });
                });
            }

            $testimonials.slick('setPosition');

            PPWidgetUpdate($testimonials, '.tk-testimonials', 'slick');
            var isEditMode = false;

            if (isEditMode) {
                $testimonials_wrap.resize(function () {
                    $testimonials.slick('setPosition');
                });
            }

        }
    };

    var TkVideo = {

        /**
         * Auto Play Video
         */

        _play: function ($selector) {

            var $iframe = $('<iframe/>');
            var $vid_src = $selector.data('src');

            if (0 === $selector.find('iframe').length) {

                $iframe.attr('src', $vid_src);
                $iframe.attr('frameborder', '0');
                $iframe.attr('allowfullscreen', '1');
                $iframe.attr('allow', 'autoplay;encrypted-media;');

                $selector.html($iframe);
            }
        }
    };

    var TamanKitProVideoHandler = function ($scope, $) {
        var videoPlay = $scope.find('.tk-video-play'),
            isLightbox = videoPlay.hasClass('tk-video-play-lightbox');

        videoPlay.off('click').on('click', function (e) {

            e.preventDefault();

            var $selector = $(this).find('.tk-video-player');

            if (!isLightbox) {
                TkVideo._play($selector);
            }

        });

        if (videoPlay.data('autoplay') == '1' && !isLightbox) {

            TkVideo._play($scope.find('.tk-video-player'));

        }
    };

    $(window).on("elementor/frontend/init", function () {
        if (elementorFrontend.isEditMode()) {
            isEditMode = true;
        }

        elementorFrontend.hooks.addAction("frontend/element_ready/tk_counter.default", TamanKitCounterHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/tk-countdown.default", TamanKitCountDownHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/tk-progress.default", TamanKitProgressHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/tk-circleprogress.default", TamanKitCircleProgressHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/tk-imagecomparison.default", TamanKitImageComparisonHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/tk-pricetable.default", TamanKitPricingTableHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/tk-testimonials.default", TamanKitTestimonialsHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/tk-video.default", TamanKitProVideoHandler);


    });
})(jQuery);
