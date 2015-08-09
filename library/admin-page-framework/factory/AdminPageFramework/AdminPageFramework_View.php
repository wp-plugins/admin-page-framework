<?php
/**
 Admin Page Framework v3.5.12 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework_View extends AdminPageFramework_Model {
    public function _replyToPrintAdminNotices() {
        if (!$this->_isInThePage()) {
            return;
        }
        foreach ($this->oProp->aAdminNotices as $_aAdminNotice) {
            $_sClassSelectors = $this->oUtil->generateClassAttribute($this->oUtil->getElement($_aAdminNotice, array('sClassSelector'), ''), 'notice is-dismissible');
            echo "<div class='{$_sClassSelectors}' id='{$_aAdminNotice['sID']}'>" . "<p>" . $_aAdminNotice['sMessage'] . "</p>" . "</div>";
        }
    }
    public function content($sContent) {
        return $sContent;
    }
}