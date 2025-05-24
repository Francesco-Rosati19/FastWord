<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <title>Gioco di Scrittura Avanzato</title>
  <link rel="stylesheet" href="stylegioco.css" />
</head>
<body>
  <div class="container">
    <div class="navigation">
      <a href="../Index/index.php" class="nav-btn">Torna alla Home</a>
      <a href="../Profilo/profilo.php" class="nav-btn">Torna al Profilo</a>
    </div>

    <div class="game-header">
      <div class="dropdown">
        <select id="languageSelect">
          <option value="seleziona lingua" selected>Seleziona lingua</option>
          <option value="italiano">Italiano</option>
          <option value="inglese">Inglese</option>
          <option value="spagnolo">Spagnolo</option>
        </select>
      </div>
      <div class="dropdown">
        <select id="timerSelect">
          <option value="30">30 secondi</option>
          <option value="60" selected>60 secondi</option>
          <option value="90">90 secondi</option>
          <option value="1800">1800 secondi</option>
        </select>
      </div>
      <div class="dropdown">
        <select id="textSelect">
          <option value="">Seleziona un testo</option>
          <option value="testocarlo.txt">Testo Carlo</option>
          <option value="testoprova.txt">Testo Prova</option>
        </select>
      </div>
    </div>

    <div id="timer">⏳ Tempo: 60s</div>
    <div id="gameBox" onclick="inputArea.focus()"></div>
    <input type="text" id="inputArea" autofocus />
    <div id="result"></div>
    <button class="nav-btn" onclick="startGame()">Reset</button>
  </div>

  <script>
    const phrases = [
      "Il gatto salta sopra il tavolo.",
      "La tastiera è veloce e precisa.",
      "Oggi il cielo è sereno.",
      "Studiare ogni giorno aiuta a migliorare."
    ];

    const inputArea = document.getElementById("inputArea");
    const gameBox = document.getElementById("gameBox");
    const result = document.getElementById("result");
    const timerDisplay = document.getElementById("timer");

    let currentPhrase = "";
    let currentIndex = 0;
    let timeLeft = 60;
    let timer;
    let userInput = [];
    let errorCount = 0;
    let time = 0;

    // Funzione per resettare il timer e il display
    function resetTimerDisplay() {
      timeLeft = parseInt(document.getElementById("timerSelect").value);
      timerDisplay.textContent = `⏳ Tempo: ${timeLeft}s`;
      clearInterval(timer); // Ferma il timer se era in esecuzione
      time = 0; // Reset per permettere un nuovo avvio del timer
    }

    // Funzione per resettare l'input e le statistiche
    function resetInputAndStats() {
      currentIndex = 0;
      userInput = [];
      errorCount = 0; //resetta il numero di errori
      result.textContent = "";
      inputArea.value = "";
      inputArea.disabled = false;
      updateDisplay();
      inputArea.focus();
    }

    // Funzione per caricare una frase casuale
    function loadRandomPhrase() {
      currentPhrase = phrases[Math.floor(Math.random() * phrases.length)];
      resetInputAndStats();
    }

    function startGame() {
      loadRandomPhrase();
      resetTimerDisplay();
    }

    document.getElementById("textSelect").addEventListener("change", function () {
      resetTimerDisplay();
      const fileName = this.value;
      if (!fileName) return;

      fetch(`texts/${fileName}`)
        .then(response => {
          if (!response.ok) throw new Error("File non trovato");
          return response.text();
        })
        .then(text => {
          currentPhrase = text.trim();
          resetInputAndStats();
        })
        .catch(err => {
          result.innerHTML = `⚠️ Errore nel caricamento del file: ${err.message}`;
        });
    });

    //listener per menù timer
    document.getElementById("timerSelect").addEventListener("change", () => {
      resetTimerDisplay();
      loadRandomPhrase();
    });

    //listener per menù lingua
    document.getElementById("languageSelect").addEventListener("change", function () {
      resetTimerDisplay();
      inputArea.disabled = true;
      const selectedLang = this.value;
      const textSelect = document.getElementById("textSelect");

      textSelect.innerHTML = '<option value="">Caricamento...</option>';
      textSelect.disabled = true;

      fetch(`texts/getTextFiles.php?lingua=${selectedLang}`)
        .then(response => {
          if (!response.ok) throw new Error("Errore nel recupero dei file");
          return response.json();
        })
        .then(files => {
          textSelect.innerHTML = '<option value="">Seleziona un testo</option>';
          files.forEach(file => {
            const option = document.createElement("option");
            option.value = `${selectedLang}/${file}`;
            option.textContent = file.replace(".txt", "");
            textSelect.appendChild(option);
          });
          textSelect.disabled = false;
        })
        .catch(err => {
          textSelect.innerHTML = '<option value="">Errore nel caricamento</option>';
          console.error("Errore:", err);
        });
    });
    
    // Funzione per aggiornare la visualizzazione del testo
    function updateDisplay() {
      let html = "";

      for (let i = 0; i < currentPhrase.length; i++) {
        const expectedChar = currentPhrase[i];
        const typedChar = userInput[i];

        if (i < currentIndex) {
          const className = typedChar === expectedChar ? 'correct' : 'incorrect';
          html += `<span class="${className}">${expectedChar}</span>`;
        } else if (i === currentIndex) {
          const isCorrect = typedChar === expectedChar;
          const cursorClass = isCorrect ? 'cursor correct' : 'cursor incorrect';
          html += `<span id="activeChar" class="${cursorClass}">${expectedChar}</span>`;
        } else {
          html += `<span>${expectedChar}</span>`;
        }
      }

      gameBox.innerHTML = html;

      //scorrimento verso il cursore
      const activeChar = document.getElementById("activeChar");
      if (activeChar) {
        activeChar.scrollIntoView({ 
            behavior: "auto",
            block: "nearest",
            inline: "nearest" 
        });
      }
    }

    // Funzione per avviare il timer
    function startTimer() {
      clearInterval(timer);
      timer = setInterval(() => {
        timeLeft--;
        timerDisplay.textContent = `⏳ Tempo: ${timeLeft}s`;

        if (timeLeft <= 0) {
          clearInterval(timer);
          inputArea.disabled = true;
          showResults();
        }
      }, 1000);
    }

    // Funzione per mostrare i risultati finali
    function showResults() {
      const correctChars = userInput.filter((char, idx) => char === currentPhrase[idx]).length;
      const totalTyped = correctChars + errorCount;
      const accuracy = totalTyped === 0 ? 0 : Math.round((correctChars / totalTyped) * 100);

      const totalTime = parseInt(document.getElementById("timerSelect").value);
      const timeUsed = totalTime - timeLeft;
      const minutes = timeUsed / 60;
      const wpm = minutes > 0 ? Math.round((correctChars / 5) / minutes) : 0;

      result.innerHTML = `
        ⏱️ Tempo scaduto!<br>
        ✅ Precisione: ${accuracy}%<br>
        ✍️ Caratteri corretti: ${correctChars} / ${currentPhrase.length}<br>
        ❌ Errori: ${errorCount}<br>
        🚀 Velocità: ${wpm} parole/minuto
      `;
    }

    // Listener per l'input dell'utente
    inputArea.addEventListener("keydown", (e) => {
      e.preventDefault(); // impedosce la scrittura diretta nel campo
      if (time === 0) {
        startTimer();
        time++;
      }

      const expectedChar = currentPhrase[currentIndex];
      if (e.key.length === 1) {  
        if (e.key === expectedChar) {
          userInput[currentIndex] = e.key;
          currentIndex++;
        } else {
          userInput[currentIndex] = e.key; //sovrascrive l'errore precedente
          errorCount++;
        }
      }

      updateDisplay();

      if (userInput.join("") === currentPhrase) {
        clearInterval(timer);
        result.innerHTML = "🎉 Frase completata correttamente!";
        inputArea.disabled = true;
        showResults();
      }
    });

    window.onload = startGame;
  </script>
</body>
</html>
