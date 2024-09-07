<?php
include 'db_connect.php';

// Contoh query untuk mengambil data dari tabel posts
$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Title: " . $row["title"]. " - Content: " . $row["content"]. "<br>";
    }
} else {
    echo "0 results";
}

$conn->close(); // Menutup koneksi setelah selesai
?>
