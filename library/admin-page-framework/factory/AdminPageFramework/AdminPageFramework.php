<?php
/**
 Admin Page Framework v3.5.8 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework extends AdminPageFramework_Controller {
    public function __construct($sOptionKey = null, $sCallerPath = null, $sCapability = 'manage_options', $sTextDomain = 'admin-page-framework') {
        if (!$this->_isInstantiatable()) {
            return;
        }
        parent::__construct($sOptionKey, $sCallerPath ? trim($sCallerPath) : $sCallerPath = (is_admin() && (isset($GLOBALS['pagenow']) && in_array($GLOBALS['pagenow'], array('plugins.php',)) || isset($_GET['page'])) ? AdminPageFramework_Utility::getCallerScriptPath(__FILE__) : null), $sCapability, $sTextDomain);
        $this->oUtil->addAndDoAction($this, 'start_' . $this->oProp->sClassName, $this);
    }
}