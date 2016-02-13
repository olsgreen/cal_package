<?php
namespace Concrete\Package\ConcreteCalendarPackage;

use DateTime;
use Exception;
use AssetList;
use Asset;
use BlockType;
use View;
use Package;
use PageType;
use Route;
use CollectionAttributeKey;
use PageList;
use PageTemplate;
use Concrete\Core\Attribute\Key\Category as AttributeCategory;
use Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Page\Type\Composer\Control\Type\Type as PageTypeComposerControlType;
use \Concrete\Core\Page\Type\PublishTarget\Type\Type as PageTypePublishTargetType;
use \Concrete\Core\Page\Type\Composer\Control\CorePageProperty\NameCorePageProperty;

/**
 * Package adding calendar functionality to C5
 *
 * @author Oliver Green <dubious@codeblog.co.uk>
 * @link http://www.codeblog.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPLs
 */
class Controller extends Package
{
    protected $pkgHandle = 'concrete-calendar-package';

    protected $appVersionRequired = '5.7.1';

    protected $pkgVersion = '0.9.0';

    public function getPackageName()
    {
        return t("Calendar Components Package");
    }

    public function getPackageDescription()
    {
        return t("Package adding simple calendar functionality to concrete.");
    }

    public function on_start()
    {
        $this->registerCalendarJsonRoute();
        $this->registerAssets();
    }

    public function install()
    {
        $pkg = parent::install();

        // Get a list of installed page templates
        $templates = PageTemplate::getList();
        if (0 === count($templates)) {
            throw Exception(
                'This package requires at least one page template, there are no page templates defined.'
            );
        }

        // Install the custom attribute type
        $at = AttributeType::add('calendar_date_time', 'Calendar Date & Time', $pkg);
        AttributeCategory::getByHandle('collection')->associateAttributeKeyType($at);

        // Install Calendar Entry Page Type
        $ePT = PageType::add(
            array(
                'name' => 'Calendar Entry',
                'handle' => 'calendar-entry',
                'ptLaunchInComposer' => true,
                'defaultTemplate' => $templates[0],
            ),
            $pkg
        );

        // Set the composer publish target for the new page type
        // to 'Choose from all pages when publishing'
        $target = PageTypePublishTargetType::getByHandle('all');
        $configuredTarget = $target->configurePageTypePublishTarget($ePT, false);
        $ePT->setConfiguredPageTypePublishTargetObject($configuredTarget);

        // Add the 'Basics' composer control set to the page type
        $basics_set = $ePT->addPageTypeComposerFormLayoutSet(
            'Basics',
            'Adds the basic fields for creating a new page.'
        );

        $this->addCoreToComposerFormSet($basics_set);

        // Add Start & End Date / Time Attribute Keys
        $sAK = CollectionAttributeKey::add(
            $at,
            array(
                'akHandle' => 'cal_start_date',
                'akName' => 'Calendar Entry Start Date / Time',
                'akIsSearchable' => 1,
                'akIsSearchableIndexed'=> 1,
                'akSelectAllowMultipleValues' => 0,
                'akSelectAllowOtherValues' => 0
            ),
            $pkg
        );

        $eAK = CollectionAttributeKey::add(
            $at,
            array(
                'akHandle' => 'cal_end_date',
                'akName' => 'Calendar Entry End Date / Time',
                'akIsSearchable' => 1,
                'akIsSearchableIndexed'=> 1,
                'akSelectAllowMultipleValues' => 0,
                'akSelectAllowOtherValues' => 0
              ),
            $pkg
        );

        // Add Composer Layout Set
        $set = $ePT->addPageTypeComposerFormLayoutSet(
            'Calendar Dates / Times',
            'The dates and times that the entry will be shown on the calendar between.'
        );

        // Add the attributes to the page types composer form
        $this->addCollectionAttributeToComposerFormSet($set, $sAK);
        $this->addCollectionAttributeToComposerFormSet($set, $eAK);


        // Install the calendar block type
        $calendarBT = BlockType::installBlockTypeFromPackage('simple-calendar', $pkg);

        return $pkg;

    }

    protected function addCollectionAttributeToComposerFormSet($set, $attr)
    {
        $type = PageTypeComposerControlType::getByHandle('collection_attribute');
        $control = $type->getPageTypeComposerControlByIdentifier($attr->getAttributeKeyID());
        return $control->addToPageTypeComposerFormLayoutSet($set);
    }

    protected function addCoreToComposerFormSet($set)
    {
        $control = new NameCorePageProperty();
        $control->setPageTypeComposerControlName('Entry Name');
        return $control->addToPageTypeComposerFormLayoutSet($set);
    }

    protected function registerAssets()
    {
        // Register themes assets
        $al = AssetList::getInstance();

        // Tooltip
        $al->register(
            'css',
            'cal/tooltip',
            'assets/tooltip.css',
            array(
                'version' => '2.3.1', 'position' => Asset::ASSET_POSITION_HEADER,
                'minify' => true, 'combine' => false
            ),
            $this
        );

        // Full Calendar
        $al->register(
            'css',
            'fullcalendar/css',
            'assets/full-calendar/fullcalendar.css',
            array(
                'version' => '2.3.1', 'position' => Asset::ASSET_POSITION_HEADER,
                'minify' => true, 'combine' => false
            ),
            $this
        );

        $al->register(
            'css',
            'fullcalendar/css-print',
            'assets/full-calendar/fullcalendar.print.css',
            array(
                'version' => '2.3.1', 'position' => Asset::ASSET_POSITION_HEADER,
                'minify' => true, 'combine' => false
            ),
            $this
        );

        $al->register(
            'javascript',
            'fullcalendar/js',
            'assets/full-calendar/fullcalendar.js',
            array(
                'version' => '2.3.1', 'position' => Asset::ASSET_POSITION_FOOTER,
                'minify' => true, 'combine' => false
            ),
            $this
        );

        $al->register(
            'javascript',
            'fullcalendar/gcal',
            'assets/full-calendar/gcal.js',
            array(
                'version' => '2.3.1', 'position' => Asset::ASSET_POSITION_FOOTER,
                'minify' => true, 'combine' => false
            ),
            $this
        );

        $al->register(
            'javascript',
            'fullcalendar/moment',
            'assets/full-calendar/lib/moment.min.js',
            array(
                'version' => '2.3.1', 'position' => Asset::ASSET_POSITION_FOOTER,
                'minify' => true, 'combine' => false
            ),
            $this
        );

        $al->registerGroup(
            'fullcalendar',
            array(
                array('css', 'fullcalendar/css'),
                array('css', 'fullcalendar/css-print'),
                array('javascript', 'fullcalendar/moment'),
                array('javascript', 'fullcalendar/js'),
                array('javascript', 'fullcalendar/gcal'),
                array('css', 'cal/tooltip'),
                array('javascript', 'bootstrap/tooltip')
            )
        );

        // Mini Calendar
        $al->register(
            'javascript',
            'minicalendar/js',
            'assets/mini-calendar/jquery.mini-calendar.js',
            array(
                'version' => '1.6.1', 'position' => Asset::ASSET_POSITION_HEADER,
                'minify' => true, 'combine' => false
            ),
            $this
        );

        $al->registerGroup(
            'minicalendar',
            array(
                array('javascript', 'minicalendar/js'),
                array('css', 'cal/tooltip'),
                array('javascript', 'bootstrap/tooltip')
            )
        );
    }

    protected function getFormattedEvent($page)
    {
        return array(
            'title' => $page->getCollectionName(),
            'start' => DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $page->getAttribute('cal_start_date')
            )->format(DATE_ISO8601),
            'end' => DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $page->getAttribute('cal_end_date')
            )->format(DATE_ISO8601),
            'url' => View::url($page->getCollectionPath()),
            'allDay' => false,
            'ignoreTimezone' => false
        );
    }

    protected function registerCalendarJsonRoute()
    {
        Route::register(
            '/get-cal-json/{parent_cid}',
            function ($parent_cid) {
                $pl = new PageList();
                $pl->filterByCollectionTypeHandle('cal_entry');

                if (intval($parent_cid) > 0) {
                    $pl->filterByParentID(intval($parent_cid));
                }

                $events = array();

                foreach ($pl->get() as $page) {
                    $events[] = $this->getFormattedEvent($page);
                }

                return json_encode($events);
            },
            null
        );
    }
}
