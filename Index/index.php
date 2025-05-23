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
            <a href="../Gioco/gioco.php"> GIOCO </a>
            <a href="../Faq/faq.html"> FAQ </a>
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
            Con FastWord tutto è possibile. Vincere premi e portare a casa un pò di soldi non fa male a nessuno.
            Non fartelo raccontare ma provalo in prima persona
        </div>
        <div class="box" id="box2">
            Ciao
            Digitare e ragionare allo stesso momento aiuta il ragionamento.
            Il numero di persone che abbiamo aiutato negli ultimi anni
            si aggira intorno ai 2000 e il 90% delle persone sono 
            soddisfatte di aver imparato e giocato con noi
        </div>
        <div class="box" id="box3"> BOX 3</div>
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
                    <button type="submit">Accedi</button>
                </form>
            </div>

            <!-- REGISTER TAB -->
            <div id="registerTab" class="tab-content hidden">
                <form id="registerForm" action="../Login/register.php" method="POST">
                    <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($oldRegisterData['username'] ?? '') ?>" required><br>
                    <div id="registerUsernameError" class="error-message"></div>
                    <input type="text" name="nome" placeholder="Nome" value="<?= htmlspecialchars($oldRegisterData['nome'] ?? '') ?>" required><br>
                    <input type="text" name="cognome" placeholder="Cognome" value="<?= htmlspecialchars($oldRegisterData['cognome'] ?? '') ?>" required><br>
                    <input type="date" name="data" placeholder="Data di nascita" value="<?= htmlspecialchars($oldRegisterData['data'] ?? '') ?>" required><br>
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
        /*Controllo data e controllo password*/
        document.getElementById("registerForm").addEventListener("submit", function(e) {
            const dataNascita = new Date(this.data.value);
            const oggi = new Date();
            const eta = oggi.getFullYear() - dataNascita.getFullYear();
            const m = oggi.getMonth() - dataNascita.getMonth();
            const maggiorenne = (eta > 18 || (eta === 18 && (m > 0 || (m === 0 && oggi.getDate() >= dataNascita.getDate()))));
    
            if (!maggiorenne) {
                e.preventDefault();
                alert("Devi avere almeno 18 anni per registrarti.");
                return;
            }
    
           const password = document.getElementById('password').value;
           const confirm = document.getElementById('confirm_password').value;
           let valid = true;

           if (password.length < 8) {
              document.getElementById('passwordError').textContent = "La password deve contenere almeno 8 caratteri.";
              valid = false;
           }

           if (password !== confirm) {
               document.getElementById('confirmError').textContent = "Le password non coincidono.";
               valid = false;
            }

            if (!valid) e.preventDefault(); // Blocca l'invio solo se ci sono errori

            
        });
        /*ICONE PASSWORD*/
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
    </script>
</body>
</html>