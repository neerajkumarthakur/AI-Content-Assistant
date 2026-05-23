async function generateContent() {
    const input = document.getElementById("userInput").value;
    const result = document.getElementById("result");
    const loading = document.getElementById("loading");

    if (!input.trim()) {
        alert("Please enter text");
        return;
    }

    loading.innerHTML = "Generating response...";

    try {
        const response = await fetch("./templates/api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ text: input })
        });

        const data = await response.json();

        result.innerHTML = data.response;
    } catch (error) {
        result.innerHTML = "Something went wrong";
    }

    loading.innerHTML = "";
}