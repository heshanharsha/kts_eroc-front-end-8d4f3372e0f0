<html>
<head>
    <title>FORM 45</title>
    <style>
        table,th,td {
            border: 2px solid black;
            border-collapse: collapse;
            margin-right: 0px;
            margin-left: 0px;
            margin-bottom: 0px;
            font-size: 15px;
            padding: 5px;
            font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, "sans-serif"
        }

        span {
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
                        <td style=" border: 0;"><img src="{{ URL::to('/') }}/css/forms/govlogo.jpg" width="110" height="auto" alt="" /></td>
                        <td style="border: 0;" width="60%">
                            <center>
                                <span style="font-size:16px;">
                                    <b>FORM 45</b>
                                </span>
                            </center>
                        </td>
                        <td style=" border: 0;"><img src="{{ URL::to('/') }}/css/forms/eroc.png" width="180" height="auto" alt="" /></td>
                    </tr>
                    <tr width="80%" style="border: 0;">
                        <td colspan="3" style="border: 0;">
                            <center>
                                <span style="font-size:16px;">
                                    <b>LIST AND PARTICULARS OF THE DIRECTORS OF A COMPANY INCORPORATED OUTSIDE SRI LANKA WITH
                                        A PLACE OF BUSINESS ESTABLISHED IN SRI LANKA</b>
                                </span>
                                <br>
                                <span style="font-size:12px;">
                                    The companies Act No.7 of 2007 Pursuant to Sec 489(b)
                                </span>
                            </center>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>


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


            <table width="100%" height="30">
                <tbody>
                    <tr height="20">
                        <td width="28%" style="background-color:#b9b9b9 !important;">Name of the Company </td>
                        <td width="72%"><?php echo $company_info->name; ?></td>
                    </tr>
                    <tr height="20">
                        <td width="28%" style="background-color:#b9b9b9;">Registered or Principal office address(country in which it is incorporated)</td>
                        <td width="72%">&nbsp;</td>
                    </tr>
                    <tr height="20">
                        <td width="28%" style="background-color:#b9b9b9;">principal place of Business in Sri Lanka</td>
                        <td width="72%">
                         
                        <?php  echo $company_address->address1.','; ?>
                        <?php echo ($company_address->address2) ? $company_address->address2.',' : ''; ?>
                        <?php  echo $company_address->city.','; ?>
                        <?php  echo $company_address->postcode; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>


            <table width="100%" height="30">
                <tbody>
                    <tr height="20">
                        <td width="28%" style="background-color:#b9b9b9;">Full Name </td>
                        <td width="72%">&nbsp;</td>
                    </tr>
                    <tr height="20">
                        <td width="28%" style="background-color:#b9b9b9;">Passport No(indicate the Country of issue)</td>
                        <td width="72%">&nbsp;</td>
                    </tr>
                    <tr height="20">
                        <td width="28%" style="background-color:#b9b9b9;">Resedential Address</td>
                        <td width="72%">&nbsp;</td>
                    </tr>
                    <tr height="20">
                        <td width="28%" style="background-color:#b9b9b9;">Other business or occupation/Directorships if any </td>
                        <td width="72%">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            <br>


            <table width="100%">
                <tbody>
                    <tr height="50">
                        <td width="28%" style="background-color:#b9b9b9;">Signature Full Name of Authorised Person</td>
                        <td width="72%">&nbsp;</td>
                    </tr>
                    <tr height="20">
                        <td width="28%" style="background-color:#b9b9b9;">Signature of Authorised Person</td>
                        <td width="72%">&nbsp;</td>
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
                        <td colspan="2" style="background-color:#b9b9b9;">
                            <center>Day</center>
                        </td>
                        <td width="6%" style="border: 0"></td>
                        <td colspan="2" style="background-color:#b9b9b9;">
                            <center>Month</center>
                        </td>
                        <td width="6%" style="border: 0"></td>
                        <td colspan="4" style="background-color:#b9b9b9;">
                            <center>Year</center>
                        </td>
                    </tr>
                </tbody>
            </table>

            <span>Presented by</span>


            <table width="100%">
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