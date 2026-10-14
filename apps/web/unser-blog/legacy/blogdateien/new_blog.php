<?php $title="Neuer Blogeintrag";  include ("header.php");?>

<div align="center">
	<h2 align="center">Blogeintrag verfassen</h2>
	<a href="blog.php?beginn=0">Zur&uuml;ck zur &Uuml;bersicht</a><br><br>
</div>

<form name="Nachricht" action="send_blog.php" method="post" onsubmit='return checkNeuerBlog()'>
	<table class="farbeTable">
	<tr>
	    <td>
                <input class='metroSchrift 12' placeholder="Betreff" name="betreff" size="50" maxlength="50">
            </td>
	</tr>
        <tr>
            <td>
                <textarea class='metroSchrift 11' cols="80%" rows="30%" name="text"></textarea>
            </td>
        </tr>
        <tr>
		<td align="center"><input name="submit" type="submit"></td>
	</tr>
	</table>
</form>

<?php include("footer.php"); ?>