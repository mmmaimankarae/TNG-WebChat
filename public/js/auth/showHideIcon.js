/* Show and Hide Password */
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const showIcon = document.getElementById('showPass');
    const hideIcon = document.getElementById('hidePass');
  
    if (showIcon && hideIcon) {
      showIcon.addEventListener('click', () => {
        passwordInput.setAttribute('type', 'text');
        showIcon.style.display = 'none';
        hideIcon.style.display = 'block';
      });
  
      hideIcon.addEventListener('mouseout', () => {
        passwordInput.setAttribute('type', 'password');
        showIcon.style.display = 'block';
        hideIcon.style.display = 'none';
      });
    }
});