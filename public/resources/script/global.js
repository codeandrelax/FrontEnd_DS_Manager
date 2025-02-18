document.addEventListener("DOMContentLoaded", () => {
  const messageContainer = document.getElementById("message-container");

  if (messageContainer && messageContainer.textContent.trim() !== "") {
    // Postavi timer za brisanje sadržaja poruke
    setTimeout(() => {
      messageContainer.textContent = ""; // Očisti samo tekst poruke
    }, 5000); // Poruka nestaje nakon 5 sekundi
  }
});
