<?php
include ("db.php");
if(isset($_SESSION['buyr_name'])) {
/* Some logic for login users */
}else {
        exit ("<script>alert('login plz.');location.replace('index.php');</script>");
}
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
            <a class="nav-link active" href="./main.php">Home
              <span class="visually-hidden"></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./goods.php">Store</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./cart.php">Cart</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<div class="m-5">
  <h1>Today's Deals</h1>
  <div class="card mb-3 m-5">
  <h3 class="card-header">Toyota Supra A80</h3>
  <div class="card-body">
    <h5 class="card-title">"Above","To surpass","Go beyond"</h5>
    <h6 class="card-subtitle text-muted">Masterpiece of the <strong>Toyota Motor Corporation</strong></h6>
  </div>
  <svg xmlns="http://www.w3.org/2000/svg" class="d-block user-select-none" width="100%" height="250" aria-label="Placeholder: Image cap" focusable="false" role="img" preserveAspectRatio="xMidYMid slice" viewBox="0 0 318 180" style="font-size:1.125rem;text-anchor:middle">
    <a xlink:href="view.php?id=1" target="_blank">
    <image href="https://cdn.pixabay.com/photo/2015/10/01/13/36/car-967011_640.jpg" width="100%" height="200">
    <rect width="100%" height="100%" fill="#868e96"></rect>
    <text x="50%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text>
  </svg>
  <div class="card-body">
    <p class="card-text">The initial four generations of the Supra were produced from 1978 to 2002. The fifth generation has been produced since March 2019 and went on sale in May 2019. The styling of the original Supra was derived from the Toyota Celica, but it was both longer and wider. Starting in mid-1986, the A70 Supra became a separate model from the Celica. In turn, Toyota also stopped using the prefix Celica and named the car Supra. Owing to the similarity and past of the Celica's name, it is frequently mistaken for the Supra, and vice versa. The first, second and third generations of the Supra were assembled at the Tahara plant in Tahara, Aichi, while the fourth generation was assembled at the Motomachi plant in Toyota City. The 5th generation of the Supra is assembled alongside the G29 BMW Z4 in Graz, Austria by Magna Steyr.</p>
  </div>
</div>

</div>
</div>
</body>
</html>
