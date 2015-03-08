<?php
/**
 Admin Page Framework v3.5.5 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework_Form_View extends AdminPageFramework_Form_Model {
    public function _replyToGetSectionHeaderOutput($sSectionDescription, $aSection) {
        return $this->oUtil->addAndApplyFilters($this, array('section_head_' . $this->oProp->sClassName . '_' . $aSection['section_id']), $sSectionDescription);
    }
    public function _replyToGetFieldOutput($aField) {
        $_sCurrentPageSlug = $this->oProp->getCurrentPageSlug();
        $_sSectionID = $this->oUtil->getElement($aField, 'section_id', '_default');
        $_sFieldID = $aField['field_id'];
        if ($aField['page_slug'] != $_sCurrentPageSlug) {
            return '';
        }
        $this->aFieldErrors = isset($this->aFieldErrors) ? $this->aFieldErrors : $this->_getFieldErrors($_sCurrentPageSlug);
        $sFieldType = isset($this->oProp->aFieldTypeDefinitions[$aField['type']]['hfRenderField']) && is_callable($this->oProp->aFieldTypeDefinitions[$aField['type']]['hfRenderField']) ? $aField['type'] : 'default';
        $_aTemp = $this->getSavedOptions();
        $_oField = new AdminPageFramework_FormField($aField, $_aTemp, $this->aFieldErrors, $this->oProp->aFieldTypeDefinitions, $this->oMsg);
        $_sFieldOutput = $_oField->_getFieldOutput();
        unset($_oField);
        return $this->oUtil->addAndApplyFilters($this, array(isset($aField['section_id']) && $aField['section_id'] != '_default' ? 'field_' . $this->oProp->sClassName . '_' . $aField['section_id'] . '_' . $_sFieldID : 'field_' . $this->oProp->sClassName . '_' . $_sFieldID,), $_sFieldOutput, $aField);
    }
}