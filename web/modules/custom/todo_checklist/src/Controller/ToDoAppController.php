<?php
/**
 * @file
 * Contains \Drupal\test_twig\Controller\TestTwigController.
 */

namespace Drupal\todo_checklist\Controller;

use Drupal\Core\Controller\ControllerBase;

class ToDoAppController extends ControllerBase {
  public function content() {

    $todo_app_form = \Drupal::formBuilder()->getForm('Drupal\todo_checklist\Form\ToDoAppForm');
    return [
      '#theme' => 'todo_app',
      '#form' => $todo_app_form,
    ];

  }
}
