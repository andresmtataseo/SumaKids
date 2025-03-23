document.getElementById("loginForm").addEventListener("submit", function (event) {
    event.preventDefault();

    // Capturar valores del formulario
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let loginMessage = document.getElementById("loginMessage");

    // Verificar que los campos no estén vacíos
    if (email === "" || password === "") {
        loginMessage.innerHTML = '<div class="alert alert-danger" role="alert">Por favor, complete todos los campos.</div>';
        return;
    }

    // Enviar la petición al servidor
    fetch("http://localhost/SumaKids/api/Users.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: email, password: password })
    })
        .then(response => response.json()) // Obtener la respuesta como JSON
        .then(data => {
            console.log("Respuesta del servidor:", data); // Ver qué está devolviendo el servidor

            if (data.status === 200) {
                loginMessage.innerHTML = '<div class="alert alert-success">Inicio de sesión exitoso.</div>';
                setTimeout(() => { window.location.href = "juegos.html"; }, 1000);
            } else if (data.status === 401) {
                loginMessage.innerHTML = '<div class="alert alert-danger">Credenciales incorrectas.</div>';
            }
        })
        .catch(error => {
            console.error("Error en la respuesta JSON:", error);
            loginMessage.innerHTML = '<div class="alert alert-danger">Error en el servidor.</div>';
        });
});
