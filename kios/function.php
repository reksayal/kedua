<?php

session_start();


//bikin koneksi

$c = mysqli_connect('localhost','root','','kios');

//login
if(isset($_POST['login'])){
    //initiate variable
    $username = $_POST['username'];
    $password= $_POST['password'];

    $check = mysqli_query($c,"SELECT * FROM user WHERE username= '$username' and password= '$password' ");
    $hitung = mysqli_num_rows($check);

    if($hitung>0){
       //jika datanya di temukan
       //berhasil login

       $_SESSION['login'] = 'True';
       header('location:index.php');
    } else {
        //data tidak ditemukan
        //maka gagal login
        echo '
        <script>alert("Username atau Password salah");
        window.location.href="login.php"
        </script>
        ';
    }

}

      if(isset($_POST['tambahbarang'])){
          $namaproduk = $_POST['namaproduk'];
          $deskripsi = $_POST['deskripsi'];
          $stock = $_POST['stock'];
          $harga = $_POST['harga'];

          $insert = mysqli_query($c,"insert into produk (namaproduk,deskripsi,harga,stock) values ('$namaproduk','$deskripsi','$harga','$stock')");

          if($insert){
              header('location:stock.php');
          } else{
            echo '
            <script>alert("Gagal");
            window.location.href="stock.php"
            </script>
            ';
          }
      }

       if(isset($_POST['tambahpelanggan'])){
        $namapelanggan = $_POST['namapelanggan'];
        $notelp = $_POST['notelp'];
        $alamat = $_POST['alamat'];
     

        $insert = mysqli_query($c,"insert into pelanggan (namapelanggan,notelp,alamat) values ('$namapelanggan','$notelp','$alamat ')");

        if($insert){
            header('location:pelanggan.php');
        } else{
          echo '
          <script>alert("Gagal");
          window.location.href="pelanggan.php"
          </script>
          ';
        }
    }

    if(isset($_POST['tambahpesanan'])){
        $idpelanggan = $_POST['idpelanggan'];
        $notelp = $_POST['notelp'];
        $alamat = $_POST['alamat'];
     

        $insert = mysqli_query($c,"insert into pesanan (idpelanggan) values ('$idpelanggan')");

        if($insert){
            header('location:index.php');
        } else{
          echo '
          <script>alert("Gagal");
          window.location.href="index.php"
          </script>
          ';
        }
    }



    if(isset($_POST['addproduk'])){
        $idproduk = $_POST['idproduk'];
        $idp = $_POST['idp'];
        $qty = $_POST['qty'];


        $hitung1 = mysqli_query($c,"select * from produk where idproduk='$idproduk'");
        $hitung2 = mysqli_fetch_array($hitung1);
        $stocksekarang = $hitung2['stock'];

        if($stocksekarang>=$qty){

            $selisih = $stocksekarang-$qty;
            
        $insert = mysqli_query($c,"insert into detailpesanan (idpesanan,idproduk,qty) values ('$idp','$idproduk','$qty')");
        $update = mysqli_query($c,"update produk set stock='$selisih' where idproduk='$idproduk'");

        if($insert&&$update){
            header('location:view.php?idp='.$idp);
        } else{
          echo '
          <script>alert("Gagal");
          window.location.href="view.php?idp='.$idp.'"
          </script>
          ';
        }

        }else{
            echo '
          <script>alert("Stock Barang Tidak Cukup");
          window.location.href="view.php?idp='.$idp.'"   
          </script>
          ';

        }
     

    }


    if(isset($_POST['barangmasuk'])){
        $idproduk = $_POST['idproduk'];
        $qty = $_POST['qty'];

        $insertb = mysqli_query($c,"insert into masuk (idproduk,qty) values('$idproduk','$qty')");
        
        if($insertb){
            header('location:masuk.php');

        }else{
            echo '
            <script>alert("Gagal");
            window.location.href="masuk.php"   
            </script>
            ';

        }
    }


    if(isset($_POST['hapusprodukpesanan'])){
        $idp = $_POST['idp'];
        $idpr = $_POST['idpr'];
        $idorder = $_POST['idorder'];

        $cek1 = mysqli_query($c,"select * from detailpesanan where iddetailpesanan='$idp'");
        $cek2 = mysqli_fetch_array($cek1);
        $qtysekarang = $cek2['qty'];

        $cek3 = mysqli_query($c,"select * from produk where idproduk='$idpr'");
        $cek4 = mysqli_fetch_array($cek3);
        $stocksekarang = $cek4['stock'];


        $hitung = $stocksekarang+$qtysekarang;

        $update = mysqli_query($c,"update produk set stock='$hitung' where idproduk='$idpr'");
        $hapus = mysqli_query($c,"delete from detailpesanan where idproduk='$idpr' and iddetailpesanan='$idp'");

        if($update&&$hapus){
            header('location:view.php?idp='.$idorder);
        }else{
            echo '
          <script>alert("Stock Barang Tidak Cukup");
          window.location.href="view.php?idp='.$idorder.'"   
          </script>
          ';

        }


    }

    if(isset($_POST['editbarang'])){
        $np = $_POST['namaproduk'];
        $desc = $_POST['deskripsi'];
        $harga = $_POST['harga'];
        $idp = $_POST['idp'];

        $query = mysqli_query($c,"update produk set namaproduk='$np', deskripsi='$desc', harga='$harga' where idproduk='$idp'");

        if($query){
            header('location:stock.php');
        }else{
            echo '
            <script>alert("Gagal");
            window.location.href="stock.php"   
            </script>
            ';
        }
    }


    if(isset($_POST['hapusbarang'])){
        $idp = $_POST['idp'];

        $query = mysqli_query($c,"delete from produk where idproduk='$idp'");
        if($query){
            header('location:stock.php');
        }else{
            echo '
            <script>alert("Gagal");
            window.location.href="stock.php"   
            </script>
            ';
        }
    }

    if(isset($_POST['editpelanggan'])){
        $np = $_POST['namapelanggan'];
        $nt = $_POST['notelp'];
        $a = $_POST['alamat'];
        $id = $_POST['idpl'];

        $query = mysqli_query($c,"update pelanggan set namapelanggan='$np', notelp='$nt', alamat='$a' where idpelanggan='$id'");

        if($query){
            header('location:pelanggan.php');
        }else{
            echo '
            <script>alert("Gagal");
            window.location.href="pelanggan.php"   
            </script>
            ';
        }
    }

    if(isset($_POST['hapuspelanggan'])){
        $idpl = $_POST['idpl'];

        $query = mysqli_query($c,"delete from pelanggan where idpelanggan='$idpl'");
        if($query){
            header('location:pelanggan.php');
        }else{
            echo '
            <script>alert("Gagal");
            window.location.href="pelanggan.php"   
            </script>
            ';
        }
    }


?>