<?php
    include ("db.php");
    header("Content-type: text/html; charset=utf-8");
    
    /* 
    ==========================================================================
         가격 확인을 위한 결제 금액 조회                                                                 
    --------------------------------------------------------------------------
    */
    /**ordr_mony를 위해서 특정 유저의 데이터베이스 카트에 있는 값들을 전부 더함 */
    //Prepared Statement No SQLi
    $buyr_name = $_SESSION['buyr_name'];
    $stmt = $conn->prepare("SELECT sum(good_mny) AS total FROM orders WHERE buyr_name = ?");
    $stmt->bind_param('s',$buyr_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $sql_row = mysqli_fetch_array($result);
    //Prepared Statement 종료
    $stmt->close();
    //정상적인 결제 금액
    $total_price = $sql_row['total'];
    

    /* 
    ==========================================================================
         결제 API URL                                                                 
    --------------------------------------------------------------------------
    */
    $target_URL = "https://stg-spl.kcp.co.kr/gw/enc/v1/payment"; // 개발서버
    //$target_URL = "https://spl.kcp.co.kr/gw/enc/v1/payment"; // 운영서버
    /* 
    ==========================================================================
         요청정보                                                                
    --------------------------------------------------------------------------
    */
    $tran_cd            = $_POST[ "tran_cd"  ]; // 요청코드
    $site_cd            = $_POST[ "site_cd"  ]; // 사이트코드
    // 인증서 정보(직렬화)
    $kcp_cert_info      = "-----BEGIN CERTIFICATE-----MIIDgTCCAmmgAwIBAgIHBy4lYNG7ojANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yMTA2MjkwMDM0MzdaFw0yNjA2MjgwMDM0MzdaMHAxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTERMA8GA1UECgwITG9jYWxXZWIxETAPBgNVBAsMCERFVlBHV0VCMRkwFwYDVQQDDBAyMDIxMDYyOTEwMDAwMDI0MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAppkVQkU4SwNTYbIUaNDVhu2w1uvG4qip0U7h9n90cLfKymIRKDiebLhLIVFctuhTmgY7tkE7yQTNkD+jXHYufQ/qj06ukwf1BtqUVru9mqa7ysU298B6l9v0Fv8h3ztTYvfHEBmpB6AoZDBChMEua7Or/L3C2vYtU/6lWLjBT1xwXVLvNN/7XpQokuWq0rnjSRThcXrDpWMbqYYUt/CL7YHosfBazAXLoN5JvTd1O9C3FPxLxwcIAI9H8SbWIQKhap7JeA/IUP1Vk4K/o3Yiytl6Aqh3U1egHfEdWNqwpaiHPuM/jsDkVzuS9FV4RCdcBEsRPnAWHz10w8CX7e7zdwIDAQABox0wGzAOBgNVHQ8BAf8EBAMCB4AwCQYDVR0TBAIwADANBgkqhkiG9w0BAQsFAAOCAQEAg9lYy+dM/8Dnz4COc+XIjEwr4FeC9ExnWaaxH6GlWjJbB94O2L26arrjT2hGl9jUzwd+BdvTGdNCpEjOz3KEq8yJhcu5mFxMskLnHNo1lg5qtydIID6eSgew3vm6d7b3O6pYd+NHdHQsuMw5S5z1m+0TbBQkb6A9RKE1md5/Yw+NymDy+c4NaKsbxepw+HtSOnma/R7TErQ/8qVioIthEpwbqyjgIoGzgOdEFsF9mfkt/5k6rR0WX8xzcro5XSB3T+oecMS54j0+nHyoS96/llRLqFDBUfWn5Cay7pJNWXCnw4jIiBsTBa3q95RVRyMEcDgPwugMXPXGBwNoMOOpuQ==-----END CERTIFICATE-----";
    $enc_data           = $_POST[ "enc_data" ]; // 암호화 인증데이터
    $enc_info           = $_POST[ "enc_info" ]; // 암호화 인증데이터  
    $ordr_mony          = $total_price; // 결제요청금액   ** 1 원은 실제로 업체에서 결제하셔야 될 원 금액을 넣어주셔야 합니다. 결제금액 유효성 검증 **
    /* = -------------------------------------------------------------------------- = */
    $use_pay_method     = $_POST[ "use_pay_method" ]; // 결제 방법
    $ordr_idxx          = $_POST[ "ordr_idxx" ]; // 주문번호
    
    $data = [
              'tran_cd'        => $tran_cd, 
              'site_cd'        => $site_cd,
              'kcp_cert_info'  => $kcp_cert_info,
              'enc_data'       => $enc_data,
              'enc_info'       => $enc_info,
              'ordr_mony'      => $ordr_mony
            ];
    
    $req_data = json_encode($data);
    
    $header_data = array( "Content-Type: application/json", "charset=utf-8" );
    
    // API REQ
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target_URL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    // API RES
    $res_data  = curl_exec($ch); 
    
    /* 
    ==========================================================================
    응답정보                                                               
    --------------------------------------------------------------------------
    */
    // 공통
    $res_cd         = "";
    $res_msg        = "";
    $res_en_msg     = "";
    $tno            = "";
    $amount         = "";
    $app_time       = ""; // 공통(카드:승인시간,계좌이체:계좌이체시간,가상계좌:가상계좌 채번시간)
    // 카드
    $card_cd     = ""; // 카드코드
    $card_name   = ""; // 카드사
    $app_no      = ""; // 승인번호
    $quota       = ""; // 할부개월
    $noinf       = ""; // 무이자여부
    // 포인트
    $pnt_issue        = ""; // 포인트 서비스사
    $add_pnt          = ""; // 발생 포인트
    $use_pnt          = ""; // 사용가능 포인트
    $rsv_pnt          = ""; // 적립 포인트
    $pnt_app_time     = ""; // 승인시간
    $pnt_app_no       = ""; // 승인번호
    $pnt_amount       = ""; // 적립금액 or 사용금액
    // 계좌이체
    $bank_name        = ""; // 은행명
    $bank_code        = ""; // 은행코드
    // 가상계좌
    $bankname         = ""; // 입금할 은행
    $bankcode         = ""; // 입금할 은행코드
    $depositor        = ""; // 입금할 계좌 예금주
    $account          = ""; // 입금할 계좌 번호
    $va_date          = ""; // 가상계좌 입금마감시간
    // 휴대폰
    $commid           = ""; // 통신사 코드
    $mobile_no        = ""; // 휴대폰 번호
    // 상품권
    $tk_van_code      = ""; // 발급사 코드
    $tk_app_no        = ""; // 승인 번호
    $tk_app_time      = ""; // 상품권 승인시간
    // 현금 영수증
    $cash_yn        = $_POST[ "cash_yn"        ]; // 현금 영수증 등록 여부
    $cash_tr_code   = $_POST[ "cash_tr_code"   ]; // 현금 영수증 발행 구분
    $cash_id_info   = $_POST[ "cash_id_info"   ]; // 현금 영수증 등록 번호
    $cash_authno    = ""; // 현금 영수증 승인 번호
    $cash_no        = ""; // 현금 영수증 거래 번호    
    
    // RES JSON DATA Parsing
    $json_res = json_decode($res_data, true);
    
    $res_cd = $json_res["res_cd"];
    $res_msg = $json_res["res_msg"];
    
    if ( $res_cd == "0000" )
    {
        $tno       = $json_res["tno"];
        $res_cd    = $json_res["res_cd"];
        $res_msg   = $json_res["res_msg"];
        $amount    = $json_res["amount"];
        
        // 카드
        if ( $use_pay_method == "100000000000" )
        {
            $card_cd   = $json_res["card_cd"];
            $card_name = $json_res["card_name"];
            $app_no    = $json_res["app_no"];
            $app_time  = $json_res["app_time"];
            $noinf     = $json_res["noinf"];
            $quota     = $json_res["quota"];
            // 포인트 복합결제
            if(isset($json_res["pnt_issue"])){
            $pnt_issue = $json_res["pnt_issue"];
            }
            if ( $pnt_issue == "SCSK" || $pnt_issue ==  "SCWB" )
            {
                $pnt_issue    = $json_res["pnt_issue"];
                $add_pnt      = $json_res["add_pnt"];
                $use_pnt      = $json_res["use_pnt"];
                $rsv_pnt      = $json_res["rsv_pnt"];
                $pnt_app_time = $json_res["pnt_app_time"];
                $pnt_app_no   = $json_res["pnt_app_no"];
                $pnt_amount   = $json_res["pnt_amount"];
                // 현금영수증 발급시
                if ( $cash_yn == "Y" )
                {
                    $cash_authno = $json_res["cash_authno"];
                    $cash_no     = $json_res["cash_no"];
                }
            }
        }
        // 계좌이체
        else if ( $use_pay_method == "010000000000" )
        {
            $bank_name = $json_res["bank_name"];
            $bank_code = $json_res["bank_code"];
            $app_time  = $json_res["app_time"];
            
            // 현금영수증 발급시
            if ( $cash_yn == "Y" )
            {
                $cash_authno = $json_res["cash_authno"];
                $cash_no     = $json_res["cash_no"];
            }
        }
        // 가상계좌
        else if ( $use_pay_method == "001000000000" )
        {
            $bankname  = $json_res["bankname"];
            $bankcode  = $json_res["bankcode"];
            $depositor = $json_res["depositor"];
            $account   = $json_res["account"];
            $va_date   = $json_res["va_date"];
            $app_time  = $json_res["app_time"];
        }
        // 포인트
        else if ( $use_pay_method == "000100000000" )
        {
            $pnt_issue    = $json_res["pnt_issue"];
            $add_pnt      = $json_res["add_pnt"];
            $use_pnt      = $json_res["use_pnt"];
            $rsv_pnt      = $json_res["rsv_pnt"];
            $pnt_app_time = $json_res["pnt_app_time"];
            $pnt_app_no   = $json_res["pnt_app_no"];
            $pnt_amount   = $json_res["pnt_amount"];
            // 현금영수증 발급시
            if ( $cash_yn == "Y" )
            {
                $cash_authno = $json_res["cash_authno"];
                $cash_no     = $json_res["cash_no"];
            }
        }
        // 휴대폰
        else if ( $use_pay_method == "000010000000" )
        {
            $app_time    = $json_res["app_time"];
            $commid      = $json_res["commid"];
            $mobile_no   = $json_res["mobile_no"];
        }
        // 상품권
        else if ( $use_pay_method == "000000001000" )
        {
            $tk_van_code  = $json_res["tk_van_code"];
            $tk_app_no    = $json_res["tk_app_no"];
            $tk_app_time  = $json_res["tk_app_time"];
        }
    }
    
    curl_close($ch); 
    
    if ( $res_cd === "0000" )
    {
         /* 
    ==========================================================================
         DB 처리 구현 + 승인 결과 DB 처리 실패시 : 자동취소
    --------------------------------------------------------------------------
         승인 결과를 DB 작업 하는 과정에서 정상적으로 승인된 건에 대해
    DB 작업을 실패하여 DB update 가 완료되지 않은 경우, 자동으로
         승인 취소 요청을 하는 프로세스가 구성되어 있습니다.

    DB 작업이 실패 한 경우, bSucc 라는 변수(String)의 값을 "false"
         로 설정해 주시기 바랍니다. (DB 작업 성공의 경우에는 ""로 설정하면 됩니다.)
    --------------------------------------------------------------------------
    */
    //DB 업데이트 (거래 승인/확인) 구현
    /**User exists in the 'payments' table? */
    //Prepared Statement No SQLi
    $stmt = $conn->prepare("SELECT * FROM payments WHERE buyr_name = ?");
    $stmt->bind_param('s',$buyr_name);
    $stmt->execute();
    $result = $stmt->get_result();
    //Prepared Statement 종료
    $stmt->close();

    //If there's user data == UPDATE query (RACE CONDITION POINT)
    if(isset($result)){
        $stmt = $conn->prepare("UPDATE payments SET pay_method = ?, tno = ?, amount = (SELECT sum(good_mny) FROM orders WHERE buyr_name = ? ) WHERE buyr_name = ? ");
        $stmt->bind_param('ssss',$use_pay_method,$tno,$buyr_name,$buyr_name);
        $stmt->execute();
        //If update logic failed
        if($stmt === false)
        {
            $bSucc = "false";
        //Update successful
        } else {
            $bSucc = "";
        }
        //Prepared Statement 종료
        $stmt->close();
    //If there's no user data == INSERT query
    } else {
        $stmt = $conn->prepare("INSERT INTO payments (buyr_name, pay_method, tno, amount) VALUES (?, ?, ?, (SELECT sum(good_mny) FROM orders WHERE buyr_name = ? ))");
        $stmt->bind_param('ssss',$buyr_name,$use_pay_method,$tno,$buyr_name);
        $stmt->execute();
        $update_result = $stmt->get_result();
        //If update logic failed
        if($stmt === false)
        {
            $bSucc = "false";
        //Update successful
        } else {
            $bSucc = "";
        }
        //Prepared Statement 종료
        $stmt->close();
    }

    //$bSucc = "";

        if ( $bSucc === "false")
        {
            $res_data      = "";
            $req_data      = "";
            $kcp_sign_data = "";
            /* 
            ==========================================================================
            취소 API URL                                                           
            --------------------------------------------------------------------------
            */
            $target_URL = "https://stg-spl.kcp.co.kr/gw/mod/v1/cancel"; // 개발서버
            //$target_URL = "https://spl.kcp.co.kr/gw/mod/v1/cancel"; // 운영서버
            
            // 서명데이터생성에시
            // site_cd(사이트코드) + "^" + tno(거래번호) + "^" + mod_type(취소유형)
            // NHN KCP로부터 발급받은 개인키(PRIVATE KEY)로 SHA256withRSA 알고리즘을 사용한 문자열 인코딩 값
            $cancel_target_data = $site_cd . "^" . $tno . "^" . "STSC";
            /*
             ==========================================================================
             privatekey 파일 read
             --------------------------------------------------------------------------
             */
            $key_data = file_get_contents('C:\xampp\htdocs\KCP\certificate\splPrikeyPKCS8.pem');
            
            /*
             ==========================================================================
             privatekey 추출
             'changeit' 은 테스트용 개인키비밀번호
             --------------------------------------------------------------------------
             */
            $pri_key = openssl_pkey_get_private($key_data,'changeit');
            
            /*
             ==========================================================================
             sign data 생성
             --------------------------------------------------------------------------
             */
            // 결제 취소 signature 생성
            openssl_sign($cancel_target_data, $signature, $pri_key, 'sha256WithRSAEncryption');
            //echo "cancel_signature :".base64_encode($signature)."<br><br>";
            $kcp_sign_data = base64_encode($signature);
            
            $data = [
                'site_cd'        => $site_cd,
                'kcp_cert_info'  => $kcp_cert_info,
                'kcp_sign_data'  => $kcp_sign_data,
                'tno'            => $tno,
                'mod_type'       => 'STSC',
                'mod_desc'       => '가맹점 DB 처리 실패(자동취소)',
            ];
            
            $req_data = json_encode($data);
            
            $header_data = array( "Content-Type: application/json", "charset=utf-8" );
            
            // API REQ
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $target_URL);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            // API RES
            $res_data  = curl_exec($ch);
            
            // RES JSON DATA Parsing
            $json_res = json_decode($res_data, true);
            
            $res_cd = $json_res["res_cd"];
            $res_msg = $json_res["res_msg"];

            curl_close($ch); 
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>*** NHN KCP API SAMPLE ***</title>
    <meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
    <link href="static/css/style.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        /* 신용카드 영수증 */ 
        /* 실결제시 : "https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=card_bill&tno=" */ 
        /* 테스트시 : "https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=card_bill&tno=" */ 
         function receiptView( tno, ordr_idxx, amount ) 
        {
            receiptWin = "https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=card_bill&tno=";
            receiptWin += tno + "&";
            receiptWin += "order_no=" + ordr_idxx + "&"; 
            receiptWin += "trade_mony=" + amount ;
    
            window.open(receiptWin, "", "width=455, height=815"); 
        }
    
        /* 현금 영수증 */ 
        /* 실결제시 : "https://admin8.kcp.co.kr/assist/bill.BillActionNew.do" */ 
        /* 테스트시 : "https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do" */   
        function receiptView2( cash_no, ordr_idxx, amount ) 
        {
            receiptWin2 = "https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=cash_bill&cash_no=";
            receiptWin2 += cash_no + "&";             
            receiptWin2 += "order_id="     + ordr_idxx + "&";
            receiptWin2 += "trade_mony="  + amount ;
    
            window.open(receiptWin2, "", "width=370, height=625"); 
        }
    
        /* 가상 계좌 모의입금 페이지 호출 */
        /* 테스트시에만 사용가능 */
        /* 실결제시 해당 스크립트 주석처리 */
        function receiptView3() 
        {
            receiptWin3 = "http://devadmin.kcp.co.kr/Modules/Noti/TEST_Vcnt_Noti.jsp"; 
            window.open(receiptWin3, "", "width=520, height=300"); 
        }
    </script>
</head>
<body oncontextmenu="return false;">
    <div class="wrap">
        <!-- header -->
        <div class="header">
            <a href="index.html" class="btn-back"><span>뒤로가기</span></a>
            <h1 class="title">결제가 완료 되었습니다!</h1>
        </div>
        <!-- //header -->
        <!-- contents -->
        <div id="skipCont" class="contents">
            <!--
            <h2 class="title-type-3">요청  DATA</h2>
            <ul class="list-type-1">
                <li>
                    <div class="left">
                        <p class="title"></p>
                    </div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-3">
                            <textarea style="height:200px; width:450px" readonly>$req_data</textarea>
                        </div>
                    </div>
                </li>
            </ul>-->

            <h2 class="title-type-3">응답  DATA </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left">
                        <p class="title"></p>
                    </div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-3">
                            <textarea style="height:200px; width:450px" readonly><?=$res_data ?></textarea>
                        </div>
                    </div>
                </li>
            </ul>
            <h2 class="title-type-3">처리 결과 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">결과코드</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$res_cd ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">결과메세지</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$res_msg ?><br/>
                        </div>
                    </div>
                </li>
                 <?php 
                 //DB처리 실패 로직
                 if ( $bSucc === "false" ) 
                 {
                 ?>
                <li>
                    <div class="left"><p class="title">결과 상세 메세지</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?php
                            if ( $res_cd === "0000" )
                            {
                                echo('결제는 정상적으로 이루어졌지만 쇼핑몰에서 결제 결과를 처리하는 중 오류가 발생하여 자동으로 취소 처리 되었습니다.');                            
                            }
                            else 
                            {
                                echo('결제는 정상적으로 이루어졌지만 쇼핑몰에서 결제 결과를 처리하는 중 오류가 발생하여 자동으로 취소 처리 요청하였으나, 취소가 실패 되었습니다.');                            
                            }
                            ?>
                        </div>
                    </div>
                </li>
                <?php
                 }
                ?>
            </ul>
            <?php
            //DB처리 성공 + 결제성공 로직
            if ( $res_cd === "0000" && $bSucc === "" )
            {
                $sql = "TRUNCATE TABLE orders";
                $result = mysqli_query($conn, $sql);
            ?>
            <h2 class="title-type-3">공통 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">KCP 거래번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$tno ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">결제금액</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$amount ?>
                        </div>
                    </div>
                </li>
            </ul>
            <?php
                // 신용카드 결제 결과 출력
                if ( $use_pay_method == "100000000000" )
                {
            ?>
            <h2 class="title-type-3">카드 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">카드</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$card_name ?>(<?=$card_cd ?>)
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">승인번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$app_no ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">할부개월</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$quota ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">무이자여부</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$noinf ?>
                        </div>
                    </div>
                </li>
                <?php
                    // 복합결제(포인트+신용카드) 승인 결과 처리
                    if ( $pnt_issue == "SCSK" || $pnt_issue ==  "SCWB"  )
                    {
                ?>
                <li>
                    <div class="left"><p class="title">포인트사</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_issue ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">포인트 승인시간</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_app_time ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">포인트 승인번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_app_no ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">적립금액  or 사용금액</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_amount ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">발생 포인트</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$add_pnt ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">사용가능 포인트</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$use_pnt ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">총 누적 포인트</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$rsv_pnt ?>
                        </div>
                    </div>
                </li>
                <!-- 포인트 현금영수증 출력 -->
                <?php 
                        if ( $cash_yn == "Y" )
                        {
                ?>
                <li>
                    <div class="left"><p class="title">현금영수증 확인</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <a href="javascript:receiptView2('<?= $cash_no ?>', '<?= $ordr_idxx ?>', '<?= $pnt_amount ?>' )"><span style="color:blue">현금영수증을  확인합니다.</span></a>
                        </div>
                    </div>
                </li>
                <?php 
                        }
                    }
                ?>
                <!-- 신용카드 영수증 확인 -->
                <li>
                    <div class="left"><p class="title">영수증 확인</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <a href="javascript:receiptView('<?=$tno?>','<?=$ordr_idxx?>','<?=$amount?>')"><span style="color:blue">영수증을 확인합니다.</span></a>
                        </div>
                    </div>
                </li>
            </ul>
            <?php
                }
                // 계좌이체 결과 출력
                else if ( $use_pay_method == "010000000000" )
                {
            ?>
            <h2 class="title-type-3">계좌이체 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">계좌이체시간</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$app_time ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">이체은행</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$bank_name ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">은행코드</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$bank_code ?>
                        </div>
                    </div>
                </li>
            </ul>
            <?php
                }
                // 가상계좌 결과 출력
                else if ( $use_pay_method == "001000000000" )
                {
            ?>
            <h2 class="title-type-3">가상계좌 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">가상계좌 채번시간</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$app_time ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">채번은행</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$bankname ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">채번은행코드</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$bankcode ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">가상계좌번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$account ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">입금할 계좌 입금주</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$depositor ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">가상계좌 입금마감일자</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$va_date ?>
                        </div>
                    </div>
                </li>
                <!-- 모의 입금 -->
                <li>
                    <div class="left"><p class="title">가상계좌 모의입금<br/>(테스트시 사용)</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <a href="javascript:receiptView3()"><span style="color:blue">모의입금 페이지로 이동합니다.</span></a>
                        </div>
                    </div>
                </li>
            </ul>
            <?php
                }
                // 포인트 결과 출력
                else if ( $use_pay_method == "000100000000" )
                {
            ?>
            <h2 class="title-type-3">포인트 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">포인트사</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_issue ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">포인트 승인시간</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_app_time ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">포인트 승인번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_app_no ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">적립금액 or 사용금액</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_amount ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">발생 포인트</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$add_pnt ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">사용가능 포인트</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$use_pnt ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">총 누적 포인트</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$rsv_pnt ?>
                        </div>
                    </div>
                </li>
            </ul>
            <?php
                }
                // 휴대폰 결과 출력
                else if ( $use_pay_method == "000010000000" )
                {
            ?>
            <h2 class="title-type-3">휴대폰 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">휴대폰 결제시간</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$app_time ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">통신사 코드</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$commid ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">휴대폰 번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$mobile_no ?>
                        </div>
                    </div>
                </li>
            </ul>
            <?php
                }
                // 상품권 결과 출력
                else if ( $use_pay_method == "000000001000" )
                {
            ?>
            <h2 class="title-type-3">상품권 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">발급사 코드</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$tk_van_code ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">승인 시간</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$tk_app_time ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">승인 번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$tk_app_no ?>
                        </div>
                    </div>
                </li>
            </ul>
            
            <?php
                }
                // 현금영수증 정보 출력
                if( $cash_yn != "" )
                {
                    // 결제수단 가상계좌, 계좌이체, 포인트
                    if ( $use_pay_method == "010000000000" | $use_pay_method ==  "001000000000" | $use_pay_method == "000100000000" )
                    {
            ?>
            <h2 class="title-type-3">현금영수증 </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">현금영수증 등록여부</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$cash_yn ?>
                        </div>
                    </div>
                </li>
                <?php 
                        //현금영수증이 등록된 경우 승인번호 값이 존재
                        if( $cash_authno != "" )
                        {
                ?>
                <li>
                    <div class="left"><p class="title">현금영수증 승인번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$cash_authno ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">현금영수증 거래번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$cash_no ?>
                        </div>
                    </div>
                </li>
                <!-- 현금영수증 출력 -->
                <li>
                    <div class="left"><p class="title">현금영수증 확인</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?php 
                            // 결제수단 포인트
                            if ( $use_pay_method == "000100000000" )
                            {
                            ?>
                            <a href="javascript:receiptView2('<?= $cash_no ?>', '<?= $ordr_idxx ?>', '<?= $pnt_amount ?>' )"><span style="color:blue">현금영수증을  확인합니다.</span></a>
                            <?php
                            }
                            else 
                            {
                            ?>
                            <a href="javascript:receiptView2('<?= $cash_no ?>', '<?= $ordr_idxx ?>', '<?= $amount ?>' )"><span style="color:blue">현금영수증을  확인합니다.</span></a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </li>
            </ul>
            <?php
                        }
                    }
                }
            }
            
            ?>
            
            <ul class="list-btn-2">
                <li class="pc-only-show"><a href="index.html" class="btn-type-3 pc-wd-2">처음으로</a></li>
            </ul>
        </div>
        <div class="grid-footer">
            <div class="inner">
                <!-- footer -->
                <div class="footer">
                                     ⓒ NHN KCP Corp.
                </div>
                <!-- //footer -->
            </div>
        </div>
    </div>
    <!--//wrap-->
</body>
</html>