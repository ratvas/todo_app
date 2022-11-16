/**
 * @file
 * JavaScript behaviors for ToDoApp form.
 */

(function($, Drupal) {
  "use strict";

  /**
   * Attach handlers to ToDoApp form.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.toDoApp = {
    attach: function (context, settings) {

      console.log("mile");
      $('#edit-master-on-off').click(function() {
        var checked = $(this).prop('checked');
        $('#edit-todo-checkboxes').find('input:checkbox').prop('checked', checked);
      });

    }
  };
})(jQuery, Drupal, drupalSettings);
