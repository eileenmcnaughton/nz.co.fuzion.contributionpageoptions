<?php

require_once 'contributionpageoptions.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function contributionpageoptions_civicrm_config(&$config) {
  _contributionpageoptions_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function contributionpageoptions_civicrm_xmlMenu(&$files) {
  _contributionpageoptions_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function contributionpageoptions_civicrm_install() {
  return _contributionpageoptions_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function contributionpageoptions_civicrm_uninstall() {
  return _contributionpageoptions_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function contributionpageoptions_civicrm_enable() {
  return _contributionpageoptions_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function contributionpageoptions_civicrm_disable() {
  return _contributionpageoptions_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function contributionpageoptions_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _contributionpageoptions_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function contributionpageoptions_civicrm_managed(&$entities) {
  return _contributionpageoptions_civix_civicrm_managed($entities);
}

/**
 * Implementation of entity setting hook_civicrm_alterEntitySettingsFolders
 * declare folders with entity settings
 */

function contributionpageoptions_civicrm_alterEntitySettingsFolders(&$folders) {
  static $configured = FALSE;
  if ($configured) return;
  $configured = TRUE;

  $extRoot = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
  $extDir = $extRoot . 'settings';
  if(!in_array($extDir, $folders)){
    $folders[] = $extDir;
  }
}

/**
 * implements postProcess hook
 * @param string $formName
 * @param object $form
 */
function contributionpageoptions_civicrm_postProcess($formName, &$form) {
  if($formName != 'CRM_Contribute_Form_Contribution_Confirm') {
    return;
  }

  if(($redirectTo = _contributionpageoptions_getredirect($form->get('id'))) != FALSE) {
    CRM_Utils_System::redirect($redirectTo);
  }
}

/**
 * Get permission for a given entity id in a given direction
 * @param integer $entity_id
 * @return string
 */
function _contributionpageoptions_getredirect($entity_id) {
  $entity_settings = civicrm_api3('entity_setting', 'get', array(
    'key' => 'nz.co.fuzion.contributionpageoptions',
    'entity_id' => $entity_id,
    'entity_type' => 'contribution_page')
  );
  return CRM_Utils_Array::value('thankyou_redirect', $entity_settings['values']);
}
