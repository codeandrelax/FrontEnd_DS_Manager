<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Upravljanje uređajima</title>

  <style>
    /* Osnovni stilovi – tamna tema */
    body {
      background-color: #181818;
      color: #e0e0e0;
      margin: 0;
      padding: 0;
    }

    .container-fluid.mt-3 a {
      margin: 16px;
    }

    /* GRID za uređaje: 10 stupaca */
    .device-grid {
      display: grid;
      grid-template-columns: repeat(10, 1fr);
      gap: 1rem;
      margin-top: 16px;
    }

    .device-item {
      text-align: center;
      cursor: pointer;
      position: relative;
    }

    .device-box {
      width: 95px;
      height: 95px;
      background-color: #2c2c2c;
      border-radius: 8px;
      margin: 0 auto;
      overflow: visible;
      position: relative;
    }

    .device-box img {
      width: 95px;
      height: 95px;
      object-fit: cover;
      border-radius: 8px;
      display: block;
    }

    .star-icon {
      position: absolute;
      top: 0;
      right: 0;
      transform: translate(50%, -50%);
      font-size: 1.3rem;
      color: gold;
    }

    .badge-discount {
      position: absolute;
      bottom: 0;
      right: 0;
      transform: translate(50%, 50%);
      background-color: #dc3545;
      color: #fff;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .device-name {
      margin-top: 8px;
      font-weight: 500;
      font-size: 0.95rem;
    }

    /* Modal – tamna tema */
    .modal-content {
      background-color: #2c2c2c;
      color: #e0e0e0;
      border-radius: 8px;
    }

    .modal-header,
    .modal-footer {
      border-color: #444;
    }

    .btn-close {
      filter: invert(1);
    }

    /* Dropdown za kategorije */
    .category-dropdown {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .dropdown-menu {
      z-index: 2000;
    }

    /* Advertisement Modal – stil liste reklama */
    #reklamaModal .list-group-item {
      background-color: #3a3a3a;
      color: #e0e0e0;
      border: 1px solid #555;
      margin-bottom: 5px;
      cursor: pointer;
    }

    #reklamaModal .list-group-item:hover {
      background-color: #555;
    }

    #reklamaModal .list-group-item.selected {
      background-color: #696969;
      color: #fff;
    }

    /* Ad Selection Modal – ista stilizacija kao Advertisement Modal */
    #availableAdList .list-group-item {
      background-color: #3a3a3a;
      color: #e0e0e0;
      border: 1px solid #555;
      margin-bottom: 5px;
      cursor: pointer;
    }

    #availableAdList .list-group-item:hover {
      background-color: #555;
    }

    #availableAdList .list-group-item.selected {
      background-color: #696969;
      color: #fff;
    }

    /* Dugmad u Nova Reklama Modal – standard outline dugmad */
    .advertisement-type .btn {
      border: 1px solid #e0e0e0;
    }

    /* Stil za overlay s grafom – povećan overlay */
    #graphOverlay {
      display: none;
      position: absolute;
      z-index: 2100;
      background-color: #2c2c2c;
      padding: 20px;
      border: 1px solid #444;
      border-radius: 8px;
      width: 400px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    }

    #graphOverlay h5 {
      margin-top: 0;
    }

    #graphOverlay p {
      margin: 4px 0;
    }

    #graphOverlay button {
      margin-top: 8px;
    }

    /* Dugme "Back to Administrators" */
    .container-fluid {
      padding: 16px;
    }

    .btn-back {
      color: #e0e0e0;
      border: 1px solid #e0e0e0;
      padding: 8px 12px;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-back:hover {
      background-color: #e0e0e0;
      color: #181818;
    }
  </style>
</head>

<body>
  <!-- Dropdown za kategorije / filtere -->
  <div class="category-dropdown">
    <div class="dropdown">
      <button class="btn btn-outline-light dropdown-toggle" type="button" id="categoryDropdown"
        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class="bi bi-grid me-1"></i> Kategorije
      </button>
      <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="categoryDropdown" style="min-width:200px; background-color:white;">
        <li class="mb-1"><button class="btn btn-sm w-100 filter-btn" style="background-color:#CCCCCC; color:#000;">Active</button></li>
        <li class="mb-1"><button class="btn btn-sm w-100 filter-btn" style="background-color:#CCCCCC; color:#000;">Exclusive</button></li>
        <li class="mb-1"><button class="btn btn-sm w-100 filter-btn" style="background-color:#CCCCCC; color:#000;">Popular</button></li>
        <li class="mb-1"><button class="btn btn-sm w-100 filter-btn" style="background-color:#CCCCCC; color:#000;">Recommended</button></li>
      </ul>
    </div>
  </div>

  <!-- Naslov stranice -->
  <div class="container mt-3">
    <h2 class="mb-4">Upravljanje uređajima</h2>
  </div>

  <!-- GRID uređaja -->
  <div class="container device-grid">
    <!-- Uređaj 1 -->
    <div class="device-item" data-device-img="/public/resources/images/kafic1.avif" data-device-name="Uređaj 1" data-device-location="Banja Luka" data-bs-toggle="modal" data-bs-target="#reklamaModal">
      <div class="device-box">
        <img src="/public/resources/images/kafic1.avif" alt="Uređaj 1" />
        <div class="star-icon">★</div>
        <div class="badge-discount">60%</div>
      </div>
      <p class="device-name">Uređaj 1</p>
    </div>
    <!-- Uređaj 2 -->
    <div class="device-item" data-device-img="/public/resources/images/kafic2.avif" data-device-name="Uređaj 2" data-device-location="Banja Luka" data-bs-toggle="modal" data-bs-target="#reklamaModal">
      <div class="device-box">
        <img src="/public/resources/images/kafic2.avif" alt="Uređaj 2" />
        <div class="badge-discount">40%</div>
      </div>
      <p class="device-name">Uređaj 2</p>
    </div>
    <!-- Uređaj 3 -->
    <div class="device-item" data-device-img="/public/resources/images/kafic3.avif" data-device-name="Uređaj 3" data-device-location="Banja Luka" data-bs-toggle="modal" data-bs-target="#reklamaModal">
      <div class="device-box">
        <img src="/public/resources/images/kafic3.avif" alt="Uređaj 3" />
        <div class="badge-discount">2%</div>
      </div>
      <p class="device-name">Uređaj 3</p>
    </div>
    <!-- Uređaj 4 -->
    <div class="device-item" data-device-img="/public/resources/images/kafic4.avif" data-device-name="Uređaj 4" data-device-location="Banja Luka" data-bs-toggle="modal" data-bs-target="#reklamaModal">
      <div class="device-box">
        <img src="/public/resources/images/kafic4.avif" alt="Uređaj 4" />
        <div class="badge-discount">94%</div>
      </div>
      <p class="device-name">Uređaj 4</p>
    </div>
    <!-- Dodati ostale uređaje po potrebi -->
  </div>

  <!-- Overlay za grafikon (prikazuje se pri hoveru) -->
  <div id="graphOverlay">
    <h5>Statistika</h5>
    <p id="overlayDeviceName">Displej: </p>
    <p id="overlayDeviceLocation">Lokacija: </p>
    <canvas id="graphCanvas" width="360" height="200"></canvas>
    <p id="overlayTime">Vrijeme prikazivanja: </p>
    <p id="overlayCount">Broj prikazivanja: </p>
    <button id="downloadPdfBtn" class="btn btn-outline-light btn-sm">Preuzmi u PDF</button>
  </div>

  <!-- Advertisement Modal (Lista reklama) -->
  <div class="modal fade" id="reklamaModal" tabindex="-1" aria-labelledby="reklamaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="reklamaModalLabel">Lista reklama za <span id="nazivUredjajaHolder"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearHash()"></button>
        </div>
        <!-- Body -->
        <div class="modal-body">
          <ul class="list-group" id="reklamaList">
            <li class="list-group-item">Heineken - 2 min na 15 min 21.6.2025 do 27.8.2025</li>
            <li class="list-group-item">Tuborg - 2 min na 15 min 21.6.2025 do 27.8.2025</li>
            <li class="list-group-item">Jelen - 2 min na 15 min 21.6.2025 do 27.8.2025</li>
          </ul>
        </div>
        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="reklamaBackBtn" onclick="handleAdvBack()">Nazad</button>
          <button type="button" class="btn btn-primary" id="dodajReklamaBtn">Dodaj reklamu</button>
          <button type="button" class="btn btn-danger" id="ukloniSekcijuBtn">Ukloni sekciju</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Nova Reklama Modal -->
  <div class="modal fade" id="novaReklamaModal" tabindex="-1" aria-labelledby="novaReklamaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="novaReklamaModalLabel">Nova Reklama</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearHash()"></button>
        </div>
        <!-- Body -->
        <div class="modal-body">
          <div class="device-header mb-3 d-flex justify-content-between align-items-center">
            <div>
              <p>Odabrali ste displej <strong id="deviceNameNovaModal">#imeDispleja</strong></p>
              <p>Lokacija displeja: Banja Luka, BB</p>
            </div>
            <div>
              <img id="deviceImageNovaModal" src="/public/resources/images/displej.jpg" alt="Displej" style="width:60px; height:60px; object-fit:cover;">
            </div>
          </div>
          <!-- Dva zasebna dugmeta (ne switch) -->
          <div class="advertisement-type d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-outline-light" id="btnNovaReklama">Nova reklama</button>
            <button type="button" class="btn btn-outline-light ms-2" id="btnPostojecaReklama">Postojeca reklama</button>
          </div>
          <!-- Forma za novu reklamu -->
          <div id="novaReklamaContent">
            <div class="slider-section mb-3 d-flex align-items-center">
              <div class="slider-container flex-grow-1">
                <p>Max dostupno: <span id="maxTimeModal">#vrijeme</span></p>
                <p>Odabrano: <span id="selectedTimeModal">0</span> minuta</p>
                <input type="range" id="timeSliderModal" min="0" max="30" value="0" class="form-range">
              </div>
              <div class="progress-circle ms-3">
                <svg width="80" height="80">
                  <circle cx="40" cy="40" r="35" stroke="#e6e6e6" stroke-width="8" fill="none"></circle>
                  <circle cx="40" cy="40" r="35" stroke="#007bff" stroke-width="8" fill="none"
                    stroke-dasharray="219.91" stroke-dashoffset="98" transform="rotate(-90 40 40)"></circle>
                  <text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="16">55%</text>
                </svg>
              </div>
            </div>
            <div class="calendar-section mb-3">
              <div class="row">
                <div class="col">
                  <label for="startDateModal" class="form-label">Početni datum:</label>
                  <input type="date" id="startDateModal" class="form-control">
                </div>
                <div class="col">
                  <label for="endDateModal" class="form-label">Krajnji datum:</label>
                  <input type="date" id="endDateModal" class="form-control">
                </div>
              </div>
            </div>
            <!-- Kontejner za prikaz izabrane postojeće reklame (nakon potvrde) -->
            <div id="selectedAdDisplayModal" class="mb-3"></div>
          </div>
        </div>
        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="novaModalBackBtn">Nazad</button>
          <button type="button" class="btn btn-primary" id="novaModalContinueBtn">Nastavi</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Ad Selection Modal (Postojeca reklama) -->
  <div class="modal fade" id="adSelectionModal" tabindex="-1" aria-labelledby="adSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="adSelectionModalLabel">Izaberite reklamu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- Body -->
        <div class="modal-body">
          <ul class="list-group" id="availableAdList">
            <li class="list-group-item">Tuborg 2 min na 15 min. 21.21.2025 / 22.22.2025</li>
            <li class="list-group-item">Tuborg1 2 min na 15 min. 21.21.2025 / 22.22.2025</li>
            <li class="list-group-item">Tuborg2 2 min na 15 min. 21.21.2025 / 22.22.2025</li>
            <li class="list-group-item">Tuborg3 2 min na 15 min. 21.21.2025 / 22.22.2025</li>
          </ul>
          <p class="mt-2">Izaberite jednu reklamu.</p>
        </div>
        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="adSelectionBackBtn">Nazad</button>
          <button type="button" class="btn btn-primary" id="confirmAdBtn">Potvrdi</button>
        </div>
      </div>
    </div>
  </div>


  <script>
    /* ================================
       Osnovne funkcije za upravljanje modalima i dropdownovima
       ================================ */
    function getModalInstance(id) {
      return bootstrap.Modal.getOrCreateInstance(document.getElementById(id));
    }

    function clearHash() {
      history.pushState("", document.title, window.location.pathname + window.location.search);
    }

    function removeBackdropIfNoModalOpen() {
      if (!document.querySelector('.modal.show')) {
        document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
          backdrop.remove();
        });
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
      }
    }
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.modal').forEach(function(modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function(e) {
          removeBackdropIfNoModalOpen();
        });
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      // Selektovanje reklama u Advertisement Modal
      document.querySelectorAll('#reklamaList .list-group-item').forEach(function(item) {
        item.addEventListener('click', function() {
          item.classList.toggle('selected');
        });
      });
      document.getElementById('ukloniSekcijuBtn').addEventListener('click', function() {
        document.querySelectorAll('#reklamaList .list-group-item.selected').forEach(function(sel) {
          sel.remove();
        });
      });
      // Postavljanje podataka za modale na klik uređaja
      document.querySelectorAll('.device-item').forEach(function(device) {
        device.addEventListener('click', function() {
          const devName = device.getAttribute('data-device-name');
          const devImg = device.getAttribute('data-device-img');
          document.getElementById('nazivUredjajaHolder').textContent = devName;
          document.getElementById('deviceNameNovaModal').textContent = devName;
          document.getElementById('deviceImageNovaModal').src = devImg;
        });
      });
      document.getElementById('reklamaBackBtn').addEventListener('click', function() {
        getModalInstance('reklamaModal').hide();
      });
      document.getElementById('dodajReklamaBtn').addEventListener('click', function() {
        getModalInstance('reklamaModal').hide();
        window.location.hash = "#nova-reklama";
        getModalInstance('novaReklamaModal').show();
      });
      document.getElementById('novaModalBackBtn').addEventListener('click', function() {
        getModalInstance('novaReklamaModal').hide();
        clearHash();
      });
      document.getElementById('timeSliderModal').addEventListener('input', function() {
        document.getElementById('selectedTimeModal').textContent = this.value;
      });
      document.getElementById('btnNovaReklama').addEventListener('click', function() {
        window.location.href = "/nova-reklama/upload";
      });
      document.getElementById('btnPostojecaReklama').addEventListener('click', function() {
        getModalInstance('adSelectionModal').show();
      });
      document.querySelectorAll('#availableAdList .list-group-item').forEach(function(item) {
        item.addEventListener('click', function() {
          document.querySelectorAll('#availableAdList .list-group-item').forEach(function(el) {
            el.classList.remove('selected');
          });
          item.classList.add('selected');
        });
      });
      document.getElementById('adSelectionBackBtn').addEventListener('click', function() {
        getModalInstance('adSelectionModal').hide();
      });
      document.getElementById('confirmAdBtn').addEventListener('click', function() {
        const selectedItem = document.querySelector('#availableAdList .list-group-item.selected');
        if (selectedItem) {
          const adData = selectedItem.textContent.trim();
          localStorage.setItem("izabranaReklama", adData);
          document.getElementById('selectedAdDisplayModal').innerHTML = '<p>Izabrana reklama: ' + adData + '</p>';
          getModalInstance('adSelectionModal').hide();
        } else {
          alert("Molimo, izaberite jednu reklamu.");
        }
      });
      document.getElementById('novaModalContinueBtn').addEventListener('click', function() {
        alert("Nastavi clicked – podaci sačuvani u localStorage.");
      });
    });

    /* ======================================
       Funkcionalnost za overlay s grafikonima
       ====================================== */
    let chartInstance = null;
    const graphOverlay = document.getElementById('graphOverlay');
    const graphCanvas = document.getElementById('graphCanvas');
    const overlayDeviceName = document.getElementById('overlayDeviceName');
    const overlayDeviceLocation = document.getElementById('overlayDeviceLocation');
    const overlayTime = document.getElementById('overlayTime');
    const overlayCount = document.getElementById('overlayCount');
    const downloadPdfBtn = document.getElementById('downloadPdfBtn');

    // Funkcija za generiranje slučajnih brojeva
    function randomInt(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    // Funkcija za kreiranje grafikona pomoću Chart.js
    function createChart() {
      if (chartInstance) {
        chartInstance.destroy();
      }
      const ctx = graphCanvas.getContext('2d');
      const labels = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
      const dataset1 = labels.map(() => randomInt(10, 50));
      const dataset2 = labels.map(() => randomInt(50, 100));
      const dataset3 = labels.map(() => randomInt(5, 30));

      chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
              label: 'Vrijeme prikazivanja (min)',
              data: dataset1,
              borderColor: 'rgba(75, 192, 192, 1)',
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              tension: 0.3
            },
            {
              label: 'Broj prikazivanja',
              data: dataset2,
              borderColor: 'rgba(255, 99, 132, 1)',
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              tension: 0.3
            },
            {
              label: 'Dodatni pokazatelj',
              data: dataset3,
              borderColor: 'rgba(153, 102, 255, 1)',
              backgroundColor: 'rgba(153, 102, 255, 0.2)',
              tension: 0.3
            }
          ]
        },
        options: {
          responsive: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
      const totalTime = dataset1.reduce((a, b) => a + b, 0);
      const totalCount = dataset2.reduce((a, b) => a + b, 0);
      overlayTime.textContent = 'Vrijeme prikazivanja: ' + totalTime + ' min';
      overlayCount.textContent = 'Broj prikazivanja: ' + totalCount;
    }

    // Funkcija za pozicioniranje i prikaz overlaya
    function showGraphOverlay(deviceEl) {
      const deviceName = deviceEl.getAttribute('data-device-name');
      const deviceLocation = deviceEl.getAttribute('data-device-location') || "Banja Luka";
      // Postavljamo podatke – ovdje koristimo elemente s id-jevima "overlayDeviceName" i "overlayDeviceLocation"
      // Ako ih niste definirali u HTML-u, definirajte ih ili koristite innerHTML na postojeći <p> element
      // Primjer: <p id="overlayDeviceName">Displej: </p>
      document.getElementById('overlayDeviceName').textContent = 'Displej: ' + deviceName;
      document.getElementById('overlayDeviceLocation').textContent = 'Lokacija: ' + deviceLocation;
      createChart();
      const rect = deviceEl.getBoundingClientRect();
      graphOverlay.style.top = (rect.bottom + window.scrollY + 10) + 'px';
      graphOverlay.style.left = (rect.left + window.scrollX) + 'px';
      graphOverlay.style.display = 'block';
    }

    function hideGraphOverlay() {
      graphOverlay.style.display = 'none';
    }

    document.querySelectorAll('.device-item').forEach(function(device) {
      device.addEventListener('mouseenter', function() {
        showGraphOverlay(device);
      });
      device.addEventListener('mouseleave', function() {
        setTimeout(() => {
          if (!graphOverlay.matches(':hover')) {
            hideGraphOverlay();
          }
        }, 200);
      });
    });
    graphOverlay.addEventListener('mouseleave', function() {
      hideGraphOverlay();
    });

    downloadPdfBtn.addEventListener('click', function() {
      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF();
      doc.setTextColor(0, 0, 0);
      doc.text("Statistika", 10, 10);
      doc.text(document.getElementById('overlayDeviceName').textContent, 10, 20);
      doc.text(document.getElementById('overlayDeviceLocation').textContent, 10, 30);
      doc.text(document.getElementById('overlayTime').textContent, 10, 40);
      doc.text(document.getElementById('overlayCount').textContent, 10, 50);
      const canvasImg = graphCanvas.toDataURL("image/png");
      doc.addImage(canvasImg, "PNG", 10, 60, 180, 100);
      doc.save("statistika.pdf");
    });
  </script>
</body>

</html>