<?php
class OtherFunctions {
    
    //18位居民身份证有效验证
    public function IDCard($IdCardNum){
        //验证身份证是否有效
        function validateIDCard($IDCard) {
            if (strlen($IDCard) == 18) {
                return check18IDCard($IDCard);
            } elseif ((strlen($IDCard) == 15)) {
                $IDCard = convertIDCard15to18($IDCard);
                return check18IDCard($IDCard);
            } else {
                return false;
            }
        }

        //计算身份证的最后一位验证码,根据国家标准GB 11643-1999
        function calcIDCardCode($IDCardBody) {
            if (strlen($IDCardBody) != 17) {
                return false;
            }

            //加权因子 
            $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            //校验码对应值 
            $code = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $checksum = 0;

            for ($i = 0; $i < strlen($IDCardBody); $i++) {
                $checksum += substr($IDCardBody, $i, 1) * $factor[$i];
            }

            return $code[$checksum % 11];
        }

        // 将15位身份证升级到18位 
        function convertIDCard15to18($IDCard) {
            if (strlen($IDCard) != 15) {
                return false;
            } else {
                // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码 
                if (array_search(substr($IDCard, 12, 3), array('996', '997', '998', '999')) !== false) {
                    $IDCard = substr($IDCard, 0, 6) . '18' . substr($IDCard, 6, 9);
                } else {
                    $IDCard = substr($IDCard, 0, 6) . '19' . substr($IDCard, 6, 9);
                }
            }
            $IDCard = $IDCard . calcIDCardCode($IDCard);
            return $IDCard;
        }

        // 18位身份证校验码有效性检查 
        function check18IDCard($IDCard) {
            if (strlen($IDCard) != 18) {
                return false;
            }

            $IDCardBody = substr($IDCard, 0, 17); //身份证主体
            $IDCardCode = strtoupper(substr($IDCard, 17, 1)); //身份证最后一位的验证码

            if (calcIDCardCode($IDCardBody) != $IDCardCode) {
                return false;
            } else {
                return true;
            }
        }

        /**
         * 根据身份证判断,是否满足年龄条件
         * @param type $IDCard 身份证
         * @param type $minAge 最小年龄
         */
        function isMeetAgeByIDCard($IDCard, $minAge) {
            $ret = validateIDCard($IDCard);
            if ($ret === FALSE) {
                return FALSE;
            }

            if (strlen($IDCard) <= 15) {
                $IDCard = convertIDCard15to18($IDCard);
            }

            $year = date('Y') - substr($IDCard, 6, 4);
            $monthDay = date('md') - substr($IDCard, 10, 4);

            return ($year > $minAge || $year == $minAge && $monthDay > 0) ? TRUE : FALSE;
        }
        
        return isMeetAgeByIDCard($IdCardNum,18);
    }
    
    //图形码
    public function CreateImageCode($width,$height,$code){
        $image_width = $width;
        $image_height = $height;
        $image_str = $code;
 
        if (isset($_GET['w']))
        {
            $image_width = intval($_GET['w']);
        }
 
        if (isset($_GET['h']))
        {
            $image_height = intval($_GET['h']);
        }
 
        if (isset($_GET['s']))
        {
            $image_str = $_GET['s'];
        }
 
        $img = imagecreate($image_width, $image_height);
        $color = imagecolorallocate($img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($img, 0, $image_height, $image_width, 0, $color);
 
        $step = mt_rand(15, 30);
        $start = mt_rand(0, $step);
        $color = imagecolorallocate($img, mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));
        imagesetthickness($img, mt_rand(3, 10));
 
        if ($image_height > $image_width)
        {
            for ($i=$start; $i<$image_height * 2; $i+=$step)
            {
                imageline($img, 0, $i, $i, 0, $color);
            }
        }
        else
        {
            for ($i=$start; $i<$image_width * 2; $i+=$step)
            {
                imageline($img, $i, 0, 0, $i, $color);
            }
        }
 
        if ($image_str != '')
        {
            $black = imagecolorallocate($img, 0, 0, 0);
            imagestring($img, 12, 5, 5, $image_str, $black);
        }
        
        return $img;
    }
    
    
    //获取客户端IP
    public function GetClientIP(){
        if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
            $ip = getenv("HTTP_CLIENT_IP");
        }else if(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }else if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
            $ip = getenv("REMOTE_ADDR");
        }else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = "unknown";
        }
        return($ip);
    }
    
    //获取四位字母和数字的随机数
    public function GetfourStr($len){ 
        $chars_array = array( 
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", 
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", 
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", 
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", 
            "S", "T", "U", "V", "W", "X", "Y", "Z", 
        ); 
        $charsLen = count($chars_array) - 1; 
  
        $outputstr = ""; 
        for ($i=0; $i<$len; $i++) { 
            $outputstr .= $chars_array[mt_rand(0, $charsLen)]; 
        } 
        return $outputstr; 
    }
}