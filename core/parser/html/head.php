<meta name='keywords' content='<?php echo $this->keywords; ?>'/>
<meta name='description' content='<?php echo $this->description; ?>'/>
<meta name='ROBOTS' content='<?php echo $this->robots; ?>'/>
<meta property="og:title" content="<?php echo $this->getOgTitle(); ?>"/>
<meta property="og:type" content="<?php echo $this->getOgType(); ?>"/>
<meta property="og:url" content="<?php echo $this->getOgUrl(); ?>"/>
<meta property="og:image" content="<?php echo $this->getOgImage(); ?>"/>
<meta property="og:description" content="<?php echo $this->getOgDescription(); ?>"/>
<?php
foreach ($this->styles as $key => $val) {
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $val['path']; ?>" media="<?php echo $val['media']; ?>"/>
<?php
}
?>
<link rel="stylesheet" type="text/css" href="/includes/js/slimbox/css/slimbox.css" media="screen"/>
<title><?php echo $this->title; ?></title>
<?php
if (isset($this->scripts['js'])) {
    foreach ($this->scripts['js'] as $key => $val) {
        ?>
        <script type='text/javascript' src='<?php echo $engine->settings->general->server . $val; ?>'></script>
    <?php
    }
}
?>