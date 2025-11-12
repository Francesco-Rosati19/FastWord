    <?php
        session_start();
        if (!isset($_SESSION['login_error']) && !isset($_SESSION['register_error'])) {
        unset($_SESSION['old_register_data']);
        }
        $loginError = $_SESSION['login_error'] ?? null;
        $registerError = $_SESSION['register_error'] ?? null;
        $oldRegisterData = $_SESSION['old_register_data'] ?? [];


        unset($_SESSION['login_error'], $_SESSION['register_error']);
        
    ?>

    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FastWord</title>
        <link rel="stylesheet" href="styleIndex.css">
    </head>
    <body>
        <div class="container">
            <div class="titolo">
                <img src="logo.jpg" alt="Logo" class="logo">
            </div>
            <div class="bottoni">
                <a href="../Faq/faq.php"> FAQ </a>
                <a href="../Info/info.html"> CHI SIAMO</a>
                <a href="#" class="login"> LOGIN </a>
            </div>    
        </div>

        <div class="prova">
            <a href="../Prova/prova.html">SU! FACCIAMO UNA PROVA</a> 
        </div>

        <div class="box-container">
            <div class="box" id="box1">
                Imparare e Giocare.
                Con FastWord tutto è possibile. Il nostro obiettivo è quello di aiutarti a migliorare il tuo vocabolario 
                e le tue abilità linguistiche in modo divertente e coinvolgente.
            </div>
            <div class="box" id="box2">
                Digitare e ragionare contemporaneamente aiuta a migliorare le tue capacità cognitive e la tua velocità di pensiero.
                Con il nostro gioco, potrai anche migliorare la tua velocità di scrittura e la tua capacità di concentrazione 
            </div>
            <div class="box" id="box3"> 
                Arrivando primo nelle classifiche mensili c'è la possibilità di portarsi a casa un piccolo premio.
                Quindi impegnati e fai del tuo meglio per riuscire ad aggiudicarti il premio. Ricorda che però devi essere loggato
                e aver completato almeno 25 partite per provare a vincere
            </div>
                
        </div>

        <!--CONTAINER POPUP-->
        <div id="popup" class="popup hidden">
            <div class="popup-content">
                <span class="close" onclick="togglePopup()">&times;</span>
                <div class="tabs">
                    <button class="tab-button active" onclick="showTab('loginTab')">Accedi</button>
                    <button class="tab-button" onclick="showTab('registerTab')">Registrati</button>
                </div>
            
                <!-- LOGIN TAB -->
                <div id="loginTab" class="tab-content">
                    <form action="../Login/login.php" method="POST">
                        <input type="email" name="email" placeholder="Email" required><br>
                        <div id="loginEmailError" class="error-message"></div>
                        <input type="password" name="password" placeholder="Password" required><br>
                        <div id="loginPasswordError" class="error-message"></div>
                        <!-- Carica il client di Google Identity Services -->
                        <script src="https://accounts.google.com/gsi/client" async defer></script>

                        <!-- Pulsante di login con google -->
                        <div id="g_id_onload"
                            data-client_id="29896433443-op7hun0hru4088oloejcpoj1aui21s8u.apps.googleusercontent.com"
                            data-context="signin"
                            data-ux_mode="redirect"
                            data-login_uri="http://localhost:3000/Login/google-callback.php"
                            data-auto_prompt="false">
                        </div>

                        <div class="g_id_signin"
                            data-type="standard"
                            data-shape="rectangular"
                            data-theme="outline"
                            data-text="sign_in_with"
                            data-size="large">
                        </div>
                        <button type="submit">Accedi</button> 
                    </form>
                    <br>  
                        <a href="#" onclick="openModal()" style="margin-top:10px">Password dimenticata?</a>
                            <!-- Popup recupero password -->
                            <div id="myModal" class="modal">
                                <div class="modal-content">
                                    <span class="close" onclick="closeModal()">&times;</span>
                                    <h3>Recupera password</h3>
                                    <p>Inserisci la tua email:</p>
                                
                                    <form id="passwordForm" method="POST" action="../Login/password_dimenticata.php">
                                        <input type="email" name="email" id="emailInput" placeholder="Email" required>
                                        <br>
                                    <button type="submit">Invia</button>
                                    </form>
                                </div>
                            </div>             
                </div>
                        
                <!-- REGISTER TAB -->
                <div id="registerTab" class="tab-content hidden">
                    <form id="registerForm" action="../Login/register.php" method="POST">
                        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($oldRegisterData['username'] ?? '') ?>" required><br>
                        <div id="registerUsernameError" class="error-message"></div>
                        <input type="text" name="nome" placeholder="Nome" value="<?= htmlspecialchars($oldRegisterData['nome'] ?? '') ?>" required><br>
                        <input type="text" name="cognome" placeholder="Cognome" value="<?= htmlspecialchars($oldRegisterData['cognome'] ?? '') ?>" required><br>
                        <input type="date" name="data" placeholder="Data di nascita" value="<?= htmlspecialchars($oldRegisterData['data'] ?? '') ?>" required><br>
                        <div id="dataError" class="error-message"></div>
                        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($oldRegisterData['email'] ?? '') ?>" required><br>
                        <div id="registerEmailError" class="error-message"></div>

                    <!-- Password -->
                    <div class="password-wrapper"> 
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <span class="toggle-password" onclick="togglePassword('password', this)">👁️</span>
                    </div>
                    <div id="passwordError" class="error-message"></div>
                    <!-- Conferma Password -->
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Conferma Password" required>
                        <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁️</span>
                    </div>
                    <div id="confirmError" class="error-message"></div>
                    <button type="submit">Registrati</button>
                </form>
            </div>
        </div>
        </div>
        <script>
            function openModal() {
                document.getElementById("myModal").style.display = "block";
                document.getElementById("passwordForm").style.display = "block";
                document.getElementById("confirmation-message").style.display = "none";
                document.getElementById("emailInput").value = "";
            }

            function closeModal() {
                document.getElementById("myModal").style.display = "none";
            }

            // Chiudi cliccando fuori
            window.onclick = function(event) {
                var modal = document.getElementById("myModal");
                if (event.target == modal) {
                closeModal();
                }
            }

            document.querySelector('.login').addEventListener('click', function(event) {
                event.preventDefault();
                togglePopup();
            });
        
            function togglePopup() {
                document.getElementById('popup').classList.toggle('hidden');
            }
        
            function showTab(tabId) {
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
                document.getElementById(tabId).classList.remove('hidden');
        
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                const activeButton = Array.from(document.querySelectorAll('.tab-button')).find(btn =>
                    btn.textContent.toLowerCase().includes(tabId === 'loginTab' ? 'accedi' : 'registrati')
                );
                if (activeButton) activeButton.classList.add('active');
            }
            function togglePassword(id, icon) {
                const field = document.getElementById(id);
                if (field.type === "password") {
                    field.type = "text";
                    icon.textContent = "🙈";
                } else {
                    field.type = "password";
                    icon.textContent = "👁️";
                }
            }
            // Rimuove il messaggio di errore email quando l'utente modifica il campo email nel form di registrazione
            document.querySelector("#registerTab input[name='email']").addEventListener("input", function () {
                    document.getElementById('registerEmailError').textContent = "";
            });


            const loginError = "<?= $loginError ?>";
            const registerError = "<?= $registerError ?>";

            if (loginError) {
                togglePopup();
                showTab('loginTab');
                if(loginError === 'email')
                    document.getElementById('loginEmailError').textContent = "⚠️Email errata. Per favore, riprova.";
                else
                    document.getElementById('loginPasswordError').textContent = "⚠️Password errata. Per favore, riprova.";
            }
            if (registerError) {
                togglePopup();
                showTab('registerTab');
                if (registerError === 'duplicato_username')
                    document.getElementById('registerUsernameError').textContent = "⚠️ Username già registrato. Scegli un altro.";
                else
                    document.getElementById('registerEmailError').textContent = "⚠️ Email già registrata. Usa un'altra email.";
            }
            /*Controllo data e controllo password*/
            document.getElementById("registerForm").addEventListener("submit", function(e) {
                const dataNascita = new Date(this.data.value);
                const oggi = new Date();
                const eta = oggi.getFullYear() - dataNascita.getFullYear();
                const m = oggi.getMonth() - dataNascita.getMonth();
                const maggiorenne = (eta > 18 || (eta === 18 && (m > 0 || (m === 0 && oggi.getDate() >= dataNascita.getDate()))));
        
                if (!maggiorenne) {
                    e.preventDefault();
                    document.getElementById('dataError').textContent = "⚠️Devi avere almeno 18 anni per fare il login";
                    return;
                }
            document.getElementById('dataError').textContent = "";  

            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            let valid = true;

            if (password.length < 8) {
                document.getElementById('passwordError').textContent = "⚠️La password deve contenere almeno 8 caratteri.";
                valid = false;
            }

            if (password !== confirm) {
                document.getElementById('confirmError').textContent = "⚠️Le password non coincidono.";
                valid = false;
                }

                if (!valid) e.preventDefault(); // Blocca l'invio solo se ci sono errori

                
            });
            /*ICONE PASSWORD*/
            
        </script>
    </body>
    </html>