<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_Form_Model_Validation' ) ) :
/**
 * Deals with validating submitted options.
 * 
 * 
 * @abstract
 * @since           3.0.0
 * @since           3.3.1       Changed the name from `AdminPageFramework_Setting_Validation`.
 * @extends         AdminPageFramework_Setting_Port
 * @package         AdminPageFramework
 * @subpackage      AdminPage
 * @internal
 */
abstract class AdminPageFramework_Form_Model_Validation extends AdminPageFramework_Form_Model_Port {     
       
    /**
     * Handles the form submitted data.
     * 
     * If the form is submitted, it calls the validation callback method and reloads the page.
     * 
     * @since       3.1.0
     * @since       3.1.5       Moved from AdminPageFramework_Setting_Form.
     * @remark      This method is triggered after form elements are registered when the page is abut to be loaded with the `load_after_{instantiated class name}` hook.
     * @remark      The $_POST array will look like the below.
     *  <code>array(
     *      [option_page]       => APF_Demo
     *      [action]            => update
     *      [_wpnonce]          => d3f9bd2fbc
     *      [_wp_http_referer]  => /wp39x/wp-admin/edit.php?post_type=apf_posts&page=apf_builtin_field_types&tab=textfields
     *      [APF_Demo]          => Array (
     *          [text_fields] => Array( ...)
     *      )
     *      [page_slug]         => apf_builtin_field_types
     *      [tab_slug]          => textfields
     *      [_is_admin_page_framework] => ...
     *  )</code>
     *        
     */
    protected function _handleSubmittedData() {

        // 1. Verify the form submission. 
        if ( ! $this->_verifyFormSubmit() ) {
            return;
        }

        // 2. Apply user validation callbacks to the submitted data.
        // If only page-meta-boxes are used, it's possible that the option key element does not exist.
        
        // 2-1. Prepare the saved options 
        $_aDefaultOptions   = $this->oProp->getDefaultOptions( $this->oForm->aFields );     
        $_aOptions          = $this->oUtil->addAndApplyFilter( 
            $this, 
            "validation_saved_options_{$this->oProp->sClassName}", 
            $this->oUtil->uniteArrays( $this->oProp->aOptions, $_aDefaultOptions ), 
            $this
        );
        
        // 2-2. Prepare the user submit input data.
        $_aInput     = isset( $_POST[ $this->oProp->sOptionKey ] ) 
            ? stripslashes_deep( $_POST[ $this->oProp->sOptionKey ] )
            : array();        
        // copy one for parsing as $aInput will be merged with the default options.
        $_aInputRaw  = $_aInput;       
        
        // Merge the submitted input data with the default options. Now $_aInput is modified.
        $_sTabSlug   = isset( $_POST['tab_slug'] )   ? $_POST['tab_slug']    : ''; 
        $_sPageSlug  = isset( $_POST['page_slug'] )  ? $_POST['page_slug']   : '';        
        $_aInput     = $this->oUtil->uniteArrays( 
            $_aInput, 
            $this->oUtil->castArrayContents( 
                $_aInput, 
                // do not include the default values of the submitted page's elements as they merge recursively
                $this->_removePageElements( $_aDefaultOptions, $_sPageSlug, $_sTabSlug )
            ) 
        );                

        /* 3. Execute the submit_{...} actions. */
        $_aSubmit           = isset( $_POST['__submit'] )   ? $_POST['__submit']    : array();
        $_sPressedFieldID   = $this->_getPressedSubmitButtonData( $_aSubmit, 'field_id' );
        $_sPressedInputID   = $this->_getPressedSubmitButtonData( $_aSubmit, 'input_id' );        
        $_sSubmitSectionID  = $this->_getPressedSubmitButtonData( $_aSubmit, 'section_id' );
        if ( has_action( "submit_{$this->oProp->sClassName}_{$_sPressedInputID}" ) ) {
            trigger_error( 
                'Admin Page Framework: ' . ' : ' 
                    . sprintf( 
                        __( 'The hook <code>%1$s</code>is deprecated. Use <code>%2$s</code> instead.', $this->oProp->sTextDomain ), 
                        "submit_{instantiated class name}_{pressed input id}", 
                        "submit_{instantiated class name}_{pressed field id}"
                    ), 
                E_USER_NOTICE 
            );
        }
        $this->oUtil->addAndDoActions(
            $this,
            array( 
                // @todo deprecate the hook with the input ID
                "submit_{$this->oProp->sClassName}_{$_sPressedInputID}",  // will be deprecated in near future release
                $_sSubmitSectionID 
                    ? "submit_{$this->oProp->sClassName}_{$_sSubmitSectionID}_{$_sPressedFieldID}" 
                    : "submit_{$this->oProp->sClassName}_{$_sPressedFieldID}",
                $_sSubmitSectionID 
                    ? "submit_{$this->oProp->sClassName}_{$_sSubmitSectionID}" 
                    : null, // if null given, the method will ignore it
                isset( $_POST['tab_slug'] ) 
                    ? "submit_{$this->oProp->sClassName}_{$_sPageSlug}_{$_sTabSlug}"
                    : null, // if null given, the method will ignore it
                "submit_{$this->oProp->sClassName}_{$_sPageSlug}",
                "submit_{$this->oProp->sClassName}",
            ),
            // 3.3.1+ Added parameters to be passed
            $_aInput,
            $_aOptions,
            $this
        );     

        // 4. Validate the data.
        $_aStatus   = array( 'settings-updated' => true );        
        $_aInput    = $this->_validateSubmittedData( 
            $_aInput, 
            $_aInputRaw,    // without default values being merged.
            $_aOptions,
            $_aDefaultOptions,
            $_aStatus   // passed by reference
        ); 
        
        // 5. Save the data.
        $_bUpdated = false;
        if ( ! $this->oProp->_bDisableSavingOptions ) {  
            $_bUpdated = $this->oProp->updateOption( $_aInput );
        }
        
        // 6. Trigger the submit_after_{...} action hooks. [3.3.1+]
        $this->oUtil->addAndDoActions(
            $this,
            array( 
                $_sSubmitSectionID 
                    ? "submit_after_{$this->oProp->sClassName}_{$_sSubmitSectionID}_{$_sPressedFieldID}" 
                    : "submit_after_{$this->oProp->sClassName}_{$_sPressedFieldID}",
                $_sSubmitSectionID 
                    ? "submit_after_{$this->oProp->sClassName}_{$_sSubmitSectionID}" 
                    : null, 
                isset( $_POST['tab_slug'] ) 
                    ? "submit_after_{$this->oProp->sClassName}_{$_sPageSlug}_{$_sTabSlug}" 
                    : null, 
                "submit_after_{$this->oProp->sClassName}_{$_sPageSlug}",
                "submit_after_{$this->oProp->sClassName}",
            ),
            // 3.3.1+ Added parameters to be passed
            $_bUpdated ? $_aInput : array(),
            $_aOptions,
            $this
        );           
       
        // 7. Reload the page with the update notice.
        exit( wp_redirect( $this->_getSettingUpdateURL( $_aStatus, $_sPageSlug, $_sTabSlug ) ) );
        
    }
        /**
         * Returns the url to reload.
         * 
         * Sanitizes the $_GET query key-values.
         * 
         * @since       3.4.1
         */
        private function _getSettingUpdateURL( array $aStatus, $sPageSlug, $sTabSlug ) {
            
            // Apply filters. This allows page-meta-box classes to insert the 'field_errors' key when they have validation errors.
            $aStatus = $this->oUtil->addAndApplyFilters(    // 3.4.1+
                $this, 
                array( 
                    "options_update_status_{$sPageSlug}_{$sTabSlug}",
                    "options_update_status_{$sPageSlug}", 
                    "options_update_status_{$this->oProp->sClassName}", 
                ), 
                $aStatus
            ); 
            
            // Drop the 'field_errors' key.
            $_aRemoveQueries = array();
            if ( ! isset( $aStatus[ 'field_errors' ] ) || ! $aStatus[ 'field_errors' ] ) {
                unset( $aStatus[ 'field_errors' ] );
                $_aRemoveQueries[] = 'field_errors';
            }        
            return $this->oUtil->getQueryURL( $aStatus, $_aRemoveQueries, $_SERVER['REQUEST_URI'] );            
            
        }
        /**
         * Verifies the form submit.
         * 
         * @since       3.3.1
         * @internal
         * @return      boolean     True if it is verified; otherwise, false.
         */
        private function _verifyFormSubmit() {
            
            if ( 
                ! isset( 
                    // The framework specific keys
                    $_POST['_is_admin_page_framework'], // holds the form nonce
                    $_POST['page_slug'], 
                    $_POST['tab_slug'], 
                    $_POST['_wp_http_referer']
                ) 
            ) {     
                return false;
            }
            $_sRequestURI   = remove_query_arg( array( 'settings-updated', 'confirmation', 'field_errors' ), wp_unslash( $_SERVER['REQUEST_URI'] ) );
            $_sReffererURI  = remove_query_arg( array( 'settings-updated', 'confirmation', 'field_errors' ), $_POST['_wp_http_referer'] );
            if ( $_sRequestURI != $_sReffererURI ) { // see the function definition of wp_referer_field() in functions.php.
                return false;
            }
            
            $_sNonceTransientKey = 'form_' . md5( $this->oProp->sClassName . get_current_user_id() );
            if ( $_POST['_is_admin_page_framework'] !== $this->oUtil->getTransient( $_sNonceTransientKey ) ) {
                $this->setAdminNotice( $this->oMsg->get( 'nonce_verification_failed' ) );
                return false;
            }
            // Do not delete the nonce transient to let it vanish by itself. This allows the user to open multiple pages/tabs in their browser and save forms by switching pages/tabs.
            // $this->oUtil->deleteTransient( $_sNonceTransientKey );            
            
            return true;
            
        }
       
    /**
     * Validates the submitted user input.
     * 
     * @since       2.0.0
     * @since       3.3.0       Changed the name from _doValidationCall(). The input array is passed by reference and returns the status array.
     * @access      protected
     * @remark      This method is not intended for the users to use.
     * @remark      the scope must be protected to be accessed from the extended class. The <em>AdminPageFramework</em> class uses this method in the overloading <em>__call()</em> method.
     * @param       array       $aInput     The modifying submitted form data. It will be merged with the original saved options so that other page's data will not be lost.
     * @param       array       &$aStatus   A status array that will be inserted in the url $_GET query array in next page load, passed by reference.
     * @return      array       Returns the filtered validated input which will be saved in the options table.
     * @internal
     */ 
    protected function _validateSubmittedData( $aInput, $aInputRaw, $aOptions, $aDefaultOptions, &$aStatus ) {

        // 1. Set up local variables.
        
        // no need to retrieve the default tab slug here because it's an embedded value that is already set in the previous page. 
        $_sTabSlug  = isset( $_POST['tab_slug'] )   ? $_POST['tab_slug']    : ''; 
        $_sPageSlug = isset( $_POST['page_slug'] )  ? $_POST['page_slug']   : '';
        $_aSubmit   = isset( $_POST['__submit'] )   ? $_POST['__submit']    : array();
        
        // Retrieve the pressed submit field data.
        $_sPressedInputName         = $this->_getPressedSubmitButtonData( $_aSubmit, 'name' );
        $_bIsReset                  = $this->_getPressedSubmitButtonData( $_aSubmit, 'is_reset' );  // if the 'reset' key in the field definition array is set, this value will be set.
        $_sKeyToReset               = $this->_getPressedSubmitButtonData( $_aSubmit, 'reset_key' ); // this will be set if the user confirms the reset action.
        $_sSubmitSectionID          = $this->_getPressedSubmitButtonData( $_aSubmit, 'section_id' );
        $_bConfirmingToSendEmail    = $this->_getPressedSubmitButtonData( $_aSubmit, 'confirming_sending_email' );
        $_bConfirmedToSendEmail     = $this->_getPressedSubmitButtonData( $_aSubmit, 'confirmed_sending_email' );
                  
        
        // 2. Custom submit actions [part 1]
        // Check if the sending email is confirmed - this should be done before the redirect because the user may set a redirect and email. In that case, send the email first and redirect to the set page.
        if ( $_bConfirmedToSendEmail ) {
            // @todo Consider passing $aInput rather than $aInputRaw.
            $this->_sendEmailInBackground( $aInputRaw, $_sPressedInputName, $_sSubmitSectionID );
            $this->oProp->_bDisableSavingOptions = true;
            $this->oUtil->deleteTransient( 'apf_tfd' . md5( 'temporary_form_data_' . $this->oProp->sClassName . get_current_user_id() ) );
            return $aInputRaw;
        }                
        
        // Import
        if ( isset( $_POST['__import']['submit'], $_FILES['__import'] ) ) {
            return $this->_importOptions( $this->oProp->aOptions, $_sPageSlug, $_sTabSlug );
        } 
        
        // Export
        if ( isset( $_POST['__export']['submit'] ) ) {
            exit( $this->_exportOptions( $this->oProp->aOptions, $_sPageSlug, $_sTabSlug ) );     
        }
        
        // Reset
        if ( $_bIsReset ) {
            $aStatus = $aStatus + array( 'confirmation' => 'reset' );
            return $this->_confirmSubmitButtonAction( $_sPressedInputName, $_sSubmitSectionID, 'reset' );
        }
        
        // Link button
        if ( $_sLinkURL = $this->_getPressedSubmitButtonData( $_aSubmit, 'link_url' ) ) {
            exit( wp_redirect( $_sLinkURL ) ); // if the associated submit button for the link is pressed, it will be redirected.
        }
        
        // Redirect button
        if ( $_sRedirectURL = $this->_getPressedSubmitButtonData( $_aSubmit, 'redirect_url' ) ) {
            $aStatus = $aStatus + array( 'confirmation' => 'redirect' );
            $this->_setRedirectTransients( $_sRedirectURL, $_sPageSlug );
        }
    
        // 3. Validate the submitted input data 
        $aInput           = $this->_getFilteredOptions( $aInput, $aInputRaw, $aOptions, $_sPageSlug, $_sTabSlug );
        $_bHasFieldErrors = $this->hasFieldError();
        if ( $_bHasFieldErrors ) {
            $this->_setLastInput( $aInputRaw );
            $aStatus = $aStatus + array( 'field_errors' => $_bHasFieldErrors );  // 3.4.1+
        } 
   
        /* 4. Custom submit actions [part 2] - these should be done after applying the filters. */
        
        // Reset
        if ( $_sKeyToReset ) {
            $aInput = $this->_resetOptions( $_sKeyToReset, $aInput );
        }
                
        // Email
        if ( ! $_bHasFieldErrors && $_bConfirmingToSendEmail ) {
            $this->_setLastInput( $aInput );
            $this->oProp->_bDisableSavingOptions = true;
            $aStatus    = $aStatus + array( 'confirmation' => 'email' );
            return $this->_confirmSubmitButtonAction( $_sPressedInputName, $_sSubmitSectionID, 'email' );            
        }        
        
        // 5. Set the admin notice.
        if ( ! $this->hasSettingNotice() ) {     
            $_bEmpty = empty( $aInput );
            $this->setSettingNotice( 
                $_bEmpty ? $this->oMsg->get( 'option_cleared' ) : $this->oMsg->get( 'option_updated' ), 
                $_bEmpty ? 'error' : 'updated', 
                $this->oProp->sOptionKey, // the id
                false // do not override
            );
        }
        
        // 6. Return
        return $aInput;
        
    }
    
        /**
         * Sends an email set via the form.
         * 
         * The email contents should be set with the form fields. 
         * 
         * @since   3.3.0
         */
        private function _sendEmailInBackground( $aInput, $sPressedInputNameFlat, $sSubmitSectionID ) {
            
            $_sTranskentKey = 'apf_em_' . md5( $sPressedInputNameFlat . get_current_user_id() );
            $_aEmailOptions = $this->oUtil->getTransient( $_sTranskentKey );
            $this->oUtil->deleteTransient( $_sTranskentKey );
            $_aEmailOptions = $this->oUtil->getAsArray( $_aEmailOptions ) + array(
                'to'            => '',
                'subject'       => '',
                'message'       => '',
                'headers'       => '',
                'attachments'   => '',
                'is_html'       => false,
                'from'          => '',
                'name'          => '',
            );

            $_sTransientKey  = 'apf_emd_' . md5( $sPressedInputNameFlat . get_current_user_id() );
            $_aFormEmailData = array(
                'email_options' => $_aEmailOptions,
                'input'         => $aInput,
                'section_id'    => $sSubmitSectionID,
            );
            $_bIsSet = $this->oUtil->setTransient( $_sTransientKey,  $_aFormEmailData, 100 );
            
            // Send the email in the background.
            $_oaResponse = wp_remote_get( 
                add_query_arg( 
                    array( 
                        'apf_action' => 'email',
                        'transient'  => $_sTransientKey,
                    ), 
                    admin_url( $GLOBALS['pagenow'] ) 
                ),
                array( 
                    'timeout'     => 0.01, 
                    'sslverify'   => false, 
                ) 
            );  
            
            // not possible to tell whether it is sent or not at the moment because it is performed in the background.
            $_bSent      = $_bIsSet;    
            $this->setSettingNotice( 
                $this->oMsg->get( 
                    $_bSent 
                        ? 'email_sent' 
                        : 'email_could_not_send'
                ),
                $_bSent ? 'updated' : 'error'
            );
        
        }   
 
            
        /**
         * Confirms the given submit button action and sets a confirmation message as a field error message and admin notice.
         * 
         * @since   2.1.2
         * @since   3.3.0       Changed the name from _askResetOptions(). Deprecated the page slug parameter. Added the $sType parameter.
         * @return  array       The intact stored options.
         */
        private function _confirmSubmitButtonAction( $sPressedInputName, $sSectionID, $sType='reset' ) {
            
            switch( $sType ) {
                default:
                case 'reset':
                    $_sFieldErrorMessage = $this->oMsg->get( 'reset_options' );
                    $_sTransientKey      =  'apf_rc_' . md5( $sPressedInputName . get_current_user_id() );
                    break;
                case 'email':
                    $_sFieldErrorMessage = $this->oMsg->get( 'send_email' );
                    $_sTransientKey      =  'apf_ec_' . md5( $sPressedInputName . get_current_user_id() );
                    break;                
            }
            
            // Retrieve the pressed button's associated submit field ID.
            $_aNameKeys = explode( '|', $sPressedInputName );    
            $_sFieldID  = $sSectionID 
                ? $_aNameKeys[ 2 ]  // OptionKey|section_id|field_id
                : $_aNameKeys[ 1 ]; // OptionKey|field_id
            
            // Set up the field error array to show a confirmation message just above the field besides the admin notice at the top of the page.
            $_aErrors = array();
            if ( $sSectionID ) {
                $_aErrors[ $sSectionID ][ $_sFieldID ] = $_sFieldErrorMessage;
            } else {
                $_aErrors[ $_sFieldID ] = $_sFieldErrorMessage;
            }
            $this->setFieldErrors( $_aErrors );
                
            // Set a flag that the confirmation is displayed
            $this->oUtil->setTransient( $_sTransientKey, $sPressedInputName, 60*2 );
            
            // Set the admin notice
            $this->setSettingNotice( $this->oMsg->get( 'confirm_perform_task' ) );            
            
            // Their returned options will be saved so returned the saved options not to change anything.
            return $this->oProp->aOptions;
            
        }
             
        /**
         * Performs reset options.
         * 
         * @since 2.1.2
         * @remark $aInput has only the page elements that called the validation callback. In other words, it does not hold other pages' option keys.
         */
        private function _resetOptions( $sKeyToReset, $aInput ) {
            
            // As of 3.1.0, an empty value is accepted for the option key.
            if ( ! $this->oProp->sOptionKey ) {
                return array();
            }
            
            // The key to delete is not specified.
            if ( $sKeyToReset == 1 || $sKeyToReset === true ) {
                delete_option( $this->oProp->sOptionKey );
                return array();
            }
            
            // The key to reset is specified.
            // @todo: make it possible to specify a dimensional key.
            unset( $this->oProp->aOptions[ trim( $sKeyToReset ) ], $aInput[ trim( $sKeyToReset ) ] );
            update_option( $this->oProp->sOptionKey, $this->oProp->aOptions );
            $this->setSettingNotice( $this->oMsg->get( 'specified_option_been_deleted' ) );
        
            return $aInput; // the returned array will be saved with the Settings API.
        }
        
        /**
         * Sets the given URL's transient.
         */
        private function _setRedirectTransients( $sURL, $sPageSlug ) {
            if ( empty( $sURL ) ) { return; }
            $_sTransient = 'apf_rurl' . md5( trim( "redirect_{$this->oProp->sClassName}_{$sPageSlug}" ) );
            return $this->oUtil->setTransient( $_sTransient, $sURL , 60*2 );
        }
        
        /**
         * Retrieves the target key's value associated with the given data to a custom submit button.
         * 
         * This method checks if the associated submit button is pressed with the input fields.
         * 
         * @since   2.0.0
         * @return  null|string Returns null if no button is found and the associated link url if found. Otherwise, the URL associated with the button.
         * @remark  The structure of the $aPostElements array looks like this:
         * <code>[submit_buttons_submit_button_field_0] => Array
         *      (
         *          [input_id] => submit_buttons_submit_button_field_0
         *          [field_id] => submit_button_field
         *          [name] => APF_Demo|submit_buttons|submit_button_field
         *          [section_id] => submit_buttons
         *      )
         *
         *  [submit_buttons_submit_button_link_0] => Array
         *      (
         *          [input_id] => submit_buttons_submit_button_link_0
         *          [field_id] => submit_button_link
         *          [name] => APF_Demo|submit_buttons|submit_button_link|0
         *          [section_id] => submit_buttons
         *      )
         * </code>
         * The keys are the input id.
         */ 
        private function _getPressedSubmitButtonData( $aPostElements, $sTargetKey='field_id' ) {    

            foreach( $aPostElements as $_sInputID => $_aSubElements ) {
                
                // the 'name' key must be set.
                $_aNameKeys = explode( '|', $_aSubElements[ 'name' ] ); 
                
                // The count of 4 means it's a single element. Count of 5 means it's one of multiple elements.
                // The isset() checks if the associated button is actually pressed or not.
                if ( count( $_aNameKeys ) == 2 && isset( $_POST[ $_aNameKeys[0] ][ $_aNameKeys[1] ] ) ) {
                    return isset( $_aSubElements[ $sTargetKey ] ) ? $_aSubElements[ $sTargetKey ] :null;
                }
                if ( count( $_aNameKeys ) == 3 && isset( $_POST[ $_aNameKeys[0] ][ $_aNameKeys[1] ][ $_aNameKeys[2] ] ) ) {
                    return isset( $_aSubElements[ $sTargetKey ] ) ? $_aSubElements[ $sTargetKey ] :null;
                }
                if ( count( $_aNameKeys ) == 4 && isset( $_POST[ $_aNameKeys[0] ][ $_aNameKeys[1] ][ $_aNameKeys[2] ][ $_aNameKeys[3] ] ) ) {
                    return isset( $_aSubElements[ $sTargetKey ] ) ? $_aSubElements[ $sTargetKey ] :null;
                }
                    
            }
            return null; // not found
            
        }
    
        /**
         * Applies validation filters to the submitted input data.
         * 
         * @since       2.0.0
         * @since       2.1.5       Added the `$sPressedFieldID` and `$sPressedInputID` parameters.
         * @since       3.0.0       Removed the `$sPressedFieldID` and `$sPressedInputID` parameters.
         * @return      array       The filtered input array.
         */
        private function _getFilteredOptions( $aInput, $aInputRaw, $aOptions, $sPageSlug, $sTabSlug ) {
               
            $_aOptionsWODynamicElements = $this->oUtil->addAndApplyFilter( 
                $this, 
                "validation_saved_options_without_dynamic_elements_{$this->oProp->sClassName}", 
                $this->oForm->dropRepeatableElements( $aOptions ),
                $this
            ); // 3.4.1+ 

            // For each submitted element
            $aInput         = $this->_validateEachField( 
                $aInput, 
                $aOptions, 
                $_aOptionsWODynamicElements, 
                $aInputRaw, 
                $sPageSlug, 
                $sTabSlug 
            );
            
            // For tabs     
            $_aTabOptions   = array(); // stores options of the belonging in-page tab.
            $aInput         = $this->_validateTabFields( 
                $aInput, 
                $aOptions, 
                $_aOptionsWODynamicElements, 
                $_aTabOptions,  // passed by reference
                $sPageSlug,
                $sTabSlug 
            );
 
            // For pages
            $aInput = $this->_validatePageFields( 
                $aInput,
                $aOptions,
                $_aOptionsWODynamicElements,
                $_aTabOptions,
                $sPageSlug,
                $sTabSlug
            );
        
            // For the class
            return $this->oUtil->addAndApplyFilter( 
                $this, 
                "validation_{$this->oProp->sClassName}", 
                $aInput, 
                $aOptions, 
                $this 
            );
        
        }    
            
            /**
             * Validates each field or section.
             * 
             * @since       3.0.2
             */
            private function _validateEachField( array $aInput, array $aOptions, array $aOptionsWODynamicElements, array $aInputToParse, $sPageSlug, $sTabSlug ) {
                
                foreach( $aInputToParse as $sID => $aSectionOrFields ) { // $sID is either a section id or a field id
                    
                    // For each section
                    if ( $this->oForm->isSection( $sID ) ) {
                        
                        // If the parsing item does not belong to the current page, do not call the validation callback method.
                        if ( 
                            ( $sPageSlug && isset( $this->oForm->aSections[ $sID ][ 'page_slug' ] ) && $this->oForm->aSections[ $sID ][ 'page_slug' ] != $sPageSlug )
                            || ( $sTabSlug && isset( $this->oForm->aSections[ $sID ][ 'tab_slug' ] ) && $this->oForm->aSections[ $sID ][ 'tab_slug' ] != $sTabSlug )
                        ) {
                            continue;
                        }
                        
                        // Call the validation method.
                        foreach( $aSectionOrFields as $sFieldID => $aFields ) { // For fields
                            $aInput[ $sID ][ $sFieldID ] = $this->oUtil->addAndApplyFilter( 
                                $this, 
                                "validation_{$this->oProp->sClassName}_{$sID}_{$sFieldID}", 
                                $aInput[ $sID ][ $sFieldID ], 
                                isset( $aOptions[ $sID ][ $sFieldID ] ) ? $aOptions[ $sID ][ $sFieldID ] : null,
                                $this
                            );
                        }
                        
                        // For an entire section - consider each field has a different individual capability. In that case, the key itself will not be sent,
                        // which causes data loss when a lower capability user submits the form but it was stored by a higher capability user.
                        // So merge the submitted array with the old stored array only for the first level.
                        $_aSectionInput = is_array( $aInput[ $sID ] ) ? $aInput[ $sID ] : array();
                        $_aSectionInput = $_aSectionInput + ( isset( $aOptionsWODynamicElements[ $sID ] ) && is_array( $aOptionsWODynamicElements[ $sID ] ) ? $aOptionsWODynamicElements[ $sID ] : array() );
                        $aInput[ $sID ] = $this->oUtil->addAndApplyFilter( 
                            $this, 
                            "validation_{$this->oProp->sClassName}_{$sID}", 
                            $_aSectionInput,
                            isset( $aOptions[ $sID ] ) ? $aOptions[ $sID ] : null,
                            $this
                        );     
                        
                        continue;
                        
                    }
                                        
                    // Check if the parsing item(the default section) belongs to the current page; if not, do not call the validation callback method.
                    if ( 
                        ( $sPageSlug && isset( $this->oForm->aSections[ '_default' ][ 'page_slug' ] ) && $this->oForm->aSections[ '_default' ][ 'page_slug' ] != $sPageSlug )
                        || ( $sTabSlug && isset( $this->oForm->aSections[ '_default' ][ 'tab_slug' ] ) && $this->oForm->aSections[ '_default' ][ 'tab_slug' ] != $sTabSlug )
                    ) {
                        continue;
                    }     
                    // For a field
                    $aInput[ $sID ] = $this->oUtil->addAndApplyFilter( 
                        $this, 
                        "validation_{$this->oProp->sClassName}_{$sID}", 
                        $aInput[ $sID ], 
                        isset( $aOptions[ $sID ] ) ? $aOptions[ $sID ] : null,
                        $this
                    );
                    
                }
                
                return $aInput;
                
            }    
            
            /**
             * Validates field options which belong to the given in-page tab.
             * 
             * @since       3.0.2
             */
            private function _validateTabFields( array $aInput, array $aOptions, array $aOptionsWODynamicElements, & $aTabOptions, $sPageSlug, $sTabSlug ) {
                
                if ( ! ( $sTabSlug && $sPageSlug ) ) { return $aInput; }
                                
                $_aTabOnlyOptions   = $this->oForm->getTabOnlyOptions( $aOptions, $sPageSlug, $sTabSlug ); // does not respect page meta box fields
                $aTabOptions        = $this->oForm->getTabOptions( $aOptions, $sPageSlug, $sTabSlug ); // respects page meta box fields
                $aTabOptions        = $this->oUtil->addAndApplyFilter( $this, "validation_saved_options_{$sPageSlug}_{$sTabSlug}", $aTabOptions, $this );
            
                // Consider each field has a different individual capability. In that case, the key itself will not be sent,
                // which causes data loss when a lower capability user submits the form but it was stored by a higher capability user.
                // So merge the submitted array with the old stored array only for the first level.     
                $_aTabOnlyOptionsWODynamicElements = $this->oForm->getTabOnlyOptions( $aOptionsWODynamicElements, $sPageSlug, $sTabSlug ); // this method excludes injected elements such as page-meta-box fields
                $_aTabOnlyOptionsWODynamicElements = $this->oUtil->addAndApplyFilter( $this, "validation_saved_options_without_dynamic_elements_{$sPageSlug}_{$sTabSlug}", $_aTabOnlyOptionsWODynamicElements, $this );    // 3.4.1+
                $aInput = $aInput + $this->oForm->getTabOptions( $_aTabOnlyOptionsWODynamicElements, $sPageSlug, $sTabSlug );
                
                $aInput = $this->oUtil->addAndApplyFilter( 
                    $this, 
                    "validation_{$sPageSlug}_{$sTabSlug}",
                    $aInput,
                    $aTabOptions,
                    $this 
                );
                return $this->oUtil->uniteArrays( 
                    $aInput, 
                    $this->oUtil->invertCastArrayContents( $aTabOptions, $_aTabOnlyOptions ), // will only consist of page meta box fields
                    $this->oForm->getOtherTabOptions( $aOptions, $sPageSlug, $sTabSlug )
                );
                
            }     
            
            /**
             * Validates field options which belong to the given page.
             * 
             * @since       3.0.2
             */
            private function _validatePageFields( array $aInput, array $aOptions, array $aOptionsWODynamicElements, array $aTabOptions, $sPageSlug, $sTabSlug ) {
                
                if ( ! $sPageSlug ) { return $aInput; }

                // Prepare the saved page option array.
                $_aPageOptions = $this->oForm->getPageOptions( $aOptions, $sPageSlug ); // this method respects injected elements into the page ( page meta box fields )     
                $_aPageOptions = $this->oUtil->addAndApplyFilter( $this, "validation_saved_options_{$sPageSlug}", $_aPageOptions, $this );
                
                // Consider each field has a different individual capability. In that case, the key itself will not be sent,
                // which causes data loss when a lower capability user submits the form but it was stored by a higher capability user.
                // So merge the submitted array with the old stored array only for the first level.     
                $_aPageOnlyOptionsWODynamicElements = $this->oForm->getPageOnlyOptions( $aOptionsWODynamicElements, $sPageSlug ); // this method excludes injected elements
                $_aPageOnlyOptionsWODynamicElements = $this->oUtil->addAndApplyFilter( $this, "validation_saved_options_without_dynamic_elements_{$sPageSlug}", $_aPageOnlyOptionsWODynamicElements, $this );    // 3.4.1+
                $aInput = $aInput + $this->oForm->getPageOptions( $_aPageOnlyOptionsWODynamicElements, $sPageSlug );
                
                $aInput = $this->oUtil->addAndApplyFilter(
                    $this, 
                    "validation_{$sPageSlug}", 
                    $aInput,            // new values
                    $_aPageOptions,     // stored page options
                    $this 
                );

                // If it's in a tab-page, drop the elements which belong to the tab so that arrayed-options will not be merged such as multiple select options.
                $_aPageOptions = $sTabSlug && ! empty( $aTabOptions ) 
                    ? $this->oUtil->invertCastArrayContents( $_aPageOptions, $aTabOptions ) 
                    : ( ! $sTabSlug // if the tab is not specified, do not merge the input array with the page options as the input array already includes the page options. This is for dynamic elements(repeatable sections).
                        ? array()
                        : $_aPageOptions
                    );
            
                return $this->oUtil->uniteArrays( 
                    $aInput, 
                    $_aPageOptions, // repeatable elements have been dropped
                    $this->oUtil->invertCastArrayContents( $this->oForm->getOtherPageOptions( $aOptions, $sPageSlug ), $_aPageOptions )
                );    
                                
            }     
            
            /**
             * Removes option array elements that belong to the given page/tab by their slug.
             * 
             * This is used when merging options and avoiding merging options that have an array structure as the framework uses the recursive merge
             * and if an option is not a string but an array, the default array of such a structure will merge with the user input of the corresponding structure. 
             * This problem will occur with the select field type with multiple attribute enabled. 
             * 
             * @since       3.0.0
             */
            private function _removePageElements( $aOptions, $sPageSlug, $sTabSlug ) {
                
                if ( ! $sPageSlug && ! $sTabSlug ) { return $aOptions; }
                
                // If the tab is given
                if ( $sTabSlug && $sPageSlug ) {
                    return $this->oForm->getOtherTabOptions( $aOptions, $sPageSlug, $sTabSlug );
                }
                
                // If only the page is given 
                return $this->oForm->getOtherPageOptions( $aOptions, $sPageSlug );
                
            }
            
}
endif;