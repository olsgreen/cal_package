<?php
namespace Concrete\Package\ConcreteCalendarPackage\Block\SimpleCalendar;

use BlockType;
use Concrete\Core\Block\BlockController;

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
    protected $btInterfaceHeight = "260";
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 300;

    public function getBlockTypeName()
    {
        return t("Calendar Block");
    }

    public function getBlockTypeDescription()
    {
        return t("Displays a calendar.");
    }

    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');

        $this->requireAsset('minicalendar');
        
        $this->requireAsset('fullcalendar');
    }
}
