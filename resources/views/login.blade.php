<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body>
<h1>Ingresar</h1>

@if ($errors->any())
    <div style="color: red;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
<form action="{{ route('login.process', $event) }}" method="POST">
    @csrf
    <label>
        Código de Invitación:
        <input type="text" name="code" required>
    </label>
    <button type="submit">Entrar</button>
</form>
</body>
</html>
