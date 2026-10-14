<?php $title=""; include("header.php");?>

<div align="center">
    <h2>PROFILFOTO &Auml;NDERN</h3>
</div>

<div align='center'>
    <table class='farbeTable'>
            <td align='center'>
                <form method="POST" enctype="multipart/form-data" action="bildPruefen2.php" onsubmit="return checkFoto()">
                    <table class='farbeTable'>
                        <div>
                            <br><input type="file" name="userfile"><br><br>
                        </div>
                        <div>
                            <input type="submit" value="Hochladen" name="Hochladen"><br><br>
                        </div>
                        <div>
                            <u>Bitte ber&uuml;cksichte folgende Hinweise: </u><br><br>
                            Maximale Abmessungen: 100 x 100 px<br>
                            Maximale Gr&ouml;sse: 100 kb<br><br>
                        </div>
                    </table>
                </form>
            </td>
    </table> 
</div>


<?php include("footer.php");?>