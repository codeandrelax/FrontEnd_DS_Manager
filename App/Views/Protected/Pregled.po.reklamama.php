<!DOCTYPE html>
<html lang="hr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lista Reklama</title>
  <link rel="stylesheet" href="/public/resources/css/pregled.po.reklamama.css">
</head>

<body>
  <!-- Sekcija s listom reklama -->
  <section id="adListSection" class="content-section active">
    <div class="container-fluid">
      <div class="row">
        <!-- Reklame -->
        <div class="col-lg-9">
          <h2 class="mb-3">Lista Reklama</h2>
          <div class="ad-grid" id="adGrid">
            <div class="ad-item" data-ad-id="1" data-ad-title="Reklama 1" data-ad-image="https://picsum.photos/400?random=1">
              <div class="ad-image-container">
                <img src="https://picsum.photos/100?random=1" alt="Reklama 1">
                <input type="checkbox" class="delete-checkbox">
              </div>
              <div class="ad-title">Reklama 1</div>
            </div>
            <div class="ad-item" data-ad-id="2" data-ad-title="Reklama 2" data-ad-image="https://picsum.photos/400?random=2">
              <div class="ad-image-container">
                <img src="https://picsum.photos/100?random=2" alt="Reklama 2">
                <input type="checkbox" class="delete-checkbox">
              </div>
              <div class="ad-title">Reklama 2</div>
            </div>
            <div class="ad-item" data-ad-id="3" data-ad-title="Reklama 3" data-ad-image="https://picsum.photos/400?random=3">
              <div class="ad-image-container">
                <img src="https://picsum.photos/100?random=3" alt="Reklama 3">
                <input type="checkbox" class="delete-checkbox">
              </div>
              <div class="ad-title">Reklama 3</div>
            </div>
            <!-- Daljnje reklame po potrebi -->
          </div>
        </div>
        <!-- AdManager Panel -->
        <div class="col-lg-3">
          <div class="ad-manager">
            <h4>AdManager</h4>
            <p>Ovdje možete upravljati reklamama. Dodajte novu reklamu ili označite postojeće za brisanje.</p>
            <div class="d-grid gap-2">
              <button id="addAdButton" class="btn btn-primary">Dodaj reklamu</button>
              <button id="removeAdButton" class="btn btn-danger">Ukloni reklamu</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Sekcija s detaljima reklame -->
  <section id="adDetailsSection" class="content-section">
    <div class="container-fluid">
      <!-- Gornji red: tipka Nazad (s ikonom) lijevo, a gumbi za displej desno -->
      <div class="row align-items-center detail-header mb-3">
        <div class="col-auto">
          <button class="btn btn-secondary back-button">
            <i class="bi bi-arrow-left"></i> Nazad
          </button>
        </div>
        <div class="col-auto ms-auto">
          <div class="d-flex gap-2">
            <button id="addDisplejButton" class="btn btn-primary">Dodaj displej</button>
            <button id="removeDisplejButton" class="btn btn-danger">Izbriši displej</button>
          </div>
        </div>
      </div>
      <!-- Zaglavlje s detaljima reklame -->
      <div class="row mb-3">
        <div class="col">
          <h2 id="adDetailsHeader"></h2>
        </div>
      </div>
      <!-- Displej grid: slike displeja -->
      <div class="row">
        <div class="col">
          <div class="displej-grid" id="displejGrid">
            <!-- Displeji će biti generirani dinamički -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Improved Modal: Dodaj Displej -->
  <div class="modal fade" id="addDisplejModal" tabindex="-1" aria-labelledby="addDisplejModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-dark text-light">
        <!-- Modal Header -->
        <div class="modal-header border-secondary">
          <div class="d-flex align-items-center">
            <img id="clickedAdImage" src="" alt="Reklama" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
            <div>
              <h5 class="modal-title" id="addDisplejModalLabel">Dodavanje displeja za reklamu</h5>
              <small class="text-muted">imereklame.mp4</small>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Zatvori"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <!-- Section: Vrijeme prikaza reklame -->
          <section class="mb-4 text-center">
            <p>( Vrijeme prikaza reklame u <span id="displayTimeLabel">30</span> min )</p>
            <input type="range" class="form-range mx-auto" min="0" max="30" step="1" value="30" id="timeSlider">
            <p>Vrijeme: <span id="sliderValue">30</span> min</p>
          </section>

          <!-- Section: Odabir datuma -->
          <section class="mb-4 text-center">
            <div class="text-center">
              <input type="text" id="dateRange" class="form-control mx-auto" style="max-width:300px;" placeholder="Odaberite početni i krajnji datum">
            </div>
            <small class="text-muted">Sve izmjene su važeće od sljedećeg dana u 00:00</small>
          </section>



          <hr class="border-secondary">

          <!-- Section: Selektuj displej i ograničenja -->
          <section>
            <div class="row">
              <!-- Grid displeja -->
              <div class="col-md-8">
                <p class="mb-2">Klikni da selektuješ displej</p>
                <div class="row" id="displaySelection">
                  <!-- Displej 1 -->

                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=1" alt="Displej 1" class="img-fluid rounded">
                      <span class="position-absolute top-0 end-0 badge bg-warning" style="font-size: 0.6rem;">★</span>
                      <span class="position-absolute bottom-0 end-0 badge bg-info" style="font-size: 0.6rem;">50%</span>
                    </div>
                    <small class="d-block text-center">Displej 1</small>
                  </div>

                  <!-- Displej 2 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=2" alt="Displej 2" class="img-fluid rounded">
                    </div>
                    <small class="d-block text-center">Displej 2</small>
                  </div>
                  <!-- Displej 3 -->

                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=3" alt="Displej 3" class="img-fluid rounded">
                      <span class="position-absolute top-0 end-0 badge bg-warning" style="font-size: 0.6rem;">★</span>
                    </div>
                    <small class="d-block text-center">Displej 3</small>
                  </div>

                  <!-- Displej 4 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=4" alt="Displej 4" class="img-fluid rounded">
                      <span class="position-absolute bottom-0 end-0 badge bg-info" style="font-size: 0.6rem;">70%</span>
                    </div>
                    <small class="d-block text-center">Displej 4</small>
                  </div>

                  <!-- Displej 5 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=5" alt="Displej 5" class="img-fluid rounded">
                    </div>
                    <small class="d-block text-center">Displej 5</small>
                  </div>

                  <!-- Displej 6 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=6" alt="Displej 6" class="img-fluid rounded">
                      <span class="position-absolute top-0 end-0 badge bg-warning" style="font-size: 0.6rem;">★</span>
                    </div>
                    <small class="d-block text-center">Displej 6</small>
                  </div>

                  <!-- Displej 7 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=7" alt="Displej 7" class="img-fluid rounded">
                    </div>
                    <small class="d-block text-center">Displej 7</small>
                  </div>

                  <!-- Displej 8 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=8" alt="Displej 8" class="img-fluid rounded">
                      <span class="position-absolute bottom-0 end-0 badge bg-info" style="font-size: 0.6rem;">30%</span>
                    </div>
                    <small class="d-block text-center">Displej 8</small>
                  </div>

                  <!-- Displej 9 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=9" alt="Displej 9" class="img-fluid rounded">
                    </div>
                    <small class="d-block text-center">Displej 9</small>
                  </div>

                  <!-- Displej 10 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=10" alt="Displej 10" class="img-fluid rounded">
                      <span class="position-absolute top-0 end-0 badge bg-warning" style="font-size: 0.6rem;">★</span>
                    </div>
                    <small class="d-block text-center">Displej 10</small>
                  </div>

                  <!-- Displej 11 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=11" alt="Displej 11" class="img-fluid rounded">
                    </div>
                    <small class="d-block text-center">Displej 11</small>
                  </div>

                  <!-- Displej 12 -->
                  <div class="col-3 mb-3">
                    <div class="position-relative text-center">
                      <img src="https://picsum.photos/50?random=12" alt="Displej 12" class="img-fluid rounded">
                      <span class="position-absolute bottom-0 end-0 badge bg-info" style="font-size: 0.6rem;">90%</span>
                    </div>
                    <small class="d-block text-center">Displej 12</small>
                  </div>
                </div>
              </div>

              <!-- Ograničenja displeja -->
              <div class="col-md-4">
                <h6 class="mb-3">Ograničenja displeja i info</h6>
                <ul class="list-unstyled">
                  <li>• Zabranjen Nektar</li>
                  <li>• Zabranjen Jelen</li>
                  <!-- Dodatne stavke prema potrebi -->
                </ul>
              </div>

            </div>
          </section>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer border-secondary">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
          <button type="button" class="btn btn-primary">Sačuvaj promjene</button>
        </div>
      </div>
    </div>
  </div>

  <script src="/public/resources/script/Pregled.po.reklamama.js"></script>
</body>

</html>