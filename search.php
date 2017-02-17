<?php
    $cookie = "./cookie.txt"; 
    if (! file_exists($cookie) || ! is_writable($cookie))exit('Cookie file missing or not writable.'); 
    $user = "Username";//CHnage this with your user name 
    $pass = "password";//change this with your password 
	$searchTerm = "unique";
    $md5Pass = md5($pass);//hash the pass this needs to be done for forum login api 
    //THE LOGIN API -- Now make sure that cookieuser=1 so that it generates cookie 
    $data = "do=login&url=%2Findex.php&vb_login_md5password=$md5Pass&vb_login_username=$user&cookieuser=1"; 
    $ch = curl_init(); 
    curl_setopt ($ch, CURLOPT_URL, "http://forum.sa-mp.com/login.php?do=login");//the login api link 
    //USER_AGENT option makes us looks like we are visiting site from a browser not from script 
    curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");     
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);//set the post data 
    curl_setopt($ch, CURLOPT_COOKIEJAR, realpath($cookie));//setting cookie file path to STORE cookie, you have to use absolute path here 
    curl_setopt($ch, CURLOPT_COOKIEFILE, realpath($cookie));//*************************** Read ****************************************** 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//this handles redirects 
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);//this tells curl to return result not print it on screen 
    curl_exec ($ch);     
    curl_close($ch);//close the login session 
    //now you have logged in and cookies are in cookie file so u dont need to login again so this time we search 
    $ch = curl_init(); 
    curl_setopt ($ch, CURLOPT_URL, "http://forum.sa-mp.com/search.php?do=process&searchthreadid=&query=$searchTerm&titleonly=1&searchuser=&starteronly=1&exactname=&prefixchoice%5B%5D=filterscript&prefixchoice%5B%5D=tool&prefixchoice%5B%5D=include&replyless=0&replylimit=0&searchdate=0&beforeafter=after&sortby=lastpost&order=descending&showposts=0&forumchoice%5B%5D=0&childforums=1&dosearch=Search+Now&saveprefs=1");//the search api 
    curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); 
    curl_setopt($ch, CURLOPT_COOKIEJAR, realpath($cookie)); 
    curl_setopt($ch, CURLOPT_COOKIEFILE, realpath($cookie)); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    $r = curl_exec ($ch);//now the search page is being stored in $r 
    curl_close($ch);     
    preg_match_all('|<a href="(.*)" id="thread_title_[^>]+">(.*)</a>|U', $r, $ar);//i writed this regex to exract all thread link and titles from the result 
    //now $ar[1] contains thread link in format showthread.php?t=xxxxxx 
    //and $ar[2]contians respective thread titles so we simply loop and shoow them 
    for($i = 0; $i < sizeof($ar[1]); $i++)//loop and print the result with links 
    echo "<a href='http://forum.sa-mp.com/".$ar[1][$i]."'>".$ar[2][$i]."</a> <br />";
?>