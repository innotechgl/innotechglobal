<?php
$months = implode(",", $engine->calendar->lang->months);
$days = implode(",", $engine->calendar->lang->days);

$cal = <<<EOT
    <script type="text/javascript">
    var calendar_{$engine->calendar->calendar_no} = new calendar_class({
            link: '{$engine->calendar->ajax_link}',
            meseci: [{$months}],
            daniUNedelji: [{$days}],
            div: '{$engine->calendar->div}',
            mesec:{$engine->calendar->month},
            godina:{$engine->calendar->year},
            maxProslost:{$engine->calendar->max_backward},
            maxBuducnost:{$engine->calendar->max_forward}
    });
   EOT;
   </script>
   echo $cal;
?>