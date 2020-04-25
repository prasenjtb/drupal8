<?php

namespace Drupal\customconfig\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Extension\ModuleHandler;

/**
 * Defines a form that configures forms module settings.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'customconfig_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'customconfig.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	  
	global $base_path;
    $customconfig = $this->config('customconfig.settings');
    $assets_type = $customconfig->get('assets_type') ? $customconfig->get('assets_type') : 'default';		
								//echo "assets_type >> ".$customconfig->get('assets_type');
								//echo " assets_type >> ".$customconfig->get('assets_paths');
        //"" => t("- Select -"), 
    $assets_type_options = array('default' => 'Default', 'custom' => 'Custom');
    //Assets Type
    $form['assets_type'] = array(
			'#type' => 'select',
			'#title' => $this->t('Assets type'),
			'#options' => $assets_type_options,		
			'#required' => false,
			'#default_value' => $customconfig->get('assets_type'),
			'#attributes' => [
				// 'id' => 'select-assets-type',
				'name' => 'select_assets_type',
				//'class' => ['form-control'],
			], 
		);
	
	$form['assets_paths'] = [
		  '#type' => 'textarea',
		  '#title' => $this->t('Assets paths'),
		  '#default_value' => $customconfig->get('assets_paths'),
		  '#attributes' => [
			'id' => 'assets-paths',
		  ],
		  '#states' => [
			//show this textfield only if the radio 'other' is selected above
			'visible' => [
			  ':input[name="select_assets_type"]' => ['value' => 'custom'],
			],
		  ],
    ];
    
    return parent::buildForm($form, $form_state);
  }
  
  
  

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
	  $values = $form_state->getValues();
    $this->config('customconfig.settings')
      ->set('assets_paths', $form_state->getValue('assets_paths'))
      ->set('assets_type', $form_state->getValue('assets_type'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
