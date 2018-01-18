/*	sIFR v2.0.5
	Copyright 2004 - 2007 Mark Wubben and Mike Davidson. Prior contributions by Shaun Inman and Tomas Jogin.
	
	This software is licensed under the CC-GNU LGPL <http://creativecommons.org/licenses/LGPL/2.1/>
*/


if(typeof sIFR == "function" && !sIFR.UA.bIsIEMac && (!sIFR.UA.bIsWebKit || sIFR.UA.nWebKitVersion >= 100)){
	sIFR.setup();
	sIFR.replaceElement(".callout", named({sFlashSrc: "rockford_bold.swf", cColor: "#000", sBgColor: "#ffa415", sWmode: "transparent"}));
};


