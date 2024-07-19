<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset de Contraseña - {{ config('app.name') }}</title>
</head>

<body>
    <p>
        Hola <strong>{{ $name }}</strong>,
        <br><br>
        Recibiste este correo electrónico porque recibimos una solicitud para restablecer la contraseña de tu cuenta.
        <br><br>
        Para continuar con el proceso de reseteo de contraseña, haz clic en el siguiente enlace:
        <br><br>
        <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
        <br><br>
        Si no solicitaste restablecer tu contraseña, puedes ignorar este correo electrónico de forma segura.
        <br><br>
        Gracias,
        <br>
        El equipo de {{ config('app.name') }}
    </p>
</body>

</html>