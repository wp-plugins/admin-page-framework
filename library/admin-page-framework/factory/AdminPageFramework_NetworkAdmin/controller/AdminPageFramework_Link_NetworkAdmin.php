<?php
/**
 Admin Page Framework v3.5.4 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class AdminPageFramework_Link_NetworkAdmin extends AdminPageFramework_Link_Page {
    public $oProp;
    public function __construct(&$oProp, $oMsg = null) {
        if (!$oProp->bIsAdmin) {
            return;
        }
        $this->oProp = $oProp;
        $this->oMsg = $oMsg;
        if ($oProp->bIsAdminAjax) {
            return;
        }
        $this->oProp->sLabelPluginSettingsLink = null === $this->oProp->sLabelPluginSettingsLink ? $this->oMsg->get('settings') : $this->oProp->sLabelPluginSettingsLink;
        add_action('in_admin_footer', array($this, '_replyToSetFooterInfo'));
        if (in_array($this->oProp->sPageNow, array('plugins.php')) && 'plugin' == $this->oProp->aScriptInfo['sType']) {
            add_filter('network_admin_plugin_action_links_' . plugin_basename($this->oProp->aScriptInfo['sPath']), array($this, '_replyToAddSettingsLinkInPluginListingPage'));
        }
    }
    public function _addLinkToPluginTitle($asLinks) {
        static $_sPluginBaseName;
        if (!is_array($asLinks)) {
            $this->oProp->aPluginTitleLinks[] = $asLinks;
        } else {
            $this->oProp->aPluginTitleLinks = array_merge($this->oProp->aPluginTitleLinks, $asLinks);
        }
        if ('plugins.php' !== $this->oProp->sPageNow) {
            return;
        }
        if (!isset($_sPluginBaseName)) {
            $_sPluginBaseName = plugin_basename($this->oProp->aScriptInfo['sPath']);
            add_filter("network_admin_plugin_action_links_{$_sPluginBaseName}", array($this, '_replyToAddLinkToPluginTitle'));
        }
    }
}