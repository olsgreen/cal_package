<?php
namespace Concrete\Package\ConcreteCalendarPackage\Attribute\CalendarDateTime;

use Loader;
use Core;
use \Concrete\Core\Attribute\Controller as AttributeTypeController;

class Controller extends AttributeTypeController
{
    public $helpers = array('form');

    protected $searchIndexFieldDefinition = array('type' => 'datetime', 'options' => array('notnull' => false));

    public function getDisplayValue()
    {
        $v = $this->getValue();
        if(empty($v)) {
            return '';
        }
        $dh = Core::make('helper/date'); 
        $display = '';

        if ('1' === $v['is_all_day']) {
            $display = $dh->formatCustom('d/m/Y', $v['date_from'], false, 'system');

            if ('1' === $v['is_multi_day']) {
                $display .= ' - ' . $dh->formatCustom('d/m/Y', $v['date_to'], false, 'system');
            }
        } else {
            $display = $dh->formatCustom('d/m/Y h:ia', $v['date_from']);

            if ('1' === $v['is_multi_day']) {
                $display .= ' - ' . $dh->formatCustom('d/m/Y h:ia', $v['date_to']);
            }
        }

        return $display;
    }

    public function searchForm($list)
    {
        $dateFrom = $this->request('from');
        $dateTo = $this->request('to');

        if ($dateFrom) {
            $dateFrom = date('Y-m-d', strtotime($dateFrom));
            $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $dateFrom, '>=');
        }
        if ($dateTo) {
            $dateTo = date('Y-m-d', strtotime($dateTo));
            $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $dateTo, '<=');
        }

        return $list;
    }

    public function form()
    {
        $dt = Loader::helper('form/date_time');
        $html = Loader::helper('html');
        $this->requireAsset('bootstrap-daterangepicker');
        $this->requireAsset('javascript', 'calendar-control/js');
        
        $this->set('id', $this->getAttributeKey()->getAttributeKeyID());
        $this->set('row', $this->getValue());
    }


    public function validateValue()
    {
        $v = $this->getValue();

        return $v != false;
    }

    public function validateForm($data)
    {
        return true;
    }

    public function getValue()
    {
        $db = Loader::db();

        $value = $db->GetRow(
            "select * from atCalendarDateTime where avID = ?",
            array($this->getAttributeValueID())
        );

        return $value;
    }

    public function search()
    {
        $dt = Loader::helper('form/date_time');
        $html = $dt->date($this->field('from'), $this->request('from'), true);
        $html .= ' ' . t('to') . ' ';
        $html .= $dt->date($this->field('to'), $this->request('to'), true);
        print $html;
    }

    public function duplicateKey($newAK)
    {
        $db = Loader::db();
        $db->Execute(
            'insert into atDateTimeSettings (akID, akDateDisplayMode) values (?, ?)',
            array($newAK->getAttributeKeyID(), $this->akDateDisplayMode)
        );
    }

    public function saveForm($data)
    {
        if (empty($data['value'])) {
            return;
        }

        $dates = explode(' - ', $data['value']);

        $dates = array_map(function ($item) use ($data) {
            if ('1' === $data['is_all_day']) {
                return \DateTime::createFromFormat('d/m/Y', $item);
            }

            return \DateTime::createFromFormat('d/m/Y h:ia', $item);
        }, $dates);

        if ('1' === $data['is_multi_day']) {
            list($date_from, $date_to) = $dates;
        } else {
            $date_from = $dates[0];
            $date_to = clone $dates[0];
        }

        if ('1' === $data['is_all_day']) {
            $date_from->setTime(0, 0, 0);
            $date_to->setTime(23, 59, 59);
        }

        $db = Loader::db();
        $db->Replace('atCalendarDateTime', array(
            'avID' => $this->getAttributeValueID(),
            'date_from' => $date_from->format('Y-m-d H:i:s'),
            'date_to' => $date_to->format('Y-m-d H:i:s'),
            'is_all_day' => $data['is_all_day'],
            'is_multi_day' => $data['is_multi_day'],
        ), 'avID', true);
    }

    public function deleteKey()
    {
        $db = Loader::db();
        $arr = $this->attributeKey->getAttributeValueIDList();
        foreach ($arr as $id) {
            $db->Execute('delete from atCalendarDateTime where avID = ?', array($id));
        }
    }
    public function deleteValue()
    {
        $db = Loader::db();
        $db->Execute('delete from atCalendarDateTime where avID = ?', array($this->getAttributeValueID()));
    }
}
