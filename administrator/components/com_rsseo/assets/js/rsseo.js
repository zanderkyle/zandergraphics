function isset () {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: FremyCompany
  // +   improved by: Onno Marsman
  // +   improved by: Rafał Kukawski
  // *     example 1: isset( undefined, true);
  // *     returns 1: false
  // *     example 2: isset( 'Kevin van Zonneveld' );
  // *     returns 2: true
  var a = arguments,
    l = a.length,
    i = 0,
    undef;

  if (l === 0) {
    throw new Error('Empty isset');
  }

  while (i !== l) {
    if (a[i] === undef || a[i] === null) {
      return false;
    }
    i++;
  }
  return true;
}

function number_format (number, decimals, dec_point, thousands_sep) {
  // http://kevin.vanzonneveld.net
  // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +     bugfix by: Michael White (http://getsprink.com)
  // +     bugfix by: Benjamin Lupton
  // +     bugfix by: Allan Jensen (http://www.winternet.no)
  // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // +     bugfix by: Howard Yeend
  // +    revised by: Luke Smith (http://lucassmith.name)
  // +     bugfix by: Diogo Resende
  // +     bugfix by: Rival
  // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
  // +   improved by: davook
  // +   improved by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Jay Klehr
  // +   improved by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Amir Habibi (http://www.residence-mixte.com/)
  // +     bugfix by: Brett Zamir (http://brett-zamir.me)
  // +   improved by: Theriault
  // +      input by: Amirouche
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // *     example 1: number_format(1234.56);
  // *     returns 1: '1,235'
  // *     example 2: number_format(1234.56, 2, ',', ' ');
  // *     returns 2: '1 234,56'
  // *     example 3: number_format(1234.5678, 2, '.', '');
  // *     returns 3: '1234.57'
  // *     example 4: number_format(67, 2, ',', '.');
  // *     returns 4: '67,00'
  // *     example 5: number_format(1000);
  // *     returns 5: '1,000'
  // *     example 6: number_format(67.311, 2);
  // *     returns 6: '67.31'
  // *     example 7: number_format(1000.55, 1);
  // *     returns 7: '1,000.6'
  // *     example 8: number_format(67000, 5, ',', '.');
  // *     returns 8: '67.000,00000'
  // *     example 9: number_format(0.9, 0);
  // *     returns 9: '1'
  // *    example 10: number_format('1.20', 2);
  // *    returns 10: '1.20'
  // *    example 11: number_format('1.20', 4);
  // *    returns 11: '1.2000'
  // *    example 12: number_format('1.2000', 3);
  // *    returns 12: '1.200'
  // *    example 13: number_format('1 000,50', 2, '.', ' ');
  // *    returns 13: '100 050.00'
  // *    returns 9999999
  // Strip all characters but numerical ones.
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

function rsseo_history(id) {
	$('filter_parent').value = id;
	Joomla.submitform();
}

function rsseo_competitor(id) {
	$('refresh'+id).style.display = 'none';
	$('loading'+id).style.display = '';
	var req = new Request.JSON({
		method: 'post',
		url: 'index.php?option=com_rsseo&task=competitors.refresh',
		data: 'id='+id,
		onSuccess: function(response) {
			if (isset($('pagerank'+id))) {
				$('pagerank'+id).innerHTML = response.pagerank;
				$('pagerank'+id).className = 'badge badge-'+response.pagerankbadge;
			}
			if (isset($('googlep'+id))) {
				$('googlep'+id).innerHTML = response.googlep;
				$('googlep'+id).className = 'badge badge-'+response.googlepbadge;
			}
			if (isset($('googleb'+id))) {
				$('googleb'+id).innerHTML = response.googleb;
				$('googleb'+id).className = 'badge badge-'+response.googlebbadge;
			}
			if (isset($('bingp'+id))) {
				$('bingp'+id).innerHTML = response.bingp;
				$('bingp'+id).className = 'badge badge-'+response.bingpbadge;
			}
			if (isset($('bingb'+id))) {
				$('bingb'+id).innerHTML = response.bingb;
				$('bingb'+id).className = 'badge badge-'+response.bingbbadge;
			}
			if (isset($('alexa'+id))) {
				$('alexa'+id).innerHTML = response.alexa;
				$('alexa'+id).className = 'badge badge-'+response.alexabadge;
			}
			if (isset($('technorati'+id))) {
				$('technorati'+id).innerHTML = response.technorati;
				$('technorati'+id).className = 'badge badge-'+response.technoratibadge;
			}
			if (isset($('dmoz'+id))) {
				$('dmoz'+id).innerHTML = response.dmoz;
				$('dmoz'+id).className = 'badge badge-'+response.dmozbadge;
			}
			
			$('date'+id).innerHTML = response.date;
			
			$('loading'+id).style.display = 'none';
			$('refresh'+id).style.display = '';
		}
	});
	req.send();
}

function rss_pagecheck(id) {
	$('loader').style.display = '';
	$('pageloadtr').style.display = 'none';
	$('pagesizetr').style.display = 'none';
	var req = new Request({
		method: 'post',
		url: 'index.php?option=com_rsseo&task=pagecheck',
		data: 'id='+id,
		onSuccess: function(responseText, responseXML) {
			if (responseText != 0) {
				$('loader').style.display = 'none';
				$('pageloadtr').style.display = '';
				$('pagesizetr').style.display = '';
				
				var response = responseText.split('RSDELIMITER');
				$('pageload').innerHTML = response[1];
				$('pagesize').innerHTML = response[0];
			} else {
				$('loader').style.display = 'none';
				$('pageloadtr').style.display = 'none';
				$('pagesizetr').style.display = 'none';
			}
		}
	});
	req.send();
}

function rsseo_keyword(id) {	
	$('refresh'+id).style.display = 'none';
	$('loading'+id).style.display = '';
	var req = new Request.JSON({
		method: 'post',
		url: 'index.php?option=com_rsseo&task=keywords.refresh',
		data: 'id='+id,
		onSuccess: function(response) {
			$('position'+id).innerHTML = response.position;
			$('position'+id).className = 'badge badge-'+response.badge;
			$('date'+id).innerHTML = response.date;
			
			$('loading'+id).style.display = 'none';
			$('refresh'+id).style.display = '';
		}
	});
	req.send();
}

function rsseo_create(file) {
	$(file+'loading').style.display = '';
	var req = new Request({
		method: 'post',
		url: 'index.php?option=com_rsseo&task=sitemap.create',
		data: 'file='+file,
		onSuccess: function(responseText, responseXML) {
			if (responseText == 1) {
				$(file).style.display = 'none';
				$('btn' + file).style.display = '';
				$('sitemapbtn').disabled = false;
			}
			
			$(file+'loading').style.display = 'none';
		}
	});
	req.send();
}

function rsseo_sitemap(isnew) {
	var protocol = $('jform_protocol').value;
	var modified = $('jform_modified').value;
	var auto	 = $('jform_auto').value;
	
	$('jform_protocol').disabled = true;
	$('jform_modified').disabled = true;
	$('jform_auto').disabled = true;
	$('sitemapbtn').disabled = true;
	
	if (typeof jQuery != 'undefined') {
		jQuery("#jform_protocol").trigger("liszt:updated");
		jQuery("#jform_auto").trigger("liszt:updated");
	}
	
	var req = new Request({
		method: 'post',
		url: 'index.php?option=com_rsseo&task=sitemap.generate',
		data: 'new='+isnew+'&protocol='+protocol+'&modified='+modified+'&auto='+auto,
		onSuccess: function(responseText, responseXML) {
			if (responseText != 'finish') {
				//set the width and the procentage of the status bar
				var percent = responseText + '%';
				$('com-rsseo-bar').style.width = percent;
				$('com-rsseo-bar').innerHTML = percent;
				rsseo_sitemap(0);
			} else {
				$('com-rsseo-bar').style.width = '100%';
				$('com-rsseo-bar').innerHTML = '100%';
				
				$('jform_protocol').disabled = false;
				$('jform_modified').disabled = false;
				$('jform_auto').disabled = false;
				$('sitemapbtn').disabled = false;
				
				if (typeof jQuery != 'undefined') {
					jQuery("#jform_protocol").trigger("liszt:updated");
					jQuery("#jform_auto").trigger("liszt:updated");
				}
			}
		}
	});
	req.send();
}

function rsseo_analytics(view) {
	$('img'+view).style.display = '';
	var req = new Request({
		method: 'post',
		url: 'index.php?option=com_rsseo&view=analytics&layout=' + view,
		data: 'ajax=1',
		onSuccess: function(responseText, responseXML) {
			$('img'+view).style.display = 'none';
			$('ga'+view).innerHTML = responseText;
			
			if (view == 'general') {
				$$('.hasTip').each(function(el) {
					var title = el.get('title');
					if (title) {
						var parts = title.split('::', 2);
						el.store('tip:title', parts[0]);
						el.store('tip:text', parts[1]);
					}
				});
				new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
			}
			
		}
	});
	req.send();
}

function rsseo_page(id, original) {
	$('refresh'+id).style.display = 'none';
	$('loading'+id).style.display = '';
	
	var req = new Request.JSON({
		method: 'post',
		url: 'index.php?option=com_rsseo&task=crawl',
		data: 'init=0&id=' + id+'&original='+original,
		onSuccess: function(response) {
			$('title'+id).innerHTML = response.title;
			$('date'+id).innerHTML = response.date;
			$('page'+id).className = response.color;
			$('page'+id).setStyle('width',response.grade+'%');
			$('page'+id).getElement('span').innerHTML = response.grade+'%';
			$('refresh'+id).style.display = '';
			$('loading'+id).style.display = 'none';
			if (isset(original) && original == 1) {
				$('img'+id).className = 'icon-unpublish';
			}
			
		}
	});
	req.send();
}

function rsseo_pause() {
	$('pause').value = 1;
}

function rsseo_continue() {
	$('pause').value = 0;
	rsseo_crawl(0,0);
}

function rsseo_crawl(init, id) {
	if (init && isset($('rssmessage'))) {
		$('rssmessage').style.display = '';
	}

	var req = new Request.JSON({
		method: 'post',
		url: 'index.php?option=com_rsseo&task=crawl',
		data: 'init=' + init + '&id=' + id + '&auto=' + $('auto').value,
		onSuccess: function(response) {
			if (response.finished == 0) {
				if ($('pause').value == 0) {
					$('url').innerHTML = response.url;
					$('level').innerHTML = response.level;
					$('scaned').innerHTML = response.crawled;
					$('remaining').innerHTML = response.remaining;
					$('total').innerHTML = response.total;
					rsseo_crawl(0,0);
				}
			} else {
				$('url').innerHTML = response.finishtext;
				$('level').innerHTML = '';
				$('scaned').innerHTML = '';
				$('remaining').innerHTML = '';
				$('total').innerHTML = '';
				$('pause').value = 0;
				
				if (isset($('rssmessage'))) {
					$('rssmessage').style.display = 'none';
				}
			}
		}
	});
	req.send();
}

function checkKeycode(e) {
	var keycode;
	
	if (window.event) {
		keycode = window.event.keyCode;
		ctrlKey = window.event.ctrlKey;
	} else if (e) {
		keycode = e.which;
		ctrlKey = e.ctrlKey;
	}
	
	if(ctrlKey && keycode == 38) {
		$('jform_canonical').focus();
		$('jform_canonical').value = '';
	}
	
	if(keycode == 27)
		$('rss_results').style.display = 'none';
	
	if(keycode==40)
		nextItem('down' , rs_results);
	
	if(keycode==38)
		nextItem('up' , rs_results);
	
	if(keycode==13)
		gotoItem(rs_results);
}

function gotoItem(items) {
	for(i=0;i<items;i++)
		if($('result_' + i).className == 'rsActive')
			document.location = $('result_' + i).href;
}

function nextItem(direction, items) {	
	if (items > 0) {
		current_active = -1;
		//get active item
		for (i=0; i < items; i++) {
			if($('result_' + i))
				if($('result_' + i).className == 'rsActive') 
					current_active = i;
		}
		
		if (direction == 'up') current_active -= 1;  else current_active += 1;
		if (current_active == -1) current_active = items-1;
		if (current_active == items) current_active = 0;	
		
		for(i=0;i<items;i++) {
			if ($('result_' + i))
				$('result_' + i).className = 'rsInactive';
				
			if (i == current_active)
				if ($('result_' + i))
					$('result_' + i).className = 'rsActive';
		}
	}
}

function resolveMouseOver(items) {
	if (items > 0) {
		for (i=0; i < items; i++) {
			if ($('result_' + i)) {
				$('result_' + i).onmouseover = function() {
					for (i=0; i < items; i++) {
						$('result_' + i).className = 'rsInactive';
					}
					this.className = 'rsActive';
				}
				document.getElementById('result_' + i).onmouseout = function() {
					this.className = 'rsInactive';
				}
			}
		}
	}
}

function generateRSResults(e) {
	var keycode;
	
	if (window.event) 
		keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	
	if($('jform_canonical').value.length > 1 && keycode != 40 && keycode != 38 && keycode != 27 ) {
		var req = new Request({
			method: 'post',
			url: 'index.php?option=com_rsseo&task=search',
			data: 'search=' + $('jform_canonical').value,
			onSuccess: function(responseText, responseXML) {
				$('rss_results').setStyle('width',$('jform_canonical').getWidth());
				$('rsResultsUl').innerHTML = responseText;
				$('rss_results').style.display = 'block';
				rs_results = responseText.split("\n").length - 1;
				nextItem('down',1);
				resolveMouseOver(rs_results);
			}
		});
		req.send();
	}
}

function addCanonical(url) {
	$('jform_canonical').value = url;
	$('rss_results').style.display = 'none';
}

function closeCanonicalSearch() {
	$('rss_results').style.display = 'none';
}