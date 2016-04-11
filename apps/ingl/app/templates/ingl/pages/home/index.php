<?php

$engine->parser->setOgSiteName($engine->settings->general->site);
$engine->parser->setOgDescription($engine->settings->general->description);
$engine->parser->setOgTitle($engine->settings->general->title);
$engine->parser->setOgUrl($engine->settings->general->site. $_SERVER['REQUEST_URI']);
$engine->parser->setOgType('website');
//$engine->parser->setOgImage("http://www.lovekopaonik.com/templates/kopaonik/images/logo.png");

?>
<div class="social"></div>
<!-- .social -->

<div class="pages-container">
    <div class="pages">
        <div class="page home">
            <a name="home"></a>
            <div class="banners">
                <div class="banner"></div>
                <!-- .banner -->
                <div class="banner"></div>
                <!-- .banner -->
                <div class="banner"></div>
                <!-- .banner -->
                <div class="banner"></div>
                <!-- .banner -->
            </div>
            <!-- .banners -->
            <div class="container">
                <div class="big-logo"></div>
                <!-- .big-logo -->
                <div class="titles"><span class="title">Build</span><!-- .title -->
                    <span class="title">Connect</span><!-- .connect -->
                    <span class="title">Interact</span><!-- .interact -->
                    <span class="title">Advise</span><!-- .advise -->
                </div>
                <!-- .titles -->
            </div><!-- .container -->
            <a id="move-next"></a>
        </div>
        <!-- .page -->
        <div class="page home who-are-we">
            <a name="who-are-we"></a>
            <div class="top">
                <div class="container">
                    <div class="slogan">
                        <div><span class="top-slogan">HI WE ARE,</span></div>
                        <div><span class="highlite bottom-slogan">INNOTECH GLOBAL </span><span
                                class="bottom-slogan">DEVELOPING</span></div>
                        <div><span class="small-slogan">AND DESIGN STUDIO</span></div>
                    </div>
                    <!-- .slogan -->
                </div>
                <!-- .container -->
            </div>
            <!-- .top -->
            <div class="bottom">
                <div class="container">
                    <div class="row two-col">
                        <div class="col">
                            <h3>Who are we</h3>

                            <p>Innotech Global was founded in 2015 with one goal – to become the leader in the field of business while providing our clients with high quality solutions to suit their needs. We’re a dynamic team of young professionals ready to tackle any challenge client’s ideas throw at us.
                            </p>
                        </div>
                        <div class="col">
                            <h3>What we do</h3>

                            <div>
                                <strong>BUILD</strong>
                                <p>- We design mobile applications, design and redesign websites.</p><br />
                                <strong>CONNECT</strong>
                                <p>- We connect people with our mobile phones.</p><br />
                                <strong>INTERACT</strong>
                                <p>- We offer innovative interactive solutions with our ExploreIT technology.</p><br />
                                <strong>ADVISE</strong>
                                <p>- Our business consulting services will help you get your business on a whole new level.</p>
                            </div>
                        </div>

                    </div>
                    <!-- <button class="more" type="button">READ MORE</button> -->
                </div>
                <!-- .container -->
            </div>
            <!-- .bottom -->
        </div>
        <!-- .page -->
        <div class="page home-services-and-products">
            <a name="products"></a>
            <div class="container">
                <h2>SERVICES <span class="light">&amp;</span> PRODUCTS</h2>

                <div class="service-list">
                    <ul>
                        <li><a href="#products" class="build"><span class="c"></span><span class="c"></span><span
                                    class="c"></span><span class="c"></span><span class="image-back"></span><span
                                    class="title">build</span><span class="image"></span> </a></li>
                        <li><a href="#products" class="connect"><span class="c"></span><span class="c"></span><span
                                    class="c"></span><span class="c"></span><span class="image-back"></span><span
                                    class="title">connect</span><span class="image"></span></a></li>
                        <li><a href="#products" class="interact"><span class="c"></span><span class="c"></span><span
                                    class="c"></span><span class="c"></span><span class="image-back"></span><span
                                    class="title">interact</span><span class="image"></span></a></li>
                        <li><a href="#products" class="advise"><span class="c"></span><span class="c"></span><span
                                    class="c"></span><span class="c"></span><span class="image-back"></span><span
                                    class="title">advise</span><span class="image"></span></a></li>
                    </ul>
                </div>
                <!-- .service-list -->
            </div>
            <!-- .container -->
        </div>
        <!-- .page .home-services-and-products -->
        <div class="page home-meet-the-team">
            <a name="team"></a>
            <div class="container">
                <scms:widget:team>
            </div>
            <!-- .container -->
        </div>
        <!-- .page.home-meet-the-team-->

        <div class="page home-portfolio">
            <a name="portfolio"></a>
            <div class="container">
                <scms:widget:portfolio>
            </div>
        </div>

        <!-- .page.home-portfolio -->
        <div class="page home-get-in-touch">
            <a name="contact"></a>
            <div class="container">
                <div class="row">
                    <div class="col tablet-full">
                        <div class="form-touch">
                            <h2>Get in touch</h2>

                            <p>Have a question about a project we completed,our process, or just curious about
                                what’s on tap this week? Drop us a note or give us a call; we’re happy to answer all
                                your questions.</p>

                            <div class="form-items">
                                <input type="text" name="name" placeholder="Full name">
                                <input type="text" name="email" placeholder="E-mail address">
                                <textarea placeholder="Your Message" name="message" id="touch-message"></textarea>
                                <button class="submit" type="button">Submit</button>
                            </div>
                            <!-- .form-items -->
                        </div>
                        <!-- .form -->

                        <div class="form-work">
                            <h3>Want to work with us</h3>

                            <div class="form-items">
                                <input type="text" name="name" placeholder="Full name">
                                <input type="text" name="email" placeholder="E-mail address">
                                <textarea placeholder="Your Message" name="message" id="work-message"></textarea>
                                <button type="button">Submit</button>
                            </div>
                            <!-- .form-items -->
                        </div>
                        <!-- .formWork-->
                    </div>
                    <!-- .col -->
                    <div class="col tablet-full">
                        <div class="map" id="map"></div>
                        <!-- .map -->

                        <script>
                            function initMap() {
                                var mapDiv = document.getElementById('map');
                                var map = new google.maps.Map(mapDiv, {
                                    center: {lat: 44.796681, lng: 20.466777},
                                    zoom: 15
                                });

                                var marker = new google.maps.Marker({
                                    position: {lat: 44.796681, lng: 20.466777},
                                    map: map
                                });
                            }
                            initMap();
                        </script>
                        <div class="row-address">
                            <div class="col">Skerlićeva 4,<br>
                                11000 Belgrade, Serbia
                            </div>
                            <div class="col">
                                <a id="insert-email"></a>
                            </div>
                            <!-- <div class="col"><a href="#">Get Directions </a></div>-->
                            <!-- .col -->
                        </div>
                        <!-- .address -->
                    </div>
                    <!-- .col -->
                </div>
                <!-- .row -->
            </div>
            <!-- .container -->

        </div>
        <!-- .page.home-get-in-touch -->

    </div>
    <!-- .pages -->
</div>
<!-- .pages-container -->