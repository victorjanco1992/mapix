<?php

class MapaModel extends Model
{

    public function store_()
    {
        // Recibir datos del POST
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $dificultad = $_POST['dificultad'] ?? '';
        $terreno = $_POST['terreno'] ?? '';
        $etiquetas = $_POST['etiquetas'] ?? '';
        $duracion = $_POST['duracion'] ?? '';
        $distancia_km = floatval($_POST['distancia_km'] ?? 0);
        $puntos_gps_json = $_POST['puntos_gps'] ?? '';
        $imagenBase64 = $_POST['imagen'] ?? '';
        $velocidad = $_POST['velocidad'] ?? '';

        // Decodificar JSON de puntos GPS para uso interno
        $puntos_gps_array = json_decode($puntos_gps_json, true); // ahora es array asociativo

        try {

            // Guardar la imagen (igual que antes)
            $rutaImagen = '';
            if ($imagenBase64) {
                $imagenBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64);
                $imagenDecodificada = base64_decode($imagenBase64);

                if ($imagenDecodificada !== false) {
                    $nombreArchivo = uniqid('ruta_') . '.jpg';
                    $rutaImagen = 'public/assets/images/uploads/' . $nombreArchivo;

                    if (!file_exists('public/assets/images/uploads')) {
                        mkdir('public/assets/images/uploads', 0777, true);
                    }
                    file_put_contents($rutaImagen, $imagenDecodificada);
                }
            }

            // Extraer puntos inicio y fin para guardarlos en campos separados
            $ruta_inicio = '';
            $ruta_fin = '';
            if (is_array($puntos_gps_array) && count($puntos_gps_array) >= 2) {
                $inicio = $puntos_gps_array[0];
                $fin = $puntos_gps_array[count($puntos_gps_array) - 1];

                // Suponiendo que los puntos tienen 'lat' y 'lng'
                $ruta_inicio = $inicio['lat'] . ',' . $inicio['lng'];
                $ruta_fin = $fin['lat'] . ',' . $fin['lng'];
            }

            $sql = 'INSERT INTO rutas (nombre, descripcion, dificultad, terreno, etiquetas, ruta_img, ruta_inicio, ruta_fin, fecha_registro, duracion, distancia_km, velocidad) VALUES (:nombre, :descripcion, :dificultad, :terreno, :etiquetas, :ruta_img, :ruta_inicio, :ruta_fin, NOW(), :duracion, :distancia_km, :velocidad)';
            $params = [':nombre' => $nombre, ':descripcion' => $descripcion, ':dificultad' => $dificultad, ':terreno' => $terreno, ':etiquetas' => $etiquetas, ':ruta_img' => $rutaImagen, ':ruta_inicio' => $ruta_inicio, ':ruta_fin' => $ruta_fin, ':duracion' => $duracion, ':distancia_km' => $distancia_km,':velocidad'=>$velocidad];

            $this->query($sql, $params);

            return [
                'status' => 'success',
                'message' => 'Ruta registrada correctamente.',
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'message' => 'Error al guardar la ruta: ' . $th->getMessage()
            ];
        }
    }

    public function storePlaces_()
    {

        try {

            // Leer el JSON enviado
            $input = json_decode(file_get_contents('php://input'), true);

            // Validar datos
            if (
                !isset($input['name']) ||
                !isset($input['lat']) || !is_numeric($input['lat']) ||
                !isset($input['lng']) || !is_numeric($input['lng'])
            ) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
                exit;
            }

            $name = $input['name'];
            $type = $input['type'] ?? 'otro';
            $lat = floatval($input['lat']);
            $lng = floatval($input['lng']);
            $description = $input['description'] ?? '';
            $danger = $input['danger'] ?? 'baja';
            $year = isset($input['year']) && is_numeric($input['year']) ? intval($input['year']) : date('Y');
            $imagenBase64 = $input['img'];

            // Guardar la imagen (igual que antes)
            $rutaImagen = '';
            if ($imagenBase64) {
                $imagenBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64);
                $imagenDecodificada = base64_decode($imagenBase64);

                if ($imagenDecodificada !== false) {
                    $nombreArchivo = uniqid('ruta_') . '.jpg';
                    $rutaImagen = 'public/assets/images/uploads/' . $nombreArchivo;

                    if (!file_exists('public/assets/images/uploads')) {
                        mkdir('public/assets/images/uploads', 0777, true);
                    }
                    file_put_contents($rutaImagen, $imagenDecodificada);
                }
            }

            // Insertar en la base de datos
            $stmt = "
                    INSERT INTO places (name, type, lat, lng, description, danger,path_img, year)
                    VALUES (:name, :type, :lat, :lng, :description, :danger,:path_img, :year)
                ";

            $query = [
                ':name' => $name,
                ':type' => $type,
                ':lat' => $lat,
                ':lng' => $lng,
                ':description' => $description,
                ':danger' => $danger,
                ':path_img' => $rutaImagen,
                ':year' => $year
            ];

            $this->query($stmt, $query);

            return ['status' => 'success', 'message' => 'Lugar guardado correctamente.'];
        } catch (PDOException $e) {
            http_response_code(500);
            return ['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }

    public function getPlacesComents_()
    {
        try {
            // 1. Obtener todos los lugares
            $sql = "SELECT * FROM places";
            $places = $this->getAll($sql);

            // 1.1 Obtener los favoritos
            $sqlf = "
            SELECT id_place_f 
            FROM favorites 
            WHERE id_user_f = :id_user AND favorites = 1
        ";
            $params = [':id_user' => $_SESSION["usuario_cliente"]->id_users];
            $favorites = $this->getAll($sqlf, $params);

            // Transformar favoritos en array plano de IDs
            $favoritePlaces = [];
            foreach ($favorites as $fav) {
                $favoritePlaces[] = intval($fav->id_place_f);
            }

            // 2. Obtener todos los comentarios
            $sqlc = "SELECT c.id_coments, c.id_user, c.id_place, c.text, c.rating, c.date, u.name_users as user_name
                 FROM comments c
                 LEFT JOIN users u ON c.id_user = u.id_users";
            $comments = $this->getAll($sqlc);

            // 3. Indexar comentarios por id_place
            $commentsByPlace = [];
            foreach ($comments as $comment) {
                $commentsByPlace[$comment->id_place][] = [
                    'user' => $comment->user_name,
                    'text' => $comment->text,
                    'date' => $comment->date,
                    'rating' => $comment->rating,
                ];
            }

            // 4. Construir el resultado
            $result = [];
            foreach ($places as $place) {
                $placeData = [
                    'id' => intval($place->id),
                    'name' => $place->name,
                    'type' => $place->type,
                    'lat' => floatval($place->lat),
                    'lng' => floatval($place->lng),
                    'description' => $place->description,
                    'danger' => $place->danger,
                    'year' => intval($place->year),

                    'favorites' => in_array(intval($place->id), $favoritePlaces) ? 1 : 0, // ✅ Ya está bien
                    'images' => [$place->path_img],
                    'comments' => isset($commentsByPlace[$place->id]) ? $commentsByPlace[$place->id] : []
                ];

                $result[] = $placeData;
            }

            return [
                'status' => 'success',
                'data' => $result
            ];
        } catch (PDOException $e) {
            http_response_code(500);
            return [
                'status' => 'error',
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ];
        }
    }

    public function ToggleFavorite_()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_place = $data['id_place'];
        $favorite = $data['favorite'];
        $user_id = $_SESSION['usuario_cliente']->id_users;

        try {
            // 1️⃣ Verificar si el favorito ya existe
            $checkSql = "
            SELECT COUNT(*) as count 
            FROM favorites 
            WHERE id_place_f = :id_place AND id_user_f = :user_id
        ";
            $checkParams = [':id_place' => $id_place, ':user_id' => $user_id];
            $result = $this->getOne($checkSql, $checkParams);

            if ($result->count > 0) {
                // 2️⃣ Existe → Actualizar
                $updateSql = "
                UPDATE favorites 
                SET favorites = :favorite 
                WHERE id_place_f = :id_place AND id_user_f = :user_id
            ";
                $updateParams = [
                    ':id_place' => $id_place,
                    ':favorite' => $favorite,
                    ':user_id' => $user_id
                ];
                $this->query($updateSql, $updateParams);
            } else {
                // 3️⃣ No existe → Insertar
                $insertSql = "
                INSERT INTO favorites (id_place_f, id_user_f, favorites)
                VALUES (:id_place, :user_id, :favorite)
            ";
                $insertParams = [
                    ':id_place' => $id_place,
                    ':user_id' => $user_id,
                    ':favorite' => $favorite
                ];
                $this->query($insertSql, $insertParams);
            }

            return [
                'status' => 'success',
                'data' => 'Favorito actualizado correctamente'
            ];
        } catch (PDOException $e) {
            http_response_code(500);
            return [
                'status' => 'error',
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ];
        }
    }

    public function addComment_()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_place = $data['id_place'];
        $text = $data['text'];
        $rating = $data['rating']; // Si lo usas, sino se omite
        $id_user = $_SESSION['usuario_cliente']->id_users;

        date_default_timezone_set('America/Lima');
        $fecha_actual = date('Y-m-d');


        try {
            $sql = "INSERT INTO comments (id_user, id_place, text, rating, date) VALUES (:id_user, :id_place, :text, :rating, :fecha_actual)";
            $params = [
                ':id_user' => $id_user,
                ':id_place' => $id_place,
                ':text' => $text,
                ':rating' => $rating,
                ':fecha_actual' => $fecha_actual
            ];
            $this->query($sql, $params);

            return [
                'status' => 'success',
                'message' => 'Comentario agregado correctamente.'
            ];
        } catch (PDOException $e) {
            http_response_code(500);
            return [
                'status' => 'error',
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ];
        }
    }

    public function obtenerRutas_()
    {
        try {

            $sql = "SELECT * FROM rutas";
            $result = $this->getAll($sql);

             return [
                'status' => 'success',
               'data' => $result
            ];
        } catch (PDOException $e) {
            http_response_code(500);
            return [
                'status' => 'error',
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ];
        }
    }
   
    public function obtenerFavoritos_()
    {
        try {

            $sql = "SELECT favorites.id_favorite as id, places.id as id_lugar,places.name, places.description, places.path_img FROM favorites INNER JOIN places ON places.id = favorites.id_place_f WHERE favorites.id_user_f = :id";
            $result = $this->getAll($sql,[":id"=>$_SESSION['usuario_cliente']->id_users]);

             return [
                'status' => 'success',
               'data' => $result
            ];
        } catch (PDOException $e) {
            http_response_code(500);
            return [
                'status' => 'error',
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ];
        }
    }
}
