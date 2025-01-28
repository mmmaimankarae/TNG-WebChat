document.addEventListener("DOMContentLoaded", () => {
  /* Show and Hide Forgot Password Popup */
  const showForgotPopup = document.getElementById('showForgotPopup');
  const closeForgotPopup = document.getElementById('closeForgotPopup');
  const cancelForgotPopup = document.getElementById('cancelForgotPopup');
  const forgotPopup = document.getElementById('forgotPopup');

  if (showForgotPopup) {
    showForgotPopup.addEventListener('click', () => {
      forgotPopup.classList.remove('hidden');
    });

    closeForgotPopup.addEventListener('click', () => {
      forgotPopup.classList.add('hidden');
      clearErrors();
    });

    cancelForgotPopup.addEventListener('click', () => {
      forgotPopup.classList.add('hidden');
      clearErrors();
    });
  }

  /* Show and Hide Reset Password Popup */
  const showResetPassPopup = document.getElementById('showResetPassPopup');
  const closeResetPopup = document.getElementById('closeResetPopup');
  const cancelResetPopup = document.getElementById('cancelResetPopup');
  const resetPopup = document.getElementById('resetPopup');
  const passError = document.getElementById('passError');
  const oldPassError = document.getElementById('oldPassError');
  
  if (showResetPassPopup) {
    showResetPassPopup.addEventListener('click', () => {
      const pass = document.getElementById('password');
      if (pass.value.trim() === '') {
        passError.classList.remove('hidden');
      } else {
        resetPopup.classList.remove('hidden');
        if (passError.classList.contains('hidden')) {
          passError.classList.add('hidden');
        }
        const newPass = document.getElementById('newPass');
        newPass.value = pass.value.trim();
      }
    });

    closeResetPopup.addEventListener('click', () => {
      resetPopup.classList.add('hidden');
      clearErrors();
    });

    cancelResetPopup.addEventListener('click', () => {
      resetPopup.classList.add('hidden');
      clearErrors();
    });

    if (oldPassError) {
      resetPopup.classList.remove('hidden');
    } else {
      resetPopup.classList.add('hidden');
    }
  }

  /* Clear Error on Popup when it closed */
  function clearErrors() {
    const errorElements = ['successResetError', 'forgotAccError', 'oldPassError'];
    errorElements.forEach(function(id) {
      const element = document.getElementById(id);
      if (element) {
        element.textContent = '';
      }
    });
  }
});
