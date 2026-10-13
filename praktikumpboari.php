<?php
// ==========================================
// PROGRAM TRANSAKSI SEDERHANA
// ==========================================

// Variabel
$namaPembeli = "";
$namaBarang = "";
$hargaBarang = 0;
$jumlahBeli = 1;
$statusMember = "reguler";
$totalHarga = 0;
$diskon = 0;
$totalBayar = 0;
$pesan = "";
$diproses = false;

// Fungsi format rupiah
function rupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fungsi menghitung diskon
function hitungDiskon($total, $status)
{
    if ($status == "vip") {
        return $total * 0.10;
    } elseif ($total >= 100000) {
        return $total * 0.05;
    } else {
        return 0;
    }
}

// Memproses formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaPembeli = trim($_POST["namaPembeli"] ?? "");
    $namaBarang = trim($_POST["namaBarang"] ?? "");
    $hargaBarang = (float) ($_POST["hargaBarang"] ?? 0);
    $jumlahBeli = (int) ($_POST["jumlahBeli"] ?? 1);
    $statusMember = $_POST["statusMember"] ?? "reguler";

    // Validasi data
    if (
        $namaPembeli == "" ||
        $namaBarang == "" ||
        $hargaBarang <= 0 ||
        $jumlahBeli <= 0 ||
        !in_array($statusMember, ["reguler", "vip"])
    ) {
        $pesan = "Mohon isi semua data dengan benar.";
    } else {
        // Perhitungan transaksi
        $totalHarga = $hargaBarang * $jumlahBeli;
        $diskon = hitungDiskon($totalHarga, $statusMember);
        $totalBayar = $totalHarga - $diskon;
        $diproses = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Sederhana</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 35px 18px;
            background: #f5f6fa;
            font-family: Arial, Helvetica, sans-serif;
            color: #293247;
        }

        .container {
            max-width: 765px;
            margin: 0 auto;
            padding: 34px 32px;
            background: #ffffff;
            border-radius: 17px;
            box-shadow: 0 8px 35px rgba(35, 42, 70, 0.07);
        }

        .header {
            text-align: center;
            padding-bottom: 18px;
            margin-bottom: 28px;
            border-bottom: 1px solid #edf0f5;
        }

        .label-top {
            display: inline-block;
            padding: 7px 13px;
            background: #f0edff;
            color: #5746df;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        h1 {
            color: #5746df;
            font-size: 27px;
            margin: 17px 0 9px;
        }

        .subtitle {
            margin: 0;
            color: #81889a;
            font-size: 14px;
            line-height: 1.6;
        }

        .section-title {
            font-size: 17px;
            margin: 0 0 22px;
            color: #30384d;
        }

        .form-group {
            margin-bottom: 21px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 9px;
        }

        input, select {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #d9dde6;
            border-radius: 7px;
            background: #fff;
            font-size: 14px;
            color: #30384d;
            outline: none;
            transition: 0.2s;
        }

        input:focus, select:focus {
            border-color: #6855ee;
            box-shadow: 0 0 0 3px rgba(104, 85, 238, 0.10);
        }

        input::placeholder {
            color: #a1a6b2;
        }

        .hint {
            margin-top: 7px;
            color: #8b91a0;
            font-size: 12px;
        }

        .button {
            display: block;
            width: 100%;
            border: none;
            border-radius: 7px;
            padding: 14px;
            background: #5545e8;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 28px;
        }

        .button:hover {
            background: #4434d0;
            transform: translateY(-1px);
        }

        .result {
            border-top: 2px dashed #e0e3ec;
            margin-top: 34px;
            padding-top: 28px;
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .result-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eeeaff;
            border-radius: 12px;
            font-size: 22px;
        }

        .result-header h2 {
            font-size: 19px;
            margin: 0 0 5px;
        }

        .result-header p {
            color: #8b91a0;
            font-size: 12px;
            margin: 0;
        }

        .receipt {
            background: #fafaff;
            border: 1px solid #ecebfa;
            border-radius: 11px;
            padding: 19px;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 11px 0;
            font-size: 14px;
        }

        .receipt-row span:first-child {
            color: #777f91;
        }

        .receipt-row span:last-child {
            text-align: right;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .receipt-divider {
            border-top: 1px dashed #d9d8e8;
            margin: 8px 0;
        }

        .discount {
            color: #16845b;
        }

        .total-box {
            background: #f0edff;
            border-radius: 9px;
            padding: 16px;
            margin-top: 12px;
        }

        .total-box .receipt-row {
            padding: 0;
            align-items: center;
        }

        .total-box span:first-child {
            color: #4e43a7;
            font-weight: bold;
        }

        .total-price {
            color: #5545e8;
            font-size: 21px;
            font-weight: bold;
        }

        .message {
            background: #fff0f0;
            color: #b42332;
            padding: 13px;
            border-radius: 7px;
            font-size: 14px;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            color: #969baa;
            font-size: 12px;
            margin-top: 28px;
            line-height: 1.7;
        }

        @media (max-width: 500px) {
            body {
                padding: 15px 10px;
            }

            .container {
                padding: 25px 19px;
                border-radius: 12px;
            }

            h1 {
                font-size: 22px;
            }

            .total-price {
                font-size: 17px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <header class="header">
        <span class="label-top">SIMPLE TRANSACTION</span>
        <h1>Program Transaksi Sederhana</h1>
        <p class="subtitle">
            Kelola transaksi dengan mudah dan praktis.<br>
            Masukkan detail pembelian untuk menghitung total pembayaran.
        </p>
    </header>

    <section>
        <h2 class="section-title">Informasi Pembelian</h2>

        <form method="POST" action="">
            <div class="form-group">
                <label for="namaPembeli">Nama Pembeli</label>
                <input
                    type="text"
                    id="namaPembeli"
                    name="namaPembeli"
                    placeholder="Masukkan nama pembeli"
                    value="<?= htmlspecialchars($namaPembeli) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="namaBarang">Nama Produk / Barang</label>
                <input
                    type="text"
                    id="namaBarang"
                    name="namaBarang"
                    placeholder="Contoh: Buku Tulis Premium"
                    value="<?= htmlspecialchars($namaBarang) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="hargaBarang">Harga Satuan (Rp)</label>
                <input
                    type="number"
                    id="hargaBarang"
                    name="hargaBarang"
                    placeholder="Contoh: 25000"
                    min="1"
                    step="1"
                    value="<?= $hargaBarang > 0 ? $hargaBarang : '' ?>"
                    required
                >
                <div class="hint">Masukkan harga satu produk tanpa tanda titik.</div>
            </div>

            <div class="form-group">
                <label for="jumlahBeli">Jumlah Pembelian (Qty)</label>
                <input
                    type="number"
                    id="jumlahBeli"
                    name="jumlahBeli"
                    min="1"
                    step="1"
                    value="<?= $jumlahBeli ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="statusMember">Status Keanggotaan</label>
                <select id="statusMember" name="statusMember">
                    <option value="reguler"
                        <?= $statusMember == "reguler" ? "selected" : "" ?>>
                        Reguler (Diskon sesuai total belanja)
                    </option>
                    <option value="vip"
                        <?= $statusMember == "vip" ? "selected" : "" ?>>
                        VIP (Diskon 10%)
                    </option>
                </select>
                <div class="hint">
                    Member VIP mendapatkan diskon khusus 10%.
                </div>
            </div>

            <button type="submit" class="button">
                Hitung Transaksi
            </button>
        </form>

        <?php if ($pesan != ""): ?>
            <div class="message">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>
    </section>

    <?php if ($diproses): ?>
    <section class="result">
        <div class="result-header">
            <div class="result-icon">&#10003;</div>
            <div>
                <h2>Ringkasan Transaksi</h2>
                <p>Perhitungan pembelian berhasil dilakukan.</p>
            </div>
        </div>

        <div class="receipt">
            <div class="receipt-row">
                <span>Nama Pembeli</span>
                <span><?= htmlspecialchars($namaPembeli) ?></span>
            </div>

            <div class="receipt-row">
                <span>Nama Produk</span>
                <span><?= htmlspecialchars($namaBarang) ?></span>
            </div>

            <div class="receipt-row">
                <span>Harga Satuan</span>
                <span><?= rupiah($hargaBarang) ?></span>
            </div>

            <div class="receipt-row">
                <span>Jumlah Pembelian</span>
                <span><?= $jumlahBeli ?> item</span>
            </div>

            <div class="receipt-row">
                <span>Status Member</span>
                <span>
                    <?= $statusMember == "vip" ? "VIP" : "Reguler" ?>
                </span>
            </div>

            <div class="receipt-divider"></div>

            <div class="receipt-row">
                <span>Subtotal</span>
                <span><?= rupiah($totalHarga) ?></span>
            </div>

            <div class="receipt-row">
                <span>Diskon</span>
                <span class="discount">
                    - <?= rupiah($diskon) ?>
                </span>
            </div>

            <div class="total-box">
                <div class="receipt-row">
                    <span>Total Pembayaran</span>
                    <span class="total-price">
                        <?= rupiah($totalBayar) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="footer">
            <?php
            // Perulangan untuk menampilkan pesan
            for ($i = 1; $i <= 2; $i++) {
                echo "Terima kasih telah menggunakan layanan kami!<br>";
            }
            ?>
        </div>
    </section>
    <?php endif; ?>

    <div class="footer">
        Praktikum Pemrograman PHP<br>
        Program Transaksi Sederhana &copy; 2026
    </div>

</div>
</body>
</html> 