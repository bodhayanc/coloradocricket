<script language="javascript">
  function gotosite(site)
  {
  if(site !="")
    {
    self.location=site;
    }
  }
</script>
<script language="JavaScript1.2">

<!--

var url = "http://www.coloradocricket.org";
var title = document.title;

function bookmark(){
if(document.all)
window.external.AddFavorite(url,title)
}

// -->

</script>
<script language="JavaScript1.2">

<!--

var url = "http://www.coloradocricket.org";
var title = document.title;

function bookmark(){
if(document.all)
window.external.AddFavorite(url,title)
}

// -->

</script>

<table width="850" border="0" cellspacing="0" cellpadding="4" bgcolor="#000000">
  <tr>
    <td valign="top" align="left" class="blackbar">&nbsp;<a href="javascript:bookmark()" class="blackbar">add to favourites</a> | <a href="http://babelfish.altavista.com/babelfish/trurl_pagecontent?lp=en_es&trurl=http%3a%2f%2fwww.coloradocricket.org%2findex.php" class="blackbar">espa�ol</a></td>
    <td valign="top" align="right" class="blackbar"><a href="http://www.coloradocricket.org/board/posting.php?mode=newtopic&f=6" class="blackbar">help</a> | <a href="http://www.coloradocricket.org/search.php" class="blackbar">search</a> | <a href="http://www.coloradocricket.org/administration/main.php" class="blackbar">admin</a> | <a href="http://www.coloradocricket.org" class="blackbar">home</a> &nbsp;</td>
</tr>
</table>

<table width="850" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td rowspan="2" valign="top"><img src="http://www.coloradocricket.org/images/logo1.gif" width="150" height="100"></td> 
    <td colspan="2" height="80" bgcolor="#030979" valign="middle" background="http://www.coloradocricket.org/images/header_cougars.gif">
      <div align="right">

    <select name=ccl_mode onChange="gotosite(this.options[this.selectedIndex].value)">
      <option>select a site</option>
      <option value="http://www.coloradocricket.org">� colorado cricket league</option>
      <option value="http://cougars.coloradocricket.org">� colorado cougars</option>
      <option value="http://tennis.coloradocricket.org">� tennis ball cricket</option>
    </select>&nbsp;&nbsp;

      </div>
    </td>
  </tr>
<?php 
  if($page == "scorecardfull" || $page == "statistics") {
?>
  <tr>
    <td colspan="2" height="20" valign="top"><img src="http://www.coloradocricket.org/images/bar_1.gif"></td>
  </tr>
<?php 
  } else { 
?>
  <tr>
    <td colspan="2" height="20" valign="top"><img src="http://www.coloradocricket.org/images/bar700_grey.gif" width="700" height="20"></td>
  </tr>
<?php
  }
?>
  <tr>
    <td width="150" valign="top" align="right" bgcolor="#030979"><img src="http://www.coloradocricket.org/images/logo2.gif" width="150" height="70">

