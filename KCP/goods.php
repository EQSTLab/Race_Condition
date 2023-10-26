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

  <!--게시글 목록 윗부분-->
  <div class="mt-3">
  <h1>Miscellaneous items Store</h1>
  <div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">번호</th>
        <th scope="col">상품명</th>
        <th scope="col">가격</th>
      </tr>
    </thead>
  <tbody class="table-group-divider">
  <!--변수설정-->
  <?php
  if(isset($_GET['page'])){
    $page = $_GET['page'];
 }else{
    $page = 1;
 }

$sql = "SELECT * FROM goods";
$res = mysqli_query($conn,$sql);
$sql_row = mysqli_fetch_array($res);
$row_num = mysqli_num_rows($res);
$list = 5;
                  $block_ct = 5;


$block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
          $block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
          $block_end = $block_start + $block_ct - 1; //블록 마지막 번호
          $total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
          if($block_end > $total_page) $block_end = $total_page; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
          $total_block = ceil($total_page/$block_ct); //블럭 총 개수
		  $start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.
		  $sql2 = "select * from goods limit $start_num, $list";
		  $result = mysqli_query($conn, $sql2);
		  while($goods = mysqli_fetch_array($result)){
                  $name=$goods["name"];
                    if(strlen($name)>30)
                    {
                        $name=str_replace($goods["name"],mb_substr($goods["name"],0,30,"utf-8")."...",$goods["name"]);
                    }
//                    $id_board = $board['idx'];
?>

<!-- 게시판 목록 출력 -->

  <tr class="table-light">
    <th scope="row"><?php echo $goods['idx']; ?></th>
    <td><a href='./view.php?id=<?=$goods['idx'];?>'><?=$name;?></a></td>
    <td><?php echo $goods['price']?> Won</td>
  </tr>
</tbody>
<?php } ?>
</table>

  <div>
    <ul class="pagination d-flex justify-content-center">
      <?php
  //처음
      if($page <= 1)
      {
      echo '<li class="page-item disabled">';
      echo '<a class="page-link" href="#">처음</a></li>';
      }else{
      echo '<li class="page-item">';
      echo '<a class="page-link" href="?page=1">처음</a></li>';
      }

  //이전
      if($page <= 1)
      {

      }else{
      $pre = $page-1;
      echo '<li class="page-item">';
      echo '<a class="page-link" href="?page='.$pre.'">이전</a></li>';
      }

  //숫자
      for($i=$block_start; $i<=$block_end; $i++){
        if($page == $i){
      echo '<li class="page-item active">';
      echo '<a class="page-link" href="#">'.$i.'</a></li>';
    }else{
      echo '<li class="page-item">';
      echo '<a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
    }
  }

  //다음
      if($page >= $block_end){

      }else{
        $next = $page + 1;
        echo '<li class="page-item">';
        echo '<a class="page-link" href="?page='.$next.'">다음</a></li>';
      }

  //마지막
      if($page >= $total_page)
      {
      echo '<li class="page-item disabled">';
      echo '<a class="page-link" href="#">마지막</a></li>';
      }else{
      echo '<li class="page-item">';
      echo '<a class="page-link" href="?page='.$total_page.'">마지막</a></li>';
      }
    ?>

  </ul>
  </div>
</body>
</html>
