<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/public/resources/css/reg.uredjaja.css">

</head>

<body>
  <!-- Prvi kontejner: samo dugme Nazad -->
  <div class="container-fluid mt-3">
    <a href="/administrators" class="btn btn-outline-light">
      &larr; Back to Administrators
    </a>
  </div>

  <!-- Drugi kontejner: Forma -->
  <div class="container main-container mx-auto mb-5">
    <h2 class="mb-4">Osnovni podaci uređaja</h2>
    <form>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Naziv uređaja</label>
          <input
            type="text"
            class="form-control"
            placeholder="Unesite naziv uređaja" />
        </div>

        <div class="col-md-6">
          <label class="form-label">MAC Adresa</label>
          <input
            type="text"
            class="form-control"
            placeholder="Unesite MAC adresu" />
        </div>

        <div class="col-md-6">
          <label class="form-label">Opis lokacije</label>
          <input
            type="text"
            class="form-control"
            placeholder="Unesite opis lokacije" />
        </div>

        <div class="col-md-6">
          <label class="form-label">Veličina displeja</label>
          <input
            type="text"
            class="form-control"
            placeholder="Unesite veličinu displeja" />
        </div>

        <div class="col-12">
          <label class="form-label d-block">Slika uređaja</label>
          <button type="button" class="btn btn-secondary">
            <i class="bi bi-camera"></i> Dodaj sliku
          </button>
        </div>

        <div class="col-12">
          <label class="form-label">Geo Lokacija uređaja</label>
          <div id="map"></div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Latitude</label>
          <input
            type="text"
            id="latitude"
            class="form-control"
            placeholder="Latitude"
            readonly />
        </div>

        <div class="col-md-6">
          <label class="form-label">Longitude</label>
          <input
            type="text"
            id="longitude"
            class="form-control"
            placeholder="Longitude"
            readonly />
        </div>
      </div>

      <div class="mt-4">
        <!-- 
          Koristi data-bs-toggle i data-bs-target da otvori modal
          umesto type="submit", stavljamo type="button" da ne šalje formu 
          (ili, ako želiš prvo validaciju, moraš to naknadno podesiti).
        -->
        <button
          type="button"
          class="btn btn-primary w-100"
          data-bs-toggle="modal"
          data-bs-target="#restrictionsModal">
          <i class="bi bi-plus-circle"></i> Registruj uređaj
        </button>
      </div>
    </form>
  </div>

  <!-- ========== MODAL ========== -->
  <div
    class="modal fade"
    id="restrictionsModal"
    tabindex="-1"
    aria-labelledby="restrictionsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="restrictionsModalLabel">Ograničenja</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <!-- Tri početna input polja -->
          <div id="restrictionsContainer">
            <div class="mb-3">
              <input
                type="text"
                class="form-control"
                placeholder="Dodaj ograničenje" />
            </div>
            <div class="mb-3">
              <input
                type="text"
                class="form-control"
                placeholder="Dodaj ograničenje" />
            </div>
            <div class="mb-3">
              <input
                type="text"
                class="form-control"
                placeholder="Dodaj ograničenje" />
            </div>
          </div>

          <!-- Dugme za dodavanje novih input polja -->
          <button
            type="button"
            class="btn btn-secondary mb-2"
            id="btnAddRestriction">
            Dodaj još ograničenje
          </button>

          <!-- Poruka kada se dostigne max broj polja -->
          <p id="maxLimitMsg" class="text-danger d-none">
            Dostigli ste maksimalan broj ograničenja (25).
          </p>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-light"
            data-bs-dismiss="modal">
            Nazad
          </button>
          <button type="button" class="btn btn-primary" id="btnConfirm">
            Potvrdi registraciju
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- ========== END MODAL ========== -->

  <!-- Google Maps Script -->
  <script>
    function initMap() {
      const defaultLocation = {
        lat: 44.7866,
        lng: 20.4489
      }; // Beograd
      const map = new google.maps.Map(document.getElementById("map"), {
        center: defaultLocation,
        zoom: 12,
      });

      let marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
      });

      // Ažuriranje polja sa koordinatama kada se marker pomera
      function updateLatLng(lat, lng) {
        document.getElementById("latitude").value = lat.toFixed(6);
        document.getElementById("longitude").value = lng.toFixed(6);
      }

      updateLatLng(defaultLocation.lat, defaultLocation.lng);

      google.maps.event.addListener(map, "click", function(event) {
        marker.setPosition(event.latLng);
        updateLatLng(event.latLng.lat(), event.latLng.lng());
      });

      google.maps.event.addListener(marker, "dragend", function(event) {
        updateLatLng(event.latLng.lat(), event.latLng.lng());
      });
    }
  </script>

  <!-- Google Maps API -->
  <script
    src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap"
    async
    defer></script>

  <!-- Skripta za rad sa modalom i dodavanjem polja -->
  <script>
    const restrictionsContainer = document.getElementById("restrictionsContainer");
    const btnAddRestriction = document.getElementById("btnAddRestriction");
    const maxLimitMsg = document.getElementById("maxLimitMsg");
    const btnConfirm = document.getElementById("btnConfirm");

    let restrictionsCount = 3; // već imamo 3 polja

    // Funkcija za dodavanje novog input polja
    btnAddRestriction.addEventListener("click", () => {
      if (restrictionsCount < 25) {
        const wrapper = document.createElement("div");
        wrapper.classList.add("mb-3");

        const newInput = document.createElement("input");
        newInput.type = "text";
        newInput.classList.add("form-control");
        newInput.placeholder = "Dodaj ograničenje";

        wrapper.appendChild(newInput);
        restrictionsContainer.appendChild(wrapper);

        restrictionsCount++;
      } else {
        // Ako je dostignut max, prikazujemo poruku
        maxLimitMsg.classList.remove("d-none");
      }
    });

    // Dugme "Potvrdi" - ovde možeš da uradiš nešto sa ograničenjima
    btnConfirm.addEventListener("click", () => {
      // Ovde možeš da dohvatiš vrednosti svih input polja, npr.:
      const inputs = restrictionsContainer.querySelectorAll("input");
      let allRestrictions = [];
      inputs.forEach((inp) => {
        if (inp.value.trim() !== "") {
          allRestrictions.push(inp.value.trim());
        }
      });

      console.log("Ograničenja:", allRestrictions);

      // Možeš da zatvoriš modal
      const modalElement = document.getElementById("restrictionsModal");
      const modalBootstrap = bootstrap.Modal.getInstance(modalElement);
      modalBootstrap.hide();

      // Po potrebi, izvrši dodatnu logiku (čuvanje u bazi, slanje preko AJAX i slično).
    });
  </script>
</body>

</html>