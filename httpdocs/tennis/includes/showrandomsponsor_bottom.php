<?php

//------------------------------------------------------------------------------
// Mini Featured Member v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_randomsponsor_bottom($db)
{
	global $PHP_SELF;

	$img_files = "";
	$img_href = "";
	$first_img = "";
	$first_href = "";
                        
	if (!$db->Exists("SELECT * FROM sponsors WHERE isActive=1")) {
		echo "<p align='center'>The sponsors table is being edited. Please check back shortly.</p>\n";
		return;
	} else {
		$db->QueryRow("SELECT url,picture FROM sponsors WHERE isActive=1 ORDER BY Rand() DESC ;");

		// setup variables
		//
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
            $db->BagAndTag();
		
			$url = $db->data['url'];
			$pic = $db->data['picture'];
		
		if($i == 0) {
			//$img_files = "'".$pic."'";
			//$img_href = "'".$url."'";
	                $first_img = $pic;
			$first_href = "'".$url."'";
                        $img_files = $pic;
			$img_href = "'".$url."'";
		}
		else{
			$img_files .= ",'".$pic."'";
			$img_href .= ",'".$url."'";
		}
		
		
		}
		// output story, show the image, if no image show the title


		/*if($pic != "") {
		echo "  <center><a href=\"$url\" target=\"_new\"><img src=\"http://www.coloradocricket.org/uploadphotos/sponsors/$pic\" border=\"1\" style=\"border-color: #000000\"></a></center>\n";
		} else {
		echo "  <p align=\"center\"><a href=\"$url\" target=\"_new\">$url</a></p>\n";
		}*/
		
		echo "
			<script language='JavaScript1.1'>
			<!--
			
			//specify interval between slide (in mili seconds)
			var slidespeed=40000
			
			//specify images
			var slideimagesb=new Array($img_files)
			
			//specify corresponding links
			var slidelinksb=new Array($img_href)
			
			var newwindow=1 //open links in new window? 1=yes, 0=no
			
			var imageholder1=new Array()
			var ie=document.all
			for (i=0;i<slideimagesb.length;i++){
			imageholder1[i]=new Image()
			imageholder1[i].src='http://www.coloradocricket.org/uploadphotos/sponsors/' + slideimagesb[i]
			}
			
			function gotoshowb(){
			if (newwindow)
			window.open(slidelinks[whichlink].replace(/\&amp;/g,'&'))
			else
			window.location=slidelinks[whichlink].replace(/\&amp;/g,'&')
			}
			
			//-->
			</script>
		
		";
		
             //   echo "<br><center><a href=\"javascript:gotoshowb()\"><img src=\"http://www.coloradocricket.org/uploadphotos/sponsors/$first_img\" name=\"slidebottom\" border=0 style=\"filter:blendTrans(duration=3)\"></a></center>"; 
		echo "<br><center><a href=$first_href target='_blank'><img src=\"http://www.coloradocricket.org/uploadphotos/sponsors/$first_img\" name=\"slidebottom\" border=0 style=\"filter:blendTrans(duration=3)\"></a></center>"; 		
//echo "<br><center><a href=\"javascript:gotoshowb()\"><img src=\"http://www.coloradocricket.org/uploadphotos/sponsors/$img_files\" name=\"slidebottom\" border=0 style=\"filter:blendTrans(duration=3)\" //></a></center>";
		echo "<br>\n";
		}
}

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_randomsponsor_bottom($db);

?>

<script language="JavaScript1.1">
	
	var whichlink1=0
	var whichimage1=0
	var blenddelay=(ie)? document.images.slidebottom.filters[0].duration*1000 : 0
	function slideit1(){
	if (!document.images) return
	if (ie) document.images.slidebottom.filters[0].apply()
	document.images.slidebottom.src=imageholder1[whichimage1].src
	if (ie) document.images.slidebottom.filters[0].play()
	whichlink1=whichimage1
	whichimage1=(whichimage1<slideimagesb.length-1)? whichimage1+1 : 0
	setTimeout("slideit1()",slidespeed+blenddelay)
	}
	slideit1()

</script>
