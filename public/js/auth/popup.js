document.addEventListener("DOMContentLoaded", () => {
  /* Show and Hide Forgot Password Popup */
  const showForgotPopup = document.getElementById('showForgotPopup');
  const closeForgotPopup = document.getElementById('closeForgotPopup');
  const cancelForgotPopup = document.getElementById('cancelForgotPopup');
  const forgotPopup = document.getElementById('forgotPopup');

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

  /* Show and Hide Reset Password Popup */
  const showResetPopup = document.getElementById('showResetPopup');
  const closeResetPopup = document.getElementById('closeResetPopup');
  const cancelResetPopup = document.getElementById('cancelResetPopup');
  const resetPopup = document.getElementById('resetPopup');

  showResetPopup.addEventListener('click', () => {
    resetPopup.classList.remove('hidden');
  });

  closeResetPopup.addEventListener('click', () => {
    resetPopup.classList.add('hidden');
    clearErrors();
  });

  cancelResetPopup.addEventListener('click', () => {
    resetPopup.classList.add('hidden');
    clearErrors();
  });

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
