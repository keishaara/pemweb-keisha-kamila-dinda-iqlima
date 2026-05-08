<?php

require_once '../../model/admin_model.php';

$model = new AdminModel();

$totalUsers = $model->countUsers();

$totalOrganisasi = $model->countOrganisasi();

$latestUsers = $model->getLatestUsers();

$verifikasiAcara = $model->getVerifikasiAcara();

$allUsers = $model->getAllUsers();

$kategori = $model->getKategori();

?>