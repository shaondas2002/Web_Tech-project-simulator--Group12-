document.addEventListener("DOMContentLoaded", () => {
  
  const signupForm = document.querySelector('form[action*="SignUpValidation.php"]');
  if (signupForm) {
    const email = signupForm.querySelector('input[name="email"]');
    const pass  = signupForm.querySelector('input[name="password"]');
    const btn   = signupForm.querySelector('button[type="submit"]');

    const passMsg = document.createElement("small");
    passMsg.style.display = "block";
    passMsg.style.marginTop = "6px";
    passMsg.style.color = "#444";
    pass.parentNode.insertBefore(passMsg, pass.nextSibling);

    const emailMsg = document.createElement("small");
    emailMsg.style.display = "block";
    emailMsg.style.marginTop = "6px";
    emailMsg.style.color = "#444";
    email.parentNode.insertBefore(emailMsg, email.nextSibling);

    const toggleWrap = document.createElement("label");
    toggleWrap.style.display = "block";
    toggleWrap.style.marginTop = "8px";
    toggleWrap.style.fontSize = "14px";
    const toggle = document.createElement("input");
    toggle.type = "checkbox";
    toggle.style.marginRight = "6px";
    toggleWrap.appendChild(toggle);
    toggleWrap.appendChild(document.createTextNode("Show password"));
    pass.parentNode.insertBefore(toggleWrap, passMsg);

    toggle.addEventListener("change", () => {
      pass.type = toggle.checked ? "text" : "password";
    });

    let emailAvailable = true;
    let t = null;

    async function checkEmailAvailability(value) {
      emailMsg.textContent = "Checking email...";
      try {
        const res = await fetch(`../Controller/check_email.php?email=${encodeURIComponent(value)}`);
        const data = await res.json();
        if (!data.ok) {
          emailAvailable = false;
          emailMsg.textContent = data.message || "Invalid email";
          emailMsg.style.color = "#b00020";
        } else if (!data.available) {
          emailAvailable = false;
          emailMsg.textContent = "Email already exists";
          emailMsg.style.color = "#b00020";
        } else {
          emailAvailable = true;
          emailMsg.textContent = "Email available";
          emailMsg.style.color = "#1a7f37";
        }
      } catch (e) {
        emailAvailable = true; 
        emailMsg.textContent = "";
      }
    }

    function validateSignup() {
      let ok = true;

      const e = email.value.trim();
      if (e.length > 0) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!pattern.test(e)) ok = false;
        if (!emailAvailable) ok = false;
      }

      if (pass.value.length > 0 && pass.value.length < 6) {
        ok = false;
        passMsg.textContent = "Password should be at least 6 characters.";
        passMsg.style.color = "#b00020";
      } else {
        passMsg.textContent = "";
      }

      if (btn) btn.disabled = !ok;
      return ok;
    }

    email.addEventListener("input", () => {
      emailAvailable = true;
      emailMsg.textContent = "";
      clearTimeout(t);
      const v = email.value.trim();
      if (v.length === 0) {
        validateSignup();
        return;
      }
      t = setTimeout(() => {
        checkEmailAvailability(v).then(validateSignup);
      }, 350);
      validateSignup();
    });

    signupForm.addEventListener("input", validateSignup);
    signupForm.addEventListener("submit", (e) => {
      if (!validateSignup()) {
        e.preventDefault();
        alert("Please fix the form errors before submitting.");
      }
    });

    validateSignup();
  }

  const loginForm = document.querySelector('form[action*="LoginValidation.php"]');
  if (loginForm) {
    const pass = loginForm.querySelector('input[name="password"]');
    if (pass) {
      const toggleWrap = document.createElement("label");
      toggleWrap.style.display = "block";
      toggleWrap.style.marginTop = "8px";
      toggleWrap.style.fontSize = "14px";

      const toggle = document.createElement("input");
      toggle.type = "checkbox";
      toggle.style.marginRight = "6px";

      toggleWrap.appendChild(toggle);
      toggleWrap.appendChild(document.createTextNode("Show password"));
      pass.parentNode.insertBefore(toggleWrap, pass.nextSibling);

      toggle.addEventListener("change", () => {
        pass.type = toggle.checked ? "text" : "password";
      });
    }
  }

  document.querySelectorAll('input[data-qty-input="1"]').forEach((inp) => {
    inp.addEventListener('input', () => {
      const v = parseInt(inp.value || '1', 10);
      if (Number.isNaN(v) || v < 1) inp.value = 1;
    });
  });
});
