<?php

//------------------------------------------------------------------------------
// Mini Featured Member v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_randomsponsor($db)
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
			//$img_files = " ' ".$pic." ' ";
			//$img_href = "'".$url."'";
			//$first_img = "'".$pic."'";
			//$first_href = "'".$url."'";
                        $img_files = $pic;
			$img_href = "'".$url."'";
			$first_img = $pic;
			$first_href = "'".$url."'";
		}
		else{
			//$img_files .= ",'".$pic."'";
			//$img_href .= ",'".$url."'";
                           $img_files .= ",'".$pic."'";
			   $img_href .= ",'".$url."'";
		}
		
		
		}
		// output story, show the image, if no image show the title


		/*if($pic != "") {
		echo "  <center><a href=\"$url\" target=\"_new\"><img src=\"/uploadphotos/sponsors/$pic\" border=\"1\" style=\"border-color: #000000\"></a></center>\n";
		} else {
		echo "  <p align=\"center\"><a href=\"$url\" target=\"_new\">$url</a></p>\n";
		}*/
		//echo $img_href;
		echo "
			<script language='JavaScript1.1'>
			<!--
			
			//specify interval between slide (in mili seconds)
			var slidespeed=40000
			
			//specify images
			var slideimages=new Array($img_files)
			
			//specify corresponding links
			var slidelinks=new Array($img_href)
			
			var newwindow=1 //open links in new window? 1=yes, 0=no
			
			var imageholder=new Array()
			var ie=document.all
			for (i=0;i<slideimages.length;i++){
			imageholder[i]=new Image()
			imageholder[i].src='/uploadphotos/sponsors/' + slideimages[i]
			}
			
			function gotoshow(){
			if (newwindow)
			window.open(slidelinks[whichlink].replace(/\&amp;/g,'&'))
			else
			window.location=slidelinks[whichlink].replace(/\&amp;/g,'&')
			}
			
			//-->
			</script>
		
		";
		
		//echo "<center><a href=\"javascript:gotoshow()\"><img src=\"/uploadphotos/sponsors/$first_img\" name=\"slide\" border=0 style=\"filter:blendTrans(duration=3)\" ></a></center>";
                echo "<center><a href=$first_href target='_blank'><img src=\"/uploadphotos/sponsors/$first_img\" name=\"slide\" border=0 style=\"filter:blendTrans(duration=3)\" ></a></center>";
		echo "<br>\n";
		echo "  <hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"left\">\n\n";
		}
}

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_randomsponsor($db);

?>

<script language="JavaScript1.1">
	
	var whichlink=0
	var whichimage=0
	var blenddelay= document.images.slide.filters[0].duration*1000
	function slideit(){
	if (!document.images) return
	if (ie) document.images.slide.filters[0].apply()
	document.images.slide.src=imageholder[whichimage].src
	if (ie) document.images.slide.filters[0].play()
	whichlink=whichimage
	whichimage=(whichimage<slideimages.length-1)? whichimage+1 : 0
	setTimeout("slideit()",slidespeed+blenddelay)
	}
	slideit()

</script>
