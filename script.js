// script.js
document.addEventListener('DOMContentLoaded', () => {
  const form  = document.getElementById('leadForm');
  const alert = document.createElement('p');
  alert.className =
    'mt-4 text-center font-semibold transition-opacity duration-300';
  form.parentNode.insertBefore(alert, form.nextSibling);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Simple front‑end validation
    const name  = form.name.value.trim();
    const email = form.email.value.trim();
    if (!name || !/^\S+@\S+\.\S+$/.test(email)) {
      showMsg('Please enter a valid name and email.', false);
      return;
    }

    try {
      const res = await fetch('process.php', {
        method: 'POST',
        body: new FormData(form),
      });
      const data = await res.json();

      if (data.success) {
        form.reset();
        showMsg(data.message, true);
      } else {
        throw new Error(data.message);
      }
    } catch (err) {
      showMsg(`Error: ${err.message}`, false);
    }
  });

  function showMsg(msg, ok) {
    alert.textContent = msg;
    alert.classList.toggle('text-green-600', ok);
    alert.classList.toggle('text-red-600', !ok);
    alert.classList.remove('opacity-0');
    setTimeout(() => alert.classList.add('opacity-0'), 5000);
  }
});
