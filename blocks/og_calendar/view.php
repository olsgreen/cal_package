<?php 
defined('C5_EXECUTE') or die("Access Denied.");

/**
 *
 * Calendar view template
 *
 * @author Oliver Green <oliver@devisegraphics.co.uk>
 * @link http://www.devisegraphics.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @version $Id$
 *
 */

$uh = Loader::helper('concrete/urls');

$c = Page::getCurrentPage();
$parentCID = ($parentCID > 0) ? $parentCID : $c->getCollectionID();

// Full Calendar
if($calendarType == 0) {
?>

<div id="calendar-<?php echo $bID; ?>"></div>
<script type="text/javascript">

    $(document).ready(function() {
    
        $('#calendar-<?php echo $bID; ?>').fullCalendar({
            events: {
                    url: '<?php echo $uh->getToolsURL('get_events', 'og_calendar'); ?>',
                    data: {
                        parentCID: '<?php echo $parentCID; ?>'
                    },
                    error: function(data) {
                        alert('There was an error while fetching the events! Please try again.');
                    },
                    color: '#8a5c85',
                    textColor: 'white'
                },
                timeFormat: 'h:mmtt',
                                                       
        })
    
    });
    
</script>

<?php } else { // Mini Calendar ?>
<div id="calendar-<?php echo $bID; ?>"></div>


<script type="text/javascript">

    $(function(){

        $("#calendar-<?php echo $bID; ?>").miniCalendar({
            
            url: '<?php echo $uh->getToolsURL('get_events', 'og_calendar'); ?>',
            data: {
                    parentCID: '<?php echo $parentCID; ?>'
                  },
            dayOnClick: goToEvent,
            dayOnMouseOver: function(evt, data){                        
                var title = '';
                
                for(var i=0; i < data.length; i++) {            
                    var date = new Date(data[i].start);
                        title += date.getHours() + ':' + ((date.getMinutes() < 10) ? date.getMinutes() + '0' : date.getMinutes()) + ' ' + data[i].title + '<br />';                
                }            
                
                $(evt.delegateTarget).tooltip({title: title.substr(0,title.length - 6), container: 'body'}).tooltip('show')
            },
            error: function(error) { alert('The calendar could not be loaded.'); }
        });        
        
    }); 
    
    function goToEvent(evt, data) {
        
        window.location = data[0].url;
        
    }
    
</script>
<?php } ?>