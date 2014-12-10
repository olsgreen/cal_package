<?php 
	defined('C5_EXECUTE') or die("Access Denied.");
/**
 *
 * Calendar block add / edit form
 *
 * @author Oliver Green <oliver@devisegraphics.co.uk>
 * @link http://www.devisegraphics.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @version $Id$
 *
 */

$pageSelector = Loader::helper('form/page_selector');
?>

<div class="ccm-ui">
    
    <label for="calendarType">Calendar Size:</label>
    <select name="calendarType">
        <option value="0"<?php echo ($calendarType == '0') ? ' selected="selected"' : ''; ?>>Full Calendar</option>
        <option value="1"<?php echo ($calendarType == '1') ? ' selected="selected"' : ''; ?>>Mini Calendar</option>
    </select>
    
    <br /><br />
    
    Display events that are located:
    <input type="radio" name="eventsFrom" value="PARENT"<?php echo (intval($parentCID) == 0) ? 'checked="checked"' : ''; ?>> beneath this page
    <input type="radio" name="eventsFrom" value="OTHER"<?php echo (intval($parentCID) > 0) ? 'checked="checked"' : ''; ?>> beneath another page    
    
    <div id="pageSelectorContainer" style="display: none;">
        <?php print $pageSelector->selectPage('parentCID',$parentCID,'ccm_selectSitemapNode'); ?>
    </div>
    
</div>

<script type="text/javascript">

    $(function(){
     
        function setPageSelectorState() {
         
            $('input[name=eventsFrom]').each(function(){
                
                if($(this).attr('checked') == 'checked' && $(this).val() == 'PARENT') {
                    
                    $('#pageSelectorContainer').css('display', 'none');
                    $('input[name=parentCID]').val('0');
                   
                } else if($(this).attr('checked') == 'checked') {
                    
                    $('#pageSelectorContainer').css('display', 'block');
                    
                }
            
            });
            
        }
        
        $('input[name=eventsFrom]').change(setPageSelectorState);        
        setPageSelectorState();      
        
        
    });

</script>