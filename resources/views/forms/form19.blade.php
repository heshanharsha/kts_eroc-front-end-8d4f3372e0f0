<html>

<head>
<title>FORM 19</title>
  <style>
    table,
    th,
    td {
      border: 1px solid black;
      border-collapse: collapse;
      margin-right: 0px;
      margin-left: 0px;
      margin-bottom: 0px;
      font-size: 15px;
      padding: 5px;
      font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, "sans-serif"
    }

    font {
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
      <table style="border: 0; margin-left: 0; margin-right: 0;" width="100%">
        <tbody>
          <tr style="border: 0;">
            <td style=" border: 0;">
              <img src="{{ URL::to('/') }}/css/forms/govlogo.jpg" width="110" height="auto" alt="" /></td>            
            <td style="border: 0;" width="60%">
              <center>
                <font style="font-size:16px;">
                  <b>FORM 19</b>
                  <br>
                  <br>
                  <font style="font-size:12px;">Notice of</font>
                  <br>
                  <font style="font-size:16px;">
                    <b>CONSENT AND CERTIFICATE OF SECRETARY/SECRATARIES</b>
                    </br>
                  </font>
                  <font style="font-size:12px;">Section 221(2) of the companies Act No.7 of 2007</font>
              </center>
            </td>
            <td style=" border: 0;">
              <img src="{{ URL::to('/') }}/css/forms/eroc.png" width="180" height="auto" alt="" />
            </td>
          </tr>
        </tbody>
      </table>


      <table style="border: 0;" width="100%" height="30">
        <tbody>
          <tr>
            <td width="28%" style="border: 0;">Number of the company </td>
            <td width="8%">&nbsp;</td>
            <td width="8%">&nbsp;</td>
            <td width="40%">&nbsp;</td>
            <td width="8%">&nbsp;</td>
            <td width="8%">&nbsp;</td>
          </tr>
        </tbody>
      </table>
      <br>


      <table style="border: 0;" width="100%" height="30">
        <tbody>
          <tr>
            <td width="28%">Name of the company </td>
            <td width="72%"><?php echo $company_info->name; ?></td>
          </tr>
        </tbody>
      </table>
      <br>

      <table  width="100%" height="30">
        <tbody>
          <tr height="20">
            <td width="28%" >First Name of Secretary </td>
            <td width="72%"><?php echo $sec['firstname'];?></td>
          </tr>
          <tr height="20">
            <td width="28%" >Last Name of Secretary</td>
            <td width="72%"><?php echo $sec['lastname'];?></td>
          </tr>
          <tr height="80">
            <td width="28%" >Resedential Address </td>
            <td width="72%" >
     
            <?php  echo $sec['localAddress1'].','; ?>
            <?php echo ($sec['localAddress2']) ? $sec['localAddress2'].',' : ''; ?>
            <?php  echo $sec['city'].','; ?>
            <?php  echo $sec['postcode']; ?>
                
  
            </td>
          </tr>
        </tbody>
      </table>
      <br>

      <font>I consent to be Secretary of the above company and certify that I am qualified to be appointed to hold office of Secretary</font>
      <br>
     
      <table style="border: 0" width="100%" height="50.4">
        <tbody>
          <tr>

          <?php 

$o_date = $sec['date'];

$o_d = date('d', strtotime($o_date));
$o_m = date('m', strtotime($o_date));
$o_y = date('Y', strtotime($o_date));

 $o_d = ($o_date == '1970-01-01') ? '  ' : $o_d;
 $o_m = ($o_date == '1970-01-01') ? '  ' : $o_m;
 $o_y = ($o_date == '1970-01-01') ? '    ' : $o_y;

 ?>
             <td height="22" align="right" style="border: 0">Date of appointment:</td>
            <td style="border: 0"></td>
            <td width="6%" align="center"><?php echo $o_d[0];?></td>
            <td width="6%" align="center"><?php echo $o_d[1];?></td>
            <td style="border: 0"></td>
            <td width="6%" align="center"><?php echo $o_m[0];?></td>
            <td width="6%" align="center"><?php echo $o_m[1];?></td>
            <td style="border: 0"></td>
            <td width="6%" align="center"><?php echo $o_y[0];?></td>
            <td width="6%" align="center"><?php echo $o_y[1];?></td>
            <td width="6%" align="center"><?php echo $o_y[2];?></td>
            <td width="6%" align="center"><?php echo $o_y[3];?></td>
          </tr>
          <tr>
            <td width="24%" height="22" style="border: 0"></td>
            <td width="6%" style="border: 0"> </td>
            <td colspan="2">
              <center>Day</center>
            </td>
            <td width="6%" style="border: 0"></td>
            <td colspan="2">
              <center>Month</center>
            </td>
            <td width="6%" style="border: 0"></td>
            <td colspan="4">
              <center>Year</center>
            </td>
          </tr>
        </tbody>
      </table>
      <br>

      <table  width="100%" height="20">
        <tbody>
          <tr>
            <td width="28%">Registration No.(if applicable) </td>
            <td width="72%"><?php echo $sec['regDate']; ?></td>
          </tr>
        </tbody>
      </table>
      <br>

      <table  width="100%">
        <tbody>
          <tr height="50">
            <td width="28%" >Signature</td>
            <td width="72%">&nbsp;</td>
          </tr>
          <tr>
            <td width="28%" height="20">Full Name of Secretary </td>
            <td width="72%"><?php echo ( $sec['title'].' '.$sec['firstname'].' '.$sec['lastname'] );?></td>
          </tr>
        </tbody>
      </table>
      <br>

      <table style="border: 0" width="100%" height="50.4">
          <tbody>
            <tr>

            <?php  
                        $d = date('d',time());
                        $m = date('m',time());
                        $y = date('Y',time());


            ?>
              <td height="22" align="right" style="border: 0">Date:</td>
            <td style="border: 0"></td>
            <td  width="6%" align="center"><?php echo $d[0];?></td>
            <td  width="6%" align="center"><?php echo $d[1];?></td>
            <td style="border: 0"></td>
            <td  width="6%" align="center"><?php echo $m[0];?></td>
            <td  width="6%" align="center"><?php echo $m[1];?></td>
            <td style="border: 0"></td>
            <td  width="6%" align="center"><?php echo $y[0];?></td>
            <td  width="6%" align="center"><?php echo $y[1];?></td>
            <td  width="6%" align="center"><?php echo $y[2];?></td>
            <td  width="6%" align="center"><?php echo $y[3];?></td>
            </tr>
            <tr>
              <td width="24%" height="22" style="border: 0"></td>
              <td width="6%" style="border: 0"> </td>
              <td colspan="2">
                <center>Day</center>
              </td>
              <td width="6%" style="border: 0"></td>
              <td colspan="2">
                <center>Month</center>
              </td>
              <td width="6%" style="border: 0"></td>
              <td colspan="4">
                <center>Year</center>
              </td>
            </tr>
  
          </tbody>
        </table>

      <font>Presented by</font>

      <table  width="100%">
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
</body>

</html>