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
	  default:return ($r+$g+$b)/3;
	  
	}
	
      }
      
      function lightenarea($x,$y,$im,$width,$height)
      {
	
	$delta[0][1]=0;
	$delta[0][2]=0;
	$delta[0][3]=0;
	$delta[1][1]=0;
	$delta[1][2]=0;
	$delta[1][3]=0;
	$delta[2][1]=0;
	$delta[2][2]=0;
	$delta[2][3]=0;
	$delta[3][1]=0;
	$delta[3][2]=0;
	$delta[3][3]=0;
	for($j=$y;$j<$y+$width;$j++)
	  for($i=$x;$i<$x+$width;$i++)
	  {
	    $col='none';
	    $deltax[$i][$j][0][1]=getcomp(imagecolorat($im[1],$x,$y),$col);
	    $deltax[$i][$j][0][2]=getcomp(imagecolorat($im[2],$x,$y),$col);
	    $deltax[$i][$j][0][3]=getcomp(imagecolorat($im[3],$x,$y),$col);
	    $delta[0][1]+=$deltax[$i][$j][0][1];
	    $delta[0][2]+=$deltax[$i][$j][0][2];
	    $delta[0][3]+=$deltax[$i][$j][0][3];
	    $col='green';
	    $deltax[$i][$j][1][1]=getcomp(imagecolorat($im[1],$x,$y),$col);
	    $deltax[$i][$j][1][2]=getcomp(imagecolorat($im[2],$x,$y),$col);
	    $deltax[$i][$j][1][3]=getcomp(imagecolorat($im[3],$x,$y),$col);
	    $delta[1][1]+=$deltax[$i][$j][1][1];
	    $delta[1][2]+=$deltax[$i][$j][1][2];
	    $delta[1][3]+=$deltax[$i][$j][1][3];
	    $col='red';
	    $deltax[$i][$j][2][1]=getcomp(imagecolorat($im[1],$x,$y),$col);
	    $deltax[$i][$j][2][2]=getcomp(imagecolorat($im[2],$x,$y),$col);
	    $deltax[$i][$j][2][3]=getcomp(imagecolorat($im[3],$x,$y),$col);
	    $delta[2][1]+=$deltax[$i][$j][2][1];
	    $delta[2][2]+=$deltax[$i][$j][2][2];
	    $delta[2][3]+=$deltax[$i][$j][2][3];
	    $col='blue';
	    $deltax[$i][$j][3][1]=getcomp(imagecolorat($im[1],$x,$y),$col);
	    $deltax[$i][$j][3][2]=getcomp(imagecolorat($im[2],$x,$y),$col);
	    $deltax[$i][$j][3][3]=getcomp(imagecolorat($im[3],$x,$y),$col);
	    $delta[3][1]+=$deltax[$i][$j][3][1];
	    $delta[3][2]+=$deltax[$i][$j][3][2];
	    $delta[3][3]+=$deltax[$i][$j][3][3];
	  }
	 $max=array();
	 $max=array();
	for($p=0;$p<4;$p++)
	{
	  $max[$p]=-100;
	  if($delta[$p][1]>=$delta[$p][2]&&$delta[$p][1]>=$delta[$p][3]) $max[$p]=1;
	  if($delta[$p][2]>=$delta[$p][1]&&$delta[$p][2]>=$delta[$p][3]) $max[$p]=2;
	  if($delta[$p][3]>=$delta[$p][1]&&$delta[$p][3]>=$delta[$p][2]) $max[$p]=3;
	  
	  
	  $min[$p]=-100;
	  if($delta[$p][1]<=$delta[$p][2]&&$delta[$p][1]<=$delta[$p][3]) $min[$p]=1;
	  if($delta[$p][2]<=$delta[$p][1]&&$delta[$p][2]<=$delta[$p][3]) $min[$p]=2;
	  if($delta[$p][3]<=$delta[$p][1]&&$delta[$p][3]<=$delta[$p][2]) $min[$p]=3;
	  
	  $med[$p]=-100;
	  if($min[$p]==1&&$max[$p]==2||$min[$p]==2&&$max[$p]==1) $med[$p]=3;
	  if($min[$p]==1&&$max[$p]==3||$min[$p]==3&&$max[$p]==1) $med[$p]=2;
	  if($min[$p]==3&&$max[$p]==2||$min[$p]==2&&$max[$p]==3) $med[$p]=1;
	  
	}
	if(($max[0]!=-100&&$min[0]!=-100&&$med[0]!=-100)||($max[1]!=-100&&$min[1]!=-100&&$med[1]!=-100)||($max[2]!=-100&&$min[2]!=-100&&$med[2]!=-100))
	{
	  
	  $delnum=0;
	  if($max[0]!=-100&&$min[0]!=-100&&$med[0]!=-100)
	  $delmax=(abs($delta[0][$min[0]]-$delta[0][$max[0]]));
	  else $delmax=0;
	  for($p=1;$p<4;$p++)
	  {
	    if($max[$p]!=-100&&$min[$p]!=-100&&$med[$p]!=-100)
	    if(abs($delta[$p][$min[$p]]-$delta[$p][$max[$p]])>$delmax)
	    {
	      $delmax=abs($delta[$p][$min[$p]]-$delta[$p][$max[$p]]);
	      $delnum=$p;
	    }
	  }
	  
	  
	if($med[$delnum]!=-100&&$min[$delnum]!=-100)
	  if(abs($delta[$delnum][$med[$delnum]]-$delta[$delnum][$max[$delnum]])>abs($delta[$delnum][$med[$delnum]]-$delta[$delnum][$min[$delnum]]))
	  {
	  
	    for($j=$y;$j<$y+$width;$j++)
	      for($i=$x;$i<$x+$width;$i++)
	      {
		imagesetpixel($im[$med[$delnum]],$i,$j,1);
		imagesetpixel($im[$min[$delnum]],$i,$j,1);
	      }
	    
	  }
	  else
	  {
	 
	    for($j=$y;$j<$y+$width;$j++)
	      for($i=$x;$i<$x+$width;$i++)
	      {
		imagesetpixel($im[$med[$delnum]],$i,$j,1);
		imagesetpixel($im[$max[$delnum]],$i,$j,1);
	      }
	    
	  }
	}
	else
	{
	  
	 
	}
	  
	 
	 
	
	
	
	
      }
     
      function lightenpixels($x,$y,$im)
      {
	$col='none';
	$delta[0][1]=getcomp(imagecolorat($im[1],$x,$y),$col);
	$delta[0][2]=getcomp(imagecolorat($im[2],$x,$y),$col);
	$delta[0][3]=getcomp(imagecolorat($im[3],$x,$y),$col);
	$col='green';
	$delta[1][1]=getcomp(imagecolorat($im[1],$x,$y),$col);
	$delta[1][2]=getcomp(imagecolorat($im[2],$x,$y),$col);
	$delta[1][3]=getcomp(imagecolorat($im[3],$x,$y),$col);
	$col='red';
	$delta[2][1]=getcomp(imagecolorat($im[1],$x,$y),$col);
	$delta[2][2]=getcomp(imagecolorat($im[2],$x,$y),$col);
	$delta[2][3]=getcomp(imagecolorat($im[3],$x,$y),$col);
	$col='blue';
	$delta[3][1]=getcomp(imagecolorat($im[1],$x,$y),$col);
	$delta[3][2]=getcomp(imagecolorat($im[2],$x,$y),$col);
	$delta[3][3]=getcomp(imagecolorat($im[3],$x,$y),$col);
	//print_r($delta);
	

	$max=array();
	for($p=0;$p<4;$p++)
	{
	  $max[$p]=-100;
	  if($delta[$p][1]>=$delta[$p][2]&&$delta[$p][1]>=$delta[$p][3]) $max[$p]=1;
	  if($delta[$p][2]>=$delta[$p][1]&&$delta[$p][2]>=$delta[$p][3]) $max[$p]=2;
	  if($delta[$p][3]>=$delta[$p][1]&&$delta[$p][3]>=$delta[$p][2]) $max[$p]=3;
	  
	  
	  $min[$p]=-100;
	  if($delta[$p][1]<=$delta[$p][2]&&$delta[$p][1]<=$delta[$p][3]) $min[$p]=1;
	  if($delta[$p][2]<=$delta[$p][1]&&$delta[$p][2]<=$delta[$p][3]) $min[$p]=2;
	  if($delta[$p][3]<=$delta[$p][1]&&$delta[$p][3]<=$delta[$p][2]) $min[$p]=3;
	  
	  $med[$p]=-100;
	  if($min[$p]==1&&$max[$p]==2||$min[$p]==2&&$max[$p]==1) $med[$p]=3;
	  if($min[$p]==1&&$max[$p]==3||$min[$p]==3&&$max[$p]==1) $med[$p]=2;
	  if($min[$p]==3&&$max[$p]==2||$min[$p]==2&&$max[$p]==3) $med[$p]=1;
	  
	}
	//echo 'max:';
	//print_r($max);
	//  die();
	
	if(($max[0]!=-100&&$min[0]!=-100&&$med[0]!=-100)||($max[1]!=-100&&$min[1]!=-100&&$med[1]!=-100)||($max[2]!=-100&&$min[2]!=-100&&$med[2]!=-100))
	{
	  
	  $delnum=0;
	  if($max[0]!=-100&&$min[0]!=-100&&$med[0]!=-100)
	  $delmax=(abs($delta[0][$min[0]]-$delta[0][$max[0]]));
	  else $delmax=0;
	  for($p=1;$p<4;$p++)
	  {
	    if($max[$p]!=-100&&$min[$p]!=-100&&$med[$p]!=-100)
	    if(abs($delta[$p][$min[$p]]-$delta[$p][$max[$p]])>$delmax)
	    {
	      $delmax=abs($delta[$p][$min[$p]]-$delta[$p][$max[$p]]);
	      $delnum=$p;
	    }
	  }
	  
	  
	if($med[$delnum]!=-100&&$min[$delnum]!=-100)
	  if(abs($delta[$delnum][$med[$delnum]]-$delta[$delnum][$max[$delnum]])>abs($delta[$delnum][$med[$delnum]]-$delta[$delnum][$min[$delnum]]))
	  {
	  //  imagesetpixel($im[$med],$x,$y,imagecolorallocatealpha($im[$med],0,0,0,127));
	    //imagesetpixel($im[$min],$x,$y,imagecolorallocatealpha($im[$min],0,0,0,127));
	   // imagesetpixel($im[$max],$x,$y,imagecolorallocatealpha($im[$min],255,0,0,0));
	   $tolight=array();
	   $tolight[]=$med[$delnum];
	   $tolight[]=$min[$delnum];
	   return($tolight);
	     // imagesetpixel($im[$med[$delnum]],$x,$y,1);
	     // imagesetpixel($im[$min[$delnum]],$x,$y,1);
	    
	  }
	  else
	  {
	   // imagesetpixel($im[$med],$x,$y,imagecolorallocatealpha($im[$med],0,0,0,127));
	   // imagesetpixel($im[$max],$x,$y,imagecolorallocatealpha($im[$max],0,0,0,127));
	   // imagesetpixel($im[$max],$x,$y,imagecolorallocatealpha($im[$min],0,255,0,0));
	   $tolight=array();
	   $tolight[]=$med[$delnum];
	   $tolight[]=$max[$delnum];
	   return($tolight);
	     // imagesetpixel($im[$med[$delnum]],$x,$y,1);
	     //imagesetpixel($im[$max[$delnum]],$x,$y,1);
	    
	  }
	}
	else
	{
	   $tolight=array();
	   return($tolight);
	  //imagesetpixel($im[1],$x,$y,imagecolorallocatealpha($im[1],255,0,0,0));
	  //imagesetpixel($im[2],$x,$y,imagecolorallocatealpha($im[2],255,0,0,0));
	  //imagesetpixel($im[3],$x,$y,imagecolorallocatealpha($im[3],255,0,0,0));
	}
	
      }
      $areasize=20;
      $foldername="images1";
      $sizex=getimagesize($foldername.'/1.png')[0];
      $sizey=getimagesize($foldername.'/1.png')[1];
       $im[1]=imagecreatefrompng($foldername.'/1.png');
       $im[2]=imagecreatefrompng($foldername.'/2.png');
       $im[3]=imagecreatefrompng($foldername.'/3.png');
       //imagealphablending($im[1],true);
       imagecolortransparent($im[1], 1);
       //imagealphablending($im[2],true);
       imagecolortransparent($im[2], 1);
     //  imagealphablending($im[3],true);
       imagecolortransparent($im[3], 1);
       
       
       for($i=1;$i<$sizex-$areasize;$i+=$areasize)
	 for($j=1;$j<$sizey-$areasize;$j+=$areasize)
	 {
	   
	   lightenarea($i,$j,$im,$areasize,$areasize);
	   
	 }
       header('Content-Type: image/jpeg');
       imagecopy($im[1], $im[2], 0, 0, 0, 0, $sizex, $sizey);
       imagecopy($im[1], $im[3], 0, 0, 0, 0, $sizex, $sizey);
       imagejpeg($im[1]);
       
       
       
       

?> 
