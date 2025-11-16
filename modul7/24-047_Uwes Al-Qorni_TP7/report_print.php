<?php
include "koneksi.php";

$tgl1 = $_GET['tgl1'];
$tgl2 = $_GET['tgl2'];

$q = mysqli_query($koneksi,"
    SELECT tanggal, nama_pelanggan, total_bayar
    FROM transaksi
    WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'
    ORDER BY tanggal ASC
");

$q_total = mysqli_query($koneksi,"
    SELECT COUNT(*) AS jml_pelanggan,
           SUM(total_bayar) AS total_pendapatan
    FROM transaksi
    WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'
");
$total = mysqli_fetch_assoc($q_total);
?>

<!DOCTYPE html>
<html>
<head>
<title>Print Laporan</title>
</head>
<body onload="window.print()">

<h2>Laporan Penjualan</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Total</th>
    </tr>

<?php while ($r = mysqli_fetch_assoc($q)) { ?>
    <tr>
        <td><?= $r['tanggal'] ?></td>
        <td><?= $r['nama_pelanggan'] ?></td>
        <td><?= number_format($r['total_bayar']) ?></td>
    </tr>
<?php } ?>
</table>

<br>

<h3>Total Keseluruhan</h3>
<p>Jumlah Pelanggan: <?= $total['jml_pelanggan'] ?> Orang</p>
<p>Total Pendapatan: Rp <?= number_format($total['total_pendapatan']) ?></p>

</body>
</html>
