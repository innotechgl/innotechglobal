<script type="text/javascript" src="/includes/js/utils/load_ajax.js"></script>
<div id="<?php echo 'advanced_select_' . $id; ?>" class="<?php echo $class; ?>">
    <input type="text" id="label_<?php echo $id; ?>" class="advanced_list_name"></span>
    <input type="hidden" value="<?php echo $value; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>"/>
    <ul id="advanced_list_<?php echo $id; ?>" class="advanced_select_list hide">
    </ul>
</div>
<script type='text/javascript'>
    window.addEvent('domready', function () {
        load_ajax($('advanced_list_<?php echo $id; ?>'), '<?php echo $ajax_call; ?>');
        $('label_<?php echo $id; ?>').addEvent('focus', function (event) {
            var advanced_list = 'advanced_list_<?php echo $id; ?>'
            $(advanced_list).removeClass('hide');
            $(advanced_list).addClass('show');
        });
        $('label_<?php echo $id; ?>').addEvent('blur', function (event) {
            hide_list.delay(500);
        });
        $('advanced_list_<?php echo $id; ?>').addEvent('click', function (event) {
            var advanced_list = 'advanced_list_<?php echo $id; ?>'
            if ($(event.target).get('tag') == 'a') {
                $('label_<?php echo $id; ?>').set('value', $(event.target).get('text'));
                $('<?php echo $id; ?>').set('value', $(event.target).get('id'));
                $(advanced_list).removeClass('show');
                $(advanced_list).addClass('hide');
            }
        });
        function hide_list() {
            var advanced_list = 'advanced_list_<?php echo $id; ?>'
            $(advanced_list).removeClass('show');
            $(advanced_list).addClass('hide');
        }

        function get_selected() {
            $$('#advanced_list_<?php echo $id; ?> li a').each(function (item) {
                if ($(item).get('id') ==<?php echo $value; ?>) {
                    $('label_<?php echo $id; ?>').set('value', $(item).get('text'));
                }
            });
        }

        get_selected.delay(1000);
    });
</script>