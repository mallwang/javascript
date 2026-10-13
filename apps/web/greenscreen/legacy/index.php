<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>evosoft infoscreen</title>
        <!-- CSS laden -->
        <link href="css/foundation.min.css" rel="stylesheet" type="text/css"/>
        <link href="slick/slick.css" rel="stylesheet" type="text/css"/>
        <link href="slick/slick-theme.css" rel="stylesheet" type="text/css"/>
        <!-- Google Maps API laden -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
        <!-- ToDo: in eigenes Stylesheet auslagern (z.B. style.css) -->
        <style>
            header {
                background-color:#33CC33;
                color:white;
                text-align:center;
                padding:5px;	 
            }
            footer {
                background-color:#33CC33;
                color:white;
                clear:both;
                text-align:center;
                padding:5px;	 	 
            }
            .row {
                max-width:100%;	 	 
            }
            html, body {
                height: 100%;
                margin: 0px;
                padding: 0px;
                background: #ffffff;
            }
            .maps {
                height: 100%;
                width: 700px;
                height: 500px;
            }
            green{
                color: green;
            }
            red{
                color: red;
            }
        </style>
    </head>
    <body>
        <!--
          <header>
                      <h1><img src="http://www.evosoft.com/sites/all/themes/evotheme/logo.png" alt="evosoft.com"></h1>
              </header>
              <br>
        -->
        <div class="row panel">
            <div align="right" class="large-12 columns" id="uhrzeit"></div>
        </div>

        <hr/>


        <div class="row">
            <div class="large-3 columns">
                <div class="panel">

                    <div align="center" class="autoplay_wetter">
                        <div>
                            <div id="wettercom-button-7d66275330966b05d1ecefc980ec3658">
                                <link id="wettercom-css" href="http://www.wetter.com/css/woys3.1.css?108602,7d66275330966b05d1ecefc980ec3658" rel="stylesheet" type="text/css" />
                                <div id="wettercom-title">
                                    <a id="wettercom-citylink" href="http://www.wetter.com/wetter_aktuell/wettervorhersage/3_tagesvorhersage/?id=DE0007131104" title="Wetter Marienberg" target="_blank">Wetter Marienberg</a>
                                </div>
                                <div id="wettercom-woys-7d66275330966b05d1ecefc980ec3658"></div>
                                <div id="wettercom-search">
                                    <form id ="wettercom-form" method="get" action="http://www.wetter.com/suche" target="_blank">
                                        <div id="wettercom-form-label">
                                            <label for="wettercom-form-city">Suche</label>
                                        </div>
                                        <div id="wettercom-form-input">
                                            <input id="wettercom-form-city" name="search" type="text" value="PLZ oder Stadt" onfocus="this.value = ''" />
                                            <button id="wettercom-form-submit" type="submit"><img src="http://www.wetter.com/img/websitetools/woys/submit.gif" alt="submit" /></button>
                                        </div>
                                    </form>
                                </div>
                                <div id="wettercom-brand">
                                    <a id="wettercom-link" href="http://www.wetter.com" target="_blank">
                                        <span id="wettercom-more">mehr Wetter auf </span>
                                        <img id="wettercom-logo" src="http://www.wetter.com/img/websitetools/woys/logo.png" alt="wetter.com" title="wetter.com" />
                                    </a>
                                </div>  <div id="wettercom-clear">
                                    <script type="text/javascript" src="http://www.wetter.com/js/woys3.1.js?108602,7d66275330966b05d1ecefc980ec3658"></script>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="wettercom-button-3c4c44af06679e9afe0fe9c8fc2a11e6">
                                <link id="wettercom-css" href="http://www.wetter.com/css/woys3.1.css?108604,3c4c44af06679e9afe0fe9c8fc2a11e6" rel="stylesheet" type="text/css" />
                                <div id="wettercom-title">
                                    <a id="wettercom-citylink" href="http://www.wetter.com/wetter_aktuell/wettervorhersage/3_tagesvorhersage/?id=HU0BU0001" title="Wetter Budapest" target="_blank">Wetter Budapest</a>
                                </div>
                                <div id="wettercom-woys-3c4c44af06679e9afe0fe9c8fc2a11e6"></div>
                                <div id="wettercom-search">
                                    <form id ="wettercom-form" method="get" action="http://www.wetter.com/suche" target="_blank">
                                        <div id="wettercom-form-label">
                                            <label for="wettercom-form-city">Suche</label>
                                        </div>
                                        <div id="wettercom-form-input">
                                            <input id="wettercom-form-city" name="search" type="text" value="PLZ oder Stadt" onfocus="this.value = ''" />
                                            <button id="wettercom-form-submit" type="submit"><img src="http://www.wetter.com/img/websitetools/woys/submit.gif" alt="submit" /></button>
                                        </div>
                                    </form>
                                </div>
                                <div id="wettercom-brand">
                                    <a id="wettercom-link" href="http://www.wetter.com" target="_blank">
                                        <span id="wettercom-more">mehr Wetter auf </span>
                                        <img id="wettercom-logo" src="http://www.wetter.com/img/websitetools/woys/logo.png" alt="wetter.com" title="wetter.com" />
                                    </a>
                                </div>  <div id="wettercom-clear">
                                    <script type="text/javascript" src="http://www.wetter.com/js/woys3.1.js?108604,3c4c44af06679e9afe0fe9c8fc2a11e6"></script>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="wettercom-button-d435c87cf1e1be2142ae07a4f5bce186">
                                <link id="wettercom-css" href="http://www.wetter.com/css/woys3.1.css?108605,d435c87cf1e1be2142ae07a4f5bce186" rel="stylesheet" type="text/css" />
                                <div id="wettercom-title">
                                    <a id="wettercom-citylink" href="http://www.wetter.com/wetter_aktuell/wettervorhersage/3_tagesvorhersage/?id=RO0681290" title="Wetter Klausenburg" target="_blank">Wetter Klausenburg</a>
                                </div>
                                <div id="wettercom-woys-d435c87cf1e1be2142ae07a4f5bce186"></div>
                                <div id="wettercom-search">
                                    <form id ="wettercom-form" method="get" action="http://www.wetter.com/suche" target="_blank">
                                        <div id="wettercom-form-label">
                                            <label for="wettercom-form-city">Suche</label>
                                        </div>
                                        <div id="wettercom-form-input">
                                            <input id="wettercom-form-city" name="search" type="text" value="PLZ oder Stadt" onfocus="this.value = ''" />
                                            <button id="wettercom-form-submit" type="submit"><img src="http://www.wetter.com/img/websitetools/woys/submit.gif" alt="submit" /></button>
                                        </div>
                                    </form>
                                </div>
                                <div id="wettercom-brand">
                                    <a id="wettercom-link" href="http://www.wetter.com" target="_blank">
                                        <span id="wettercom-more">mehr Wetter auf </span>
                                        <img id="wettercom-logo" src="http://www.wetter.com/img/websitetools/woys/logo.png" alt="wetter.com" title="wetter.com" />
                                    </a>
                                </div>  <div id="wettercom-clear">
                                    <script type="text/javascript" src="http://www.wetter.com/js/woys3.1.js?108605,d435c87cf1e1be2142ae07a4f5bce186"></script>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <br>
                <div class="panel">
                    <div align="center" id="oeffi"></div>
                </div>
            </div>

            <div align="center" class="large-5 columns">
                <div class="panel">
                    <div class="autoplay_citymap">
                        <div>
                            <div class="maps" align="center" id="map-canvas1"></div>
                        </div>
                        <div>
                            <div class="maps" align="center" id="map-canvas2"></div>
                        </div>
                        <div>
                            <div class="maps" align="center" id="map-canvas3"></div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div id="stauinfo"></div>
                </div>
            </div>

            <div class="large-4 columns">
                <div class="panel">
                    <div id="chart"><img src="http://chart.finance.yahoo.com/z?s=SIE.DE&t=1y&q=l&l=on&z=l&region=DE&lang=de-DE" /></div>
                </div>
                <div align="center" class="panel">
                    <div class="autoplay_news">
                        <div>
                            <h4>Politik</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=politik&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                        <div>
                            <h4>Wirtschaft</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=wirtschaft&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                        <div>
                            <h4>Wissen</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=wissen&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                        <div>
                            <h4>Gesundheit</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=gesundheit&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                        <div>
                            <h4>Kultur</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=kultur&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                        <div>
                            <h4>Sport</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=sport&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                        <div>
                            <h4>Computer</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=computer&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                        <div>
                            <h4>Reise</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=reise&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                        <div>
                            <h4>Spezial</h4>
                            <iframe name="news" src="http://www.nachrichten.de/widget.html?feed=special&amount=4&teasers=true&thumbnails=true&width=562&height=599" frameBorder="0" style="border: none; width: 562px; height: 599px; background-color: #ffffff;"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div align="right" class="large-12 columns panel">
                    <h4><marquee st><div id="stocks"><?php include 'getStocks.php'; ?></div></marquee></h4>
                </div>
            </div>

            <script src="js/vendor/jquery.js" type="text/javascript"></script>
            <script src="slick/slick.min.js" type="text/javascript"></script>
            <script src="js/vendor/modernizr.js" type="text/javascript"></script>
            <script src="js/foundation.min.js" type="text/javascript"></script>
            <script src="js/foundation/foundation.orbit.js" type="text/javascript"></script>
            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    // Karten
                                                    var map1, map2, map3;
                                                    var trafficLayer1, trafficLayer2, trafficLayer3;

                                                    // Wechsel der Wetterkarten
                                                    $('.autoplay_wetter').slick({
                                                        draggable: false,
                                                        speed: 500,
                                                        fade: true,
                                                        arrows: false,
                                                        slidesToShow: 1,
                                                        slidesToScroll: 1,
                                                        autoplay: true,
                                                        autoplaySpeed: 10000
                                                    });
                                                    // Wechsel der Karten
                                                    $('.autoplay_citymap').slick({
                                                        draggable: false,
                                                        speed: 500,
                                                        fade: true,
                                                        arrows: false,
                                                        slidesToShow: 1,
                                                        slidesToScroll: 1,
                                                        autoplay: true,
                                                        autoplaySpeed: 10000
                                                    });
                                                    $('.autoplay_news').slick({
                                                        draggable: false,
                                                        speed: 500,
                                                        fade: true,
                                                        arrows: false,
                                                        slidesToShow: 1,
                                                        slidesToScroll: 1,
                                                        autoplay: true,
                                                        autoplaySpeed: 20000
                                                    });
                                                });

                                                // Initialisieren der Karten
                                                function initialize() {
                                                    // Erste Karte
                                                    var myLatlng1 = new google.maps.LatLng(49.4481203,11.0797658);
                                                    var mapOptions1 = {
                                                        zoom: 13,
                                                        center: myLatlng1
                                                    }
                                                    map1 = new google.maps.Map(document.getElementById('map-canvas1'), mapOptions1);
                                                    trafficLayer1 = new google.maps.TrafficLayer();
                                                    trafficLayer1.setMap(map1);
                                                    // Zweite Karte
                                                    var myLatlng2 = new google.maps.LatLng(49.4481203,11.0797658);
                                                    var mapOptions2 = {
                                                        zoom: 12,
                                                        center: myLatlng2
                                                    }
                                                    map2 = new google.maps.Map(document.getElementById('map-canvas2'), mapOptions2);
                                                    trafficLayer2 = new google.maps.TrafficLayer();
                                                    trafficLayer2.setMap(map2);
                                                    // Dritte Karte
                                                    var myLatlng3 = new google.maps.LatLng(49.4481203,11.0797658);
                                                    var mapOptions3 = {
                                                        zoom: 11,
                                                        center: myLatlng3
                                                    }
                                                    map3 = new google.maps.Map(document.getElementById('map-canvas3'), mapOptions3);
                                                    trafficLayer3 = new google.maps.TrafficLayer();
                                                    trafficLayer3.setMap(map3);
                                                    
                                                    // Intervall für Karte aktualisieren (jede Minute)
                                                    var intervallKarte = setInterval(function () {
                                                        reloadMap()
                                                    }, 60000);
                                                }
                                                // Laden der Karten
                                                google.maps.event.addDomListener(window, 'load', initialize);

                                                // Funktion zum Berechnen der aktuellen Uhrzeit
                                                function reloadUhrzeit() {
                                                    var container = document.getElementById("uhrzeit");

                                                    var now = new Date();

                                                    var d = now.getDay();
                                                    var d1 = "";
                                                    switch (d) {
                                                        case 0:
                                                            d1 = "Sonntag";
                                                            break;
                                                        case 1:
                                                            d1 = "Montag";
                                                            break;
                                                        case 2:
                                                            d1 = "Dienstag";
                                                            break;
                                                        case 3:
                                                            d1 = "Mittwoch";
                                                            break;
                                                        case 4:
                                                            d1 = "Donnerstag";
                                                            break;
                                                        case 5:
                                                            d1 = "Freitag";
                                                            break;
                                                        case 6:
                                                            d1 = "Samstag";
                                                            break;
                                                    }

                                                    var dt = now.getDate();
                                                    if (dt < 10) {
                                                        dt = "0" + dt;
                                                    }

                                                    var mo = now.getMonth();
                                                    mo += 1;
                                                    if (mo < 10) {
                                                        mo = "0" + mo;
                                                    }

                                                    var y = now.getFullYear();

                                                    var h = now.getHours();
                                                    if (h < 10) {
                                                        h = "0" + h;
                                                    }
                                                    var m = now.getMinutes();
                                                    if (m < 10) {
                                                        m = "0" + m;
                                                    }

                                                    container.innerHTML = "<h4>" + d1 + ", " + dt + "." + mo + "." + y + " " + h + ":" + m + "</h4>";
                                                }


                                                // Funktion zum Nachladen der Stauinfo
                                                function requestStauinfo() {
                                                    var request = $.ajax({
                                                        url: "getStauinfo.php",
                                                        type: "GET",
                                                        dataType: "html"
                                                    });

                                                    request.done(function (msg) {
                                                        document.getElementById("stauinfo").innerHTML = msg;
                                                    });

                                                    request.fail(function (jqXHR, textStatus) {
                                                        var err = eval("(" + xhr.responseText + ")");
                                                        alert("Request failed: " + err.Message);
                                                    });
                                                }

                                                // Funktion zum Nachladen der Stauinfo
                                                function reloadOeffi() {
                                                    var request = $.ajax({
                                                        url: "getOeffi.php",
                                                        type: "GET",
                                                        dataType: "html"
                                                    });

                                                    request.done(function (msg) {
                                                        document.getElementById("oeffi").innerHTML = msg;
                                                    });

                                                    request.fail(function (jqXHR, textStatus) {
                                                        var err = eval("(" + xhr.responseText + ")");
                                                        alert("Request failed: " + err.Message);
                                                    });
                                                }

                                                // Funktion zum Nachladen der News
                                                function reloadNews() {
                                                    var news = document.getElementsByName("news");
                                                    var i;
                                                    for (i = 0; i < news.length; i++) {
                                                        news[i].src += '';
                                                    }
                                                }

                                                // Funktion zum Nachladen der Aktieninfos
                                                function requestStocks() {
                                                    var request = $.ajax({
                                                        url: "getStocks.php",
                                                        type: "GET",
                                                        dataType: "html"
                                                    });

                                                    request.done(function (msg) {
                                                        document.getElementById("stocks").innerHTML = msg;
                                                    });

                                                    request.fail(function (jqXHR, textStatus) {
                                                        var err = eval("(" + xhr.responseText + ")");
                                                        alert("Request failed: " + err.Message);
                                                    });
                                                }

                                                // Funktion zum Nachladen des Charts
                                                function reloadChart() {
                                                    document.getElementById("chart").innerHTML = '<img src="http://chart.finance.yahoo.com/z?s=SIE.DE&t=1y&q=l&l=on&z=l&region=DE&lang=de-DE" />';
                                                }

                                                // Funktion zum Nachladen der Karte
                                                function reloadMap() {
                                                    trafficLayer1.setMap(null);
                                                    trafficLayer2.setMap(null);
                                                    trafficLayer3.setMap(null);
                                                    trafficLayer1.setMap(map1);
                                                    trafficLayer2.setMap(map2);
                                                    trafficLayer3.setMap(map3);
                                                }

                                                // Intervall für Uhrzeit setzen (jede Minute)
                                                var intervallUhrzeit = setInterval(function () {
                                                    reloadUhrzeit()
                                                }, 60000);
                                                // Intervall für News (jede Minute)
                                                var intervallNews = setInterval(function () {
                                                    reloadNews()
                                                }, 60000);
                                                // Intervall für Stauinfo setzen (jede Minute)
                                                var intervallStauinfo = setInterval(function () {
                                                    requestStauinfo()
                                                }, 60000);
                                                // Intervall für Aktien setzen (jede Minute)
                                                var intervallAktien = setInterval(function () {
                                                    requestStocks()
                                                }, 60000);
                                                // Intervall für Chart (jede Minute)
                                                var intervallChart = setInterval(function () {
                                                    reloadChart()
                                                }, 60000);
                                                // Intervall für Oeffi (jede Minute)
                                                var intervallChart = setInterval(function () {
                                                    reloadOeffi()
                                                }, 60000);

                                                // Erstmaliges Starten
                                                reloadUhrzeit();
                                                requestStauinfo();
                                                requestStocks();
                                                reloadOeffi();
            </script>

            <!--
    <footer>
                    Copyright © evosoft.com
            </footer>
            -->
    </body>
</html>
