<?php
/**
 Admin Page Framework v3.5.10 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class AdminPageFramework_WPUtility_File extends AdminPageFramework_WPUtility_Hook {
    static public function getScriptData($sPath, $sType = 'plugin') {
        $aData = get_file_data($sPath, array('sName' => 'Name', 'sURI' => 'URI', 'sScriptName' => 'Script Name', 'sLibraryName' => 'Library Name', 'sLibraryURI' => 'Library URI', 'sPluginName' => 'Plugin Name', 'sPluginURI' => 'Plugin URI', 'sThemeName' => 'Theme Name', 'sThemeURI' => 'Theme URI', 'sVersion' => 'Version', 'sDescription' => 'Description', 'sAuthor' => 'Author', 'sAuthorURI' => 'Author URI', 'sTextDomain' => 'Text Domain', 'sDomainPath' => 'Domain Path', 'sNetwork' => 'Network', '_sitewide' => 'Site Wide Only',), $sType);
        switch (trim($sType)) {
            case 'theme':
                $aData['sName'] = $aData['sThemeName'];
                $aData['sURI'] = $aData['sThemeURI'];
            break;
            case 'library':
                $aData['sName'] = $aData['sLibraryName'];
                $aData['sURI'] = $aData['sLibraryURI'];
            break;
            case 'script':
                $aData['sName'] = $aData['sScriptName'];
            break;
            case 'plugin':
                $aData['sName'] = $aData['sPluginName'];
                $aData['sURI'] = $aData['sPluginURI'];
            break;
            default:
            break;
        }
        return $aData;
    }
    static public function download($sURL, $iTimeOut = 300) {
        if (false === filter_var($sURL, FILTER_VALIDATE_URL)) {
            return false;
        }
        $_sTmpFileName = self::setTempPath(self::getBaseNameOfURL($sURL));
        if (!$_sTmpFileName) {
            return false;
        }
        $_aoResponse = wp_safe_remote_get($sURL, array('timeout' => $iTimeOut, 'stream' => true, 'filename' => $_sTmpFileName));
        if (is_wp_error($_aoResponse)) {
            unlink($_sTmpFileName);
            return false;
        }
        if (200 != wp_remote_retrieve_response_code($_aoResponse)) {
            unlink($_sTmpFileName);
            return false;
        }
        $_sContent_md5 = wp_remote_retrieve_header($_aoResponse, 'content-md5');
        if ($_sContent_md5) {
            $_boIsMD5 = verify_file_md5($_sTmpFileName, $_sContent_md5);
            if (is_wp_error($_boIsMD5)) {
                unlink($_sTmpFileName);
                return false;
            }
        }
        return $_sTmpFileName;
    }
    static public function setTempPath($sFilePath = '') {
        $_sDir = get_temp_dir();
        $sFilePath = basename($sFilePath);
        if (empty($sFilePath)) {
            $sFilePath = time() . '.tmp';
        }
        $sFilePath = $_sDir . wp_unique_filename($_sDir, $sFilePath);
        touch($sFilePath);
        return $sFilePath;
    }
    static public function getBaseNameOfURL($sURL) {
        $_sPath = parse_url($sURL, PHP_URL_PATH);
        $_sFileBaseName = basename($_sPath);
        return $_sFileBaseName;
    }
}