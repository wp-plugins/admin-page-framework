<?php
/**
 Admin Page Framework v3.5.7 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework_Utility_URL extends AdminPageFramework_Utility_Path {
    static public function getQueryValueInURLByKey($sURL, $sQueryKey) {
        $_aURL = parse_url($sURL) + array('query' => '');
        parse_str($_aURL['query'], $aQuery);
        return self::getElement($aQuery, $sQueryKey, null);
    }
    static public function getCurrentURL() {
        $_bSSL = self::isSSL();
        $_sServerProtocol = strtolower($_SERVER['SERVER_PROTOCOL']);
        $_aProrocolSuffix = array(0 => '', 1 => 's',);
        $_sProtocol = substr($_sServerProtocol, 0, strpos($_sServerProtocol, '/')) . $_aProrocolSuffix[( int )$_bSSL];
        $_sPort = self::_getURLPortSuffix($_bSSL);
        $_sHost = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
        return $_sProtocol . '://' . $_sHost . $_sPort . $_SERVER['REQUEST_URI'];
    }
    static private function _getURLPortSuffix($_bSSL) {
        $_sPort = isset($_SERVER['SERVER_PORT']) ? ( string )$_SERVER['SERVER_PORT'] : '';
        $_aPort = array(0 => ':' . $_sPort, 1 => '',);
        $_bPortSet = (!$_bSSL && '80' === $_sPort) || ($_bSSL && '443' === $_sPort);
        return $_aPort[( int )$_bPortSet];
    }
    static public function isSSL() {
        return array_key_exists('HTTPS', $_SERVER) && 'on' === $_SERVER['HTTPS'];
    }
}