<?php 
defined('C5_EXECUTE') or die("Access Denied.");

/**
 *
 * AJAX server for the full calendar
 *
 * @author Oliver Green <oliver@devisegraphics.co.uk>
 * @link http://www.devisegraphics.co.uk
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @version $Id$
 *
 */

header('Content-type: application/json');


// Get the list of pages that fall within the dates
$pl = new PageList();
$pl->filterByCollectionTypeHandle('og_calendar_event');
$pl->filterByParentID(intval($_GET['parentCID']));

$events = array();


    foreach($pl->get() as $page) {
    
        $events[] = array('title' => $page->getCollectionName(),
                          'start' => DateTime::createFromFormat('Y-m-d H:i:s', $page->getAttribute('og_start_date'))->format(DATE_RFC2822),
                          'end' => DateTime::createFromFormat('Y-m-d H:i:s', $page->getAttribute('og_end_date'))->format(DATE_RFC2822),
                          'url' => View::url($page->getCollectionPath()),
                          'allDay' => false,
                          'ignoreTimezone' => false);
        
    }

print json_encode($events);