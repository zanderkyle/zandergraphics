/**
 * Main JavaScript file
 *
 * @package         Tabs
 * @version         5.1.4
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

(function($) {
	$(document).ready(function() {
		if (typeof( window['nn_tabs_use_hash'] ) != "undefined") {
			setTimeout(function() {
				nnTabs.init();
			}, nn_tabs_init_timeout);
		}
	});

	nnTabs = {
		timers: [],

		init: function() {
			var self = this;

			try {
				this.hash_id = decodeURIComponent(window.location.hash.replace('#', ''));
			} catch (err) {
				this.hash_id = '';
			}

			this.current_url = window.location.href;
			if (this.current_url.indexOf('#') !== -1) {
				this.current_url = this.current_url.substr(0, this.current_url.indexOf('#'));
			}

			// Remove the transition durations off to make initial setting of active tabs as fast as possible
			$('.nn_tabs').removeClass('has_effects');


			this.showByURL();

			this.showByHash();

			setTimeout((function() {
				self.initActiveClasses();


				self.initClickMode();


				if (nn_tabs_use_hash) {
					self.initHashHandling();
				}

				self.initHashLinkList();

				if (nn_tabs_reload_iframes) {
					self.initIframeReloading();
				}


				// Add the transition durations
				$('.nn_tabs').addClass('has_effects');
			}), 1000);

		},

		show: function(id, scroll, openparents, slideshow) {
			if (openparents) {
				this.openParents(id, scroll);
				return;
			}

			var self = this;
			var $el = this.getElement(id);

			if (!$el.length) {
				return;
			}


			$el.tab('show');

			$el.closest('ul.nav-tabs').find('.nn_tabs-toggle').attr('aria-selected', false);
			$el.attr('aria-selected', true);

			$el.closest('div.nn_tabs').find('.tab-content').first().children().attr('aria-hidden', true);
			$('div#' + id).attr('aria-hidden', false);

			this.updateActiveClassesOnTabLinks($el);

			if (!slideshow) {
				$el.focus();
			}
		},


		getElement: function(id) {
			return this.getTabElement(id);
		},

		getTabElement: function(id) {
			return $('a.nn_tabs-toggle[data-id="' + id + '"]');
		},

		getSliderElement: function(id) {
			return $('#' + id + '.nn_sliders-body');
		},


		showByURL: function() {
			var id = this.getUrlVar();

			if (id == '') {
				return;
			}

			this.showByID(id);
		},

		showByHash: function() {
			if (this.hash_id == '') {
				return;
			}

			var id = this.hash_id;

			if (id == '' || id.indexOf("&") != -1 || id.indexOf("=") != -1) {
				return;
			}

			// hash is a text anchor
			if ($('a.nn_tabs-toggle[data-id="' + id + '"]').length == 0) {
				this.showByHashAnchor(id);

				return;
			}

			// hash is a tab
			if (!nn_tabs_use_hash) {
				return;
			}

			if (!nn_tabs_urlscroll) {
				// Prevent scrolling to anchor
				$('html,body').animate({scrollTop: 0});
			}

			this.showByID(id);
		},

		showByHashAnchor: function(id) {
			if (id == '') {
				return;
			}

			var $anchor = $('a#anchor-' + id);

			if ($anchor.length == 0) {
				$anchor = $('a#' + id);
			}

			if ($anchor.length == 0) {
				return;
			}

			// Check if anchor has a parent tab
			if ($anchor.closest('.nn_tabs').length == 0) {
				return;
			}

			var $tab = $anchor.closest('.tab-pane').first();

			// Check if tab has sliders. If so, let Sliders handle it.
			if ($tab.find('.nn_sliders').length > 0) {
				return;
			}

			this.openParents($tab.attr('id'), 0);

			setTimeout(function() {
				$('html,body').animate({scrollTop: $anchor.offset().top});
			}, 250);
		},

		showByID: function(id) {
			var $el = $('a.nn_tabs-toggle[data-id="' + id + '"]');

			if ($el.length == 0) {
				return;
			}

			this.openParents(id, nn_tabs_urlscroll);
		},

		openParents: function(id, scroll) {
			var $el = this.getElement(id);

			if (!$el.length) {
				return;
			}

			var parents = new Array;

			var parent = this.getElementArray($el);
			while (parent) {
				parents[parents.length] = parent;
				parent = this.getParent(parent.el);
			}

			if (!parents.length) {
				return false;
			}

			this.stepThroughParents(parents, null, scroll);
		},

		stepThroughParents: function(parents, parent, scroll) {
			var self = this;

			if (!parents.length && parent) {

				parent.el.focus();
				return;
			}

			parent = parents.pop();

			if (parent.el.hasClass('in') || parent.el.parent().hasClass('active')) {
				self.stepThroughParents(parents, parent, scroll);
				return;
			}

			switch (parent.type) {
				case 'tab':
					if (typeof( window['nnTabs'] ) == "undefined") {
						self.stepThroughParents(parents, parent, scroll);
						break;
					}

					parent.el.one('shown shown.bs.tab', function(e) {
						self.stepThroughParents(parents, parent, scroll);
					});

					nnTabs.show(parent.id);
					break;

				case 'slider':
					if (typeof( window['nnSliders'] ) == "undefined") {
						self.stepThroughParents(parents, parent, scroll);
						break;
					}

					parent.el.one('shown shown.bs.collapse', function(e) {
						self.stepThroughParents(parents, parent, scroll);
					});

					nnSliders.show(parent.id);
					break;
			}
		},

		getParent: function($el) {
			if (!$el) {
				return false;
			}

			var $parent = $el.parent().closest('.nn_tabs-pane, .nn_sliders-body');

			if (!$parent.length) {
				return false;
			}

			var parent = this.getElementArray($parent);

			return parent;
		},

		getElementArray: function($el) {
			var id = $el.attr('data-toggle') ? $el.attr('data-id') : $el.attr('id');
			var type = ($el.hasClass('nn_tabs-pane') || $el.hasClass('nn_tabs-toggle')) ? 'tab' : 'slider'

			return {
				'type': type,
				'id'  : id,
				'el'  : type == 'tab' ? this.getTabElement(id) : this.getSliderElement(id)
			};
		},

		initActiveClasses: function() {
			$('li.nn_tabs-tab-sm').removeClass('active');
		},

		updateActiveClassesOnTabLinks: function(active_el) {
			active_el.parent().parent().find('.nn_tabs-toggle').each(function($i, el) {
				$('a.nn_tabs-link[data-id="' + $(el).attr('data-id') + '"]').each(function($i, el) {
					var $link = $(el);

					if ($link.attr('data-toggle') || $link.hasClass('nn_tabs-toggle-sm') || $link.hasClass('nn_sliders-toggle-sm')) {
						return;
					}

					if ($link.attr('data-id') !== active_el.attr('data-id')) {
						$link.removeClass('active');
						return;
					}

					$link.addClass('active');
				});
			});
		},

		initHashLinkList: function() {
			var self = this;

			$('a[href^="#"],a[href^="' + this.current_url + '#"]').each(function($i, el) {
				self.initHashLink(el);
			});
		},

		initHashLink: function(el) {
			var self = this;
			var $link = $(el);

			// link is a tab or slider or list link, so ignore
			if ($link.attr('data-toggle') || $link.hasClass('nn_aliders-link') || $link.hasClass('nn_tabs-toggle-sm') || $link.hasClass('nn_sliders-toggle-sm')) {
				return;
			}

			var id = $link.attr('href').substr($link.attr('href').indexOf('#') + 1);

			// No id found
			if (id == '') {
				return;
			}

			var $anchor = $('a#anchor-' + id);

			// No accompanying link found
			if ($anchor.length == 0) {
				return;
			}

			// Check if anchor has a parent tab
			if ($anchor.closest('.nn_tabs').length == 0) {
				return;
			}

			var $tab = $anchor.closest('.tab-pane').first();
			var tab_id = $tab.attr('id');

			// Check if link is inside the same tab
			if ($link.closest('.nn_tabs').length > 0) {
				if ($link.closest('.tab-pane').first().attr('id') == tab_id) {
					return;
				}
			}

			$link.click(function(e) {
				// Open parent tab and parents
				self.openParents(tab_id);
				e.stopPropagation();
			});
		},

		initHashHandling: function(el) {
			if (window.history.replaceState) {
				$('a.nn_tabs-toggle').on('shown shown.bs.tab', function(e) {
					if ($(this).closest('div.nn_tabs').hasClass('slideshow')) {
						return;
					}

					var id = $(this).attr('data-id');
					history.replaceState({}, '', '#' + id);
					e.stopPropagation();
				});
			}
		},

		initClickMode: function() {
			var self = this;

			$('body').on('click.tab.data-api', 'a.nn_tabs-toggle', function(e) {
				$el = $(this);

				e.preventDefault();

				nnTabs.show($el.attr('data-id'), $el.hasClass('nn_tabs-doscroll'));


				e.stopPropagation();
			});
		},


		initIframeReloading: function() {
			var self = this;

			$('.tab-pane.active iframe').each(function() {
				$(this).attr('reloaded', true);
			});

			$('a.nn_tabs-toggle').on('show show.bs.tab', function(e) {
				// Re-inintialize Google Maps on tabs show
				if (typeof initialize == 'function') {
					initialize();
				}

				var $el = $('#' + $(this).attr('data-id'));

				$el.find('iframe').each(function() {
					if (this.src && !$(this).attr('reloaded')) {
						this.src += '';
						$(this).attr('reloaded', true);
					}
				});
			});

			$(window).resize(function() {
				if (typeof initialize == 'function') {
					initialize();
				}

				$('.tab-pane iframe').each(function() {
					$(this).attr('reloaded', false);
				});

				$('.tab-pane.active iframe').each(function() {
					if (this.src) {
						this.src += '';
						$(this).attr('reloaded', true);
					}
				});
			});
		},


		getUrlVar: function() {
			var search = 'tab';
			var query = window.location.search.substring(1);

			if (query.indexOf(search + '=') == -1) {
				return '';
			}

			var vars = query.split('&');
			for (var i = 0; i < vars.length; i++) {
				var keyval = vars[i].split('=');

				if (keyval[0] != search) {
					continue;
				}

				return keyval[1];
			}

			return '';
		}
	};
})
(jQuery);
