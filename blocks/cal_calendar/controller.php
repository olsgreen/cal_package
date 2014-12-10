<?php
namespace Concrete\Package\CalPackage\Block\CalCalendar;

use Loader; // remove me
use BlockType;
use Concrete\Core\Block\BlockController;

defined('C5_EXECUTE') or die("Access Denied.");
/**
 *
 * Calendar block controller
 *
 * @author Oliver Green <dubious@codeblog.co.uk>
 * @link http://www.codeblog.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPL
 *
 */
class Controller extends BlockController
{
	protected $btTable = 'btCalCalendar';
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

    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');
        if ('mini_calendar' === $this->getBlockObject()->bFilename) {   
            $this->requireAsset('minicalendar');
        } else {
            $this->requireAsset('fullcalendar');
        }
    }
}
