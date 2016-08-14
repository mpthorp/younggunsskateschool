$(document).ready(function() {

  var doSubmit = function(values) {
    return $.ajax({
      type: "POST",
      url: "php/contact-us.php",
      data: values,
      cache: false
    });
  };

  var resetForm = function(){
    $('.sending').hide();
    $('#Submit').text('Send');
  };

  $('.validate').validate({
    submitHandler: function(form) {

      $('#Submit').text('Sending...');
      $('.sending').fadeIn('ease');

      var fields = ['TYPE', 'FNAME', 'EMAIL', 'PHONE', 'MESSAGE'];
      submitForm(form, fields, doSubmit, function(err) {
        if (!err) {
          $('.pre-submit').hide();
          $('.post-submit').fadeIn(200);
          resetForm();
        } else {
          console.log('Error - form not submitted');
        }
      });
    },

    errorLabelContainer: '.error-list',
    wrapper: 'li',
    errorClass: 'error-text',
    messages: {
      'FNAME': {
        minlength: 'Your first name must be at least 2 characters.'
      },
      'LNAME': {
        minlength: 'Your last name must be at least 2 characters.'
      },
      'EMAIL': {
        email: 'Your email address is not valid.'
      },
      'PHONE': {
        phoneINTL: 'Your phone number is not valid.'
      }
    },

    invalidHandler: function(event, validator) {
      validator.showErrors();
      $('.errors').show();
    }
  });
});
