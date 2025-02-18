<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Drag & Drop Upload - Tamni Režim</title>
    <!-- Učitavanje Bootstrap CSS -->

    <style>
        /* Tamni režim */
        body {
            background-color: #121212;
            color: #e0e0e0;
        }

        .container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            position: relative;
        }

        #dropArea {
            border: 2px dashed #555;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            color: #aaa;
            margin: 20px auto;
            width: 80%;
            transition: background-color 0.3s;
            background-color: #1e1e1e;
        }

        #dropArea.hover {
            background-color: #333;
            border-color: #888;
            color: #fff;
        }

        .modal-content {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }

        /* Status limita */
        #queueStatus {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }

        /* Akcije nad queue-om (tipke) */
        #queueActions {
            text-align: center;
            margin-top: 10px;
        }

        /* Lista čekajućih stavki */
        #uploadListSection {
            margin-top: 20px;
            display: none;
        }

        #uploadListSection ul {
            list-style: none;
            padding: 0;
        }

        #uploadListSection li {
            padding: 5px 10px;
            border-bottom: 1px solid #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Tipka za brisanje pojedinačne stavke */
        .removeItemBtn {
            background: transparent;
            border: none;
            color: #dc3545;
            font-weight: bold;
            cursor: pointer;
        }

        /* Tipka za čišćenje localStorage – samo za testiranje */
        #clearLocalStorageBtn {
            position: absolute;
            top: 10px;
            right: 10px;
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
    <!-- Dugme "Back to Administrators" -->
    <div class="container-fluid">
        <a href="/korisnici" class="btn-back">&larr; Nazad na Uredjaj #ImeUredjaja</a>
    </div>

    <div class="container">
        <!-- Tipka za čišćenje localStorage (samo za testiranje) -->
        <button id="clearLocalStorageBtn" class="btn btn-danger btn-sm">Clear LocalStorage</button>
        <h1 class="text-center mt-4">Drag & Drop Upload</h1>
        <!-- Prikaz limita: Slike X/10 | Video Y/10 -->
        <div id="queueStatus"></div>
        <!-- Drop area i gumb za dodavanje -->
        <div id="dropArea">
            <p>Povucite i ispustite sliku/video ovdje ili kliknite "Odaberi Datoteku"</p>
            <!-- File input – samo jedan file -->
            <input type="file" id="fileInput" style="display: none;" accept="image/*,video/*">
            <button id="addFileBtn" class="btn btn-primary">Odaberi Datoteku</button>
        </div>
        <div id="alertContainer"></div>
        <!-- Akcije nad queue-om – prikazuju se kad u queue-u ima stavki -->
        <div id="queueActions" style="display: none;">
            <button id="finishUploadBtn" class="btn btn-success me-2">Završi Upload</button>
            <button id="cancelUploadBtn" class="btn btn-warning">Otkazi Upload</button>
        </div>
        <!-- Lista čekajućih stavki -->
        <div id="uploadListSection">
            <h3>Upload Queue:</h3>
            <ul id="uploadListUl"></ul>
        </div>
    </div>

    <!-- Modal 1: Detekcija i unos detalja -->
    <div class="modal fade" id="modalUpload" tabindex="-1" aria-labelledby="modalUploadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Naslov će se dinamički mijenjati -->
                    <h5 class="modal-title" id="modalUploadLabel">Detektirana je slika ili video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
                </div>
                <div class="modal-body">
                    <!-- Slider container – prikazuje se samo za slike -->
                    <div class="mb-3" id="sliderContainer">
                        <label for="durationSlider" class="form-label">
                            Koliko dugo želite da traje vaša reklama (<span id="durationValue">15</span> sec)
                        </label>
                        <input type="range" class="form-range" min="0" max="30" step="1" id="durationSlider" value="15">
                    </div>
                    <!-- Unos naziva kompanije (vidljiv za oba tipa) -->
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Naziv kompanije kojoj pripada reklama:</label>
                        <input type="text" class="form-control" id="companyName" placeholder="Unesite naziv kompanije">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modalUploadCancel" class="btn btn-secondary" data-bs-dismiss="modal">Otkazi</button>
                    <button type="button" id="modalUploadConfirm" class="btn btn-primary">Potvrdi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Uvjeti i potvrda (ostaje isti za oba tipa) -->
    <div class="modal fade" id="modalTerms" tabindex="-1" aria-labelledby="modalTermsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTermsLabel">Prihvaćam uvjete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li>Reklame ne pripadaju #Organizacija grupaciji.</li>
                        <li>Ne prikazujem pornografski sadržaj.</li>
                        <li>Reklama nije političkog karaktera.</li>
                        <li>Reklama ne promovira nacionalnu netrepeljivost.</li>
                    </ul>
                    <p><small>Svako odstupanje od navedenih pravila podliježe krivičnom gonjenju.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modalTermsCancel" class="btn btn-secondary" data-bs-dismiss="modal">Otkazi</button>
                    <button type="button" id="modalTermsConfirm" class="btn btn-primary">Potvrdi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Globalne varijable
        let fileQueue = []; // Stavke u queue-u čekaju finalizaciju upload-a
        let currentFile = null;
        let currentFileDataURL = null;
        let currentFileType = "";
        let currentDuration = 15;
        let currentCompanyName = "";

        // Učitavanje već upload-ovanih datoteka iz localStorage
        let uploadedImages = JSON.parse(localStorage.getItem('uploadedImages')) || [];
        let uploadedVideos = JSON.parse(localStorage.getItem('uploadedVideos')) || [];

        // DOM elementi
        const fileInput = document.getElementById('fileInput');
        const addFileBtn = document.getElementById('addFileBtn');
        const alertContainer = document.getElementById('alertContainer');
        const queueStatus = document.getElementById('queueStatus');
        const queueActions = document.getElementById('queueActions');
        const finishUploadBtn = document.getElementById('finishUploadBtn');
        const cancelUploadBtn = document.getElementById('cancelUploadBtn');
        const uploadListSection = document.getElementById('uploadListSection');
        const uploadListUl = document.getElementById('uploadListUl');
        const clearLocalStorageBtn = document.getElementById('clearLocalStorageBtn');

        // Modali
        const modalUpload = new bootstrap.Modal(document.getElementById('modalUpload'));
        const modalTerms = new bootstrap.Modal(document.getElementById('modalTerms'));

        // Slider logika – ažuriranje prikaza trajanja
        const durationSlider = document.getElementById('durationSlider');
        const durationValue = document.getElementById('durationValue');
        durationSlider.addEventListener('input', () => {
            durationValue.textContent = durationSlider.value;
        });

        // Ažuriranje statusa queue-a i prikaza akcijskih tipki te liste
        function updateQueueStatus() {
            // Broj stavki u queue po tipu
            const queuedImages = fileQueue.filter(item => item.type === 'image').length;
            const queuedVideos = fileQueue.filter(item => item.type === 'video').length;
            // Ukupno (uključujući već upload-ovane)
            const totalImages = uploadedImages.length + queuedImages;
            const totalVideos = uploadedVideos.length + queuedVideos;
            queueStatus.textContent = `Slike: ${totalImages}/10 | Video: ${totalVideos}/10`;

            // Prikaz akcijskih tipki i liste ako queue nije prazan
            if (fileQueue.length > 0) {
                queueActions.style.display = 'block';
                uploadListSection.style.display = 'block';
                updateUploadList();
            } else {
                queueActions.style.display = 'none';
                uploadListSection.style.display = 'none';
            }
        }

        // Ažuriranje liste čekajućih upload stavki s X tipkom za brisanje pojedinačnih stavki
        function updateUploadList() {
            uploadListUl.innerHTML = "";
            fileQueue.forEach((item, index) => {
                const li = document.createElement('li');
                li.innerHTML = `${item.fileName} (${item.type}) - ${item.company}, trajanje: ${item.duration} sec `;
                // X tipka za brisanje ove stavke
                const removeBtn = document.createElement('button');
                removeBtn.textContent = "X";
                removeBtn.className = "removeItemBtn";
                removeBtn.addEventListener('click', () => {
                    fileQueue.splice(index, 1);
                    updateQueueStatus();
                });
                li.appendChild(removeBtn);
                uploadListUl.appendChild(li);
            });
        }

        // Funkcija za prikaz Bootstrap alert-a
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-' + type + ' alert-dismissible fade show';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            alertContainer.appendChild(alertDiv);
            setTimeout(() => {
                alertDiv.classList.remove('show');
                alertDiv.remove();
            }, 3000);
        }

        // Obrada odabrane datoteke
        function processFile(file) {
            // Provjera tipa i limita (uključujući već upload-ovane i one u queue-u)
            if (file.type.startsWith('image/')) {
                const queuedImages = fileQueue.filter(f => f.type === 'image').length;
                if (uploadedImages.length + queuedImages >= 10) {
                    showAlert('Dosegnut je limit za slike (10).', 'danger');
                    return;
                }
                currentFileType = 'image';
            } else if (file.type.startsWith('video/')) {
                const queuedVideos = fileQueue.filter(f => f.type === 'video').length;
                if (uploadedVideos.length + queuedVideos >= 10) {
                    showAlert('Dosegnut je limit za videe (10).', 'danger');
                    return;
                }
                currentFileType = 'video';
            } else {
                showAlert('Nepodržan format: ' + file.name, 'warning');
                return;
            }
            currentFile = file;
            const reader = new FileReader();
            reader.onload = (e) => {
                currentFileDataURL = e.target.result;
                // Reset modala
                if (currentFileType === 'image') {
                    document.getElementById('sliderContainer').style.display = 'block';
                    document.getElementById('modalUploadLabel').textContent = 'Detektirana je slika';
                } else if (currentFileType === 'video') {
                    document.getElementById('sliderContainer').style.display = 'none';
                    document.getElementById('modalUploadLabel').textContent = 'Detektiran je video';
                }
                // Reset slider i unos kompanije
                durationSlider.value = 15;
                durationValue.textContent = 15;
                document.getElementById('companyName').value = "";
                modalUpload.show();
            };
            reader.readAsDataURL(file);
        }

        // Event za file input – odabir datoteke
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                processFile(e.target.files[0]);
            }
            fileInput.value = "";
        });

        // Gumb za dodavanje datoteke
        addFileBtn.addEventListener('click', () => {
            fileInput.click();
        });

        // Drag & Drop događaji – procesira se samo prva datoteka
        const dropArea = document.getElementById('dropArea');
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('hover');
        });
        dropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropArea.classList.remove('hover');
        });
        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('hover');
            if (e.dataTransfer.files.length > 0) {
                processFile(e.dataTransfer.files[0]);
            }
        });

        // Modal 1 – unos detalja (trajanje i naziv kompanije)
        document.getElementById('modalUploadConfirm').addEventListener('click', () => {
            currentCompanyName = document.getElementById('companyName').value.trim();
            if (currentCompanyName === "") {
                showAlert('Molimo unesite naziv kompanije.', 'warning');
                return;
            }
            // Ako je tip slike, dohvaćamo vrijednost slidera, a za video postavljamo trajanje na 0
            if (currentFileType === 'image') {
                currentDuration = durationSlider.value;
            } else {
                currentDuration = 0;
            }
            modalUpload.hide();
            modalTerms.show();
        });

        document.getElementById('modalUploadCancel').addEventListener('click', () => {
            modalUpload.hide();
            currentFile = null;
            currentFileDataURL = null;
        });

        // Modal 2 – uvjeti
        document.getElementById('modalTermsConfirm').addEventListener('click', () => {
            modalTerms.hide();
            // Kreiramo objekt s podacima i dodajemo ga u queue
            const uploadData = {
                fileName: currentFile.name,
                fileData: currentFileDataURL,
                type: currentFileType,
                duration: currentDuration,
                company: currentCompanyName,
                timestamp: new Date().toISOString()
            };
            fileQueue.push(uploadData);
            showAlert(currentFile.name + ' je dodan u queue.', 'success');
            currentFile = null;
            currentFileDataURL = null;
            updateQueueStatus();
        });

        document.getElementById('modalTermsCancel').addEventListener('click', () => {
            modalTerms.hide();
            currentFile = null;
            currentFileDataURL = null;
        });

        // "Commit" queue – završetak upload-a
        function commitUploadQueue() {
            fileQueue.forEach(item => {
                if (item.type === 'image') {
                    uploadedImages.push(item);
                } else if (item.type === 'video') {
                    uploadedVideos.push(item);
                }
            });
            localStorage.setItem('uploadedImages', JSON.stringify(uploadedImages));
            localStorage.setItem('uploadedVideos', JSON.stringify(uploadedVideos));
            showAlert('Upload završen za ' + fileQueue.length + ' stavki.', 'success');
            fileQueue = [];
            updateQueueStatus();
        }

        // Brisanje cijelog queue-a (otkaz upload)
        function cancelUploadQueue() {
            fileQueue = [];
            updateQueueStatus();
            showAlert('Queue je otkazan.', 'warning');
        }

        // Event listeneri za akcijske tipke nad queue-om
        finishUploadBtn.addEventListener('click', commitUploadQueue);
        cancelUploadBtn.addEventListener('click', cancelUploadQueue);

        // Tipka za čišćenje localStorage (samo za testiranje)
        clearLocalStorageBtn.addEventListener('click', () => {
            localStorage.removeItem('uploadedImages');
            localStorage.removeItem('uploadedVideos');
            showAlert('LocalStorage je očišćen.', 'info');
        });

        // Inicijalno ažuriramo status queue-a
        updateQueueStatus();
    </script>
</body>

</html>