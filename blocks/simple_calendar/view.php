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
$uh = Loader::helper('concrete/urls');
$c = Page::getCurrentPage();
$parentCID = ($parentCID > 0) ? $parentCID : $c->getCollectionID();?>
<div id="calendar-<?php echo $bID; ?>"></div>
<script type="text/javascript">

    $(document).ready(function() {

        $('#calendar-<?php echo $bID; ?>').fullCalendar({
            <?php if ($source !== '2') { ?>
            events: {
                url: '<?php echo View::url("/get-cal-json", $parentCID); ?>',
                error: function(data) {
                    alert('There was an error while fetching the events! Please try again.');
                }
            },
            <?php } else { ?>
            googleCalendarApiKey: '<?php echo $apiKey; ?>',
            events: {
                googleCalendarId: '<?php echo $calendarId; ?>'
            },
            <?php } ?>
            timeFormat: 'h:mm',
        });
    });
</script>