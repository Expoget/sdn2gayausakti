<?php
// Memulai sesi (jika diperlukan)
session_start();

// Mengambil data dari form
$nomor_surat = isset($_POST['nomor_surat']) ? $_POST['nomor_surat'] : '';
$tujuan_surat = isset($_POST['tujuan_surat']) ? $_POST['tujuan_surat'] : '';
$nomor_referensi = isset($_POST['nomor_referensi']) ? $_POST['nomor_referensi'] : '';
$nama1 = isset($_POST['nama1']) ? $_POST['nama1'] : '';
$nip1 = isset($_POST['nip1']) ? $_POST['nip1'] : '';
$pangkat1 = isset($_POST['pangkat1']) ? $_POST['pangkat1'] : '';
$nama2 = isset($_POST['nama2']) ? $_POST['nama2'] : '';
$nip2 = isset($_POST['nip2']) ? $_POST['nip2'] : '';
$pangkat2 = isset($_POST['pangkat2']) ? $_POST['pangkat2'] : '';
$tujuan_perjalanan = isset($_POST['tujuan_perjalanan']) ? $_POST['tujuan_perjalanan'] : '';
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
$waktu = isset($_POST['waktu']) ? $_POST['waktu'] : '';
$tempat = isset($_POST['tempat']) ? $_POST['tempat'] : '';
$tanggal_surat = isset($_POST['tanggal_surat']) ? $_POST['tanggal_surat'] : '';
$berangkat_dari = isset($_POST['berangkat_dari']) ? $_POST['berangkat_dari'] : '';
$tanggal_berangkat = isset($_POST['tanggal_berangkat']) ? $_POST['tanggal_berangkat'] : '';
$tujuan_tempat = isset($_POST['tujuan_tempat']) ? $_POST['tujuan_tempat'] : '';

// Array untuk menyimpan pesan kesalahan
$errors = [];

// Validasi Nomor Surat
if (empty($nomor_surat)) {
    $errors[] = "Nomor surat harus diisi.";
}

// Validasi Tujuan Surat
if (empty($tujuan_surat)) {
    $errors[] = "Tujuan surat harus diisi.";
}

// Validasi NIP Petugas 1
if (empty($nip1)) {
    $errors[] = "NIP Petugas 1 harus diisi.";
} elseif (!preg_match('/^\d{8}$/', $nip1)) { // Misalnya, NIP harus 8 digit
    $errors[] = "NIP Petugas 1 harus terdiri dari 8 digit.";
}

// Validasi Tanggal
if (empty($tanggal)) {
    $errors[] = "Tanggal harus diisi.";
} elseif (!DateTime::createFromFormat('Y-m-d', $tanggal)) {
    $errors[] = "Format tanggal tidak valid. Gunakan format YYYY-MM-DD.";
}

// Validasi Tanggal Surat
if (empty($tanggal_surat)) {
    $errors[] = "Tanggal pembuatan surat harus diisi.";
} elseif (!DateTime::createFromFormat('Y-m-d', $tanggal_surat)) {
    $errors[] = "Format tanggal pembuatan surat tidak valid. Gunakan format YYYY-MM-DD.";
}

// Jika ada kesalahan, tampilkan pesan kesalahan
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    // Hentikan eksekusi lebih lanjut
    exit;
}

// Jika tidak ada kesalahan, lanjutkan dengan pemrosesan data
require 'dompdf/autoload.inc.php'; // Pastikan path ini sesuai dengan lokasi autoload.php

use Dompdf\Dompdf;

// Inisialisasi Dompdf
$dompdf = new Dompdf();

// Buat HTML untuk surat
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perintah Tugas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; }
        .content { margin: 20px; }
        .footer { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Nama Instansi</h2>
        <h3>Surat Perintah Tugas</h3>
        <p>Nomor: ' . htmlspecialchars($nomor_surat) . '</p>
    </div>
    <div class="content">
        <p>Tujuan: ' . htmlspecialchars($tujuan_surat) . '</p>
        <p>Nomor Referensi: ' . htmlspecialchars($nomor_referensi) . '</p>
        <h4>Petugas 1</h4>
        <p>Nama: ' . htmlspecialchars($nama1) . '</p>
        <p>NIP: ' . htmlspecialchars($nip1) . '</p>
        <p>Pangkat/Golongan: ' . htmlspecialchars($pangkat1) . '</p>
        <h4>Petugas 2</h4>
        <p>Nama: ' . htmlspecialchars($nama2) . '</p>
        <p>NIP: ' . htmlspecialchars($nip2) . '</p>
        <p>Pangkat/Golongan: ' . htmlspecialchars($pangkat2) . '</p>
        <p>Tujuan Perjalanan: ' . htmlspecialchars($tujuan_perjalanan) . '</p>
        <p>Hari/Tanggal: ' . htmlspecialchars($tanggal) . '</p>
        <p>Waktu: ' . htmlspecialchars($waktu) . '</p>
        <p>Tempat: ' . htmlspecialchars($tempat) . '</p>
        <p>Tanggal Pembuatan Surat: ' . htmlspecialchars($tanggal_surat) . '</p>
        <p>Berangkat Dari: ' . htmlspecialchars($berangkat_dari) . '</p>
        <p>Tanggal Berangkat: ' . htmlspecialchars($tanggal_berangkat) . '</p>
        <p>Tujuan Tempat: ' . htmlspecialchars($tujuan_tempat) . '</p>
    </div>
    <div class="footer">
        <p>Jakarta, ' . date('d-m-Y') . '</p>
        <p>Mengetahui,</p>
        <p>____________________</p>
    </div>
</body>
</html>
';

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// Set ukuran kertas dan orientasi
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output PDF ke browser
$dompdf->stream('surat_perintah_tugas.pdf', ['Attachment' => false]);
?>