document.addEventListener("DOMContentLoaded", function () {
    console.log("Signup page loaded");

    document.getElementById("registroForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Evita el envío por defecto
        console.log("Signup form submitted");

        // Capturar datos del formulario
        let nombre = document.getElementById("nombre").value.trim();
        let apellido = document.getElementById("apellido").value.trim();
        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("password").value.trim();

        // Validación básica
        if (!nombre || !apellido || !email || !password) {
            alert("Por favor complete todos los campos.");
            return;
        }

        let formData = {
            nombre: nombre,
            apellido: apellido,
            email: email,
            password: password
        };

        console.log(formData);

        // Enviar datos al servidor
        fetch("http://localhost/SumaKids/api/Users.php", { // Modificar cuando subas el proyecto
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => {
                const responseMessage = document.getElementById("responseMessage");
                if (data.status === "SUCCESS") {
                    alert("Registro exitoso. Redirigiendo...");
                    window.location.href = "signin.html"; // Redirigir al login
                } else {
                    responseMessage.innerHTML = `<p class="text-danger">Error: ${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                document.getElementById("responseMessage").innerHTML = `<p class="text-danger">Error: No se pudo procesar la solicitud. Por favor, intente nuevamente más tarde.</p>`;
            });
    });
});