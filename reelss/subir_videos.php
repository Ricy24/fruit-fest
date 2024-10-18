
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Subir videos</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="styles.css" />
    <!-- BOX ICONS LINK  -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  </head>
  <body>
    <!-- HEADER  -->
    <div class="header">
      <div class="align-element">
        <!-- LOGO  -->
        <a href="index.php" class="logo">
          <h4>Fruit fest</h4>
        </a>
        <!-- SEARCH INPUT  -->
        <div class="search-input">
          <div class="input-box">
            <input type="text" placeholder="Search" />
            <button><i class="bx bx-search"></i></button>
          </div>
        </div>
        <!-- SMART ICONS  -->
        <div class="smart-icons">
          <button class="upload-btn">
            <i class="bx bx-plus"></i>
            Upload
          </button>
          <span><i class="bx bx-send"></i></span>
          <span class="notification">
            <i class="bx bx-message-dots"></i>
            <span class="notification-badge">6</span>
          </span>
        </div>
      </div>
    </div>

    <!-- SIDEBAR  -->
    <div class="sidebar">
      <ul>
      <li class="active">
    <a href="index.php" style="text-decoration: none; color: inherit;">
        <span><i class="bx bxs-home"></i></span>
        <span>Inicio</span>
    </a>
</li>

        <li>
          <span><i class="bx bx-trending-up"></i></span>
          <span class="new">Explore <span>New</span></span>
        </li>
        <li>
          <span><i class="bx bx-user-plus"></i></span>
          <span>Following</span>
        </li>
      </ul>
      <!-- INFORMATION  -->
      <ul class="information">
        <li>
          <span><i class="bx bxl-bitcoin"></i></span>
          <span>Create TikTok effects get a reward</span>
        </li>
      </ul>
    </div>

    <!-- VIDEOS  -->
    <div class="videos">
      <div class="align-element">
      <form action="upload_reel.php" method="post" enctype="multipart/form-data">
    <input type="file" name="video" accept="video/*" required>
    <textarea name="descripcion" placeholder="Describe tu finca o producto..."></textarea>
    <button type="submit">Subir video</button>
    </form>


        <!-- SCRIPT LINK  -->
        <script src="script.js"></script>

    </body>
</html>

<style>


form {
    display: flex;
    flex-direction: column;
}

input[type="file"] {
    background-color: #222; /* Fondo para el input */
    color: #fff; /* Texto blanco */
    padding: 10px;
    border: none;
    border-radius: 4px;
    margin-bottom: 10px;
    cursor: pointer;
}

textarea {
    background-color: #222; /* Fondo para el textarea */
    color: #fff; /* Texto blanco */
    padding: 10px;
    border: none;
    border-radius: 4px;
    margin-bottom: 10px;
    resize: none; /* Evitar redimensionar */
    min-height: 100px; /* Altura mínima del textarea */
}

button {
    background-color: #4CAF50; /* Color del botón */
    color: white; /* Texto blanco */
    border: none;
    border-radius: 4px;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049; /* Color al pasar el mouse */
}

button:active {
    transform: scale(0.95); /* Animación al hacer clic */
}

</style>