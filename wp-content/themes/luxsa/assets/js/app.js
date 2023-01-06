(function ($) {
    "use strict"; // Initialize global variable

    var LaStudio = {
        global: {},
        utils: {},
        component: {},
        core: {}
    };
    window.LaStudio = LaStudio;

    if (typeof window.LA_No_LoadLazyScripts === "undefined") {
        window.LA_No_LoadLazyScripts = false;
    }

    $.exists = function ($selector) {
        return $selector.length > 0;
    };

    $.getCachedScript = function (url) {
        var options = {
            dataType: "script",
            cache: true,
            url: url
        };
        return $.ajax(options);
    };

    LaStudio.global.log = function () {
        if (la_theme_config.is_dev) {
            console.log(arguments[0]);
        }
    };

    LaStudio.utils.ajax_xhr = null; // helper for ajax

    LaStudio.utils.localCache = {
        /**
         * timeout for cache in seconds, default 5 mins
         * @type {number}
         */
        timeout: typeof la_theme_config.cache_ttl !== "undefined" && parseInt(la_theme_config.cache_ttl) > 0 ? parseInt(la_theme_config.cache_ttl) : (60 * 5),

        timeout2: 10 * 60,
        /**
         * @type {{_: number, data: {}}}
         **/
        data: {},
        remove: function (url) {
            delete LaStudio.utils.localCache.data[url];
        },
        exist: function (url) {
            return !!LaStudio.utils.localCache.data[url] && ((Date.now() - LaStudio.utils.localCache.data[url]._) / 1000 < LaStudio.utils.localCache.timeout2);
        },
        get: function (url) {
            LaStudio.global.log('Get cache for ' + url);
            return LaStudio.utils.localCache.data[url].data;
        },
        set: function (url, cachedData, callback) {
            LaStudio.utils.localCache.remove(url);
            LaStudio.utils.localCache.data[url] = {
                _: Date.now(),
                data: cachedData
            };
            if ("function" == typeof callback && "number" != typeof callback.nodeType) {
                callback(cachedData)
            }
        },
        addedStyles: {},
        addedScripts: {},
        addedAssetsPromises: [],
        ajaxPromises: {},
    };

    LaStudio.utils.hashCode = function (s) {
        // if(la_theme_config.is_dev){
        //     return s;
        // }
        var hash = 0;
        if (s.length == 0) return hash;

        for (var i = 0; i < s.length; i++) {
            var char = s.charCodeAt(i);
            hash = (hash << 5) - hash + char;
            hash = hash & hash; // Convert to 32bit integer
        }

        return Math.abs(hash);
    };

    LaStudio.utils.validCache = function () {
        var expiry = typeof la_theme_config.local_ttl !== "undefined" && parseInt(la_theme_config.local_ttl) > 0 ? parseInt(la_theme_config.local_ttl) : 60 * 30; // 30 mins

        var cacheKey = 'luxsa_cache_timeout' + LaStudio.utils.hashCode(la_theme_config.home_url);
        var whenCached = localStorage.getItem(cacheKey);

        if (whenCached !== null) {
            var age = (Date.now() - whenCached) / 1000;

            if (age > expiry) {
                Object.keys(localStorage).forEach(function (key) {
                    if (key.indexOf('luxsa') === 0) {
                        localStorage.removeItem(key);
                    }
                });
                localStorage.setItem(cacheKey, Date.now());
            }
        } else {
            localStorage.setItem(cacheKey, Date.now());
        }
    };

    LaStudio.utils.AjaxRequest = function (url, options) {
        var expiry = LaStudio.utils.localCache.timeout,
            opt_body = '';

        if (typeof options === 'number') {
            expiry = options;
            options = undefined;
        } else if (typeof options === 'object') {
            expiry = options.seconds || expiry;

            if (typeof options.body !== 'undefined') {
                if (typeof options.body === 'object') {
                    opt_body = JSON.stringify(options.body);
                } else {
                    opt_body = options.body;
                }
            }
        }

        if (expiry <= 0) {
            expiry = 1;
        } // Use the URL as the cache key to localStorage


        var cacheKey = 'luxsa' + LaStudio.utils.hashCode(LaStudio.global.removeURLParameter(url, '_') + opt_body);
        var cached = localStorage.getItem(cacheKey);
        var whenCached = localStorage.getItem(cacheKey + ':ts');

        if (cached !== null && whenCached !== null) {
            var age = (Date.now() - whenCached) / 1000;

            if (age < expiry) {
                var response = new Response(new Blob([cached]));
                return Promise.resolve(response);
            } else {
                // We need to clean up this old key
                localStorage.removeItem(cacheKey);
                localStorage.removeItem(cacheKey + ':ts');
            }
        }

        return fetch(url, options).then(function (response) {
            if (response.status === 200) {
                var ct = response.headers.get('Content-Type');

                if (ct && (ct.match(/application\/json/i) || ct.match(/text\//i))) {
                    response.clone().text().then(function (content) {
                        localStorage.setItem(cacheKey, content);
                        localStorage.setItem(cacheKey + ':ts', Date.now());
                    });
                }
            }

            return response;
        });
    };

    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (options.cache) {
            //Here is our identifier for the cache. Maybe have a better, safer ID (it depends on the object string representation here) ?
            // on $.ajax call we could also set an ID in originalOptions
            var id = LaStudio.global.removeURLParameter(originalOptions.url, '_') + ("undefined" !== typeof originalOptions.ajax_request_id ? JSON.stringify(originalOptions.ajax_request_id) : "undefined" !== typeof originalOptions.data ? JSON.stringify(originalOptions.data) : '');
            id = LaStudio.utils.hashCode(id.replace(/null$/g, ''));
            options.cache = false;

            options.beforeSend = function () {
                if (!LaStudio.utils.localCache.exist(id)) {
                    jqXHR.promise().done(function (data, textStatus) {
                        LaStudio.utils.localCache.set(id, data);
                    });
                }

                return true;
            };
        }
    });
    $.ajaxTransport("+*", function (options, originalOptions, jqXHR) {
        //same here, careful because options.url has already been through jQuery processing
        var id = LaStudio.global.removeURLParameter(originalOptions.url, '_') + ("undefined" !== typeof originalOptions.ajax_request_id ? JSON.stringify(originalOptions.ajax_request_id) : "undefined" !== typeof originalOptions.data ? JSON.stringify(originalOptions.data) : '');
        options.cache = false;
        id = LaStudio.utils.hashCode(id.replace(/null$/g, ''));

        if (LaStudio.utils.localCache.exist(id)) {
            return {
                send: function (headers, completeCallback) {
                    setTimeout(function () {
                        completeCallback(200, "OK", [LaStudio.utils.localCache.get(id)]);
                    }, 50);
                },
                abort: function () {
                    /* abort code, nothing needed here I guess... */
                }
            };
        }
    });

    LaStudio.global.isPageSpeed = function () {
        return typeof navigator !== "undefined" && (/(lighthouse|gtmetrix)/i.test(navigator.userAgent.toLocaleLowerCase()) || /mozilla\/5\.0 \(x11; linux x86_64\)/i.test(navigator.userAgent.toLocaleLowerCase()));
    };

    LaStudio.global.hasClass = function (elm, cls) {
        return (' ' + elm.className + ' ').indexOf(' ' + cls + ' ') > -1;
    };

    LaStudio.global.isRTL = function () {
        return document.body.classList ? document.body.classList.contains('rtl') : /\brtl\b/g.test(document.body.className);
    };

    LaStudio.global.sanitizeSlug = function (text) {
        return text.toString().toLowerCase().replace(/\s+/g, '-') // Replace spaces with -
            .replace(/[^\w\-]+/g, '') // Remove all non-word chars
            .replace(/\-\-+/g, '-') // Replace multiple - with single -
            .replace(/^-+/, '') // Trim - from start of text
            .replace(/-+$/, '');
    };

    LaStudio.global.isCookieEnable = function () {
        if (navigator.cookieEnabled) return true;
        document.cookie = "cookietest=1";
        var ret = document.cookie.indexOf("cookietest=") != -1;
        document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";
        return ret;
    };

    LaStudio.global.parseVideo = function (url) {
        // - Supported YouTube URL formats:
        //   - http://www.youtube.com/watch?v=My2FRPA3Gf8
        //   - http://youtu.be/My2FRPA3Gf8
        //   - https://youtube.googleapis.com/v/My2FRPA3Gf8
        // - Supported Vimeo URL formats:
        //   - http://vimeo.com/25451551
        //   - http://player.vimeo.com/video/25451551
        // - Also supports relative URLs:
        //   - //player.vimeo.com/video/25451551
        var _playlist = LaStudio.global.getUrlParameter('playlist', url);

        url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

        if (RegExp.$3.indexOf('youtu') > -1) {
            if (_playlist) {
                return 'https://www.youtube.com/embed/' + RegExp.$6 + '?autoplay=1&playlist=' + _playlist + '&loop=1&rel=0&iv_load_policy3';
            }

            return 'https://www.youtube.com/embed/' + RegExp.$6 + '?autoplay=1&loop=1&rel=0&iv_load_policy3';
        } else if (RegExp.$3.indexOf('vimeo') > -1) {
            url.match(/^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/)|(showcase\/[0-9]+\/video\/))?([0-9]+)/);
            return 'https://player.vimeo.com/video/' + RegExp.$6 + '?autoplay=1&loop=1&title=0&byline=0&portrait=0';
        }

        return url;
    };

    LaStudio.global.getBrowseInformation = function () {
        var name, version, platform_name, _tmp;

        var ua = navigator.userAgent.toLowerCase(),
            platform = navigator.platform.toLowerCase(),
            UA = ua.match(/(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/) || [null, 'unknown', '0'];

        function getInternetExplorerVersion() {
            var rv = -1,
                ua2,
                re2;

            if (navigator.appName == 'Microsoft Internet Explorer') {
                ua2 = navigator.userAgent;
                re2 = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
                if (re2.exec(ua2) != null) rv = parseFloat(RegExp.$1);
            } else if (navigator.appName == 'Netscape') {
                ua2 = navigator.userAgent;
                re2 = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
                if (re2.exec(ua2) != null) rv = parseFloat(RegExp.$1);
            }

            return rv;
        }

        _tmp = getInternetExplorerVersion();

        if (_tmp != -1) {
            name = 'ie';
            version = _tmp;
        } else {
            name = UA[1] == 'version' ? UA[3] : UA[1];
            version = UA[2].substring(0, 2);
        }

        platform_name = ua.match(/ip(?:ad|od|hone)/) ? 'ios' : (ua.match(/(?:webos|android)/) || platform.match(/mac|win|linux/) || ['other'])[0];
        return {
            name: name,
            version: version,
            platform: platform_name
        };
    };

    LaStudio.global.setBrowserInformation = function () {
        var information = LaStudio.global.getBrowseInformation();

        if ('ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0) {
            document.documentElement.classList.add('touchevents');
        } else {
            document.documentElement.classList.add('no-touchevents');
        }

        document.documentElement.classList.add(information.name);
        document.documentElement.classList.add(information.name + information.version);
        document.documentElement.classList.add('platform-' + information.platform);
        document.documentElement.style.setProperty('--scrollbar-width', window.innerWidth - document.documentElement.clientWidth + 'px');
        $(window).on('resize', function () {
            document.documentElement.style.setProperty('--scrollbar-width', window.innerWidth - document.documentElement.clientWidth + 'px');
        });
    };

    LaStudio.global.isIELower16 = function () {
        var information = LaStudio.global.getBrowseInformation();
        return information.name == 'ie' && parseInt(information.version) < 16;
    };

    LaStudio.global.getRandomID = function () {
        var text = "",
            char = "abcdefghijklmnopqrstuvwxyz",
            num = "0123456789",
            i;

        for (i = 0; i < 5; i++) {
            text += char.charAt(Math.floor(Math.random() * char.length));
        }

        for (i = 0; i < 5; i++) {
            text += num.charAt(Math.floor(Math.random() * num.length));
        }

        return text;
    };

    LaStudio.global.getAdminBarHeight = function () {
        return document.getElementById('wpadminbar') && window.innerWidth > 600 ? 32 : 0;
    };

    LaStudio.global.addQueryArg = function (url, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = url.indexOf('?') !== -1 ? "&" : "?";

        if (url.match(re)) {
            return url.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return url + separator + key + "=" + value;
        }
    };

    LaStudio.global.getUrlParameter = function (name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    };

    LaStudio.global.removeURLParameter = function (url, parameter) {
        var urlparts = url.split('?');

        if (urlparts.length >= 2) {
            var prefix = encodeURIComponent(parameter) + '=';
            var pars = urlparts[1].split(/[&;]/g); //reverse iteration as may be destructive

            for (var i = pars.length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url = urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
            return url;
        } else {
            return url;
        }
    };

    LaStudio.global.parseQueryString = function (query) {
        var urlparts = query.split("?");
        var query_string = {};

        if (urlparts.length >= 2) {
            var vars = urlparts[1].split("&");

            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split("=");
                var key = decodeURIComponent(pair[0]);
                var value = decodeURIComponent(pair[1]); // If first entry with this name

                if (typeof query_string[key] === "undefined") {
                    query_string[key] = decodeURIComponent(value); // If second entry with this name
                } else if (typeof query_string[key] === "string") {
                    var arr = [query_string[key], decodeURIComponent(value)];
                    query_string[key] = arr; // If third or later entry with this name
                } else {
                    query_string[key].push(decodeURIComponent(value));
                }
            }
        }

        return query_string;
    };
})(jQuery);

(function ($) {
    'use strict';

    function getHtmlScroll() {
        return {
            x: window.pageXOffset || document.documentElement.scrollLeft,
            y: window.pageYOffset || document.documentElement.scrollTop
        };
    }

    function isHtmlBodyTag(element) {
        return /^(?:body|html)$/i.test(element.tagName);
    }

    function getElementScroll(elem) {
        var element = elem.parentNode,
            position = {
                x: 0,
                y: 0
            };

        while (element && !isHtmlBodyTag(element)) {
            position.x += element.scrollLeft;
            position.y += element.scrollTop;
            element = element.parentNode;
        }

        return position;
    }

    function getStyleToString(element, style) {
        return $(element).css(style);
    }

    function getStyleToNumber(element, style) {
        return parseInt(getStyleToString(element, style)) || 0;
    }

    function getTopBorderOfElement(element) {
        return getStyleToNumber(element, 'border-top-width');
    }

    function getTopLeftOfElement(element) {
        return getStyleToNumber(element, 'border-left-width');
    }

    function elementHasBorderBox(element) {
        return getStyleToString(element, '-moz-box-sizing') == 'border-box';
    }

    function getOffset(elem) {
        var browser_information = LaStudio.global.getBrowseInformation();

        if (elem.getBoundingClientRect && browser_information.platform != 'ios') {
            var bound = elem.getBoundingClientRect(),
                html = elem.ownerDocument.documentElement,
                htmlScroll = getHtmlScroll(),
                elemScrolls = getElementScroll(elem),
                isFixed = getStyleToString(elem, 'position') == 'fixed';
            return {
                x: parseInt(bound.left) + elemScrolls.x + (isFixed ? 0 : htmlScroll.x) - html.clientLeft,
                y: parseInt(bound.top) + elemScrolls.y + (isFixed ? 0 : htmlScroll.y) - html.clientTop
            };
        }

        var element = elem,
            position = {
                x: 0,
                y: 0
            };
        if (isHtmlBodyTag(elem)) return position;

        while (element && !isHtmlBodyTag(element)) {
            position.x += element.offsetLeft;
            position.y += element.offsetTop;

            if (browser_information.name == 'firefox') {
                if (!elementHasBorderBox(element)) {
                    position.x += getTopLeftOfElement(element);
                    position.y += getTopBorderOfElement(element);
                }

                var parent = element.parentNode;

                if (parent && getStyleToString(parent, 'overflow') != 'visible') {
                    position.x += getTopLeftOfElement(parent);
                    position.y += getTopBorderOfElement(parent);
                }
            } else if (element != elem && browser_information.name == 'safari') {
                position.x += getTopLeftOfElement(element);
                position.y += getTopBorderOfElement(element);
            }

            element = element.offsetParent;
        }

        if (browser_information.name == 'firefox' && !elementHasBorderBox(elem)) {
            position.x -= getTopLeftOfElement(elem);
            position.y -= getTopBorderOfElement(elem);
        }

        return position;
    }

    LaStudio.global.getOffset = function ($element) {
        return $.exists($element) ? getOffset($element.get(0)) : {
            x: 0,
            y: 0
        };
    };
})(jQuery); // Initialize loadDependencies


(function ($) {
    var _loadedDependencies = [],
        _inQueue = {};
    $('body').on('lastudio-prepare-object-fit', function (e, $elm) {
        LaStudio.global.log('run fix object-fit');
        var objectFits = $('.figure__object_fit:not(.custom-object-fit) img', $elm);
        objectFits.each(function () {
            var $container = $(this).closest('.figure__object_fit'),
                imgUrl = $(this).prop('src');

            if (imgUrl) {
                $container.css('backgroundImage', 'url(' + imgUrl + ')').addClass('custom-object-fit');
            }
        });
    });

    if (LaStudio.global.isIELower16()) {
        $('<div class="unsupported-browser"><div><h3>'+la_theme_config.i18n.unsupported_browser.title+'</h3><p>'+la_theme_config.i18n.unsupported_browser.desc+'</p></div></div>').prependTo($('body'));
        $('body').on('lastudio-object-fit', function (e) {
            LaStudio.global.log('run fix object-fit');
            var objectFits = $('.figure__object_fit:not(.custom-object-fit) img');
            objectFits.each(function () {
                var $container = $(this).closest('.figure__object_fit'),
                    imgUrl = $(this).prop('src');

                if (imgUrl) {
                    $container.css('backgroundImage', 'url(' + imgUrl + ')').addClass('custom-object-fit');
                }
            });
        });
    }

    LaStudio.core.initAll = function ($scope) {
        var $el = $scope.find('.js-el'),
            $components = $el.filter('[data-la_component]'),
            component = null;

        if ($scope.find('.variations_form').length || $scope.find('.product_item .la-swatch-control').length) {
            $(document).trigger('reinit_la_swatches');
        }

        if ($components.length <= 0) {
            return;
        } // initialize  component


        var init_component = function (name, el) {
            var $el = $(el);
            if ($el.data('init-' + name)) return;

            if (typeof LaStudio.component[name] !== 'function') {
                LaStudio.global.log('[LaStudio Component ' + name + '] ---- init error');
            } else {
                component = new LaStudio.component[name](el);
                component.init();
                $el.data('init-' + name, true);
                LaStudio.global.log('[LaStudio Component ' + name + '] ---- init success');
                LaStudio.global.eventManager.publish('LaStudio:component_inited', [name, el]);
            }
        };

        if (!LaStudio.global.isPageSpeed()) {
            $components.each(function () {
                var self = this,
                    names = $(this).data('la_component');

                if (typeof names === 'string') {
                    var _name = names;
                    init_component(_name, self);
                } else {
                    names.forEach(function (name) {
                        init_component(name, self);
                    });
                }
            });
        } else {
            LaStudio.global.LazyLoad($components, {
                rootMargin: '200px',
                load: function (comp) {
                    var comp_name = $(comp).data('la_component');

                    if ('string' === typeof comp_name) {
                        init_component(comp_name, comp);
                    } else {
                        comp_name.forEach(function (name) {
                            init_component(name, comp);
                        });
                    }
                }
            }).observe();
        }

        $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger('lastudio-lazy-images-load').trigger('jetpack-lazy-images-load').trigger('lastudio-object-fit');
    };

    LaStudio.global.loadStyle = function (style, uri) {
        if (LaStudio.utils.localCache.addedStyles.hasOwnProperty(style) && LaStudio.utils.localCache.addedStyles[style] === uri) {
            return style;
        }

        LaStudio.utils.localCache.addedStyles[style] = uri;
        return new Promise(function (resolve, reject) {
            var tag = document.createElement('link');
            tag.id = style;
            tag.rel = 'stylesheet';
            tag.href = uri;
            tag.type = 'text/css';
            tag.media = 'all';

            tag.onload = function () {
                resolve(style);
            };
            tag.onerror = function (){
                reject(`Can not load css file "${uri}"`);
            }
            document.head.appendChild(tag);
        });
    };

    LaStudio.global.loadScriptAsync = function (script, uri, callback, async) {
        if (LaStudio.utils.localCache.addedScripts.hasOwnProperty(script)) {
            return script;
        }

        LaStudio.utils.localCache.addedScripts[script] = uri;
        return new Promise(function (resolve, reject) {
            var tag = document.createElement('script');
            tag.src = uri;
            tag.async = async;

            tag.onload = function () {
                resolve(script);

                if ("function" == typeof callback && "number" != typeof callback.nodeType) {
                    callback();
                }
            };

            tag.onerror = function (){
                reject(`Can not load javascript file "${uri}"`);
                if ("function" == typeof callback && "number" != typeof callback.nodeType) {
                    callback();
                }
            }

            LaStudio.global.log(tag);
            document.head.appendChild(tag);
        });
    };

    LaStudio.global.loadScriptAsyncSequence = function (scripts, callback) {
        scripts.forEach(function (item, idx) {
            LaStudio.global.loadScriptAsync(item[0], item[1], idx + 1 == scripts.length ? callback : '', item[2]);
        });
    };

    LaStudio.global.loadDependencies = function (dependencies, callback) {
        var _callback = callback || function () {};

        if (!dependencies) {
            _callback();

            return;
        }

        var newDeps = dependencies.map(function (dep) {
            if (_loadedDependencies.indexOf(dep) === -1) {
                if (typeof _inQueue[dep] === 'undefined') {
                    return dep;
                } else {
                    _inQueue[dep].push(_callback);

                    return true;
                }
            } else {
                return false;
            }
        });

        if (newDeps[0] === true) {
            return;
        }

        if (newDeps[0] === false) {
            _callback();

            return;
        }

        var queue = newDeps.map(function (script) {
            _inQueue[script] = [_callback];
            return $.getCachedScript(script);
        }); // Callbacks invoking

        var onLoad = function onLoad() {
            var index = 0;
            newDeps.map(function (loaded) {
                index++;

                _inQueue[loaded].forEach(function (callback) {
                    if (index == newDeps.length) {
                        LaStudio.global.log('loaded js: ' + loaded);
                        callback();
                    }
                });

                delete _inQueue[loaded];

                _loadedDependencies.push(loaded);
            });
        }; // Run callbacks when promise is resolved


        $.when.apply(null, queue).done(onLoad);
    };

    LaStudio.global.loadJsFile = function (name) {
        return la_theme_config.js_path + name + (la_theme_config.js_min ? '.min.js' : '.js');
    };

    LaStudio.global.AnimateLoadElement = function (effect_name, $elements, callback) {
        var _callback = callback || function () {};

        var animation_timeout = 0; // hide all element that not yet loaded

        $elements.css({
            'opacity': 0
        });

        if (effect_name == 'fade') {
            $elements.each(function () {
                $(this).stop().animate({
                    'opacity': 1
                }, 1000);
            });
            animation_timeout = 1000;
        } else if (effect_name == 'sequencefade') {
            $elements.each(function (i) {
                var $elm = $(this);
                setTimeout(function () {
                    $elm.stop().animate({
                        'opacity': 1
                    }, 1000);
                }, 100 + i * 50);
            });
            animation_timeout = 500 + $elements.length * 50;
        } else if (effect_name == 'upfade') {
            $elements.each(function () {
                var $elm = $(this),
                    t = parseInt($elm.css('top'), 10) + $elm.height() / 2;
                $elm.css({
                    top: t + 'px',
                    opacity: 0
                });
            });
            $elements.each(function () {
                var $el = $(this);
                $el.stop().animate({
                    top: parseInt($el.css('top'), 10) - $el.height() / 2,
                    opacity: 1
                }, 1500);
            });
            animation_timeout = 2000;
        } else if (effect_name == 'sequenceupfade') {
            $elements.each(function () {
                var $elm = $(this),
                    t = parseInt($elm.css('top'), 10) + $elm.height() / 2;
                $elm.css({
                    top: t + 'px',
                    opacity: 0
                });
            });
            $elements.each(function (i) {
                var $elm = $(this);
                setTimeout(function () {
                    $elm.stop().animate({
                        top: parseInt($elm.css('top'), 10) - $elm.height() / 2,
                        opacity: 1
                    }, 1000);
                }, 100 + i * 50);
            });
            animation_timeout = 1100 + $elements.length * 50;
        } else {
            $elements.css({
                'opacity': 1
            });
            animation_timeout = 1000;
        }
        /* run callback */


        setTimeout(function () {
            _callback.call();
        }, animation_timeout);
    };

    LaStudio.global.InsightInitLazyEffects = function (selector, $container, load_immediately) {
        function _init_effect() {
            var _effect_name = false === !!$container.attr('data-la-effect') ? 'sequenceupfade' : $container.attr('data-la-effect');

            $container.addClass('InsightInitLazyEffects-inited');
            LaStudio.global.AnimateLoadElement(_effect_name, $(selector, $container), function () {
                $(selector, $container).addClass('showmenow');

                if ($container.data('isotope')) {
                    $container.isotope('layout');
                }
            });
        }

        if ($container.hasClass('InsightInitLazyEffects-inited')) {
            return;
        }

        if (load_immediately) {
            _init_effect();
        } else {
            LaStudio.global.LazyLoad($container, {
                load: function () {
                    _init_effect();
                }
            }).observe();
        }
    };

    LaStudio.global.ShowMessageBox = function (html, ex_class) {
        if (typeof LaStudio.utils.timeOutMessageBox === "undefined") {
            LaStudio.utils.timeOutMessageBox = null;
        }

        var $content = $('<div class="la-global-message"></div>').html(html);

        var show_popup = function () {
            if ($.featherlight.close() !== undefined) {
                $.featherlight.close();
            }

            $.featherlight($content, {
                persist: 'shared',
                type: 'jquery',
                background: '<div class="featherlight featherlight-loading"><div class="featherlight-outer"><button class="featherlight-close-icon featherlight-close" aria-label="Close"><i class="lastudioicon-e-remove"></i></button><div class="featherlight-content"><div class="featherlight-inner"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div></div><div class="custom-featherlight-overlay"></div></div>',
                beforeOpen: function (evt) {
                    $('body').addClass(ex_class);
                    clearTimeout(LaStudio.utils.timeOutMessageBox);
                },
                afterOpen: function (evt) {
                    LaStudio.utils.timeOutMessageBox = setTimeout(function () {
                        $.featherlight.close();
                    }, 20 * 1000);
                },
                afterClose: function (evt) {
                    $('body').removeClass(ex_class);
                    clearTimeout(LaStudio.utils.timeOutMessageBox);
                }
            });
        };

        if ($.isFunction($.fn.featherlight)) {
            show_popup();
        } else {
            LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('featherlight')], show_popup);
        }
    };
})(jQuery); // Initialize Event Manager


(function ($) {
    'use strict';

    LaStudio.global.eventManager = {};

    LaStudio.global.eventManager.subscribe = function (evt, func) {
        $(this).on(evt, func);
    };

    LaStudio.global.eventManager.unsubscribe = function (evt, func) {
        $(this).off(evt, func);
    };

    LaStudio.global.eventManager.publish = function (evt, params) {
        $(this).trigger(evt, params);
    };
})(jQuery); // Initialize Lazyload


(function ($) {
    "use strict";

    var defaultConfig = {
        rootMargin: '50px',
        threshold: 0,
        load: function load(element) {
            var base_src = element.getAttribute('data-src') || element.getAttribute('data-lazy') || element.getAttribute('data-lazy-src') || element.getAttribute('data-lazy-original'),
                base_srcset = element.getAttribute('data-src') || element.getAttribute('data-lazy-srcset'),
                base_sizes = element.getAttribute('data-sizes') || element.getAttribute('data-lazy-sizes');

            if (base_src) {
                element.src = base_src;
            }

            if (base_srcset) {
                element.srcset = base_srcset;
            }

            if (base_sizes) {
                element.sizes = base_sizes;
            }

            if (element.getAttribute('data-background-image')) {
                element.style.backgroundImage = 'url("' + element.getAttribute('data-background-image') + '")';
            }

            element.setAttribute('data-element-loaded', true);

            if ($(element).hasClass('jetpack-lazy-image')) {
                $(element).addClass('jetpack-lazy-image--handled');
            }
        },
        complete: function ($elm) {// this function will be activated when element has been loaded
        }
    };

    function markAsLoaded(element) {
        element.setAttribute('data-element-loaded', true);
    }

    var isLoaded = function isLoaded(element) {
        return element.getAttribute('data-element-loaded') === 'true';
    };

    var onIntersection = function onIntersection(load) {
        return function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.intersectionRatio > 0) {
                    observer.unobserve(entry.target);

                    if (!isLoaded(entry.target)) {
                        load(entry.target);
                        markAsLoaded(entry.target);
                    }
                }
            });
        };
    };

    LaStudio.global.LazyLoad = function () {
        var selector = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
        var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

        var _defaultConfig$option = $.extend({}, defaultConfig, options),
            rootMargin = _defaultConfig$option.rootMargin,
            threshold = _defaultConfig$option.threshold,
            load = _defaultConfig$option.load,
            complete = _defaultConfig$option.complete; // // If initialized, then disconnect the observer


        var observer = void 0;

        if ("IntersectionObserver" in window) {
            observer = new IntersectionObserver(onIntersection(load), {
                rootMargin: rootMargin,
                threshold: threshold
            });
        }

        return {
            observe: function observe() {
                if (!$.exists(selector)) {
                    return;
                }

                for (var i = 0; i < selector.length; i++) {
                    if (isLoaded(selector[i])) {
                        continue;
                    }

                    if (observer) {
                        observer.observe(selector[i]);
                        continue;
                    }

                    load(selector[i]);
                    markAsLoaded(selector[i]);
                }

                complete(selector);
            }
        };
    };

    LaStudio.global.makeImageAsLoaded = function (elm) {
        if (!isLoaded(elm)) {
            defaultConfig.load(elm);
            markAsLoaded(elm);
            $(elm).removeClass('lazyload');
        }
    };

    $('body').on('lastudio-lazy-images-load', function () {
        var $elm = $('.la-lazyload-image:not([data-element-loaded="true"])');
        LaStudio.global.LazyLoad($elm, {
            rootMargin: '50px'
        }).observe();
        var jetpackLazyImagesLoadEvent;

        try {
            jetpackLazyImagesLoadEvent = new Event('jetpack-lazy-images-load', {
                bubbles: true,
                cancelable: true
            });
        } catch (e) {
            jetpackLazyImagesLoadEvent = document.createEvent('Event');
            jetpackLazyImagesLoadEvent.initEvent('jetpack-lazy-images-load', true, true);
        }

        $('body').get(0).dispatchEvent(jetpackLazyImagesLoadEvent);
    });
})(jQuery); // Initialize Component


(function ($) {
    'use strict';

    var $window = $(window),
        $document = $(document),
        $htmlbody = $('html,body'),
        $body = $('body'),
        $masthead = $('#lastudio-header-builder');

    LaStudio.component.SVGAnimation = function (el) {
        var $this = $(el),
            _settings = $this.data(),
            _type = _settings.type ? _settings.type : 'delayed',
            _duration = _settings.duration ? _settings.duration : 100,
            _options = {
                type: _type,
                duration: _duration
            },
            $svg = $this.find('svg');

        var setup_vivus = function () {
            var _vivus = new Vivus($svg[0], _options);

            if (_settings.hover) {
                if (_settings.hoveron) {
                    $(_settings.hoveron).on('mouseenter', function () {
                        _vivus.stop().reset().play(2);
                    }).on('mouseleave', function () {
                        _vivus.finish();
                    });
                } else {
                    $this.on('mouseenter', function () {
                        _vivus.stop().reset().play(2);
                    }).on('mouseleave', function () {
                        _vivus.finish();
                    });
                }
            }
        };

        return {
            init: function () {
                if (typeof Vivus === 'undefined') {
                    LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('vivus')], setup_vivus);
                } else {
                    setup_vivus();
                }
            }
        };
    };

    LaStudio.component.MasonryFilter = function (el) {
        var $this = $(el),
            options = $this.data('isotope_option') || {},
            $isotope = $($this.data('isotope_container'));

        var setup_filter = function () {
            $('.isotope__filter-item', $this).on('click', function (e) {
                e.preventDefault();
                var selector = $(this).attr('data-filter');
                $this.find('.active').removeClass('active');
                if (selector != '*') selector = '.' + selector;

                if ($isotope) {
                    $isotope.attr('lafilter', selector);
                    $isotope.isotope($.extend(options, {
                        filter: selector
                    }));
                }

                $(this).addClass('active');
            });
        };

        return {
            init: function () {
                if ($.isFunction($.fn.isotope)) {
                    setup_filter();
                } else {
                    LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('isotope.pkgd')], setup_filter);
                }
            }
        };
    };

    LaStudio.component.DefaultMasonry = function (el) {
        var $isotope_container = $(el),
            item_selector = $isotope_container.data('item_selector'),
            configs = $isotope_container.data('config_isotope') || {};
        configs = $.extend({
            percentPosition: true,
            itemSelector: item_selector
        }, configs);

        var setup_masonry = function () {
            $isotope_container.isotope(configs);
            LaStudio.global.LazyLoad($isotope_container.parent(), {
                rootMargin: '50px',
                load: function () {
                    LaStudio.global.eventManager.publish('LaStudio:Component:LazyLoadImage', [$isotope_container]);
                    $('.la-isotope-loading', $isotope_container).hide();
                    $isotope_container.addClass('loaded');
                    LaStudio.global.InsightInitLazyEffects(item_selector, $isotope_container, false);
                }
            }).observe();

            try {
                Waypoint.refreshAll();
            } catch (e) {}
        };

        return {
            init: function () {
                $('.la-isotope-loading', $isotope_container).show();

                if ($.isFunction($.fn.isotope)) {
                    setup_masonry();
                } else {
                    LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('isotope.pkgd')], setup_masonry);
                }
            }
        };
    };

    LaStudio.component.AdvancedMasonry = function (el) {
        var $isotope_container = $(el),
            item_selector = $isotope_container.data('item_selector'),
            configs = $isotope_container.data('config_isotope') || {};
        configs = $.extend({
            percentPosition: true,
            itemSelector: item_selector,
            masonry: {
                gutter: 0
            }
        }, configs);

        var get_isotope_column_number = function (w_w, item_w) {
            return Math.round(w_w / item_w);
        };

        LaStudio.global.eventManager.subscribe('LaStudio:AdvancedMasonry:calculatorItemWidth', function (e, $isotope_container, need_relayout) {
            if ($isotope_container.hasClass('grid-items')) {
                return;
            }

            var ww = $window.width(),
                _base_w = $isotope_container.data('item-width'),
                _base_h = $isotope_container.data('item-height'),
                _container_width_base = false !== !!$isotope_container.data('container-width') ? $isotope_container.data('container-width') : $isotope_container.width(),
                _container_width = $isotope_container.width();

            var item_per_page = get_isotope_column_number(_container_width_base, _base_w);

            if (ww > 1300) {
                var __maxItem = $isotope_container.parent().attr('class').match(/masonry-max-item-per-row-(\d+)/);

                var __minItem = $isotope_container.parent().attr('class').match(/masonry-min-item-per-row-(\d+)/);

                if (__maxItem && __maxItem[1] && item_per_page > parseInt(__maxItem[1])) {
                    item_per_page = parseInt(__maxItem[1]);
                }

                if (__minItem && __minItem[1] && item_per_page < parseInt(__minItem[1])) {
                    item_per_page = parseInt(__minItem[1]);
                }
            }

            if (ww < 1024) {
                item_per_page = $isotope_container.data('md-col');
                $isotope_container.removeClass('cover-img-bg');
            } else {
                $isotope_container.addClass('cover-img-bg');
            }

            if (ww < 800) {
                item_per_page = $isotope_container.data('sm-col');
            }

            if (ww < 576) {
                item_per_page = $isotope_container.data('xs-col');
            }

            if (ww < 480) {
                item_per_page = $isotope_container.data('mb-col');
            }

            var itemwidth = Math.floor(_container_width / item_per_page),
                selector = $isotope_container.data('item_selector'),
                margin = parseInt($isotope_container.data('item_margin') || 0),
                dimension = parseFloat(_base_w / _base_h);
            $(selector, $isotope_container).each(function (idx) {
                var thiswidth = parseFloat($(this).data('width') || 1),
                    thisheight = parseFloat($(this).data('height') || 1),
                    _css = {};
                if (isNaN(thiswidth)) thiswidth = 1;
                if (isNaN(thisheight)) thisheight = 1;

                if (ww < 1024) {
                    thiswidth = thisheight = 1;
                }

                _css.width = Math.floor(itemwidth * thiswidth - margin / 2);
                _css.height = Math.floor(itemwidth / dimension * thisheight);

                if (ww < 1024) {
                    _css.height = 'auto';
                }

                $(this).css(_css);
            });

            if (need_relayout) {
                if ($isotope_container.data('isotope')) {
                    $isotope_container.isotope('layout');
                }
            }
        });

        var setup_masonry = function () {
            LaStudio.global.eventManager.publish('LaStudio:AdvancedMasonry:calculatorItemWidth', [$isotope_container, false]);
            $window.on('resize', function (e) {
                LaStudio.global.eventManager.publish('LaStudio:AdvancedMasonry:calculatorItemWidth', [$isotope_container, true]);
            });

            if (!$isotope_container.hasClass('masonry__column-type-default')) {
                configs.masonry.columnWidth = 1;
            }

            $isotope_container.isotope(configs);

            if (!$isotope_container.hasClass('showposts-loop') && !$isotope_container.hasClass('loaded')) {
                $isotope_container.on('layoutComplete', function (e) {
                    LaStudio.global.InsightInitLazyEffects(item_selector, $isotope_container, true);
                });
            }

            LaStudio.global.LazyLoad($isotope_container.parent(), {
                rootMargin: '50px',
                load: function () {
                    LaStudio.global.eventManager.publish('LaStudio:Component:LazyLoadImage', [$isotope_container]);
                    $('.la-isotope-loading', $isotope_container).hide();
                    $isotope_container.addClass('loaded');
                    LaStudio.global.InsightInitLazyEffects(item_selector, $isotope_container, false);
                }
            }).observe();

            try {
                Waypoint.refreshAll();
            } catch (e) {}
        };

        return {
            init: function () {
                $('.la-isotope-loading', $isotope_container).show();

                if ($.isFunction($.fn.isotope)) {
                    setup_masonry();
                } else {
                    LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('isotope.pkgd')], setup_masonry);
                }
            }
        };
    };

    LaStudio.component.AutoCarousel = function (el) {
        var $slider = $(el),
            options = $slider.data('slider_config') || {};

        var setup_slick = function () {
            var laptopSlides, tabletPortraitSlides, tabletSlides, mobileSlides, mobilePortraitSlides, defaultOptions, slickOptions, slidesToShow;
            slidesToShow = parseInt(options.slidesToShow.desktop) || 1;
            laptopSlides = parseInt(options.slidesToShow.laptop) || slidesToShow;
            tabletSlides = parseInt(options.slidesToShow.tablet) || laptopSlides;
            tabletPortraitSlides = parseInt(options.slidesToShow.tabletportrait) || tabletSlides;
            mobileSlides = parseInt(options.slidesToShow.mobile) || tabletPortraitSlides;
            mobilePortraitSlides = parseInt(options.slidesToShow.mobileportrait) || mobileSlides;
            options.slidesToShow = slidesToShow;
            var rows = 1;

            if (typeof options.extras !== "undefined" && typeof options.extras.rows !== "undefined") {
                rows = parseInt(options.extras.rows);
            }

            if (rows < 1 || isNaN(rows)) {
                rows = 1;
            }

            var res_s1 = {
                    slidesToShow: laptopSlides,
                    slidesToScroll: laptopSlides
                },
                res_s2 = {
                    slidesToShow: tabletSlides,
                    slidesToScroll: tabletSlides
                },
                res_s3 = {
                    slidesToShow: tabletPortraitSlides,
                    slidesToScroll: tabletPortraitSlides
                },
                res_s4 = {
                    slidesToShow: mobileSlides,
                    slidesToScroll: mobileSlides
                },
                res_s5 = {
                    slidesToShow: mobilePortraitSlides,
                    slidesToScroll: mobilePortraitSlides
                };

            if (rows > 1) {
                res_s1.rows = rows;
                res_s2.rows = rows;
                res_s3.rows = 1;
                res_s4.rows = 1;
                res_s5.rows = 1;
            }

            defaultOptions = {
                customPaging: function (slider, i) {
                    return $('<span />').text(i + 1);
                },
                dotsClass: 'lastudio-slick-dots',
                responsive: [{
                    breakpoint: 1600,
                    settings: res_s1
                }, {
                    breakpoint: 1300,
                    settings: res_s2
                }, {
                    breakpoint: 992,
                    settings: res_s3
                }, {
                    breakpoint: 768,
                    settings: res_s4
                }, {
                    breakpoint: 576,
                    settings: res_s5
                }]
            };

            if (rows > 1) {
                defaultOptions.rows = rows;
            }

            var svg_arrow = {
                left: '<svg viewBox="0 0 33 85" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="33" height="85"><path fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="square" stroke-width="2" d="M31 2L2 42.5 31 83"/></svg>',
                right: '<svg viewBox="0 0 33 85" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="33" height="85"><path fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="square" stroke-width="2" d="M2 2l29 40.5L2 83"/></svg>'
            };
            slickOptions = $.extend({}, defaultOptions, options);

            if (typeof slickOptions.prevArrow !== "undefined") {
                slickOptions.prevArrow = slickOptions.prevArrow.replace('<button class="lastudio-arrow prev-arrow slick-prev"><i class="lastudioicon-svgleft"></i></button>', '<button class="lastudio-arrow prev-arrow slick-prev">' + svg_arrow.left + '</button>');
            }

            if (typeof slickOptions.nextArrow !== "undefined") {
                slickOptions.nextArrow = slickOptions.nextArrow.replace('<button class="lastudio-arrow next-arrow slick-next"><i class="lastudioicon-svgright"></i></button>', '<button class="lastudio-arrow next-arrow slick-next">' + svg_arrow.right + '</button>');
            }

            $slider.on('init', function (e, slick) {
                if (slick.slideCount <= slick.options.slidesToShow) {
                    slick.$slider.addClass('hidden-dots');
                } else {
                    slick.$slider.removeClass('hidden-dots');
                }

                if (slick.options.centerMode) {
                    slick.$slider.addClass('la-slick-centerMode');
                }
            });
            $slider.on('afterChange', function (e) {
                $slider.addClass('laslickinit');
            });
            $slider.slick(slickOptions);
            $('<div class="slick-controls-auto"><a class="slick-control-start" href="#"><i class="fa fa-play" aria-hidden="true"></i></a><a class="slick-control-stop active" href="#"><i class="fa fa-pause" aria-hidden="true"></i></a></div>').insertAfter($slider);
            $slider.on('click', '.slick-control-start', function (e) {
                e.preventDefault();
                $(this).removeClass('active').siblings('a').addClass('active');
                $slider.slick('slickPlay');
            }).on('click', '.slick-control-stop', function (e) {
                e.preventDefault();
                $(this).removeClass('active').siblings('a').addClass('active');
                $slider.slick('slickPause');
            });
            LaStudio.global.LazyLoad($slider.parent(), {
                rootMargin: '50px',
                load: function () {
                    LaStudio.global.eventManager.publish('LaStudio:Component:LazyLoadImage', [$slider]);
                }
            }).observe();
        };

        return {
            init: function () {
                if ("function" === typeof $.fn.slick) {
                    setup_slick();
                } else {
                    LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('slick')], setup_slick);
                }
            }
        };
    };

    LaStudio.component.CountDownTimer = function (el) {
        var $scope = $(el);
        var timeInterval,
            $coutdown = $scope.find('[data-due-date]'),
            endTime = new Date($coutdown.data('due-date') * 1000),
            elements = {
                days: $coutdown.find('[data-value="days"]'),
                hours: $coutdown.find('[data-value="hours"]'),
                minutes: $coutdown.find('[data-value="minutes"]'),
                seconds: $coutdown.find('[data-value="seconds"]')
            };

        LaStudio.component.CountDownTimer.updateClock = function () {
            var timeRemaining = LaStudio.component.CountDownTimer.getTimeRemaining(endTime);
            $.each(timeRemaining.parts, function (timePart) {
                var $element = elements[timePart];

                if ($element.length) {
                    $element.html(this);
                }
            });

            if (timeRemaining.total <= 0) {
                clearInterval(timeInterval);
            }
        };

        LaStudio.component.CountDownTimer.initClock = function () {
            LaStudio.component.CountDownTimer.updateClock();
            timeInterval = setInterval(LaStudio.component.CountDownTimer.updateClock, 1000);
        };

        LaStudio.component.CountDownTimer.splitNum = function (num) {
            var num = num.toString(),
                arr = [],
                reult = '';

            if (1 === num.length) {
                num = 0 + num;
            }

            arr = num.match(/\d{1}/g);
            $.each(arr, function (index, val) {
                reult += '<span class="lastudio-countdown-timer__digit">' + val + '</span>';
            });
            return reult;
        };

        LaStudio.component.CountDownTimer.getTimeRemaining = function (endTime) {
            var timeRemaining = endTime - new Date(),
                seconds = Math.floor(timeRemaining / 1000 % 60),
                minutes = Math.floor(timeRemaining / 1000 / 60 % 60),
                hours = Math.floor(timeRemaining / (1000 * 60 * 60) % 24),
                days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));

            if (days < 0 || hours < 0 || minutes < 0) {
                seconds = minutes = hours = days = 0;
            }

            return {
                total: timeRemaining,
                parts: {
                    days: LaStudio.component.CountDownTimer.splitNum(days),
                    hours: LaStudio.component.CountDownTimer.splitNum(hours),
                    minutes: LaStudio.component.CountDownTimer.splitNum(minutes),
                    seconds: LaStudio.component.CountDownTimer.splitNum(seconds)
                }
            };
        };

        LaStudio.component.CountDownTimer.initClock();
        return {
            init: function () {
                LaStudio.component.CountDownTimer.initClock();
            }
        };
    };

    LaStudio.component.InfiniteScroll = function (el) {
        var $pagination = $($(el).data('pagination'));
        return {
            init: function () {
                LaStudio.core.InfiniteScroll($pagination);
            }
        };
    };

    LaStudio.core.InfiniteScroll = function ($pagination) {
        LaStudio.global.LazyLoad($pagination, {
            rootMargin: '50px',
            threshold: 0.1,
            load: function () {
                $('.pagination_ajax_loadmore a', $pagination).trigger('click');
            }
        }).observe();
    };

    LaStudio.core.HeaderSticky = function () {
        var $header_builder = $('#lastudio-header-builder');
        var scroll_direction = 'none',
            last_scroll = $window.scrollTop();
        $window.on('scroll', function () {
            var currY = $window.scrollTop();
            scroll_direction = currY > last_scroll ? 'down' : currY === last_scroll ? 'none' : 'up';
            last_scroll = currY;
        });

        var prepareHeightForHeader = function () {
            var _current_height = 0;

            if ($.exists($header_builder)) {
                _current_height = $('.lahbhinner').outerHeight();
                document.documentElement.style.setProperty('--header-height', _current_height + 'px');
            }
        };

        prepareHeightForHeader();
        $window.on('resize', prepareHeightForHeader);

        function init_mobile_bar_sticky() {
            if (!$.exists($('.footer-handheld-footer-bar'))) {
                return;
            }

            var $_mobile_bar = $('.footer-handheld-footer-bar');
            $window.on('scroll', function (e) {
                var mb_height = LaStudio.global.getAdminBarHeight() + $('.lahbhinner', $header_builder).outerHeight();

                if (mb_height < 20) {
                    mb_height = 100;
                }

                if ($window.scrollTop() > mb_height) {
                    if (la_theme_config.mobile_bar == 'down') {
                        if (scroll_direction == 'down') {
                            $_mobile_bar.removeClass('sticky--unpinned').addClass('sticky--pinned');
                        } else {
                            $_mobile_bar.removeClass('sticky--pinned').addClass('sticky--unpinned');
                        }
                    } else if (la_theme_config.mobile_bar == 'up') {
                        if (scroll_direction == 'up') {
                            $_mobile_bar.removeClass('sticky--unpinned').addClass('sticky--pinned');
                        } else {
                            $_mobile_bar.removeClass('sticky--pinned').addClass('sticky--unpinned');
                        }
                    }
                } else {
                    $_mobile_bar.removeClass('sticky--pinned sticky--unpinned');
                }
            });
        }

        init_mobile_bar_sticky();
        var sticky_auto_hide = !!$body.hasClass('header-sticky-type-auto');

        function init_builder_sticky() {
            if (!$.exists($header_builder)) {
                return;
            }

            var $_header = $header_builder,
                $_header_outer = $('.lahbhouter', $header_builder),
                $_header_inner = $('.lahbhinner', $header_builder);
            var custom_bkp = 0,
                custom_bkp_offset = 0,
                has_cbkp = false;

            if (typeof la_theme_config.header_sticky_target !== "undefined" && la_theme_config.header_sticky_target != '' && $.exists($(la_theme_config.header_sticky_target))) {
                has_cbkp = $(la_theme_config.header_sticky_target);
            }

            if (typeof la_theme_config.header_sticky_offset !== "undefined") {
                custom_bkp_offset = parseInt(la_theme_config.header_sticky_offset);
            }

            var lastY = 0,
                offsetY = LaStudio.global.getOffset($_header_outer).y;
            $window.on('resize', function (e) {
                offsetY = LaStudio.global.getOffset($_header_outer).y;
            }).on('scroll', function (e) {
                if (has_cbkp !== false) {
                    custom_bkp = LaStudio.global.getOffset(has_cbkp).y;
                }

                var currentScrollY = $window.scrollTop();

                var _breakpoint = offsetY - LaStudio.global.getAdminBarHeight() + custom_bkp + custom_bkp_offset;

                if (sticky_auto_hide) {
                    _breakpoint = offsetY - LaStudio.global.getAdminBarHeight() + $_header_inner.outerHeight() + custom_bkp + custom_bkp_offset;
                }

                if (currentScrollY > _breakpoint) {
                    $_header_inner.css('top', LaStudio.global.getAdminBarHeight());

                    if (!$_header.hasClass('is-sticky')) {
                        $_header.addClass('is-sticky');
                    }

                    if (sticky_auto_hide) {
                        if (currentScrollY < $body.height() && lastY > currentScrollY) {
                            if ($_header_inner.hasClass('sticky--unpinned')) {
                                $_header_inner.removeClass('sticky--unpinned');
                            }

                            if (!$_header_inner.hasClass('sticky--pinned')) {
                                $_header_inner.addClass('sticky--pinned');
                            }
                        } else {
                            if ($_header_inner.hasClass('sticky--pinned')) {
                                $_header_inner.removeClass('sticky--pinned');
                            }

                            if (!$_header_inner.hasClass('sticky--unpinned')) {
                                $_header_inner.addClass('sticky--unpinned');
                            }
                        }
                    } else {
                        $_header_inner.addClass('sticky--pinned');
                    }
                } else {
                    if (sticky_auto_hide) {
                        if ($_header.hasClass('is-sticky')) {
                            if (_breakpoint - currentScrollY < $_header_inner.outerHeight()) {} else {
                                /** remove stuck **/
                                $_header.removeClass('is-sticky');
                                $_header_inner.css('top', '0').removeClass('sticky--pinned sticky--unpinned');
                            }
                        }
                    } else {
                        if ($_header.hasClass('is-sticky')) {
                            $_header.removeClass('is-sticky');
                            $_header_inner.css('top', '0').removeClass('sticky--pinned sticky--unpinned');
                        }
                    }
                }

                lastY = currentScrollY;
            });
        }

        if (!$body.hasClass('enable-header-sticky')) return;
        init_builder_sticky();
    };

    LaStudio.core.InstanceSearch = function ($modal) {
        if ($modal.hasClass('has-init')) {
            return;
        }

        $modal.addClass('has-init');
        var xhr = null,
            term = '',
            searchCache = {},
            $form = $modal.find('form.search-form'),
            $search = $form.find('input.search-field'),
            $results = $modal.find('.search-results'),
            $button = $results.find('.search-results-button'),
            post_type = $modal.find('input[name=post_type]').val(),
            search_type = $modal.hasClass('header-search-simple-wrap') ? 'minimal' : 'normal';

        var delaySearch = function () {
            var timer = 0;
            return function (callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        }();

        $modal.on('keyup', '.search-field', function (e) {
            var valid = false;

            if (typeof e.which === 'undefined') {
                valid = true;
            } else if (typeof e.which === 'number' && e.which > 0) {
                valid = !e.ctrlKey && !e.metaKey && !e.altKey;
            }

            if (!valid) {
                return;
            }

            if (xhr) {
                xhr.abort();
            }

            delaySearch(function () {
                search(true);
            }, 400);
        }).on('change', '.product-cats input', function () {
            if (xhr) {
                xhr.abort();
            }

            search(false);
        }).on('change', 'select', function () {
            if (xhr) {
                xhr.abort();
            }

            search(false);
        }).on('click', '.search-reset', function () {
            if (xhr) {
                xhr.abort();
            }

            $modal.addClass('reset');
            $results.find('.results-container, .view-more-results').slideUp(function () {
                $modal.removeClass('searching searched found-products found-no-product invalid-length reset');
            });
        }).on('focusout', '.search-field', function () {
            if ($(this).val().length < 2) {
                $results.find('.results-container, .view-more-results').slideUp(function () {
                    $modal.removeClass('searching searched found-products found-no-product invalid-length');
                });
            }
        }).on('focus', '.search-field', function () {
            if ($modal.hasClass('found-products')) {
                $results.find('.results-container').slideDown(200);
            }
        });
        /**
         * Private function for searching products
         */

        function search(typing) {
            var keyword = $search.val(),
                $category = $form.find('.product-cats input:checked'),
                category = $category.length ? $category.val() : $form.find('select').length ? $form.find('select').val() : '',
                key = keyword + '[' + category + ']';

            if (term === keyword && typing) {
                return;
            }

            term = keyword;

            if (keyword.length < 2) {
                $modal.removeClass('searching found-products found-no-product').addClass('invalid-length');
                return;
            }

            var url = $form.attr('action') + '?' + $form.serialize();
            $button.removeClass('fadeInUp');
            $('.view-more-results', $results).slideUp(10);
            $modal.removeClass('found-products found-no-product').addClass('searching');

            if (key in searchCache) {
                showResult(searchCache[key]);
            } else {
                xhr = $.get(url, function (response) {
                    var $content = $('#content.site-content', response);

                    if ('product' === post_type) {
                        var $products = $('#la_shop_products .row ul.products', $content);

                        if ($products.length) {
                            $products.children('li:eq(20)').nextAll().remove(); // Cache

                            searchCache[key] = {
                                found: true,
                                items: $products,
                                url: url
                            };
                        } else {
                            // Cache
                            searchCache[key] = {
                                found: false,
                                text: $('.woocommerce-info', $content).text()
                            };
                        }
                    } else {
                        var $posts = $('#blog-entries .lastudio-posts__item', $content);

                        if ($posts.length) {
                            $posts.addClass('col-md-4');
                            searchCache[key] = {
                                found: true,
                                items: $('<div class="posts" />').append($posts),
                                url: url
                            };
                        } else {
                            searchCache[key] = {
                                found: false,
                                text: $('#blog-entries article .entry', $content).text()
                            };
                        }
                    }

                    showResult(searchCache[key]);
                    $modal.addClass('searched');
                    xhr = null;
                }, 'html');
            }
        }
        /**
         * Private function for showing the search result
         *
         * @param result
         */


        function showResult(result) {
            var extraClass = 'product' === post_type ? 'woocommerce' : 'la-post-grid';
            $modal.removeClass('searching');

            if (result.found) {
                var grid = result.items.clone(),
                    items = grid.children();
                $modal.addClass('found-products');

                if (search_type == 'minimal') {
                    if (grid.hasClass('products')) {
                        grid.attr('class', 'products ul_products products-grid grid-items');
                        items.removeAttr('style');
                    }
                }

                $results.find('.results-container').addClass(extraClass).html(grid);
                $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger('lastudio-lazy-images-load').trigger('jetpack-lazy-images-load').trigger('lastudio-object-fit');
                LaStudio.core.initAll($results); // Add animation class

                for (var index = 0; index < items.length; index++) {
                    $(items[index]).css('animation-delay', index * 100 + 'ms');
                }

                items.addClass('fadeInUp animated');
                $button.attr('href', result.url).css('animation-delay', index * 100 + 'ms').addClass('fadeInUp animated');
                $results.find('.results-container, .view-more-results').slideDown(300, function () {
                    $modal.removeClass('invalid-length');
                });
            } else {
                $modal.addClass('found-no-product');
                $results.find('.results-container').removeClass(extraClass).html($('<div class="not-found text-center" />').text(result.text));
                $button.attr('href', '#');
                $results.find('.view-more-results').slideUp(300);
                $results.find('.results-container').slideDown(300, function () {
                    $modal.removeClass('invalid-length');
                });
            }

            $modal.addClass('searched');
        }
    };

    LaStudio.core.MegaMenu = function () {
        function fix_megamenu_position($elem, $container, container_width, isVerticalMenu) {
            if ($('.megamenu-inited', $elem).length) {
                return false;
            }

            var $popup = $('> .sub-menu', $elem);
            if ($popup.length == 0) return;
            var megamenu_width = $popup.outerWidth();

            if (megamenu_width > container_width) {
                megamenu_width = container_width;
            }

            if (!isVerticalMenu) {
                var container_padding_left = parseInt($container.css('padding-left')),
                    container_padding_right = parseInt($container.css('padding-right')),
                    parent_width = $popup.parent().outerWidth(),
                    left = 0,
                    container_offset = LaStudio.global.getOffset($container),
                    megamenu_offset = LaStudio.global.getOffset($popup);
                var megamenu_offset_x = megamenu_offset.x,
                    container_offset_x = container_offset.x;

                if (megamenu_width > parent_width) {
                    left = -(megamenu_width - parent_width) / 2;
                } else {
                    left = 0;
                }

                if (LaStudio.global.isRTL()) {
                    var megamenu_offset_x_swap = $window.width() - (megamenu_width + megamenu_offset_x),
                        container_offset_x_swap = $window.width() - ($container.outerWidth() + container_offset_x);

                    if (megamenu_offset_x_swap - container_offset_x_swap - container_padding_right + left < 0) {
                        left = -(megamenu_offset_x_swap - container_offset_x_swap - container_padding_right);
                    }

                    if (megamenu_offset_x_swap + megamenu_width + left > container_offset_x + $container.outerWidth() - container_padding_left) {
                        left -= megamenu_offset_x_swap + megamenu_width + left - (container_offset_x + $container.outerWidth() - container_padding_left);
                    }

                    $popup.css('right', left).css('right');
                } else {
                    if (megamenu_offset_x - container_offset_x - container_padding_left + left < 0) {
                        left = -1 * (megamenu_offset_x - container_offset_x - container_padding_left);
                    }

                    if (megamenu_offset_x + megamenu_width + left > container_offset_x + $container.outerWidth() - container_padding_right) {
                        left = 0;
                        left = -1 * (megamenu_offset_x + megamenu_width + left - (container_offset_x + $container.outerWidth() - container_padding_right));
                    }

                    if ($container.is('body')) {
                        left = -1 * megamenu_offset_x;
                    }

                    $popup.css('left', left).css('left');
                }
            }

            if (isVerticalMenu) {
                var clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                    itemOffset = $popup.offset(),
                    itemHeight = $popup.outerHeight(),
                    scrollTop = $window.scrollTop();

                if (itemOffset.top - scrollTop + itemHeight > clientHeight) {
                    var __top = clientHeight - (itemOffset.top + scrollTop + itemHeight + 50);

                    if (itemHeight >= clientHeight) {
                        //__top = 1 - itemOffset.top - scrollTop;
                        $popup.offset({
                            top: LaStudio.global.getAdminBarHeight()
                        });
                    } else {
                        $popup.css({
                            top: __top
                        });
                    }
                }
            }

            $popup.addClass('megamenu-inited');
        }

        LaStudio.global.eventManager.subscribe('LaStudio:MegaMenuBuilder:MenuPosition', function (e, $megamenu) {
            if ($.exists($megamenu)) {
                $megamenu.closest('.lahb-content-wrap').addClass('position-relative');
                $megamenu.each(function () {
                    var _that = $(this),
                        container_width = 0,
                        $container = _that.closest('.lahb-content-wrap'),
                        isVerticalMenu = false;

                    container_width = $container.width();

                    if (_that.closest('.lahb-vertital-menu_nav').length) {
                        isVerticalMenu = true;
                    }

                    if ($body.hasClass('header-type-vertical')) {
                        container_width = 1200;

                        if ($window.width() < 1500) {
                            if ($body.hasClass('header-type-vertical--toggle')) {
                                container_width = $window.width() - $('.lahb-vertical-toggle-wrap').outerWidth() - $('.lahb-vertical.lahb-vcom').outerWidth();
                            } else {
                                container_width = $window.width() - $masthead.outerWidth();
                            }
                        }
                    } else {
                        if (_that.hasClass('lahb-vertital-menu_nav')) {
                            container_width = container_width - _that.outerWidth();
                        }
                    }

                    $('li.mega .megamenu-inited', _that).removeClass('megamenu-inited');
                    $('li.mega > .sub-menu', _that).removeAttr('style');
                    $('li.mega', _that).each(function () {
                        var $menu_item = $(this),
                            $popup = $('> .sub-menu', $menu_item),
                            $inner_popup = $('> .sub-menu > .mm-mega-li', $menu_item),
                            item_max_width = parseInt(!!$inner_popup.data('maxWidth') ? $inner_popup.data('maxWidth') : $inner_popup.css('maxWidth')),
                            $_container = $container;

                        var _cw = $megamenu.closest('.container').width();

                        var default_width = _cw > 1200 ? _cw : 1200;

                        if (container_width < default_width) {
                            default_width = container_width;
                        }

                        if (isNaN(item_max_width)) {
                            item_max_width = default_width;
                        }

                        if (default_width > item_max_width) {
                            default_width = parseInt(item_max_width);
                        }

                        if ($menu_item.hasClass('mm-popup-force-fullwidth') && $menu_item.closest('.lahb-vertical').length == 0) {
                            $inner_popup.data('maxWidth', item_max_width).css('maxWidth', 'none');
                            $('> .mm-mega-ul', $inner_popup).css('width', item_max_width);

                            if (!isVerticalMenu) {
                                default_width = $window.width();
                                $_container = $body;
                            } else {
                                if (_that.closest('.vertital-menu_nav-hastoggle').length == 0) {
                                    default_width = $('#outer-wrap > #wrap').width();
                                }
                            }
                        }

                        $popup.width(default_width);
                        fix_megamenu_position($menu_item, $_container, container_width, isVerticalMenu);
                    });
                });
            }
        });
        LaStudio.global.eventManager.publish('LaStudio:MegaMenuBuilder:MenuPosition', [$('body .lahb-nav-wrap.has-megamenu')]);
        $window.on('resize', function () {
            LaStudio.global.eventManager.publish('LaStudio:MegaMenuBuilder:MenuPosition', [$('body .lahb-nav-wrap.has-megamenu')]);
        });
        $('.lahb-vertital-menu_nav .lahb-vertital-menu_button button').on('click', function (e) {
            e.preventDefault();
            var $parent = $(this).closest('.lahb-vertital-menu_nav');
            $parent.hasClass('open') ? $parent.removeClass('open') : $parent.addClass('open');
        });
    };

    LaStudio.core.ElementClickEvent = function () {
        $document.on('LaStudio:Component:Popup:Close', function (e) {
            e.preventDefault();

            try {
                $.featherlight.close();
            } catch (e) {}
        }).on('touchend click', '.la-overlay-global', function (e) {
            e.preventDefault();
            $body.removeClass('open-lahb-vertical open-cart-aside');
        }).on('click', '.footer-handheld-footer-bar .la_com_action--dropdownmenu .component-target', function (e) {
            e.preventDefault();
            var $_parent = $(this).parent();
            $body.removeClass('open-mobile-menu open-search-form');

            if ($_parent.hasClass('active')) {
                $_parent.removeClass('active');
                $body.removeClass('open-overlay');
            } else {
                $_parent.addClass('active');
                $_parent.siblings().removeClass('active');
                $body.addClass('open-overlay');
            }
        }).on('click', '.footer-handheld-footer-bar .la_com_action--searchbox', function (e) {
            e.preventDefault();
            var $this = $(this);

            if ($(this).hasClass('active')) {
                $body.removeClass('open-search-form ');
                $(this).removeClass('active');
            } else {
                $body.addClass('open-search-form');
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
                $body.removeClass('open-overlay');
            }
        }).on('click', '.la-popup:not(.elementor-widget):not([data-gallery-id]), .la-popup.elementor-widget a', function (e) {
            e.preventDefault();

            var $that = $(this),
                _href = LaStudio.global.parseVideo($that.attr('href')),
                typeMapping = {
                    'image': /\.(png|jp?g|gif|tiff?|bmp|svg|webp)(\?\S*)?$/i,
                    'inline': /^[#.]\w/,
                    'html': /^\s*<[\w!][^<]*>/,
                    'elementor_image': /\.(png|jpe?g|gif|svg|webp)(\?\S*)?$/i
                };

            var _type = 'iframe';

            if (_href.match(typeMapping.image)) {
                _type = 'image';
            } else if (_href.match(typeMapping.inline)) {
                _type = 'jquery';
            } else if (_href.match(typeMapping.html)) {
                _type = 'html';
            } else {
                _type = 'iframe';
            }

            if (_href.match(typeMapping.elementor_image) && typeof elementorFrontend !== "undefined" && elementorFrontend.getKitSettings("global_image_lightbox")) {
                return;
            }

            var init_auto_popup = function () {
                $.featherlight(_href, {
                    type: _type,
                    persist: 'shared',
                    background: '<div class="featherlight featherlight-loading"><div class="featherlight-outer"><button class="featherlight-close-icon featherlight-close" aria-label="Close"><i class="lastudioicon-e-remove"></i></button><div class="featherlight-content"><div class="featherlight-inner"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div></div><div class="custom-featherlight-overlay"></div></div>',
                    beforeClose: function (evt) {
                        if (_type == 'jquery' && $(_href).length > 0) {
                            var _temp_id = _href.replace('#', '#__tmp__');

                            $(_href).insertBefore($(_temp_id));
                            $(_temp_id).remove();
                        }
                    },
                    beforeOpen: function () {
                        if (_type == 'jquery' && $(_href).length > 0) {
                            var _temp_id = _href.replace('#', '__tmp__');

                            $('<div id="' + _temp_id + '" class="featherlight__placeholder"></div>').insertBefore($(_href));
                        }
                    },
                    iframeAllow: "autoplay",
                    iframeAllowfullscreen: "1"
                });
            };

            if ($.isFunction($.fn.featherlight)) {
                init_auto_popup();
            } else {
                LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('featherlight')], init_auto_popup);
            }
        }).on('click', '.la-inline-popup', function (e) {
            e.preventDefault();

            var _this = $(this);

            var $popup = $(_this.data('href') || _this.attr('href'));
            var extra_class = _this.data('component_name') || '';
            extra_class += ' featherlight--inline';

            var init_auto_popup = function () {
                $.featherlight($popup, {
                    // persist: 'shared',
                    // type: 'jquery',
                    background: '<div class="featherlight featherlight-loading"><div class="featherlight-outer"><button class="featherlight-close-icon featherlight-close" aria-label="Close"><i class="lastudioicon-e-remove"></i></button><div class="featherlight-content"><div class="featherlight-inner"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div></div><div class="custom-featherlight-overlay"></div></div>',
                    beforeOpen: function (evt) {
                        $body.addClass(extra_class);
                    },
                    afterClose: function (evt) {
                        $body.removeClass(extra_class);
                    }
                });
            };

            if ($.isFunction($.fn.featherlight)) {
                init_auto_popup();
            } else {
                LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('featherlight')], init_auto_popup);
            }
        }).on('click', '.custom-lighcase-btn-close, .popup-button-continue, .custom-lightcase-overlay, .custom-featherlight-close, .btn-close-newsletter-popup', function (e) {
            e.preventDefault();
            $document.trigger('LaStudio:Component:Popup:Close');
        }).on('click', '.btn-close-search', function (e) {
            e.preventDefault();
            $body.removeClass('open-search-form');
        }).on('click', '.btn-close-cart', function (e) {
            e.preventDefault();
            $body.removeClass('open-cart-aside');
        }).on('click', '.la-ajax-pagination .pagination_ajax_loadmore a', function (e) {
            e.preventDefault();

            if ($('body').hasClass('elementor-editor-active')) {
                return false;
            }

            var $pagination, url_request, _parent_container, _container, _item_selector;

            $pagination = $(this).closest('.la-ajax-pagination');
            _parent_container = $pagination.data('parent-container');
            _container = $pagination.data('container');
            _item_selector = $pagination.data('item-selector');
            var _infinite_flag = false;
            var _use_restapi = false;

            if ($pagination.hasClass('with-wp-ajax')) {
                _use_restapi = false;
            }

            if ($pagination.data('infinite-flag')) {
                _infinite_flag = $pagination.data('infinite-flag');
            }

            if ($('a.next', $pagination).length) {
                if ($pagination.hasClass('doing-ajax')) {
                    return false;
                }

                $pagination.addClass('doing-ajax');
                $(_parent_container).addClass('doing-ajax');

                var success_func = function (response) {
                    var $data = $(response).find(_container + ' ' + _item_selector);

                    if ($(_container).hasClass('la-slick-slider')) {
                        $(_container).slick('slickAdd', $data);
                        $(_container).slick('setPosition');
                    } else if ($(_container).data('isotope')) {
                        $(_container).isotope('insert', $data.addClass('showmenow'));

                        if ($(_container).data('la_component') == 'AdvancedMasonry') {
                            LaStudio.global.eventManager.publish('LaStudio:AdvancedMasonry:calculatorItemWidth', [$(_container), false]);
                            $(_container).isotope('layout');
                        } else {
                            setTimeout(function () {
                                $(_container).isotope('layout');
                            }, 500);
                        }

                        $(_container).trigger('LaStudio:Masonry:ajax_loadmore', [$(_container)]);
                    } else {
                        $data.addClass('fadeIn animated').appendTo($(_container));
                    }

                    $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger('lastudio-lazy-images-load').trigger('jetpack-lazy-images-load').trigger('lastudio-object-fit');
                    LaStudio.core.initAll($(_parent_container));
                    $(_parent_container).removeClass('doing-ajax');
                    $pagination.removeClass('doing-ajax la-ajax-load-first');

                    if ($(response).find(_parent_container + ' .la-ajax-pagination').length) {
                        var $new_pagination = $(response).find(_parent_container + ' .la-ajax-pagination');
                        $pagination.replaceWith($new_pagination);
                        $pagination = $new_pagination;
                    } else {
                        $pagination.addClass('nothingtoshow');
                    }

                    if (_infinite_flag !== false) {
                        setTimeout(function () {
                            LaStudio.core.InfiniteScroll($pagination);
                        }, 2000);
                    }
                };

                if (_use_restapi) {
                    LaStudio.utils.AjaxRequest(LaStudio.global.addQueryArg(la_theme_config.ajax_url, 'action', $pagination.data('queryaction')), {
                        method: 'POST',
                        body: JSON.stringify({
                            'args': $pagination.data('queryargs')
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(function (response) {
                        return response.json();
                    }).then(function (response) {
                        success_func(response.data);
                    });
                } else {
                    url_request = $('a.next', $pagination).attr('href').replace(/^\//, '');
                    url_request = LaStudio.global.removeURLParameter(url_request, '_');
                    var ajaxOpts = {
                        url: url_request,
                        type: "get",
                        cache: true,
                        dataType: 'html',
                        success: function (res) {
                            success_func(res);
                        }
                    };
                    $.ajax(ajaxOpts);
                }
            }
        }).on('click', '.la-ajax-pagination .page-numbers a', function (e) {
            e.preventDefault();

            if ($('body').hasClass('elementor-editor-active')) {
                return false;
            }

            var $pagination, url_request, _parent_container, _container, _item_selector;

            $pagination = $(this).closest('.la-ajax-pagination');
            _parent_container = $pagination.data('parent-container');
            _container = $pagination.data('container');
            _item_selector = $pagination.data('item-selector');

            if ($(_parent_container).hasClass('doing-ajax')) {
                return;
            }

            $(_parent_container).addClass('doing-ajax');
            $pagination.addClass('doing-ajax');
            url_request = LaStudio.global.removeURLParameter($(this).attr('href').replace(/^\//, ''), '_');
            $.ajax({
                url: url_request,
                type: "get",
                dataType: 'html',
                cache: true,
                ajax_request_id: LaStudio.global.getUrlParameter($pagination.data('ajax_request_id'), url_request),
                success: function (response) {
                    var $data = $(response).find(_container + ' ' + _item_selector);

                    if ($(_container).hasClass('la-slick-slider')) {
                        $(_container).slick('unslick').removeData('initAutoCarousel');
                        $data.appendTo($(_container).empty());
                    } else if ($(_container).data('isotope')) {
                        $(_container).isotope('remove', $(_container).isotope('getItemElements'));
                        $(_container).isotope('insert', $data.addClass('showmenow'));

                        if ($(_container).data('la_component') == 'AdvancedMasonry') {
                            LaStudio.global.eventManager.publish('LaStudio:AdvancedMasonry:calculatorItemWidth', [$(_container), false]);
                            $(_container).isotope('layout');
                        } else {
                            setTimeout(function () {
                                $(_container).isotope('layout');
                            }, 500);
                        }

                        $(_container).trigger('LaStudio:Masonry:ajax_pagination', [$(_container)]);
                    } else {
                        $data.addClass('fadeIn animated');
                        $data.appendTo($(_container).empty());
                    }

                    $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger('lastudio-lazy-images-load').trigger('jetpack-lazy-images-load').trigger('lastudio-object-fit');
                    LaStudio.core.initAll($(_parent_container));
                    $(_parent_container).removeClass('doing-ajax');
                    $pagination.removeClass('doing-ajax');

                    if ($(response).find(_parent_container + ' .la-ajax-pagination').length) {
                        $pagination.replaceWith($(response).find(_parent_container + ' .la-ajax-pagination').removeClass('nothingtoshow'));
                    }
                }
            });
        });
    };

    LaStudio.core.Blog = function ($sidebar_inner) {
        $sidebar_inner = $sidebar_inner || $('.sidebar-inner');
        $('.menu li a:empty', $sidebar_inner).each(function () {
            $(this).closest('li').remove();
        });
        $('.widget_pages > ul, .widget_archive > ul, .widget_categories > ul, .widget_product_categories > ul, .widget_meta > ul', $sidebar_inner).addClass('menu').closest('.widget').addClass('accordion-menu');
        $('.widget_nav_menu', $sidebar_inner).closest('.widget').addClass('accordion-menu');
        $('.widget_categories > ul li.cat-parent,.widget_product_categories li.cat-parent', $sidebar_inner).addClass('mm-item-has-sub');
        $('.menu li > ul', $sidebar_inner).each(function () {
            var $ul = $(this);
            $ul.before('<span class="narrow"><i></i></span>');
        });
        $sidebar_inner.on('click', '.accordion-menu li.menu-item-has-children > a,.menu li.mm-item-has-sub > a,.menu li > .narrow', function (e) {
            e.preventDefault();
            var $parent = $(this).parent();

            if ($parent.hasClass('open')) {
                $parent.removeClass('open');
                $parent.find('>ul').stop().slideUp();
            } else {
                $parent.addClass('open');
                $parent.find('>ul').stop().slideDown();
                $parent.siblings().removeClass('open').find('>ul').stop().slideUp();
            }
        });
    };

    LaStudio.core.SitePreload = function () {
        var pbar = document.getElementById('wpadminbar');

        if (pbar) {
            pbar.classList.add('wpbar');
        }
        /** Back To Top **/


        $window.on('load scroll', function () {
            if ($window.scrollTop() > $window.height() + 100) {
                $('.backtotop-container').addClass('show');
            } else {
                $('.backtotop-container').removeClass('show');
            }
        });
        $document.on('click', '.btn-backtotop', function (e) {
            e.preventDefault();
            $htmlbody.animate({
                scrollTop: 0
            }, 800);
        });
        $body.on('lastudio-fix-ios-limit-image-resource', function () {
            if (!('matchMedia' in window)) {
                return;
            }

            if (window.matchMedia("(max-width: 1024px)").matches) {
                $('li.product_item.thumb-has-effect').each(function () {
                    $(this).removeClass('thumb-has-effect');
                    $(this).find('.p_img-second').remove();
                });
            }
        }).trigger('lastudio-fix-ios-limit-image-resource');
        $body.removeClass('site-loading');
        $window.on('beforeunload', function (e) {
            var browser_information = LaStudio.global.getBrowseInformation();
            if (browser_information.name != 'safari' && window.self === window.top) {
                if (typeof window['hack_beforeunload_time'] === "undefined" || typeof window['hack_beforeunload_time'] !== "undefined" && e.timeStamp - window['hack_beforeunload_time'] > 1000) {
                    $body.addClass('site-loading');
                }
            }
        });
        $document.on('click', 'a[href^="tel:"], a[href^="mailto:"], a[href^="callto"], a[href^="skype"], a[href^="whatsapp"], a.mail-link, a.noloading', function (e) {
            window['hack_beforeunload_time'] = parseInt(e.timeStamp);
        });
        $window.on('pageshow', function (e) {
            if (e.originalEvent.persisted) {
                $body.removeClass('site-loading body-loading');
            }
        });
        LaStudio.global.eventManager.subscribe('LaStudio:Component:LazyLoadImage', function (e, $container) {
            $container.find('.la-lazyload-image:not([data-element-loaded="true"]), img[data-lazy-src]:not([data-element-loaded="true"]), img[data-lazy-original]:not([data-element-loaded="true"])').each(function (idx, element) {
                LaStudio.global.makeImageAsLoaded(element);
            });
        });
        $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger('lastudio-lazy-images-load').trigger('jetpack-lazy-images-load').trigger('lastudio-object-fit');
    };

    LaStudio.core.AjaxLoadTemplates = function () {
        var elementorFrontendInit = function (templateContainer) {
            if (typeof elementorFrontend !== "undefined" && typeof elementorFrontend.hooks !== "undefined") {
                $(window).trigger('elementor/frontend/init');
            }

            $(templateContainer).find('div[data-element_type]').each(function () {
                try {
                    if (window.elementorFrontend && window.elementorFrontend.elementsHandler) {
                        elementorFrontend.elementsHandler.runReadyTrigger($(this));
                    }
                } catch (ex) {
                    LaStudio.global.log(ex);
                    $(this).remove();
                }
            });

            if ($(templateContainer).closest('.site-footer').length) {
                document.documentElement.style.setProperty('--footer-height', "" + $('#footer').innerHeight() + "px");
            }
        };

        var processInsertData = function (el, templateContent){
            if (templateContent) {
                $(el).html(templateContent);
                elementorFrontendInit(el);
                LaStudio.core.initAll($(el));
                if($(el).find('div[data-ajaxtmp]:not(.template-loaded,.is-loading)').length){
                    LaStudio.global.log('found template in ajax content');
                    LaStudio.core.AjaxLoadTemplates();
                }
            }

            $('.lahb-responsive-menu-wrap').trigger('LaStudioHeaderBuilder:render-responsive');
            $('.la-hamburger-wrap,.hamburger-menu-content').trigger('LaStudioHeaderBuilder:render-hamburger-menu');
            $('.hamburger-nav,.responav').trigger('LaStudioHeaderBuilder:render-nav-toogleicon');

            $('.elementor-motion-effects-element').trigger('resize');
            $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger('lastudio-lazy-images-load').trigger('jetpack-lazy-images-load').trigger('lastudio-object-fit');
            LaStudio.global.eventManager.publish('LaStudio:AjaxLoadTemplates:success', [$(el)]);
        }

        var elementorContentRender = function (el, templateContent) {
            Promise.all(LaStudio.utils.localCache.addedAssetsPromises).then(function (value) {
                processInsertData(el, templateContent);
            }, function (reason){
                LaStudio.global.log(`An error occurred while insert the asset resources, however we still need to insert content. Reason detail: "${reason}"`);
                processInsertData(el, templateContent);
            })
        };

        var templates = document.querySelectorAll('body:not(.elementor-editor-active) div[data-ajaxtmp]:not(.template-loaded)');

        var templateRenderCallback = function (response, el){
            var templateContent = response['template_content'],
                templateScripts = response['template_scripts'],
                templateStyles = response['template_styles'],
                template_metadata = response['template_metadata'];

            for (var scriptHandler in templateScripts) {
                LaStudio.utils.localCache.addedAssetsPromises.push(LaStudio.global.loadScriptAsync(scriptHandler, templateScripts[scriptHandler], '', true));
            }

            for (var styleHandler in templateStyles) {
                LaStudio.utils.localCache.addedAssetsPromises.push(LaStudio.global.loadStyle(styleHandler, templateStyles[styleHandler]));
            }
            document.querySelectorAll('body:not(.elementor-editor-active) div[data-ajaxtmp][data-cache-id="' + el + '"]:not(.template-loaded)').forEach(function (elm) {
                elm.classList.remove('is-loading');
                elm.classList.add('template-loaded');
                elementorContentRender(elm, templateContent);
            });
            var wpbar = document.querySelectorAll('#wp-admin-bar-elementor_edit_page ul');

            if (wpbar && typeof template_metadata['title'] !== "undefined") {
                setTimeout(function () {
                    $('<li><a class="ab-item" href="' + template_metadata['href'] + '"><span class="elementor-edit-link-title">' + template_metadata['title'] + '</span><span class="elementor-edit-link-type">' + template_metadata['sub_title'] + '</span></a></li>').prependTo($(wpbar));
                }, 2000);
            }
        }

        if (templates.length) {

            var template_ids = [];
            templates.forEach(function (el) {
                if (!el.classList.contains('is-loading')) {
                    el.classList.add('is-loading');
                    var _cache_key = el.getAttribute('data-template-id');
                    if (!template_ids.includes(_cache_key)) {
                        template_ids.push(_cache_key);
                    }
                    el.setAttribute('data-cache-id', _cache_key);
                }
            });
            template_ids.forEach(function (el) {

                var cached_key = 'ElementorTemplate_' + el;
                var cached_key2 = 'ElementorTemplateExist_' + el;

                if(LaStudio.utils.localCache.exist(cached_key2)){
                    if(LaStudio.utils.localCache.exist(cached_key)){
                        templateRenderCallback(LaStudio.utils.localCache.get(cached_key), el);
                    }
                    return;
                }

                LaStudio.utils.localCache.set(cached_key2, 'yes');

                if(LaStudio.utils.localCache.exist(cached_key)){
                    templateRenderCallback(LaStudio.utils.localCache.get(cached_key), el);
                }
                else{
                    var _url = LaStudio.global.addQueryArg(la_theme_config.ajax_url, 'action', 'lastudio_get_elementor_template');
                    _url = LaStudio.global.addQueryArg(_url, 'id', el);
                    _url = LaStudio.global.addQueryArg(_url, 'dev', la_theme_config.disable_cache);
                    var request = LaStudio.utils.AjaxRequest(_url, {
                        method: 'GET'
                    });
                    request.then(function (response) {
                        return response.json();
                    }).then(function (response) {
                        templateRenderCallback(response, el);
                        LaStudio.utils.localCache.set(cached_key, response);
                    });
                }
            });
        }

        var nav_templates = document.querySelectorAll('div[data-ajaxnavtmp]:not(.template-loaded)');

        if (nav_templates.length) {
            var nav_template_ids = [];
            nav_templates.forEach(function (el) {
                if (!el.classList.contains('is-loading')) {
                    el.classList.add('is-loading');

                    var _cache_key = btoa('id:' + el.getAttribute('data-menu-id') + '|args:' + el.getAttribute('data-options'));

                    if (!nav_template_ids.includes(_cache_key)) {
                        nav_template_ids.push(_cache_key);
                    }

                    el.setAttribute('data-cache-id', _cache_key);
                }
            });
            nav_template_ids.forEach(function (el) {
                var _url = LaStudio.global.addQueryArg(la_theme_config.current_url, 'la_ajax', 'lastudio_get_menu');

                _url = LaStudio.global.addQueryArg(_url, 'dev', la_theme_config.disable_cache);

                var _new_body = atob(el).split('|');

                var request = LaStudio.utils.AjaxRequest(_url, {
                    method: 'POST',
                    body: JSON.stringify({
                        'menu_id': _new_body[0].replace('id:', ''),
                        'menu_args': JSON.parse(_new_body[1].replace('args:', ''))
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                request.then(function (response) {
                    return response.json();
                }).then(function (response) {
                    document.querySelectorAll('div[data-ajaxnavtmp][data-cache-id="' + el + '"]:not(.template-loaded)').forEach(function (elm) {
                        elm.classList.remove('is-loading');
                        elm.classList.add('template-loaded');
                        elementorContentRender(elm, response.data);
                    });
                });
            });
        }
    };

    LaStudio.core.OnLoadEvent = function () {
        $body.removeClass('site-loading body-loading').addClass('body-loaded');
        LaStudio.core.HeaderSticky();
        $('.slick-slider').on('beforeChange afterChange', function (event, slick, currentSlide, nextSlide) {
            LaStudio.global.eventManager.publish('LaStudio:Component:LazyLoadImage', [$(this)]);
        });
        $('.force-active-object-fit').each(function () {
            $body.trigger('lastudio-prepare-object-fit', [$(this)]);
        });

        if ($('#footer').length) {
            document.documentElement.style.setProperty('--footer-height', "" + $('#footer').innerHeight() + "px");
        }
    };

    LaStudio.core.CustomFunction = function () {
        $('.entry div.gallery[class*="galleryid-"], .wp-block-gallery').each(function () {
            var _id = LaStudio.global.getRandomID();

            $(this).find('a').addClass('la-popup').attr('data-elementor-lightbox-slideshow', _id);
        });

        function init_wc_swatch() {
            var init_swatch_cb = function () {
                $('.variations_form').trigger('wc_variation_form');
            };

            if (la_theme_config.la_extension_available.swatches) {
                if ("function" === typeof $.fn.lastudio_variation_form) {
                    init_swatch_cb();
                } else {
                    LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('swatches')], init_swatch_cb);
                }
            }
        }

        $(document).on('reinit_la_swatches', function () {
            $('.product_item .product_item--info').each(function () {
                $(this).closest('.product_item').css('--item_info', $(this).innerHeight() + 'px');
            });

            if ($body.hasClass('elementor-editor-active')) {
                return;
            }

            $('.product_item .la-swatch-control:not(.has-inited)').each(function (){
                $(this).append('<div class="swatch-wrapper-more"><a href="javascript:;"><i class="lastudioicon-i-add"></i><span>'+(la_theme_config.i18n.more_text || 'more')+'</span></a></div>');
                $(this).addClass('has-inited');
                $('.swatch-wrapper-more a', $(this)).on('click', function (e){
                    e.preventDefault();
                    $(this).closest('.la-swatch-control').toggleClass('active-sub');
                })
            });

            if ($('.variations_form').length && la_theme_config.has_wc) {
                var $variations_form = $('.variations_form');

                var init_wc_swatch_default = function () {
                    $variations_form.each(function () {
                        $(this).wc_variation_form();

                        if ($(this).closest('.product_item')) {
                            $(this).closest('.product_item').css('--variations_form', $(this).innerHeight() + 'px');
                        }
                    });
                    init_wc_swatch();
                };

                if ($.fn.wc_variation_form) {
                    init_wc_swatch_default();
                }
                else {
                    if (typeof _wpUtilSettings === "undefined") {
                        window._wpUtilSettings = {
                            ajax: {
                                url: la_theme_config.ajax_url
                            }
                        };
                    }

                    if (typeof wc_add_to_cart_variation_params === "undefined") {
                        window.wc_add_to_cart_variation_params = la_theme_config.i18n.variation;
                    }

                    LaStudio.global.loadScriptAsyncSequence([['underscore-js', la_theme_config.wc_variation.underscore, true], ['wp-util-js', la_theme_config.wc_variation.wp_util, false], ['wc-add-to-cart-variation-js', la_theme_config.wc_variation.base, false]], init_wc_swatch_default);
                }
            }
        });
    };

    LaStudio.core.DomLoadEvent = function () {
        LaStudio.utils.validCache();
        $window.on('elementor/frontend/init', function () {
            if (typeof elementorFrontend !== "undefined" && typeof elementorFrontend.hooks !== "undefined") {
                elementorFrontend.hooks.addFilter('frontend/handlers/menu_anchor/scroll_top_distance', function (scrollTop) {
                    if ($('body').hasClass('enable-header-sticky') && $('#lastudio-header-builder').length) {
                        return scrollTop - $('#lastudio-header-builder').outerHeight();
                    }

                    return scrollTop;
                });
                elementorFrontend.hooks.addAction('frontend/element_ready/toggle.default', function ($scope) {
                    if ($scope.hasClass('toggle__active-first')) {
                        $scope.find('.elementor-toggle-item:first-child .elementor-tab-title').trigger('click');
                    }
                }, 20);
            }
        });

        function LoadLazyScripts() {
            if (!LaStudio.global.isPageSpeed()) {
                $('body').addClass('body-completely-loaded');
                LaStudio.core.AjaxLoadTemplates();
            }

            if (la_theme_config.has_wc && typeof LaStudioWooCommerce === "undefined") {
                LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('woocommerce')], function () {
                    LaStudioWooCommerce.AutoInit();
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            LoadLazyScripts();

            if (la_theme_config.has_wc && typeof LaStudioWooCommerce !== "undefined") {
                LaStudioWooCommerce.AutoInit();
            }
        });
    };

    LaStudio.core.OpenNewsletterPopup = function ($popup, callback) {
        var extra_class = 'open-newsletter-popup';

        var show_popup = function () {
            $.featherlight($popup, {
                persist: 'shared',
                type: 'jquery',
                background: '<div class="featherlight featherlight-loading"><div class="featherlight-outer"><button class="featherlight-close-icon featherlight-close" aria-label="Close"><i class="lastudioicon-e-remove"></i></button><div class="featherlight-content"><div class="featherlight-inner"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div></div><div class="custom-featherlight-overlay"></div></div>',
                beforeClose: function (evt) {
                    var _temp_id = '#__tmp__' + $popup.attr('id');

                    $popup.insertBefore($(_temp_id));
                    $(_temp_id).remove();
                },
                beforeOpen: function () {
                    var _temp_id = '__tmp__' + $popup.attr('id');

                    $('<div id="' + _temp_id + '" class="featherlight__placeholder"></div>').insertBefore($popup);
                    $body.addClass(extra_class);
                },
                afterOpen: function (evt) {
                    LaStudio.core.initAll($('.featherlight-content'));
                },
                afterClose: function (evt) {
                    if (typeof callback === 'function') {
                        callback();
                    }

                    $body.removeClass(extra_class);
                }
            });
        };

        if ($.isFunction($.fn.featherlight)) {
            show_popup();
        } else {
            LaStudio.global.loadDependencies([LaStudio.global.loadJsFile('featherlight')], show_popup);
        }
    };

    LaStudio.component.NewsletterPopup = function (el) {
        var $popup = $(el),
            disable_on_mobile = parseInt($popup.attr('data-show-mobile') || 0),
            p_delay = parseInt($popup.attr('data-delay') || 2000),
            backtime = parseInt($popup.attr('data-back-time') || 1),
            waitfortrigger = parseInt($popup.attr('data-waitfortrigger') || 0);

        if (waitfortrigger == 1) {
            $(document).on('click touchend', '.elm-trigger-open-newsletter', function (e) {
                e.preventDefault();
                LaStudio.core.OpenNewsletterPopup($popup);
            });
        }

        return {
            init: function () {
                if (waitfortrigger != 1) {
                    if ($(window).width() < 767) {
                        if (disable_on_mobile) {
                            return;
                        }
                    }

                    try {
                        if (Cookies.get('luxsa_dont_display_popup') == 'yes') {
                            return;
                        }
                    } catch (ex) {
                        LaStudio.global.log(ex);
                    }

                    $(window).load(function () {
                        setTimeout(function () {
                            LaStudio.core.OpenNewsletterPopup($popup, function () {
                                if ($('.cbo-dont-show-popup', $popup).length && $('.cbo-dont-show-popup', $popup).is(':checked')) {
                                    try {
                                        Cookies.set('luxsa_dont_display_popup', 'yes', {
                                            expires: backtime,
                                            path: '/'
                                        });
                                    } catch (ex) {}
                                }
                            });
                        }, p_delay);
                    });
                }
            }
        };
    };
})(jQuery); // Kickoff all event


(function ($) {
    'use strict';

    LaStudio.global.setBrowserInformation();
    $(function () {
        $(document).trigger('LaStudio:Document:BeforeRunScript');
        LaStudio.core.SitePreload();
        LaStudio.core.MegaMenu();
        $('.la-ajax-searchform').each(function () {
            LaStudio.core.InstanceSearch($(this));
        });
        LaStudio.core.initAll($(document));
        LaStudio.core.ElementClickEvent();
        LaStudio.core.Blog();
        /**
         * WooCommerce
         */

        LaStudio.core.CustomFunction();
        $(document).trigger('LaStudio:Document:AfterRunScript');
    });
    window.addEventListener('load', LaStudio.core.OnLoadEvent);
    LaStudio.core.DomLoadEvent();
    $(document).trigger('LaStudio:Document:AfterInitAllScript');
})(jQuery);