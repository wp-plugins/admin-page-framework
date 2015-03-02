<?php
/**
 Admin Page Framework v3.5.4 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class AdminPageFramework_Link_PostType extends AdminPageFramework_Link_Base {
    public $aFooterInfo = array('sLeft' => '', 'sRight' => '',);
    public function __construct($oProp, $oMsg = null) {
        if (!$oProp->bIsAdmin) {
            return;
        }
        $this->oProp = $oProp;
        $this->oMsg = $oMsg;
        if ($oProp->bIsAdminAjax) {
            return;
        }
        add_action('in_admin_footer', array($this, '_replyToSetFooterInfo'));
        if (isset($_GET['post_type']) && $_GET['post_type'] == $this->oProp->sPostType) {
            add_action('get_edit_post_link', array($this, '_replyToAddPostTypeQueryInEditPostLink'), 10, 3);
        }
        if ('plugins.php' === $this->oProp->sPageNow && 'plugin' === $this->oProp->aScriptInfo['sType']) {
            add_filter('plugin_action_links_' . plugin_basename($this->oProp->aScriptInfo['sPath']), array($this, '_replyToAddSettingsLinkInPluginListingPage'), 20);
        }
    }
    public function _replyToAddSettingsLinkInPluginListingPage($aLinks) {
        $_sLinkLabel = isset($this->oProp->aPostTypeArgs['labels']['plugin_listing_table_title_cell_link']) ? $this->oProp->aPostTypeArgs['labels']['plugin_listing_table_title_cell_link'] : $this->oMsg->get('manage');
        if (!$_sLinkLabel) {
            return $aLinks;
        }
        array_unshift($aLinks, "<a href='" . esc_url("edit.php?post_type={$this->oProp->sPostType}") . "'>" . $_sLinkLabel . "</a>");
        return $aLinks;
    }
    public function _replyToSetFooterInfo() {
        if (!$this->isPostDefinitionPage($this->oProp->sPostType) && !$this->isPostListingPage($this->oProp->sPostType) && !$this->isCustomTaxonomyPage($this->oProp->sPostType)) {
            return;
        }
        $this->_setFooterInfoLeft($this->oProp->aScriptInfo, $this->aFooterInfo['sLeft']);
        $this->_setFooterInfoRight($this->oProp->_getLibraryData(), $this->aFooterInfo['sRight']);
        add_filter('admin_footer_text', array($this, '_replyToAddInfoInFooterLeft'));
        add_filter('update_footer', array($this, '_replyToAddInfoInFooterRight'), 11);
    }
    public function _replyToAddPostTypeQueryInEditPostLink($sURL, $iPostID = null, $sContext = null) {
        return add_query_arg(array('post' => $iPostID, 'action' => 'edit', 'post_type' => $this->oProp->sPostType), $sURL);
    }
    public function _replyToAddInfoInFooterLeft($sLinkHTML = '') {
        if (empty($this->oProp->aScriptInfo['sName'])) {
            return $sLinkHTML;
        }
        return $this->aFooterInfo['sLeft'];
    }
    public function _replyToAddInfoInFooterRight($sLinkHTML = '') {
        return $this->aFooterInfo['sRight'];
    }
}