<?php

namespace Drupal\todo_checklist\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
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

    $options = $this->getOptions();
    $form['todo_wrapper'] = [
      '#type' => 'fieldset',
      '#open' => TRUE,
      '#collapsible' => FALSE,
      '#title' => '',
    ];

    if (!$options) {
      $form['todo_wrapper']['markup'] = [
        '#markup' => $this->t('At least one ToDoChecklist entity needs to be created.'),
      ];
    }
    else {
      // Checkbox for checking/unchecking all options.
      $form['todo_wrapper']['master_on_off'] = [
        '#type' => 'checkbox',
      ];

      // Textfield.
      $form['todo_wrapper']['title'] = [
        '#type' => 'textfield',
        '#attributes' => [
          'placeholder' => $this->t('What needs to be done?'),
        ],
        '#size' => 60,
        '#maxlength' => 128,
      ];

      // CheckBoxes, ToDoChecklist options.
      $form['todo_wrapper']['todo_checkboxes'] = [
        '#type' => 'checkboxes',
        '#options' => $options[0],
      ];

      $form['todo_wrapper']['actions'] = [
        '#type' => 'actions',
      ];

      // Add a submit button
      $form['todo_wrapper']['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ];

      // Add a reset button
      $form['todo_wrapper']['actions']['reset'] = [
        '#type' => 'button',
        '#button_type' => 'reset',
        '#value' => $this->t('Reset'),
        '#attributes' => [
          'onclick' => 'this.form.reset(); return false;',
        ],
      ];
    }
    $form['#theme'] = 'todo_app_form';

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
    $options = [];
    $http_client = new Client();
    $host = \Drupal::request()->getSchemeAndHttpHost();
    // Get session token.
    $request = (new Request('GET', $host . '/web/session/token'));
    $response = $http_client->send($request);
    $token = $response->getBody()->getContents();
    if ($token) {
      // Get ToDoChecklist entities json data.
      $request_options[RequestOptions::HEADERS] = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'X-CSRF-Token' => $token,
      ];
      $request = (new Request('GET', $host . '/web/jsonapi/todochecklist', $request_options));
      $response = $http_client->send($request);
      $body = Json::decode($response->getBody()->getContents());
      if ($body['data']) {
        foreach ($body['data'] as $checklist) {
          $options[] = $checklist['attributes']['options'];
        }
      }
    }

    return $options;
  }
}
