<?php
// index.php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- iOS Web App Meta -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Date Ideas">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="color-scheme" content="dark light">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">

    <title>Date Ideas</title>
    <!-- Google Font for mobile screens -->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;700&display=swap" rel="stylesheet">

    <!-- (Optional) Android/Chrome PWA manifest -->
    <link rel="manifest" href="/manifest.json">
    <!-- External CSS -->
    <link rel="stylesheet" href="style.css">
    <style>
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        @keyframes shake {
            0% { transform: translateX(0); }
            20% { transform: translateX(-10px); }
            40% { transform: translateX(10px); }
            60% { transform: translateX(-10px); }
            80% { transform: translateX(10px); }
            100% { transform: translateX(0); }
        }
        .fade-out { animation: fadeOut 1s forwards; }
        .shake    { animation: shake 0.5s; }
    </style>


</head>
<body>
<!-- Dark mode toggle icon -->
<button class="theme-toggle" id="themeToggle" onclick="toggleTheme()">🌙</button>

<header>
    <h1>Date Ideas Hub</h1>
</header>

<!-- Visible Logout Button -->
<div style="text-align: right; width: 100%; margin-bottom: 10px;">
    <a href="logout.php" style="text-decoration: none; background: var(--accent-color); color: #fff; padding: 8px 16px; border-radius: 5px;">Logout</a>
</div>

<!-- Idea creation form -->
<div class="container" id="createIdeaSection">
    <h2>Create a Date Idea</h2>
    <form id="ideaForm">
        <label for="ideaTitle">Title/Description:</label>
        <textarea id="ideaTitle" rows="2" placeholder="Describe your date idea..." required></textarea>

        <!-- Money slider -->
        <label for="money">Money:</label>
        <input type="range" id="money" min="1" max="5" value="3"
               oninput="document.getElementById('moneyOutput').textContent = '💰'.repeat(this.value)">
        <div class="slider-labels" id="moneyOutput">💰💰💰</div>

        <!-- Time slider -->
        <label for="time">Time:</label>
        <input type="range" id="time" min="1" max="5" value="3"
               oninput="document.getElementById('timeOutput').textContent = '⏳'.repeat(this.value)">
        <div class="slider-labels" id="timeOutput">⏳⏳⏳</div>

        <!-- Energy slider -->
        <label for="energy">Energy:</label>
        <input type="range" id="energy" min="1" max="5" value="3"
               oninput="document.getElementById('energyOutput').textContent = '⚡'.repeat(this.value)">
        <div class="slider-labels" id="energyOutput">⚡⚡⚡</div>

        <!-- Time of Day slider for creation -->
        <label for="timeOfDay">Time of Day:</label>
        <input type="range" id="timeOfDay" min="1" max="3" value="2"
               oninput="document.getElementById('timeOfDayOutput').textContent = getTimeOfDayEmoji(this.value)">
        <div class="slider-labels" id="timeOfDayOutput">❓</div>

        <button type="submit">Add Date Idea</button>
    </form>
</div>

<!-- Search form -->
<div class="container" id="searchSection">
    <h2>Search Date Ideas</h2>
    <form id="searchForm">
        <!-- Money slider -->
        <label for="searchMoney">Your Available Money:</label>
        <input type="range" id="searchMoney" min="1" max="5" value="3"
               oninput="document.getElementById('searchMoneyOutput').textContent = '💰'.repeat(this.value)">
        <div class="slider-labels" id="searchMoneyOutput">💰💰💰</div>

        <!-- Time slider -->
        <label for="searchTime">Your Available Time:</label>
        <input type="range" id="searchTime" min="1" max="5" value="3"
               oninput="document.getElementById('searchTimeOutput').textContent = '⏳'.repeat(this.value)">
        <div class="slider-labels" id="searchTimeOutput">⏳⏳⏳</div>

        <!-- Energy slider -->
        <label for="searchEnergy">Your Available Energy:</label>
        <input type="range" id="searchEnergy" min="1" max="5" value="3"
               oninput="document.getElementById('searchEnergyOutput').textContent = '⚡'.repeat(this.value)">
        <div class="slider-labels" id="searchEnergyOutput">⚡⚡⚡</div>

        <!-- Time of Day slider for search: only day or night (step 2) -->
        <label for="searchTimeOfDay">Preferred Time of Day:</label>
        <input type="range" id="searchTimeOfDay" min="1" max="3" step="2" value="3"
               oninput="document.getElementById('searchTimeOfDayOutput').textContent = getTimeOfDayEmoji(this.value)">
        <div class="slider-labels" id="searchTimeOfDayOutput">☀️</div>

        <button type="submit">Search Ideas</button>
    </form>

    <div id="results">
        <h3>Results:</h3>
        <div id="ideasList"></div>
    </div>
</div>

<!-- External JavaScript -->
<script src="script.js"></script>
</body>
</html>
