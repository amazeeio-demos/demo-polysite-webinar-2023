<?php

/**
 * @file
 * Contains Drupal\custom_module\Form\CustomModuleForm.
 */

namespace Drupal\custom_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomModuleForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_module_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('custom_module.settings');

    // Source text field.
    $form['custom_module_settings'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Region Name settings'),
      '#default_value' => $config->get('custom_module.custom_module_settings'),
      '#description' => $this->t('Enter region name setting'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('custom_module.settings');
    $config->set('custom_module.custom_module_settings', $form_state->getValue('custom_module_settings'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_module.settings',
    ];
  }

}
