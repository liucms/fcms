<?php 
header("Content-Type: text/html; charset=utf-8"); 
$IP = getRandIP();
if(empty($_GET['id'])) {
$dom = getTxt('domain.txt');
$tmp = getHtml('muban.html');
$links = content_re($tmp, $dom);
$html = preg_replace('/{当前域名}/', trim(getHost()), $tmp);
$html = preg_replace('/{标题}/', trim($links['title']), $html);
$html = preg_replace('/{根域名}/', trim($links['domain']), $html);
$html = preg_replace('/{当前链接}/', empty($_GET)?'':''.trim($links['list']).'/'.trim($links['id']).'.html', $html);
$html = preg_replace('/{时间}/', trim(date("Y-m-d H:i:s",trim($links['time']))), $html);
if(!empty($links['url'])){
    foreach($links['url'] as $k => $v){
        $html = str_replace('{内链'.trim($v[0]).'}', '<a href="//'.trim($v[1]).'.'.$links['domain'].'/" title="'.trim($v[2]).'">'.trim($v[2]).'</a>', $html);
    }
}
if(!empty($links['article'])){
    foreach($links['article'] as $k => $v){
        $html = str_replace('{内容'.trim($v[0]).'}', trim($v[1]), $html);
    }
}
if(!empty($links['links'])){
    foreach($links['links'] as $k => $v){
        $srtRand = getSrtRand(1, 6); // 随机字符 替换 固定字符$v[1]
        $html = str_replace('{外链'.trim($v[0]).'}', '<a href="/'.trim($links['list']).'/'.trim($v[2]).'.html" title="'.trim($v[3]).$srtRand.'" target="_blank">'.trim($v[3]).trim($srtRand).'</a>', $html);
    }
}
if(!empty($links['image'])){
    foreach($links['image'] as $k => $v){
        $html = str_replace('{随机图片'.trim($v[0]).'}', '<img layout="responsive" width="350" height="263" src="'.trim($v[1]).'" alt="'.trim($links['title']).(trim($v[0])+1).'">', $html);
    }
}
if(!empty($links['randsrt'])){
    foreach($links['randsrt'] as $k => $v){
        $html = str_replace('{随机字符'.trim($v[0]).'}', trim($v[1]), $html);
    }
}
if(!empty($links['tops'])){
    foreach($links['tops'] as $k => $v){
        $html = str_replace('{排行榜'.trim($v[0]).'}', trim($v[1]), $html);
    }
}
echo $html;
}
if(!empty($_GET['id'])&&!empty($_GET['n'])) {
    preg_match('/^[0-9]{1,}$/', $_GET['id'], $id);
    $keywords = @file(__DIR__.DIRECTORY_SEPARATOR.'keywords.txt');
    echo '已采集 '.count($keywords).' 条数据';
    if(!empty($keywords[$id[0]-1])){
        $data = mb_convert_encoding(trim($keywords[$id[0]-1]), 'utf-8', mb_detect_encoding(trim($keywords[$id[0]-1]), array('ASCII','UTF-8','GB2312','GBK','LATIN1','BIG5')));
        do {
            $baidu = getBaidu(trim($data));
        } while ($baidu == '采集失败');
        do{
            $sogou = getSogou(trim($data));
        } while ($sogou == '采集失败');
        do {
            $senma = getShenma(trim($data));
        } while ($senma == '采集失败');
        do {
            $socom = getSocom(trim($data));
        } while ($socom == '采集失败');
        if(is_array($baidu)){
            $keywords = array_values(array_flip(array_flip(array_merge($keywords, $baidu))));
        }
        if(is_array($sogou)){
            $keywords = array_values(array_flip(array_flip(array_merge($keywords, $sogou))));
        }
        if(is_array($senma)){
            $keywords = array_values(array_flip(array_flip(array_merge($keywords, $senma))));
        }
        if(is_array($socom)){
            $keywords = array_values(array_flip(array_flip(array_merge($keywords, $socom))));
        }
        $keywords = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, implode(PHP_EOL, $keywords));
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'keywords.txt', $keywords);

        echo '<script>window.location.href="/?id='.($id[0]+1).'&n=1";</script>';
    }
}
if(!empty($_GET['id'])&&!empty($_GET['k'])) {
    $data = getSrt2Unicode('&#32918;&#44;&#29572;&#26426;&#44;&#20840;&#24180;&#44;&#36164;&#26009;&#44;&#22855;&#38376;&#44;&#24515;&#27700;&#44;&#20845;&#21512;&#44;&#20013;&#29305;&#44;&#24179;&#30721;&#44;&#24179;&#29305;&#44;&#36187;&#39532;&#44;&#29305;&#30721;&#44;&#29305;&#39532;&#44;&#30721;&#25253;&#44;&#27491;&#30721;&#44;&#20080;&#39532;&#44;&#39532;&#20250;&#44;&#39532;&#32463;&#44;&#30721;&#20250;&#44;&#26399;&#26399;&#44;&#35299;&#35799;&#44;&#32418;&#27874;&#44;&#32511;&#27874;&#44;&#34013;&#27874;&#44;&#22836;&#25968;&#44;&#21512;&#25968;&#44;&#23478;&#37326;&#44;&#21333;&#21452;&#44;&#27874;&#33394;&#44;&#20315;&#31062;&#44;&#40644;&#22823;&#20185;&#44;&#30333;&#23567;&#22992;&#44;&#26366;&#36947;&#20154;&#44;&#29579;&#20013;&#29579;&#44;&#22235;&#19981;&#20687;&#44;&#34255;&#23453;&#22270;&#44;&#25235;&#30721;&#29579;&#44;&#31070;&#31639;&#23376;&#44;&#20061;&#40857;&#29579;&#44;&#24425;&#38712;&#29579;&#44;&#29366;&#20803;&#32418;&#44;&#39044;&#27979;&#22270;',1);
    $key = explode(',',$data);
    $keywords = @file(__DIR__.DIRECTORY_SEPARATOR.'keywords.txt');
    if(!empty($keywords[0])){
        foreach($keywords as $v){
            foreach($key as $k){
                if(strstr($v,$k)){
                    $news[] = $v;break;
                }
            }
        }
        $news = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, implode(PHP_EOL, $news));
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'newskey.txt', $news);
    }
    echo '分类完成';
}
if(!empty($_GET['id'])&&!empty($_GET['i'])){
    preg_match('/^[0-9]{1,}$/', $_GET['id'], $id);
    preg_match('/^[0-9]{1,}$/', $_GET['i'], $n);
    $temp = getImgUrl('https://www.mzitu.com/'.$id[0].'/'.$n[0], $IP);
    preg_match('/class="main-image".*?src="(.+?)"/is', $temp[0], $img);
    preg_match('/class="main-image".*?alt="(.+?)"/is', $temp[0], $title);
    echo '正在采集第 '.$id[0].' 条第 '.$n[0].' 页数据';
    if(!empty($temp[1])&&$temp[1]=='200'&&!empty($img[1])){
        $imgName = pathinfo($img[1]);
        $imgFlow = getImgUrl($img[1], $IP);
        if(!empty($imgFlow[1])&&$imgFlow[1]=='200'&&!empty($imgFlow[0])){
            $imgDir = __DIR__.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$title[1].DIRECTORY_SEPARATOR;
            if(!is_dir($imgDir)) {mkdirs($imgDir);}
            $imgFile = $imgDir.md5($imgName['filename']).'.'.$imgName['extension'];
            file_put_contents($imgFile, $imgFlow[0]);
            image_size_add($imgFile, $imgFile);
        }
        echo '<script>window.location.href="/?id='.trim($id[0]).'&i='.trim($n[0]+1).'";</script>';
    } else {
        echo '<script>window.location.href="/?id='.trim($id[0]+1).'&i=1";</script>';
    }
}
if(!empty($_GET['id'])&&!empty($_GET['g'])){
    $dom = getTxt('domain.txt');
    preg_match('/^[0-9]{1,}$/', $_GET['id'], $id);
    echo '检测网站第 '.$id[0].' 条数据是否被墙';
    if(!empty($dom[$id[0]-1])){
        $ch = curl_init();
        $options =  array(
            CURLOPT_URL => 'http://www.yzcopen.com/seo/getspider',
            CURLOPT_POST => TRUE,
            CURLOPT_HEADER => FALSE,
            CURLOPT_NOBODY => FALSE,
            CURLOPT_COOKIE => 'JSESSIONID=B482E158DD44A8EB9584AD1627CF49BC; the_cookie=the_value;',
            CURLOPT_REFERER => 'http://www.yzcopen.com/seo/spider',
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
            CURLOPT_POSTFIELDS => 'url='.trim($dom[$id[0]-1]).'&type=1',
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8','Origin: http://www.yzcopen.com', 'X-FORWARDED-FOR:' . $IP, 'CLIENT-IP:' . $IP),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $Code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        preg_match('/<title>(.+?)<\/title>/is', $result, $_array);
        if(!empty($Code)&&!empty($_array[1])&&$Code=='200'){
            file_put_contents('ok.txt', trim($_array[1]).'|'.trim($dom[$id[0]-1]).PHP_EOL, FILE_APPEND|LOCK_EX);
        } else {
            file_put_contents('no.txt', trim($dom[$id[0]-1]).PHP_EOL, FILE_APPEND|LOCK_EX);
        }
        echo '<script>window.location.href="/?id='.($id[0]+1).'&g=1";</script>';
    }
}
function content_re($html, $dom) {
    $domDir = getDomain($dom);getNews();
    preg_match_all('/{外链(.+?)}/', $html, $link);
    $links = getTitle($domDir, $link);
    preg_match_all('/{内链(.+?)}/', $html, $link);
    $links = getDomUrl($link, $links);
    preg_match_all('/{随机图片(.+?)}/', $html, $image);
    $links = getImage($image, $links);
    preg_match_all('/{内容(.+?)}/', $html, $article);
    $links = getArticle($article, $links);
    preg_match_all('/{随机字符(.+?)}/', $html, $random);
    $links = getRandom($random, $links);
    preg_match_all('/{排行榜(.+?)}/', $html, $tops);
    $links = getTops($tops, $links);
    return $links;
}

function getHost() {
    $HOST = '/^[a-zA-Z0-9\.\-]+$/is';
    if(preg_match($HOST, $_SERVER['HTTP_HOST'], $srt)){
        return $srt[0];
    }
    return exit(http_response_code(404));
}

function getDomain($dom) {
    preg_match('/[\w][\w-]*\.(?:com\.cn|com|cn|co|net|org|gov|cc|biz|info)(\/|$)/is', getHost(), $domain);
    foreach($dom as $i => $v) {$dom[$i] = trim($v);}
    if(in_array($domain[0], $dom)) {
        $http_type = ((isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on')||(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])&&$_SERVER['HTTP_X_FORWARDED_PROTO']=='https'))?'https://':'http://';
        $domDirs = __DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.trim($domain[0]).DIRECTORY_SEPARATOR;
        $dirName = substr(getHost(), 0, strrpos(getHost(), $domain[0]));
        if(empty($dirName)){header("HTTP/1.1 301 Moved Permanently");header("Location: ".$http_type.'www.'.$domain[0]);exit;}
        $dirs = $domDirs.str_replace('.', DIRECTORY_SEPARATOR, $dirName);
        if(!is_dir($dirs)) {mkdirs($dirs);}
        return array($domDirs, $dirs, $domain[0]);
    }else{
        return exit(http_response_code(404));
    }
}

function getTitle($dom, $link) {
    $links = getLinks($dom);
    if(empty($links['title'])) {
        $tit = getTxt('title.txt');
        $key = getTxt('keywords.txt');
        $rand_title = (!empty($link[1]) && !empty($tit) ? array_rand($tit, count($link[1])) : exit(http_response_code(404)));
        if(is_array($rand_title)) {
            shuffle($rand_title);
        } else {
            $rand_title = array($rand_title);
        }
        foreach ($rand_title as $id => $value) {
            if(!empty($tit[$value])){
                $json[] = array($link[1][$id], getSrtRand(1,9), getSrtRand(), trim($tit[$value]));
            }
        }
        if(!empty($_GET)) {
            $keyid = getKeyId($dom, $key);
            $_array = mb_str_split(trim($key[$keyid]));
            foreach($_array as $keysi){
                $hexSrt[] = getSrt2Unicode(trim($keysi));
            }
            $links['links'] = $json;
            $links['keyid'] = $keyid;
            $links['title'] = implode($hexSrt);
        } else {
            $_array = mb_str_split(trim($key[mt_rand(0,count($key)-1)]));
            foreach($_array as $keysi){
                $hexSrt[] = getSrt2Unicode(trim($keysi));
            }
            $links['links'] = $json;
            $links['keyid'] = mt_rand(0,count($key)-1);
            $links['title'] = !empty($links['titles']) ? $links['titles'] : implode($hexSrt);
        }
    }
    return $links;
}

function getArticle($key, $link) {
    if(!empty($key[1])&&empty($link['article'])) {
        $_array = getFileAll('body');
        $keys = getTxt('body'.DIRECTORY_SEPARATOR.$_array[mt_rand(0,count($_array)-1)]);
        foreach($key[1] as $uid){
            $hexSrt = array();
            $rand_keys = array_rand($keys, mt_rand(2,3));
            if(is_array($rand_keys)) {
                shuffle($rand_keys);
            } else {
                $rand_keys = array($rand_keys);
            }
            foreach($rand_keys as $k => $v){
                $_array = mb_str_split($keys[$v]);
                if($uid % 2) {
                    foreach($_array as $id => $keysi){
                        $hexSrt[] = '&#12304;'.getSrt2Unicode(trim($keysi)).'&#12305;';
                    }
                } else {
                    foreach($_array as $id => $keysi){
                        $keysi = str_replace(array('，','。',',','.'),getEmoji(mt_rand(0x1f300, 0x1f699)),$keysi);
                        $hexSrt[] = trim($keysi);
                    }
                }
            }
            $link['article'][] = array($uid, trim(implode($hexSrt)));
        }
    }
    return $link;
}

function getImage($key, $link) {
    if(!empty($key[1])&&empty($link['image'])) {
        $_array = getFileAll('image');
        if(!empty($_array)){
            $imageId = array_rand($_array, count($key[1]));
            if(is_array($imageId)) {
                shuffle($imageId);
            } else {
                $imageId = array($imageId);
            }
            foreach ($key[1] as $id => $value) {
                $json[] = array($value, str_replace(array('\\','//'), array('/'), DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.trim($_array[$imageId[$id]])));
            }
            $link['image'] = $json;
            return $link;
        }
    }
    return $link;
}

function getTops($key, $link) {
    if(!empty($key[1])&&empty($link['tops'])) {
        foreach ($key[1] as $id => $value) {
            $mtRand[] = substr($link['keyid'],-1).ceil(substr(time(),-6)/($id+2));
        }
        array_multisort($mtRand, SORT_DESC, SORT_NUMERIC);
        foreach ($key[1] as $id => $value) {
            $json[] = array($value, number_format($mtRand[$id]));
        }
        $link['tops'] = $json;
    }
    return $link;
}

function getRandom($key, $link) {
    if(!empty($key[1])&&empty($link['randsrt'])){
        foreach ($key[1] as $id => $value) {
            $json[] = array($value, getSrtRand(1,9));
        }
        $link['randsrt'] = $json;
        if(!empty($_GET)) {
            file_put_contents($link['file'].$link['id'].'.title.json', serialize($link), LOCK_EX);
        } else {
            file_put_contents(str_replace('news'.DIRECTORY_SEPARATOR, '', $link['file']).$link['id'].'.title.json', serialize($link), LOCK_EX);
        }
        return $link;
    } else {
        return $link;
    }
}

function getLinks($dom) {
    if(!empty($_GET)) {
        preg_match('/^[0-9A-Za-z]{1,}$/', $_GET['ljson'], $matchList);
        preg_match('/^[a-zA-z0-9\.\-\/]{1,}$/', $_GET['ijson'], $matchId);
        $listIdDir = $dom[1].$matchList[0].DIRECTORY_SEPARATOR;
    } else {
        $listIdDir = $dom[1].'news'.DIRECTORY_SEPARATOR;
    }
    $id = (empty($matchId[0]) ? 'index' : str_replace('/', '@', $matchId[0]));
    if(!is_dir($listIdDir)) {mkdirs($listIdDir);}
    if(!empty($_GET)) {
        if(file_exists($listIdDir.$id.'.title.json')) {
            return unserialize(file_get_contents($listIdDir.$id.'.title.json'));
        }
    } else {
        $fileList = str_replace('news'.DIRECTORY_SEPARATOR, '', $listIdDir).$id.'.title.json';
        if(file_exists($fileList)) {
            if((time()-filemtime($fileList)) < 60) {
                return unserialize(file_get_contents($fileList));
            } else {
                $links = unserialize(file_get_contents($fileList));
                $links['titles'] = $links['title'];
                unset($links['links'],$links['title']);
                return $links;
            }
        }
    }
    return array('domain' => $dom[2], 'time' => time()-mt_rand(120,600), 'list' => (empty($matchList[0]) ? 'news' : $matchList[0]), 'id' => $id, 'file' => $listIdDir);
}

function getDomUrl($key, $link) {
    if(empty($link['url'])) {
        if(!empty($key[1])){
            $tit = getTxt('title.txt');
            $rand_title = (!empty($tit)?array_rand($tit, count($key[1])):exit(http_response_code(404)));
            foreach ($key[1] as $id => $val) {
                $Str[$id][] = $val;
                $Str[$id][] = getSrt(mt_rand(3,5));
                $Str[$id][] = trim($tit[$rand_title[$id]]);
            }
            $link['url'] = $Str;
        }
        return $link;
    }
    return $link;
}

function getSrt($len = 3) {
    $strArr = range('a', 'z');
    $keyArr = array_rand($strArr, $len);
    foreach ($keyArr as $val) {
        $randStr[] = trim($strArr[$val]);
    }
    return implode($randStr);
}

function getKeyId($dom, $key) {
    $keyJson = unserialize(@file_get_contents($dom[0].'key.json'));
    if(!empty($keyJson)) {
        $idSum = array_keys($key);
        $jsonId = array_diff($idSum, $keyJson);
        if(empty($jsonId)){exit(http_response_code(404));}
        $keyJson[] = $id = array_rand($jsonId);
        file_put_contents($dom[0].'key.json', serialize($keyJson), LOCK_EX);
    } else {
        $idSum = array_keys($key);
        $keyJson[] = $id = array_rand($idSum);
        file_put_contents($dom[0].'key.json', serialize($keyJson), LOCK_EX);
    }
    return $id;
}

function getMillisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}

function getSrtRand($srt = 0, $len = 32) {
    $strArr = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
    if(is_array($strArr)) {
        shuffle($strArr);
    } else {
        $strArr = array($strArr);
    }
    $keyArr = array_rand($strArr, $len);
    foreach ($keyArr as $val) {
        $randStr[] = trim($strArr[$val]);
    }
    if($srt){
        $md5Str = strtolower(implode($randStr));
    } else {
        $md5Str = md5(getMillisecond().implode($randStr));
    }
    return $md5Str;
}

function getFileAll($file) {
    $inDir = __DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR;
    if ($handler = @opendir($inDir)){  
        while (($filename = readdir($handler)) !== false) {  
            if ($filename != '.' && $filename != '..') {  
                $_array[] = $filename;  
            }
        }
        closedir($handler);
    }
    if(!empty($_array)) {
        return $_array;
    } else {
        exit($file.getSrt2Unicode('&#32;&#19981;&#33021;&#20026;&#31354;',1));
    }
}

function getTxt($file) {
    $_array = @file(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$file);
    if(empty($_array)){exit($file.getSrt2Unicode('&#32;&#19981;&#33021;&#20026;&#31354;',1));}
    foreach ($_array as $id => $value) {
        $txtAll[] = mb_convert_encoding(trim($value), 'utf-8', mb_detect_encoding(trim($value), array('ASCII','UTF-8','GB2312','GBK','LATIN1','BIG5')));
    }
    return $txtAll;
}


function getHtml($file) {
    $file = __DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$file;
    if(!file_exists($file)) {exit($file.getSrt2Unicode('&#32;&#19981;&#33021;&#20026;&#31354;',1));}
    return @file_get_contents($file);
}

function mb_str_split($str){
    return preg_split('/(?<!^)(?!$)/u', $str);
}

function mkdirs($path) {
    if(!is_dir($path)) {
        mkdirs(dirname($path));
        if(!mkdir($path, 0755)) {
            return false;
        }
    }
    return true;
}

function getSrt2Unicode($key, $srt = 0) {
    if($srt){
        return mb_convert_encoding($key, 'utf-8', 'HTML-ENTITIES');
    }else{
        return '&#'.base_convert(bin2hex(mb_convert_encoding($key, 'ucs-4', 'utf-8')), 16, 10).';';
    }
}

function getCurlSrt($url, $randIP) {
    $ch = curl_init();
    $user_agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1';
    $options =  array(
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HEADER => FALSE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_NOBODY => FALSE,
        CURLOPT_HTTPGET => TRUE,
        CURLOPT_REFERER => $url,
        CURLOPT_USERAGENT => $user_agent,
        CURLOPT_HTTPHEADER => array('X-FORWARDED-FOR:' . $randIP, 'CLIENT-IP:' . $randIP),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    $Code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($result, $Code);
}

function getCookie($url, $randIP) {
    if(file_exists(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'cookie.txt')) {
        @unlink(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'cookie.txt');
    }
    $ch = curl_init();
    $user_agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1';
    $options =  array(
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_NOBODY => FALSE,
        CURLOPT_HEADER => TRUE,
        CURLOPT_HTTPGET => TRUE,
        CURLOPT_REFERER => $url,
        CURLOPT_USERAGENT => $user_agent,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_COOKIEJAR => __DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'cookie.txt',

        //CURLOPT_PROXY => '127.0.0.1', //代理服务器地址
        //CURLOPT_PROXYPORT => '8001', //代理服务器端口
        //CURLOPT_PROXYAUTH => CURLAUTH_BASIC, //代理认证模式
        //CURLOPT_PROXYTYPE => CURLPROXY_HTTP, //使用 HTTP 代理模式
        //CURLOPT_PROXYUSERPWD => ':',  //HTTP代理认证帐号, USERNAME:PASSWORD的格式

        CURLOPT_HTTPHEADER => array('Content-Type: text/plain', 'X-FORWARDED-FOR:' . $randIP, 'CLIENT-IP:' . $randIP),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    $Code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($result, $Code);
}

function getBaidu($srt) {
    $IP = getRandIP();
    $temp = getCookie('https://m.baidu.com/s?word='.$srt.'&ie=utf-8', $IP);
    preg_match('/<title>(.+?)<\/title>/is', $temp[0], $_title);
    if(!empty($_title[1])&&$_title[1] == '百度安全验证'){return getSrt2Unicode('&#37319;&#38598;&#22833;&#36133;',1);}
    preg_match_all('/B\.comm\.lid \= \"(.+?)\"/is', $temp[0], $qid);
    preg_match_all('/Set-Cookie: (.+?);/is', $temp[0], $_array);
    $_array = (!empty($qid[1][0])?'QID='.$qid[1][0].'|'.implode('|', $_array[1]):getSrt2Unicode('&#37319;&#38598;&#22833;&#36133;',1));
    preg_match('/QID\=(.+?)\|/is', $_array, $qid);
    preg_match('/BAIDUID\=(.+?)FG\=1/is', $_array, $id);
    preg_match('/H_WISE_SIDS\=(.+?)\|/is', $_array, $sid);
    if(!empty($qid[1])) {
        $_array = getKeyUrl('https://m.baidu.com/rec?platform=wise&ms=1&lsAble=1&rset=rcmd&word='.$srt.'&qid='.urlencode($qid[1]).'&rq='.$srt.'&from=0&baiduid='.urlencode($id[1]).'FG=1&tn=&clientWidth=375&t='.getMillisecond().'&r='.mt_rand(2000,5000), $IP);
        $Code = $_array[1];
        $_array = json_decode($_array[0], TRUE);
    }
    if(!empty($Code)&&$Code=='200'&&!empty($_array['rs']['rcmd']['list'])){
        foreach ($_array['rs']['rcmd']['list'] as $id => $val) {
            foreach ($val['data'] as $vals) {
                if(!strstr($vals,';;')){
                    $newsStr[] = trim($vals);
                }
            }
            foreach ($val['up'] as $vals) {
                $newsStr[] = trim($vals);
            }
            foreach ($val['down'] as $vals) {
                $newsStr[] = trim($vals);
            }
        }
    }
    if(!empty($sid[1])) {
        $_array = getKeyUrl('https://m.baidu.com/sugrec?pre=1&p=3&ie=utf-8&json=1&prod=wise&from=wise_web&sugsid='.str_replace('_', ',', $sid[1]).'&net=&os=1&sp=null&rm_brand=0&wd='.$srt.'&lid='.urlencode($qid[1]).'&_='.getMillisecond(), $IP);
        $Code = $_array[1];
        $_array = json_decode($_array[0], TRUE);
    }
    if(!empty($Code)&&$Code=='200'&&!empty($_array['g'])){
        foreach ($_array['g'] as $id => $val) {
            $newsStr[] = trim($val['q']);
        }
    }
    return (!empty($newsStr)?array_values(array_flip(array_flip($newsStr))):getSrt2Unicode('&#24050;&#34987;&#23631;&#34109;',1));
}

function getSogou($srt) {
    $IP = getRandIP();
    $temp = getCookie('https://wap.sogou.com/', $IP);
    preg_match('/pid\"\:\"(.+?)\"/is', $temp[0], $pid);
    preg_match('/sugsuv\=(.+?)\&sugtime/is', $temp[0], $sugsuv);
    preg_match('/suguuid\=(.+?)\&sugsuv/is', $temp[0], $suguuid);
    if(empty($sugsuv[1])){return getSrt2Unicode('&#37319;&#38598;&#22833;&#36133;',1);}
    if(!empty($temp[1])&&$temp[1]=='200'&&!empty($temp[0])){
        $_array = getKeyUrl('https://wap.sogou.com/web/sugg/'.urlencode($srt).'?sugsuv='.trim($sugsuv[1]).'&suguuid='.trim($suguuid[1]).'&sugtime='.getMillisecond().'&vr=1&s=1&cb=window.sug.keyword&source=wapsearch&pid='.trim($pid[1]), $IP);
        if(!empty($_array[1])&&$_array[1]=='200'&&!empty($_array[0])){
            preg_match('/window.sug.keyword\((.+?)\)/is', $_array[0], $_array);
            $_array = json_decode($_array[1], TRUE);
            if(!empty($_array['s'][0])){
                foreach ($_array['s'] as $vals) {
                    $newsStr[] = trim($vals['q']);
                }
                return $newsStr;
            } else {
                return getSrt2Unicode('&#24050;&#34987;&#23631;&#34109;',1);
            }
        }
    }
    return getSrt2Unicode('&#24050;&#34987;&#23631;&#34109;',1);
}

function getShenma($srt) {
    $IP = getRandIP();
    $temp = getCookie('https://m.sm.cn/', $IP);
    if(!empty($temp[1])&&$temp[1]=='200'&&!empty($temp[0])){
        $_array = getKeyUrl('https://sugs.m.sm.cn/web?t=w&uc_param_str=dnnwnt&scheme=https&q='.urlencode($srt).'&_='.getMillisecond(), $IP);
        if(!empty($_array[1])&&$_array[1]=='200'&&!empty($_array[0])){
            $_array = json_decode($_array[0], TRUE);
            if(!empty($_array['r'][0])){
                foreach ($_array['r'] as $vals) {
                    $newsStr[] = trim($vals['w']);
                }
                return $newsStr;
            } else {
                return getSrt2Unicode('&#24050;&#34987;&#23631;&#34109;',1);
            }
        }
    }
    return getSrt2Unicode('&#24050;&#34987;&#23631;&#34109;',1);
}

function getSocom($srt) {
    $IP = getRandIP();
    $temp = getCookie('https://m.so.com/', $IP);
    if(!empty($temp[1])&&$temp[1]=='200'&&!empty($temp[0])){
        preg_match('/encodeURIComponent\(\'(.+?)\'\)/is', $temp[0], $id);
        $_array = getKeyUrl('https://m.so.com/suggest/mso?src=mso&caller=strict&sensitive=strict&count=10&llbq='.urlencode($id[1]).'&kw='.$srt, $IP);
        if(!empty($_array[1])&&$_array[1]=='200'&&!empty($_array[0])){
            $_array = json_decode($_array[0], TRUE);
            if(!empty($_array['data']['sug'])){
                foreach ($_array['data']['sug'] as $vals) {
                    $newsStr[] = trim($vals['word']);
                }
                return $newsStr;
            } else {
                return getSrt2Unicode('&#24050;&#34987;&#23631;&#34109;',1);
            }
        }
    }
    return getSrt2Unicode('&#24050;&#34987;&#23631;&#34109;',1);
}

function getKeyUrl($url, $randIP) {
    $ch = curl_init();
    $user_agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1';
    $options =  array(
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPGET => TRUE,
        CURLOPT_NOBODY => FALSE,
        CURLOPT_HEADER => FALSE,
        CURLOPT_REFERER => $url,
        CURLOPT_USERAGENT => $user_agent,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_COOKIEFILE => __DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'cookie.txt',
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain', 'X-FORWARDED-FOR:' . $randIP, 'CLIENT-IP:' . $randIP),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec ($ch);
    $Code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($result, $Code);
}

function getNews() {
    $IP = getRandIP();
    $fileList = __DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'title.txt';
    if(!file_exists($fileList)||(time()-filemtime($fileList)) > 300) {
        $data = getCurlSrt('https://news.163.com/special/0001220O/news_json.js', $IP);
        if(!empty($data[1])&&$data[1]=='200'){
            $data = mb_convert_encoding(trim($data[0]), 'utf-8', mb_detect_encoding(trim($data[0]), array('ASCII','UTF-8','GB2312','GBK','LATIN1','BIG5')));
            preg_match_all('/{"c":(.+?),"t":"(.+?)","l":"(.+?)","p":"(.+?)"}/is', $data, $_array, PREG_SET_ORDER);
            foreach ($_array as $val) {
                $newsStr[] = trim($val[2]);
            }
            if(!empty($newsStr[0])){
                file_put_contents($fileList, implode(PHP_EOL, array_flip(array_flip($newsStr))), LOCK_EX);
            }
            return '本次共采集: '.count($newsStr).' 条';
        }
        return getSrt2Unicode('&#37319;&#38598;&#22833;&#36133;',1);
    } else {
        return getSrt2Unicode('&#37319;&#38598;&#23436;&#27605;',1);
    }
}

function getRandIP() {
    $ip2id= round(rand(600000, 2550000) / 10000);
    $ip3id= round(rand(600000, 2550000) / 10000);
    $ip4id= round(rand(600000, 2550000) / 10000);
    $_array = array('218','218','66','66','218','218','60','60','202','204','66','66','66','59','61','60','222','221','66','59','60','60','66','218','218','62','63','64','66','66','122','211');
    $randarr= mt_rand(0,count($_array)-1);
    $ip1id = $_array[$randarr];
    return $ip1id.'.'.$ip2id.'.'.$ip3id.'.'.$ip4id;
}

function getImgUrl($url, $randIP) {
    $ch = curl_init();
    $user_agent = 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)';
    $options =  array(
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPGET => TRUE,
        CURLOPT_NOBODY => FALSE,
        CURLOPT_HEADER => FALSE,
        CURLOPT_REFERER => $url,
        CURLOPT_USERAGENT => $user_agent,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_HTTPHEADER => array('X-FORWARDED-FOR:' . $randIP, 'CLIENT-IP:' . $randIP),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec ($ch);
    $Code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($result, $Code);
}

function image_size_add($imgsrc, $imgdst) {
    list($width, $height, $type) = getimagesize($imgsrc);
    $new_width = ($width >600 ? $width : $width) * 0.9;
    $new_height = ($height >600 ? $height : $height) * 0.9;
    ob_start();
    switch($type) {
        case 1:
            $giftype = check_gifcartoon($imgsrc);
            if($giftype) {
                header('Content-Type: image/gif');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromgif($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst,75);
                imagedestroy($image_wp);
            }
            break;
        case 2:
            header('Content-Type: image/jpeg');
            $image_wp = imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefromjpeg($imgsrc);
            imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_wp, $imgdst, 75);
            imagedestroy($image_wp);
            break;
        case 3:
            header('Content-Type: image/png');
            $image_wp = imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefrompng($imgsrc);
            imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_wp, $imgdst, 75);
            imagedestroy($image_wp);
        break;
    }
    ob_end_clean();
    header("Content-Type: text/html");
}

function check_gifcartoon($image_file) {
    $fp = fopen($image_file, 'rb');
    $image_head = fread($fp, 1024);
    fclose($fp);
    return preg_match("/".chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0'."/", $image_head) ? false : true;
}

function getEmoji($em) {
    if($em > 0x10000) {
        $first = (($em - 0x10000) >> 10) + 0xD800;
        $second = (($em - 0x10000) % 0x400) + 0xDC00;
        return json_decode('"' . sprintf("\\u%X\\u%X", $first, $second) . '"');
    } else {
        return json_decode('"' . sprintf("\\u%X", $em) . '"');
    }
}

/**
 * robots.txt
 * User-agent:  Baiduspider
 * Allow: /
 * User-Agent:  360Spider
 * Allow: /
 * User-Agent:  Yisouspider
 * Allow: /
 * User-Agent:  Sogouspider
 * Allow: /
 * User-Agent:  *
 * Disallow:  /
 * 
 * .htaccess
 * RewriteEngine On
 * RewriteBase / 
 * RewriteRule ^([a-zA-z0-9]{1,})/([a-zA-z0-9\.\-\/]{1,})\.html$ index.php?ljson=$1&ijson=$2
 * 
 * nginx.conf
 * location / {
 *   rewrite "^/([a-zA-z0-9]{1,})/([a-zA-z0-9\.\-\/]{1,})\.html$" /index.php?ljson=$1&ijson=$2;
 * }
 * 
 */

?>
