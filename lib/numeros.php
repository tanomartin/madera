<?php
function ControlCien($Palabras, $Cientos, $SubValue, $UltimaCentena) {
  if ($SubValue==0) return '';
  if ($SubValue==100) return 'cien';

	$Num1=substr($SubValue,0,1);
	$Num2=$SubValue-($Num1*100);

	if ($Num2==0) {
		return $Cientos[$Num1];
  }
	else {
    if ($SubValue>100) {$Result=$Cientos[$Num1].$Palabras[$Num2];}
    else {$Result=$Palabras[$SubValue];}
    
    //Fix para que en lugar de "veintiuno mil" salga "veintiun mil"
/*    if ($UltimaCentena) {
      $Aux=sprintf('%0.0f', $SubValue);
      if ((substr($Aux, strlen($Aux)-1, 1)=='1') and (substr($Aux, strlen($Aux)-2, 2)<>'11')) {
        $Result=substr($Result,0,strlen($Result)-1);
      }
    }
*/
    
    return $Result;
  }
}

function cfgValorEnLetras($Value) {
  $Palabras=array( 1=>'uno',
                   2=>'dos',
                   3=>'tres',
                   4=>'cuatro',
                   5=>'cinco',
                   6=>'seis',
                   7=>'siete',
                   8=>'ocho',
                   9=>'nueve',
                  10=>'diez',
                  11=>'once',
                  12=>'doce',
                  13=>'trece',
                  14=>'catorce',
                  15=>'quince',
                  16=>'dieciseis',
                  17=>'diecisiete',
                  18=>'dieciocho',
                  19=>'diecinueve',
                  20=>'veinte',
                  21=>'veintiuno',
                  22=>'veintidos',
                  23=>'veintitres',
                  24=>'veinticuatro',
                  25=>'veinticinco',
                  26=>'veintiseis',
                  27=>'veintisiete',
                  28=>'veintiocho',
                  29=>'veintinueve',
                  30=>'treinta',
                  31=>'treinta y uno',
                  32=>'treinta y dos',
                  33=>'treinta y tres',
                  34=>'treinta y cuatro',
                  35=>'treinta y cinco',
                  36=>'treinta y seis',
                  37=>'treinta y siete',
                  38=>'treinta y ocho',
                  39=>'treinta y nueve',
                  40=>'cuarenta',
                  41=>'cuarenta y uno',
                  42=>'cuarenta y dos',
                  43=>'cuarenta y tres',
                  44=>'cuarenta y cuatro',
                  45=>'cuarenta y cinco',
                  46=>'cuarenta y seis',
                  47=>'cuarenta y siete',
                  48=>'cuarenta y ocho',
                  49=>'cuarenta y nueve',
                  50=>'cincuenta',
                  51=>'cincuenta y uno',
                  52=>'cincuenta y dos',
                  53=>'cincuenta y tres',
                  54=>'cincuenta y cuatro',
                  55=>'cincuenta y cinco',
                  56=>'cincuenta y seis',
                  57=>'cincuenta y siete',
                  58=>'cincuenta y ocho',
                  59=>'cincuenta y nueve',
                  60=>'sesenta',
                  61=>'sesenta y uno',
                  62=>'sesenta y dos',
                  63=>'sesenta y tres',
                  64=>'sesenta y cuatro',
                  65=>'sesenta y cinco',
                  66=>'sesenta y seis',
                  67=>'sesenta y siete',
                  68=>'sesenta y ocho',
                  69=>'sesenta y nueve',
                  70=>'setenta',
                  71=>'setenta y uno',
                  72=>'setenta y dos',
                  73=>'setenta y tres',
                  74=>'setenta y cuatro',
                  75=>'setenta y cinco',
                  76=>'setenta y seis',
                  77=>'setenta y siete',
                  78=>'setenta y ocho',
                  79=>'setenta y nueve',
                  80=>'ochenta',
                  81=>'ochenta y uno',
                  82=>'ochenta y dos',
                  83=>'ochenta y tres',
                  84=>'ochenta y cuatro',
                  85=>'ochenta y cinco',
                  86=>'ochenta y seis',
                  87=>'ochenta y siete',
                  88=>'ochenta y ocho',
                  89=>'ochenta y nueve',
                  90=>'noventa',
                  91=>'noventa y uno',
                  92=>'noventa y dos',
                  93=>'noventa y tres',
                  94=>'noventa y cuatro',
                  95=>'noventa y cinco',
                  96=>'noventa y seis',
                  97=>'noventa y siete',
                  98=>'noventa y ocho',
                  99=>'noventa y nueve');
  $Cientos=array(1=>'ciento ',
                 2=>'doscientos ',
                 3=>'trescientos ',
                 4=>'cuatrocientos ',
                 5=>'quinientos ',
                 6=>'seiscientos ',
                 7=>'setecientos ',
                 8=>'ochocientos ',
                 9=>'novecientos ');

  $AuxValue=sprintf('%01.2f',abs($Value));
  $AuxInt='';
  $AuxCents='';
  
  if (strpos($AuxValue,'.')) {
    $AuxDecimal=(int)substr($AuxValue, strpos($AuxValue,'.')+1, strlen($AuxValue));
    if ($AuxDecimal<>0) $AuxCents=' con '.$AuxDecimal.'/100';
    $AuxValue=substr($AuxValue, 0, strpos($AuxValue,'.'));
  }

  if ($AuxValue==0) {
    $AuxInt='cero';
  }
  elseif ($AuxValue<1000) {
    $AuxInt=ControlCien($Palabras, $Cientos, $AuxValue, true);
  }
  elseif ($AuxValue<1000000) {
    $Aux=sprintf('%06.0f', $AuxValue);
    if ($AuxValue<2000) {$AuxInt='mil ';}
    else {$AuxInt=ControlCien($Palabras, $Cientos, (float)substr($Aux,0,3), false).' mil ';}
    $AuxInt=$AuxInt.ControlCien($Palabras, $Cientos, (float)substr($Aux,3,3), true);
  }
  elseif ($AuxValue<1000000000) {
    $Aux=sprintf('%09.0f', $AuxValue);

    if ($AuxValue<2000000) {$AuxInt='un millón ';}
    else {$AuxInt=ControlCien($Palabras, $Cientos, (float)substr($Aux,0,3), false).' millones ';}

    if (substr($Aux,3,3)!='000') {
      if (substr($Aux,3,3)=='001') {$AuxInt=$AuxInt.'mil ';}
      else {$AuxInt=$AuxInt.ControlCien($Palabras, $Cientos, (float)substr($Aux,3,3), false).' mil ';}
    }

    $AuxInt=$AuxInt.ControlCien($Palabras, $Cientos, (float)substr($Aux,6,3), true);
  }
  elseif ($AuxValue<1000000000000) {
    $Aux=sprintf('%012.0f', $AuxValue);
    
    if ($AuxValue<2000000000) {$AuxInt='mil ';}
    else {$AuxInt=ControlCien($Palabras, $Cientos, (float)substr($Aux,0,3), false).' mil ';}

    if (substr($Aux,3,3)=='001') {$AuxInt=$AuxInt.' millones ';}
    else {$AuxInt=$AuxInt.ControlCien($Palabras, $Cientos, (float)substr($Aux,3,3), false).' millones ';}

    if (substr($Aux,6,3)!='000') {
      if (substr($Aux,6,3)=='001') {$AuxInt=$AuxInt.'mil ';}
      else {$AuxInt=$AuxInt.ControlCien($Palabras, $Cientos, (float)substr($Aux,6,3), false).' mil ';}
    }

    $AuxInt=$AuxInt.ControlCien($Palabras, $Cientos, (float)substr($Aux,9,3), true);
   }
   else {
    $AuxInt='';
    $AuxCents='';
  }
  return strtoupper(substr($AuxInt,0,1)).substr($AuxInt,1,strlen($AuxInt)).$AuxCents;
}
?>
