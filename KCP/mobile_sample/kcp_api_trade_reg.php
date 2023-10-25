<?php
    header("Content-type: text/html; charset=utf-8");
    /*
     ==========================================================================
          거래등록 API URL
     --------------------------------------------------------------------------
     */
    $target_URL = "https://stg-spl.kcp.co.kr/std/tradeReg/register"; //개발환경
    //$target_URL = "https://spl.kcp.co.kr/std/tradeReg/register"; //운영환경
    /* 
    ==========================================================================
    요청 정보                                                          
    --------------------------------------------------------------------------
    */
    $site_cd            = $_POST[ "site_cd"  ]; // 사이트코드
    // 인증서정보(직렬화)
    $kcp_cert_info      = "-----BEGIN CERTIFICATE-----MIIDgTCCAmmgAwIBAgIHBy4lYNG7ojANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yMTA2MjkwMDM0MzdaFw0yNjA2MjgwMDM0MzdaMHAxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTERMA8GA1UECgwITG9jYWxXZWIxETAPBgNVBAsMCERFVlBHV0VCMRkwFwYDVQQDDBAyMDIxMDYyOTEwMDAwMDI0MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAppkVQkU4SwNTYbIUaNDVhu2w1uvG4qip0U7h9n90cLfKymIRKDiebLhLIVFctuhTmgY7tkE7yQTNkD+jXHYufQ/qj06ukwf1BtqUVru9mqa7ysU298B6l9v0Fv8h3ztTYvfHEBmpB6AoZDBChMEua7Or/L3C2vYtU/6lWLjBT1xwXVLvNN/7XpQokuWq0rnjSRThcXrDpWMbqYYUt/CL7YHosfBazAXLoN5JvTd1O9C3FPxLxwcIAI9H8SbWIQKhap7JeA/IUP1Vk4K/o3Yiytl6Aqh3U1egHfEdWNqwpaiHPuM/jsDkVzuS9FV4RCdcBEsRPnAWHz10w8CX7e7zdwIDAQABox0wGzAOBgNVHQ8BAf8EBAMCB4AwCQYDVR0TBAIwADANBgkqhkiG9w0BAQsFAAOCAQEAg9lYy+dM/8Dnz4COc+XIjEwr4FeC9ExnWaaxH6GlWjJbB94O2L26arrjT2hGl9jUzwd+BdvTGdNCpEjOz3KEq8yJhcu5mFxMskLnHNo1lg5qtydIID6eSgew3vm6d7b3O6pYd+NHdHQsuMw5S5z1m+0TbBQkb6A9RKE1md5/Yw+NymDy+c4NaKsbxepw+HtSOnma/R7TErQ/8qVioIthEpwbqyjgIoGzgOdEFsF9mfkt/5k6rR0WX8xzcro5XSB3T+oecMS54j0+nHyoS96/llRLqFDBUfWn5Cay7pJNWXCnw4jIiBsTBa3q95RVRyMEcDgPwugMXPXGBwNoMOOpuQ==-----END CERTIFICATE-----";
    $ordr_idxx          = $_POST[ "ordr_idxx" ]; // 주문번호
    $good_mny           = $_POST[ "good_mny" ]; // 결제 금액
    $good_name          = $_POST[ "good_name" ]; // 상품명
    $pay_method         = $_POST[ "pay_method" ]; // 결제수단
    $Ret_URL            = $_POST[ "Ret_URL" ]; // 리턴 URL
    /* ============================================================================== */
    $actionResult       = $_POST[ "ActionResult" ]; // pay_method에 매칭되는 값 (인증창 호출 시 필요)
    $van_code           = $_POST[ "van_code" ]; // (포인트,상품권 인증창 호출 시 필요)
    
    $data = [
        'site_cd'        => $site_cd,
        'kcp_cert_info'  => $kcp_cert_info,
        'ordr_idxx'      => $ordr_idxx,
        'good_mny'       => $good_mny,
        'good_name'      => $good_name,
        'pay_method'     => $pay_method,
        'Ret_URL'        => $Ret_URL,
        'escw_used'      => 'N',
        'user_agent'     => ''
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
    거래등록 응답정보                                                               
    --------------------------------------------------------------------------
    */
    $res_cd      = ""; // 응답코드
    $res_msg     = ""; // 응답메세지
    $approvalKey = ""; // 거래등록키
    $traceNo     = ""; // 추적번호
    $PayUrl      = ""; // 거래등록 PAY URL
    
    // RES JSON DATA Parsing
    $json_res = json_decode($res_data, true);
    
    $res_cd      = $json_res["Code"];
    $res_msg     = $json_res["Message"];
    $approvalKey = $json_res["approvalKey"];
    $traceNo     = $json_res["traceNo"];
    $PayUrl      = $json_res["PayUrl"];
    
    curl_close($ch); 

?>

<!DOCTYPE>
<html>
<head>
    <title>*** NHN KCP API SAMPLE ***</title>
    <meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">  
    <script type="text/javascript">

    function goReq()
    {
        <?
        // 거래등록 처리 정상
        if ( $res_cd == "0000" )
        {
        ?>
            alert("거래등록 성공");
            document.form_trade_reg.action = "order_mobile.php";
            document.form_trade_reg.submit();
        <?
        }
    
        // 거래등록 처리 실패, 여기(샘플)에서는 trade_reg page로 리턴 합니다.
        else
        {
        ?>
            alert("에러 코드 : <?=$res_cd?>, 에러 메세지 : <?=$res_msg?>");
            location.href = "./trade_reg.html";
        <?
        }
        ?>
    }
    </script>
</head>
<body onload="goReq();">
    <div class="wrap">
        <!--  거래등록 form : form_trade_reg -->
        <form name="form_trade_reg" method="post">
            <input type="hidden" name="site_cd"         value="<?=$site_cd ?>" />  <!-- 사이트 코드 -->
            <input type="hidden" name="ordr_idxx"       value="<?=$ordr_idxx ?>" /><!-- 주문번호     -->
            <input type="hidden" name="good_mny"        value="<?=$good_mny ?>" /> <!-- 결제금액     -->
            <input type="hidden" name="good_name"       value="<?=$good_name ?>" /><!-- 상품명        -->
            <!-- 인증시 필요한 파라미터(변경불가)-->
            <input type="hidden" name="pay_method"      value="<?=$pay_method ?>" />
            <input type="hidden" name="ActionResult"    value="<?=$actionResult ?>" />
            <input type="hidden" name="van_code"        value="<?=$van_code ?>" />
            <!-- 리턴 URL (kcp와 통신후 결제를 요청할 수 있는 암호화 데이터를 전송 받을 가맹점의 주문페이지 URL) -->
            <input type="hidden" name="Ret_URL"         value="<?=$Ret_URL ?>" />
            <!-- 거래등록 응답 값 -->
            <input type="hidden" name="approvalKey"     value="<?=$approvalKey ?>" />
            <input type="hidden" name="traceNo"         value="<?=$traceNo ?>" />
            <input type="hidden" name="PayUrl"          value="<?=$PayUrl ?>" />
        </form>
    </div>
<!--//wrap-->
</body>
</html>