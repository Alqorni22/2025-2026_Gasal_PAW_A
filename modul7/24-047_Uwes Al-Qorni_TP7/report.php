<?php
include "koneksi.php";

$tgl1 = isset($_GET['tgl1']) ? $_GET['tgl1'] : "";
$tgl2 = isset($_GET['tgl2']) ? $_GET['tgl2'] : "";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Penjualan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Report Penjualan</h2>

<form method="GET">
    Tanggal Awal:
    <input type="date" name="tgl1" value="<?= $tgl1 ?>" required>

    Tanggal Akhir:
    <input type="date" name="tgl2" value="<?= $tgl2 ?>" required>

    <button type="submit">Tampilkan</button>
</form>

<hr>

<?php
if ($tgl1 && $tgl2) {

    // DATA GRAFIK
    $q_chart = mysqli_query($koneksi,"
        SELECT tanggal, SUM(total_bayar) AS total
        FROM transaksi
        WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'
        GROUP BY tanggal
    ");

    $label = [];
    $nilai = [];

    while ($d = mysqli_fetch_assoc($q_chart)) {
        $label[] = $d['tanggal'];
        $nilai[] = $d['total'];
    }

    // REKAP DETAIL
    $q_rekap = mysqli_query($koneksi,"
        SELECT tanggal, nama_pelanggan, total_bayar
        FROM transaksi
        WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'
        ORDER BY tanggal ASC
    ");

    // TOTAL
    $q_total = mysqli_query($koneksi,"
        SELECT COUNT(*) AS jml_pelanggan,
               SUM(total_bayar) AS total_pendapatan
        FROM transaksi
        WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'
    ");

    $total = mysqli_fetch_assoc($q_total);
?>

<p><b>Periode <?= $tgl1 ?> sampai <?= $tgl2 ?></b></p>

<h3>Grafik Penjualan</h3>

<canvas id="chart" height="120"></canvas>

<script>
new Chart(document.getElementById("chart"), {
    type: "bar",
    data: {
        labels: <?= json_encode($label) ?>,
        datasets: [{
            label: "Total Penjualan",
            data: <?= json_encode($nilai) ?>,
            backgroundColor: "rgba(135,206,250,0.7)"
        }]
    }
});
</script>

<br>

<button onclick="window.print()">Print</button>

<a href="report_excel.php?tgl1=<?= $tgl1 ?>&tgl2=<?= $tgl2 ?>">
    <button>Export Excel</button>
</a>

<hr>

<h3>Rekap Penjualan</h3>

<table border="1" cellpadding="5">
    <tr>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Total</th>
    </tr>

<?php while ($r = mysqli_fetch_assoc($q_rekap)) { ?>
    <tr>
        <td><?= $r['tanggal'] ?></td>
        <td><?= $r['nama_pelanggan'] ?></td>
        <td><?= number_format($r['total_bayar']) ?></td>
    </tr>
<?php } ?>
</table>

<h3>Total</h3>

<p>Jumlah Pelanggan: <?= $total['jml_pelanggan'] ?> Orang</p>
<p>Total Pendapatan: Rp <?= number_format($total['total_pendapatan']) ?></p>

<?php } ?>

</body>
</html>
