// Function to map "Time of Day" slider values to emojis.
// 1 → Night (🌙), 2 → Not Important (❓), 3 → Day (☀️)
function getTimeOfDayEmoji(value) {
    const val = parseInt(value, 10);
    switch (val) {
        case 1: return '🌙';
        case 2: return '❓';
        case 3: return '☀️';
        default: return '❓';
    }
}

// Store date ideas (each idea's ratings are numeric values).
let dateIdeas = [];

// Function to display ideas.
// Function to display ideas.
function displayIdeas(ideas = []) {
    // If ideas isn't an array, default to an empty array.
    ideas = Array.isArray(ideas) ? ideas : [];

    const ideasList = document.getElementById("ideasList");
    ideasList.innerHTML = "";

    if (ideas.length === 0) {
        ideasList.innerHTML = "<p>No ideas found matching your criteria.</p>";
        return;
    }

    ideas.forEach((idea, index) => {
        const ideaDiv = document.createElement("div");
        ideaDiv.className = "idea";

        // Create info section with title, scores, and time of day.
        const infoDiv = document.createElement("div");
        infoDiv.className = "info";
        infoDiv.innerHTML = `
          <strong>${idea.title}</strong>
          <div class="scores">
            Money: ${'💰'.repeat(idea.money)},
            Time: ${'⏳'.repeat(idea.time)},
            Energy: ${'⚡'.repeat(idea.energy)},
            Time of Day: ${getTimeOfDayEmoji(idea.timeOfDay)}
          </div>
        `;

        // Create delete button (assumes each idea has a unique id).
        const deleteButton = document.createElement("button");
        deleteButton.className = "delete-button";
        deleteButton.textContent = "X";
        deleteButton.onclick = () => deleteIdea(idea.id);

        ideaDiv.appendChild(infoDiv);
        ideaDiv.appendChild(deleteButton);
        ideasList.appendChild(ideaDiv);
    });
}

// Function to load ideas from the server.
function loadIdeas() {
    fetch('get_date_ideas.php')
        .then(response => response.json())
        .then(data => {
            // Ensure that data is an array; if not, set it to an empty array.
            dateIdeas = Array.isArray(data) ? data : [];
            displayIdeas(dateIdeas);
        })
        .catch(error => {
            console.error('Error loading ideas:', error);
            dateIdeas = [];
            displayIdeas(dateIdeas);
        });
}

// Load ideas on page load.
window.addEventListener('load', loadIdeas);

// Handle idea creation.
document.getElementById("ideaForm").addEventListener("submit", function(e) {
    e.preventDefault();

    // Ensure dateIdeas is an array.
    if (!Array.isArray(dateIdeas)) {
        dateIdeas = [];
    }

    const title = document.getElementById("ideaTitle").value;
    const money = parseInt(document.getElementById("money").value, 10);
    const time = parseInt(document.getElementById("time").value, 10);
    const energy = parseInt(document.getElementById("energy").value, 10);
    const timeOfDay = parseInt(document.getElementById("timeOfDay").value, 10);

    const newIdea = { title, money, time, energy, timeOfDay };

    fetch('save_date_ideas.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newIdea)
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === "success") {
                // Reload the ideas instead of using result.ideas.
                loadIdeas();
                alert("Date idea added!");
                document.getElementById("ideaForm").reset();
                document.getElementById("moneyOutput").textContent = '💰'.repeat(3);
                document.getElementById("timeOutput").textContent = '⏳'.repeat(3);
                document.getElementById("energyOutput").textContent = '⚡'.repeat(3);
                document.getElementById("timeOfDayOutput").textContent = getTimeOfDayEmoji(2);
            } else {
                alert("Error: " + result.message);
            }
        })
        .catch(error => {
            console.error('Error saving idea:', error);
            // Make sure to reset dateIdeas to an empty array on error.
            dateIdeas = [];
            displayIdeas(dateIdeas);
        });
});

// Handle deletion of an idea.
function deleteIdea(id) {
    if (!confirm("Are you sure you want to delete this idea?")) return;

    fetch('delete_date_idea.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ idea_id: id })
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === "success") {
                // Reload the ideas after deletion.
                loadIdeas();
            } else {
                alert("Error: " + result.message);
            }
        })
        .catch(error => {
            console.error('Error deleting idea:', error);
            // Reset dateIdeas to avoid further errors.
            dateIdeas = [];
            displayIdeas(dateIdeas);
        });
}

// Handle search.
document.getElementById("searchForm").addEventListener("submit", function(e) {
    e.preventDefault();

    // Ensure dateIdeas is defined as an array before filtering.
    if (!Array.isArray(dateIdeas)) {
        dateIdeas = [];
    }

    const availableMoney = parseInt(document.getElementById("searchMoney").value, 10);
    const availableTime = parseInt(document.getElementById("searchTime").value, 10);
    const availableEnergy = parseInt(document.getElementById("searchEnergy").value, 10);
    const searchTimeOfDay = parseInt(document.getElementById("searchTimeOfDay").value, 10);

    const filteredIdeas = dateIdeas.filter(idea =>
        idea.money <= availableMoney &&
        idea.time <= availableTime &&
        idea.energy <= availableEnergy &&
        (idea.timeOfDay === 2 || idea.timeOfDay === searchTimeOfDay)
    );
    displayIdeas(filteredIdeas);
});

// Dark mode toggle functionality.
function toggleTheme() {
    if (document.body.classList.contains('dark-mode')) {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
        document.getElementById('themeToggle').textContent = "🌙";
    } else {
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
        document.getElementById('themeToggle').textContent = "☀️";
    }
}

// Set initial theme based on saved preference or system default.
function setInitialTheme() {
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme) {
        if (storedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            document.getElementById('themeToggle').textContent = "☀️";
        } else {
            document.body.classList.remove('dark-mode');
            document.getElementById('themeToggle').textContent = "🌙";
        }
    } else {
        // Use browser preference.
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark) {
            document.body.classList.add('dark-mode');
            document.getElementById('themeToggle').textContent = "☀️";
            localStorage.setItem('theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            document.getElementById('themeToggle').textContent = "🌙";
            localStorage.setItem('theme', 'light');
        }
    }
}

// Run initial theme setting.
setInitialTheme();
