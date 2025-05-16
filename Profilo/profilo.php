<?php
   session_start();
   $passwordError  = $_SESSION['error_message'] ?? null;
   $successMessage = $_SESSION['success_message'] ?? null;


   unset($_SESSION['error_message'], $_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styleprofilo.css">
</head>
<body>
    <div class="container">
        <div class="menu">
            <h2>Menu Profilo</h2>
            <ul>
                <li><a href="#" onclick="showSection('statistiche')">Statistiche</a></li>
                <li><a href="#" onclick="showSection('dati-personali')">Dati Personali</a></li>
                <li><a href="#" onclick="showSection('videolezioni')">Videolezioni</a></li>
                <li><a href="#" onclick="showSection('classifiche')">Classifiche</a></li>
                <li class="home-menu-item"><a href="../Index/index.php" class="home-button">Torna alla Home</a></li>
                <li class="game-menu-item"><a href="../Game/game.php" class="game-button">Andiamo a Giocare</a></li>
            </ul>
        </div>
        <!--body statistiche-->
        <div class="content">
            <div id="statistiche" class="content-section">
                <h2>Statistiche</h2>
                <div class="charts-container">
                    <div class="chart-wrapper full-width">
                        <h3>Andamento Punteggi nel Tempo</h3>
                        <canvas id="progressChart"></canvas>
                    </div>
                    <div class="chart-wrapper">
                        <h3>Distribuzione Punteggi</h3>
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="chart-wrapper">
                        <h3>Miglioramenti Mensili</h3>
                        <canvas id="improvementChart"></canvas>
                    </div>
                </div>
            </div>

            <!--dati personali-->
            <div id="dati-personali" class="content-section">
                <h2>Dati Personali</h2>
                <form id="personalDataForm" class="personal-data-form">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($_SESSION['nome']);?>"readonly>
                    </div>
                    <div class="form-group">
                        <label for="cognome">Cognome:</label>
                        <input type="text" id="cognome" name="cognome" value="<?php echo htmlspecialchars($_SESSION['cognome']);?>"readonly >
                    </div>
                    <div class="form-group">
                        <label for="username">Nome Utente:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']);?>"readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']);?>"readonly>
                    </div>
                    <div class="form-group">
                        <label for="data_nascita">Data di Nascita:</label>
                        <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($_SESSION['data']);?>"readonly>
                    </div>
                    <div class="form-group password-group">
                        <label for="password">Password:</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" value= "********" disabled>
                            <button type="button" class="modify-password-btn" onclick="togglePopup()">Modifica Password</button>
                        </div>
                    </div>
                </form>
            <!--form modifica della password-->
                <div id="changePasswordPopup" class="popup hidden">
                    <div class="popup-content">
                        <span class="close" onclick="togglePopup()">&times;</span>
                        <h3>Modifica Password</h3>
                        <form id="changePasswordForm" action="update_password.php" method="POST">
                            <div class="form-group">
                                <label for="currentPassword">Password Attuale:</label>
                                <input type="password" id="currentPassword" name="currentPassword" required>
                                <div id="currentPasswordError" class="error-message"></div>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">Nuova Password:</label>
                                <input type="password" id="newPassword" name="newPassword" required>
                                <div id="newPasswordError" class="error-message"></div>
                            </div>
                            <div class="form-group">
                                <label for="confirmNewPassword">Conferma Nuova Password:</label>
                                <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>
                                <div id="confirmNewPasswordError" class="error-message"></div>
                            </div>
                            <button type="submit">Salva Modifiche</button>
                            <button type="button" onclick="togglePopup()">Annulla</button>
                        </form>
                    </div>
                </div>
            </div>
            <!--Videolezioni-->

            <div id="videolezioni" class="content-section">
                <h2>Videolezioni</h2>
                <p>Qui troverai l'elenco delle videolezioni disponibili.</p>
            </div>

            <!--classifiche-->
            <div id="classifiche" class="content-section">
                <h2>Classifiche Mensili</h2>
                <div class="months-grid">
                    <button class="month-btn" onclick="showRanking('gennaio')">Gennaio</button>
                    <button class="month-btn" onclick="showRanking('febbraio')">Febbraio</button>
                    <button class="month-btn" onclick="showRanking('marzo')">Marzo</button>
                    <button class="month-btn" onclick="showRanking('aprile')">Aprile</button>
                    <button class="month-btn" onclick="showRanking('maggio')">Maggio</button>
                    <button class="month-btn" onclick="showRanking('giugno')">Giugno</button>
                    <button class="month-btn" onclick="showRanking('luglio')">Luglio</button>
                    <button class="month-btn" onclick="showRanking('agosto')">Agosto</button>
                    <button class="month-btn" onclick="showRanking('settembre')">Settembre</button>
                    <button class="month-btn" onclick="showRanking('ottobre')">Ottobre</button>
                    <button class="month-btn" onclick="showRanking('novembre')">Novembre</button>
                    <button class="month-btn" onclick="showRanking('dicembre')">Dicembre</button>
                </div>
                <div id="ranking-container" class="ranking-container">
                    <h3 id="ranking-title">Seleziona un mese per visualizzare la classifica</h3>
                    <div class="ranking-table-container">
                        <table id="ranking-table" class="ranking-table">
                            <thead>
                                <tr>
                                    <th>Posizione</th>
                                    <th>Username</th>
                                    <th>Punteggio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- I dati verranno inseriti dinamicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        // Dati di esempio per i grafici
        const progressData = {
            labels: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
            datasets: [{
                label: 'Punteggio',
                data: [65, 70, 75, 80, 85, 90],
                borderColor: '#2ecc71',
                tension: 0.4,
                fill: false
            }]
        };

        const pieData = {
            labels: ['< 60', '60-70', '70-80', '80-90', '> 90'],
            datasets: [{
                data: [10, 20, 30, 25, 15],
                backgroundColor: [
                    '#e74c3c',
                    '#f39c12',
                    '#3498db',
                    '#2ecc71',
                    '#9b59b6'
                ]
            }]
        };

        const improvementData = {
            labels: ['Principiante (0-30)', 'Intermedio (31-60)', 'Avanzato (61-80)', 'Esperto (81-100)'],
            datasets: [{
                data: [15, 35, 30, 20],
                backgroundColor: [
                    '#e74c3c',
                    '#f39c12',
                    '#3498db',
                    '#2ecc71'
                ]
            }]
        };

        // Configurazione dei grafici
        const progressConfig = {
            type: 'line',
            data: progressData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const pieConfig = {
            type: 'pie',
            data: pieData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        };

        const improvementConfig = {
            type: 'pie',
            data: improvementData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Distribuzione Livelli Utenti'
                    }
                }
            }
        };

        // Inizializzazione dei grafici
        window.onload = function() {
            showSection('statistiche');
            new Chart(document.getElementById('progressChart'), progressConfig);
            new Chart(document.getElementById('pieChart'), pieConfig);
            new Chart(document.getElementById('improvementChart'), improvementConfig);
        }
        //Pulsante di modifica password
        function togglePopup() {
            const popup = document.getElementById('changePasswordPopup');
            popup.classList.toggle('hidden');
        }
        window.onload = function () {
        showSection('statistiche');
        new Chart(document.getElementById('progressChart'), progressConfig);
        new Chart(document.getElementById('pieChart'), pieConfig);
        new Chart(document.getElementById('improvementChart'), improvementConfig);

        <?php if ($passwordError): ?>
            showSection('dati-personali');
            togglePopup();                
            const passwordError = "<?php echo $passwordError; ?>";
            if (passwordError === "current") {
                document.getElementById('currentPasswordError').textContent = "⚠️ Password attuale errata.";
            } else if (passwordError === "length") {
                document.getElementById('newPasswordError').textContent = "⚠️ La nuova password deve avere almeno 8 caratteri.";
            } else if (passwordError === "mismatch") {
                document.getElementById('confirmNewPasswordError').textContent = "⚠️ Le password non coincidono.";
            }
        <?php endif; ?>
        }

        // Gestione del form
        document.getElementById('personalDataForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Qui andrà il codice per salvare i dati nel database
            alert('Dati salvati con successo!');
        });

        function showRanking(month) {
            // Rimuovi la classe active da tutti i pulsanti
            document.querySelectorAll('.month-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Aggiungi la classe active al pulsante cliccato
            event.target.classList.add('active');
            
            // Aggiorna il titolo
            document.getElementById('ranking-title').textContent = `Classifica - ${month.charAt(0).toUpperCase() + month.slice(1)}`;
            
            // Chiamata AJAX per recuperare i dati dal database
            fetch(`get_ranking.php?month=${month}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Errore nel recupero della classifica: ' + data.error);
                    } else {
                        updateRankingTable(data);
                    }
                })
                .catch(error => {
                    alert('Errore nella comunicazione con il server: ' + error);
                });
        }

        function updateRankingTable(data) {
            const tbody = document.querySelector('#ranking-table tbody');
            tbody.innerHTML = '';
            
            data.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.position}</td>
                    <td>${user.username}</td>
                    <td>${user.score}</td>
                `;
                tbody.appendChild(row);
            });
        }
    </script>
</body>
</html> 