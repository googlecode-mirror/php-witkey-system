<?php
/**
 * OAuth��Ȩ��
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20
 */
class OAuth
{
    public static $client_id = '';
    public static $client_secret = '';
    
    private static $accessTokenURL = 'https://open.t.qq.com/cgi-bin/oauth2/access_token';
    private static $authorizeURL = 'https://open.t.qq.com/cgi-bin/oauth2/authorize';

    /**
     * ��ʼ��
     * @param $client_id �� appid
     * @param $client_secret �� appkey
     * @return
     */
    public static function init($client_id, $client_secret)
    {
        if (!$client_id || !$client_secret) exit('client_id or client_secret is null');
        self::$client_id = $client_id;
        self::$client_secret = $client_secret;
    }

    /**
     * ��ȡ��ȨURL
     * @param $redirect_uri ��Ȩ�ɹ���Ļص���ַ����������Ӧ�õ�url
     * @param $response_type ��Ȩ���ͣ�Ϊcode
     * @param $wap ����ָ���ֻ���Ȩҳ�İ汾��Ĭ��PC��ֵΪ1ʱ����wap1.0����Ȩҳ��Ϊ2ʱͬ��
     * @return string
     */
    public static function getAuthorizeURL($redirect_uri, $response_type = 'code', $wap = false)
    {
        $params = array(
            'client_id' => self::$client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => $response_type,
            'type' => $type
        );
        return self::$authorizeURL.'?'.http_build_query($params);
    }

    /**
     * ��ȡ����token��url
     * @param $code ����authorizeʱ���ص�code
     * @param $redirect_uri �ص���ַ�����������codeʱ��redirect_uriһ��
     * @return string
     */
    public static function getAccessToken($code, $redirect_uri)
    {
        $params = array(
            'client_id' => self::$client_id,
            'client_secret' => self::$client_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri
        );
        return self::$accessTokenURL.'?'.http_build_query($params);
    }
    
    /**
     * ˢ����Ȩ��Ϣ
     * �˴���SESSION��ʽ�洢����ʾ��ʵ��ʹ�ó���������Ӧ���޸�
     */
    public static function refreshToken()
    {
        $params = array(
            'client_id' => self::$client_id,
            'client_secret' => self::$client_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $_SESSION['ten_token']['refresh_token']
        );
        $url = self::$accessTokenURL.'?'.http_build_query($params);
        $r = Http::request($url);
        parse_str($r, $out);
        if ($out['access_token']) {//��ȡ�ɹ�
            $_SESSION['ten_token']['access_token'] = $out['access_token'];
            $_SESSION['ten_token']['refresh_token'] = $out['refresh_token'];
            $_SESSION['ten_token']['expire_in'] = $out['expire_in'];
            return $out;
        } else {
            return $r;
        }
    }
    
    /**
     * ��֤��Ȩ�Ƿ���Ч
     */
    public static function checkOAuthValid()
    {
        $r = json_decode(Tencent::api('user/info'), true);
        if ($r['data']['name']) {
            return true;
        } else {
            self::clearOAuthInfo();
            return false;
        }
    }
    
    /**
     * �����Ȩ
     */
    public static function clearOAuthInfo()
    {
        if (isset($_SESSION['ten_token']['access_token'])) unset($_SESSION['ten_token']['access_token']);
        if (isset($_SESSION['ten_token']['expire_in'])) unset($_SESSION['ten_token']['expire_in']);
        if (isset($_SESSION['ten_token']['code'])) unset($_SESSION['ten_token']['code']);
        if (isset($_SESSION['ten_token']['openid'])) unset($_SESSION['ten_token']['openid']);
        if (isset($_SESSION['ten_token']['openkey'])) unset($_SESSION['ten_token']['openkey']);
        if (isset($_SESSION['ten_token']['oauth_version'])) unset($_SESSION['ten_token']['oauth_version']);
    }
}

/**
 * ��Ѷ΢��API������
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20
 */
class Tencent
{
    //�ӿ�url
    public static $apiUrlHttp = 'http://open.t.qq.com/api/';
    public static $apiUrlHttps = 'https://open.t.qq.com/api/';
    
    //����ģʽ
    public static $debug = false;
    
    /**
     * ����һ����ѶAPI����
     * @param $command �ӿ����� �磺t/add
     * @param $params �ӿڲ���  array('content'=>'test');
     * @param $method ����ʽ POST|GET
     * @param $multi ͼƬ��Ϣ
     * @return string
     */
    public static function api($command, $params = array(), $method = 'GET', $multi = false)
    {
        if (isset($_SESSION['ten_token']['access_token'])) {//OAuth 2.0 ��ʽ
            //��Ȩ����
            $params['access_token'] = $_SESSION['ten_token']['access_token'];
            $params['oauth_consumer_key'] = OAuth::$client_id;
            $params['openid'] = $_SESSION['ten_token']['openid'];
            $params['oauth_version'] = '2.a';
            $params['clientip'] = Common::getClientIp();
            $params['scope'] = 'all';
            $params['appfrom'] = 'php-sdk2.0beta';
            $params['seqid'] = time();
            $params['serverip'] = $_SERVER['SERVER_ADDR'];
            
            $url = self::$apiUrlHttps.trim($command, '/');
        } elseif (isset($_SESSION['ten_token']['openid']) && isset($_SESSION['ten_token']['openkey'])) {//openid & openkey��ʽ
            $params['appid'] = OAuth::$client_id;
            $params['openid'] = $_SESSION['ten_token']['openid'];
            $params['openkey'] = $_SESSION['ten_token']['openkey'];
            $params['clientip'] = Common::getClientIp();
            $params['reqtime'] = time();
            $params['wbversion'] = '1';
            $params['pf'] = 'php-sdk2.0beta';
            
            $url = self::$apiUrlHttp.trim($command, '/');
            //����ǩ��
            $urls = @parse_url($url);
            $sig = SnsSign::makeSig($method, $urls['path'], $params, OAuth::$client_secret.'&');
            $params['sig'] = $sig;
        }
        
        //����ӿ�
        $r = Http::request($url, $params, $method, $multi);
        $r = preg_replace('/[^\x20-\xff]*/', "", $r); //������ɼ��ַ�
        $r = iconv("utf-8", "utf-8//ignore", $r); //UTF-8ת��
        //������Ϣ
        if (self::$debug) {
            echo '<pre>';
            echo '�ӿڣ�'.$url;
            echo '<br>���������<br>';
            print_r($params);
            echo '���ؽ����'.$r;
            echo '</pre>';
        }
        return $r;
    }
}

/**
 * HTTP������
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20
 */
class Http
{
    /**
     * ����һ��HTTP/HTTPS������
     * @param $url �ӿڵ�URL 
     * @param $params �ӿڲ���   array('content'=>'test', 'format'=>'json');
     * @param $method ��������    GET|POST
     * @param $multi ͼƬ��Ϣ
     * @param $extheaders ��չ�İ�ͷ��Ϣ
     * @return string
     */
    public static function request( $url , $params = array(), $method = 'GET' , $multi = false, $extheaders = array())
    {
        if(!function_exists('curl_init')) exit('Need to open the curl extension');
        $method = strtoupper($method);
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_USERAGENT, 'PHP-SDK OAuth2.0');
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ci, CURLOPT_TIMEOUT, 3);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ci, CURLOPT_HEADER, false);
        $headers = (array)$extheaders;
        switch ($method)
        {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($params))
                {
                    if($multi)
                    {
                        foreach($multi as $key => $file)
                        {
                            $params[$key] = '@' . $file;
                        }
                        curl_setopt($ci, CURLOPT_POSTFIELDS, $params);
                        $headers[] = 'Expect: ';
                    }
                    else
                    {
                        curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($params));
                    }
                }
                break;
            case 'DELETE':
            case 'GET':
                $method == 'DELETE' && curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($params))
                {
                    $url = $url . (strpos($url, '?') ? '&' : '?')
                        . (is_array($params) ? http_build_query($params) : $params);
                }
                break;
        }
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
        curl_setopt($ci, CURLOPT_URL, $url);
        if($headers)
        {
            curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        }

        $response = curl_exec($ci);
        curl_close ($ci);
        return $response;
    }
}

/**
 * ����������
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20 *
 */
class Common
{
    //��ȡ�ͻ���IP
    public static function getClientIp()
    {
        if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
            $ip = getenv ( "HTTP_CLIENT_IP" );
        else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
            $ip = getenv ( "HTTP_X_FORWARDED_FOR" );
        else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
            $ip = getenv ( "REMOTE_ADDR" );
        else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
            $ip = $_SERVER ['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return ($ip);
    }
}

/**
 * Openid & Openkeyǩ����
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20
 */
class SnsSign
{
    /**
     * ����ǩ��
     * @param string    $method ���󷽷� "get" or "post"
     * @param string    $url_path 
     * @param array     $params ������
     * @param string    $secret ��Կ
     */
    public static function makeSig($method, $url_path, $params, $secret) 
    {
        $mk = self::makeSource ( $method, $url_path, $params );
        $my_sign = hash_hmac ( "sha1", $mk, strtr ( $secret, '-_', '+/' ), true );
        $my_sign = base64_encode ( $my_sign );
        return $my_sign;
    }
    
    private static function makeSource($method, $url_path, $params) 
    {
        ksort ( $params );
        $strs = strtoupper($method) . '&' . rawurlencode ( $url_path ) . '&';
        $str = ""; 
        foreach ( $params as $key => $val ) { 
            $str .= "$key=$val&";
        }   
        $strc = substr ( $str, 0, strlen ( $str ) - 1 );
        return $strs . rawurlencode ( $strc );
    }
}