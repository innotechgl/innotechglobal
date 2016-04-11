<?php
$users = $args[2];
$tasks = $args[1];
$groups = $args[3];
$allowed = $args[4];
?>
<div class="ownership">
    <h3>Access control</h3>
    Group:
    <select name="group_id">
        <?php
        foreach ($groups as $key_g => $val_g) {
            ?>
            <option
                value="<?php echo $val_g['id']; ?>"><?php echo $val_g['name'] . '-' . $engine->user_groups->types[$val_g['type']]; ?></option>
        <?php
        }
        ?>
    </select><!-- group_id --><br/><br/>
    User:
    <select name="user">
        <?php
        foreach ($users as $key_u => $val_u) {
            ?>
            <option
                value="<?php echo $val_u['id']; ?>"><?php echo $val_u['username'] . " <span class=\"small\">(Name: " . $val_u['first_name'] . " " . $val_u['last_name'] . ") </span>- " . $val_u['group_name']; ?></option>
        <?php
        }
        ?>
    </select><!-- user_id --><br/><br/>
    Task:
    <select name="task">
        <?php
        foreach ($tasks as $key_t => $val_t) {
            ?>
            <option value="<?php echo $val_t['task']; ?>"><?php echo $val_t['task']; ?></option>
        <?php
        }
        ?>
    </select>

    <div id="selected_cont">
        <h2>Users</h2>
        <ul id="ownership_users">
            <li>
                <?php
                foreach ($allowed as $key_a => $val_a) {
                    if ($val_a['user_id'] > 0) {
                        ?>
                        <input name="ownership_user_id[]" value="<?php echo $val_a['user_id']; ?>" type="hidden"/>
                        <span class="username"><?php echo $val_a['user_id']; ?></span>
                    <?
                    }
                }
                ?>
            </li>
        </ul>
        <h2>Groups</h2>
        <ul id="ownership_groups">
            <?php
            $i = 0;
            foreach ($allowed as $key_a => $val_a) {
                if ($val_a['user_id'] > 0) {
                    ?>
                    <li>
                        <input name="ownership_user_id[]" value="<?php echo $val_a['user_id']; ?>" type="hidden"/>
                        <span class="username"><?php echo $val_a['user_id']; ?></span>
                        <a href="#" onclick="removeMe(this);"></a>
                    </li>
                    <?
                    $i++;
                }
            }
            ?>
        </ul>
    </div>
    <!-- selected_cont -->
</div><!-- ownership -->
<script type="textext/javascript">
    function removeMe(el)
    {
        $(el).getParent().destroy();
    }
    function add(type)
    {
       switch(type){
       }
    }

</script>