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

      $(document).ready(function () {
        // Display number of items left to check.
        calculateLeftItems();
        // Place button fo removing an option.
        $('#edit-todo-checkboxes input:checkbox').each( function() {
          $(this).parent().once().after("<button type='button' class='close-x'>x</button>");
        });

        // Remove an option.
        $('.close-x').click(function() {
          $(this).prev().remove();
          $(this).remove();
          calculateLeftItems();
        });
      });

      // Check/uncheck all checkboxes.
      $('#edit-master-on-off').click(function() {
        var checked = $(this).prop('checked');
        $('#edit-todo-checkboxes').find('input:checkbox').prop('checked', checked);
        calculateLeftItems();
      });

      // Display all, checked and unchecked.
      $("#edit-all").click(function(){
        $('#edit-todo-checkboxes input:checkbox').each( function() {
            $(this).parent().show();
            $(this).parent().next().show();
        });
        calculateLeftItems();
      });

      // Hide checked and leave active options only.
      $("#edit-active").click(function(){
        $('#edit-todo-checkboxes input:checkbox').each( function() {
          if (this.checked) {
            $(this).parent().hide();
            $(this).parent().next().hide();
          }
          else {
            $(this).parent().show();
            $(this).parent().next().show();
          }
        });
        calculateLeftItems();
      });

      // Hide unchecked/done and leave all unchecked.
      $("#edit-completed").click(function(){
        $('#edit-todo-checkboxes input:checkbox').each( function() {
          if (!this.checked) {
            if ($(this).parent().attr('id') !== 'edit-master-on-off') {
              $(this).parent().hide();
              $(this).parent().next().hide();
            }
          }
          else {
            $(this).parent().show();
            $(this).parent().next().show();
          }
        });
        calculateLeftItems();
      });

      // Hide checked and leave active options only.
      $("#edit-clear-completed").click(function(){
        $('#edit-todo-checkboxes input:checkbox').each( function() {
          if (this.checked) {
            $(this).parent().remove();
            $(this).parent().next().hide();
            $("#edit-clear-completed").hide();
          }
        });
        calculateLeftItems();
      });

      $("#edit-todo-checkboxes input:checkbox").change(function() {
        if(this.checked) {
          $("#edit-clear-completed").show();
        }
      });

      // Update number of items left to check.
      $("#edit-todo-checkboxes input:checkbox").change(function() {
        $("#edit-todo-checkboxes input:checkbox").each( function() {
          if (this.checked) {
            calculateLeftItems();
          }
        });
      });

      // Calculate items left to check.
      function calculateLeftItems() {
        var matches = document.querySelectorAll('#edit-todo-checkboxes input[type="checkbox"]:not(:checked)');
        if (matches.length === 1) {
          var items = 'item';
        } else {
          items = 'items';
        }
        $('.todo-items-left').html(matches.length + ' ' + items + ' left');
      }
    }
  };
})(jQuery, Drupal, drupalSettings);
