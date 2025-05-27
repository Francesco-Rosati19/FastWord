<?php
session_start();

$message = $_SESSION['message'] ?? '';
$message_color = $_SESSION['message_color'] ?? 'green';

unset($_SESSION['message'], $_SESSION['message_color']);
?>
<!DOCTYPE html>
<html lang ="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FAQ</title>
    <link rel="stylesheet" href="styleFaq.css" />
</head>
<body>

    <div class="altoadestra">
        <a href="../Index/index.php">HOME</a>
    </div>
    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Domande più frequenti</th>
                </tr>
            </thead>
            <tbody>
                <tr tabindex="0" class="question">
                    <td>
                        1. Cos'è questo gioco di scrittura?
                        <div class="answer" style="display:none;">
                            Questo è un gioco educativo pensato per migliorare la velocità e la precisione nella digitazione. Puoi scegliere testi in diverse lingue, impostare un timer e allenarti digitando le frasi mostrate.
                        </div>
                    </td>
                </tr>
                <tr tabindex="0" class="question">
                    <td>
                        2. Posso usare la tastiera del telefono o del tablet?
                        <div class="answer" style="display:none;">
                            Sì, il gioco è compatibile con dispositivi mobili. Tuttavia, per un'esperienza ottimale consigliamo l’uso di una tastiera fisica.
                        </div>
                    </td>
                </tr>
                <tr tabindex="0" class="question">
                    <td>
                        3. Come vengono calcolati i risultati?
                        <div class="answer" style="display:none;">
                            I risultati vengono calcolati in base alla velocità di digitazione (parole al minuto) e alla precisione (percentuale di errori). Puoi visualizzare le statistiche dopo ogni sessione di gioco.
                        </div>
                    </td>
                </tr>
                <tr tabindex="0" class="question">
                    <td>
                        4. Cosa succede se faccio errori durante la digitazione?
                        <div class="answer" style="display:none;">
                            Se fai errori, il gioco li evidenzierà e sottrarrà punti dalla tua precisione. Tuttavia, puoi correggere gli errori mentre digiti.
                        </div>
                    </td>
                </tr>
                <tr tabindex="0" class="question">
                    <td>
                        5. Perché il timer non parte quando digito?
                        <div class="answer" style="display:none;">
                            Il timer inizia a contare solo dopo che hai iniziato a digitare il primo carattere. Assicurati di premere un tasto per avviare il timer.
                        </div>
                    </td>
                </tr>
                <tr tabindex="0" class="question">
                    <td>
                        6. Ho trovato un errore o ho un suggerimento. Come posso contattarvi?
                        <div class="answer" style="display:none;">
                            Puoi contattarci tramite il nostro modulo di contatto disponibile alla fine della pagina. Apprezziamo i tuoi feedback e suggerimenti per migliorare il gioco.
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        const questions = document.querySelectorAll('.question');

        questions.forEach(q => {
            q.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    toggleAnswer(this);
                }
            });

            q.addEventListener('click', function() {
                toggleAnswer(this);
            });
        });

        function toggleAnswer(selectedRow) {
            questions.forEach(row => {
                const answer = row.querySelector('.answer');
                if (row !== selectedRow && answer) {
                    answer.style.display = 'none';
                }
            });
            const answer = selectedRow.querySelector('.answer');
            if (answer) {
                answer.style.display = (answer.style.display === 'block') ? 'none' : 'block';
            }
        }
    </script>
     <div>
        <form class="ask-box" action="salva_messaggio.php" method="POST">
            <textarea id="userQuestion" name="userQuestion" placeholder="Scrivi qui il tuo suggerimento o l'errore trovato" rows="4" required></textarea>
            <input type="text" id="email" name="email" placeholder="Inserisci la tua email per ricevere una risposta" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" />
            <div id="message" style="text-align:center; font-size:16px; color: <?= htmlspecialchars($message_color) ?>; min-height: 24px;">
                <?= htmlspecialchars($message) ?>
            </div>
            <button type="submit">Invia</button>
        </form>
        
    </div>

    
</body>
</html>
