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
                
                if($(this).prop('checked') && $(this).val() == 'PARENT') {
                    
                    $('#pageSelectorContainer').css('display', 'none');
                    $('input[name=parentCID]').val('0');
                   
                } else if($(this).prop('checked')) {
                    
                    $('#pageSelectorContainer').css('display', 'block');
                    
                }
            
            });
            
        }
        
        $('input[name=eventsFrom]').change(setPageSelectorState);        
        setPageSelectorState();      
        
        
    });

</script>