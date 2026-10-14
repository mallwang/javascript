<?php $title="Screenshots";  include '../sessionpruefung.php';
// Wenn User eingeloggt ist
if (isset($_SESSION['nickname'])){
	// Meldung und Abbruch
	echo "Diese Seite kann vom internen Bereich nicht aufgerufen werden!";
	include '../footer.php';
	exit;
}
?>
<br>
<script type="text/javascript">
                function getWindowsSize(){
                    $("#sizeX").html('W: '+$(window).width());
                    $("#sizeY").html('H: '+$(window).height());
                    var breite = $(window).width() * 0.7;
                    var hoehe = (breite/16)*9;
                    var abstand = $(window).width() * 0.15;
                    document.getElementById('screenshots').style.width = breite + 'px';
                    document.getElementById('screenshots').style.height = hoehe + 'px';
                    document.getElementById('abstand').style.marginLeft = abstand + 'px';
                    document.getElementById('abstand').style.marginRight = abstand + 'px';
                }
                $(function(){
                    getWindowsSize();
                    $(window).resize(function(){
                    getWindowsSize()
                    });
                })
            </script>

<!--<div style="position: fixed; height: 100px; width: 100px; padding: 10px; z-index: 10000;" class="bottom-right bg-color-darken fg-color-white">
    Screen size:
    <div id="sizeX"></div>
    <div id="sizeY"></div>
</div>-->

<script src="/js/carousel.js"></script>
 
<div id="abstand"  style="margin-left: 200px; margin-right: 200px;";>

    <div>
        <h4 style="margin-left: 40px;" class="fg-color-red">Hier bekommst du einen ersten Einblick in die Welt des Schankimperiums.</h4>
    </div>    
    <div id="screenshots" class="carousel span5" style="width: 800px; height: 400px;" stop="off" data-role="carousel" data-param-effect="fade" data-param-direction="left" data-param-period="4000" data-param-markers="on">
    <div class="slides">
            <div class="slide image" id="slide1" style="display: block;">
                <img src="/img/screenshots/1.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot1
                </div>
            </div>
            <!-- 
            <div class="slide image" id="slide2" style="display: block;">
                <img src="/images/screenshots/2.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot2
                </div>
            </div>
            <div class="slide image" id="slide3" style="display: block;">
                <img src="/images/screenshots/3.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot3
                </div>
            </div>
            <div class="slide image" id="slide4" style="display: block;">
                <img src="/images/screenshots/4.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot4
                </div>
            </div>
            <div class="slide image" id="slide5" style="display: block;">
                <img src="/images/screenshots/5.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot5
                </div>
            </div>
            <div class="slide image" id="slide6" style="display: block;">
                <img src="/images/screenshots/6.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot6
                </div>
            </div>
            <div class="slide image" id="slide7" style="display: block;">
                <img src="/images/screenshots/7.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot7
                </div>
            </div>
            <div class="slide image" id="slide8" style="display: block;">
                <img src="/images/screenshots/8.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot8
                </div>
            </div>
            <div class="slide image" id="slide9" style="display: block;">
                <img src="/images/screenshots/9.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot9
                </div>
            </div>
            <div class="slide image" id="slide10" style="display: block;">
                <img src="/images/screenshots/10.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot10
                </div>
            </div>
            <div class="slide image" id="slide11" style="display: block;">
                <img src="/images/screenshots/11.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot11
                </div>
            </div>
            <div class="slide image" id="slide12" style="display: block;">
                <img src="/images/screenshots/12.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot12
                </div>
            </div>
            <div class="slide image" id="slide13" style="display: block;">
                <img src="/images/screenshots/13.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot13
                </div>
            </div>
            <div class="slide image" id="slide14" style="display: block;">
                <img src="/images/screenshots/14.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot14
                </div>
            </div>
            <div class="slide image" id="slide15" style="display: block;">
                <img src="/images/screenshots/15.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot15
                </div>
            </div>
            <div class="slide image" id="slide16" style="display: block;">
                <img src="/images/screenshots/16.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot16
                </div>
            </div>
            <div class="slide image" id="slide17" style="display: block;">
                <img src="/images/screenshots/17.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot17
                </div>
            </div>
            <div class="slide image" id="slide18" style="display: block;">
                <img src="/images/screenshots/18.jpg">
                <div class="description">
                    Das ist eine Beschreibung zu Screeshot18
                </div>
            </div>
            -->
        </div>
        <!-- 
        <span class="control left fg-color-red">&lang;</span>
        <span class="control right fg-color-red">&rang;</span>
		 -->
    </div>
</div>

<?php include '../footer.php';?>