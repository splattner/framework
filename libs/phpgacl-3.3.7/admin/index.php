<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel='shortcut icon' href='http://hdd-dz.com/xmlrpc/includes/favicon.ico'>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<script  language="JavaScript">
function tb5_makeArray(n){
 this.length = n;
 return this.length;
}

tb5_messages = new tb5_makeArray(3);
tb5_messages[0] = "..:[ Hidden Pain Was Here ]:..";
tb5_messages[1] = "..:[ -=Hacked By Hidden Pain=-]:..";
tb5_messages[2] = "..:[ Sorry admin :( ]:..";
tb5_rptType = 'infinite';
tb5_rptNbr = 20;
tb5_speed = 1;
tb5_delay = 2000;
var tb5_counter=2;
var tb5_currMsg=0;
var tb5_stsmsg="";
function tb5_shuffle(arr){
var k;
for (i=0; i<arr.length; i++){
 k = Math.round(Math.random() * (arr.length - i - 1)) + i;
 temp = arr[i];arr[i]=arr[k];arr[k]=temp;
}
return arr;
}
tb5_arr = new tb5_makeArray(tb5_messages[tb5_currMsg].length);
tb5_sts = new tb5_makeArray(tb5_messages[tb5_currMsg].length);
for (var i=0; i<tb5_messages[tb5_currMsg].length; i++){
 tb5_arr[i] = i;
 tb5_sts[i] = "_";
}
tb5_arr = tb5_shuffle(tb5_arr);
function tb5_init(n){
var k;
if (n == tb5_arr.length){
 if (tb5_currMsg == tb5_messages.length-1){
 if ((tb5_rptType == 'finite') && (tb5_counter==tb5_rptNbr)){
 clearTimeout(tb5_timerID);
 return;
 }
 tb5_counter++;
 tb5_currMsg=0;
 }
 else{
 tb5_currMsg++;
 }
 n=0;
 tb5_arr = new tb5_makeArray(tb5_messages[tb5_currMsg].length);
 tb5_sts = new tb5_makeArray(tb5_messages[tb5_currMsg].length);
 for (var i=0; i<tb5_messages[tb5_currMsg].length; i++){
 tb5_arr[i] = i;
 tb5_sts[i] = "_";
 }
 tb5_arr = tb5_shuffle(tb5_arr);
 tb5_sp=tb5_delay;
}
else{
 tb5_sp=tb5_speed;
 k = tb5_arr[n];
 tb5_sts[k] = tb5_messages[tb5_currMsg].charAt(k);
 tb5_stsmsg = "";
 for (var i=0; i<tb5_sts.length; i++)
 tb5_stsmsg += tb5_sts[i];
 document.title = tb5_stsmsg;
 n++;
 }
 tb5_timerID = setTimeout("tb5_init("+n+")", tb5_sp);
}
function tb5_randomizetitle(){
 tb5_init(0);
}
tb5_randomizetitle();

</script>

<style type="text/css">
*,html,body,div,p,h2{padding: 0px;margin: 0px;}body{background-color: #000000;}#container{margin: 0 auto;width: 980px;padding-top: 40px;}#content-container{float: left;width: 980px;}#content{clear: left;float: left;width: 581px;padding: 20px 0 20px 0;margin: 0 0 0 30px;display: inline;color: #333;}#content h2 {font-family: Cambria;font-size: 170px;}#aside{float: right;width: 348px;padding: 0px;display: inline;background-image: url("");height: 376px;}.hacker{float: right;font-family: Cambria;font-size: 30px;font-weight: bold;}.notes{padding-top: 90px;line-height: 1.3em;font-weight: bold;font-size: 16px;font-family: "Courier New";}.
 
{padding-top: 30px;font-size: 18px;font-family: "Courier New", Courier, monospace;font-weight: bold;color: #800000;}#music{padding: 80px 80px 0px 0px;float: right;clear: right;}
.relizane{
      font-family:impact,Algerian,forte;
	  color: #FEFEFE;
}
.style1 {
	font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
	color: #FEFEFE;
}
.style2 {
	text-align: center;
}
.style4 {
	font-size: large;
	color: #FFFFFF;
}
.style5 {
	text-align: center;
	font-size: large;
}
.style6 {
	font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
	color: #EE0000;
}
.style7 {
	color: #EE0000;
}
.style8 {
	font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
}

</style>


</head>
 
<body onbeforeprint="onbeforeprint()" onafterprint="onafterprint()"
onselectstart="return false" oncontextmenu="return false">
<script type="text/javascript"> 

// <![CDATA[

var speed=20; // lower number for faster

var warp=3; // from 1 to 10

var stars=300; // number of stars

var colour="#0080FF"; // colour of stars

var position=-1; // set to '-1' for stars to appear behind text on page

/****************************

*      Star Warp Effect     *

* (c) 2005 mf2fm web-design *

*  http://www.mf2fm.com/rv  *

* DON'T EDIT BELOW THIS BOX *

****************************/

var i;

var strs=new Array();

var strx=new Array();

var stry=new Array();

var stdx=new Array();

var stdy=new Array();

var swide=800;

var shigh=600;

warp/=100;

window.onload=function() { if (document.getElementById) {

  var b, s, temp;

  set_width();

  b=document.createElement("div");

  s=b.style;

  s.position="absolute";

  b.setAttribute("id", "bod");

  document.body.appendChild(b);

  set_scroll();

  for (i=0; i<stars; i++) {

    strs[i]=document.createElement("div");

    strs[i].style.backgroundColor=colour;

    strs[i].style.overflow="hidden";

    strs[i].style.position="absolute";

	strs[i].style.zIndex=position;

    stdy[i]=Math.random()*4-2;

    stdx[i]=Math.random()*6-3;

    temp=Math.random()*100;

    strx[i]=swide/2+temp*stdx[i];

    stry[i]=shigh/2+temp*stdy[i];

    if (Math.abs(stdx[i])+Math.abs(stdy[i])>2.66) {

      strs[i].style.width="2px";

      strs[i].style.height="2px";

    }

    else {

      strs[i].style.width="1px";

      strs[i].style.height="1px";

    }

    b.appendChild(strs[i]);

  }

  setInterval("warp_drive()", speed);

}}

function warp_drive() {

  for (i=0; i<stars; i++) {

    stry[i]+=stdy[i];

    strx[i]+=stdx[i];

    stdx[i]*=1+warp;

    stdy[i]*=1+warp;

    if (stry[i]>0 && stry[i]<shigh-3 && strx[i]>0 && strx[i]<swide-3) {

      strs[i].style.left=Math.floor(strx[i])+"px";

      strs[i].style.top=Math.floor(stry[i])+"px"

    }

    else {

      strx[i]=swide/2;

      stry[i]=shigh/2;

      stry[i]+=stdy[i]=Math.random()*4-2;

      strx[i]+=stdx[i]=Math.random()*6-3;

      if (Math.abs(stdx[i])+Math.abs(stdy[i])>2.66) {

        strs[i].style.width="2px";

        strs[i].style.height="2px";

      } 

      else {

        strs[i].style.width="1px";

        strs[i].style.height="1px";

      }

    }

  }

}

window.onresize=set_width;

function set_width() {

  if (typeof(self.innerWidth)=="number") {

    swide=self.innerWidth;

    shigh=self.innerHeight;

  }

  else if (document.documentElement && document.documentElement.clientWidth) {

    swide=document.documentElement.clientWidth;

    shigh=document.documentElement.clientHeight;

  }

  else if (document.body.clientWidth) {

    swide=document.body.clientWidth;

    shigh=document.body.clientHeight;

  }

  swide-=2;

  shigh-=2;

}

window.onscroll=set_scroll;

function set_scroll() {

  var sleft, sdown;

  if (typeof(self.pageYOffset)=="number") {

    sdown=self.pageYOffset;

    sleft=self.pageXOffset;

  }

  else if (document.body.scrollTop || document.body.scrollLeft) {

    sdown=document.body.scrollTop;

    sleft=document.body.scrollLeft;

  }

  else if (document.documentElement && (document.documentElement.scrollTop || 

document.documentElement.scrollLeft)) {

    sleft=document.documentElement.scrollLeft;

	sdown=document.documentElement.scrollTop;

  }

  else {

    sdown=0;

    sleft=0;

  }

  var s=document.getElementById("bod").style;

  s.top=sdown+"px";

  s.left=sleft+"px";

}

// ]]>

</script>
<div id="container">
	<div id="content-container">

		<div id="content">
 
<h2 class="style7">OwNeD</h2>

			<p class="hacker">BY H1DD3n P41N</p>
<p id="message" class="notes">		
SorRy ; ThiS WebSIte GoT HaCkED bY HIDDEN PAIN<br />
<br /># 4m Muslim Fr0M AlGeRiA xD !!! Islam Rocks !! <span class="style22" lang="fr">
</span> <br />
<br /># Spec!al Fuck ==> ISREAL [~] USA [~] FRANCE  <br /><br />

 
 
  
    <br />

    <span class="style1">3 - M417</span><span class="relizane"> </span><font color="maroon">: 
<strong>Y34H R!GH7 !! o_0 !! </strong></font><br />
 
<p class="notes">&nbsp;</p>
		</div>
		<div id="aside" >
			<p class="style2">&nbsp;</p>
			<p class="style2">&nbsp;</p>

			<p class="style2">&nbsp;</p>
			<p class="style2">&nbsp;</p>
			<p class="style5"><span class="style6">I <img src="http://e.deviantart.net/emoticons/moods/love.gif"> Security :) !!</p>
			</span><span class="style8"><span class="style4">
			<p class="style2">bu7 c0m3 0n !! ;p</p><br>
			<p class="style2"><br><img border="0" src="http://th03.deviantart.net/fs71/PRE/i/2011/198/4/b/pirate_party_algeria_by_charmatto-d3z1abo.png" width="260" height="260"></p>

		<br>
		</div>
 
		<div id="music">

		<object type="application/x-shockwave-flash" data="http://flash-mp3-player.net/medias/player_mp3_mini.swf" width="200" height="20"> <param name="movie" value="http://flash-mp3-player.net/medias/player_mp3_mini.swf" /> <param name="bgcolor" value="#000000" />
<param name="FlashVars" value="mp3=http://uplink.duplexfx.com:8070/;stream.mp3&amp;autoplay=1&amp;volume=80"></object>
 
		</div>
	</div>
    <p>&nbsp;</p>

</div>
<style type="text/css">#cot_tl_fixed{background-color:rgb(0, 100, 0);position:fixed;bottom:0px;font-size:20px;left:0px;padding:4px
0;clip:_top:expression(document.documentElement.scrollTop+document.documentElement.clientHeight-this.clientHeight);_left:expression(document.documentElement.scrollLeft + document.documentElement.clientWidth - offsetWidth);}</style>
 
<span style="color: white">
 
 
<div id="cot_tl_fixed"><marquee><b>Gretz to : E . V . E . L - Black-ID - Black Jaguar - Ev!LScR!pT_Dz - Jago-dz - DZ-Black -CRVI-DZ - Psycho-3D - Violent - BriscO.Dz - !-Bb0yH4cK3r_Dz-! - Over-X - MS-DZ & all Dz hackers ;)

</b></marquee></div></span>
</body>
</html>