<html>
<head>
<title>FORM 01</title>
<style>
        table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                margin-right: 0px;
                margin-left: 0px;
                margin-bottom: 0px;
                font-size: 15px;;
                padding: 5px;
                font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, "sans-serif"
        }
        font{
                margin-left: 0px;
                margin-right: 0px;
                /* font-size: 16px; */
                font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, "sans-serif";
                margin-bottom: 1px;
        }
   
        
</style>
        
    </head>
    <body>
    
        <section class="form-body">

      
    
        <header class="form-header">

        <table style="border: 0; margin-left: 0; margin-right: 0;" width="100%" >
		<tbody>
		        <tr style="border: 0;">
			        <td style=" border: 0;" >
		                        <img src="{{url('/') }}/css/forms/govlogo.jpg" width="110" height="auto" alt=""/></td>
			        <td style="border: 0;" width="60%">
			                <center>
                                        <font style="font-size:20px;;"><b>FORM 1</b></font> <br>
                                       
                                        <font>Application for</font><br>
	                               
                                        <font style="font-size: 20px;"><b>REGISTRATION OF A COMPANY</b></font><br/>
	                             
	                                <font style="font-size: 13px;">Section 4(1) of Companies Act No.7 of 2007 ("the Act")</font><br/>
                                        </center>
			        </td>
			        <td style=" border: 0;" >
		                        <img src="{{ URL::to('/') }}/css/forms/eroc.png" width="180" height="auto" alt=""/></td>
			
		        </tr>
		
		</tbody>
        </table>
		
        <table  style="border: 0;" width="100%" height="30" >
                <tbody>
                        <tr>
                                <td width="28%" style="border: 0;">Number of the company </td>
<!--
        <td width="72%" style="border: 0; margin-left: 0px; ma">
			<table   height="30" style="margin-left: 0px; margin-right: 0px; width: 100%; margin-bottom: 0px; margin-top: 15px;" >
    <tbody>
      <tr>
-->
                                <td width="8%">&nbsp;</td>
                                <td width="8%">&nbsp;</td>
                                <td width="40%">&nbsp;</td>
                                <td width="8%">&nbsp;</td>
                                <td width="8%">&nbsp;</td>
                        <!-- </tr>
                </tbody>
        </table>&nbsp;</td> -->
                        </tr>
                </tbody>
        </table>
        <br>
	
	<table  style="border: 0;" width="100%" height="30" >
                <tbody>
 
                        <tr>
                                <td width="28%" style="border: 0;">Type of the company </td>
                                <td width="72%"><?php echo $company_type; ?></td>
			
                                <!-- </table>&nbsp;</td>
                                 </tr> -->
                </tbody>
        </table>
        <br>
	
	<table style="--primary-text-color: #212121"   width="100%"  >
                <tbody>
                        <tr>
                                <td width="28%" height="20">Name approval Nmuber </td>
                                <td width="72%"><?php echo $company_info->id; ?></td>
                        </tr>
                        <tr>
                                <td width="28%" height="20">Name of Proposed Company</td>
                                <td width="72%"><?php echo $company_info->name; ?></td>
                        </tr> 
     
                        <tr>
                                <td width="28%" height="60">Registered Address</br><small>(Physical Address in Sri Lnaka and must not be a PO Box address)</small> </td>
                                <td width="72%" height="60">

                                <?php

                                $line1 = $company_address['address1'];
                                $line2 = $company_address['address2'];
                                $city = $company_address['city'];
                                $district = $company_address['district'];
                                $province = $company_address['district'];
                                $post_code = $company_address['postcode'];

                                echo $line1.','.$line2.','.$city.' '.$post_code;

                                ?>



                                </td>
                        </tr>
                </tbody>
         </table>

        <table  style="border: 0" width="100%" >
                <tbody>
                        <tr>
		                <td style="border: 0"><b><u>DECLARATION UNDER SECTION 4(1)(a) OF THE ACT</u></b></td>
		        </tr>
	                <tr>
		                <td style="border: 0; font-size: 15px;">I/We declare that to the best of my/our knowledge the name of this proposed company is not identical or simillar to that of any existing company</td>
		        </tr>
                </tbody>
        </table>

        <table  style="border: 0" width="100%"  >
                <tbody>
                        <tr>
		                <td style="border: 0"><b><u>ARTICLES OF ASSOCIATION</u></b></td>
		        </tr>
	                <tr>
		                <td style="border: 0; font-size: 13.5px;">
		                <ul>
			        <li>The articles of Association of the proposed Company shall be as set out in the first schedule to the Act.</li>
			        <li>The articles of Association of the proposed Company shall be as set out in the Annexure "A" signed by each of us</li>
			        </ul>
		                </td>
		        </tr>
                </tbody>
        </table>

       
        <table  style="border-color: #FFFFFF; border-top-color: #000000" width="100%" >
                <tbody>
                        <tr>
		                <td style="border: 0"><b><u>INITIAL DIRECTORS</u></b></td>
		        </tr>
	                <tr>
		                <td style="border: 0; font-size: 13.5px;">The following persons are the initial directors of the proposed company and signify their consent by signing below and certify that each one of them is not disqualified from being appointed or holding office as a directory of a company</td>
		        </tr>
                </tbody>
        </table>
        
        <?php 
        $dc=0;
        
        foreach($directors as $d ){ $dc++; ?>
        <table  style="border: 1" width="100%"  >
                <tbody>
                        <tr style="border-bottom: 0;">
                                <td width="2%" rowspan="2" style="border-bottom: 0;"> </td>
                                <td width="20%"  rowspan="2">Full Name </td>
                                <td width="38%" rowspan="2"> <?php echo ( $d['title'].' '.$d['firstname'].' '.$d['lastname'] );?></td>
                                <td width=40%  colspan="2"><center>Email Address</center></td>
                                <tr style="border-bottom: 0;" >
			        <td colspan="2" height="20"> <?php echo  $d['email']; ?></td></tr>
                        </tr>
                        <tr style="border: 0;">
                                <td width="2%" style="border-bottom: 0; border-top: 0;"> <?php echo $dc;?> </td>
                                <td width="20%" >NIC No/PP NO & Country if Foreigner </td>
                                <td width="38%">

                                <?php 
                                
                                 if($d['country'] != 'Sri Lanka'){
                                         echo 'Passport No: '. $d['passport'];
                                         echo '<br/>';
                                         echo 'Country: '. $d['country'];
                                 }else{
                                        echo  $d['nic'];  
                                 }

                                ?>
                                        
                        
                        </td>
                                <td width="5%" style="border-bottom: 0;" > Sig</td>
                                <td width="30%" style="border-bottom: 0;"></td>

	                </tr>
	
	                <tr style="border: 1;">
		                <td width="2%" style="border-top: 0;"> </td>
		                <td width="20%" height= "40"> Residential</br> Address</td>
		                <td width="38%" height="30">

                                <?php if($d['country'] != 'Sri Lanka'){ ?>

                                 <?php  echo $d['localAddress1'].','; ?>
                                 <?php echo ($d['localAddress2']) ? $d['localAddress2'].',' : ''; ?>
                                 <?php  echo $d['city'].','; ?>
                                 <?php  echo $d['district'].','; ?>
                                 <?php  echo $d['province'].','; ?>
                                 <?php  echo $d['postcode']; ?>

                                <?php }else { ?>

                                 <?php  echo $d['localAddress1'].','; ?>
                                 <?php echo ($d['localAddress2']) ? $d['localAddress2'].',' : ''; ?>
                                 <?php  echo $d['city'].','; ?>
                                 <?php  echo $d['postcode']; ?>
                                
                                <?php } ?>

                                </td>
                                <td width="5%" height="30" style="border-top: 0;"  ></td>
		                <td width="30%" height="30" style="border-top: 0;"> </td>
                        </tr>
	
	        </tbody>
        </table>

        <?php } ?>

<br><br>
        <table  width="100%"  height="120">
                <tbody>
              
                        <tr height="20">
                                <td width="28%" >Full Name </td>
                                <td width="72%"><?php echo $loginUser->first_name; ?> <?php echo $loginUser->last_name; ?></td>
                        </tr>
                        <tr height="20">
                                <td width="28%">Email Address</td>
                                <td width="72%"><?php echo $loginUser->email; ?></td>
                        </tr> 
                        <tr  height="20">
                                <td width="28%">Telephone No</td>
                                <td width="72%"><?php echo $loginUser->telephone; ?></td>
                        </tr> 
		        <tr  height="20">
                                <td width="28%" >Mobile No</td>
                                <td width="72%"><?php echo $loginUser->mobile; ?></td>
                        </tr > 
                        <tr  height="40">
                                <td width="28%" >Address</td>
                                <td width="72%">

                                <?php  echo $loginUserAddress->address1.','; ?>
                                 <?php  echo ( $loginUserAddress->address2 ) ? $loginUserAddress->address2.',' : ''; ?>
                                 <?php  echo $loginUserAddress->city.','; ?>
                                 <?php  echo $loginUserAddress->postcode; ?>

                                </td>
                        </tr>
                </tbody>
        </table>
       <br><br>
        <table  style="border-color: #FFFFFF;"  width="100%" height="20" >
                <tbody>
		
                         <tr>
		                <td style="border: 0"><b><u>INITIAL SHARE HOLDERS</u></b></td>
	                </tr>
	                <tr>
		                <td style="border: 0">The following persons are the initial shareholders of the proposed company</td>
	                </tr>
	                <tr>
		                <td style="border: 0;"></td>
		        </tr>
                </tbody>
        </table>
       

          <?php 

          $count_sh = count($shs);
          $sh =0;

          $sharecount = 0;
          
          
          foreach($shs as $d ){ 

                $sh++;
                  
                  ?>
        <table   style="border-left: 0; border-right: 0; border-bottom: 0;" width="100%"  >
                <tbody>
                        <tr style="border-bottom: 0;">
                                <td width="2%" rowspan="2" style="border-bottom: 0;"> </td>
                                <td width="20%"  rowspan="2">Full Name/Company Name </td>
                                <td width="38%" colspan="2" rowspan="2" ><?php echo ( $d['title'].' '.$d['firstname'].' '.$d['lastname'] );?> </td>
                                <td width=22% height="20"># of shares</td>
                                <td width=18% height="20">
                                        <?php if($d['share']['value']) {?>

                                        <?php echo $d['share']['value'].'('.$d['share']['type'].')';
                                        
                                         if($d['share']['type'] == 'core share' && $sh == 1){
                                                $sharecount +=$d['share']['value'];
                                         }
                                         if($d['share']['type'] != 'core share'){
                                                $sharecount +=$d['share']['value'];
                                         }
                                         
                                        
                                        ?>

                                        <?php } ?>

                                  
                                </td>
                                <tr style="border-bottom: 0;" height="50">
                                <td colspan="2" height="20"></td>
                        </tr>
                        <tr style="border: 0;">
                                <td width="2%" style="border-bottom: 0; border-top: 0;"><?php echo $sh; ?> </td>
                                <td width="20%" height= "50">NIC No/Passport & Country(if Foreigner)/Company No(if a company) </td>
                                <td width="38%" colspan="2" >

                                 <?php 
                                
                                if($d['country'] != 'Sri Lanka'){
                                        echo 'Passport No: '. $d['passport'];
                                        echo '<br/>';
                                        echo 'Country: '. $d['country'];
                                }else{
                                       echo  $d['nic'];  
                                }

                               ?>
                                       
                                        
                        
                                </td>
                                <td width="10%" rowspan="2" > Sign</td>
                                <td width="30%" style="border-bottom: 0;"></td>
                        </tr>
	
                        <tr style="border: 1;">
                                <td width="2%" style="border-top: 0;"> </td>
                                <td width="20%" height= "30"> Residential</br> Address</td>
                                <td width="38%" colspan="2" >

                                <?php if($d['country'] != 'Sri Lanka'){ ?>

                                <?php  echo $d['localAddress1'].','; ?>
                                <?php echo ($d['localAddress2']) ? $d['localAddress2'].',' : ''; ?>
                                <?php  echo $d['city'].','; ?>
                                <?php  echo $d['district'].','; ?>
                                <?php  echo $d['province'].','; ?>
                                <?php  echo $d['postcode']; ?>

                                <?php }else { ?>

                                <?php  echo $d['localAddress1'].','; ?>
                                <?php echo ($d['localAddress2']) ? $d['localAddress2'].',' : ''; ?>
                                <?php  echo $d['city'].','; ?>
                                <?php  echo $d['postcode']; ?>

                                <?php } ?>
                                                                        
                        
                        </td>
                                <td width="30%" style="border-top: 0;"> </td>
                        </tr>


                        <?php if($count_sh == $sh ){ ?>
                        <tr height="40" style="border-bottom: 0; border-left=0;">
                                <td style="border-right: 0; border-bottom: 0; border-left: 0; font-size: large;">+</td>
                                <td style="border-left: 0; border-right: 0; border-bottom: 0;"></td>
                                <td width="10%" style="border-left: 0; border-right: 0; border-bottom: 0;"></td>
                                <td>Total Number of Shares</td>
                                <td colspan="2"><?php echo  $sharecount; ?></td>
                        </tr>

                        <?php } ?>

	
	        </tbody>
        </table>
          <?php  } ?>

        <br>


        <table  style="border-color: #FFFFFF;"  width="100%" height="30" >
                <tbody>
		
                        <tr>
		                <td style="border: 0"><br><b><u>INITIAL SECRATARY/ SECRATARIES</u></b></td>
	                </tr>
	                <tr>
		                <td style="border: 0">The following persons(s) shall be the initial secratary/secrataries* of the proposed company and he/she/they signify his/her/their consent by signing below:-</td>
	                </tr>
                </tbody>
        </table>

        <?php  foreach($secs as $d ){ ?>
        <table style="--primary-text-color: #212121"   width="100%"  >
                <tbody>
                        <tr>
                                <td width="15%" height="30">Full Name </td>
                                <td width="60%"><?php echo ( $d['title'].' '.$d['firstname'].' '.$d['lastname'] );?></td>
                                <td rowspan="4" >Signature</td>
                                <td rowspan="4" width="10%"></td>
                        </tr>
                        <tr>
                                <td width="15%" height="30">Registration No</td>
                                <td width="60%">

                                  <?php if ($d['isReg'] ) { echo $d['regDate']; } ?>
                                </td>
	                </tr> 
                         <tr>
                                <td width="15%" height="30">Email address</td>
                                <td width="60%"><?php echo $d['email'];?></td>
	                </tr> 
                        <tr>
                                <td width="15%" height="80">Permanent Address</td>
                                <td width="60%" height="80"> <?php  echo $d['localAddress1'].','; ?>
                                <?php echo ($d['localAddress2']) ? $d['localAddress2'].',' : ''; ?>
                                <?php  echo $d['city'].','; ?>
                                <?php  echo $d['postcode']; ?></td>
	                </tr>
                </tbody>
        </table>

        <?php } ?>
        <br>

        
        <br>
        <br>

        <font style="font-size: 18px;">Signatures of initial shareholders:</font>


        <table width="100%">
	        <tbody>

                <?php  foreach($shs as $d ) {  ?>
		        <tr height="80">
                                <td width="15%" align="center">Full Name</td>
                                <td width="40%"><?php echo ( $d['title'].' '.$d['firstname'].' '.$d['lastname'] );?></td>
                                <td width="10%" >Signature</td>
                                <td width="35%"></td>
                        </tr>
                <?php } ?>      

	        </tbody>
        </table>

    <br/>
        <table  style="border: 0" width="100%"  >
                <tbody>
                        <tr >

                        <?php  
                        $d = date('d',time());
                        $m = date('m',time());
                        $y = date('Y',time());


                        ?>
       
                                <td  width="7%" align="center"><?php echo $d[0];?></td>
                                <td  width="7%" align="center"><?php echo $d[1];?></td>
                                <td style="border: 0" width="7%"></td>
                                <td  width="7%" align="center"><?php echo $m[0];?></td>
                                <td  width="7%" align="center"><?php echo $m[1];?></td>
                                <td style="border: 0" width="7%"></td>
                                <td  width="7%" align="center"><?php echo $y[0];?></td>
                                <td  width="7%" align="center"><?php echo $y[1];?></td>
                                <td  width="7%" align="center"><?php echo $y[2];?></td>
                                <td  width="7%" align="center"><?php echo $y[3];?></td>
                                <td height="6%" align="center" style="border: 0" > Date:</td>
                                <td style="border: 0"></td>
                        </tr>
                         <tr>
        
                                <td colspan="2"><center>Day</center></td>
                                <td  width="7%" style="border: 0"></td>
                                <td colspan="2"><center>Month</center></td>
                                <td  width="7%" style="border: 0"></td>
                                <td colspan="4"><center>Year</center></td>
                                <td width="23%"  style="border: 0"></td>
                                <td  width="7%" style="border: 0">   </td>
                        </tr>
		
                 </tbody>
        </table>
	

	
        </body>
        </html>