(function () {
  "use strict";

  let forms = document.querySelectorAll('.php-email-form');

  forms.forEach(function (e) {
    e.addEventListener('submit', function (event) {
      event.preventDefault();

      let thisForm = this;
      let action = thisForm.getAttribute('action');
      let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');

      // Ensure action attribute exists
      if (!action) {
        displayError(thisForm, 'The form action property is not set!');
        return;
      }

      // Select loading, error message, and sent message elements (if they exist)
      const loadingElement = thisForm.querySelector('.loading');
      const errorMessageElement = thisForm.querySelector('.error-message');
      const sentMessageElement = thisForm.querySelector('.sent-message');

      // Show loading indicator
      if (loadingElement) {
        loadingElement.classList.add('d-block');
      }
      // Hide error and sent messages
      if (errorMessageElement) {
        errorMessageElement.classList.remove('d-block');
      }
      if (sentMessageElement) {
        sentMessageElement.classList.remove('d-block');
      }

      let formData = new FormData(thisForm);

      // If reCaptcha is present, handle it
      if (recaptcha) {
        if (typeof grecaptcha !== "undefined") {
          grecaptcha.ready(function () {
            try {
              grecaptcha.execute(recaptcha, { action: 'php_email_form_submit' })
                .then(token => {
                  formData.set('recaptcha-response', token);
                  php_email_form_submit(thisForm, action, formData);
                });
            } catch (error) {
              displayError(thisForm, error);
            }
          });
        } else {
          displayError(thisForm, 'The reCaptcha javascript API url is not loaded!');
        }
      } else {
        // Proceed with form submission if no reCaptcha
        php_email_form_submit(thisForm, action, formData);
      }
    });
  });

  function php_email_form_submit(thisForm, action, formData) {
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(response => {
        if (response.ok) {
          return response.text();
        } else {
          throw new Error(`${response.status} ${response.statusText} ${response.url}`);
        }
      })
      .then(data => {
        // Hide loading indicator
        const loadingElement = thisForm.querySelector('.loading');
        const sentMessageElement = thisForm.querySelector('.sent-message');

        if (loadingElement) {
          loadingElement.classList.remove('d-block');
        }

        if (data.trim() === 'OK') {
          // Show sent message
          if (sentMessageElement) {
            sentMessageElement.classList.add('d-block');
          }
          thisForm.reset(); // Reset form after submission
        } else {
          throw new Error(data ? data : 'Form submission failed and no error message returned from: ' + action);
        }
      })
      .catch((error) => {
        displayError(thisForm, error);
      });
  }

  function displayError(thisForm, error) {
    // Hide loading indicator
    const loadingElement = thisForm.querySelector('.loading');
    const errorMessageElement = thisForm.querySelector('.error-message');

    if (loadingElement) {
      loadingElement.classList.remove('d-block');
    }

    // Display error message
    if (errorMessageElement) {
      errorMessageElement.innerHTML = error;
      errorMessageElement.classList.add('d-block');
    }
  }

})();
