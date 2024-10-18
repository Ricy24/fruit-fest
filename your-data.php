<?php include 'procesos/perfil.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Fest | Perfil</title>
    <link rel="stylesheet" href="css/your-data.css">
    <link rel="icon" href="imagenes/favicon.ico" type="image/x-icon">
</head>
<header>
<?php include 'Estructura/header.php'; ?>
</header>
<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Configuración de la cuenta
        </h4>
        <?php if ($success_message) { ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php } ?>
        <?php if ($error_message) { ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php } ?>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#cuenta-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#cuenta-cambiar-contrasena">Cambiar contraseña</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="cuenta-general">
                            <form method="POST" action="">
                                <div class="card-body media align-items-center">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Avatar" class="d-block ui-w-80">
                                    <div class="media-body ml-4">
                                        <label class="btn btn-outline-primary">
                                            Subir nueva foto
                                            <input type="file" class="account-settings-fileinput" accept="image/*">
                                        </label> &nbsp;
                                        <div class="text-light small mt-1">Permitido JPG, GIF o PNG. Tamaño máximo de 800K</div>
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Correo electrónico</label>
                                        <input type="text" class="form-control mb-1" name="email" value="<?= htmlspecialchars($email) ?>">
                                        <div class="alert alert-warning mt-3">
                                            Tu correo electrónico no está confirmado. Por favor revisa tu bandeja de entrada.<br>
                                            <a href="javascript:void(0)">Reenviar confirmación</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Fecha de nacimiento</label>
                                        <input type="date" class="form-control" name="fecha_nacimiento" value="<?= htmlspecialchars($fecha_nacimiento) ?>">
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="submit" name="update_data" class="btn btn-outline-primary">Guardar cambios</button>&nbsp;
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="cuenta-cambiar-contrasena">
                            <form method="POST" action="">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Contraseña actual</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nueva contraseña</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Repetir nueva contraseña</label>
                                        <input type="password" class="form-control" name="repeat_new_password" required>
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="submit" name="change_password" class="btn btn-outline-primary">Guardar cambios</button>&nbsp;
                                    <button type="reset" class="btn btn-default">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
