<?php
   session_start();
   $passwordError  = $_SESSION['error_message'] ?? null;
   $successMessage = $_SESSION['success_message'] ?? null;
   $month = $_SESSION['ranking-month'] ?? null;

   // Verifica se l'utente è autenticato
   if (!isset($_SESSION['username'])) {
       header("Location: ../Index/index.php");
       exit();
   }

   if (isset($_POST['month'])) {
        // Salva il mese selezionato nella sessione
        $_SESSION['ranking_month'] = $_POST['month'];
    }
   
   // Connessione al database FastWord
   $dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=rootpassword");

   //parte die da eliminare al massimo sostituire con un errore
   /*if (!$dbconn) {
       die("Errore di connessione al database");
   }*/

   // Recupera i dati dell'utente
   $query = "SELECT * FROM utentedati WHERE username = $1";
   $result = pg_query_params($dbconn, $query, array($_SESSION['username']));
   $userData = pg_fetch_assoc($result);

   // Recupera i punteggi medi di tutti gli utenti per il terzo grafico
   $query_all_scores = "SELECT punteggio_medio FROM utentedati";
   $result_all_scores = pg_query($dbconn, $query_all_scores);
   
   // Inizializza i contatori per ogni range
   $range_0_25 = 0;
   $range_26_50 = 0;
   $range_51_75 = 0;
   $range_76_100 = 0;
   $total_users = 0;

   // Calcola le percentuali per ogni range
   while ($row = pg_fetch_assoc($result_all_scores)) {
       $score = (float)$row['punteggio_medio'];
       if ($score >= 0 && $score <= 25) {
           $range_0_25++;
       } elseif ($score <= 50) {
           $range_26_50++;
       } elseif ($score <= 75) {
           $range_51_75++;
       } else {
           $range_76_100++;
       }
       $total_users++;
   }

   // Calcola le percentuali
   $percent_0_25 = ($range_0_25 / $total_users) * 100;
   $percent_26_50 = ($range_26_50 / $total_users) * 100;
   $percent_51_75 = ($range_51_75 / $total_users) * 100;
   $percent_76_100 = ($range_76_100 / $total_users) * 100;

   // Prepara i dati per il primo grafico
   $velocitaMesi = [
       'Gennaio' => (float)$userData['velocita_gennaio'],
       'Febbraio' => (float)$userData['velocita_febbraio'],
       'Marzo' => (float)$userData['velocita_marzo'],
       'Aprile' => (float)$userData['velocita_aprile'],
       'Maggio' => (float)$userData['velocita_maggio'],
       'Giugno' => (float)$userData['velocita_giugno'],
       'Luglio' => (float)$userData['velocita_luglio'],
       'Agosto' => (float)$userData['velocita_agosto'],
       'Settembre' => (float)$userData['velocita_settembre'],
       'Ottobre' => (float)$userData['velocita_ottobre'],
       'Novembre' => (float)$userData['velocita_novembre'],
       'Dicembre' => (float)$userData['velocita_dicembre']
   ];

   $precisione = (float)$userData['precisione'];
   $punteggioMedio = (float)$userData['punteggio_medio'];

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
        <!--statistiche-->
        <div class="content">
            <div id="statistiche" class="content-section">
                <h2>Statistiche</h2>
                <div class="charts-container">
                    <div class="chart-wrapper full-width">
                        <h3>Andamento Punteggi nel Tempo</h3>
                        <canvas id="progressChart"></canvas>
                    </div>
                    <div class="chart-wrapper">
                        <h3>Percentuale errori</h3>
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="chart-wrapper">
                        <h3>Distribuzione giocatori</h3>
                        <canvas id="improvementChart"></canvas>
                    </div>
                </div>
            </div>

            <!--dati personali-->
            <div id="dati-personali" class="content-section">
                <h2>Dati Personali</h2>
                <div id="successMessage" class="success-message hidden">Password cambiata con successo!</div>
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
                <!-- Overlay + Popup -->
                    <div id="popupOverlay" class="popup-overlay hidden">
                        <div class="popup">
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
                                    <button type="submit" class="modify-password-btn">Salva Modifiche</button>
                                    <button type="button" class="modify-password-btn" onclick="togglePopup()">Annulla</button>
                                </form>
                            </div>
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
                <form id="rankingForm" method="POST" action="get_ranking.php" onsubmit="showClassifiche();">
                    <div class="months-grid">
                        <?php 
                        $mesi = ['gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'];
                        foreach ($mesi as $mese): ?>
                            <button type="submit" name="month" value="<?= $mese ?>" class="month-btn"><?= ucfirst($mese) ?></button>
                        <?php endforeach; ?>
                    </div>
                </form>
                <div id="ranking-container" class="ranking-container">
                    <h3 id="ranking-title">Classifica - <?= $_SESSION['ranking_month'] ?? "Seleziona un mese" ?></h3>
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
                                <?php if (!empty($_SESSION['ranking_data'])): ?>
                                    <?php foreach ($_SESSION['ranking_data'] as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['position']) ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td><?= htmlspecialchars($row['score']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">
                                            <?= isset($_SESSION['ranking_error']) ? htmlspecialchars($_SESSION['ranking_error']) : "Seleziona un mese per visualizzare la classifica." ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                             </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showClassifiche() {
            // Mostra la sezione delle classifiche
            showSection('classifiche');
        }

        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        // Dati per i grafici dal database
        const velocitaMesi = <?php echo json_encode($velocitaMesi); ?>;
        const precisione = <?php echo $precisione; ?>;
        const punteggioMedio = <?php echo $punteggioMedio; ?>;

        // Configurazione del grafico di progresso
        const progressConfig = {
            type: 'line',
            data: {
                labels: Object.keys(velocitaMesi),
                datasets: [{
                    label: 'Velocità di Digitazione (WPM)',
                    data: Object.values(velocitaMesi),
                    borderColor: 'rgb(54, 173, 133)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Configurazione del grafico a torta
        const pieConfig = {
            type: 'doughnut',
            data: {
                labels: ['Precisione', 'Errori'],
                datasets: [{
                    data: [precisione, 100 - precisione],
                    backgroundColor: ['rgb(54, 173, 133)', '#e74c3c']
                }]
            },
            options: {
                responsive: true
            }
        };

        // Configurazione del grafico di percentuale
        const improvementConfig = {
            type: 'pie',
            data: {
                labels: [
                    'Principiante (0-25)',
                    'Intermedio (26-50)',
                    'Avanzato (51-75)',
                    'Esperto (76-100)'
                ],
                datasets: [{
                    data: [
                        <?php echo $percent_0_25; ?>,
                        <?php echo $percent_26_50; ?>,
                        <?php echo $percent_51_75; ?>,
                        <?php echo $percent_76_100; ?>
                    ],
                    backgroundColor: [
                        '#e74c3c',  
                        '#f39c12',  
                        '#3498db',  
                        '#2ecc71'   
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Distribuzione Livelli Utenti'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw.toFixed(1)}%`;
                            }
                        }
                    }
                }
            }
        };

        // Inizializzazione dei grafici
        new Chart(document.getElementById('progressChart'), progressConfig);
        new Chart(document.getElementById('pieChart'), pieConfig);
        new Chart(document.getElementById('improvementChart'), improvementConfig);

        //Pulsante di modifica password
        function togglePopup() {
            const overlay = document.getElementById('popupOverlay');
            overlay.classList.toggle('hidden');

            document.getElementById('currentPasswordError').textContent = '';
            document.getElementById('newPasswordError').textContent = '';
            document.getElementById('confirmNewPasswordError').textContent = '';
        }

        const passwordError = "<?php echo $passwordError ?? ''; ?>";
        const successMessage = "<?php echo $successMessage ?? ''; ?>";
        const month = "<?php echo $month ?? '' ; ?>";
        
    window.onload = function () {
        // Se c'è un errore nella password, mostriamo la sezione 'Dati Personali' con il popup
        <?php if ($passwordError): ?>
            showSection('dati-personali');
            togglePopup();
            if (passwordError === "current") {
                document.getElementById('currentPasswordError').textContent = "⚠️ Password attuale errata.";
            } else if (passwordError === "length") {
                document.getElementById('newPasswordError').textContent = "⚠️ La nuova password deve avere almeno 8 caratteri.";
            } else if (passwordError === "mismatch") {
                document.getElementById('confirmNewPasswordError').textContent = "⚠️ Le password non coincidono.";
            } else {
                alert("Errore: " + passwordError);
            }
            return;
        <?php endif; ?>   

        // Se c'è un messaggio di successo, mostriamo un messaggio nella sezione 'Dati Personali'
        <?php if ($successMessage): ?>
            showSection('dati-personali');
            const successBox = document.getElementById('successMessage');
            successBox.classList.remove('hidden');
            setTimeout(() => {
                successBox.classList.add('hidden');
            }, 5000); 
            return;
        <?php endif; ?>   

        // Gestisci la sezione predefinita da mostrare
        var defaultSection = "<?php echo isset($_SESSION['ranking_month']) ? 'classifiche' : 'statistiche'; ?>"; // Mostra "Classifiche" se un mese è selezionato
        showSection(defaultSection);

        // Inizializzazione dei grafici
        new Chart(document.getElementById('progressChart'), progressConfig);
        new Chart(document.getElementById('pieChart'), pieConfig);
        new Chart(document.getElementById('improvementChart'), improvementConfig);
    };


    </script>
</body>
</html> 
<?php
      unset($_SESSION['error_message'], $_SESSION['success_message']);
?>