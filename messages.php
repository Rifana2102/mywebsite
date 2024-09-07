<?php
require 'db_connect.php';

// Menampilkan error jika terjadi kesalahan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cek apakah form dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan sanitasi
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $message = $conn->real_escape_string(trim($_POST['message']));

    // Validasi input
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Persiapkan dan eksekusi statement SQL
        $sql = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $name, $email, $message);

        if ($sql->execute()) {
            echo "Pesan berhasil dikirim!<br><br>";

            // Ambil ID pesan terakhir
            $last_id = $conn->insert_id;
            $result = $conn->query("SELECT * FROM messages WHERE id = $last_id");

            if ($result->num_rows > 0) {
                echo '<div style="width: 80%; margin: 0 auto;">';
                echo '<h3>Data yang Baru Saja Anda Kirim:</h3>';
                echo '<table border="1" style="width: 100%; border-collapse: collapse;">';
                echo '<tr><th>Nama</th><th>Email</th><th>Pesan</th></tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['message']) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
                echo '</div>';
            } else {
                echo "Data tidak ditemukan.";
            }
        } else {
            echo "Error: " . $sql->error;
        }

        $sql->close();
    } else {
        echo "Semua field harus diisi.";
    }

    $conn->close();
}
?>