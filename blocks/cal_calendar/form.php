<?php 
	defined('C5_EXECUTE') or die("Access Denied.");
/**
 *
 * Calendar block add / edit form
 *
 * @author Oliver Green <dubious@codeblog.co.uk>
 * @link http://www.codeblog.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @version $Id$
 *
 */

$pageSelector = \Loader::helper('form/page_selector');
?>

<div class="ccm-ui">
    
    Display events that are located:
    <br>
    <input type="radio" name="source" value="0"<?php echo (intval($source) === 0) ? 'checked="checked"' : ''; ?>> beneath this page
    <input type="radio" name="source" value="1"<?php echo (intval($source) === 1) ? 'checked="checked"' : ''; ?>> beneath another page    
    <input type="radio" name="source" value="2"<?php echo (intval($source) === 2) ? 'checked="checked"' : ''; ?>> on a Google Calendar    
    
    <div id="pageSelectorContainer" style="display: none;">
        <?php print $pageSelector->selectPage('parentCID',$parentCID,'ccm_selectSitemapNode'); ?>
    </div>

    <div id="gcalContainer" style="display: none;">
        <hr>
        Google Calendar ID:<br>
        <textarea name="calendarId" style="width: 100%;"><?php echo $calendarId; ?></textarea>
        <br>
        Google API Key:<br>
        <textarea name="apiKey" style="width: 100%;"><?php echo $apiKey; ?></textarea>
    </div>
    
</div>

<script type="text/javascript">

    $(function(){
     
        function setPageSelectorState() {
         
            $('input[name=source]').each(function(){
                
                if($(this).prop('checked') && $(this).val() == '0') {
                    
                    $('#pageSelectorContainer, #gcalContainer').css('display', 'none');
                    $('input[name=parentCID]').val('0');
                    $('input[name=calendarId],input[name=apiKey').val('');
                   
                } else if($(this).prop('checked') && $(this).val() == '1') {
                    
                    $('#pageSelectorContainer').css('display', 'block');
                    $('#gcalContainer').css('display', 'none');
                    $('input[name=calendarId],input[name=apiKey').val('');
                    
                } else if($(this).prop('checked') && $(this).val() == '2') {

                    $('#gcalContainer').css('display', 'block');
                    $('#pageSelectorContainer').css('display', 'none');
                    $('input[name=parentCID]').val('0');

                }
            
            });
            
        }
        
        $('input[name=source]').change(setPageSelectorState);        
        setPageSelectorState();      
        
        
    });

</script>