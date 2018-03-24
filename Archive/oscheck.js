<!--
// JavaScript to check user's OS
// BLP Changes 3/22/2010: added Chrome logic and changed Gecko logic to check if
// Chrome first. Updated the Gecko versionNumber.

// THIS NEEDS TO CHANGE WHEN A NEW VERSION IS RELEASED OF FIREFOX!!!
var versionNumber = "1.9.2";  // as of 3/22/2010 the latest Window version says 1.9.2 while Ubuntu Linux says 1.9.2.3pre

var verNum = versionNumber.split(".");
// ******************************************************

var win;

var Browser = new Object();

Browser.version = parseInt(navigator.appVersion);
Browser.os = navigator.platform;
Browser.product = navigator.product;
Browser.cookieEnabled = navigator.cookieEnabled;
Browser.name = 'Unknown Browser Name';

var info; // just a temp for matches etc.

// Firefox, Chrome, Epiphany, Galion, Konqueror, etc. Almost anything
// that uses the Gecko or WebKit engines say it's Netscape.

if(navigator.appName.indexOf("Netscape") != -1) {
  Browser.isNetscape = true;
}

// Microsoft is so proud of its Internet Explorer CRAP
// The full appName for XP IE8 is "Microsoft Internet Explorer".

if(navigator.appName.indexOf("Microsoft") != -1) {
  Browser.isMicrosoft = true;
  Browser.name = navigator.appName;
}

// Opero also sets appName to tell the world.

if(navigator.appName.indexOf("Opera") != -1) {
  Browser.isOpera = true;
  Browser.name = navigator.appName;
}

// Check the user agent string to get further info

if((info = navigator.userAgent.match(/MSIE\s*(\d+)\.(\d*)/)) != null) {
  Browser.MsieMajorVersion = info[1];
  Browser.MsieMinorVersion = info[2];
}


// BLP 3/22/2010: Added Chrome and check if Chrome before looking at Gecko

// Check for the WebKit engine

if(navigator.userAgent.indexOf("AppleWebKit") != -1) {
  Browser.isWebKit = true;
}

if(navigator.userAgent.indexOf("Konqueror") != -1) {
  Browser.isKonqueror = true;
  Browser.name = "Konqueror";
}

if(navigator.userAgent.indexOf("Chrome") != -1) {
  Browser.isChrome = true;
  Browser.name = "Google Chrome";
}

if(!Browser.isChrome && navigator.userAgent.indexOf("Safari") != -1) {
  Browser.isSafari = true;
  Browser.name = "Safari";
  Browser.safariVersion = navigator.userAgent.match(/Version\/((\d)(\.\d+)+\D)/)[1];
}

// Look for various Firefox names

if(info = navigator.userAgent.match(/(Firefox|Shiretoko|Namoroka|Fennec)/)) {
  Browser.isFirefox = true;
  Browser.name = info[1];
}

// A real Gecko is NOT webkit or Chrome.

if(!Browser.isWebKit && !Browser.isChrome && navigator.userAgent.indexOf("Gecko") != -1) {
        Browser.isGecko = true;
        Browser.GeckoVersion = navigator.productSub;
}

// Look for Gecko Revision number
// BLP 3/22/2010 fix so the Linux version of Firefox
// (Namoroka/3.6.3pre) works. The rv: is 'rv:1.9.2.3pre' which failed
// because the previous check looked for a ')' as the terminator after
// the last number.

if(Browser.isGecko && (info = navigator.userAgent.match(/rv:((\d*((\.?|\S?)\d+)+))\D/))) {
  //alert("info: "+info[1]);

  Browser.GeckoRv = info[1];
}
// BLP End of changes 3/22/2010

// ---------------------------------------------------------------------------
// If CheckOs is called with a value 1 (CheckOs(1)) then flag is set
// and we display the "features" as aquired from CheckFeatures()
// If the value is not 1 and not zero then ignore cookie

function CheckOs() {
/*
  var args = "<p>Arguments object:</p><ul>";
  for(var i=0; i < arguments.length; ++i) {
    args += "<li>"+ i + "=" +arguments[i]+ "</li>";
  }
  args += "</ul>";
  document.write(args);
*/

  var flag = arguments[0]; // flag !=0 ignore cookie, flag == 1 show features and ignore cookie
  var isOk = false;
  var msgOk = "";

  var msg = "";

  var cookie = document.cookie;

  //document.write("COOKIE=" + cookie);
  
  if(!flag && (cookie.match(/OldBrowser/) != null)) {
    return;
  }

  // Check the Mozilla/{version}
  // Microsoft IE still reports Mozilla/4 along with other OLD and non
  // complient browsers.
  
  if(Browser.version < 5) {
    // Less than Mozilla/5

    if(Browser.isMicrosoft) {
      if(Browser.MsieMajorVersion < 7) {
        // MSIE before 6
        msg += "<div class='oscheck'>\n"+
               "<h2>Your are using an old Microsoft Internet Explorer (version " + Browser.MsieMajorVersion+ "). "+
               "Update to Mozilla/Firefox<\/h2>"+
               "<p>You can get the most up to date version of Mozilla/Firefox at "+
               "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
      } else {
        // MSIE 7 or greater -- Still Crap
        msg += "<div class='oscheck'>\n"+
               "<h2>Your are using Microsoft Internet Explorer version " +
               Browser.MsieMajorVersion + "." + Browser.MsieMinorVersion+ "<\/h2>"+
               "<p>Microsoft Internet Explorer version 7 and above may partially work; "+
               "however, IE still does not support many of the W3c CSS and HTML 4.0.1 "+
               "standards. This site has been tested with version 7 and 8 and most everything works."+
               "You can get the most up to date version of Mozilla/Firefox, which is very standard compliant, at "+
               "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
      }
    } else if(Browser.isNetscape || Browser.isGecko) {
      // Browser version is still LESS THAN 5
      // Old Gecko's or Netscape's
      msg += "<div class='oscheck'>\n"+
             "<h2>Old Netscape version " + Browser.version + "<\/h2>" +
             "Many features used by this site are <u>not</u> supported by old "+
             "Netscape versions. "+
             "You can get the most up to date version of Mozilla/Firefox at "+
             "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
    } else {
      // Who knows what?
      msg += "<div class='oscheck'>\n"+
             "<h2>You are using an old '" + navigator.appName + "' version " + Browser.version + "<\/h2>" +
             "This browser may not support HTML or CSS features used on this site. "+
             "You can get the most up to date version of Mozilla/Firefox at "+
             "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
    }
  } else {
    // Mozilla/5 or greater
    
    if(Browser.isNetscape) {
      // Almost everything except Internet Explorer (Microsoft) and Opera
      // say there are Netscape.

      // New browsers version 5 or better
      if(Browser.isGecko) {
        // These are browsers like Firefox, Galion, Konqueror (which says
        // 'like Gecko' but does not have a Gecko rv: number), etc. Most
        // open source browsers are based on either Gecko or AppleWebKit
        // engines.

        // If Gecko then make sure it is pretty new

        var grev = '';

        if(typeof Browser.GeckoRv != 'undefined') {
          grev = Browser.GeckoRv.split(".");

          //alert("grev="+grev+" verNum="+verNum+" grev < verNum="+ (grev < verNum));

          if(grev < verNum) { // || (grev[0] < verNum[0] || grev[1] < verNum[1] || grev[2] < verNum[2] || grev[3] < verNum[3])) {
            // Not the latest but probably ok
            msg += "<div class='oscheck'>\n"+
                   "<h2>You have Netscape/Gecko rev " + Browser.GeckoRv +
                   " but your revision is less then " + versionNumber + "<\/h2>"+
                   "<p>You should think about upgrading. "+
                   "You can get the most up to date version of Mozilla/Firefox at "+
                   "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
          } else {
            msgOk = "<div class='oscheck'>\n"+
                    "<h2>You have " + Browser.product + " " +
                    Browser.GeckoRv +". This site was tested for this browser.<\/h2></div>";
            isOk = true;
          }
        } else if(Browser.isKonqueror) {
          msg += "<div class='oscheck'>\n"+
                 "<h2>You are running Konqueror which has some major issues. Use Firefox instead.</h2></div>";

        } else {
          // NO GeckoRv
          msg += "<div class='oscheck'>\n"+
                 "<h2>Your user agent is "+navigator.userAgent+"</h2>" +
                 "<p>This site may not work correctly with this agent." +
                 "<p>You should think about upgrading. "+
                 "You can get the most up to date version of Mozilla/Firefox at "+
                 "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
        }
      } else if(Browser.isWebKit) {
        // Some other version 5 browser?
        if(Browser.isChrome) {
          msgOk += "<div class='oscheck'>\n"+
                   "<h2>You are running Google's Chrome which works well for this site.</h2></div>";
          isOk = true;
        } else if(Browser.isSafari) {

          msgOk += "<div class='oscheck'>\n"+
                   "<h2>Your are using Safari version " + Browser.safariVersion  + ".</h2>"+
                   "<p>Version 4 has been tested on this site and works OK."+
                   "<br>However, you could upgrade your browser to Mozilla/Firefox at "+
                   "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
          isOk = true;
        } else {
          msg += "<div class='oscheck'>\n"+
                 "<h2>You are using agent " + navigator.userAgent +
                 "<br>Which uses the AppleWebKit Engine.</h2><p>This browser will probably work OK."+
                 "<br>However, you could upgrade your browser to Mozilla/Firefox at "+
                 "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/h2></div>";
        }
      } else {
        // Not Gecko or WebKit
        msg += "<div class='oscheck'>\n"+
               "<h2>Your are using agent "+navigator.userAgent + "</h2>" +
               "<p>This browser uses neither the Gecko or AppleWebKit Engines. It may not render this site correctly."+
               "<br>You could upgrade your browser to Mozilla/Firefox at "+
               "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
      }
    } else if(Browser.isOpera) { // Not isNetscape so is it Opera
      if(Browser.version < 9) {
        // Opera less than version 9
        msg += "<div class='oscheck'>\n"+
               "<h2 align=center>Your have Opera (" +Browser.version+") but not the latest version.<\/h2>"+
               "<p>This site has been tested with version 9 and greater and works OK.<br>"+
               "You can either upgrade your Opera version or you can get the most up to date version of Mozilla/Firefox at "+
               "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
      } else {
        msgOk += "<div class='oscheck'>\n"+
                 "<h2>Your have Opera version "+Browser.version+ " which should work OK on this site.</h2></div>";
        isOk = true;
      }
    } else {
      // Vesion 5 Browser but who knows
      msg += "<div class='oscheck'>\n"+
             "<h2 align=center>You are using '"+navigator.appName+ "' " +navigator.appVersion+ "<\/h2>"+
             "<p>This site has only been tested with Mozilla/Firefox 1.0.x most other browsers "+
             "lack some features that this site uses. "+
             "You can get the most up to date version of Mozilla/Firefox at "+
             "<a href='http://www.mozilla.org'>www.mozilla.org<\/a><\/p></div>";
    }
  }
  
  // Check to make sure the user has cookies enables! This site just
  // will not work without cookies!

  if(!Browser.cookieEnabled) {
    msg += "<div class='oscheck'>\n"+
           "<h2>You <u>MUST ENABLE</u> the use of 'cookies' for this site<\/h2>";

    if(Browser.isMicrosoft) {
      msg += "<p>To enable 'cookies' for MSIE 6.0 or newer goto the menu "+
             "'Tools | Internet Options...' "+
             "then select 'Privacy' tab "+
             "at the top of the dialog box. You can select anything except 'High' with "+
             "the slider.<\/p>";

    } else if(Browser.isGecko) {
      msg += "<p>To enable 'cookies' for Mozilla goto the menu 'Edit | Preferences...' "+
             "then select 'Privacy &amp; Security | Cookies' from the 'Catagory' window "+
             "at the left. You can select either 'Enable cookies for the originating "+
             "web site only' or 'Enable all cookies'. If you select 'originating "+
             "web site only' then third party trackers will not work (which is "+
             "probably what you want).<\/p>";
    } else {
      msg += "<p>You are using "+ navigator.appName + "<br>agent " +
             navigator.userAgent +
             "<br>I am sorry but I don't know how to enable 'cookies' on " +
             "this browser. Try looking under your 'Preferences' or 'Internet Settings'. "+
             "However, you should really upgrade your browser to Mozilla/Firefox!<\/p>";
    }
    msg += "</div>";
  }

  // The screen should be at least 600x800 or stuff will not fit
  // horizontaly

  if((screen.height) < 500 || (screen.width < 750)) {
    msg += "<div class='oscheck'>\n"+
           "<p>Your screen resolution is less than 800 wide and 600 high. This site is best "+
           "viewed with a resolution of at least 800x600<\/p></div>";
  }

  // Flag is not set unless this is called from the button "Check the
  // Features of your Browser".

  if(flag == 1) {
    msg += CheckFeatures();
  }

  if(flag || msg) {
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

    document.write("<div class='show' id='OsMsg'>");
    document.write(msg);
    document.write("<br>This message will disapear in 20 seconds");
    document.write("<\/div>");

    if(!flag) {
      setTimeout(hideOsMsg, 20000);           // close it after 20 sec.
    }

    // don't set expires so this will only last while the browser us
    // up.

    document.cookie = "OldBrowser=1";
  }
  return isOk;
}

// ---------------------------------------------------------------------------
// Two helpers for the pop up window we create in CheckOs

// Also ClosePopup is on the <body onunload

function hideOsMsg() {
  var item = document.getElementById("OsMsg");
  item.className = "hide";
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

// ------------------------------------------------------------

function ShowAll() {
  var browser = "Browser Info:<br>object=navigator<br>\n";

  for(var propname in navigator) {
    browser += propname + ": " + navigator[propname] + "<br>\n";
  }
  browser += "object=Browser<br>\n";

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

  msg = browser;

  msg += "<hr><p align=center><input type=button value='Close Window' "+
         "onclick='window.close(); window.opener.focus()'></p>";

  win = window.open("", "", features);
  win.document.write(msg);
  win.document.close();
  win.focus();
}

//-->
