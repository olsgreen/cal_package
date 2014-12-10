<?php 
	defined('C5_EXECUTE') or die("Access Denied.");
/**
 *
 * Calendar block controller
 *
 * @author Oliver Green <oliver@devisegraphics.co.uk>
 * @link http://www.devisegraphics.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @version $Id$
 *
 */

class OgCalendarBlockController extends BlockController {
	
	protected $btTable = 'btOgCalendar';
	protected $btInterfaceWidth = "450";
	protected $btInterfaceHeight = "160";
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = 300;
	
	public function getBlockTypeDescription() {
		return t("Displays a calendar.");
	}
	
	public function getBlockTypeName() {
		return t("Calendar Block");
	}
    
    public function on_page_view() {      
        
        $uh = Loader::helper('concrete/urls');
        $bt = BlockType::getByHandle($this->btHandle);
        $btURL = $uh->getBlockTypeAssetsURL($bt);
        $this->addHeaderItem('<link href="' . $btURL . '/js/fullcalendar/fullcalendar.css" rel="stylesheet" media="screen">');
        $this->addHeaderItem('<link href="' . $btURL . '/js/fullcalendar/fullcalendar.print.css" rel="stylesheet" media="print">');
        $this->addHeaderItem('<script src="' . $btURL . '/js/fullcalendar/fullcalendar.min.js"></script>');
        $this->addHeaderItem('<script src="' . $btURL . '/js/mini-calendar/jquery.mini-calendar.min.js"></script>');
        $this->addHeaderItem('<script src="' . $btURL . '/js/bootstrap-tooltip.js"></script>');
        $this->addHeaderItem('<link href="' . $btURL . '/css/tooltip.css" rel="stylesheet" media="screen">');
    }
		
		
}