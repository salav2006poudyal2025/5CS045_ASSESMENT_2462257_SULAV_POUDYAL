// Run the script only after the HTML page is fully loaded
document.addEventListener("DOMContentLoaded", function () {

    // Get input and display elements from the page
    const search = document.getElementById("search");       
    const category = document.getElementById("category");   
    const year = document.getElementById("year");           
    const results = document.getElementById("results");    

    // Function to prevent XSS by converting special characters to safe HTML
    function escapeHTML(text) {
        if (!text) return "";
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Function to fetch books from the server using AJAX (Fetch API)
    function fetchBooks() {

        // Base URL for AJAX search
        let url = "search.php?ajax=1";

        // Add search keyword if user typed something
        if (search.value.trim()) {
            url += "&search=" + encodeURIComponent(search.value.trim());
        }

        // Add selected category if chosen
        if (category.value) {
            url += "&category=" + encodeURIComponent(category.value);
        }

        // Add year filter if entered
        if (year.value) {
            url += "&year=" + encodeURIComponent(year.value);
        }

        // Send AJAX request to the server
        fetch(url)
            .then(res => res.json()) // Convert response to JSON
            .then(data => {

                // If no books are found, show message
                if (!data.length) {
                    results.innerHTML = "<p>No books found.</p>";
                    return;
                }

                // Build HTML list for search results
                let html = "<ul>";
                data.forEach(book => {
                    html += `<li>
                        <strong>${escapeHTML(book.title)}</strong>
                        (${escapeHTML(book.category)}, ${escapeHTML(book.year)})
                    </li>`;
                });
                html += "</ul>";

                // Display results on the page
                results.innerHTML = html;
            })
            .catch(() => {
                // Show error message if something goes wrong
                results.innerHTML = "<p>Error loading data.</p>";
            });
    }

    // Call fetchBooks whenever user types in search box
    search.addEventListener("input", fetchBooks);

    // Call fetchBooks when category changes
    category.addEventListener("change", fetchBooks);

    // Call fetchBooks when year changes
    year.addEventListener("change", fetchBooks);

    // Load all books when the page loads for the first time
    fetchBooks();
});
