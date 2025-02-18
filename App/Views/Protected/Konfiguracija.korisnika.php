<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upravljanje Klijentima</title>
    <link rel="stylesheet" href="/public/resources/css/konfi.korisnika.css">
</head>

<body>
    <!-- Dugme "Back to Administrators" -->
    <div class="container-fluid">
        <a href="/administrators" class="btn-back">&larr; Back to Administrators</a>
    </div>
    <!-- Naslov i tipka "Dodaj Klijenta" -->
    <div class="container-title">
        <h2>Upravljanje Klijentima</h2>
        <button class="btn-add-client" data-bs-toggle="modal" data-bs-target="#addClientModal">
            Dodaj Klijenta <i class="bi bi-person-plus-fill"></i>
        </button>
    </div>
    <!-- Mreža klijenata -->
    <div class="client-grid">
        <!-- Primer klijenta -->
        <div class="client-item">
            <img src="/public/resources/images/kafic1.avif" alt="Klijent 1" class="client-img">
            <div class="client-info">
                <span class="client-name">Debela Berta</span>
                <span class="client-duration">250 min</span>
            </div>
        </div>
        <!-- Dodajte ostale klijente prema potrebi -->
    </div>

    <!-- Glavni modal za dodavanje klijenta -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Dodaj Klijenta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClientForm">
                        <!-- Upload Slika – centriran kontejner sa fiksnom 100x100 okruglom placeholder slikom -->
                        <div class="upload-container">
                            <img id="clientImagePreview" src="/public/resources/images/default-profile.png" alt="Profil Slika" class="profile-img">
                            <label for="clientImageUpload" class="upload-btn"><i class="bi bi-upload"></i></label>
                            <input type="file" id="clientImageUpload" accept="image/*" style="display: none;">
                        </div>
                        <!-- Ime, Email, Tel (bez placeholdera) -->
                        <div class="mb-3">
                            <label for="clientName" class="form-label">Ime</label>
                            <input type="text" class="form-control bg-secondary text-light" id="clientName">
                        </div>
                        <div class="mb-3">
                            <label for="clientEmail" class="form-label">Email</label>
                            <input type="email" class="form-control bg-secondary text-light" id="clientEmail">
                        </div>
                        <div class="mb-3">
                            <label for="clientTel" class="form-label">Tel</label>
                            <input type="tel" class="form-control bg-secondary text-light" id="clientTel">
                        </div>
                        <hr style="border-top: 1px solid #444;">
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="clientUsername" class="form-label">Username</label>
                            <input type="text" class="form-control bg-secondary text-light" id="clientUsername">
                        </div>
                        <!-- Password sa toggle prikazom -->
                        <div class="mb-3">
                            <label for="clientPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control bg-secondary text-light" id="clientPassword">
                                <button class="btn btn-outline-light" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Retype Password sa toggle prikazom -->
                        <div class="mb-3">
                            <label for="clientPasswordRetype" class="form-label">Retype Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control bg-secondary text-light" id="clientPasswordRetype">
                                <button class="btn btn-outline-light" type="button" id="togglePasswordRetype">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Autogen Password tipka – može se otvarati više puta -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-light w-100" data-bs-toggle="modal" data-bs-target="#autogenModal">
                                Autogen Password
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ponisti</button>
                    <button type="button" class="btn btn-primary" id="registerClientBtn">Registruj Klijenta</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Autogen Password modal -->
    <div class="modal fade" id="autogenModal" tabindex="-1" aria-labelledby="autogenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="autogenModalLabel">Generiši Lozinku</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="generatedPassword" style="font-size: 1.5rem; letter-spacing: 1px;"></p>
                    <button class="btn btn-outline-light btn-sm mt-2" id="generateNewPasswordBtn">Generate New</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="choosePasswordBtn">Izaberi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Skripta za upload preview slike -->
    <script src="/public/resources/script/Konfiguracija.korisnika.js"></script>
</body>

</html>