<?php include 'procesos/chat.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="css/chat.css"> <!-- Enlace a tu archivo CSS -->

</head>
<body>
    <header>
        <h1>Chat con Vendedor</h1>
    </header>
    <main>
        <div class="chat-container">
            <div class="messages">
                <?php foreach ($mensajes as $mensaje): ?>
                <div class="message <?php echo ($mensaje['id_usuario'] === $id_usuario) ? 'sent' : 'received'; ?>">
                    <div class="usuario"><?php echo htmlspecialchars($mensaje['id_usuario'] === $id_usuario ? $mensaje['nombre_usuario'] : $mensaje['nombre_vendedor']); ?></div>
                    <div class="mensaje"><?php echo htmlspecialchars($mensaje['mensaje']); ?></div>
                    <div class="fecha"><?php echo htmlspecialchars($mensaje['fecha']); ?></div>
                    <?php if ($mensaje['id_usuario'] !== $id_usuario): ?>
                    <div class="leido"><?php echo $mensaje['leido'] ? 'Leído' : 'No leído'; ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <form class="message-input" method="post">
                <input type="text" name="mensaje" placeholder="Escribe un mensaje..." required>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </main>
</body>
</html>
