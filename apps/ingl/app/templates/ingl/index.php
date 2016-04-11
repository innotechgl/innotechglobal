<?php
// define language
$lang = array("lat" => "sr", "sr" => "sr", "en" => "en");
?>
<!DOCTYPE html>
<html lang="<?php echo $lang[$engine->get_lang()]; ?>">
<head>

    <?php
    $engine->parser->createHead();
    ?>
    <meta charset="utf-8">

    <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,latin-ext'
          rel='stylesheet' type='text/css'>
    <link href="<?php echo $engine->settings->general->site; ?>/templates/<?php echo $engine->settings->general->template; ?>/css/fonts/gotham/styles.css" media="all"
          rel="stylesheet" type="text/css">
    <link href="<?php echo $engine->settings->general->site; ?>/templates/<?php echo $engine->settings->general->template; ?>/css/fonts/futura/md/styles.css"
          media="all" rel="stylesheet" type="text/css">
    <link href="<?php echo $engine->settings->general->site; ?>/templates/<?php echo $engine->settings->general->template; ?>/css/fonts/futura/xb/styles.css"
          media="all" rel="stylesheet" type="text/css">

    <script src="<?php echo $engine->settings->general->site; ?>/templates/<?php echo $engine->settings->general->template; ?>/js/jquery-2.2.0.js"
            type="text/javascript"></script>
    <script src="<?php echo $engine->settings->general->site; ?>/templates/<?php echo $engine->settings->general->template; ?>/js/app.js"
            type="text/javascript"></script>

    <link rel="stylesheet" type="text/css"
          href="<?php echo $engine->settings->general->site; ?>/templates/<?php echo $engine->settings->general->template; ?>/css/normalize.css" media="all">
    <link rel="stylesheet" type="text/css"
          href="<?php echo $engine->settings->general->site; ?>templates/<?php echo $engine->settings->general->template; ?>/css/default.css" media="all">
    <link rel="stylesheet" media="only screen and (max-width: 980px)"
          href="<?php echo $engine->settings->general->site; ?>templates/<?php echo $engine->settings->general->template; ?>/css/tablet.css"/>
    <link rel="stylesheet" media="only screen and (max-width: 620px)"
          href="<?php echo $engine->settings->general->site; ?>templates/<?php echo $engine->settings->general->template; ?>/css/mobile.css"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel='shortcut icon' type='image/png'
          href='<?php echo $engine->settings->general->site; ?>/templates/<?php echo $engine->settings->general->template; ?>/images/icon.png'>

    <meta name="msvalidate.01" content="C77C0D544996E58458183953A60BCA39"/>
    <meta property="fb:app_id" content="<?php echo $engine->settings->fb->appID; ?>"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-538755-65', 'auto');
        ga('send', 'pageview');

    </script>

    <script>
        window.fbAsyncInit = function () {
            FB.init({
                appId: '<?php echo $engine->settings->fb->appID; ?>',
                xfbml: true,
                version: 'v2.5'
            });
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
</head>
<body>
<header>
    <div class="container">
        <a class="logo" href="<?php echo $engine->settings->general->site; ?>/<?php echo $engine->get_lang(); ?>/"></a>
        <scms:widget:menu>
    </div>
    <!-- .container -->
</header>

<section class="main-content">
    <scms:page>
</section>
<!-- .main-content -->
<footer>
    <div class="container">
        © Innotech Global <?php echo date("Y"); ?>. All Rights Reserved.
    </div><!-- .container -->
</footer>
<script
    src="<?php echo $engine->settings->general->site; ?>templates/<?php echo $engine->settings->general->template; ?>/js/cbox/jquery.colorbox-min.js"></script>
<script
    src="<?php echo $engine->settings->general->site; ?>templates/<?php echo $engine->settings->general->template; ?>/js/app.js"></script>

<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=105918806165198";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>