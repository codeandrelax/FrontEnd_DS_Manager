let deleteMode = false;
let currentAdImage = "";

document.addEventListener("DOMContentLoaded", function () {
  setupAdClickHandlers();

  // Dodajemo listener za back dugme
  document.querySelector(".back-button").addEventListener("click", () => {
    history.pushState(
      "",
      document.title,
      window.location.pathname + window.location.search
    );
    showSection("adListSection");
  });

  document.getElementById("addAdButton").addEventListener("click", addAd);
  document
    .getElementById("removeAdButton")
    .addEventListener("click", toggleDeleteMode);
  document
    .getElementById("addDisplejButton")
    .addEventListener("click", function () {
      document.getElementById("clickedAdImage").src = currentAdImage;
      const modalEl = document.getElementById("addDisplejModal");
      const modal = new bootstrap.Modal(modalEl, {
        backdrop: "static",
        keyboard: false,
      });
      modal.show();
      flatpickr("#dateRange", {
        mode: "range",
        inline: true,
        dateFormat: "d-m-Y",
      });
    });

  document
    .getElementById("removeDisplejButton")
    .addEventListener("click", function () {
      alert("Funkcionalnost brisanja displeja nije implementirana.");
    });

  const timeSlider = document.getElementById("timeSlider");
  const sliderValue = document.getElementById("sliderValue");
  timeSlider.addEventListener("input", function () {
    sliderValue.textContent = timeSlider.value;
  });

  // Dodajemo provjeru hash-a pri učitavanju
  checkUrlHash();

  // Dodajemo listener za promjene hash-a
  window.addEventListener("hashchange", checkUrlHash);
});

function checkUrlHash() {
  const hash = window.location.hash;
  if (hash.startsWith("#ad-")) {
    const adId = hash.replace("#ad-", "");
    const adElement = document.querySelector(`[data-ad-id="${adId}"]`);
    if (adElement) {
      const adTitle = adElement.getAttribute("data-ad-title");
      const adImage = adElement.getAttribute("data-ad-image");
      currentAdImage = adImage;
      document.getElementById("adDetailsHeader").textContent =
        "Detalji Reklame #" + adId;
      setupDisplejGrid();
      showSection("adDetailsSection");
    }
  }
}

function setupAdClickHandlers() {
  document.querySelectorAll(".ad-item").forEach((ad) => {
    ad.addEventListener("click", function (e) {
      if (deleteMode) return;
      if (e.target.tagName.toLowerCase() === "input") return;
      const adId = ad.getAttribute("data-ad-id");
      const adTitle = ad.getAttribute("data-ad-title");
      const adImage = ad.getAttribute("data-ad-image");
      currentAdImage = adImage;
      showAdDetails(adId, adTitle, adImage);
    });
  });
}

function showAdDetails(adId, adTitle, adImage) {
  document.getElementById("adDetailsHeader").textContent =
    "Detalji Reklame #" + adId;
  setupDisplejGrid();
  showSection("adDetailsSection");
  // Dodajemo hash u URL
  window.location.hash = `ad-${adId}`;
}

function setupDisplejGrid() {
  const grid = document.getElementById("displejGrid");
  grid.innerHTML = "";
  for (let i = 1; i <= 20; i++) {
    const displej = document.createElement("div");
    displej.className = "displej-item";
    const img = document.createElement("img");
    img.src = `https://picsum.photos/100?random=${i}`;
    img.alt = `Displej ${i}`;
    const label = document.createElement("span");
    label.textContent = `Displej ${i}`;
    displej.appendChild(img);
    displej.appendChild(label);
    grid.appendChild(displej);
  }
}

function showSection(sectionId) {
  document.querySelectorAll(".content-section").forEach((section) => {
    section.classList.remove("active");
  });
  document.getElementById(sectionId).classList.add("active");
}

function addAd() {
  alert("Funkcionalnost dodavanja reklame nije implementirana.");
}

function toggleDeleteMode() {
  const removeBtn = document.getElementById("removeAdButton");
  const adItems = document.querySelectorAll(".ad-item");
  if (!deleteMode) {
    deleteMode = true;
    removeBtn.textContent = "Završi Brisanje";
    adItems.forEach((ad) => {
      const checkbox = ad.querySelector(".delete-checkbox");
      if (checkbox) {
        checkbox.style.display = "block";
      }
    });
  } else {
    adItems.forEach((ad) => {
      const checkbox = ad.querySelector(".delete-checkbox");
      if (checkbox && checkbox.checked) {
        ad.remove();
      } else if (checkbox) {
        checkbox.checked = false;
        checkbox.style.display = "none";
      }
    });
    deleteMode = false;
    removeBtn.textContent = "Ukloni reklamu";
  }
}
