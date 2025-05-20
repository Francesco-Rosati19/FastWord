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
    let errorCount=0;
    let time=0;
    
  function startGame() {
  currentPhrase = phrases[Math.floor(Math.random() * phrases.length)];
  currentIndex = 0;
  
  const selectedTime = document.getElementById("timerSelect").value;
  timeLeft = parseInt(selectedTime);
  timerDisplay.textContent = `⏳ Tempo: ${timeLeft}s`;

  inputArea.value = "";
  inputArea.disabled = false;
  result.textContent = "";
  userInput = [];
  errorCount = 0; //reset numero errori
  time = 0; // Reset per permettere un nuovo avvio del timer

  updateDisplay();
  inputArea.focus();
}

//listener per cambiare e selezionare testo
document.getElementById("textSelect").addEventListener("change", function () {
  const fileName = this.value;
  if (!fileName) return;

  fetch(`texts/${fileName}`)
    .then(response => {
      if (!response.ok) {
        throw new Error("File non trovato");
      }
      return response.text();
    })
    .then(text => {
      currentPhrase = text.trim();
      currentIndex = 0;
      userInput = [];
      errorCount = 0;
      result.textContent = "";
      inputArea.value = "";
      time = 0;
      updateDisplay();
      inputArea.disabled = false;
      inputArea.focus();
      timerDisplay.textContent = `⏳ Tempo: ${timeLeft}s`;
    })
    .catch(err => {
      result.innerHTML = `⚠️ Errore nel caricamento del file: ${err.message}`;
    });
});



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
          html += `<span class="${cursorClass}">${expectedChar}</span>`;
        } else {
          html += `<span>${expectedChar}</span>`;
        }
      }

      gameBox.innerHTML = html;
    }

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
    //listener menu a tendina timer
    document.getElementById("timerSelect").addEventListener("change", () => {
  const selectedTime = parseInt(document.getElementById("timerSelect").value);
  timeLeft = selectedTime;
  timerDisplay.textContent = `⏳ Tempo: ${timeLeft}s`;

  // Reset del contatore interno per impedire l'avvio automatico del timer
  time = 0;
});



    function showResults() {
      const correctChars = userInput.filter((char, idx) => char === currentPhrase[idx]).length;
      const accuracy = Math.round((correctChars / currentPhrase.length) * 100);
      result.innerHTML = `⏱️ Tempo scaduto!<br> Precisione: ${accuracy}%<br> Caratteri corretti: ${correctChars} / ${currentPhrase.length} <br> ❌ Errori: ${errorCount}`;
    }

    inputArea.addEventListener("keydown", (e) => {
      e.preventDefault(); // impedisce la scrittura diretta nel campo
      if(time==0){
         startTimer();
         time++;
      }
      const expectedChar = currentPhrase[currentIndex];

      if (e.key.length === 1) { // Solo caratteri stampabili
        if (e.key === expectedChar) {
          userInput[currentIndex] = e.key;
          currentIndex++;
        } else {
          userInput[currentIndex] = e.key; // Sovrascrive l'errore precedente
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