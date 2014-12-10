<?php 
defined('C5_EXECUTE') or die("Access Denied.");

/**
 *
 * Package adding calendar functionality to C5
 *
 * @author Oliver Green <oliver@devisegraphics.co.uk>
 * @link http://www.devisegraphics.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @version $Id$
 *
 */

class OgCalendarPackage extends Package
{
 
    protected $pkgHandle = 'og_calendar';
    protected $appVersionRequired = '5.6';
    protected $pkgVersion = '0.1';

    public function getPackageDescription() {
    	return t("Package adding calendar functionality to C5.");
    }

    public function getPackageName() {
    	return t("Calendar");
    }
    
    public function install() {
     
        $pkg = parent::install();
        
        // Install Calendar Event Page Type
        $cCT = CollectionType::add(array('ctName'=>'Calendar Event', 'ctHandle'=>'og_calendar_event'), $pkg);   
        
        // Add Start & End Date and Time to Page Type
		$sak = CollectionAttributeKey::add(AttributeType::getByHandle('date_time'), array(
			'akHandle' => 'og_start_date', 
			'akName' => 'Calendar Event Start Date', 
			'akIsSearchable' => 1, 
			'akIsSearchableIndexed'=> 1, 
			'akSelectAllowMultipleValues' => 0,
			'akSelectAllowOtherValues' => 0
			
		), $pkg);
        
        $eak = CollectionAttributeKey::add(AttributeType::getByHandle('date_time'), array(
			'akHandle' => 'og_end_date', 
			'akName' => 'Calendar Event End Date', 
			'akIsSearchable' => 1, 
			'akIsSearchableIndexed'=> 1, 
			'akSelectAllowMultipleValues' => 0,
			'akSelectAllowOtherValues' => 0
			
		), $pkg);
			
		// Assign to our page type
		$cCT->assignCollectionAttribute($sak);
        $cCT->assignCollectionAttribute($eak);
        
        // Install the calendar block type             
        $calendarBT = BlockType::installBlockTypeFromPackage('og_calendar', $pkg);
        
        return $pkg;
        
    }
    
}