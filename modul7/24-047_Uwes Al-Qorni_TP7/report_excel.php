<?php
include "koneksi.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

$tgl1 = $_GET['tgl1'];
$tgl2 = $_GET['tgl2'];

$q = mysqli_query($koneksi,"
    SELECT tanggal, nama_pelanggan, total_bayar
    FROM transaksi
    WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'
    ORDER BY tanggal ASC
");
?>

<table border="1">
    <h3>Laporan Penjualan</h3>
    <tr>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Total</th>
    </tr>

<?php while ($r = mysqli_fetch_assoc($q)) { ?>
    <tr>
        <td><?= $r['tanggal'] ?></td>
        <td><?= $r['nama_pelanggan'] ?></td>
        <td><?= $r['total_bayar'] ?></td>
    </tr>
<?php } ?>

</table>
