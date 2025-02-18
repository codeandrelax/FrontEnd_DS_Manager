const clientImageUpload = document.getElementById("clientImageUpload");
const clientImagePreview = document.getElementById("clientImagePreview");
clientImageUpload.addEventListener("change", function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.addEventListener("load", function () {
      clientImagePreview.src = reader.result;
    });
    reader.readAsDataURL(file);
  }
});

//
const togglePassword = document.getElementById("togglePassword");
const clientPassword = document.getElementById("clientPassword");
togglePassword.addEventListener("click", function () {
  const type =
    clientPassword.getAttribute("type") === "password" ? "text" : "password";
  clientPassword.setAttribute("type", type);
  this.innerHTML =
    type === "password"
      ? '<i class="bi bi-eye"></i>'
      : '<i class="bi bi-eye-slash"></i>';
});

const togglePasswordRetype = document.getElementById("togglePasswordRetype");
const clientPasswordRetype = document.getElementById("clientPasswordRetype");
togglePasswordRetype.addEventListener("click", function () {
  const type =
    clientPasswordRetype.getAttribute("type") === "password"
      ? "text"
      : "password";
  clientPasswordRetype.setAttribute("type", type);
  this.innerHTML =
    type === "password"
      ? '<i class="bi bi-eye"></i>'
      : '<i class="bi bi-eye-slash"></i>';
});

function generatePassword() {
  const chars =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  const passwordLength = 10;
  let password = "";
  for (let i = 0; i < passwordLength; i++) {
    password += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return password;
}

const generatedPasswordElem = document.getElementById("generatedPassword");
const generateNewPasswordBtn = document.getElementById(
  "generateNewPasswordBtn"
);
const choosePasswordBtn = document.getElementById("choosePasswordBtn");
let currentGeneratedPassword = "";

// Kad se autogen modal otvori, uvek generiši novu lozinku
const autogenModal = document.getElementById("autogenModal");
autogenModal.addEventListener("shown.bs.modal", function () {
  currentGeneratedPassword = generatePassword();
  generatedPasswordElem.textContent = currentGeneratedPassword;
});

generateNewPasswordBtn.addEventListener("click", function () {
  currentGeneratedPassword = generatePassword();
  generatedPasswordElem.textContent = currentGeneratedPassword;
});

// Kad kliknemo "Izaberi", kopiramo u password polja i zatvaramo autogen modal,
// a zatim osiguravamo da se glavni modal ponovo prikaže.
choosePasswordBtn.addEventListener("click", function () {
  clientPassword.value = currentGeneratedPassword;
  clientPasswordRetype.value = currentGeneratedPassword;
  const autogenModalInstance = bootstrap.Modal.getInstance(autogenModal);
  autogenModalInstance.hide();
  // Nakon zatvaranja autogen modala, ponovo prikaži glavni modal
  setTimeout(function () {
    const mainModalEl = document.getElementById("addClientModal");
    if (!mainModalEl.classList.contains("show")) {
      const mainModalInstance = new bootstrap.Modal(mainModalEl);
      mainModalInstance.show();
    }
  }, 300);
});
