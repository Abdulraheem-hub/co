$(document).ready(function () {
  let phoneInput = $('input[name="phone"]');

  // UK Phone number regex (accepts +44 7XXX XXXXXX or 07XXX XXXXXX)
  const ukPhoneRegex = /^(?:(?:\+44\s?|0)(?:7\d{3}|\d{4})\s?\d{6})$/;

  // Form validation on submit
  $("form#customer-form").on("submit", function (e) {
    // Remove any existing error messages
    $(".phone-error-msg").remove();

    let phoneNumber = phoneInput.val().trim();

    if (!ukPhoneRegex.test(phoneNumber)) {
      e.preventDefault();

      // Add error message
      phoneInput.after(
        '<div class="phone-error-msg alert alert-danger">Please enter a valid UK phone number (e.g., +44 7911 123456 or 07911 123456)</div>'
      );

      return false;
    }

    return true;
  });
});
