<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocDrive</title>
</head>

<body>
    <p>
        <strong>Hola {{ $name }}</strong>,

        ¡Bienvenido a nuestra aplicación! Tu cuenta ha sido creada con éxito.

        <br>
        A continuación, encontrarás tus credenciales de inicio de sesión:

        <br>
        <b>
            Correo electrónico: {{ $email }}
            Contraseña: {{ $password }}
        </b>
        <br>
        Te recomendamos que cambies tu contraseña después de iniciar sesión por primera vez.
        <br>
        Gracias por unirte a nosotros.
        <br>
        Saludos,
        <br>
        El equipo de {{ config('app.name') }}
    </p>
</body>

</html>