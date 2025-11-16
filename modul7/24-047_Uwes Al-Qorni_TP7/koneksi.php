
<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_mod7");

if (!$koneksi) {
    echo "Koneksi gagal: " . mysqli_connect_error();
}
?>
v