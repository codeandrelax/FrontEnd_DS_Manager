<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Upravljanje uređajima</title>
  <link rel="stylesheet" href="/public/resources/css/uprav.uredjajima.css">
</head>



<body>
  <!-- Dugme Nazad -->
  <div class="container-fluid mt-3">
    <a href="/administrators" class="btn btn-outline-light">
      &larr; Back to Administrators
    </a>
  </div>

  <!-- Dropdown za Kategorije / Filtere -->
  <div class="category-dropdown">
    <div class="dropdown">
      <button
        class="btn btn-outline-light dropdown-toggle"
        type="button"
        id="categoryDropdown"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false">
        <i class="bi bi-grid me-1"></i> Kategorije
      </button>
      <ul
        class="dropdown-menu dropdown-menu-end p-2"
        aria-labelledby="categoryDropdown"
        style="min-width: 200px; background-color: white;">
        <li class="mb-1">
          <button class="btn btn-sm w-100 filter-btn" style="background-color: #CCCCCC; color: #000;">
            Active
          </button>
        </li>
        <li class="mb-1">
          <button class="btn btn-sm w-100 filter-btn" style="background-color: #CCCCCC; color: #000;">
            Exclusive
          </button>
        </li>
        <li class="mb-1">
          <button class="btn btn-sm w-100 filter-btn" style="background-color: #CCCCCC; color: #000;">
            Popular
          </button>
        </li>
        <li class="mb-1">
          <button class="btn btn-sm w-100 filter-btn" style="background-color: #CCCCCC; color: #000;">
            Recommended
          </button>
        </li>
      </ul>
    </div>
  </div>

  <!-- Naslov stranice -->
  <div class="container mt-3">
    <h2 class="mb-4">Upravljanje uređajima</h2>
  </div>

  <!-- Grid uređaja -->
  <div class="container device-grid">
    <!-- Uređaj 1 -->
    <div
      class="device-item"
      data-bs-toggle="modal"
      data-bs-target="#deviceModal"
      data-device-img="/public/resources/images/kafic1.avif"
      data-device-name="Uređaj 1"
      data-device-desc="Lorem ipsum dolor sit amet, consectetur adipisicing elit."
      data-device-discount="60%"
      data-device-star="true">
      <div class="device-box">
        <img src="/public/resources/images/kafic1.avif" alt="Uređaj 1" />
        <div class="star-icon">★</div>
        <div class="badge-discount">60%</div>
      </div>
      <p class="device-name">Uređaj 1</p>
    </div>
    <!-- Uređaj 2 -->
    <div
      class="device-item"
      data-bs-toggle="modal"
      data-bs-target="#deviceModal"
      data-device-img="/public/resources/images/kafic2.avif"
      data-device-name="Uređaj 2"
      data-device-desc="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus iste."
      data-device-discount="40%"
      data-device-star="false">
      <div class="device-box">
        <img src="/public/resources/images/kafic2.avif" alt="Uređaj 2" />
        <div class="badge-discount">40%</div>
      </div>
      <p class="device-name">Uređaj 2</p>
    </div>
    <!-- Uređaj 3 -->
    <div
      class="device-item"
      data-bs-toggle="modal"
      data-bs-target="#deviceModal"
      data-device-img="/public/resources/images/kafic3.avif"
      data-device-name="Uređaj 3"
      data-device-desc="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, asperiores?"
      data-device-discount="2%"
      data-device-star="false">
      <div class="device-box">
        <img src="/public/resources/images/kafic3.avif" alt="Uređaj 3" />
        <div class="badge-discount">2%</div>
      </div>
      <p class="device-name">Uređaj 3</p>
    </div>
    <!-- Uređaj 4 -->
    <div
      class="device-item"
      data-bs-toggle="modal"
      data-bs-target="#deviceModal"
      data-device-img="/public/resources/images/kafic4.avif"
      data-device-name="Uređaj 4"
      data-device-desc="Lorem ipsum dolor sit amet. Ad, voluptatibus atque? Odit, officiis?"
      data-device-discount="94%"
      data-device-star="false">
      <div class="device-box">
        <img src="/public/resources/images/kafic4.avif" alt="Uređaj 4" />
        <div class="badge-discount">94%</div>
      </div>
      <p class="device-name">Uređaj 4</p>
    </div>
    <!-- Ostali uređaji po želji -->
  </div>

  <!-- Device Modal (Detalji uređaja) -->
  <div
    class="modal fade"
    id="deviceModal"
    tabindex="-1"
    aria-labelledby="deviceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Header modala -->
        <div class="modal-header">
          <h5 class="modal-title" id="deviceModalLabel">Detalji uređaja</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- Body modala -->
        <div class="modal-body">
          <div class="text-center mb-3">
            <img
              id="deviceModalImg"
              src=""
              alt="Device Image"
              style="width: 220px; height: 220px; object-fit: cover; border-radius: 8px;" />
          </div>
          <h5 id="deviceModalName" class="text-center mb-3"></h5>
          <div id="deviceModalDesc" class="mb-3" style="white-space: pre-wrap;"></div>
        </div>
        <!-- Footer modala -->
        <div class="modal-footer d-flex justify-content-between align-items-center w-100">
          <div class="form-check form-switch d-flex align-items-center">
            <input class="form-check-input" type="checkbox" role="switch" id="blokirajSwitch" />
            <label class="form-check-label ms-2" for="blokirajSwitch">Blokiraj uređaj</label>
          </div>
          <div>
            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
              Nazad
            </button>
            <button type="button" class="btn btn-primary" id="listaReklamaBtn">
              Lista Reklama
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Advertisement Modal (Lista Reklama) -->
  <div
    class="modal fade"
    id="reklamaModal"
    tabindex="-1"
    aria-labelledby="reklamaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Header modala -->
        <div class="modal-header">
          <h5 class="modal-title" id="reklamaModalLabel">
            Lista reklama za <span id="nazivUredjajaHolder"></span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- Body modala -->
        <div class="modal-body">
          <ul class="list-group" id="reklamaList">
            <li class="list-group-item">
              Heineken - 2 min na 15 min 21.6.2025 do 27.8.2025
            </li>
            <li class="list-group-item">
              Tuborg - 2 min na 15 min 21.6.2025 do 27.8.2025
            </li>
            <li class="list-group-item">
              Jelen - 2 min na 15 min 21.6.2025 do 27.8.2025
            </li>
          </ul>
        </div>
        <!-- Footer modala -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="reklamaNazadBtn" data-bs-dismiss="modal">
            Nazad
          </button>
          <button type="button" class="btn btn-primary" id="dodajReklamaBtn">
            Dodaj Reklamu
          </button>
          <button type="button" class="btn btn-danger" id="ukloniIzabraneBtn">
            Ukloni Izabrane
          </button>
        </div>
      </div>
    </div>
  </div>


  <!-- Skripta za dinamičko upravljanje modala -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Kategorije functionality
      document.querySelectorAll('.filter-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
          if (!btn.classList.contains('active')) {
            btn.classList.add('active');
            btn.style.backgroundColor = "#696969";
            btn.style.color = "#fff";
          } else {
            btn.classList.remove('active');
            btn.style.backgroundColor = "#CCCCCC";
            btn.style.color = "#000";
          }
        });
      });

      // Device modal initialization and handling
      const deviceModal = document.getElementById('deviceModal');
      deviceModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (!button) return;
        const deviceImg = button.getAttribute('data-device-img');
        const deviceName = button.getAttribute('data-device-name');
        const deviceDesc = button.getAttribute('data-device-desc');
        document.getElementById('deviceModalImg').src = deviceImg || '';
        document.getElementById('deviceModalName').textContent = deviceName || '';
        document.getElementById('deviceModalDesc').textContent = deviceDesc || '';
      });

      // Initialize Bootstrap modals
      const deviceModalInstance = new bootstrap.Modal(document.getElementById('deviceModal'));
      const reklamaModalInstance = new bootstrap.Modal(document.getElementById('reklamaModal'));

      // Handle transition between modals
      document.getElementById('listaReklamaBtn').addEventListener('click', function() {
        const deviceName = document.getElementById('deviceModalName').textContent;
        document.getElementById('nazivUredjajaHolder').textContent = deviceName;
        deviceModalInstance.hide();
        reklamaModalInstance.show();
      });

      // Return to device modal when advertisement modal is closed
      document.getElementById('reklamaModal').addEventListener('hidden.bs.modal', function() {
        deviceModalInstance.show();
      });

      // Handle reklama list item selection
      document.querySelectorAll('#reklamaList .list-group-item').forEach(function(item) {
        item.addEventListener('click', function() {
          item.classList.toggle('selected');
        });
      });

      // Handle "Dodaj Reklamu" button
      document.getElementById('dodajReklamaBtn').addEventListener('click', function() {
        alert('Dodaj Reklamu clicked');
      });

      // Handle "Ukloni Izabrane" button
      document.getElementById('ukloniIzabraneBtn').addEventListener('click', function() {
        document.querySelectorAll('#reklamaList .list-group-item.selected').forEach(function(item) {
          item.remove();
        });
      });

      // Handle "Nazad" button on reklama modal
      document.getElementById('reklamaNazadBtn').addEventListener('click', function() {
        reklamaModalInstance.hide();
        deviceModalInstance.show();
      });

      // Handle device blocking toggle
      document.getElementById('blokirajSwitch').addEventListener('change', function() {
        // Add your blocking logic here
        console.log('Device blocking toggled:', this.checked);
      });
    });
  </script>

</body>

</html>