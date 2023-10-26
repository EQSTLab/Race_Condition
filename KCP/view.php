<?php
include ("db.php");
if(isset($_SESSION['buyr_name'])) {
/* Some logic for login users */
}else {
        exit ("<script>alert('login plz.');location.replace('index.php');</script>");
}
if(isset($_REQUEST['id'])){
  $id = $_REQUEST['id'];
}else{
  $id = 1;
}
//Prepared Statement No SQLi
$stmt = $conn->prepare("SELECT * FROM goods WHERE idx = ?");
$stmt->bind_param('i',$id);
$stmt->execute();
$result = $stmt->get_result();
$sql_row = mysqli_fetch_array($result);

$good_name = $sql_row['name'];
$img_src = $sql_row['src'];
$good_price = $sql_row['price'];
$content_abb = $sql_row['content_abb'];
$content = $sql_row['content'];
$good_spec = $sql_row['specifications'];

$stmt->close();
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="/KCP/static/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/KCP/static/css/bootstrap.css">
<style>
img {
  position:absolute;
  max-width:100%; max-height:100%;
  width:auto; height:auto;
  margin:auto;
  top:0; bottom:0; left:0; right:0;
}
</style>
<title>Race Condition</title>
</head>
<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="./main.php">Store</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link" href="./main.php">Home
              <span class="visually-hidden"></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="./goods.php">Store</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./cart.php">Cart</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<div class="m-5">
  <div class="card mb-3 m-5">
  <h3 class="card-header"><?=$good_name?></h3>
  <div class="card-body">
  <?=$content_abb?>
  </div>
  <svg xmlns="http://www.w3.org/2000/svg" class="d-block user-select-none" width="100%" height="250" aria-label="Placeholder: Image cap" focusable="false" role="img" preserveAspectRatio="xMidYMid slice" viewBox="0 0 318 180" style="font-size:1.125rem;text-anchor:middle">
    <image href="<?=$img_src?>" width="100%" height="200">
    <rect width="100%" height="100%" fill="#868e96"></rect>
    <text x="50%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text>
  </svg>
</div>

<div class="ms-5 me-5">
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link" data-bs-toggle="tab" href="#home" aria-selected="false" role="tab" tabindex="-1">About</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link active" data-bs-toggle="tab" href="#profile" aria-selected="true" role="tab">Profile</a>
  </li>
</ul>
<div id="myTabContent" class="tab-content">
  <div class="tab-pane fade" id="home" role="tabpanel">
    <p class="mt-3"><?=$content?></p>
  </div>
  <div class="tab-pane fade active show" id="profile" role="tabpanel">
    <p class="mt-3">
        <?=$good_spec?>
    </p>
  </div>
</div>
<hr>
<h1><?=$good_price?> Won</h1>
<form action="./cart.php" method="post">
    <input type="hidden" name="idx" value="<?=$id?>">
    <input type="hidden" name="mode" value="add">
    <button type="submit" class="btn btn-primary float-right">Cart</button>
  </form>
</div>

</div>
</div>
</body>
</html>
