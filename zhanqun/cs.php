<?php 
session_start();
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ERROR);
set_time_limit(0);

function setarray() {
    return array('blfile' => 'bl.txt', 'btfile' => 'bt.txt', 'keyfile' => 'key.txt', 'templetefile' => 'mb.txt', 'index' => 'mbs.txt', 'picfile' => 'pic.txt', 'txtfile' => 'txt.txt', 'dbsfile' => 'dbs.txt', 'mapfile' => 'map.txt', 'nlfile' => 'links.txt', 'linkfile' => 'link.txt', 'isopenext' => true);
}

define('_sj_', date('Ymd', time()));

function getTXT($srt) {
    $header = get_headers($srt, TRUE);
    return (isset($header[0]) && (strpos($header[0], '200'))? TRUE : FALSE);
}

function getUnicode($str, $prefix = '&#', $postfix = ';') {
    $str = str_split(iconv('UTF-8', 'UCS-2', $str), 2);
    for($i = 0, $len = count($str); $i < $len; $i++) {
        $dec = hexdec(bin2hex($str[$i]));
        $unistr[] = $prefix . $dec . $postfix;
    } 
    return implode('',$unistr);
}

function getKeys($digits = 9) {
    if($digits == 6) {
        $chars = 'ABCDEF0123456789';
        $max = 15;
    } else {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $max = 61;
    }
    $hash = '';
    for ($i = 0; $i < $digits; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

function getKey($digits = 6, $s = 1) {
    switch ($s) {
        case "4":
            $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $max = 35;
            break;
        case "3":
            $chars = 'bpmfdtnlgkhjqxrzcsyw';
            $max = 19;
            break;
        case "2":
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $max = 61;
            break;
        case "1":
            $chars = 'abcdefghijklmnopqrstuvwxyz';
            $max = 25;
            break;
        case "0":
            $chars = '0123456789';
            $max = 9;
            break;
    }
    $hash = '';
    for ($i = 0; $i < $digits; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

function dy_sk($str, $split_len = 1) {
    if (!preg_match('/^[0-9]+$/', $split_len) || $split_len < 1) {
        return FALSE;
    }
    $str = GetUTF8($str);
    $len = mb_strlen($str, 'UTF-8');
    if ($len <= $split_len) {
        return $str;
    }
    preg_match_all('/.{' . $split_len . '}|[^x00]{1,' . $split_len . '}$/us', $str, $ar);
    return join(" ", $ar[0]);
}

function Pinyin($_String, $_Code = '0') {
    $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" . "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" . "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" . "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" . "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" . "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" . "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" . "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" . "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" . "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" . "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" . "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" . "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" . "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" . "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" . "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
    $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" . "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" . "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" . "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" . "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" . "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" . "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" . "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" . "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" . "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" . "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" . "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" . "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" . "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" . "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" . "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" . "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" . "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" . "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" . "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" . "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" . "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" . "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" . "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" . "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" . "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" . "|-10270|-10262|-10260|-10256|-10254";
    $_TDataKey = explode('|', $_DataKey);
    $_TDataValue = explode('|', $_DataValue);
    $_Data = PHP_VERSION >= '5.0' ? array_combine($_TDataKey, $_TDataValue) : _Array_Combine($_TDataKey, $_TDataValue);
    arsort($_Data);
    reset($_Data);
    $_Res = '';
    $_String = iconv('UTF-8', 'GBK', $_String);
    for ($i = 0; $i < strlen($_String); $i++) {
        $_P = ord(substr($_String, $i, 1));
        if ($_P > 160) {
            $_Q = ord(substr($_String, ++$i, 1));
            $_P = $_P * 256 + $_Q - 65536;
        }
        if ($_Code) {
            $_Res .= substr(_Pinyin($_P, $_Data), 0, 1);
        } else {
            $_Res .= _Pinyin($_P, $_Data);
        }
    }
    return preg_replace('/[^a-z0-9]*/', '', $_Res);
}

function _Pinyin($_Num, $_Data) {
    if ($_Num > 0 && $_Num < 160) {
        return chr($_Num);
    } elseif ($_Num < -20319 || $_Num > -10247) {
        return '';
    } else {
        foreach ($_Data as $k => $v) {
            if ($v <= $_Num) {
                break;
            }
        }
        return $k;
    }
}

function setPath() {
    $path = '';
    if (isset($_SERVER['REQUEST_URI'])) {
        $path = $_SERVER['REQUEST_URI'];
    } else {
        if (isset($_SERVER['argv'])) {
            $path = $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
        } else {
            $path = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        }
    }
    if (isset($_SERVER['SERVER_SOFTWARE']) && false !== stristr($_SERVER['SERVER_SOFTWARE'], 'IIS')) {
        if (function_exists('mb_convert_encoding')) {
            $path = mb_convert_encoding($path, 'UTF-8', 'GBK');
        } else {
            $path = @iconv('GBK', 'UTF-8', @iconv('UTF-8', 'GBK', $path)) == $path ? $path : @iconv('GBK', 'UTF-8', $path);
        }
    }
    $r = explode('#', $path, 2);
    $path = $r[0];
    $path = str_ireplace("http://" . ($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']) . "/", '', $path);
    $path = str_ireplace("http://" . ($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']) . ":" . $_SERVER['SERVER_PORT'] . "/", '', $path);
    return $path;
}

function GetRanNum($min, $max) {
    return mt_rand($min, $max);
}

function GetRanrq() {
    return mt_rand(2006, 2019) . '年' . sprintf('%02d', mt_rand(1, 12)) . '月' . sprintf('%02d', mt_rand(1, 28)) . '日';
}

function GetUTF8($str) {
    return mb_convert_encoding($str, 'utf-8', mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')));
}

function GetRanrq1() {
    return mt_rand(2006, 2019) . '-' . sprintf('%02d', mt_rand(1, 12)) . '-' . sprintf('%02d', mt_rand(1, 28));
}

function dyy_xgl($aa) {
    $xxgl[0] = chr(7);
    $xxgl[1] = chr(6);;
    $xxgl[2] = chr(5);;
    $xxgl[3] = chr(8);;
    $ds = mt_rand(3, 5);
    $hash = "";
    for ($i = 0; $i < $ds; $i++) {
        $hash .= $xxgl[mt_rand(0, 3)];
    }
    return $hash . $aa[0];
}

function mainshow($allnum, $appsplit, $hostpath, $hostkey, $templetePath, $myArt, $txtid, $linkArt, $linkid, $picArt, $picid, $blArt, $blid, $btArt, $btid, $zmlm, $ext, $mlm) {
    $scmlm = "";
    global $skinext, $kwz, $kym, $wse, $xwbt, $cdg;
    $skinextk = $skinext;
    if ($skinextk == "/index.html") {
        $skinextk = "/";
    }
    if ($zmlm) {
        $scmlm = $zmlm . "/";
    }
    if($cdg == '1' && $xwbt == 2){
        $rtemplete = preg_replace('/\$站名\$/', trim(getUnicode(GetUTF8($wse))), $templetePath);
        $rtemplete = preg_replace('/\$标题\$/', trim(getUnicode(GetUTF8($hostkey))), $rtemplete);
    }else{
        $rtemplete = preg_replace('/\$站名\$/', trim(GetUTF8($wse)), $templetePath);
        $rtemplete = preg_replace('/\$标题\$/', trim(GetUTF8($hostkey)), $rtemplete);
    }
    $rtemplete = preg_replace('/\$标题k\$/', trim(GetUTF8(dy_sk($hostkey))), $rtemplete);
    $rtemplete = preg_replace('/\$当前地址\$/', '' . $kwz . $scmlm . $hostpath . $skinextk, $rtemplete);
    $ahost = readApp($appsplit, GetRanNum(0, $allnum - 1));
    $rtemplete = preg_replace('/\$作者链接\$/', '<a href="' . $kwz . $scmlm . $ahost[0] . $skinextk . '" target="_bank">' . GetUTF8($ahost[1]) . '</a>', $rtemplete);
    $rtemplete = preg_replace('/\$域名\$/', $kym, $rtemplete);
    $rtemplete = preg_replace('/\$时间\$/', date("Y-m-d H:i:s"), $rtemplete);
    $rtemplete = preg_replace('/\$时间1\$/', date("Y-m-d H:i"), $rtemplete);
    $rtemplete = preg_replace('/\$时间2\$/', date("Y年m月d日 H:i:s"), $rtemplete);
    $rtemplete = preg_replace('/\$时间3\$/', date("Y年m月d日 H:i"), $rtemplete);
    $rtemplete = preg_replace('/\$日期\$/', date("Y年m月d日"), $rtemplete);
    $rtemplete = preg_replace_callback("/{随机日期}/iUs", "GetRanrq", $rtemplete);
    $rtemplete = preg_replace('/\$日期1\$/', date("Y-m-d"), $rtemplete);
    $rtemplete = preg_replace_callback("/{随机日期1}/iUs", "GetRanrq1", $rtemplete);
    $rtemplete = preg_replace('/\$干扰字符\$/', getKeys(), $rtemplete);
    $rtemplete = preg_replace_callback("/{干扰字符}/iUs", "getKeys", $rtemplete);
    $srt = count(explode('$内容$', $rtemplete)) - 1;
    for ($ii = 0; $ii < $srt; $ii++) {
        $rtemplete = preg_replace('/\$内容\$/', trim(GetUTF8($myArt[mt_rand(0, $txtid)])), $rtemplete, 1);
    }
    $srt = count(explode('$干扰内容$', $rtemplete)) - 1;
    for ($ii = 0; $ii < $srt; $ii++) {
        $rtemplete = preg_replace('/\$干扰内容\$/', trim(getKeys('6')), $rtemplete, 1);
    }
    $srt1 = count(explode('$关键字1$', $rtemplete)) - 1;
    $srt2 = count(explode('$关键字2$', $rtemplete)) - 1;
    $srt3 = count(explode('$关键字k$', $rtemplete)) - 1;
    $srt4 = count(explode('$匹配地址$', $rtemplete)) - 1;
    $srt = max(array($srt1,$srt3,$srt3,$srt4));
    for ($ii = 0; $ii < $srt; $ii++) {
        $bhostarr = readApp($appsplit, GetRanNum(0, $allnum - 1));
        $rtemplete = preg_replace('/\$关键字\$/', trim(GetUTF8($bhostarr[1])), $rtemplete);
        $rtemplete = preg_replace('/\$关键字1\$/', trim(GetUTF8($bhostarr[1])), $rtemplete, 1);
        $rtemplete = preg_replace('/\$关键字2\$/', trim(GetUTF8($bhostarr[1])), $rtemplete, 1);
        $rtemplete = preg_replace('/\$匹配地址\$/',  '' . $kwz . $scmlm . $bhostarr[0] . $skinextk, $rtemplete, 1);
    }
    $srt = count(explode('$随机数$', $rtemplete)) - 1;
    for ($ii = 0; $ii < $srt; $ii++) {
        $rtemplete = preg_replace('/\$随机数\$/', mt_rand(1, 9999), $rtemplete, 1);
    }
    $srt = count(explode('$外链$', $rtemplete)) - 1;
    for ($ii = 0; $ii < $srt; $ii++) {
        $rtemplete = preg_replace('/\$外链\$/', trim(GetUTF8($linkArt[mt_rand(0, $linkid)])), $rtemplete, 1);
    }
    $srt = count(explode('$图片$', $rtemplete)) - 1;
    for ($ii = 0; $ii < $srt; $ii++) {
        $rtemplete = preg_replace('/\$图片\$/', trim($picArt[mt_rand(0, $picid)]), $rtemplete, 1);
    }
    $rtemplete = preg_replace('/\$随机变量\$/', trim(GetUTF8($blArt[mt_rand(0, $blid)])), $rtemplete);
    $srt = count(explode('$新闻标题$', $rtemplete)) - 1;
    for ($ii = 0; $ii < $srt; $ii++) {
        $rtemplete = preg_replace('/\$新闻标题\$/', trim(GetUTF8($btArt[mt_rand(0, $btid)])), $rtemplete, 1);
    }
    $rtemplete = preg_replace_callback("/(。|，|：|、)/iUs", "dyy_xgl", $rtemplete);
    $file = $scmlm . $hostpath . $skinext;
    if ($ext == 3) {
        $scmlm .= $hostpath;
    } else {
        if ($mlm == 6 || $mlm == 5 || $mlm == 4) {
            $srt = explode('/', $hostpath);
            $scmlm .= str_replace("/" . end($srt), "", $hostpath);
        }
    }
    if ($scmlm) {
        @mkdir($scmlm . '/', 0777, TRUE);
    }
    preg_match('/charset=([^"]*?)"/is', $rtemplete, $xgdbs);
    $charset = strtolower(trim($xgdbs[1]));
    if (!$charset) {
        preg_match('/charset="([^"]*?)"/is', $rtemplete, $xgdbs);
        $charset = strtolower(trim($xgdbs[1]));
    }
    if (!$charset) {
        $charset = "UTF-8";
    }
    if ($charset != "UTF-8") {
        $rtemplete = mb_convert_encoding($rtemplete, $charset, "UTF-8");
    }
    $james = fopen($file, "w");
    fwrite($james, $rtemplete);
    fclose($james);
    unset($rtemplete);
    echo $hostkey . "：" . $file . '　success!<br>';
}

function getApp($array, $allnum, $mlm, $yml, $eml) {
    $str = '';
    for ($i = 0; $i < $allnum; $i++) {
        $HOST_HtmlKey = trim($array[$i]);
        if ($HOST_HtmlKey) {
            $hots = '';
            switch ($mlm) {
                case "9":
                    $ddzq = Pinyin($HOST_HtmlKey) . $i . "";
                    break;
                case "8":
                    $ddzq = Pinyin($HOST_HtmlKey) . "";
                    break;
                case "7":
                    $ddzq = Pinyin($HOST_HtmlKey, 1) . "";
                    break;
                case "6":
                    $ddzq = getKey(5, 2) . "/";
                    $hots = 100000 + $i;
                    break;
                case "5":
                    $ddzq = getKey(5) . "/";
                    $hots = 100000 + $i;
                    break;
                case "4":
                    $ddzq = getKey(mt_rand(2, 6), $yml) . "/";
                    $hots = getKey(mt_rand(2, 8), $eml);
                    break;
                default:
                    $ddzq = "";
                    $hots = getKey(mt_rand(2, 8), $eml);
            }
            $str .= $ddzq . $hots . '#' . $HOST_HtmlKey . '$';
        }
    }
    return $str;
}

function readApp($apps_split, $ic) {
    $appsHtml = $apps_split[$ic];
    $appArray = explode('#', $appsHtml);
    return $appArray;
}

function splitReadApp($allnum) {
    $set = setarray();
    $apps = @file_get_contents($set['dbsfile']);
    if(empty($apps)){return false;}
    $apps_split = explode('$', $apps);
    return $apps_split;
}

$wz = setPath();
$uriArr = preg_split("#/+#", $wz);
if(md5($_SERVER['SCRIPT_NAME']) == md5($wz)){$_SESSION['time'] = md5(date("Y-m-d H:i:s"));}
$ml = "http://" . $_SERVER['SERVER_NAME'] . str_replace($uriArr[count($uriArr) - 1], "", $wz);
$wjmc = @end(explode('/', $_SERVER['PHP_SELF']));
$mlss = str_replace($uriArr[count($uriArr) - 1], "", $wz);
if (isset($_GET["kwz"])) {
    $_SESSION['kkwz'] = $_GET["kwz"];
}
$kwz = isset($_SESSION['kkwz'])?$_SESSION['kkwz']:'';
preg_match('/^(\\w+:\\/\\/)?([^\\/]+)/i', $kwz, $matches);
$kym = isset($matches[2])?$matches[2]:'';
if ($kwz) {
    $ml = $kwz;
}
if (!$kwz) {
    $kwz = $ml;
}
$wse = isset($_GET["wse"])?$_GET["wse"]:'测试网站';
$xwbt = isset($_GET["xwbt"])?$_GET["xwbt"]:'';
$run = isset($_GET["ing"])?$_GET["ing"]:'';
$allnum = isset($_GET["allnum"])?$_GET["allnum"]:'';
$pns = isset($_GET["pn"])?$_GET["pn"]:'';
$pn = 100;
$page = isset($_GET["page"])?$_GET["page"]:'';
$ext = isset($_GET["ext"])?$_GET["ext"]:'';
$yml = isset($_GET["yml"])?$_GET["yml"]:'';
$eml = isset($_GET["eml"])?$_GET["eml"]:'';
$durl = isset($_GET["durl"])?$_GET["durl"]:'';
$zmlm = isset($_GET["zmlm"])?$_GET["zmlm"]:'';
$mlm = isset($_GET["mlm"])?$_GET["mlm"]:'4';
$cdg = isset($_GET["coding"])?$_GET["coding"]:'';
if ($zmlm) {
    if (!preg_match("/^[0-9a-zA-Z]*$/", $zmlm)) {
        echo "自定义目录名称请使用英文和数字";
        return false;
    }
} else {
    $kmd5 = md5($kwz);
    if ($yml == 99) {
        $mlm = 1;
    }
    switch ($mlm) {
        case "9":
            $zmlm = substr($kmd5,0,5);
            $ext = 3;
            break;
        case "8":
            $zmlm = substr($kmd5,0,5);
            $ext = 3;
            break;
        case "7":
            $zmlm = substr($kmd5,0,5);
            $ext = 3;
            break;
        case "6":
            $zmlm = substr($kmd5,0,5);
            break;
        case "5":
            $zmlm = substr(preg_replace('/[^0-9]/','',$kmd5),0,5);
            break;
        case "4":
            $zmlm = substr(preg_replace('/[^a-z]/','',$kmd5),0,5);
            break;
        case "3":
            $zmlm = date("Ym");
            break;
        case "2":
            $zmlm = date("Ymd");
            break;
        case "1":
            $zmlm = "";
            break;
    }
}
switch ($ext) {
    case "3":
        $skinext = "/index.html";
        break;
    case "2":
        $skinext = ".shtml";
        break;
    case "1":
        $skinext = ".html";
        break;
}
if ($run == 'update') {
    if(empty($_SESSION['time'])){die('请求非法，请返回首页');exit;}
    $set = setarray();
    if(empty($durl)){
        if (!file_exists($set['blfile'])) {
            die('当前目录TXT不存在');exit;
        }
    } else {
        if (empty(getTXT($durl . $set['blfile']))) {
            die('远程调用TXT不存在');exit;
        }
    }
    $ipage = isset($_GET["ipage"])?$_GET["ipage"]:'';
    if (!preg_match('/^[0-9]*$/', $ipage)) {
        $ipage = 0;
        if (file_exists($set['dbsfile'])) {
            unlink($set['dbsfile']);
        }
    }
    if ($ipage == "") {
        $ipage = 0;
        if (file_exists($set['dbsfile'])) {
            unlink($set['dbsfile']);
        }
    }
    $key = @file($durl . $set['keyfile']);
    if ($xwbt == 2) {
        $allnum = count($key);
        if ($allnum > $pns) {
            $allnum = $pns;
        }
    } else {
        $allnum = $pns;
    }
    if ($xwbt == 1) {
        $btArt = $key = @file($durl . $set['btfile']);
    }
    $fn = $set['dbsfile'];
    shuffle($key);
    $word = getApp($key, $allnum, $mlm, $yml, $eml);
    if (file_exists($set['dbsfile'])) {
        $fp = fopen($fn, 'a');
    } else {
        $fp = fopen($fn, 'w');
    }
    fwrite($fp, $word);
    fclose($fp);
    if ($xwbt == 1) {
        $btid = count($btArt) - 1;
        $listhtml = "";
        $appsplit = splitReadApp($allnum);
        if(empty($appsplit)){die('请求非法，请返回首页');exit;}
        $scmlm = "";
        $skinextk = $skinext;
        if ($zmlm) {
            $scmlm = $zmlm . "/";
        }
        for ($jj = 0; $jj <= $allnum - 1; $jj++) {
            $hx = readApp($appsplit, $jj);
            $hxpath = $hx[0];
            $hxkey = $hx[1];
            $btt = trim($btArt[mt_rand(0, $btid)]);
            $listhtml .= "<a href='" . $ml . $scmlm . $hxpath . $skinext . "' target='_bank'>" . $hxkey . "</a>\r\n";
            $linkArt[$jj] = "<a href='" . $ml . $scmlm . $hxpath . $skinext . "' target='_bank'>" . $hxkey . "</a>\r\n";
        }
        $linkid = $jj;
        if (file_exists($set['linkfile'])) {
            $james = fopen($set['linkfile'], 'a');
        } else {
            $james = fopen($set['linkfile'], 'w');
        }
        fwrite($james, $listhtml);
        fclose($james);unset($james);
    }
    echo '关键词处理完毕，转向生成文件<script>setTimeout(function(){window.location.href=\'?ing=run&allnum=' . $allnum . "&coding=" . $cdg . "&wse=" . $wse . "&pn=" . $pn . "&yml=" . $yml . "&eml=" . $eml . "&kwz=" . $kwz . "&ext=" . $ext . "&durl=" . $durl . "&mlm=" . $mlm . "&xwbt=" . $xwbt . "&zmlm=" . $zmlm . "';},3000)</script>";
    return false;
}
if ($run == 'run') {
    if(empty($_SESSION['time'])){die('请求非法，请返回首页');exit;}
    if (!preg_match("/^[0-9]*$/", $allnum)) {
        echo "生成数量不能为空";
        return false;
    }
    if (!preg_match('/^[0-9]*$/', $pn)) {
        echo "每页生成数量不能为空";
        return false;
    }
    if (!preg_match('/^[0-9]*$/', $page)) {
        $page = 1;
    }
    if ($page == "") {
        $page = 1;
    }
    $scmlm = "";
    if ($zmlm) {
        $scmlm = $zmlm . "/";
    }
    $appsplit = splitReadApp($allnum);
    if(empty($appsplit)){die('请求非法，请返回首页');exit;}
    $xpage = $page - 1;
    $startNum = $xpage * $pn;
    if ($startNum > $allnum - 1) {
        $listtxt = "";
        $listhtm = "<!doctype html><html lang=\"zh-cn\"><head><meta charset=\"utf-8\"><title>地图</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=\"UTF-8\"></head><body leftmargin=\"0\" topmargin=\"50\">";
        $set = setarray();
        $btArt = @file($durl . $set['btfile']);
        $btid = count($btArt) - 1;
        for ($jj = 0; $jj <= $allnum - 1; $jj++) {
            $hosthtml = readApp($appsplit, $jj);
            $hostpath = $hosthtml[0];
            $hostkey = $hosthtml[1];
            $listhtm .= "<li><a href='" . $ml . $scmlm . $hostpath . $skinext . "' target='_bank'>" . $hostkey . "</a></li>";
            $lists[$jj] = "<li><a href='" . $ml . $scmlm . $hostpath . "" . $skinext . "'>" . $ml . $scmlm . $hostpath . $skinext . "</a></li>";
            $listtxt .= $ml . "" . $scmlm . $hostpath . $skinext . "\r\n";
        }
        $listhtm .= "</body></html>";
        $james = fopen('map.html', "w");
        fwrite($james, $listhtm);
        fclose($james);unset($james);
        if ($xwbt == 2) {
            if (file_exists($set['mapfile'])) {
                $james = fopen($set['mapfile'], 'a');
            } else {
                $james = fopen($set['mapfile'], 'w');
            }
            fwrite($james, $listtxt);
            fclose($james);unset($james);
            $utxtname = $set['mapfile'];
            $fileindex = "index.html";
            if (!is_file($fileindex)) {
                $fileindex = "index.htm";
            }
            if (is_file($fileindex)) {
                $indexhtml = file_get_contents($fileindex);
            } else {
                $fileindex = "index.html";
                if(empty($durl)){
                    if (file_exists($set['index'])) {
					    $indexhtml = file_get_contents($set['index']);
				    }else{
                        $indexhtml = file_get_contents('http://' . $_SERVER['SERVER_NAME']);
				    }
                } else {
                    if (empty(getTXT($durl . $set['index']))) {
                        $indexhtml = file_get_contents('http://' . $_SERVER['SERVER_NAME']);
				    }else{
					    $indexhtml = file_get_contents($durl . $set['index']);
				    }
                }
            }
            preg_match('/charset=([^"]*?)"/is', $indexhtml, $xgdbs);
            $charset = !empty($xgdbs)?strtolower(trim($xgdbs[1])):'UTF-8';
            if (!$charset) {
                preg_match('/charset="([^"]*?)"/is', $indexhtml, $xgdbs);
                $charset = !empty($xgdbs)?strtolower(trim($xgdbs[1])):'UTF-8';
            }
            $indexhtml = preg_replace('/<div class="y9n9q8p8" style="position:fixed;left:-3000px;top:-3000px;">(.*?)<\/div class="y9n9q8p8">/is', '', $indexhtml);
            shuffle($lists);
            $lists = array_slice($lists, 0, 100);
            $chalink = '<div class="y9n9q8p8" style="position:fixed;left:-3000px;top:-3000px;">
';
            for ($i = 0; $i < count($lists); $i++) {
                $chalink .= "" . trim($lists[$i]) . "\r\n";
            }
            $chalink .= '</div class="y9n9q8p8">';
            if (!$charset) {
                $charset = "UTF-8";
            }
            if ($charset != "UTF-8") {
                $chalink = mb_convert_encoding($chalink, $charset, "UTF-8");
            }
            $indexlink = file($set['nlfile']);
            $indexhtml = preg_replace("/<body([^>]*?)>/i", "<body$1>" . $chalink, $indexhtml);
            if($cdg == '1'){
                $indexhtml = preg_replace('/\$站名\$/', trim(getUnicode(GetUTF8($wse))), $indexhtml);
            }else{
                $indexhtml = preg_replace('/\$站名\$/', trim(GetUTF8($wse)), $indexhtml);
            }
            $indexhtml = preg_replace('/\$域名\$/', trim($kym), $indexhtml);
            $indexhtml = preg_replace('/\$链接\$/', trim($kwz), $indexhtml);
            $indexhtml = preg_replace('/\$邮箱\$/', 'info@'.trim(str_replace(array('www.','ww.','w.'),'',$kym)), $indexhtml);
            $srt = count(explode('$内链$', $indexhtml)) - 1;
            for ($ii = 0; $ii < $srt; $ii++) {
                $indexhtml = preg_replace('/\$内链\$/', trim($indexlink[mt_rand(0,count($indexlink)-1)]), $indexhtml, 1);
            }
            $james = fopen($fileindex, "w");
            fwrite($james, $indexhtml);
            fclose($james);unset($james);
        } else {
            $utxtname = $set['linkfile'];
            if (file_exists($set['nlfile'])) {
                $nlfile = fopen($set['nlfile'], 'a');
            } else {
                $nlfile = fopen($set['nlfile'], 'w');
            }
            fwrite($nlfile, $listtxt);
            fclose($nlfile);unset($nlfile);
        }
        echo '处理完毕！<a href=\'map.html\'>点此查看</a> <a href=\'' . $utxtname . "'>点此查看urltxt</a>";
        unlink($set['dbsfile']);unset($_SESSION['time']);
        if ($xwbt == 2) {
            //unlink($wjmc);
        }
        return false;
    }
    $endNum = $page * $pn;
    if ($endNum > $allnum - 1) {
        $endNum = $allnum - 1;
    }
    $set = setarray();
    $linkArt = file($set['linkfile']);
    $linkid = count($linkArt) - 1;
    if ($xwbt == 2 && empty($linkArt)) {
        die('新闻标题页面不能为空');exit;
    }
    echo '正在处理数据：' . $startNum . "-" . $endNum . "/进度：" . $startNum / $allnum * 100 . "%<br>";
    $templetePath = file_get_contents($durl . $set['templetefile']);
    $myArt = @file($durl . $set['txtfile']);
    $txtid = count($myArt) - 1;
    $picArt = @file($durl . $set['picfile']);
    $picid = count($picArt) - 1;
    $blArt = @file($durl . $set['blfile']);
    $blid = count($blArt) - 1;
    $btArt = @file($durl . $set['btfile']);
    $btid = count($btArt) - 1;
    for ($jj = $startNum; $jj <= $endNum; $jj++) {
        $hosthtml = readApp($appsplit, $jj);
        $hostpath = $hosthtml[0];
        $hostkey = $hosthtml[1];
        mainshow($allnum, $appsplit, $hostpath, $hostkey, $templetePath, $myArt, $txtid, $linkArt, $linkid, $picArt, $picid, $blArt, $blid, $btArt, $btid, $zmlm, $ext, $mlm);
    }
    unset($templetePath, $myArt, $txtid, $linkArt, $linkid, $picArt, $picid, $blArt, $blid, $btArt, $btid);
    echo '<script>setTimeout(function(){window.location.href=\'?ing=run&allnum=' . $allnum . "&coding=" . $cdg . "&wse=" . $wse . "&pn=" . $pn . "&yml=" . $yml . "&eml=" . $eml . "&kwz=" . $kwz . "&xwbt=" . $xwbt . "&ext=" . $ext . "&durl=" . $durl . "&mlm=" . $mlm . "&zmlm=" . $zmlm . "&page=" . ($page + 1) . "';},5000)</script>";
    return false;
}
?>
<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>
<body>
<form id="form1" name="form1" method="get" action="">
<div style="border-bottom:#ccc 1px dashed;height:30px;">
远程配置地址 <input name="durl" type="text" id="durl" value="" size="30" /> *远程标题内容库服务器地址 http://domain.com/123/</div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
自定义前网址 <input name="kwz" type="text" id="kwz" value="<?php echo $kwz; ?>" size="30" /></div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
自定义网站名 <input name="wse" type="text" id="wse" value="<?php echo $wse; ?>" size="30" /></div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
每次生成页数 <input name="pn" type="text" id="pn" value="10" size="30" /> *建议默认100，不用设置</div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
自定义目录名 <input name="zmlm" type="text" id="zmlm" value="html" size="30" /> *填写自定义前目录 留空为随机目录</div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
标题站名转码 <input type="radio" name="coding" value="1" checked="checked" />是 
<input type="radio" name="coding" value="2" />否  *本功能仅转关键词和首页网站名称</div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
一级目录规则 <input type="radio" name="yml" value="0" checked="checked" />数字 
<input type="radio" name="yml" value="3" />字母 
<input type="radio" name="yml" value="4" />组合 <input type="radio" name="yml" value="99" />关闭一级目录 组合 *自定义目录时选择无效</div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
二级目录规则 <input type="radio" name="eml" value="0" checked="checked" />数字 
<input type="radio" name="eml" value="3" />字母 
<input type="radio" name="eml" value="4" />组合</div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
高级目录命名 <input type="radio" name="mlm" value="5" />abc/123 
<input type="radio" name="mlm" value="6" />a1B2c/123  
<input type="radio" name="mlm" value="7" />gjml/123 
<input type="radio" name="mlm" value="8" />gaojimulu/123  
<input type="radio" name="mlm" value="9" />gaojimulu23/123 *选择后二级目录规则无效</div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
新闻标题页面 <input type="radio" name="xwbt" value="1" checked="checked" />是 
<input type="radio" name="xwbt" value="2" />否  *先生成新闻标题页面再生成关键字页面</div>
<div style="border-bottom:#ccc 1px dashed;height:30px;">
生成目录内页 <input type="radio" name="ext" value="1" checked="checked" />HTML内页 <input type="radio" name="ext" value="2" />SHTML内页 <input type="radio" name="ext" value="3" />目录</div>
<input name="ing" type="hidden" id="ing" value="update" size="10" />
<input type="submit" name="button" id="button" value="提交" />
</form>
</body>
</html>