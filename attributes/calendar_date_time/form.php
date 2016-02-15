<?php
    $form = Loader::helper('form');
?>
<style>
    .calendar-control-label {
        margin-bottom: 10px;
        margin-top: 10px;
        display: block;
    }

     .ccm-ui input[type="radio"].calendar-radio {
        margin-left: 5px;
        margin-right: 15px;
    }

    .ccm-ui .form-control.calendar-input {
        width: 320px;
    }
</style>
<span class="calendar-control-label">Is this an multi-day event?</span>
<?php 
    echo $form->label($id.'_multi_yes', 'Yes');
    echo $form->radio($this->field('is_multi_day'), '1', $row['is_multi_day'], array('id' => $id.'_multi_yes', 'data-id' => $id, 'class' => 'calendar-radio'));
    echo $form->label($id.'_multi_no', 'No');
    echo $form->radio($this->field('is_multi_day'), '0', $row['is_multi_day'] ?: false, array('id' => $id.'_multi_no', 'data-id' => $id, 'class' => 'calendar-radio'));
?>
<span class="calendar-control-label">Is this an all-day event?</span>
<?php
    echo $form->label($id.'_all_yes', 'Yes');
    echo $form->radio($this->field('is_all_day'), '1', $row['is_all_day'] ?: true, array('id' => $id.'_all_yes', 'data-id' => $id, 'class' => 'calendar-radio'));
    echo $form->label($id.'_all_no', 'No');
    echo $form->radio($this->field('is_all_day'), '0', $row['is_all_day'], array('id' => $id.'_all_no', 'data-id' => $id, 'class' => 'calendar-radio'));
?>
<span class="calendar-control-label">Event Date(s) &amp; Time(s)</span>
<?php echo $form->text($this->field('value'), $this->controller->getDisplayValue(), array('class' => 'calendar-input')); ?>
<script>new CalendarControl(<?php echo $id; ?>);</script>