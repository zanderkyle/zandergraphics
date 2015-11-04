var ats_instant_searching = false;
var ats_instant_results = {};
var ats_instant_oldtimestamp = 0;
var ats_instant_oldsearch = '';
var ats_instant_statistics = 0;

akeeba.jQuery(document).ready(function (jQuery)
{
	var element = akeeba.jQuery('#tickettitle');

	element.keyup(function (e)
	{
		// If we're already searching, quit
		if (ats_instant_searching)
		{
			return;
		}

		// Search on space or after at least two seconds have elapsed
		var doSearch = false;

		if (e.which == 32)
		{
			doSearch = true;
		}
		else if (e.timeStamp - ats_instant_oldtimestamp > 2000)
		{
			doSearch = true;
		}

		// If we don't have to search, bail out
		if (!doSearch)
		{
			return;
		}

		// OK, let's make sure we have enough things to search for
		var searchQuery = element.val();

		if (searchQuery == '')
		{
			return;
		}

		if (searchQuery.length < 6)
		{
			return;
		}

		if (ats_instant_oldsearch == searchQuery)
		{
			return;
		}

		ats_instant_oldsearch = searchQuery;

		// Update the timestamp and start searching!
		ats_instant_oldtimestamp = e.timeStamp;

		akeeba.jQuery('#ats-instantreply-grid').css('display', 'none');
		akeeba.jQuery('#ats-instantreply-noresults').css('display', 'none');
		akeeba.jQuery('#ats-instantreply-wait').css('display', 'block');

		var structure = {
			type:    "POST",
			url:     ats_instantreply_proxy,
			cache:   false,
			data:    {
				search: searchQuery
			},
			timeout: 15000,
			success: function (msg)
			{
				ats_instant_searching = false;
				var showNoResults = false;

				akeeba.jQuery('#ats-instantreply-wait').css('display', 'none');
				akeeba.jQuery('#ats-instantreply-grid').html('');

				try
				{
					if (msg.length < 1)
					{
						showNoResults = true;
					}
					else
					{
						akeeba.jQuery('#ats-instantreply-wrapper').show('slow');
						akeeba.jQuery('#ats-instantreply-noresults').css('display', 'none');
						akeeba.jQuery('#ats-instantreply-grid').css('display', 'block');
						var myTable = akeeba.jQuery(document.createElement('table'))
							.addClass('table table-striped')
							.appendTo(akeeba.jQuery('#ats-instantreply-grid'));
						akeeba.jQuery.each(msg, function (i, row)
						{
							var myTr = akeeba.jQuery(document.createElement('tr'));
							var myDiv = akeeba.jQuery(document.createElement('td'))
								.appendTo(myTr);
							akeeba.jQuery(document.createElement('h4'))
								.addClass('akfaq-title')
								.html(row.title)
								.appendTo(myDiv);
							akeeba.jQuery(document.createElement('span'))
								.addClass('akfaq-snip')
								.html(row.preview + '&nbsp;')
								.appendTo(myDiv);
							akeeba.jQuery(document.createElement('a'))
								.attr('href', 'javascript:')
								.addClass('btn btn-info btn-mini')
								.data('source', row.source)
								.html('View')
								.click(function ()
								{
									akeeba.jQuery('#ats-instantreply-lightbox-iframe')
										.attr('src', row.url + '#docimport');
									akeeba.jQuery('#ats-instantreply-lightbox-wrapper').show('medium');
								})
								.appendTo(myDiv);
							akeeba.jQuery(myTr).appendTo(myTable);
						});
					}
				}
				catch (err)
				{
					showNoResults = true;
				}

				if (showNoResults)
				{
					akeeba.jQuery('#ats-instantreply-noresults').css('display', 'block');
					akeeba.jQuery('#ats-instantreply-wrapper').hide('fast');
				}
			},
			error:   function (Request, textStatus, errorThrown)
			{
				akeeba.jQuery('#ats-instantreply-noresults').css('display', 'block');
				akeeba.jQuery('#ats-instantreply-wait').css('display', 'none');
				akeeba.jQuery('#ats-instantreply-grid').html('');
				akeeba.jQuery('#ats-instantreply-wrapper').hide('fast');
			}
		};

		ats_instant_searching = true;
		akeeba.jQuery.ajax(structure);

		// Instant search statistics
		jQuery.ajax(ATS_ROOT_URL + 'index.php?option=com_ats&view=attempts&format=json', {
			dataType: 'json',
			cache:    false,
			data:     {
				task:            'save',
				ats_attempt_id:  ats_instant_statistics,
				title:           searchQuery,
				ats_category_id: jQuery('#ticket_catid').val(),
				modified_on:     ''
			},
			success:  function (response)
			{
				ats_instant_statistics = response.ats_attempt_id;
				jQuery('input[name="ats_attempt_id"]').val(ats_instant_statistics);
			}
		});
	});

	akeeba.jQuery('#ats-instantreply-lightbox-close').click(function ()
	{
		akeeba.jQuery('#ats-instantreply-lightbox-wrapper').hide('medium');
	});

    jQuery('#ats-instantreply-grid').on('click', 'a', function ()
    {
        var click_type = jQuery(this).data('source');

        jQuery.ajax(ATS_ROOT_URL + 'index.php?option=com_ats&view=attempts&format=json', {
            dataType: 'json',
            cache:    false,
            data:     {
                task:           'save',
                ats_attempt_id: ats_instant_statistics,
                update_clicks:  click_type
            }
        });
    })
});