<?php

require_once __DIR__ . '/../config/database.php';
class InventoriesController
{
    private $static_token = "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9";

    public function __construct() {
        $this->middleware();
    }

    private function middleware() {
        $method = $_SERVER['REQUEST_METHOD'];

        $headers = apache_request_headers();
        $token = isset($headers['Authorization']) ? $headers['Authorization'] : '';

        if ($method !== 'GET') {
            if ($token !== $this->static_token) {
                http_response_code(401);
                echo json_encode(array("message" => "Invalid token.", "token" => $token));
                exit();
            }
        }
    }

    public function get()
    {
        global $conn;

        $sql = "SELECT * FROM inventori";
        $result = $conn->query($sql);


        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo json_encode(array("message" => "No inventory items found."));
        }
    }

    public function post()
    {
        global $conn;

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['nama']) || !isset($data['harga']) || !isset($data['deskripsi']) || !isset($data['foto'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid request. 'nama', 'harga', and 'deskripsi' are required."));
            exit();
        }

        $nama = $this->removeSpecialChars($data['nama']);
        $harga = $this->removeSpecialChars($data['harga']);
        $deskripsi = $this->removeSpecialChars($data['deskripsi']);
        $foto = $data['foto'];

        $upload_photo = $this->uploadPhoto($foto);
        if ($upload_photo) {
            $sql = "INSERT INTO inventori (nama, harga, deskripsi, foto) VALUES ('$nama', '$harga', '$deskripsi', '$upload_photo')";
            if ($conn->query($sql) === TRUE) {
                http_response_code(201);
                echo json_encode(array("message" => "Inventory item created successfully."));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Error creating inventory item: " . $conn->error));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to upload photo."));
        }
    }

    public function put($id)
    {
        global $conn;
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($id) || !isset($data['nama']) || !isset($data['harga']) || !isset($data['deskripsi'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid request. 'nama', 'harga', and 'deskripsi' are required."));
            exit();
        }

        $nama = $this->removeSpecialChars($data['nama']);
        $harga = $this->removeSpecialChars($data['harga']);
        $deskripsi = $this->removeSpecialChars($data['deskripsi']);
        $foto = isset($data['foto']) ? $data['foto'] : null;

        if ($foto) {
            $uploaded_photo = $this->uploadPhoto($foto);
            if ($uploaded_photo) {
                $sql = "UPDATE inventori SET nama='$nama', harga='$harga', deskripsi='$deskripsi', foto='$uploaded_photo' WHERE id=$id";
                if ($conn->query($sql) === TRUE) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Inventory item updated successfully."));
                } else {
                    http_response_code(500);
                    echo json_encode(array("message" => "Error updating inventory item: " . $conn->error));
                }
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Failed to upload photo."));
            }
        } else {
            $sql = "UPDATE inventori SET nama='$nama', harga='$harga', deskripsi='$deskripsi' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Inventory item updated successfully."));
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(array("message" => "Error updating inventory item: " . $conn->error));
            }
        }
    }

    public function delete($id)
    {
        global $conn;

        if (!isset($id)) {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid request. 'id' is required."));
            exit();
        }

        $sql = "SELECT foto FROM inventori WHERE id = $id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $foto = $row['foto'];

            $sql_delete = "DELETE FROM inventori WHERE id = $id";
            if ($conn->query($sql_delete) === TRUE) {
                if (unlink($foto)) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Inventory item deleted successfully."));
                } else {
                    http_response_code(500);
                    echo json_encode(array("message" => "Failed to delete photo."));
                }
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Error deleting inventory item: " . $conn->error));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Inventory item not found."));
        }
    }

    private function uploadPhoto($foto)
    {
        $image_data = base64_decode($foto);
        $image_name = uniqid() . '.jpg';

        $file_path = 'uploads/' . $image_name; 
        $success = file_put_contents($file_path, $image_data);

        return $success ? $file_path : false;
    }

    private function removeSpecialChars($string) {
        $special_chars = array("'", '"', ";", "--", "#", "(", ")", "+", "%", "@", ">", "<", "?", ",", ".", "/", "\\", "-", "!", "=");
        $clean_string = str_replace($special_chars, '', $string);
    
        return $clean_string;
    }
}
