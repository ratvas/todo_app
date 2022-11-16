<?php

namespace Drupal\todo_checklist\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

/**
 * Implements a ToDoApp Form.
 */
class TodoAppForm extends FormBase
{

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    // Checkbox for checking/unchecking all options.
    $form['master_on_off'] = [
      '#type' => 'checkbox',
    ];

    // Textfield.
    $form['title'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => $this->t('What needs to be done?'),
      ],
      '#size' => 60,
      '#maxlength' => 128,
    ];

    // CheckBoxes, ToDoChecklist options.
    $options = $this->getOptions();
    $form['todo_checkboxes'] = [
      '#type' => 'checkboxes',
      '#options' => $options,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    // Add a reset button
    $form['actions']['reset'] = [
      '#type' => 'button',
      '#button_type' => 'reset',
      '#value' => $this->t('Reset'),
      '#attributes' => [
        'onclick' => 'this.form.reset(); return false;',
      ],
    ];

    return $form;
  }

  /**
   * @return string
   */
  public function getFormId()
  {
    return 'todo_app_form';
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $title = $form_state->getValue('title');
    if (strlen($title) < 15) {
      $form_state->setErrorByName('title', $this->t('The title must be at least 15 characters long.'));
    }
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $title = $form_state->getValue('title');
    $this->messenger()->addStatus($this->t('You specified a title of @title.', ['@title' => $title]));
  }

  /**
   * Helper method to get necessary ToDoChecklist options.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getOptions()
  {
    $options = ['Drupal', 'Wp'];

    $http_client = new Client();
    $host = \Drupal::request()->getSchemeAndHttpHost();
    $request_options[RequestOptions::HEADERS]['Accept'] = 'application/vnd.api+json';

    $request = (new Request('GET', $host . '/web/jsonapi/todochecklist'));
    $response = $http_client->send($request);
    $body = Json::decode($response->getBody()->getContents());
    ksm($body);

    return $options;
  }

}
