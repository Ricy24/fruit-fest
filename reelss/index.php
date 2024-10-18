<?php
// Iniciar sesión
session_start();

// Conectar a la base de datos
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "fruitfest"; 

$conn = mysqli_connect($host, $username, $password, $database);

// Verificar si la conexión fue exitosa
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si el usuario está logueado
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} elseif (isset($_SESSION['vendedor_id'])) {
    $current_user_id = $_SESSION['vendedor_id'];
    $vendedor_id = isset($_GET['id']) ? $_GET['id'] : $current_user_id;

    if (!filter_var($vendedor_id, FILTER_VALIDATE_INT)) {
        echo "ID de vendedor inválido.";
        exit;
    }

    $sql_vendedor = "SELECT * FROM vendedores WHERE id = ?";
    $stmt_vendedor = $conn->prepare($sql_vendedor);
    $stmt_vendedor->bind_param("i", $vendedor_id);
    $stmt_vendedor->execute();
    $vendedor = $stmt_vendedor->get_result()->fetch_assoc();

    if (!$vendedor) {
        echo "Vendedor no encontrado.";
        exit;
    }
} else {
    header('Location: ../login.php');
    exit();
}

// Verificar si se está incrementando "me gusta"
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Incrementar el contador de "me gusta"
    $sql = "UPDATE reels SET me_gustas = me_gustas + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Obtener el nuevo número de "me gusta"
        $sql = "SELECT me_gustas FROM reels WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($likes);
            $stmt->fetch();

            // Devolver el nuevo número de "me gusta" como JSON
            echo json_encode(['likes' => $likes]);
        } else {
            echo json_encode(['error' => 'Error al obtener los likes']);
        }
    } else {
        echo json_encode(['error' => 'Error al actualizar likes']);
    }

    // Cerrar el statement
    if ($stmt) {
        $stmt->close();
    }
    mysqli_close($conn);
    exit; // Termina el script aquí si se ha procesado un like
}

// Consulta para mostrar los videos y la información del vendedor
$sql = "SELECT r.*, v.foto, v.nombre AS vendedor_nombre FROM reels r JOIN vendedores v ON r.id_vendedor = v.id ORDER BY r.fecha_subida DESC";
$result = mysqli_query($conn, $sql);

// Verifica si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fruit fest reels</title>
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
        <button class="upload-btn" onclick="window.location.href='subir_videos.php'">
        <i class="bx bx-plus"></i>
               Upload
          </button>


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
    <a href="../index.php" style="text-decoration: none;">
        <span><i class="bx bx-arrow-back"> </i></span>
        <span> Volver</span>
    </a> 
</li>


        
      </ul>
      <!-- INFORMATION  -->

    </div>

    <!-- VIDEOS  -->
    <div class="videos">
      <div class="align-element">
        <?php
        // Verifica si hay resultados
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="video-container">';

            // Mostrar el resto de los videos
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="video">';
                echo '<div class="video-box">';
                echo '<div class="video-info">';
                echo '<div class="video-section">';
                echo '<video class="videoPlayer" controls muted preload="auto" src="uploads/' . $row['video_url'] . '"></video>';
                echo '<span class="video-play-icon"><i class="bx bx-play"></i></span>';
                echo '</div>';
                echo '<div class="user-info">';
                echo '<h5>' . htmlspecialchars($row['vendedor_nombre']) . '</h5>';
                echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';
                echo '</div>';
                echo '</div>';
                echo '<div class="video-desc">';
                echo '<div class="user-profile">';
                echo '<img src="../uploads/' . $row['foto'] . '" alt="Foto de ' . htmlspecialchars($row['vendedor_nombre']) . '" class="vendedor-foto" />';
                echo '<span class="follow"><i class="bx bx-plus"></i></span>';
                echo '</div>';
                echo '<ul>';
                echo '<li>';
                echo '<span class="like"><i class="bx bxs-heart"></i></span>';

                echo '</li>';
                echo '<li>';
                echo '<span><i class="bx bxs-message-rounded-dots"></i></span>';
                echo '<span>8k</span>'; // Placeholder for comments
                echo '</li>';
                echo '<li>';
                echo '</li>';
                echo '<li>';
                echo '<span><i class="bx bxs-share bx-flip-horizontal"></i></span>';
                echo '<span>500.5k</span>'; // Placeholder for shares
                echo '</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '</div>'; // cerrar video
            }
            echo '</div>'; // Cerrar video-container
        } else {
            echo "No hay reels disponibles.";
        }
        ?>

        <!-- SCRIPT LINK  -->
        <script src="script.js"></script>
        <script>
        function shareVideo(videoUrl) {
            if (navigator.share) {
                navigator.share({
                    title: '¡Mira este video!',
                    url: window.location.origin + '/prueba/reels/uploads/' + videoUrl // Cambia esto según la ruta correcta
                })
                .then(() => console.log('Compartido con éxito'))
                .catch(error => console.error('Error al compartir:', error));
            } else {
                prompt("Copia este enlace para compartir:", window.location.origin + '/prueba/reels/uploads/' + videoUrl);
            }
        }
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            const videos = $('.videoPlayer');

            videos.on('ended', function() {
                $(this).get(0).pause();
            });

            $('.like').on('click', function() {
                const videoId = $(this).closest('.video').data('video-id'); // Suponiendo que has agregado un data attribute con el id del video
                $.post('video.php', { id: videoId }, function(data) {
                    if (data.likes) {
                        $('#like-count-' + videoId).text(data.likes);
                    } else {
                        console.error(data.error);
                    }
                }, 'json');
            });
        });
        </script>

<script>
    // Selecciona todos los elementos de video
    const videos = document.querySelectorAll('.videoPlayer');

    // Agrega un evento de reproducción a cada video
    videos.forEach(video => {
        video.addEventListener('play', () => {
            // Pausar todos los otros videos
            videos.forEach(otherVideo => {
                if (otherVideo !== video) {
                    otherVideo.pause();
                }
            });
        });
    });
</script>
    </body>
</html>
