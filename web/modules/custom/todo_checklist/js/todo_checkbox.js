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

      var message = "nesto";
      window.alert(message);
      console.log("mile");

    }
  };
})(jQuery, Drupal, drupalSettings);
