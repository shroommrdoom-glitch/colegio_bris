<?php
include_once("conexion.php");

$conexion_exitosa = false;
$alumnos = [];
$total_registros = 0;

if ($conn) {
    $conexion_exitosa = true;

    try {
        $stmt = $conn->query("SELECT id, nombre, apellido, correo, telefono, fecha_nacimiento, ciudad, promedio FROM personas ORDER BY id DESC LIMIT 100");
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_registros = count($alumnos);
    } catch (PDOException $e) {
        echo "Error al consultar la base de datos: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión - Colegio</title>
    <link rel="icon" href="logo27.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Variables CSS para fácil personalización */
        :root {
            --color-primario: #829fda;
            --color-secundario: #0a4ea3;
            --color-acento: #5dc5cc;
            --color-exito: #2ecc71;
            --color-advertencia: #f39c12;
            --color-peligro: #e74c3c;
            --color-fondo: #f8f9fa;
            --color-texto: #333;
            --sombra-suave: 0 4px 20px rgba(0, 0, 0, 0.1);
            --sombra-media: 0 6px 25px rgba(0, 0, 0, 0.15);
            --transicion-rapida: 0.2s ease;
            --transicion-normal: 0.3s ease;
            --radio-borde: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, var(--color-primario) 0%, #6b89c9 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            color: var(--color-texto);
            padding: 20px 0;
        }

        .container {
            width: 95%;
            max-width: 1400px;
            min-height: 95vh;
            background-color: #fff;
            border-radius: var(--radio-borde);
            box-shadow: var(--sombra-media);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header mejorado */
        .header {
            background: linear-gradient(135deg, var(--color-secundario) 0%, #0d63d4 100%);
            padding: 25px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .logo-img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            animation: pulse 2s ease-in-out infinite;
            filter: drop-shadow(0 4px 8px rgba(255, 255, 255, 0.3));
            transition: transform var(--transicion-normal);
        }

        .logo-img:hover {
            transform: scale(1.1) rotate(5deg);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .logo h1 {
            font-size: 2.5em;
            margin: 0;
            font-weight: 500;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .estado-conexion {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: white;
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .indicador {
            width: 14px;
            height: 14px;
            background-color: var(--color-exito);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--color-exito);
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Botones modernos */
        .actions {
            background: linear-gradient(to right, #f5f8fc, #e8f0fe);
            padding: 25px 30px;
            border-bottom: 2px solid #e0e7ff;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .actions button {
            background: linear-gradient(135deg, var(--color-acento) 0%, #4db5bc 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            cursor: pointer;
            transition: all var(--transicion-normal);
            font-size: 16px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(93, 197, 204, 0.3);
            position: relative;
            overflow: hidden;
        }

        .actions button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .actions button:hover::before {
            width: 300px;
            height: 300px;
        }

        .actions button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(93, 197, 204, 0.4);
        }

        .actions button:active {
            transform: translateY(-1px);
        }

        /* Contenido */
        .content {
            padding: 35px;
            overflow-y: auto;
            flex: 1;
            background: linear-gradient(to bottom, #ffffff, #f8fafc);
        }

        /* Barra de estado mejorada */
        .status-bar {
            background: linear-gradient(135deg, var(--color-secundario) 0%, #0d63d4 100%);
            color: white;
            padding: 18px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 15px rgba(10, 78, 163, 0.3);
            font-weight: 500;
            font-size: 1.1em;
        }

        .status-indicator {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: var(--color-exito);
            box-shadow: 0 0 12px var(--color-exito);
            animation: blink 1.5s infinite;
        }

        /* Estadísticas con efecto glass */
        .estadisticas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .estadistica-card {
            background: linear-gradient(135deg, rgba(130, 159, 218, 0.9) 0%, rgba(107, 137, 201, 0.9) 100%);
            backdrop-filter: blur(10px);
            color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: transform var(--transicion-normal);
            position: relative;
            overflow: hidden;
        }

        .estadistica-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            transition: transform var(--transicion-normal);
        }

        .estadistica-card:hover {
            transform: translateY(-5px) scale(1.02);
        }

        .estadistica-card:hover::before {
            transform: translate(-25%, -25%);
        }

        .estadistica-card h3 {
            margin: 0 0 15px 0;
            font-size: 1.1em;
            opacity: 0.95;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .estadistica-card .numero {
            font-size: 3em;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        /* Tabla mejorada */
        .data-table h2 {
            color: var(--color-secundario);
            margin-bottom: 25px;
            font-size: 2em;
            font-weight: 500;
            position: relative;
            padding-bottom: 15px;
        }

        .data-table h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--color-acento), transparent);
            border-radius: 2px;
        }

        .data-table table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            padding: 16px 18px;
            text-align: center;
            border-bottom: 1px solid #e8edf5;
        }

        .data-table th {
            background: linear-gradient(135deg, var(--color-secundario) 0%, #0d63d4 100%);
            font-weight: 600;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 0.5px;
        }

        .data-table tbody tr {
            transition: all var(--transicion-rapida);
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .data-table tbody tr:hover {
            background: linear-gradient(to right, #e6f0ff, #f0f7ff);
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* Badges de promedio con efecto */
        .promedio-alto {
            background: linear-gradient(135deg, var(--color-exito) 0%, #27ae60 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(46, 204, 113, 0.3);
            transition: transform var(--transicion-rapida);
        }

        .promedio-medio {
            background: linear-gradient(135deg, var(--color-advertencia) 0%, #e67e22 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
            transition: transform var(--transicion-rapida);
        }

        .promedio-bajo {
            background: linear-gradient(135deg, var(--color-peligro) 0%, #c0392b 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
            transition: transform var(--transicion-rapida);
        }

        .promedio-alto:hover,
        .promedio-medio:hover,
        .promedio-bajo:hover {
            transform: scale(1.1);
        }

        /* Footer moderno */
        footer {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--color-secundario) 0%, #0d63d4 100%);
            color: white;
            margin-top: auto;
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        /* Scrollbar personalizado */
        .content::-webkit-scrollbar {
            width: 10px;
        }

        .content::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .content::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--color-acento), var(--color-secundario));
            border-radius: 10px;
        }

        .content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #4db5bc, #082f6b);
        }

        /* Mensaje sin datos */
        .mensaje-vacio {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            font-size: 1.2em;
            background: linear-gradient(135deg, #f8fafc, #e8edf5);
            border-radius: 12px;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Responsive mejorado */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                padding: 20px;
            }
            
            .logo {
                flex-direction: column;
                text-align: center;
            }

            .logo h1 {
                font-size: 1.8em;
            }

            .logo-img {
                width: 70px;
                height: 70px;
            }
            
            .data-table table {
                font-size: 13px;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }
            
            .estadisticas {
                grid-template-columns: 1fr;
            }

            .actions {
                padding: 15px;
            }

            .actions button {
                font-size: 14px;
                padding: 12px 20px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 100%;
                border-radius: 0;
            }

            body {
                padding: 0;
            }

            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="logo27.jpg" alt="Logo del Colegio" class="logo-img">
                <h1>Sistema de Gestión - Colegio</h1>
            </div>
            <div class="estado-conexion">
                <span class="indicador"></span>
                <?php echo $conexion_exitosa ? 'BD Conectada' : 'BD Desconectada'; ?>
            </div>
        </div>

        <div class="actions">
            <button onclick="mostrarMensajeConexion()">🔍 Verificar Conexión</button>
            <button onclick="location.reload()">🔄 Actualizar Datos</button>
            <button onclick="window.print()">🖨️ Imprimir</button>
        </div>

        <div class="content">
            <div class="status-bar" id="status-bar">
                <span class="status-indicator"></span>
                <?php echo $conexion_exitosa ? 'Conectado a AlwaysData' : 'Error de conexión'; ?>
            </div>

            <?php if ($conexion_exitosa): ?>
            <div class="estadisticas">
                <div class="estadistica-card">
                    <h3>📊 Total de Registros</h3>
                    <p class="numero"><?php echo $total_registros; ?></p>
                </div>
                <div class="estadistica-card">
                    <h3>📈 Promedio General</h3>
                    <p class="numero">
                        <?php 
                        $suma = array_sum(array_column($alumnos, 'promedio'));
                        echo $total_registros > 0 ? number_format($suma / $total_registros, 2) : '0.00';
                        ?>
                    </p>
                </div>
                <div class="estadistica-card">
                    <h3>💾 Base de Datos</h3>
                    <p class="numero" style="font-size: 1.5em;">AlwaysData</p>
                </div>
            </div>
            <?php endif; ?>

            <div class="data-table">
                <h2>📋 Listado de Alumnos - 100 Registros</h2>
                <?php if ($total_registros > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Fecha Nac.</th>
                            <th>Ciudad</th>
                            <th>Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $alumno): 
                            $promedio = $alumno['promedio'];
                            $clase_promedio = '';
                            if ($promedio >= 9.0) {
                                $clase_promedio = 'promedio-alto';
                            } elseif ($promedio >= 7.5) {
                                $clase_promedio = 'promedio-medio';
                            } else {
                                $clase_promedio = 'promedio-bajo';
                            }
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($alumno['id']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['correo']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['ciudad']); ?></td>
                                <td>
                                    <span class="<?php echo $clase_promedio; ?>">
                                        <?php echo number_format($promedio, 2); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="mensaje-vacio">
                        <?php echo $conexion_exitosa ? '📭 No hay registros en la base de datos.' : '⚠️ No se pudo conectar a la base de datos. Verifica tu archivo conexion.php'; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <footer>
            <p>© 2025 Sistema de Gestión - Colegio | AlwaysData Database ✨</p>
        </footer>
    </div>

    <script>
        var conexionExitosa = <?php echo json_encode($conexion_exitosa); ?>;

        window.onload = function() {
            var indicador = document.querySelector('.indicador');
            if (conexionExitosa) {
                indicador.style.backgroundColor = '#00ff00';
                indicador.style.boxShadow = '0 0 10px #00ff00';
            } else {
                indicador.style.backgroundColor = '#ff0000';
                indicador.style.boxShadow = '0 0 10px #ff0000';
            }
        };

        function mostrarMensajeConexion() {
            var statusBar = document.getElementById('status-bar');

            if (conexionExitosa) {
                statusBar.innerHTML = '<span class="status-indicator">✔</span> ✅ Conexión exitosa a AlwaysData';
                statusBar.style.background = 'linear-gradient(135deg, #2ecc71 0%, #27ae60 100%)';
            } else {
                statusBar.innerHTML = '<span class="status-indicator" style="background-color: #ff0000; box-shadow: 0 0 10px #ff0000;">✖</span> ❌ Error al conectar a AlwaysData - Verifica tus credenciales';
                statusBar.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
            }

            setTimeout(function() {
                location.reload();
            }, 3000);
        }
    </script>
</body>
</html>