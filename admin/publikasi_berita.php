<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config/db.php";

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $query = mysqli_query($koneksi,
        "UPDATE berita_reses SET status='1' WHERE id_berita='$id'"
    );

    if($query){
        header("Location: berita_admin.php");
    }else{
        echo "Query Error: " . mysqli_error($koneksi);
    }

}else{
    echo "ID tidak ditemukan!";
}
?>
