<?php
include("./db.php");
if(isset($_SESSION['buyr_name'])) {
  header('Location: ./main.php');
  exit();
}else {
  if(isset($_POST['buyr_name'])&&isset($_POST['buyr_passwd'])){
  //trimming and hashing
  $buyr_id = trim($_POST['buyr_name']);
  $buyr_passwd  = trim($_POST['buyr_passwd']);
  $buyr_passwd = $buyr_passwd."f4ke_sAlT";
  $buyr_passwd = hash("sha256", $buyr_passwd);

  if (!$buyr_id || !$buyr_passwd) {
    die("<script>alert('No Blanks!');location.replace('./index.php');</script>");
  }

  //Prepare and bind
  $stmt = $conn->prepare(" SELECT * FROM user WHERE buyr_name = ? and buyr_password = ? ");
  $stmt->bind_param('ss',$buyr_id,$buyr_passwd);
  $stmt->execute();
  $result = $stmt->get_result();
  $usr_info = mysqli_fetch_array($result);
  $usr_name = $usr_info['buyr_name'];
  $usr_mail = $usr_info['buyr_mail'];
  $usr_discount = $usr_info['discount'];

  //If not match
  if (!$usr_info['buyr_name'] || !$usr_info['buyr_password']) {
  echo "<script>alert('Unassigned ID or Wrong Password, please check again!');</script>";
  echo "<script>location.href='index.php';</script>";
  exit;
  }

  //Session
  $_SESSION['buyr_name'] = $usr_name;
  $_SESSION['buyr_mail'] = $usr_mail;
  $_SESSION['discount'] = $usr_discount;
  $stmt->close();

  //Login success
  if(isset($_SESSION['buyr_name'])){
    header('Location: ./main.php');
    exit();
  }
  }else{
?>
    <html>
    <head>
      <meta charset="utf-8">
      <title>LoginPage</title>
      <link rel="stylesheet" type="text/css" href="/KCP/static/css/bootstrap.css">
      <link rel="stylesheet" type="text/css" href="/KCP/static/css/bootstrap.css">
      <link rel="stylesheet" href="/KCP/static/css/loginStyle.css">
    </head>
    <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <!--Using prepared statement, No SQLi-->
    <!--id: Louis/ pw: Jimmy-->
    <div class="container">
      <h4 class="display-4 text-center">로그인</h4>
      <form action="./index.php" method="post">
        <div class="mb-3">
          <label for="jj_id">아이디</label>
          <input type="text" id="jj_id" name="buyr_name" class="form-control">
        </div>
        <div class="mb-3">
          <label for="jj_password">비밀번호</label>
          <input type="password" id="jj_password" name="buyr_passwd" class="form-control">
        </div>
        <button type ="submit" class="btn btn-primary">로그인</button>
      </form>
    </div>
    </body>
    </html>

<?php
  }
}
?>
