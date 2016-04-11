<table width="100%">
    <?php
    $default = array();
    if (isset($this->settings['default'])) {
        $default = $this->settings['default'];
    }
    foreach ($default as $key_s => $val_s) {
        $value = '';
        # check value
        if (isset($settings['written'])) {
            foreach ($settings['written'] as $key_w => $val_w) {
                if ($key_w == $val_s['fieldName']) {
                    $value = $val_w;
                    break;
                }
            }
        }
        if ($value == '') {
            if (@$val_s['default_value'] !== null) {
                $value = $val_s['default_value'];
            }
        }
        //print_r($val_s);
        ?>
        <tr>
            <td width="100"><?php echo $val_s['name']; ?></td>
            <td><?php
                switch ($val_s['type']) {
                    case "boolean":
                        $selected_one = '';
                        $selected_second = '';
                        if ($value == 1) {
                            $selected_one = 'checked=checked';
                        } else {
                            $selected_second = 'checked=checked';
                        }
                        ?>
                        Show
                        <input type="radio" name="<?php echo $val_s['fieldName']; ?>"
                               value="1" <?php echo $selected_one; ?> />
                        &nbsp;&nbsp;
                        Hide
                        <input type="radio" name="<?php echo $val_s['fieldName']; ?>"
                               value="0" <?php echo $selected_second; ?> />
                        <?php
                        break;
                    default:
                    case "string":
                    case "number":
                        $size = 20;
                        if (isset($val_s['size'])) {
                            $size = $val_s['size'];
                        }
                        ?>
                        <input type="text" name="<?php echo $val_s['fieldName']; ?>" value="<?php echo $value; ?>"
                               size="<?php echo $size; ?>"/>
                        <?php
                        break;
                    case "drop":
                        ?>
                        <select name="<?php echo $val_s['fieldName']; ?>">
                            <?php
                            $f_options = (array)$val_s['drop_options'];
                            print_r($f_options);
                            foreach ($f_options['fieldOption'] as $val_o) {
                                $selected = '';
                                if ($val_o == $value) {
                                    $selected = 'selected=selected';
                                }
                                ?>
                                <option
                                    value="<?php echo trim($val_o); ?>" <?php echo $selected; ?>> <?php echo $val_o; ?> </option>
                            <?php
                            }
                            ?>
                        </select>
                        <?php
                        break;
                }

                ?></td>
        </tr>
    <?php
    }
    ?>
</table>
