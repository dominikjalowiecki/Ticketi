export function ajaxPostForm(
  form,
  url,
  idAppendContainer,
  appendBefore = false
) {
  const formData = new FormData(form);

  fetch(url, {
    method: 'POST',
    body: formData,
  })
    .then((response) => {
      if (!response.ok) throw new Error('Bad response from server');

      const appendContainer = document.querySelector(idAppendContainer);
      if (appendBefore)
        appendContainer.insertBefore(response.body, appendContainer.firstChild);
      else appendContainer.appendChild(response.body);
    })
    .catch((error) => {});
}

export function ajaxPostFormCustom(form, url, customCallback) {
  const formData = new FormData(form);

  fetch(url, {
    method: 'POST',
    body: formData,
  })
    .then((response) => {
      if (!response.ok) throw new Error('Bad response from server');

      customCallback(response);
    })
    .catch((error) => {});
}

// function redirectConfirm(text, url) {
//     if (confirm(text)) window.location.assign(url);
//   }
