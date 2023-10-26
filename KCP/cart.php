<?php
include ("db.php");
if(isset($_SESSION['buyr_name'])) {
  $buyr_name = $_SESSION['buyr_name'];
}else {
        exit ("<script>alert('login plz.');location.replace('index.php');</script>");
}
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<meta http-equiv="x-ua-compatible" content="ie=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">  
<link href="static/css/style.css" rel="stylesheet" type="text/css" id="cssLink"/>
<link rel="stylesheet" type="text/css" href="/KCP/static/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/KCP/static/css/bootstrap.css">
  <script type="text/javascript">
  /****************************************************************/
        /* m_Completepayment  설명                                      */
        /****************************************************************/
        /* 인증완료시 재귀 함수                                         */
        /* 해당 함수명은 절대 변경하면 안됩니다.                        */
        /* 해당 함수의 위치는 payplus.js 보다먼저 선언되어여 합니다.    */
        /* Web 방식의 경우 리턴 값이 form 으로 넘어옴                   */
        /****************************************************************/
        function m_Completepayment( FormOrJson, closeEvent ) 
        {
            var frm = document.order_info; 
         
            /********************************************************************/
            /* FormOrJson은 가맹점 임의 활용 금지                               */
            /* frm 값에 FormOrJson 값이 설정 됨 frm 값으로 활용 하셔야 됩니다.  */
            /* FormOrJson 값을 활용 하시려면 기술지원팀으로 문의바랍니다.       */
            /********************************************************************/
            GetField( frm, FormOrJson ); 

            
            if( frm.res_cd.value == "0000" )
            {
                alert("결제 승인 요청 전,\n\n반드시 결제창에서 고객님이 결제 인증 완료 후\n\n리턴 받은 ordr_chk 와 업체 측 주문정보를\n\n다시 한번 검증 후 결제 승인 요청하시기 바랍니다."); //업체 연동 시 필수 확인 사항.
                /*
                                     가맹점 리턴값 처리 영역
                */
             
                frm.submit(); 
            }
            else
            {
                alert( "[" + frm.res_cd.value + "] " + frm.res_msg.value );
                
                closeEvent();
            }
        }
    </script>
    <script type="text/javascript" src="https://testspay.kcp.co.kr/plugin/kcp_spay_hub.js"></script> 
    <script type="text/javascript">
      /* 표준웹 실행 */
      function jsf__pay( form )
        {
            try
            {
                KCP_Pay_Execute_Web( form );  
            }
            catch (e)
            {
                /* IE 에서 결제 정상종료시 throw로 스크립트 종료 */ 
            }
        }             
    </script>
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
            <a class="nav-link" href="./goods.php">Store</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="./cart.php">Cart</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!--게시글 목록 윗부분-->
  <div class="m-3">
  <h1>Cart</h1>
  <div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">번호</th>
        <th scope="col">주문번호</th>
        <th scope="col">상품명</th>
        <th scope="col">가격</th>
        <th scope="col">할인</th>
      </tr>
    </thead>
  <tbody class="table-group-divider">
  <!--변수설정-->
  <?php

        if((isset($_REQUEST['idx']))&&($_REQUEST['mode']==="add")){
           //First pstmt
          $id = $_REQUEST['idx']; 
          $stmt = $conn->prepare("SELECT * FROM goods WHERE idx = ?");
          $stmt->bind_param('i',$id);
          $stmt->execute();
          $stuff = $stmt->get_result();
          $sql_row = mysqli_fetch_array($stuff);
          if(!isset($stuff)){
            die("Error occurr.");
          }
          $good_idx = $sql_row['idx'];
          $good_name = $sql_row['name'];
          $good_price = $sql_row['price'];
          $content_abb = $sql_row['content_abb'];
          $content = $sql_row['content'];

          /****************************************************************/
        /* Need modification if you want to put personal info.                                     */
        /****************************************************************/
        /* buyr_name, buyr_tel1, buyr_tel2, buyr_mail, discount          
        /****************************************************************/
          $sql = "INSERT INTO orders SET buyr_name='$buyr_name',good_idx='$good_idx',ordr_idxx = SHA1(NOW()),good_name='$good_name',good_mny='$good_price' ";
          $result = mysqli_query($conn, $sql);

        }
        else if(isset($_REQUEST['mode'])&&$_REQUEST['mode']==="reset"){
          $sql = "TRUNCATE TABLE orders";
          $result = mysqli_query($conn, $sql);
		}

    if(isset($_GET['page'])){
      $page = $_GET['page'];
   }else{
      $page = 1;
   }

    $sql = "SELECT * FROM orders";
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
          $sql2 = "select * from orders where buyr_name='$buyr_name' order by idx desc limit $start_num, $list";
          $result = mysqli_query($conn, $sql2);
          while($goods = mysqli_fetch_array($result)){
                      $name=$goods["ordr_idxx"];

                        if(strlen($name)>30)
                        {
                            $name=str_replace($goods["ordr_idxx"],mb_substr($goods["ordr_idxx"],0,30,"utf-8")."...",$goods["ordr_idxx"]);
                        }
    //                    $id_board = $board['idx'];
    ?>
    
    <!-- 게시판 목록 출력 -->
    
      <tr class="table-light">
        <th scope="row"><?php echo $goods['idx']; ?></th>
        <td><?=$name;?></td>
        <td><?php echo $goods['good_name']?></td>
        <td><?php echo $goods['good_mny']?></td>
        <td><?php echo $goods['discount']?></td>
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
          
      //금액total    
      $sql3 = "SELECT * FROM orders WHERE buyr_name='$buyr_name'";
      $res2 = mysqli_query($conn,$sql3);
      $ordr_idxx = SHA1(date("Y-m-d H:i:s"));
      $total_price = 0;
          while($total = mysqli_fetch_array($res2)){
            $total_price += $total['good_mny'];
          }
        ?>
      </ul>
      <form action="./cart.php" style="float:right" method="post">
        <input type="hidden" name="mode" value="reset">
        <button type="submit" class="btn btn-danger">Cart Reset</button>
      </form>
      </div>

<!--payment logic-->
<!--payment logic-->
<!--payment logic-->
<form name="order_info" method="post" action="kcp_api_pay.php">
        <div class="wrap">
            <!-- //header -->
            <!-- contents -->
            <div id="skipCont" class="contents">
                    <!-- 주문번호(ordr_idxx) -->
                    <input type="hidden" name="ordr_idxx" value="<?=$ordr_idxx?>" maxlength="40" />
                    <!-- 상품명(good_name) -->
                    <input type="hidden" name="good_name" value="장바구니 결제" />
                    <!-- 결제금액(good_mny) - ※ 필수 : 값 설정시 ,(콤마)를 제외한 숫자만 입력하여 주십시오. -->
                    <input type="hidden" name="good_mny" value="<?=$total_price?>" maxlength="9" />
                <!-- 주문정보 -->
                    <!-- 주문자명(buyr_name) -->
                    <input type="hidden" name="buyr_name" value="<?=$buyr_name?>" />   
                    <!-- 주문자 연락처1(buyr_tel1) -->
                    <input type="hidden" name="buyr_tel1" value="02-0000-0000" />
                    <!-- 휴대폰번호(buyr_tel2) -->
                    <input type="hidden" name="buyr_tel2" value="010-0000-0000" />
                    <!-- 주문자 E-mail(buyr_mail) -->
                    <input type="hidden" name="buyr_mail" value="test@test.co.kr" />
                <!-- 
                                결제 수단 정보 설정 
                    
                                결제에 필요한 결제 수단 정보를 설정합니다.                               
                                                                                          
                                신용카드 : 100000000000, 계좌이체 : 010000000000, 가상계좌 : 001000000000 
                                포인트   : 000100000000, 휴대폰   : 000010000000, 상품권   : 000000001000
                    
                                위와 같이 설정한 경우 표준웹에서 설정한 결제수단이 표시됩니다.
                                표준웹에서 여러 결제수단을 표시하고 싶으신 경우 설정하시려는 결제
                                수단에 해당하는 위치에 해당하는 값을 1로 변경하여 주십시오.
                                                                                               
                                예) 신용카드, 계좌이체, 가상계좌를 동시에 표시하고자 하는 경우
                    pay_method = "111000000000"
                                 신용카드(100000000000), 계좌이체(010000000000), 가상계좌(001000000000)에
                                 해당하는 값을 모두 더해주면 됩니다.
                                     ※ 필수
                     KCP에 신청된 결제수단으로만 결제가 가능합니다.        
                -->



                <h2 class="title-type-3">결제수단</h2>
                <ul class="list-check-1">
                    <li>
                        <input type="radio" id="radio-2-1" class="ipt-radio-1" name="pay_method" value="100000000000" checked />
                        <label for="radio-2-1"><span class="ico-radio"><span></span></span>신용카드</label>
                    </li>
                    <li>                       
                        <input type="radio" id="radio-2-2" class="ipt-radio-1" name="pay_method" value="010000000000" />
                        <label for="radio-2-2"><span class="ico-radio"><span></span></span>계좌이체</label>
                    </li>
                    <li>                        
                        <input type="radio" id="radio-2-3" class="ipt-radio-1" name="pay_method" value="001000000000" />
                        <label for="radio-2-3"><span class="ico-radio"><span></span></span>가상계좌</label>
                    </li>
                    <li>
                        <input type="radio" id="radio-2-4" class="ipt-radio-1" name="pay_method" value="000100000000" />
                        <label for="radio-2-4"><span class="ico-radio"><span></span></span>포인트</label>
                    </li>
                    <li>
                        <input type="radio" id="radio-2-5" class="ipt-radio-1" name="pay_method" value="000010000000" />
                        <label for="radio-2-5"><span class="ico-radio"><span></span></span>휴대폰</label>
                    </li>
                    <li>
                        <input type="radio" id="radio-2-6" class="ipt-radio-1" name="pay_method" value="000000001000" />
                        <label for="radio-2-6"><span class="ico-radio"><span></span></span>상품권</label>
                    </li>
                    <li>
                        <input type="radio" id="radio-2-8" class="ipt-radio-1" name="pay_method" value="111000000000" />
                        <label for="radio-2-8"><span class="ico-radio"><span></span></span>신용카드/계좌이체/가상계좌</label>
                    </li>
                </ul>
                <h1> 결제 금액: <?=$total_price?> Won</h1>
            </div>         
            
            <!-- //contents -->
            <button type="button" class="btn btn-primary" style="float:right" onclick="jsf__pay(document.order_info);" >order</button>
            <!-- 가맹점 정보 설정-->
            <input type="hidden" name="site_cd"         value="T0000" />
            <input type="hidden" name="site_name"       value="TEST SITE" />
            <input type="hidden" name="pay_method"       value="" />
            <!-- 
                            ※필수 항목
                             표준웹에서 값을 설정하는 부분으로 반드시 포함되어야 합니다.값을 설정하지 마십시오
            -->
            <input type="hidden" name="res_cd"          value=""/>
            <input type="hidden" name="res_msg"         value=""/>
            <input type="hidden" name="enc_info"        value=""/>
            <input type="hidden" name="enc_data"        value=""/>
            <input type="hidden" name="ret_pay_method"  value=""/>
            <input type="hidden" name="tran_cd"         value=""/>
            <input type="hidden" name="use_pay_method"  value=""/>
            <!-- 주문정보 검증 관련 정보 : 표준웹 에서 설정하는 정보입니다 -->
            <input type="hidden" name="ordr_chk"        value=""/>
            <!--  현금영수증 관련 정보 : 표준웹 에서 설정하는 정보입니다 -->
            <input type="hidden" name="cash_yn"         value=""/>
            <input type="hidden" name="cash_tr_code"    value=""/>
            <input type="hidden" name="cash_id_info"    value=""/>
            
            <!-- 
                ====================================================
                                 추가 옵션 정보
                                ※ 옵션 - 결제에 필요한 추가 옵션 정보를 입력 및 설정합니다. 
                ====================================================
            -->
            
            <!--사용카드 설정 여부 파라미터 입니다.(통합결제창 노출 유무) -->
            <!-- <input type="hidden" name="used_card_YN"        value="Y" /> -->
            <!-- 사용카드 설정 파라미터 입니다. (해당 카드만 결제창에 보이게 설정하는 파라미터입니다. used_card_YN 값이 Y일때 적용됩니다. -->
            <!-- <input type="hidden" name="used_card"        value="CCBC:CCKM:CCSS" /> -->
        
            <!--
                           신용카드 결제시 OK캐쉬백 적립 여부를 묻는 창을 설정하는 파라미터 입니다
                            포인트 가맹점의 경우에만 창이 보여집니다
            -->
            <!-- <input type="hidden" name="save_ocb"        value="Y" /> -->
        
            <!-- 고정 할부 개월 수 선택
                value값을 "7" 로 설정했을 경우 => 카드결제시 결제창에 할부 7개월만 선택가능  -->
            <!-- <input type="hidden" name="fix_inst"        value="07" /> -->
        
            <!-- 무이자 옵션
                    ※ 설정할부    (가맹점 관리자 페이지에 설정 된 무이자 설정을 따른다) - "" 로 설정
                    ※ 일반할부    (KCP 이벤트 이외에 설정 된 모든 무이자 설정을 무시한다) - "N" 로 설정
                    ※ 무자 할부 (가맹점 관리자 페이지에 설정 된 무이자 이벤트 중 원하는 무이자 설정을 세팅한다) - "Y" 로 설정 -->
            <!-- <input type="hidden" name="kcp_noint"       value="" /> -->
        
            <!-- 무이자 설정
                    ※ 주의 1 : 할부는 결제금액이 50,000 원 이상일 경우에만 가능
                    ※ 주의 2 : 무이자 설정값은 무이자 옵션이 Y일 경우에만 결제 창에 적용
                    예) BC 2,3,6개월, 국민 3,6개월, 삼성 6,9개월 무이자 : CCBC-02:03:06,CCKM-03:06,CCSS-03:06:04 -->
            <!-- <input type="hidden" name="kcp_noint_quota" value="CCBC-02:03:06,CCKM-03:06,CCSS-03:06:09" /> -->
        
        
            <!--  해외카드 구분하는 파라미터 입니다.(해외비자, 해외마스터, 해외JCB로 구분하여 표시) -->
            <!-- <input type="hidden" name="used_card_CCXX"        value="Y"/> -->
        
            <!--  가상계좌 은행 선택 파라미터
                 ※ 해당 은행을 결제창에서 보이게 합니다.(은행코드는 매뉴얼을 참조)  -->
            <!-- <input type="hidden" name="wish_vbank_list" value="05:03:04:07:11:23:26:32:34:81:71" /> -->
        
            <!--  가상계좌 입금 기한 설정하는 파라미터 - 발급일 + 3일 -->
            <!-- <input type="hidden" name="vcnt_expire_term" value="3"/> --> 
        
            <!-- 가상계좌 입금 시간 설정하는 파라미터
                HHMMSS형식으로 입력하시기 바랍니다
                          설정을 안하시는경우 기본적으로 23시59분59초가 세팅이 됩니다 -->
            <!-- <input type="hidden" name="vcnt_expire_term_time" value="120000" /> -->
        
            <!-- 포인트 결제시 복합 결제(신용카드+포인트) 여부를 결정할 수 있습니다.- N 일경우 복합결제 사용안함 -->
            <!-- <input type="hidden" name="complex_pnt_yn" value="N" /> -->
        
            <!-- 현금영수증 등록 창을 출력 여부를 설정하는 파라미터 입니다
                       ※ Y : 현금영수증 등록 창 출력
                       ※ N : 현금영수증 등록 창 출력 안함 
                       ※ 주의 : 현금영수증 사용 시 KCP 상점관리자 페이지에서 현금영수증 사용 동의를 하셔야 합니다 -->
            <!-- <input type="hidden" name="disp_tax_yn"     value="Y" /> -->
        
            <!--  결제창에 가맹점 사이트의 로고를 표준웹 좌측 상단에 출력하는 파라미터 입니다
                      업체의 로고가 있는 URL을 정확히 입력하셔야 하며, 최대 150 X 50  미만 크기 지원
                      ※ 주의 : 로고 용량이 150 X 50 이상일 경우 site_name 값이 표시됩니다. -->
            <!-- <input type="hidden" name="site_logo"       value="" /> -->
        
            <!-- 결제창 영문 표시 파라미터 입니다. 영문을 기본으로 사용하시려면 Y로 세팅하시기 바랍니다 --> 
            <!-- <input type="hidden" name="eng_flag"      value="Y"> -->
        
            <!--  KCP는 과세상품과 비과세상품을 동시에 판매하는 업체들의 결제관리에 대한 편의성을 제공해드리고자, 
                    복합과세 전용 사이트코드를 지원해 드리며 총 금액에 대해 복합과세 처리가 가능하도록 제공하고 있습니다
                    복합과세 전용 사이트 코드로 계약하신 가맹점에만 해당이 됩니다
                    상품별이 아니라 금액으로 구분하여 요청하셔야 합니다
                    총결제 금액은 과세금액 + 부과세 + 비과세금액의 합과 같아야 합니다. 
                (good_mny = comm_tax_mny + comm_vat_mny + comm_free_mny) -->
            <!-- <input type="hidden" name="tax_flag"       value="TG03" /> -->  <!-- 변경불가     -->
            <!-- <input type="hidden" name="comm_tax_mny"   value=""     /> -->  <!-- 과세금액     --> 
            <!-- <input type="hidden" name="comm_vat_mny"   value=""     /> -->  <!-- 부가세      -->
            <!-- <input type="hidden" name="comm_free_mny"  value=""     /> -->  <!-- 비과세 금액 -->
        
            <!--  skin_indx 값은 스킨을 변경할 수 있는 파라미터이며 총 7가지가 지원됩니다. 
                     변경을 원하시면 1부터 7까지 값을 넣어주시기 바랍니다. --> 
            <!-- <input type="hidden" name="skin_indx"      value="1" /> -->
            <!-- 상품코드 설정 파라미터 입니다.(상품권을 따로 구분하여 처리할 수 있는 옵션기능입니다.) -->
            <!-- <input type="hidden" name="good_cd"      value="" /> -->
                
            <!-- 가맹점에서 관리하는 고객 아이디 설정을 해야 합니다. 상품권 결제 시 반드시 입력하시기 바랍니다. -->
            <!-- <input type="hidden" name="shop_user_id"    value="" /> -->
                
            <!--  복지포인트 결제시 가맹점에 할당되어진 코드 값을 입력해야합니다. -->
            <!-- <input type="hidden" name="pt_memcorp_cd"   value="" /> -->
        
            <!--  결제창의 상단문구를 변경할 수 있는 파라미터 입니다. --> 
            <!-- <input type="hidden" name="kcp_pay_title"   value="상단문구추가" /> -->
        
          </div>
        </div>
    </form>

<!--payment logic ends-->
<!--payment logic ends-->
<!--payment logic ends-->

<br><br><br><br><br><br>
    </body>
    </html>
    
