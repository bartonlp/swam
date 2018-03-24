<!--
// JavaScript for 'accounts.php'

// Make this info available all of the time.
// We can use Browser to see what and who is what.

// THIS NEEDS TO CHANGE WHEN A NEW VERSION IS RELEASED!!!
var versionNumber = "1.9.0.1";
var verNum = versionNumber.split(".");
// ******************************************************

var win;

var Browser = new Object();

Browser.version = parseInt(navigator.appVersion);
Browser.os = navigator.platform;
Browser.product = navigator.product;
Browser.isGecko = false;
Browser.isNetscape = false;
Browser.isMicrosoft = false;
Browser.isOpera = false;
Browser.isGoogle = false;
Browser.cookieEnabled = navigator.cookieEnabled;

var info;

// Look for Gecko Revision number

if((info = navigator.userAgent.match(/rv:((\d+(\.?|\S?\d?))+)\)/)) != null) {
	//alert(info.join("\n"));
	Browser.GeckoRv = info[1];
}

if((info = navigator.userAgent.match(/MSIE\s*(\d+)\.(\d*)/)) != null) {
	//alert(info.join("\n"));
	Browser.MsieMajorVersion = info[1];
	Browser.MsieMinorVersion = info[2];
}

if(navigator.appName.indexOf("Netscape") != -1) {
	Browser.isNetscape = true;
}

if(navigator.appName.indexOf("Microsoft") != -1) {
	Browser.isMicrosoft = true;
}

if(navigator.appName.indexOf("Opera") != -1) {
	Browser.isOpera = true;
}

// alert("agent="+navigator.userAgent);

if(navigator.userAgent.indexOf("Gecko") != -1) {
	Browser.isGecko = true;
	Browser.GeckoVersion = navigator.productSub;
}

// new Google Chrome
if(navigator.userAgent.indexOf("Chrome") != -1) {
  Browser.isGoogle = true;
}

// ---------------------------------------------------------------------------

function CheckOs() {
	var flag = arguments.length;
	var isOk = false;
	var msgOk = "";
	
	if(flag && win && !win.closed) {
		win.focus();
		return;
	}

	var msg = "";

  
	if(Browser.version < 5) {
		// Browser versions before 5
		
		if(Browser.isMicrosoft) {
			if(Browser.MsieMajorVersion < 6) {
				// MSIE before 6
				msg += "<h1 align=center>Your are using an old Microsoft Internet Explorer. "+
							 "Update to Mozilla/Firefox</h1>"+
							 "<p>You can get the most up to date version of Mozilla/Firefox at "+
							 "<a href='http://www.mozilla.org'>www.mozilla.org</a></p>";
			} else {
				// MSIE 6 or greater -- Still Crap
				msg +="<h2 align=center>Your are using Microsoft Internet Explorer version " +
							Browser.MsieMajorVersion + "." + Browser.MsieMinorVersion+ "</h2>"+
							"<p>Microsoft MSIE version 6 and above may partially work "+
							"however IE does not support many of the W3c CSS and HTML 4.0.1 "+
							"standards and is very  out of date. "+
							"This site has been tested with Mozill/Firefox. "+
							"You can get the most up to date version of Mozilla/Firefox at "+
							"<a href='http://www.mozilla.org'>www.mozilla.org</a></p>";
			}
		} else if(Browser.isNetscape || Browser.isGecko) {
			// Old Gecko's or Netscape's
			msg += "<h2 align='center'>Old Netscape version " + Browser.version + "</h2>" +
						 "Many features used by this site are <u>not</u> supported by old "+
						 "Netscape versions. "+
						 "You can get the most up to date version of Mozilla/Firefox at "+
						 "<a href='http://www.mozilla.org'>www.mozilla.org</a></p>";
		} else {
			// Who knows what?
			msg += "<h2 align='center'>You are using an old '" + navigator.appName + "' version " + Browser.version + "</h2>" +
						 "This browser may not support HTML or CSS features used on this site. "+
						 "You can get the most up to date version of Mozilla/Firefox at "+
						 "<a href='http://www.mozilla.org'>www.mozilla.org</a></p>";
		}
  } else if(Browser.isNetscape) {
		// New browsers version 5 or better
		if(Browser.isGecko) {
			// If Gecko then make sure it is pretty new

      if(Browser.GeckoRv) {

        var grev = Browser.GeckoRv.split(".");
     

        if(grev < verNum) {
          // Not the latest but probably ok
          msg += "<h2 align=center>You have Netscape/Gecko rev "
                 + Browser.GeckoRv +" but your revision is less then " + versionNumber + "</h2>"+
                 "<p>You should think about upgrading to " + versionNumber + " or later. "+
                 "You can get the most up to date version of Mozilla/Firefox at "+
                 "<a href='http://www.mozilla.org'>www.mozilla.org</a></p>";
        } else {
          // This is up to date as of 3/5/2005. This should work ok
          msgOk = "<h2 align='center'>You have " + Browser.product +
                  ". This site was tested for this browser.</h2>";
          isOk = true;
        }
      } else {
        msg += "<h2 align='center'>You have a Netscape/Gecko without a Gecko 'rv'</h2>";
        if(Browser.isGoogle) {
          msg += "<p>It looks like you are running Google Chrome which will probably work OK</p>";
          msg += "<p>User Agent reports: " + navigator.userAgent+ "</p>";
        }
      }
		}
	} else if(Browser.isOpera && Browser.version < 7) {
		// Opera less than version 7
		msg += "<h2 align=center>Your have Opera (" +Browser.version+") but not the latest version.</h2>"+
					 "<p>Note however that even the latest version does not support key features we use.<br>"+
					 "You can get the most up to date version of Mozilla/Firefox at "+
					 "<a href='http://www.mozilla.org'>www.mozilla.org</a></p>";
	} else {
		// Latest version but still may not work.
		msg +=  "<h2 align=center>You are using '"+navigator.appName+ "' " +navigator.appVersion+ "</h2>"+
						"<p>This site has only been tested with Mozilla/Firefox 1.0 most other browsers "+
						"lack some features that this site uses. "+
						"You can get the most up to date version of Mozilla/Firefox at "+
						"<a href='http://www.mozilla.org'>www.mozilla.org</a></p>";
	}
		
	// Check to make sure the user has cookies enables! This site just
	// will not work without cookies!
	
	if(!Browser.cookieEnabled) {
		msg += "<h1 align=center>You <u>MUST ENABLE</u> the use of 'cookies' for this site</h1>";
		
		if(Browser.isMicrosoft) {
			msg += "<p>To enable 'cookies' for MSIE 6.0 or newer goto the menu "+
						 "'Tools | Internet Options...' "+
						 "then select 'Privacy' tab "+
						 "at the top of the dialog box. You can select anything except 'High' with "+
						 "the slider.</p>";

		} else if(Browser.isGecko) {
			msg += "<p>To enable 'cookies' for Mozilla goto the menu 'Edit | Preferences...' "+
						 "then select 'Privacy &amp; Security | Cookies' from the 'Catagory' window "+
						 "at the left. You can select either 'Enable cookies for the originating "+
						 "web site only' or 'Enable all cookies'. If you select 'originating "+
						 "web site only' then third party trackers will not work (which is "+
						 "probably what you want).</p>";
		} else {
			msg += "<p>You are using "+ navigator.appName + "<br>agent " +
						 navigator.userAgent +
						 "<br>I am sorry but I don't know how to enable 'cookies' on " +
						 "this browser. Try looking under your 'Preferences' or 'Internet Settings'. "+
						 "However, you should really upgrade your browser to Mozilla " +
						 versionNumber +" or newer!</p>";
		}
	}

	// The screen should be at least 600x800 or stuff will not fit
	// horizontaly
	
	if((screen.height) < 500 || (screen.width < 750)) {
		msg += "<p>Your screen resolution is less than 800 wide and 600 high. This site is best "+
					 "viewed with a resolution of at least 800x600</p>";
	}

	// Flag is not set unless this is called from the button "Check the
	// Features of your Browser".

	// alert("msg="+msg+" isGecko="+Browser.isGecko+" GeckoRv=" +Browser.GeckoRv);
	
	if(flag || !Browser.isGecko || (Browser.isGecko &&
																	(grev[0] < verNum[0] ||
																	 grev[1] < verNum[1] ||
																	 grev[2] < verNum[2]))) {
		msg += CheckFeatures();
	}

	if(msg) {
		var x, y, features;
		x = (screen.height /2) - 150;
		y = (screen.width /2) - 300;
		
		features = "height=400,width=600,scrollbars,";		
		
		if(Browser.isNetscape) {
			features += "screenX="+x+",screenY="+y;
		} else {
			features += "left="+x+",top="+y;
		}

		if(isOk) {
			msg = msgOk + msg;
		}
		
		msg += "<hr><p><input type=button value='Close Window' "+
					 "onclick='window.close(); window.opener.focus()'></p>";

		win = window.open("", "", features);
		win.document.write(msg);
		win.document.close();
		win.focus();

		if(!flag) {
			setTimeout(GiveBackFocus, 10000); // pop it to the back after 10 sec
			setTimeout(ClosePopup, 60000);		// close it after 1 min
		}
	}
}

// ---------------------------------------------------------------------------
// Two helpers for the pop up window we create in CheckOs

// Also ClosePopup is on the <body onunload

function ClosePopup() {
	//alert("close");
	if(win) {
		//alert("win");
		if(!win.closed) {
			//alert("win.close");
			win.close();
		}
	}
	return true;
}

function GiveBackFocus() {
	//alert("give back");
	window.focus();
}

// ---------------------------------------------------------------------------
// Check for needed features

function CheckFeatures() {
	var msg = "";

	// We need getElementById(), getElementsByName() and
	// getElementsByTagName()

	if(!document.getElementById || !document.getElementsByTagName
		 || !document.getElementsByName) {
		msg += "<li><b>Your browser does not support basic DOM 1! It just won't work.</b></li>";
		return msg;
	}

	if(!(document.createTreeWalker && document.createNodeIterator)) {
		msg += "<li>Your browser does not support DOM Traversal so a lot of things won't work.</li>";
	}

	if(!window.NodeFilter) {
		msg += "<li>Your browser does not support NodeFilter constants. We may be able to work around it.</li>";

		NodeFilter = {
FILTER_ACCEPT : 1,
FILTER_REJECT : 2,
FITLER_SKIP : 3,

SHOW_ALL : 0xffffff,
SHOW_ELEMENT : 1,
SHOW_ATTRIBUTE : 2,
SHOW_TEXT : 4,
SHOW_CDATA_SECTION : 8,
SHOW_ENTITY_REFERENCE : 0x10,
SHOW_ENTITY : 0x20,
SHOW_PROCESSIUNG_INSTRUCTION : 0x40,
SHOW_COMMENT : 0x80,
SHOW_DOCUMENT : 0x100,
SHOW_DOCUMENT_TYPE : 0x200,
SHOW_DOCUMENT_FRAGMENT : 0x400,
SHOW_NOTATION : 0x800
		}
	}

	if(!window.Node) {
		msg += "<li>Your browser does not support the Node constants. We may be able to work around it.</li>";

		Node = {
ELEMENT_NODE : 1,
ATTRIBUTE_NODE : 2,
TEXT_NODE : 3,
CDATA_SECTION_NODE : 4,
ENTITY_REFERENCE_NODE : 5,
ENTITY_NODE : 6,
PROCESSING_INSTRUCTION_NODE : 7,
COMMENT_NODE : 8,
DOCUMENT_NODE : 9,
DOCUMENT_TYPE_NODE : 10,
DOCUMENT_FRAGMENT_NODE : 11,
NOTATION_NODE : 12
		}
	}

	var features = "";
	var nofeatures = "";

	if(document.implementation && document.implementation.hasFeature) {
		msg += "<li>Your browser supports 'document.implementation.hasFeature' and reports:</li>";

		var feature = new Array('Core','HTML','XML','StyleSheets','CSS','CSS2','Events','UIEvents','MouseEvents',
														'HTMLEvents','MutationEvents','Range','Traversal','Views');

		var ver = new Array('1.0', '2.0');

		for(var i=0; i < feature.length; ++i) {
			if(document.implementation.hasFeature(feature[i], "")) {
				for(var j=0; j < ver.length; ++j) {
					if(document.implementation.hasFeature(feature[i], ver[j])) {
						var feat = feature[i] + (ver[j] ? " version "+ver[j] :"");
						features += "<li>Feature '"+feat+"' supported</li>";
					}
				}
			} else {
				nofeatures += "<li>Feature " + feature[i]+ " <span style='color: red'>NOT SUPPORTED</span></li>";
			}
		}
		if(features) {
			msg += "<ul>" + features + "</ul>";
		}
		if(nofeatures) {
			msg += "<ul>" + nofeatures + "</ul>";
		}
	} else {
		msg += "<li>Your browser does not support 'document.implementation'.</li>";
	}

	return msg;
}

// ---------------------------------------------------------------------------

function ShowAll() {
	var browser = "Navigator:<br>\n";

	for(var propname in navigator) {
		browser += propname + ": " + navigator[propname] + "<br>\n";
	}
	browser += "<br>Browser:<br>\n";
	
	for(propname in Browser) {
		browser += propname + ": " + Browser[propname] + "<br>\n";
	}
	var x, y, features;
	x = (screen.height /2) - 150;
	y = (screen.width /2) - 300;

	features = "height=400,width=600,scrollbars,";		

	if(Browser.isNetscape) {
		features += "screenX="+x+",screenY="+y;
	} else {
		features += "left="+x+",top="+y;
	}

	var msg = browser;

	msg += "<hr><p align=center><input type=button value='Close Window' "+
				 "onclick='window.close(); window.opener.focus()'></p>";

	win = window.open("", "", features);
	win.document.write(msg);
	win.document.close();
	win.focus();
}

// ---------------------------------------------------------------------------
// Create synthetic event

function TestEvent() {

	if(document.createEvent) {
		var e = document.createEvent("MouseEvents");
		e.initMouseEvent("mousetest", true, false, window, 1,
										 0, 0, 0,0,
										 false, false, false, false,
										 0, null);
		document.addEventListener("mousetest", Testmouse, true);
		document.dispatchEvent(e);
	}
}

function Testmouse(e) {
	alert("e=" +e.type);
}

// ---------------------------------------------------------------------------

function DoOsCheck() {
	var docref = document.referrer;

	if((docref.search(/.*\/index.php/) != -1) ||
		 (docref.search(/.*\/$/) != -1)) {
		// NO ARGS to CheckOs so don't show details
		CheckOs();
	}
}

// ---------------------------------------------------------------------------
// link to listsessionschool.php and set showwhat correctly

function ListSessionBySchool() {
	// find the radio button showwaht
	
	var showwhat = document.getElementsByName("showwhat");

	// There are ART, DANCE, KARATE and maybe more
	// so loop through and find the one that is checked
	
	for(i=0; i < showwhat.length; ++i) {
		var x = showwhat[i];

		if(x.checked) {
			document.location = "listsessionschool.php?showwhat=" + x.value;
			return;
		}
	}
	document.location = "listsessionschool.php";
}

// -->
