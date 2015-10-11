/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: rokmoomenu.js 26096 2015-01-27 14:14:12Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
var Rokmoomenu = new Class({
    version: '2.3 - mootools 1.2.3',
    Implements: [Options],
    options: {
        verhor: false,
        bgiframe: true,
        hoverClass: 'sfHover',
        delay: 500,
        animate: {
            props: ['opacity', 'height'],
            opts: Class.empty
        },
        bg: {
            enabled: false,
            margins: false,
            paddings: false,
            overEffect: {
                duration: 700,
                transition: 'quad:out'
            },
            outEffect: {
                duration: 500,
                transition: 'sine:in'
            }
        },
        submenus: {
            enabled: false,
            opacity: 0.35,
            overEffect: {
                duration: 700,
                transition: 'quad:out'
            },
            outEffect: {
                duration: 500,
                transition: 'sine:in'
            },
            offsets: {
                'left': 0,
                'right': 0,
                'top': 0,
                'bottom': 0
            }
        }
    },
    initialize: function(el, options) {
        this.setOptions(options);
        if (Browser.ie6) this.options.delay = 50;
        this.element = $$(el)[0];
        if (this.options.bg.enabled) this.bgAnimation();
        if (this.options.submenus.enabled) this.subsAnimation();
        this.element.getElements('li').each(function(el) {
            el.addEvents({
                'mouseover': this.over.bind(this, el),
                'mouseout': this.out.bind(this, el)
            })
        }, this)
    },
    over: function(el) {
        clearTimeout(el.sfTimer);
        if (!el.hasClass(this.options.hoverClass)) {
            if (Browser.ie6) {
                var classes = el.getProperty('class').split(" ");
                var option = this.options.hoverClass;
                classes = classes.filter(function(y) {
                    return !y.test("-" + option)
                });
                classes.each(function(cls) {
                    if (el.hasClass(cls)) el.addClass(cls + "-" + option)
                }, this);
                var hackish = classes.join("-") + "-" + option;
                if (!el.hasClass(hackish)) el.addClass(hackish)
            }
            el.addClass(this.options.hoverClass);
            var ul = el.getElement('ul');
            if (ul) {
                if (this.options.bgiframe) ul.bgiframe({
                    opacity: false
                });
                ul.animate(this.options.animate, this)
            }
            el.getSiblings().each(function(ele) {
                ele.removeClass(this.options.hoverClass)
            }, this)
        }
    },
    out: function(el) {
        var option = this.options.hoverClass;
        el.sfTimer = (function() {
            if (Browser.ie6) {
                var classes = el.getProperty('class').split(" ");
                classes = classes.filter(function(y) {
                    return y.test("-" + option)
                });
                classes.each(function(cls) {
                    if (el.hasClass(cls)) el.removeClass(cls)
                }, this);
                var hackish = classes.join("-") + "-" + option;
                if (!el.hasClass(hackish)) el.removeClass(hackish)
            }
            el.removeClass(option);
            var iframe = el.getElement('iframe');
            if (iframe) iframe.remove()
        }).delay(this.options.delay, this)
    },
    bgAnimation: function() {
        this.element.getChildren().each(function(li, i) {
            li.addClass('top-menu-' + (i + 1));
            if (!li.hasClass('active')) {
                li.getFirst().setStyle('position', 'relative');
                var size = li.getCoordinates();
                var margins = {
                    'left': li.getStyle('margin-left').toInt(),
                    'right': li.getStyle('margin-right').toInt(),
                    'top': li.getStyle('margin-top').toInt(),
                    'bottom': li.getStyle('margin-bottom').toInt()
                };
                var paddings = {
                    'left': li.getStyle('padding-left').toInt(),
                    'right': li.getStyle('padding-right').toInt(),
                    'top': li.getStyle('padding-top').toInt(),
                    'bottom': li.getStyle('padding-bottom').toInt()
                };
                var div = new Element('div', {
                    'class': 'animated-bg png',
                    'styles': {
                        'position': 'absolute',
                        'left': 0,
                        'top': 0,
                        'opacity': 0,
                        'width': size.width + (this.options.bg.margins ? -margins.left - margins.right : 0) + (this.options.bg.paddings ? -paddings.left - paddings.right : 0),
                        'height': size.height + (this.options.bg.margins ? -margins.top - margins.bottom : 0) + (this.options.bg.paddings ? -paddings.top - paddings.bottom : 0)
                    }
                }).inject(li);
                var self = this;
                var fx = new Fx.Tween(div, 'opacity', {
                    duration: this.options.bg.duration,
                    transition: this.options.bg.transition,
                    link: 'cancel'
                }).set('opacity', 0);
                fx.options.link = 'cancel';
                li.addEvents({
                    'mouseenter': function() {
                        fx.options.duration = self.options.bg.overEffect.duration;
                        fx.options.transition = self.options.bg.overEffect.transition;
                        fx.start('opacity', 1)
                    },
                    'mouseleave': function() {
                        fx.options.duration = self.options.bg.outEffect.duration;
                        fx.options.transition = self.options.bg.outEffect.transition;
                        fx.start('opacity', 0)
                    }
                })
            }
        }, this)
    },
    subsAnimation: function() {
        var els = this.element.getChildren().getElements('li');
        var lis = [],
            self = this;
        els.each(function(el) {
            if (el.length) {
                el.each(function(li) {
                    lis.push(li);
                    var a = li.getElement('a') || li.getElement('span');
                    if (!a) return;
                    a.setStyle('position', 'relative');
                    var coords = a.getCoordinates();
                    var offsets = this.options.submenus.offsets;
                    if (Browser.ie6) {
                        var div = new Element('div', {
                            'class': 'submenu-animation-container'
                        }).inject(li).adopt(new Element('div', {
                            'class': 'submenu-animation-left'
                        })).adopt(new Element('div', {
                            'class': 'submenu-animation-right'
                        }))
                    } else {
                        var div = new Element('div', {
                            'class': 'submenu-animation-left'
                        }).inject(li).adopt(new Element('div', {
                            'class': 'submenu-animation-right'
                        }))
                    };
                    div.setStyles({
                        'width': coords.width - offsets.right || 0,
                        'height': coords.height - offsets.bottom || 0,
                        'position': 'absolute',
                        'top': offsets.top || 0,
                        'left': offsets.left || 0,
                        'visibility': 'hidden',
                        'opacity': 0
                    });
                    if (Browser.ie6 && coords.width && coords.height) div.setStyles({
                        'width': coords.width - offsets.right || 0,
                        'height': coords.height - offsets.bottom || 0
                    });
                    else {
                        div.setStyles({
                            'width': coords.width - offsets.right || 0,
                            'height': coords.height - offsets.bottom || 0
                        })
                    };
                    var fx = new Fx.Tween(div, 'opacity', {
                        duration: this.options.submenus.duration,
                        transition: this.options.submenus.transition,
                        'link': 'cancel'
                    }).set('opacity', 0);
                    fx.options.link = 'cancel';
                    li.addEvents({
                        'mouseenter': function() {
                            fx.options.duration = self.options.submenus.overEffect.duration;
                            fx.options.transition = self.options.submenus.overEffect.transition;
                            fx.start('opacity', self.options.submenus.opacity)
                        },
                        'mouseleave': function() {
                            fx.options.duration = self.options.submenus.outEffect.duration;
                            fx.options.transition = self.options.submenus.outEffect.transition;
                            fx.start('opacity', 0)
                        }
                    })
                }, this)
            }
        }, this)
    }
});
Element.implement({
    animate: function(obj, self) {
        if (self.options.verhor) {
            var parent = this.getParent().getParent().getParent();
            var tmp = [];
            if (!parent.hasClass('menutop')) {
                obj.props.each(function(el) {
                    if (el == 'height') return;
                    else tmp.push(el)
                });
                if (tmp.indexOf('width') == -1) tmp.push('width')
            } else {
                obj.props.each(function(el) {
                    if (el == 'width') return;
                    else tmp.push(el)
                });
                if (tmp.indexOf('height') == -1) tmp.push('height')
            }
        }
        if (!tmp) var tmp = obj.props;
        if (!this.Fx) {
            var parentWrap = this.getParent();
            this.Fx = new Fx.Morph(this, obj.opts);
            if (self.options.verhor && parentWrap.hasClass('drop-wrap')) this.Fx1 = new Fx.Morph(parentWrap, obj.opts);
            this.now = this.getStyles.apply(this, tmp);
            this.FxEmpty = {};
            for (var i in this.now) this.FxEmpty[i] = 0
        }
        if (tmp && (tmp.contains('height') || tmp.contains('width'))) {
            this.getParent().setStyle('overflow', 'hidden');
            this.setStyle('overflow', 'hidden');
            this.getParents('ul').each(function(el) {
                el.setStyle('overflow', 'visible');
                this.getParent().setStyle('overflow', 'visible')
            }, this)
        }
        if (self.options.verhor) {
            if (this.Fx1) {
                this.Fx1.set(this.FxEmpty)
            }
        }
        this.Fx.set(this.FxEmpty);
        if (self.options.verhor) {
            this.Fx.start.delay((self.options.animate.opts.duration / 10), this.Fx, this.now);
            if (this.Fx1) this.Fx1.start(this.now)
        } else {
            this.Fx.start(this.now)
        }
    },
    getParents: function(expr) {
        var matched = [];
        var cur = this.getParent();
        while (cur && cur !== document) {
            if (cur.get('tag').test(expr)) matched.push(cur);
            cur = cur.getParent()
        }
        return matched
    },
    getSiblings: function() {
        var children = this.getParent().getChildren();
        children.splice(children.indexOf(this), 1);
        return children
    }
});