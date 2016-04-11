<?php
global $engine;
$link = '/ajax/utils/report/add/';
$types = $engine->util_report->get_types();
?>
<div class="report_form" id="report_form_<?php echo $this->util_id; ?>"
     style="position: absolute; display:none; padding: 10px;background-color: white;border:solid 2px black;z-index:999;">
    <div align="right"><a href="#" onclick="reports_<?php echo $this->util_id; ?>.hide_form();">X</a></div>
    <form method="post" enctype="multipart/form-data" action="<?php echo $link; ?>" id="report_form">
        <table>
            <tr>
                <td>Type</td>
                <td>
                    <select name="type" id="report_<?php echo $this->util_id; ?>_type">
                        <?php
                        foreach ($types as $key_t => $val_t) {
                            ?>
                            <option value="<?php echo $val_t; ?>">
                                <?php echo $val_t; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">Komentar</td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea cols="30" rows="10" name="" id="report_<?php echo $this->util_id; ?>_comment"
                              class="mceNoEditor"></textarea>
                </td>
            </tr>
        </table>
        <button type="button" id="submit_report" onclick="reports_<?php echo $this->util_id; ?>.submit_report();">
            Pošalji
        </button>
    </form>
</div><!-- report_form -->
