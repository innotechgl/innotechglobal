<div class="form-items row-fluid">
    <div class="col col-5">
        <label for="<?php echo $option->name; ?>"><?php echo $option->title; ?></label>
    </div>
    <div class="col col-5">
        <select name="<?php echo (string)$option->name; ?>" id="<?php echo (string)$option->name; ?>">
            <?php
            foreach ($option->fieldOptions->fieldOption as $val) {
                $selected = '';
                if (isset($loadedValues[(string)$option->name])) {
                    if ($loadedValues[(string)$option->name] == $val) {
                        $selected = "selected";
                    }
                }
                ?>
                <option value="<?php echo $val; ?>" <?php echo $selected; ?>>
                    <?php echo $val; ?>
                </option>
            <?php
            }
            ?>
        </select>
    </div>
</div><!-- .form-item -->