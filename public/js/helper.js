// =================
// Function for appending alert messages

export const ALERT_TYPE = {
  danger: 0,
  info: 1,
  warning: 2,
};

export function appendAlertMessage(alertMessage, type) {
  const alertsContainer = document.querySelector('#alertsContainer');

  if (!!alertsContainer) {
    let alertTypeClass = '';
    switch (type) {
      case ALERT_TYPE.danger:
        alertTypeClass = 'alert-danger';
        break;
      case ALERT_TYPE.info:
        alertTypeClass = 'alert-info';
        break;
      default: // ALERT_TYPE.warning
        alertTypeClass = 'alert-warning';
        break;
    }
    const alert = document.createElement('div');

    alert.classList.add('col-lg-4', 'px-3', 'pb-3');
    alert.innerHTML = `<div class="alert ${alertTypeClass} text-center mb-0" role="alert">${alertMessage}</div>`;

    alertsContainer.appendChild(alert);
  }
}

// =================
export function setValid(target) {
  target.classList.remove('is-invalid');
  target.classList.add('is-valid');
  target.setCustomValidity('');
}

export function setInvalid(target) {
  target.classList.remove('is-valid');
  target.classList.add('is-invalid');
  target.setCustomValidity('Invalid field');
}
