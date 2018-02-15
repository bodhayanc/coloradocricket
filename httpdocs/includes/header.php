<?php

//------------------------------------------------------------------------------
// Site Control v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

if(session_id() == '') {
	session_start();
}

$currentSID = session_id();


?><link rel="shortcut icon" href="/images/favicon.ico" />
<script language="javascript">
  function gotosite(site)
  {
  if(site !="")
    {
    self.location=site;
    }
  }
</script>
<script LANGUAGE="JavaScript">
<!--
function confirmSubmit()
{
var agree=confirm("Are you sure you wish to submit this form? If yes, click yes. If you need to make a change, click no.");
if (agree)
    return true ;
else
    return false ;
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

<script language="javascript" src="/includes/javascript/sorttable.js"></script>

<table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#000000">
  <tr>
    <td valign="top" align="left" class="blackbar">&nbsp;<a href="javascript:bookmark()" class="blackbar">add to favourites</a> | <a href="http://babelfish.altavista.com/babelfish/trurl_pagecontent?lp=en_es&trurl=http%3a%2f%2fwww.coloradocricket.org%2findex.php" class="blackbar">espa&ntilde;ol</a></td>
    <td valign="top" align="right" class="blackbar"><a href="/board/posting.php?mode=newtopic&f=6" class="blackbar">help</a> | <a href="/search.php" class="blackbar">search</a> | <a href="/administration/main.php?SID=<?php echo $currentSID ?>" class="blackbar">admin</a> | <a href="/" class="blackbar">home</a> &nbsp;</td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td rowspan="2" valign="top" bgcolor="#030979"><img src="/images/logo1.gif" width="150" height="100"></td> 
    <td colspan="2" height="80" bgcolor="#030979" valign="middle" background="/images/header.gif">
      <div align="right">

    <select name=ccl_mode onChange="gotosite(this.options[this.selectedIndex].value)">
      <option>select a site</option>
      <option value="http://www.coloradocricket.org">&#xbb; colorado cricket league</option>
      <option value="http://cougars.coloradocricket.org">&#xbb; colorado cougars</option>
      <option value="http://tennis.coloradocricket.org">&#xbb; tennis ball cricket</option>
    </select>&nbsp;&nbsp;

      </div>
    </td>
  </tr>
<?php if(isset($page) && ($page == "scorecardfull")) { ?>
  <tr>
    <td colspan="2" height="20" valign="top"><img src="/images/bar_1.gif" width="700" height="20"></td>
  </tr>
<?php } else { ?>
  <tr>
    <td height="20" valign="top"><img src="/images/bar700_grey_small.gif" width="100%" height="20"></td>
    <td width="400" bgcolor="#D0C7C0" height="20" valign="top"></td>
    <!-- <td colspan="2" height="20" valign="top"><img src="/images/bar700_grey.gif" width="700" height="20"></td> -->
  </tr>
<?php } ?>
  <tr>
    <td width="180" valign="top" align="left" bgcolor="#030979"><img src="/images/logo2.gif" width="150" height="70"> 

