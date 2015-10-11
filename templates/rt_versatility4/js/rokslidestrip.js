/**
* @version   $Id: rokslidestrip.js 26096 2015-01-27 14:14:12Z james $
* @author		RocketTheme, LLC http://www.rockettheme.com
* @copyright	Copyright (C) 2007 - 2011 RocketTheme, LLC
* @license		http://www.rockettheme.com/legal/license.php RocketTheme Proprietary Use License
 */
var RokSlide = new Class({
    Implements: [Options],
    version: '1.9 (mt 1.2)',
    options: {
        active: '',
        fx: {
            link: 'cancel',
            duration: 350
        },
        scrollFX: {
            link: 'cancel',
            transition: Fx.Transitions.Sine.easeInOut,
            wheelStops: false
        },
        dimensions: {
            width: 722,
            height: 200
        },
        dynamic: false,
        tabsPosition: 'top',
        arrows: true
    },
    initialize: function(a, b) {
        this.setOptions(b);
        this.content = document.id(a);
        this.sections = this.content.getElements('.tab-pane');
        if (!this.sections.length) return;
        this.filmstrip = new Element('div').inject(this.content, 'after');
        this.buildToolbar();
        this.buildFrame();
        if (Browser.ie) this.fixIE();
        this.scroller = document.id('scroller');
        this.startposition = document.id(this.sections[0].id.replace('-tab', '-pane')).getPosition().x;
        this.scroller.scrollFX = new Fx.Scroll(this.scroller, this.options.scrollFX);
        if (this.options.active) this.scrollSection(this.options.active.test(/-tab|-pane/) ? this.options.active : this.options.active + '-tab');
        else this.scrollSection(this.sectionptr[0]);
        if (this.options.tabsPosition == 'bottom') {
            this.filmstrip.getElement('hr').inject(this.filmstrip);
            var c = this.filmstrip.getElement('ul');
            c.inject(this.filmstrip);
            var d = c.getSize().y,
                frame = document.id('frame');
            frame.setStyle('height', frame.getStyle('height').toInt() - d)
        }
    },
    buildToolbar: function() {
        var b = [];
        var c = this;
        this.sectionptr = [];
        var d, title;
        if (!!this.options.dynamic) this.width = document.id(this.options.dynamic).getCoordinates().width;
        else this.width = this.options.dimensions.width;
        var e = this.width;
        this.sections.each(function(a) {
            a.setStyles({
                width: e - ((!!this.options.dynamic) ? 0 : (!this.options.arrows) ? 0 : 142),
                height: this.options.dimensions.height
            });
            this.sectionptr.push(a.id.replace('-pane', '-tab'));
            d = a.getElement('.tab-title');
            title = d.innerHTML;
            d.empty().dispose();
            b.push(new Element('li', {
                id: a.id.replace('-pane', '-tab'),
                events: {
                    'click': function() {
                        this.addClass('active');
                        c.scrollSection(this)
                    },
                    'mouseover': function() {
                        this.addClass('hover');
                        this.addClass('active')
                    },
                    'mouseout': function() {
                        this.removeClass('hover');
                        this.removeClass('active')
                    }
                }
            }).set('html', title))
        }, this);
        var f = b.length - 1;
        b[0].addClass('first');
        b[f].addClass('last');
        this.filmstrip.adopt(new Element('ul', {
            id: 'rokslide-toolbar',
            styles: {
                width: e
            }
        }).adopt(b), new Element('hr'))
    },
    buildFrame: function() {
        var a = this.width;
        var b = this,
            events = {
                'click': function() {
                    b.scrollArrow(this)
                },
                'mouseover': function() {
                    this.addClass('hover')
                },
                'mouseout': function() {
                    this.removeClass('hover')
                }
            };
        var c = {
            'left': (this.options.arrows) ? new Element('div', {
                'class': 'button',
                'id': 'left',
                'events': events
            }) : '',
            'right': (this.options.arrows) ? new Element('div', {
                'class': 'button',
                'id': 'right',
                'events': events
            }) : ''
        };
        this.filmstrip.adopt(new Element('div', {
            id: 'frame',
            styles: {
                width: a,
                height: this.options.dimensions.height
            }
        }).adopt(c.left, new Element('div', {
            id: 'scroller',
            styles: {
                width: a - ((!!this.options.dynamic) ? 0 : (!this.options.arrows) ? 0 : 102),
                height: this.options.dimensions.height
            }
        }).adopt(this.content.setStyle('width', this.sections.length * 1600)), c.right))
    },
    fixIE: function() {
        this.filmstrip.getElement('hr').setStyle('display', 'none')
    },
    scrollSection: function(a) {
        a = document.id(document.id(a || this.sections[0]).id.replace('-pane', '-tab'));
        this.startposition = document.id(this.sections[0].id.replace('-tab', '-pane')).getPosition().x;
        var b = a.getParent().getElement('.current');
        if (b) b.removeClass('current');
        a.addClass('current');
        var c = $(a.id.replace('-tab', '-pane')).getPosition().x - this.startposition;
        this.scroller.scrollFX.start(c, false)
    },
    scrollArrow: function(a) {
        var b = Math.pow(-1, ['left', 'right'].indexOf(a.id) + 1);
        var c = this.sectionptr.indexOf(this.filmstrip.getElement('.current').id);
        var d = c + b;
        this.scrollSection(this.sectionptr[d < 0 ? this.sectionptr.length - 1 : d % this.sectionptr.length])
    }
});