jQuery(function($) {'use strict',
  $(document).ready(function() {
      $('#recall-form').formValidation({
          framework: 'bootstrap',
          icon: {
              valid: 'glyphicon glyphicon-ok',
              invalid: 'glyphicon glyphicon-remove',
              validating: 'glyphicon glyphicon-refresh'
          },
          fields: {
            vin: {
              row: {
                  valid: 'has-success',
                  invalid: 'has-error'
              },
              validators: {
                notEmpty: {
                    message: 'The VIN field can not be left empty.'
                },
                stringLength: {
                    min: 17,
                    max: 17,
                    message: 'VIN numbers must be exactly 17 characters long.'
                },
                regexp: {
                    regexp: /^[a-zA-Z0-9_]+$/,
                    message: 'The VIN can only consist of alphabetical, and alphanumerical characters.'
                }
              }
            },
          }
      });
  });
});
