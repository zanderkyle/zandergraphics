<?php
/**
 * package   AkeebaTicketSystem
 * copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * license   GNU General Public License version 3, or later
 */

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;

/** @var Akeeba\TicketSystem\Admin\View\ControlPanel\Html $this */

defined('_JEXEC') or die;

$container = $this->getContainer();
$template  = $container->template;

if(ComponentParams::getParam('userfeedback', 0))
{
    $template->addCSS('admin://components/com_ats/assets/datatables/css/datatables.css');
    $template->addJS('admin://components/com_ats/assets/datatables/js/datatables.min.js', false, false, $container->mediaVersion);
    $template->addJS('admin://components/com_ats/assets/datatables/js/paging.js', false, false, $container->mediaVersion);
}

$template->addCSS('media://com_ats/css/backend.css');
$template->addCSS('media://com_ats/css/jquery.jqplot.min.css');

$template->addJS('media://com_ats/js/excanvas.min.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/jquery.jqplot.min.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/jqplot.highlighter.min.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/jqplot.dateAxisRenderer.min.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/jqplot.categoryAxisRenderer.min.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/jqplot.canvasAxisTickRenderer.min.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/jqplot.canvasTextRenderer.min.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/jqplot.barRenderer.min.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/jqplot.hermite.js', false, false, $container->mediaVersion);

// Obsolete PHP version check
if (version_compare(PHP_VERSION, '5.4.0', 'lt')):
    ?>
    <div id="phpVersionCheck" class="alert alert-warning">
        <h3><?php echo JText::_('AKEEBA_COMMON_PHPVERSIONTOOOLD_WARNING_TITLE'); ?></h3>
        <p>
            <?php echo JText::sprintf(
                'AKEEBA_COMMON_PHPVERSIONTOOOLD_WARNING_BODY',
                PHP_VERSION,
                $this->akeebaCommonDatePHP,
                $this->akeebaCommonDateObsolescence,
                '5.5'
            );
            ?>
        </p>
    </div>
<?php endif; ?>

<?php if ($this->needsdlid): ?>
    <div class="alert alert-error">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <?php echo JText::sprintf('COM_ATS_CPANEL_MSG_NEEDSDLID','https://www.akeebabackup.com/instructions/1519-akeeba-ticket-system-download-id.html'); ?>
    </div>
<?php endif;?>

<div id="updateNotice"></div>

<div class="row-fluid">
    <div class="span6" id="cpanel">
        <h2><?php echo JText::_('COM_ATS_CPANEL_TICKETS')?></h2>
        <div id="akticketschart">
            <img id="akthrobber" src="<?php echo $template->parsePath('media://com_ats/images/throbber.gif')?>" />
            <p id="aknodata" style="display:none">
                <?php echo JText::_('COM_ATS_CPANEL_STATS_NODATA')?>
            </p>
        </div>

        <h2><?php echo JText::_('COM_ATS_CPANEL_SPAREDTICKTS')?></h2>
        <label style="display: inline" for="sinceSpared"><?php echo JText::_('COM_ATS_COMMON_FROMDATE')?></label>
        <?php
        $since = new JDate('-1 month');
        echo JHTML::calendar($since->format('Y-m-d'), 'sinceSpared', 'sinceSpared', '%Y-%m-%d', array('class'=> 'input-small'));
        ?>

        <label style="display: inline" for="untilSpared"><?php echo JText::_('COM_ATS_COMMON_TODATE')?></label>
        <?php echo JHTML::calendar(date('Y-m-d'), 'untilSpared', 'untilSpared', '%Y-%m-%d', array('class'=> 'input-small'));?>

        <button class="btn btn-primary pull-right" id="reloadSpared">
            <i class="icon-white icon-refresh"></i>
            <?php echo JText::_('COM_ATS_COMMON_RELOADGRAPHS') ?>
        </button>

        <div id="aksparedtickets" style="height:300px">
            <img id="akthrobberspared" src="<?php echo $template->parsePath('media://com_ats/images/throbber.gif')?>" />
            <p id="aknodataspared" style="display:none">
                <?php echo JText::_('COM_ATS_CPANEL_STATS_NODATA')?>
            </p>
        </div>
    </div>

    <div class="span6">
        <h3><?php echo JText::_('COM_ATS_CPANEL_STATS')?></h3>
        <table width="100%" class="table table-striped">
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?php echo JText::_('COM_ATS_TICKETS_STATUS_O') ?></th>
                <th><?php echo JText::_('COM_ATS_TICKETS_STATUS_P') ?></th>
                <th><?php echo JText::_('COM_ATS_TICKETS_STATUS_C') ?></th>
                <th><?php echo JText::_('COM_ATS_TICKETS_STATUS_TOTAL') ?></th>
            </tr>
            </thead>
            <tbody>
            <tr class="row0">
                <th><?php echo JText::_('COM_ATS_TICKETS_PUBLIC_PUBLIC') ?></th>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=O&public=1&enabled=1">
                        <?php
                        /** @var \Akeeba\TicketSystem\Admin\Model\TicketStatistics $ticket */
                        $ticket = $container->factory->model('TicketStatistics')->tmpInstance();
                        $public_open = $ticket->status('O')
                                              ->public(1)
                                              ->enabled(1)
                                              ->count();

                        echo $public_open;
                        ?>
                    </a>
                </td>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=P&public=1&enabled=1">
                        <?php
                        /** @var \Akeeba\TicketSystem\Admin\Model\TicketStatistics $ticket */
                        $ticket = $container->factory->model('TicketStatistics')->tmpInstance();

                        $public_pending = $ticket
                                            ->status_array('P,1,2,3,4,5,6,7,8,9')
                                            ->public(1)
                                            ->enabled(1)
                                            ->count();

                        echo $public_pending;
                        ?>
                    </a>
                </td>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=C&public=1&enabled=1">
                        <?php
                        /** @var \Akeeba\TicketSystem\Admin\Model\TicketStatistics $ticket */
                        $ticket = $container->factory->model('TicketStatistics')->tmpInstance();
                        $public_closed = $ticket->status('C')
                                                ->public(1)
                                                ->enabled(1)
                                                ->count();

                        echo $public_closed;
                        ?>
                    </a>
                </td>
                <th>
                    <a href="index.php?option=com_ats&view=Tickets&status=&public=1&enabled=1">
                        <?php
                        echo $public_open + $public_closed + $public_pending;
                        ?>
                    </a>
                </th>
            </tr>
            <tr class="row1">
                <th><?php echo JText::_('COM_ATS_TICKETS_PUBLIC_PRIVATE') ?></th>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=O&public=0&enabled=1">
                        <?php
                        /** @var \Akeeba\TicketSystem\Admin\Model\TicketStatistics $ticket */
                        $ticket = $container->factory->model('TicketStatistics')->tmpInstance();
                        $private_open = $ticket->status('O')
                                                ->public(0)
                                                ->enabled(1)
                                                ->count();

                        echo $private_open;
                        ?>
                    </a>
                </td>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=P&public=0&enabled=1">
                        <?php
                        /** @var \Akeeba\TicketSystem\Admin\Model\TicketStatistics $ticket */
                        $ticket = $container->factory->model('TicketStatistics')->tmpInstance();

                        $private_pending = $ticket
                                                ->status_array('P,1,2,3,4,5,6,7,8,9')
                                                ->public(0)
                                                ->enabled(1)
                                                ->count();

                        echo $private_pending;
                        ?>
                    </a>
                </td>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=C&public=0&enabled=1">
                        <?php
                        /** @var \Akeeba\TicketSystem\Admin\Model\TicketStatistics $ticket */
                        $ticket = $container->factory->model('TicketStatistics')->tmpInstance();
                        $private_closed = $ticket->status('C')
                                                ->public(0)
                                                ->enabled(1)
                                                ->count();

                        echo $private_closed;
                        ?>
                    </a>
                </td>
                <th>
                    <a href="index.php?option=com_ats&view=Tickets&status=&public=0&enabled=1">
                    <?php
                        echo $private_open + $private_closed + $private_pending;
                    ?>
                    </a>
                </th>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <th><?php echo JText::_('COM_ATS_TICKETS_STATUS_TOTAL') ?></th>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=O&public=&enabled=1">
                        <?php echo $public_open + $private_open ?>
                    </a>
                </td>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=P&public=&enabled=1">
                        <?php echo $public_pending + $private_pending ?>
                    </a>
                </td>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=C&public=&enabled=1">
                        <?php echo $public_closed + $private_closed ?>
                    </a>
                </td>
                <td>
                    <a href="index.php?option=com_ats&view=Tickets&status=&public=&enabled=1">
                        <?php echo $public_open + $private_open + $public_pending + $private_pending + $public_closed + $private_closed ?>
                    </a>
                </td>
            </tr>
            </tfoot>
        </table>

        <?php if(ComponentParams::getParam('userfeedback', 0) && ATS_PRO):

            $interval['week']  = '7 days';
            $interval['month'] = '30 days';
            $interval['year']  = '365 days';
            $interval['all']   = null;

            /** @var \Akeeba\TicketSystem\Admin\Model\TicketStatistics $tickets */
            $tickets = $this->getContainer()->factory->model('TicketStatistics')->tmpInstance();
            $ratings = $tickets->getRatings($interval);
            ?>
            <div style="margin-top:20px">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="userfeedback">
                    <thead>
                    <tr>
                        <th><?php echo JText::_('JGLOBAL_USERNAME') ?></th>
                        <th style="width: 15%"><?php echo JText::_('COM_ATS_CPANEL_RATING_LAST7DAYS') ?></th>
                        <th style="width: 15%"><?php echo JText::_('COM_ATS_CPANEL_RATING_LAST30DAYS') ?></th>
                        <th style="width: 15%"><?php echo JText::_('COM_ATS_CPANEL_RATING_LAST365DAYS') ?></th>
                        <th style="width: 15%"><?php echo JText::_('COM_ATS_CPANEL_RATING_OVERALL') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($ratings as $rating):?>
                        <tr>
                            <td><?php echo $rating['user']?></td>
                            <td><?php echo isset($rating['week'])  ? $rating['week']  : 0 ?></td>
                            <td><?php echo isset($rating['month']) ? $rating['month'] : 0 ?></td>
                            <td><?php echo isset($rating['year'])  ? $rating['year']  : 0 ?></td>
                            <td><?php echo isset($rating['all'])   ? $rating['all']   : 0 ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="clearfix"></div>
    <p style="font-size: small" class="well">
        <strong>
            Akeeba Ticket System <?php echo ATS_VERSION ?>
        </strong>

        <a href="index.php?option=com_ats&view=Updates&task=force" class="btn btn-inverse btn-small">
            <?php echo JText::_('COM_ATS_CPANEL_MSG_RELOADUPDATE') ?>
        </a>

        <br/>
            <span style="font-size: x-small">
                Copyright &copy;2010&ndash;<?php echo $this->getContainer()->platform->getDate(ATS_DATE)->format('Y')?>
                Nicholas K. Dionysopoulos / AkeebaBackup.com
            </span>
        <br/>

            <span style="font-size: x-small">
                Akeeba Ticket System is Free software released under the
                <a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License,</a>
                version 3 of the license or &ndash;at your option&ndash; any later version
                published by the Free Software Foundation.
            </span>
    </p>
</div>

<?php
if($this->statsIframe)
{
    echo $this->statsIframe;
}
?>

<?php $xday = gmdate('Y-m-d', time() - 30 * 24 * 3600); ?>
<script type="text/javascript">

    var ticketPoints = [];
    var postsPoints = [];
    var myMax = 0;
    var myMax2 = 0;

    (function($) {
        $(document).ready(function()
        {

            <?php if(ComponentParams::getParam('userfeedback', 0) && ATS_PRO):?>
            $('#userfeedback').dataTable( {
                "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap"
            } );

            $.extend( $.fn.dataTableExt.oStdClasses, {
                "sWrapper": "dataTables_wrapper form-inline"
            } );
            <?php endif;?>

            var url = "index.php?option=com_ats&view=TicketStatistics&enabled=1&groupbydate=1&public=&status=&format=json";
            $.jqplot.config.enablePlugins = true;
            $.getJSON(url, {
                'limitstart' : 0,
                'limit' : 0,
                'created_on' : {
                    'method' : 'search',
                    'operator' : '>=',
                    'value' : '<?php echo $xday?>'
                }
            }, function(data){
                $.each(data, function(index, item){
                    ticketPoints.push([item.date, item.tickets]);
                    myMax = Math.max(myMax, item.tickets);
                });
                myMax++;
                if(ticketPoints.length == 0) {
                    $('#akthrobber').hide();
                    $('#aknodata').show();
                    return;
                }

                url = "index.php?option=com_ats&view=PostStatistics&enabled=1&groupbydate=1&format=json";
                $.getJSON(url, {
                    'limitstart' : 0,
                    'limit' : 0,
                    'created_on' : {
                        'method' : 'search',
                        'operator' : '>=',
                        'value' : '<?php echo $xday?>'
                    }
                }, function(data){
                    $.each(data, function(index, item){
                        postsPoints.push([item.date, item.posts]);
                        myMax2 = Math.max(myMax2, item.posts);
                    });
                    myMax2++;
                    $('#akthrobber').hide();

                    renderPlot();
                });
            });

            akeeba.jQuery('#reloadSpared').click(function(){
                $.ajax('index.php?option=com_ats&view=TicketStatistics&task=showspared&layout=spared&format=json', {
                    dataType : 'json',
                    cache : false,
                    data : {
                        'limitstart' : 0,
                        'limit' : 0,
                        created_since : $('#sinceSpared').val(),
                        created_until : $('#untilSpared').val()
                    },
                    success : function(data){
                        $('#akthrobberspared').hide();
                        renderSpared(data.tickets, data.categories);
                    }
                });
            });

            akeeba.jQuery('#reloadSpared').click();

            // Check for ATS updates
            $.ajax('index.php?option=com_ats&view=ControlPanels&task=updateinfo&tmpl=component', {
                success: function(msg, textStatus, jqXHR)
                {
                    // Get rid of junk before and after data
                    var match = msg.match(/###([\s\S]*?)###/);
                    data = match[1];

                    if (data.length)
                    {
                        $('#updateNotice').html(data);
                    }
                }
            });
        });

        function renderSpared(tickets, categories)
        {
            $.jqplot('aksparedtickets', tickets, {
                // Tell the plot to stack the bars.
                stackSeries: true,
                captureRightClick: true,
                seriesDefaults:{
                    renderer:$.jqplot.BarRenderer,
                    rendererOptions: {
                        barMargin: 30
                    },
                    pointLabels: {show: true}
                },
                axes: {
                    xaxis: {
                        renderer: $.jqplot.CategoryAxisRenderer,
                        ticks   : categories,
                        tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                        tickOptions: {
                            angle: -80,
                            labelPosition: 'end',
                            fontSize: '10pt'
                        }
                    },
                    yaxis : {
                        min : 0,
                        max : 100
                    }
                },
                legend: {
                    show: true,
                    location: 'e',
                    placement: 'outside'
                },
                series: [
                    {label : '<?php echo JText::_('COM_ATS_CPANEL_OPENED')?>'},
                    {label : '<?php echo JText::_('COM_ATS_CPANEL_SAVED')?>'}
                ],
                highlighter : {
                    tooltipAxes : 'y',
                    formatString : '%d%'
                }
            });
        }

        function renderPlot()
        {
            plot1 = $.jqplot('akticketschart', [ticketPoints, postsPoints], {
                show: true,
                axes:{
                    xaxis:{renderer:$.jqplot.DateAxisRenderer,tickInterval:'1 week'},
                    yaxis:{min: 0, max: myMax, tickOptions:{formatString:'%u'}},
                    y2axis:{min: 0, max: myMax2, tickOptions:{formatString:'%u'}}
                },
                series:[
                    {
                        yaxis: 'yaxis',
                        xaxis: 'xaxis',
                        //color: '#aae0aa',
                        lineWidth:1,
                        renderer:$.jqplot.BarRenderer,
                        rendererOptions:{barPadding: 0, barMargin: 0, barWidth: 5, shadowDepth: 0, varyBarColor: 0},
                        markerOptions: {
                            style:'none'
                        }
                    },
                    {
                        yaxis: 'y2axis',
                        xaxis: 'xaxis',
                        lineWidth:3,
                        markerOptions:{
                            style:'filledCircle',
                            size:8
                        },
                        renderer: $.jqplot.hermiteSplineRenderer,
                        rendererOptions:{steps: 60, tension: 0.6}
                    }
                ],
                highlighter: {sizeAdjust: 7.5},
                axesDefaults:{useSeriesColor: true}
            });
        }
    })(akeeba.jQuery);
</script>

<div style="clear: both;"></div>