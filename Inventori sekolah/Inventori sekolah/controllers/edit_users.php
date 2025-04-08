<?php
include'../koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM users WHERE id_user=$id");
$user = mysqli_fetch_assoc($data);

if(isset($_POST['submit'])) {
  $username = $_POST['username'];
  $role = $_POST['role'];
  mysqli_query($conn, "UPDATE users SET username='$username', role='$role' WHERE id_user=$id");
  header("Location: users.php");
}
?>

<form method="POST">
  Username: <input type="text" name="username" value="<?= $user['username'] ?>"><br>
  Role:
  <select name="role">
    <option <?= $user['role'] == 'admin' ? 'selected' : '' ?>>admin</option>
    <option <?= $user['role'] == 'petugas' ? 'selected' : '' ?>>petugas</option>
    <option <?= $user['role'] == 'siswa' ? 'selected' : '' ?>>siswa</option>
  </select><br>
  <button type="submit" name="submit">Simpan</button>
</form>
