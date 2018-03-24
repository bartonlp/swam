<?php
/* REMEMBER function names are case-insensitive!!!! */
/**
 * Swam Class
 *
 * This class provides methods for www.swam.us
 * This classs extends the Database base class.
 * @see db.class.php
 * @package Swam
 * @author Barton Phillips <barton@bartonphillips.com>
 * @version 1.0
 * @link http://www.bartonphillips.com
 * @copyright Copyright (c) 2010, Barton Phillips
 * @license http://opensource.org/licenses/gpl-3.0.html GPL Version 3
 */

/*
This is the main members table:

CREATE TABLE `swammembers` (
  `id` int(11) NOT NULL auto_increment,
  `fname` varchar(255) default NULL,
  `lname` varchar(255) default NULL,
  `name` varchar(255) default NULL COMMENT 'full name as it was in account table',
  `email` varchar(255) default NULL,
  `visits` int(11) default '0',
  `lasttime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `visittime` datetime default NULL,
  `firsttime` datetime default NULL,
  `address` varchar(30) default NULL,
  `city` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `gender` varchar(7) default NULL,
  `zip` varchar(15) default NULL,
  `country` varchar(10) default NULL,
  `phone` varchar(20) default NULL,
  `bday` varchar(12) default NULL,
  `team` varchar(20) default NULL,
  `lastmsg` int(11) default NULL,
  `secret` char(3) default NULL,
  `realEmail` varchar(40) default NULL,
  `code` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 
*/

define("FREDS_ID", 50);
define("BARTONS_ID", 192);
define("ZUPONS_ID" , 58);

// Two function to be included in other files

//require("webcrypt.phpi");

// The lib needs these defined

define("KEY", "411blp^8653");
define("HASH", MHASH_SHA1);
define("CIPHER", MCRYPT_TWOFISH);
define("MODE", MCRYPT_MODE_CFB);// Always open the database! This file is included by ALL others so this one open

require_once("/home/johnzupon/includes/site.class.php"); // from above

/**
 * @package GranbyRotary extends Database
 */

class Swam extends SiteClass {
  public $user;  // "$fname $lname" as one unit from database, or empty
  public $isMember = false;
  public $swamEncryptedId = 0;
  public $bboardCount; // number of bbs entries
  public $bboardMaxItem; // max item number in bbs
  public $lastMsg; // last bbs message read by user
  
  /**
   * Constructor
   *
   * Connects database, does page count optionally.
   * @param boolean $count Default true. If true do the counter logic else don't
   */
  
  public function __construct($count=true) {
    require("/home/johnzupon/includes/swam.config.php");
    $s->count = $count;
    parent::__construct($s); // NOTE: parent constructor calls checkId() which uses this classes method not the parents!
  }

  // If we use the memberid method of setting cookie etc we need to do this
  
  public function doCounts() {
    $this->counter();
    $this->tracker();
  }

  // Hit counter using MySql

  private function pageCounter($msg='') {
    $filename = $this->self;

    // A message can be passed by setting the $CounterMessage variable in
    // the caller. If not set then defaults to 'Number of Hits'

   // $CounterMessage = $msg ? $msg : "Number of Hits Since October 30, 2010";
       $CounterMessage = $msg ? $msg : "Number of Hits Since May 24, 2014";
    // Now select the 'count' for the web page filename.

    $query = "select count from counter where filename='$filename'";

    list($result, $n) = $this->query($query, true);

    // Check that we found a row. If not then we need to insert the new
    // page name.

    if(!$n) {
      // Not in database yet so create an entry for this file

      $query = "insert into counter (filename, count) values('$filename', '1')";
      $result = $this->query($query);

      $count = 1;
    } else {
      // The page name is already in the table so get the old value and
      // update it.
  
      list($count) = mysql_fetch_row($result);

      ++$count; // add one and update the table
    
      $query = "update counter set count='$count' where filename='$filename'";
      $result = $this->query($query);

      // The ctrnumber.php returns an image. The possible arguments are:
      // s=font size. if not pressent defaults to 11
      // text=the message which is usually a number like 11 etc. If not
      // pressent then blank.
      // font=font file, like TIMES.TTF. If not pressent defaults to
      // TIMESBD.TTF

      return <<<EOF
<div id='hitCounter'>
   <p id='hitCounterp'>$CounterMessage</p>
   <table id='hitCountertbl'>
     <tr id='hitCountertr'>
        <th id='hitCounterth'><img id='ctrnumbers' src='ctrnumbers.php?s=16&amp;text=$count' alt='$count'></th>
     </tr>
   </table>
</div>
EOF;
    }
  }
  
  /**
   * Get Page Footer
   * OVERRIDE site.class.php version
   * @param object $f. If $f->useparent is true then use the site-class.php footer logic.
   *  The rest of $f is passed onto parent::getFooter($f)
   *  If $f->useparent is false then $f is used in this method:
   *    $f->msg if set is used first (above the <hr> if $f->w3cval is null. If $f->msg is null then a link to welcome page
   *      is used instead.
   *    $f->w3cval if set is used next after $f->msg. If null then a <hr> is used instead.
   *    $f->google if true adds the google analitics at the end. Default is true if $f->google is null
   * @return string
   */

  public function getFooter($f=null) {
    $f->google = false; // DONT DO GOOGLE 
    if($f->useparent) {
      return parent::getFooter($f);
    }

    // The club is in Colorado but the server is San Diego
    
    date_default_timezone_set('America/Los_Angeles');
    $rdate = getlastmod();
    $date = date("M d, Y H:i:s", $rdate);

    $x = new DateTime($date);
    $off = $x->getOffset();
    //echo "off=$off," . 7*60*60 ."<br>";
    $daylite = $off == -(7*60*60) ? "PDT" : "PST"; // 21600 min = 6 hr

    $counter = $this->pageCounter($f->ctrmsg);

   // $msg = $f->msg ? $f->msg : '<a href="index-members.php">Back to SWAM Home Page</a>';

    $w3cval = $f->w3cval ? $f->w3cval : "<hr>";

    $ret = <<<EOF
$msg
$w3cval
$counter
<h3 align=center><br>
Webmaster <a href="mailto:john@zupons.net">John Zupon</a></h3>
<p align=center><a href="poweredby.php">This site is run with Linux, Apache, MySql and PHP</a></p>
<br>
<center>
<address>
  South West Aquatic Masters <br>
  Steve Schofield Aquatic Center Pool <br>
  Los Angles Pierce College
  6201 Winnetka <br>
  Woodland Hills, CA 91371 <br>
  Team Phone (818)347-1637 <br>
 Copyright &copy; 2000-2011 South West Aquatic Masters
</address>
<div style="text-align: center;">
<p id='lastmodified'>Last Modified&nbsp;$date $daylite</p>
</div>
</center>
<!-- Barton added these links for google search engine -->
<p>
   <a style="font-size: 2pt; color: white;"
       href="http://www.bartonphillips.com">www.bartonphillips.com</a><br>
   <a style="font-size: 2pt; color: white;"
       href="http://www.granbyrotary.org">www.granbyrotary.org</a>
</p>

EOF;
    if($f->google === null || $f->google === true) {
      $ret .= <<<EOF
<!-- Google Analitics -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-713599-5");
pageTracker._trackPageview();
} catch(err) {}</script>

EOF;
    }
    $ret .= <<<EOF
</body>
</html>
EOF;

    if(!($this->doctype == self::DOCTYPE_XHTML_STRICT || $this->doctype == self::DOCTYPE_XHTML_TRANS)) {
      $ret = preg_replace("|/>|", ">", $ret);
    }
    return $ret;
  }

  /**
   * Get the user name
   */
  
  public function getUser() {
    return $this->user;
  }

  /**
   * Set the user's name
   */
  
  public function setUser($user) {
    $this->user = $user;
  }

  /**
   * Check ID info
   *
   * Override base class!
   * @return user's id
   */
  
  public function checkId($mid=null) {
    if(!isset($mid) && $this->id) {
      return $this->id;
    }

    //echo "mid=$mid<br>";
    //vardump($_COOKIE, "cookie");
    
    if(!$mid) $id = $_COOKIE['SiteId'];

    //echo "checkId: id=$id<br>";
    
    if(!$id && !$mid) {
      return 0;
    } elseif(isset($mid)) {
      $id = $mid;
    }

    $value = rawurldecode($id);
   
    if(($pt = $this->WEB_decrypt($value)) !== false) {
      // This is a good encrypted ID

      $this->swamEncryptedId = $id;
      
      $id = $pt;
    } else {
      mail("bartonphillips@gmail.com", "checkId()", "could not decript: $id", "From: swam@zupons.net",
           "-fbartonphillips@gmail.com");
      
      return 0;
    }

    // Ok it's a number and it's in range. Now does it index into the database?

    list($result, $n) = $this->query("select concat(fname, ' ', lname) as name, fname, lname, email, team, " .
                                     "visits, lastmsg, realEmail " .
                                     "from swammembers where id='$id'", true);
    
    if(!$n) {
      // Ops didn't find the id in the database?
      mail("bartonphillips@gmail.com", "checkId()", "Didn't find id=$id in database", "From: swam@zupons.net",
           "-fbartonphillips@gmail.com");

      return 0;
    }

    $row = mysql_fetch_assoc($result);
    extract($row);
    $this->fname = $fname;
    $this->lname = $lname;
    $this->name = $name;

    // The email address should be in $realEmail. $email might be a password.
    
    if(preg_match("/^.+@.+\..+$/", $realEmail)) {
      $this->email = $realEmail;
    } else {
      $this->email = $email;
    }
    
    $this->id = $id;
    $this->isMember = !$team ? false : true;
    $this->lastMsg = $lastmsg;
    
    $result = $this->query("select count(*), max(item) from bboard");

    list($cnt, $max) = mysql_fetch_row($result);

    $this->bboardCount = $cnt;
    $this->bboardMaxItem = $max;
  
    return $id;
  }

  /**
   * Checks and Sets the ID cookie
   * @return user's ID
   */
  
  public function checkAndSetId() {
    $id = $this->checkId(); 

    if($id) {
      $this->setIdCookie($id);
    }
    return $id;
  }

  /**
   * Set Id Cookie
   * Overrides parent so we can encrypt the id
   */

  public function setIdCookie($id) {
    //echo "child setIdCookie: $id<br>";
    
    // We want to encrypt it first and then call parent setIdCookie
    $encryptedId = $this->WEB_encrypt($id);

    /*mail("bartonphillips@gmail.com", "setIdCookie", "Id=$id, Encripted id=$encryptedId", "From: swam@zupons.net",
         "-fbartonphillips@gmail.com");*/

    return parent::setIdCookie($encryptedId);
  }
  
  /**
   * Check BBoard
   * @return blank or number of new bulletin board entries ("<br/>$cnt New Post" . ($cnt == 1 ? "" : "s")
   */
  
  public function checkBBoard() {
    $msg = "";

    $id = $this->getId();

    if($id) {
      $result = $this->query("select count(item) from bboard");  // count all items in bboard

      list($bbcount) = mysql_fetch_row($result);

      $result = $this->query("select count(item) from bbsreadmsg where id='$id'"); // now count the number of items that I have read

      list($bbsreadcnt) = mysql_fetch_row($result);

      // If there are some items in the bb

      if($bbcount) {
        // subtract the total from what I have read, this is the number
        // of UN read items.

        $cnt = $bbcount - $bbsreadcnt;

        // If ther are any unread items

        if($cnt) {
          $msg = "<br/>$cnt New Post" . ($cnt == 1 ? "" : "s");
        }
      }
    }
    return $msg;
  }
  
  public function WEB_encrypt($pt) {
    $realkey=mhash(HASH, KEY);

    $td = mcrypt_module_open(CIPHER, "", MODE, "");

    $blockSize = mcrypt_enc_get_block_size($td);

    $iv = mcrypt_create_iv($blockSize, MCRYPT_DEV_URANDOM);

    mcrypt_generic_init($td, KEY, $iv);

    $blob = mcrypt_generic($td, mhash(HASH, $pt).$pt);

    mcrypt_generic_deinit($td);

/*
 * Note that mcrypt may add trailing nulls that must be stripped.
 */

    $blob = substr($blob, 0, strlen($pt) + mhash_get_block_size(HASH));

    return base64_encode($iv.$blob);
  }

/*
 * Decrypt previously encrypted plaintext message. Input is
 * expected to be base64 encoded, output is arbitrary string
 * or FALSE if decode failed.
 */

  public function WEB_decrypt($blob) {
    $td = mcrypt_module_open(CIPHER, "", MODE, "");

    $blockSize = mcrypt_enc_get_block_size($td);

    $realkey = mhash(HASH,KEY);

    $rawblob = base64_decode($blob); /* binary blob */

    $iv = substr($rawblob, 0, $blockSize); /* IV */

    if(strlen($iv) < $blockSize)
      return FALSE;

    $ct = substr($rawblob, $blockSize); /* CipherText */

    mcrypt_generic_init($td, KEY, $iv);

    $unblob = mdecrypt_generic($td, $ct);

    mcrypt_generic_deinit($td);

    // mcrypt may add trailing nulls that must be stripped.

    $unblob = substr($unblob, 0, strlen($ct));

    $pt = substr($unblob, mhash_get_block_size(HASH));

    $check = substr($unblob, 0, mhash_get_block_size(HASH));

    if ($check != mhash(HASH, $pt))
      return FALSE;
    else
      return $pt;
  }

  /**
   *  Greet Visitors
   */
 
  public function greetvisitors() {
    // Already registered?
    if($this->name) {
      // A swam member?
      if($this->isMember)
        $hi = "Hi fellow Swammer $this->name";
      else
        $hi = "Hi $this->name";   // not a swammer
    } else {
      // Not registered yet
      $hi = "Hi, glad you came back. \n";
    }

    //echo "lastMsg=$this->lastMsg, max=$this->bboardMaxItem, cnt=$this->bboardCount<br>";
    
    if($this->lastMsg < $this->bboardMaxItem) {
      $hi .= <<<EOF
<br><br>
<p class='center'>
<a class='buttons1-5em blueButton' href='bboard.php'>New BBS Messages Since You Looked Last</a>
EOF;
    }

    list($result, $n) = $this->query("select item from swammailto where id='$this->id' and dateread is null", true);

    // If we have messages then play the WAV file and show the "You Have SwamMail" button

    if($n) {
      $hi .= <<<EOF
<br>
<a class='buttons1-5em blueButton' href='swammail.php?page=readmail'>
You Have SwamMail</a>
EOF;
    }
    
    $ret = "<div id='greetvisitors'>\n<h1 class=guest>$hi</h1>\n</div>\n";

    if($this->name) {
      // check to see if we have a real looking email address and if not is the realemail
      // field looking like an email address

      $result = $this->query("select email from swammembers where id='$this->id' and email like '%@%.%'");

      list($email) = mysql_fetch_row($result);

      // Do we have an email address?

      if(!$email) {
        // May be using realEmail instead

        $result = $this->query("select realEmail from swammembers where id='$this->id' and realEmail like '%@%.%'");

        list($email) = mysql_fetch_row($result);

        // Don't have an email address

        if(!$email) {
          $ret .= <<<EOF
<p class='red'>You have not given us a valid looking email address. Please visit the
<a href='guestreg.php?SwamGuestId=$this->swamEncryptedId'>Guest Book</a> and
provide a real email address. Thanks.</p>
EOF;
        }
      }
    } else {
      // First time at this site!
      $ret = <<<EOF
<div id='greetvisitors'>
<h1 class='guest'>Welcome to the SWAM Web Site</h1>
<!--<p class='guest'>There is a lot more to this site but you need to be a registered member first</p>-->
EOF;
    }
    $ret .= "</div>\n";
    return $ret;
  }
}

// End of class GranbyRotary
// ********************************************************************************

// Callback to get the user id for db.class.php SqlError
// If this is not defined then db.class.php uses the IP and Agent information.

if(!function_exists(ErrorGetId)) {
  function ErrorGetId() {
    global $S; // This should have been defined
    
    $id = $_COOKIE['SiteId']; // NOTE the SiteId is an encrypted version of the real ID

    if($id) {
      $id = $S->WEB_decrypt($id);
    } else {
      $id = "IP={$_SERVER['REMOTE_ADDR']}, AGENT={$_SERVER['HTTP_USER_AGENT']}";
    }
    return $id;
  }
}

// WARNING THERE MUST BE NOTHING AFTER THE CLOSING PHP TAG.
// Really nothing not even a space!!!!
?>
