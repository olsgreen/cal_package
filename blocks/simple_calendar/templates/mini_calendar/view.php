<?php
defined('C5_EXECUTE') or die("Access Denied.");
/**
 *
 * Calendar view template
 *
 * @author Oliver Green <dubious@codeblog.co.uk>
 * @link http://www.codeblog.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @version $Id$
 *
 */
$c = Page::getCurrentPage();
$parentCID = ($parentCID > 0) ? $parentCID : $c->getCollectionID();?>
<div id="calendar-<?php echo $bID; ?>"></div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#calendar-<?php echo $bID; ?>").miniCalendar({
            <?php if ($source !== '2') { ?>
            url: '<?php echo View::url("/get-cal-json", $parentCID); ?>',
            <?php } else { ?>
            googleCalendarApiKey: '<?php echo $apiKey; ?>',
            googleCalendarId: '<?php echo $calendarId; ?>',
            <?php } ?>
            dayOnClick: goToEvent,
            dayOnMouseOver: function(evt, data){
                var title = '';
                for(var i=0; i < data.length; i++) {
                    var startDate = moment(data[i].start).toDate(),
                        endDate = moment(data[i].end).toDate();

                    if (! data[i].allDay && data[i].startDay) {
                        title += startDate.getHours() + ':' + ((startDate.getMinutes() < 10) ? startDate.getMinutes() + '0' : startDate.getMinutes()) + ' ';
                    }

                    if (! data[i].allDay && data[i].endDay) {
                        title += endDate.getHours() + ':' + ((endDate.getMinutes() < 10) ? endDate.getMinutes() + '0' : endDate.getMinutes()) + ' ';
                    }

                    title += data[i].title + '<br />';
                }
                $(evt.delegateTarget).tooltip({title: title.substr(0,title.length - 6), container: 'body', html: true}).tooltip('show');
            },
            error: function(error) { alert('The calendar could not be loaded.'); }
        });
    });

    function goToEvent(evt, data) {
        window.location = data[0].url;
    }
</script>