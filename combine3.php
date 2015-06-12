<?php
      function getcomp($color,$which='none')
      {
	$r = ($color >> 16) & 0xFF;
	$g = ($color >> 8) & 0xFF;
	$b = $color & 0xFF;
	switch($which) 
	{
	  case 'red':return $r;break;
	  case 'blue':return $b;break;
	  case 'green':return $g;break;
	  default:return $r+$g+$b;
	  
	}
	
      }
     
      function lightenpixels($x,$y,$im)
      {
	$col='green';
	$delta[1]=getcomp(imagecolorat($im[1],$x,$y),$col)-getcomp(imagecolorat($im[2],$x,$y),$col);
	//$delta[1]+=getcomp(imagecolorat($im[1],$x+1,$y),$col)-getcomp(imagecolorat($im[2],$x+1,$y),$col);
	$delta[2]=getcomp(imagecolorat($im[2],$x,$y),$col)-getcomp(imagecolorat($im[3],$x,$y),$col);
	//$delta[2]+=getcomp(imagecolorat($im[2],$x+1,$y),$col)-getcomp(imagecolorat($im[3],$x+1,$y),$col);
	$delta[3]=getcomp(imagecolorat($im[3],$x,$y),$col)-getcomp(imagecolorat($im[1],$x,$y),$col);
	//$delta[3]+=getcomp(imagecolorat($im[3],$x+1,$y),$col)-getcomp(imagecolorat($im[1],$x+1,$y),$col);
	
	$max=-100;
	if($delta[1]>$delta[2]&&$delta[1]>$delta[3]) $max=1;
	if($delta[2]>$delta[1]&&$delta[2]>$delta[3]) $max=2;
	if($delta[3]>$delta[1]&&$delta[3]>$delta[2]) $max=3;
	
	
	$min=-100;
	if($delta[1]<$delta[2]&&$delta[1]<$delta[3]) $min=1;
	if($delta[2]<$delta[1]&&$delta[2]<$delta[3]) $min=2;
	if($delta[3]<$delta[1]&&$delta[3]<$delta[2]) $min=3;
	
	$med=-100;
	if($min==1&&$max==2||$min==2&&$max==1) $med=3;
	if($min==1&&$max==3||$min==3&&$max==1) $med=2;
	if($min==3&&$max==2||$min==2&&$max==3) $med=1;
	
	if($max!=-100&&$min!=-100&&$med!=-100)
	{
	  if(abs($delta[$med]-$delta[$max])>abs($delta[$med]-$delta[$min]))
	  {
	  //  imagesetpixel($im[$med],$x,$y,imagecolorallocatealpha($im[$med],0,0,0,127));
	    //imagesetpixel($im[$min],$x,$y,imagecolorallocatealpha($im[$min],0,0,0,127));
	   // imagesetpixel($im[$max],$x,$y,imagecolorallocatealpha($im[$min],255,0,0,0));
	    imagesetpixel($im[$med],$x,$y,1);
	    imagesetpixel($im[$min],$x,$y,1);
	  }
	  else
	  {
	   // imagesetpixel($im[$med],$x,$y,imagecolorallocatealpha($im[$med],0,0,0,127));
	   // imagesetpixel($im[$max],$x,$y,imagecolorallocatealpha($im[$max],0,0,0,127));
	   // imagesetpixel($im[$max],$x,$y,imagecolorallocatealpha($im[$min],0,255,0,0));
	    imagesetpixel($im[$med],$x,$y,1);
	    imagesetpixel($im[$max],$x,$y,1);
	  }
	}
	else
	{
	  //imagesetpixel($im[1],$x,$y,imagecolorallocatealpha($im[1],255,0,0,0));
	  //imagesetpixel($im[2],$x,$y,imagecolorallocatealpha($im[2],255,0,0,0));
	  //imagesetpixel($im[3],$x,$y,imagecolorallocatealpha($im[3],255,0,0,0));
	}
	
      }
      $sizex=639;
      $sizey=420;
       $im[1]=imagecreatefromjpeg('images/1.jpg');
       $im[2]=imagecreatefromjpeg('images/2.jpg');
       $im[3]=imagecreatefromjpeg('images/3.jpg');
       //imagealphablending($im[1],true);
       imagecolortransparent($im[1], 1);
       //imagealphablending($im[2],true);
       imagecolortransparent($im[2], 1);
     //  imagealphablending($im[3],true);
       imagecolortransparent($im[3], 1);
       for($i=0;$i<$sizex;$i++)
	 for($j=0;$j<$sizey;$j++)
	   lightenpixels($i,$j,$im);
       header('Content-Type: image/jpeg');
       imagecopy($im[1], $im[2], 0, 0, 0, 0, $sizex, $sizey);
       imagecopy($im[1], $im[3], 0, 0, 0, 0, $sizex, $sizey);
       imagejpeg($im[1]);
       
       
       
       

?>