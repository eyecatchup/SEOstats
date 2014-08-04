<?php define('DISABLE_MAN', FALSE);
/* pref              User preference interface.
 * @package          GTB_PageRank
 * @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
interface pref
{
  const PREFERED_TLD     = "com";
}
/* tbr               Toolbar server request interface.
 * @package          GTB_PageRank
 * @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
interface tbr
{
  //  2 toolbar server hostnames, as found in the toolbar source code.
  const SERVER_HOSTS    = '["toolbarqueries.google.","alt1.toolbarqueries.google."]';

  //138 toolbar server top level domains, as found in the toolbar source code.
  const SERVER_TLDS     = '["com","ae","com.af","com.ag","com.ai","am","com.ar","as","at","com.au","az","ba","com.bd","be","bg","com.bh","bi","com.bo","com.br","bs","co.bw","com.bz","ca","cd","cg","ch","ci","co.ck","cl","com.co","co.cr","com.cu","cz","de","dj","dk","dm","com.do","com.ec","ee","com.eg","es","com.et","fi","com.fj","fm","fr","co.uk","gg","com.gi","gl","gm","gr","com.gt","com.hk","hn","hr","ht","hu","co.id","ie","co.il","co.im","co.in","is","it","co.je","com.jm","jo","co.jp","co.ke","kg","co.kr","kz","li","lk","co.ls","lt","lu","lv","com.ly","co.ma","mn","ms","com.mt","mu","mw","com.mx","com.my","com.na","com.nf","com.ni","nl","no","com.np","co.nz","com.om","com.pa","com.pe","com.ph","com.pk","pl","pn","com.pr","pt","com.py","com.qa","ro","ru","rw","com.sa","sc","se","com.sg","sh","si","sk","sm","sn","com.sv","co.th","com.tj","tm","to","com.tr","tt","com.tw","com.ua","co.ug","com.uy","co.uz","com.vc","co.ve","vg","co.vi","com.vn","co.za","co.zm"]';

  // Service request path as found in the toolbar source code.
  const SERVER_PATH     = "/tbr";

  // Request query string as found in the toolbar source code.
  const QUERY_STRING    = "?features=Rank&client=navclient-auto&ch=%s&q=info:%s";

  // Google's client-specific suggestion of a prefered top level domain (as found in tb source code).
  const SUGGEST_TLD_URL = "https://www.google.com/searchdomaincheck?format=domain&sourceid=navclient-ff";
}

/* GTB_PageRank      Hash a variable-length key into a 32-bit value.
 * @package          GTB_PageRank
 * @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
class GTB_PageRank implements tbr, pref
{
  // objects vars
  public $QUERY_URL, $URL_HASHES, $PREFERED_TLD, $GTB_SUGESSTED_TLD, $GTB_QUERY_STRINGS;
  private $GTB_SERVER;

  /** __construct - Initialize a new object of the class 'GTB_PageRank'.
   *  @access  public
   */
  public function __construct($a=NULL) {
    if(NULL===$a) {
		GTB_Exception::noUrl();
	}
    $this->GTB_SERVER = array(			// setup the toolbar server vars
		"host" => GTB_HELPER::_json_decode(tbr::SERVER_HOSTS),
		"tld"  => GTB_HELPER::_json_decode(tbr::SERVER_TLDS),
		"path" => tbr::SERVER_PATH
	);									// setup the client preferences
	if (!in_array(self::getPref('tld'), self::getTbrTlds() )) {
		GTB_Exception::invalidPref('PREFERED_TLD');
	} else {
		$this->PREFERED_TLD = pref::PREFERED_TLD;
		$this->GTB_SUGESSTED_TLD = self::getTbrTldSuggestion();
	}
	$init = self::setQueryURL($a);		// setup the query url
	if (TRUE !== $init) {
		GTB_Exception::tryAgain();
	}
  }

  public function getPageRank() {
	$host  = $this->GTB_SERVER['host'][0];
	$tld   = (strlen($this->GTB_SUGESSTED_TLD) > 0) ? $this->GTB_SUGESSTED_TLD : $this->PREFERED_TLD;
	$path  = $this->GTB_SERVER['path'];
	$tbUrl = 'http://' . $host . $tld . $path;
	$qStrings = self::getQueryStrings();

	for ( $i=0; $i < 3; $i++ ) {
		if( !isset($qStrings[$i])) {
			break;
		}
		$PR = self::getToolbarPageRank($tbUrl . $qStrings[$i]);
		if ($PR === FALSE) {
		    continue;
		}
		return $PR;
	}
	return 'Failed to generate a valid hash for PR check.';
  }
  public function getToolbarPageRank($toolbarUrl) {
	$ret = GTB_Request::_get($toolbarUrl);
	$pagerank = trim(substr($ret, 9));
	return ($this->isResultValid($pagerank)) ? $pagerank : false;
  }

  public function isResultValid($result) {
    return preg_match('/^[0-9]/',$result) || $result === "";
  }

  /** getQueryURL - Get the object query url.
   *  @access  public
   */
  public function getQueryURL() {
    return $this->QUERY_URL;
  }

  /** getHash - Get a single hash key value string from object's 'URL_HASHES' array.
   *  @access  public
   */
  public function getHash($k) {
    $array = $this->URL_HASHES;
    return $array[$k];
  }
  /** getHash - Get the object's 'URL_HASHES' array.
   *  @access  public
   *  @return  Array         returns array of hash-key-pairs for the object url.
   */
  public function getHashes() {
    return $this->URL_HASHES;
  }

  /** getQueryStrings - Get an array of formatted request query strings.
   *  @access  public
   */
  public function getQueryStrings() {
    return $this->GTB_QUERY_STRINGS;
  }

  /** getQueryUrls - Get the object's 'URL_HASHES' array.
   *  @access  public
   *  @return  Array         returns array of all possible url combinations.
   */
  public function getQueryUrls($limit=NULL) {
    $a = self::getQueryUrl();
    $b = self::getHashes();
	$QueryUrls = array();
	$limit = (NULL!==$limit && is_numeric($limit)) ? (int)$limit : 0;
	$c = 0;
	//Foreach hash key value...
	foreach ( $b as $k => $v ) {
	    //...that is a string with length > 0...
		if ( is_string($v) AND strlen($v) > 0 ) {
			//...format a query string.
			$rs = sprintf(tbr::QUERY_STRING, $v, $a);
			//Then, foreach available toolbar hostname...
			foreach ( $this->GTB_SERVER['host'] as $host ) {
				//...append any available top level domain...
				foreach ($this->GTB_SERVER['tld'] as $tld) {
					$tbUri = 'http://'. $host . $tld . tbr::SERVER_PATH . $rs;
					if ( $c < $limit || $limit == 0 ) {
						$QueryUrls[] = $tbUri;
					}
					$c++;
				}
			}
		}
	}
	return (sizeof($QueryUrls)>0) ? $QueryUrls : FALSE;
  }

  /** getTbrServer - Get the Google Toolbar server vars array.
   *  @access  public
   *  @return  Array         Array contains keys: 'host', 'tld', 'path'.
   */
  public function getTbrServer() {
	return $this->GTB_SERVER;
  }
  /** getTbrHosts - Get all available host names.
   *  @access  public
   *  @return  Array         Array containing all available Toolbar server host names.
   */
  public function getTbrHosts() {
	return $this->GTB_SERVER['host'];
  }
  /** getTbrTlds - Get all available top level domains.
   *  @access  public
   *  @return  Array         Array containing all available Toolbar server top level domains.
   */
  public function getTbrTlds() {
	return $this->GTB_SERVER['tld'];
  }
  /** getTbrTldSuggestion - Get Google's suggestion which top level domain to use.
   *  @access  public
   *  @return  Array         Array containing all available Toolbar server top level domains.
   */
  public function getTbrTldSuggestion() {
	$tmp = explode(".google.", GTB_Request::_get(tbr::SUGGEST_TLD_URL));
	return isset($tmp[1]) ? trim($tmp[1]) : 'com';
  }
  /** getTbrPath - Get the Google Toolbar Pagerank request path.
   *  @access  public
   *  @return  String
   */
  public function getTbrPath() {
	return $this->GTB_SERVER['path'];
  }

  public function getPref($k) {
    if ($k == 'tld') {
		return pref::PREFERED_TLD;
	}
  }

  public function GPR_awesomeHash() {
    $a = self::getQueryURL();
    if (NULL!==$a) {
      return GTB_awesomeHash::awesomeHash($a); }
    else { GTB_Exception::noUrl(); }
  }
  public function GPR_jenkinsHash() {
    $a = self::getQueryURL();
    if (NULL!==$a) {
      return GTB_jenkinsHash::jenkinsHash($a); }
    else { GTB_Exception::noUrl(); }
  }
  public function GPR_jenkinsHash2() {
    $a = self::getQueryURL();
    if (NULL!==$a) {
      return GTB_jenkinsHash::jenkinsHash2($a); }
    else { GTB_Exception::noUrl(); }
  }
  public function GPR_ieHash() {
    $a = self::getQueryURL();
    if (NULL!==$a) {
      return GTB_ieHash::ieHash($a); }
    else { GTB_Exception::noUrl(); }
  }

  // setQueryURL            setter function for the url key.
  // @return  Boolean       returns true if input string validated as url, else false.
  private function setQueryURL($a) {
    $this->QUERY_URL = $a;
	$b = array(
		'jenkins' => self::GPR_jenkinsHash(),
		'jenkins2'=> self::GPR_jenkinsHash2(),
		'ie'      => self::GPR_ieHash(),
		'awesome' => self::GPR_awesomeHash() );
	$this->URL_HASHES = $b;
	return (bool) self::setQueryStrings($a, $b);
  }
  private function setQueryStrings($a,$b) {
	$qs = array();
	foreach ($b as $k => $v) { //Foreach hash key value...
		if(is_string($v) && strlen($v) > 0) {
			//...format a query string.
			$qs[] = sprintf(tbr::QUERY_STRING, $v, urlencode($a));
		}
	}
	if (sizeof($qs) > 0) {
		$this->GTB_QUERY_STRINGS = $qs;
		return TRUE;
	}
	return FALSE;
  }
}//eoc

/** GTB_awesomeHash   Hash a variable-length key into a 32-bit value.
 *  @package          GTB_PageRank
 *  @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
class GTB_awesomeHash extends GTB_PageRank
{
  // hash seed, used by the "awesomeHash" algrorithm
  const HASH_SEED = "Mining PageRank is AGAINST GOOGLE'S TERMS OF SERVICE. Yes, I'm talking to you, scammer.";

  // awesomeHash - Validates input, pass it to the hash function and return the result.
  public static function awesomeHash($a) {
    return self::_awesomeHash($a);
  }
  // _awesomeHash - Returns the computed hash for string $a.
  public static function _awesomeHash($a) {
    $b = 16909125; for ($c=0; $c<strlen($a); $c++) {
        $b ^= (GTB_Helper::charCodeAt(self::HASH_SEED, ($c%87))) ^ (GTB_Helper::charCodeAt($a, $c));
        $b = GTB_Helper::unsignedRightShift($b, 23) | $b << 9;
    }
    return '8'. GTB_Helper::hexEncodeU32($b);
  }
}//eoc
/** GTB_jenkinsHash   Hash a variable-length key into a 32-bit value.
 *  @package          GTB_PageRank
 *  @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
class GTB_jenkinsHash extends GTB_PageRank
{
  // jenkinsHash  Validates input, pass it to the hash function and return the result.
  public static function jenkinsHash($a) {
    $b = GTB_Helper::strOrds("info:".$a);
    return self::_jenkinsHash ($b);
  }
  // jenkinsHash2 Validates input, pass it to the hash function and return the result.
  public static function jenkinsHash2($a) {
    $ch = sprintf("%u", self::_jenkinsHash($a, FALSE));
    $ch = ((GTB_Helper::leftShift32(($ch/7), 2)) | ((GTB_Helper::_fmod($ch, 13)) & 7));
    $buf = array($ch);
    for($i=1; $i<20; $i++) { $buf[$i] = $buf[$i-1]-9; }
    return sprintf("6%u", self::_jenkinsHash(GTB_Helper::c32to8bit($buf), FALSE));
  }
  // @copyright   (c) 1996 Bob Jenkins <bob_jenkins@burtleburtle.net>
  // @see         http://www.burtleburtle.net/bob/c/lookup2.c
  public static function _jenkinsHash($key, $encode=TRUE) {
    $url = $key;
    $length = sizeof($url);        // the key's length
    $a = $b = 0x000000009E3779B9;  // the golden ratio; an arbitrary value
    $c = 0x00000000E6359A60;       // the previous hash, or an arbitrary value
    $k = 0; $len = $length;
    while($len >= 12) {            // handle most of the key
        $a += $url[$k+0];
        $a += GTB_Helper::leftShift32($url[$k+1],  8);
        $a += GTB_Helper::leftShift32($url[$k+2], 16);
        $a += GTB_Helper::leftShift32($url[$k+3], 24);
        $b += $url[$k+4];
        $b += GTB_Helper::leftShift32($url[$k+5],  8);
        $b += GTB_Helper::leftShift32($url[$k+6], 16);
        $b += GTB_Helper::leftShift32($url[$k+7], 24);
        $c += $url[$k+8];
        $c += GTB_Helper::leftShift32($url[$k+9],  8);
        $c += GTB_Helper::leftShift32($url[$k+10],16);
        $c += GTB_Helper::leftShift32($url[$k+11],24);
        $mix = self::hashmixJenkins2($a, $b, $c);
        $a = $mix[0]; $b = $mix[1]; $c = $mix[2];
        $len -= 12; $k += 12;
    }   $c += $length; // handle the last 11 bytes
    switch($len) { // all the case statements fall through
        case 11: $c += GTB_Helper::leftShift32($url[$k+10],24);
        case 10: $c += GTB_Helper::leftShift32($url[$k+9], 16);
        case 9 : $c += GTB_Helper::leftShift32($url[$k+8],  8);
        // the first byte of $c is reserved for the length
        case 8 : $b += GTB_Helper::leftShift32($url[$k+7], 24);
        case 7 : $b += GTB_Helper::leftShift32($url[$k+6], 16);
        case 6 : $b += GTB_Helper::leftShift32($url[$k+5],  8);
        case 5 : $b += $url[$k+4];
        case 4 : $a += GTB_Helper::leftShift32($url[$k+3], 24);
        case 3 : $a += GTB_Helper::leftShift32($url[$k+2], 16);
        case 2 : $a += GTB_Helper::leftShift32($url[$k+1],  8);
        case 1 : $a += $url[$k+0];
        // case 0: nothing left to add
    }
    $mix = self::hashmixJenkins2($a, $b, $c);
    $ch  = GTB_Helper::mask32($mix[2]);
    $ch  = ($encode!==TRUE) ? $ch : sprintf("6%u", $ch);
    return $ch;
  }
  // hashmixJenkins2 - Mix three 32-bit values reversibly.
  // (c) 1996 Bob Jenkins <bob_jenkins@burtleburtle.net>
  // @see         http://www.burtleburtle.net/bob/c/lookup2.c
  private static function hashmixJenkins2($a, $b, $c) {
    $a -= $b; $a -= $c; $a ^= GTB_Helper::unsignedRightShift($c, 13);
    $b -= $c; $b -= $a; $b ^= GTB_Helper::leftShift32($a, 8);
    $c -= $a; $c -= $b; $c ^= GTB_Helper::unsignedRightShift(($b & 0x00000000FFFFFFFF), 13);
    $a -= $b; $a -= $c; $a ^= GTB_Helper::unsignedRightShift(($c & 0x00000000FFFFFFFF), 12);
    $b -= $c; $b -= $a; $b  = ($b ^ (GTB_Helper::leftShift32($a, 16))) & 0x00000000FFFFFFFF;
    $c -= $a; $c -= $b; $c  = ($c ^ (GTB_Helper::unsignedRightShift($b,  5))) & 0x00000000FFFFFFFF;
    $a -= $b; $a -= $c; $a  = ($a ^ (GTB_Helper::unsignedRightShift($c,  3))) & 0x00000000FFFFFFFF;
    $b -= $c; $b -= $a; $b  = ($b ^ (GTB_Helper::leftShift32($a, 10))) & 0x00000000FFFFFFFF;
    $c -= $a; $c -= $b; $c  = ($c ^ (GTB_Helper::unsignedRightShift($b, 15))) & 0x00000000FFFFFFFF;
    return array($a, $b, $c);
  }
}//eoc
/** GTB_jenkinsHash   Hash a variable-length key into a 32-bit value.
 *  @package          GTB_PageRank
 *  @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
class GTB_ieHash extends GTB_PageRank
{
  // ieHash - Validates input, pass it to the hash function and return the result.
  public static function ieHash ($a) {
    return self::_ieHash($a);
  }
  // _ieHash - Checksum algorithm used in the IE version of the Google Toolbar.
  public static function _ieHash ($a) {
    $NumHashString = sprintf('%u', self::hashmixIE($a));
    $NumHashLength = strlen($NumHashString);
    $CheckByte = 0;
    for ($i=($NumHashLength-1); $i>=0; $i--) {
        $Num = $NumHashString{$i};
        $CheckByte += (1===($i % 2)) ? (int)((($Num*2)/10)+(($Num*2)%10)) : $Num;
    }   $CheckByte %= 10;
    if ($CheckByte !== 0) {
        $CheckByte = 10-$CheckByte;
        if (($NumHashLength % 2) === 1) {
            if (($CheckByte % 2) === 1) {
                $CheckByte += 9; }
            $CheckByte >>= 1; }
    }
    return '7'.$CheckByte.$NumHashString;
  }
  // hashmixIE - Generates a hash for a url provided by msieHash.
  public static function hashmixIE ($url) {
    $c1 =  GTB_Helper::strToNum($url, 0x1505, 0x21);
    $c2 =  GTB_Helper::strToNum($url, 0,   0x1003f);
    $c1 =  GTB_Helper::unsignedRightShift($c1, 2);
    $c1 = (GTB_Helper::unsignedRightShift($c1, 4) & 0x3ffffc0) | ($c1 &   0x3f);
    $c1 = (GTB_Helper::unsignedRightShift($c1, 4) &  0x3ffc00) | ($c1 &  0x3ff);
    $c1 = (GTB_Helper::unsignedRightShift($c1, 4) &   0x3c000) | ($c1 & 0x3fff);
    $t1 = (GTB_Helper::leftShift32( ( GTB_Helper::leftShift32( ($c1 &      0x3c0), 4) | ($c1 &   0x3c)),   2)) | ($c2 &     0xf0f);
    $t2 = (GTB_Helper::leftShift32( ( GTB_Helper::leftShift32( ($c1 & 0xffffc000), 4) | ($c1 & 0x3c00)), 0xa)) | ($c2 & 0xf0f0000);
    return GTB_Helper::mask32(($t1 | $t2));
  }
}//eoc

/** GTB_Helper        Various methods for bit and int operations.
 *  @package          GTB_PageRank
 *  @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
class GTB_Helper extends GTB_PageRank
{
  // 64-bit safe, bit-wise left shift. By James Wade.
  public static function leftShift32($x, $y) {
    $n = $x << $y;
    if (PHP_INT_MAX != 0x80000000) {
        $n = -(~($n & 0x00000000FFFFFFFF) + 1);
    } return (int)$n;
  }
  // Unsigned right bit shift
  public static function unsignedRightShift($x, $y) {
    // convert to 32 bits
    if (0xffffffff < $x || -0xffffffff > $x) {
        $x = GTB_Helper::_fmod($x, 0xffffffff + 1);
    } // convert to unsigned integer
    if (0x7fffffff < $x) {
        $x -= 0xffffffff + 1.0;
    } elseif (-0x80000000 > $x) {
        $x += 0xffffffff + 1.0;
    } // do right shift
    if (0 > $x) {
        $x &= 0x7fffffff;           # remove sign bit before shift
        $x >>= $y;                  # right shift
        $x |= 1 << (31 - $y);       # set shifted sign bit
    } else {
        $x >>= $y;                  # use normal right shift
    }
    return (int)$x;
  }
  // mask32 - On 64-bit platforms, masks integer $a and complements bits.
  public static function mask32($a) {
    if (PHP_INT_MAX != 0x0000000080000000) { # 2147483647
      $a = -(~($a  & 0x00000000FFFFFFFF) + 1);
    } return (int)$a;
  }
  // hexEncodeU32 - Returns the hexadecimal string representation for U32 integer $a.
  public static function hexEncodeU32 ($a) {
    $b  = self::toHex8(self::unsignedRightShift($a, 24));
    $b .= self::toHex8(self::unsignedRightShift($a, 16) & 255);
    $b .= self::toHex8(self::unsignedRightShift($a,  8) & 255);
    return $b . self::toHex8($a & 255);
  }
  // toHex8 - Returns the hexadecimal string representation for integer $a.
  public static function toHex8 ($a) {
    return ($a < 16 ? "0" : "") . dechex($a);
  }
  // Unicode/multibyte capable equivelant of ord().
  public static function charCodeAt($a, $b) {
    $a = mb_convert_encoding($a,"UCS-4BE","UTF-8");
    $c = unpack("N", mb_substr($a,$b,1,"UCS-4BE"));
    return $c[1];
  }
  // Turns a string of unicode characters into an array of ordinal values.
  public static function strOrds($a) {
    $b = array();
    $a = mb_convert_encoding($a,"UCS-4BE","UTF-8");
    for ($i=0; $i<mb_strlen($a,"UCS-4BE"); $i++) {
        // Now we have 4 bytes. Find their total numeric value.
        $c = unpack("N", mb_substr($a,$i,1,"UCS-4BE"));
        $b[] = $c[1]; }
    return $b;
  }
  // Converts an array of 32-bit integers into an array with 8-bit values.
  // Equivalent to (BYTE *)arr32
  public static function c32to8bit($arr32) {
    for($i=0; $i<sizeof($arr32); $i++) {
        for ($bitOrder=$i*4; $bitOrder<=$i*4+3; $bitOrder++) {
             $arr8[$bitOrder] = $arr32[$i] & 255;
             $arr32[$i] = self::unsignedRightShift($arr32[$i], 8); }
    } return $arr8;
  }
  // strToNum - Convert a string into a 32-bit integer.
  public static function strToNum ($str, $c, $k) {
    $int32unit = 4294967296; // 2^32
    for ($i=0; $i<strlen($str); $i++) {
      $c *= $k;
      if ($c >= $int32unit) {
          $c = ($c - $int32unit * (int)($c / $int32unit));
          // if $c is less than -2^31
          $c = ($c < 0x0000000080000000) ? ($c + $int32unit) : $c;
      }
      $c += GTB_Helper::charCodeAt($str, $i);
    } return $c;
  }
  public static function _fmod($x, $y) {
    $i = floor( $x / $y );
    return (int)( $x - $i * $y );
  }

  // array_rand_val - Returns $n random values from array $a.
  public static function array_rand_val($a, $n=1) {
    shuffle($a);
    $b = array();
    for ($i=0; $i<$n; $i++) {
        $b[] = $a[$i]; }
    return $n==1 ? $b[0] : $b;
  }
  // array_rand_val_assoc - Returns $n random values from assoc array $a.
  public static function array_rand_val_assoc($a, $n=1) {
    $k = array_keys($a);
    shuffle($k);
    $b = array();
    for ($i=0; $i<$n; $i++) {
        $b[$k[$i]] = $a[$k[$i]]; }
    return $b;
  }

  // use regex to match values from string, if native json_decode is not available.
  public static function _json_decode($a) {
    if (TRUE !== function_exists('json_decode')) {
		$m = array();
		preg_match_all('#"(.*?)"#si', $a, $m);
		return (isset($m[1]) && sizeof($m[1])>0) ? $m[1] : FALSE;
	} else {
		return json_decode($a);
	}
  }
}//eoc
/** GTB_Request       Connection helper methods.
 *  @package          GTB_PageRank
 *  @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
class GTB_Request extends GTB_PageRank
{
  public static function _get($url) {
	if (!function_exists('curl_init')) {
		return self::GetWithoutCurl($url); }
	else {
		return self::GetWithCurl($url); }
  }
  /**
   * HTTP GET request with curl.
   * @access    private
   * @param     string      $url        String, containing the URL to curl.
   * @return    string      Returns string, containing the curl result.
   */
  private static function GetWithCurl($url) {
	$ch  = curl_init($url);
	curl_setopt($ch,CURLOPT_USERAGENT,'' );
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch,CURLOPT_MAXREDIRS,2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$str = curl_exec($ch);
	curl_close($ch);
	return $str;
  }
}
/** GTB_Exception     GTB_PageRank exception class.
 *  @package          GTB_PageRank
 *  @author           Stephan Schmitz <eyecatchup@gmail.com>
 */
class GTB_Exception extends Exception
{
  // exitNoUrl - throws an exception and exits, when trying to create a new object on no input.
  static function noUrl() {
    header("Content-Type: text/plain;");
    throw new GTB_Exception("No Query URL defined! Use `new GTB_PageRank('http://www.domain.tld')` to create a new GTB_PageRank object.");
    exit(0);
  }
  static function tryAgain() {
    header("Content-Type: text/plain;");
    throw new GTB_Exception("Error. Please try again!");
    exit(0);
  }
}

/* DOCUMENTATION AND TEST PROGRAM - Run './GTB_PageRank.php?man' to view the content below!
 */
function print_ln()  {
	print "--------------------------------------------------------------------------------------------------------\n"; }
function print_cbb($a="") {
	if($a!="") { print_n("\nBelow, see the output of `var_dump( $a );` :"); }
	print "------------------------------------------------------------------------------------- CODEBLOCK BEGIN --\n"; }
function print_cbe() {
	print "--------------------------------------------------------------------------------------- CODEBLOCK END --\n"; }
function print_n($a="") {
	print "$a\n"; }
function print_h($a) {
	print_n(""); print_n($a); print_ln(); }
if ( TRUE !== DISABLE_MAN AND isset($_GET['man']) ) :
try {
  //init a test object
  $url  = (isset($_GET['url']) && !empty($_GET['url'])) ? $_GET['url'] : 'http://www.nahklick.de';
  $_url = new GTB_PageRank($url);
  //send docs
  if ( !headers_sent() ):
	header("Content-Type: text/plain;");
  else :
    $codewrap = TRUE;
    print "<code><pre>\n";
  endif;
  print_h("= PHP Class 'GTB_PageRank' Documentation and Test Program =\n(c) 2012 Stephan Schmitz <eyecatchup@gmail.com>");
  print_n("Note: To use the test program with another domain, run `./GTB_PageRank.php?man&url=http://domain.tld`.");
  print_ln();
    print_h("== OBJECT INITIALIZATION ==");
    print_n("To lookup the Google (Toolbar) PageRank, create a new object of the class 'GTB_PageRank'.");
    print_n("For this example, the object is assigned to the variable '\$_url'.");
    print_cbb();							# BEGIN code block
    print_n("include ('GTB_PageRank.php');");
    print_n("try {");
    print_n("  \$_url = new GTB_PageRank('$url');");
    print_n("}");
    print_n("catch (GTB_Exception \$e) {");
    print_n("  die(\$e->getMessage());");
    print_n("}");
    print_cbe(); 							# END code block
    print_h("== OBJECT MODEL ==");
    print_n("Now first, let's have a look at the class object itself.");
    print_n();
    print_n("When creating an object, it holds already all the data we need to send requests to the Toolbar Url.");
    print_n("It contains eight array keys. Below, see the simplified data object model:");
    print_cbb();
    print_n("object(GTB_PageRank)#n (8) {");
    print_n("  [\"QUERY_URL\"]  => string(n)                  # Url to get the PageRank for.");
    print_n("  [\"URL_HASHES\"] => array(4) {                 # Array of computed hashes for url key (max. Arraysize: 4).");
    print_n("    [\"awesome\"]  => string(9) \"8xxxxxxxx\"      #   9 chars, first is 8");
    print_n("    [\"jenkins\"]  => string(n) \"6xxxxxxxxx\"     #   10-12 chars, first is 6");
    print_n("    [\"jenkins2\"] => string(n) \"6xyxxxyxxy\"     #   10-12 chars, first is 6");
    print_n("    [\"ie\"]       => string(n) \"7xxxxxxxxxx\"}   #   11-12 chars, first is 7");
    print_n("  [\"PREFERED_TLD\"] => string(n)                # The Toolbar top level domain *you* prefer.");
    print_n("  [\"GTB_SUGESSTED_TLD\"] => string(n)           # The Toolbar top level domain Google suggests to you.");
    print_n("  [\"GTB_QUERY_STRINGS\"] => string(n)           # Array of possible path combination, based on different hashes (max. Arraysize: 4).");
    print_n("  [\"GTB_SERVER\"] => array(3) {                 # Array containing the Toolbar server adress parts.");
    print_n("    [\"host\"]  => array(2)                      #   Array containing valid toolbar host names.");
    print_n("    [\"tld\"]  => array(138)                     #   Array containing valid toolbar top level domains.");
    print_n("    [\"path\"] => string(4) \"/tbr\" }             #   The toolbar request path.");
    print_n("}");
    print_cbe();
    print_n("For the initialized test URL, the full object looks as follows.");
    print_cbb("\$_url"); 					# BEGIN code block
    var_dump( $_url );
    print_cbe(); 							# END code block
    print_h("== GET THE PAGERANK ==");
    print_n("As we saw, the object holds all data we need to request the PageRank.");
	print_n("Now, it is just a question of how you work with the data.");
	print_n();
	print_n("Need a single hash key? Try one of:");
    print_cbb(); 							# BEGIN code block
    print_n("\t`\$_url->GPR_ieHash()`\n\t`\$_url->GPR_jenkinsHash()`\n\t`\$_url->GPR_jenkinsHash2()` or \n\t`\$_url->GPR_awesomeHash()` :");
    print_n("Output:");
	print_n("\t".$_url->GPR_ieHash()."\n\t".$_url->GPR_jenkinsHash()."\n\t".$_url->GPR_jenkinsHash2()."\n\t".$_url->GPR_awesomeHash() );
    print_cbe(); 							# END code block
	print_n();
    print_n("The same could be achieved using the `getHash(key)` method providing one of the key names");
    print_n("'awesome', 'jenkins', 'jenkins2', or 'ie'.");
    print_cbb("\$_url->getHash('awesome')");# BEGIN code block
    var_dump( $_url->getHash('awesome') );
    print_cbe(); 							# END code block
	print_n();
    print_n("You want all-in-one? `\$_url->getHashes()` will give it to you!");
    print_cbb("\$_url->getHashes()");# BEGIN code block
    var_dump( $_url->getHashes() );
    print_cbe(); 							# END code block
	print_n();
    print_n("You want all-in-one? `\$_url->getHashes()` will give it to you!");
    print_cbb("\$_url->getPageRank()");# BEGIN code block
    var_dump( $_url->getQueryUrls() );
    print_cbe(); 							# END code block
  if( isset($codewrap) ) {
    print "</pre></code>";
  }
} catch (GTB_Exception $e) {
  die($e->getMessage());
}
endif;//eof
