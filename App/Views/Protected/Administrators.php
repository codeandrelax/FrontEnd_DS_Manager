<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .p {
        color: #ffffff;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    canvas {
        max-height: 350px;
        max-width: 100%;
    }

    .table {
        font-size: 14px;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
    }

    body {
        background-color: #121212;
        color: #ffffff;
    }

    .card {
        background-color: #1e1e1e;
        color: #ffffff;
    }

    .table {
        background-color: #1e1e1e;
        color: #ffffff;
    }

    .table th {
        background-color: #2e2e2e;
    }

    .custom-text {
        color: rgb(110, 110, 110);
        /* Promeni boju po želji */
    }
</style>
<!-- Welcome page-->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-3">Dobrodošli na stranicu <?= htmlspecialchars($username ?? 'Guest') ?></h1>

            <p class="custom-text">Ovdje možete vidjeti zaštićene informacije koje su dostupne samo administratorima.</p>
        </div>
    </div>

    <!-- CSRF Token Section 
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="alert alert-info">
                <strong>CSRF Token:</strong>
                <code><?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?></code>
            </div>
        </div>
    </div> -->

    <!-- Admin Actions -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-diagram-3-fill"></i>
                        Upravljanje uređajima
                    </h5>
                    <p class="card-text">Ažurirajte ili uklonite uređaj u sistemu.</p>
                    <a href="/administrators/upravljanje" class="btn btn-primary">
                        <i class="bi bi-diagram-3-fill"></i>
                        Upravljaj uređajima
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-hdd-rack"></i>
                        Registracija uređaja
                    </h5>
                    <p class="card-text">Registracija novih uređaja.</p>
                    <a href="/administrators/registracija" class="btn btn-success">
                        <i class="bi bi-hdd-network-fill"></i>
                        Registruj uređaj
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-gear me-2"></i>
                        Klijentske postavke
                    </h5>
                    <p class="card-text">Konfigurirajte postavke klijenata.</p>
                    <a href="/administrators/konfiguracija" class="btn btn-warning">
                        <i class="bi bi-sliders me-2"></i>
                        Konfiguriši
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->

</div>
</div>

<style>
    /* Stilovi specifični samo za admin sadržaj */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Korisnici po ulozi
        const rolesChartCtx = document.getElementById("rolesChart").getContext("2d");
        let rolesChart;

        function fetchUsersByRoles() {
            fetch("/api/users-by-roles")
                .then((response) => {
                    if (!response.ok) throw new Error("Greška prilikom učitavanja podataka za korisnike po ulozi.");
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        const labels = data.data.map((item) => item.role_name);
                        const counts = data.data.map((item) => item.count);
                        updateRolesChart(labels, counts);
                    }
                })
                .catch((error) => console.error(error));
        }

        function updateRolesChart(labels, counts) {
            if (rolesChart) rolesChart.destroy();

            rolesChart = new Chart(rolesChartCtx, {
                type: "pie",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Korisnici po ulozi",
                        data: counts,
                        backgroundColor: ["#007bff", "#28a745", "#ffc107", "#dc3545", "#17a2b8"],
                    }, ],
                },
            });
        }

        // 2. Aktivni korisnici po danima
        const activeUsersChartCtx = document.getElementById("activeUsersDaysChart").getContext("2d");
        let activeUsersChart;

        function fetchActiveUsersByDays() {
            fetch("/api/active-users-by-days")
                .then((response) => {
                    if (!response.ok) throw new Error("Greška prilikom učitavanja podataka za aktivne korisnike po danima.");
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        const labels = data.data.map((item) => item.day);
                        const counts = data.data.map((item) => item.count);
                        updateActiveUsersChart(labels, counts);
                    }
                })
                .catch((error) => console.error(error));
        }

        function updateActiveUsersChart(labels, counts) {
            if (activeUsersChart) activeUsersChart.destroy();

            activeUsersChart = new Chart(activeUsersChartCtx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Aktivni korisnici",
                        data: counts,
                        borderColor: "#007bff",
                        backgroundColor: "rgba(0, 123, 255, 0.2)",
                        fill: true,
                        tension: 0.4,
                    }, ],
                },
            });
        }

        // 3. Registrovani korisnici po mesecima
        const registeredUsersChartCtx = document.getElementById("registeredUsersMonthsChart").getContext("2d");
        let registeredUsersChart;

        function fetchRegisteredUsersByMonths() {
            fetch("/api/registered-users-by-months")
                .then((response) => {
                    if (!response.ok) throw new Error("Greška prilikom učitavanja podataka za registrovane korisnike po mesecima.");
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        const labels = data.data.map((item) => `Mesec ${item.month}`);
                        const counts = data.data.map((item) => item.count);
                        updateRegisteredUsersChart(labels, counts);
                    }
                })
                .catch((error) => console.error(error));
        }

        function updateRegisteredUsersChart(labels, counts) {
            if (registeredUsersChart) registeredUsersChart.destroy();

            registeredUsersChart = new Chart(registeredUsersChartCtx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Registrovani korisnici",
                        data: counts,
                        backgroundColor: "#28a745",
                    }, ],
                },
            });
        }

        // 4. Iskorišćenost servera
        const serverUsageChartCtx = document.getElementById("serverUsageChart").getContext("2d");
        let serverUsageChart;

        function fetchServerUsage() {
            fetch("/api/server-usage")
                .then((response) => {
                    if (!response.ok) throw new Error("Greška prilikom učitavanja podataka za iskorišćenost servera.");
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        const labels = ["RAM", "CPU", "SSD"];
                        const usage = [data.data.ram, data.data.cpu, data.data.ssd];
                        updateServerUsageChart(labels, usage);
                    }
                })
                .catch((error) => console.error(error));
        }

        function updateServerUsageChart(labels, usage) {
            if (serverUsageChart) serverUsageChart.destroy();

            serverUsageChart = new Chart(serverUsageChartCtx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Iskorišćenost (%)",
                        data: usage,
                        backgroundColor: ["#17a2b8", "#ffc107", "#dc3545"],
                    }, ],
                },
            });
        }

        // 5. Trenutno online korisnici
        function fetchOnlineUsers() {
            fetch("/api/online-users")
                .then((response) => {
                    if (!response.ok) throw new Error("Greška prilikom učitavanja trenutnih online korisnika.");
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        updateOnlineUsersTable(data.data);
                    }
                })
                .catch((error) => console.error(error));
        }

        function updateOnlineUsersTable(users) {
            const onlineUsersTableBody = document.getElementById("onlineUsersTableBody");
            onlineUsersTableBody.innerHTML = "";

            if (users.length > 0) {
                users.forEach((user, index) => {
                    const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${user.username || "?"}</td>
                    <td>${user.email || "?"}</td>
                    <td>${user.last_active}</td>
                </tr>
            `;
                    onlineUsersTableBody.innerHTML += row;
                });
            } else {
                onlineUsersTableBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center">Trenutno nema korisnika online.</td>
            </tr>
        `;
            }
        }

        //********************************************************************************* */


        // Fetch and Update All Data
        fetchUsersByRoles();
        fetchActiveUsersByDays();
        fetchRegisteredUsersByMonths();
        fetchServerUsage();
        fetchOnlineUsers();

        // Periodično osvežavanje podataka (10 sekundi)
        setInterval(fetchOnlineUsers, 10000);
    });
</script>