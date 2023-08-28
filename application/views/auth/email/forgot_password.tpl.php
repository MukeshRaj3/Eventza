<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $this->config->item('site_title', 'ion_auth'); ?></title>

    <style type="text/css">
    .ReadMsgBody{width:100%;}
    .ExternalClass{width:100%;}
    body{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-webkit-font-smoothing:antialiased;margin:0 !important;padding:0 !important;min-width:100% !important;}

    @media only screen and (max-width: 599px) {
        body{min-width:100% !important;}
        table[class=wrapper]{width: 100% !important;}
        td[class=viewOnlineTD]                      {text-align:center !important;}
        table[class=table600Logo]                   {width:440px !important;}
        td[class=tdLogo]                            {width:440px !important;}
        table[class=table600Menu]                   {width:440px !important;}
        td[class=AnnouncementTD]                    {width:440px !important;text-align:center !important;font-size:17px !important;}
        table[class=table600Menu] td                {height:20px !important;}
        table[class=tbl6AnctText] .menuTD           {text-align:center !important;font-size:13px !important;line-height:150% !important;}
        table[class=tbl6AnctText]                   {width:440px !important;}
        td[class=viewOnlineTD]                      {width:440px !important;}
        td[class=menuTD]                            {width:440px !important;font-weight:bold !important;}
        table[class=table600]                       {width:440px !important;}
        table[class=image600] img                   {width:440px !important; height:auto !important;}       
        table[class=AnncTable]                      {width:100% !important;}
        table[class=table280d]                      {width:440px !important; border-radius:0 0 0 0 !important;}
        td[class=LMrg]                              {height:8px !important;}
        td[class=LMrg2]                             {height:6px !important;}
        td[class=LMrg3]                             {height:10px !important;}
        table[class=tblRgBrdr]                      {border-right:none !important;}
        td[class=td147]                             {width:215px !important;}
        table[class=table147]                       {width:215px !important;}
        table[class=table147tblp]                   {width:175px !important;}
        td[class=mrgnHrzntlMdl]                     {width:10px !important;}
        td[class=mvd]                               {height:30px !important;width:440px !important;}
        table[class=centerize]                      {margin:0 auto 0 auto !important;}
        table[class=tblBtnCntr2]                    {width:398px !important; margin:0 auto 0 auto !important;}
        td[class=table28Sqr] img                    {width:440px !important;height:auto !important;margin:0 auto 0 auto !important; border-radius:4px 4px 0 0 !important;}
        td[class=tbl28Rctngl] img                   {width:215px !important;height:auto !important;margin:0 auto 0 auto !important;}
        td[class=headerTD]                          {text-align:center !important;}
        td[class=bigtitle]                          {text-align:center !important;}
        td[class=header4TD]                         {text-align:center !important;}
        td[class=headerPrcTD]                       {font-size:25px !important;}
        td[class=RegularTextTD]                     {font-size:13px !important;}
        td[class=RegularText2TD]                    {height:0 !important; font-size:13px !important;}
        td[class=RegularText3TD]                    {font-size:13px !important;}
        td[class=mailingOptionsTD]                  {text-align:center !important;}
        td[class=companyAddressTD]                  {text-align:center !important;}
        td[class=esFrMb]                            {width:0 !important;height:0 !important;display:none !important;}
        table[class=table280brdr]                   {width:440px !important;}
        table[class=table608]                       {width:438px !important;}
        table[class=table518b]                      {width:398px !important;}
        table[class=table518]                       {width:398px !important;}
        table[class=table518c]                      {width:195px !important;}
        table[class=table518c2]                     {width:195px !important;}
        td[class=imgAltTxticTD] img                 {width:398px !important;height:auto !important; margin:0 auto 17px auto !important;}
        td[class=iconPdngErase]                     {width:0 !important; display:none !important;}
        td[class=table608]                          {width:438px !important;}
        td[class=invReg]                            {width:133px !important; font-size:14px !important;text-align:center !important;font-weight:lighter !important;}
        td[class=invInfoA]                          {text-align:right !important;width:195px !important;}
        td[class=invInfoB]                          {text-align:left !important;width:195px !important;}
        td[class=invoiceTD]                         {width:390px !important; font-weight:bold;}
        td[class=td528Button]                       {width:358px !important;}
        table[class=table528Button]                 {width:358px !important;}
        table[class=table528Social]                 {width:398px !important;}
        table[class=table250]                       {width:177px !important;}
        td[class=h60]                               {height:40px !important;}
        td[class=h50]                               {height:30px !important;}
        td[class=h40]                               {height:25px !important;}
        td[class=h30]                               {height:25px !important;}
        td[class=text22]                            {font-size: 20px !important;}
        td[class=text20]                            {font-size: 18px !important;}
        td[class=smalltitle]                        {font-size: 20px !important;}
    }

    @media only screen and (max-width: 479px) {
        body{min-width:100% !important;}
        td[class=viewOnlineTD]                      {text-align:center !important;}
        table[class=table600Logo]                   {width:300px !important;}
        td[class=tdLogo]                            {width:300px !important;}
        table[class=table600Menu]                   {width:300px !important;}
        td[class=AnnouncementTD]                    {width:300px !important;text-align:center !important;font-size:17px !important;}
        table[class=table600Menu] td                {height:20px !important;}
        table[class=tbl6AnctText] .menuTD           {text-align:center !important;font-size:12px !important;line-height:150% !important;}
        table[class=tbl6AnctText]                   {width:300px !important;}
        td[class=viewOnlineTD]                      {width:300px !important;}
        td[class=menuTD]                            {width:300px !important;font-weight:bold !important;}
        table[class=table600]                       {width:300px !important;}
        table[class=image600] img                   {width:300px !important;height:auto !important;}
        table[class=table608]                       {width:300px !important;}
        td[class=table608]                          {width:300px !important;}
        table[class=table518]                       {width:260px !important;}
        table[class=table518b]                      {width:268px !important;}
        table[class=table518c]                      {width:268px !important;}
        table[class=table518c2]                     {width:268px !important; margin:20px 0 0 0 !important;}
        td[class=imgAltTxticTD] img                 {width:268px !important;height:auto !important;margin:-4px auto 15px auto !important;}
        table[class=table280Button]                 {width:264px !important;}
        table[class=centerize]                      {margin:0 auto 0 auto !important;}
        table[class=tblBtnCntr2]                    {width:264px !important; margin:0 auto 0 auto !important;}
        table[class=AnncTable]                      {width:100% !important;}
        table[class=table280d]                      {width:300px !important; border-radius:0 0 0 0 !important;} 
        td[class=LMrg]                              {height:8px !important;}
        td[class=LMrg2]                             {height:6px !important;}
        td[class=LMrg3]                             {height:10px !important;}
        td[class=wz]                                {width:15px !important;}
        table[class=tblRgBrdr]                      {border-right:none !important;}
        td[class=td147]                             {width:147px !important;}
        table[class=table147]                       {width:147px !important;}
        table[class=table147tblp]                   {width:117px !important;}
        td[class=mrgnHrzntlMdl]                     {width:6px !important;}
        td[class=mvd]                               {height:30px !important;width:300px !important;}
        td[class=iconPdngErase]                     {width:0 !important; display:none !important;}
        td[class=table28Sqr] img                    {width:300px !important;height:auto !important;margin:0 auto 0 auto !important; border-radius:4px 4px 0 0 !important;}
        td[class=tbl28Rctngl] img                   {width:147px !important;height:auto !important;margin:0 auto 0 auto !important;}
        td[class=headerTD]                          {font-size:16px !important; font-weight:bold !important;text-align:center !important;}
        td[class=bigtitle]                          {font-size:20px !important; font-weight:bold !important;text-align:center !important;}
        td[class=text17]                            {font-size:15px !important;}
        td[class=buttonTD]                          {font-size:16px !important;}
        td[class=header4TD]                         {font-size:18px !important; font-weight:bold !important;text-align:center !important;}
        td[class=iconHDTD]                          {font-size:16px !important;text-align:center !important;}
        td[class=headerPrcTD]                       {font-size:18px !important;}
        td[class=RegularTextTD]                     {font-size:13px !important;text-align:left !important;}
        td[class=RegularText2TD]                    {height:0 !important;font-size:13px !important;}
        td[class=RegularText3TD]                    {font-size:13px !important;}
        td[class=mailingOptionsTD]                  {text-align:center !important;}
        td[class=companyAddressTD]                  {text-align:center !important;}
        td[class=esFrMb]                            {width:0 !important;height:0 !important;display:none !important;}
        table[class=table280brdr]                   {width:300px !important;}
        td[class=invReg]                            {width:89px !important; font-size:13px !important;text-align:center !important;}
        td[class=invInfoA]                          {text-align:center !important;width:268px !important;}
        td[class=invInfoB]                          {text-align:center !important;width:268px !important;}
        td[class=invoiceTD]                         {width:250px !important;}
        td[class="buttonVrt"]                       {height:16px !important;}
        td[class="buttonVrt2"]                      {height:12px !important;}
        td[class="buttonVrt3"]                      {height:10px !important;}
        td[class=td528Button]                       {width:238px !important;}
        table[class=table528Button]                 {width:238px !important;}
        table[class=table528Social]                 {width:266px !important;}
        table[class=table250]                       {width:117px !important;}
        td[class=termsCon2TD]                       {text-align:center !important;}
        td[class=termsConTD]                        {text-align:center !important;}
    }



</style>
</head>
<body style="background-color: #ffffff;margin: 0 auto;padding: 0 !important;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;-webkit-font-smoothing: antialiased;min-width: 100% !important;">
    <center>

        <table width="800" class="wrapper" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
            <tr>
                <td>
                    
                    <!--LOGO SECTION-->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;margin:0 auto;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center">  
                                <table width="618" bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0" border="0" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                    <tr>
                                        <td align="center">
                                            <table width="610" align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="AnncTable" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-bottom:1px solid #e9e9e9;">
                                                <tr>
                                                    <td>
                                                        <table width="175" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600Logo" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                            <tr>
                                                                <td>
                                                                    <table cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="centerize" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                        <tr>
                                                                            <td height="30" class="h30" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td valign="middle" class="logoimg" align="center" height="40" style="line-height:1px;"><img src="<?php echo base_url('assets/frameworks/email/'); ?>images/logo.png" style="display: block;text-decoration: none;border: none;" alt="Logo Image" border="0" align="top" hspace="0" vspace="0" width="298" height="40"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td height="30" class="h30" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr> 
                                </table>
                            </td>
                        </tr>
                    </table>

                    <!-- Space -->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center" height="40" class="h40" bgcolor="#ffffff" style="font-size:0;line-height:1;">&nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;margin:0 auto;">
                        <tr>
                            <td align="center">
                                <table width="618" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">
                                    <tr>
                                        <td> 
                                            <table width="500" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                    <td valign="middle" align="center" bgcolor="#ffffff" style="text-align: center;line-height: 1;text-align: center;">
                                                        <img src="<?php echo base_url('assets/frameworks/email/'); ?>images/rotation-lock.png" style="display: block;text-decoration: none;border: none;margin: 0 auto;" border="0" align="top" hspace="0" vspace="0" width="64" height="64">
                                                    </td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="50" class="h50" style="font-size:0;line-height:1;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="550" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                     <td valign="middle" align="center" bgcolor="#ffffff" class="text20" style="color: #4D4D4D;font-family: sans-serif;font-size: 20px;text-align: center;line-height: 1.18;">Your password reset link is ready below</td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="15" style="font-size:0;line-height:1;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="550" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                     <td valign="middle" align="center" bgcolor="#ffffff" class="text20" style="color: #4D4D4D;font-family: sans-serif;font-size: 20px;text-align: center;line-height: 1.18;">This link will expire in 24 hours!</td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="50" class="h50" style="font-size:0;line-height:1;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="290" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table147" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                <tbody>
                                                    <tr>
                                                        <td align="center">
                                                            <table cellpadding="0" cellspacing="0" bgcolor="#F16424" border="0" class="centerize" style="mso-table-lspace:0pt;mso-table-rspace:0pt; border:1px solid #F16424;border-radius:4px 4px 4px 4px;"> 
                                                                <tbody>
                                                                    <tr>
                                                                        <td height="12" bgcolor="#F16424" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td bgcolor="#F16424">
                                                                            <table width="250" align="center" cellpadding="0" cellspacing="0" bgcolor="#F16424" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="15" bgcolor="#F16424"></td>
                                                                                        <td valign="middle" align="center" bgcolor="#F16424" class="buttonTD" style="color: #ffffff;font-family: sans-serif;font-size: 18px;font-weight: bold;text-align: center;line-height: 23px;"><a href="<?php echo base_url('auth/reset_password/' . $forgotten['forgotten_password_code']); ?>" target="_blank" style="text-decoration: none;color: #ffffff;display: block;">Password Reset</a></td>
                                                                                        <td width="15" bgcolor="#F16424"></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td height="12" bgcolor="#F16424" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>                                                      
                            </td>
                        </tr>
                    </table>

                    <!-- Space -->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center" height="60" class="h60" bgcolor="#ffffff" style="font-size:0;line-height:1;">&nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;margin:0 auto;">
                        <tr>
                            <td align="center">
                                <table width="618" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">
                                    <tr>
                                        <td> 
                                            <table width="500" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                    <td valign="middle" align="center" bgcolor="#ffffff" class="smalltitle" style="color: #000000;font-family: sans-serif;font-size: 22px;text-align: center;line-height: 1.18;font-weight: bold;">Link not working?</td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="25" class="h25" style="font-size:0;line-height:1;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="550" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                     <td valign="middle" align="center" bgcolor="#ffffff" class="text17" style="color: #4D4D4D;font-family: sans-serif;font-size: 17px;text-align: center;line-height: 1.18;">Sorry about that! Copy and paste the link below into your browser to reset your password now:</td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="25" class="h25" style="font-size:0;line-height:1;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="550" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600" style="table-layout:fixed;">
                                                <tr>
                                                    <td width="20" class="wz"></td>
                                                    <!--CONFIRMATION LINK-->
                                                    <td class="text17"  align="center" style="color: #FD3E04;font-family: sans-serif;font-size: 18px;text-align: center;line-height: 1.2;word-break: break-all;"><a href="<?php echo base_url('auth/reset_password/' . $forgotten['forgotten_password_code']); ?>" target="_blank" style="text-decoration: underline;color: #FD3E04;font-weight: normal;word-break: break-all;"><?php echo base_url('auth/reset_password/' . $forgotten['forgotten_password_code']); ?></a></td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>                                                      
                            </td>
                        </tr>
                    </table>

                    <!-- Space -->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center" height="60" class="h60" bgcolor="#ffffff" style="font-size:0;line-height:1;">&nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;margin:0 auto;">
                        <tr>
                            <td align="center">
                                <table width="618" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">
                                    <tr>
                                        <td> 
                                            <table width="500" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                    <td valign="middle" align="center" bgcolor="#ffffff" class="smalltitle" style="color: #000000;font-family: sans-serif;font-size: 22px;text-align: center;line-height: 1.18;font-weight: bold;">Have you made a strong password?</td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="25" class="h25" style="font-size:0;line-height:1;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="550" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                     <td valign="middle" align="center" bgcolor="#ffffff" class="text17" style="color: #4D4D4D;font-family: sans-serif;font-size: 17px;text-align: center;line-height: 1.18;">Take Your Seat recommends having a strong password with a mix of letters and numbers. Make sure its memorable to you, and isn&#8217;t something obvious relating to your Take Your Seat account with us! Mix it up and make your Take Your Seat password unique from any others you may use.</td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>                                                      
                            </td>
                        </tr>
                    </table>

                    <!-- Space -->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center" height="60" class="h60" bgcolor="#ffffff" style="font-size:0;line-height:1;">&nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;margin:0 auto;">
                        <tr>
                            <td align="center">
                                <table width="618" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">
                                    <tr>
                                        <td> 
                                            <table width="500" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                    <td valign="middle" align="center" bgcolor="#ffffff" class="smalltitle" style="color: #000000;font-family: sans-serif;font-size: 22px;text-align: center;line-height: 1.18;font-weight: bold;">Account Safety</td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="25" class="h25" style="font-size:0;line-height:1;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="550" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">    <tr>
                                                    <td width="20" class="wz"></td>
                                                     <td valign="middle" align="center" bgcolor="#ffffff" class="text17" style="color: #4D4D4D;font-family: sans-serif;font-size: 17px;text-align: center;line-height: 1.18;">We will never directly ask you to give us your password or any confidential information. If you haven&#8217;t asked for this password reset link, please let us know as soon as possible so we can double check it&#8217;s you!</td>
                                                    <td width="20" class="wz"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>                                                      
                            </td>
                        </tr>
                    </table>

                    <!-- Space -->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center" height="50" class="h50" bgcolor="#ffffff" style="font-size:0;line-height:1;">&nbsp;</td>
                        </tr>
                    </table>

                    <table width="290" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table147" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tbody>
                            <tr>
                                <td align="center">
                                    <table cellpadding="0" cellspacing="0" bgcolor="#F16424" border="0" class="centerize" style="mso-table-lspace:0pt;mso-table-rspace:0pt; border:1px solid #F16424;border-radius:4px 4px 4px 4px;"> 
                                        <tbody>
                                            <tr>
                                                <td height="12" bgcolor="#F16424" style="font-size:0;line-height:1;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#F16424">
                                                    <table width="250" align="center" cellpadding="0" cellspacing="0" bgcolor="#F16424" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td width="15" bgcolor="#F16424"></td>
                                                                <td valign="middle" align="center" bgcolor="#F16424" class="buttonTD" style="color: #ffffff;font-family: sans-serif;font-size: 18px;font-weight: bold;text-align: center;line-height: 23px;"><a href="<?= base_url('contact-us'); ?>" target="_blank" style="text-decoration: none;color: #ffffff;display: block;">Contact us</a></td>
                                                                <td width="15" bgcolor="#F16424"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="12" bgcolor="#F16424" style="font-size:0;line-height:1;">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Space -->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center" height="60" class="h60" bgcolor="#ffffff" style="font-size:0;line-height:1;">&nbsp;</td>
                        </tr>
                    </table>

                    


                    <!-- SOCIAL MEDIA -->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000" style="background-color: #000000;table-layout:fixed;margin:0 auto;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center">  
                                <table width="618" bgcolor="#000000" align="center" cellpadding="0" cellspacing="0" border="0" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;background-color: #000000;">
                                    <tr>
                                        <td width="610" class="tdLogo" bgcolor="#000000" align="center" style="background-color: #000000;border-bottom:1px solid #383838;"> 
                                            <table width="175" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000" class="table600Logo" style="mso-table-lspace:0pt;mso-table-rspace:0pt;background-color: #000000;">
                                                <tr>
                                                    <td>
                                                        <table cellpadding="0" cellspacing="0" bgcolor="#000000" border="0" class="centerize" style="mso-table-lspace:0pt;mso-table-rspace:0pt;background-color: #000000;">
                                                            <tr>
                                                                <td height="15" style="font-size:0;line-height:1;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <table align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000" style="mso-table-lspace:0pt;mso-table-rspace:0pt;background-color: #000000;">
                                                                        <tr>
                                                                            <!--FACEBOOK Link-->
                                                                            <td><a href="<?= $this->config->item('email_facebook_link'); ?>" target="_blank" style="text-decoration: none;"><img src="<?php echo base_url('assets/frameworks/email/'); ?>images/facebook.png" alt="IMG" border="0" style="text-decoration: none;border: none;" width="50" height="50"></a></td>
                                                                            <!--TWITTER Link-->
                                                                            <td><a href="<?= $this->config->item('email_twitter_link'); ?>" target="_blank" style="text-decoration: none;"><img src="<?php echo base_url('assets/frameworks/email/'); ?>images/twitter.png" alt="IMG" border="0" style="text-decoration: none;border: none;" width="50" height="50"></a></td>
                                                                            <!--INSTAGRAM Link-->
                                                                            <td><a href="<?= $this->config->item('email_instagram_link'); ?>" target="_blank" style="text-decoration: none;"><img src="<?php echo base_url('assets/frameworks/email/'); ?>images/instagram.png" alt="IMG" border="0" style="text-decoration: none;border: none;" width="50" height="50"></a></td>
                                                                            <!--LINK Link-->
                                                                            <td><a href="<?= $this->config->item('email_hyper_link'); ?>" target="_blank" style="text-decoration: none;"><img src="<?php echo base_url('assets/frameworks/email/'); ?>images/link.png" alt="IMG" border="0" style="text-decoration: none;border: none;" width="50" height="50"></a></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td height="15" style="font-size:0;line-height:1;">&nbsp;</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr> 
                                </table>
                            </td>
                        </tr>
                    </table> 

                    <!--FOOTER SECTION-->
                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000" style="table-layout:fixed;margin:0 auto;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                        <tr>
                            <td align="center"> 
                                <table width="618" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                    <tr>
                                        <td align="center">
                                            <table width="610" align="left" cellpadding="0" cellspacing="0" border="0" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                <tr>
                                                    <td align="center">  
                                                        <table width="608" cellpadding="0" cellspacing="0" bgcolor="#000000" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border:1px solid #000000; border-radius:4px 4px 4px 4px;"> 
                                                            <tr>   
                                                                <td align="center">
                                                                    <table align="center" cellpadding="0" cellspacing="0" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;"> 
                                                                        <tr>
                                                                            <td height="25" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="center">
                                                                                <table width="608" align="center" cellpadding="0" cellspacing="0" bgcolor="#000000" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                    <tr>
                                                                                        <td width="20" class="wz"></td>
                                                                                        <td align="center">
                                                                                            <table align="center" cellpadding="0" cellspacing="0" bgcolor="#000000" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        <table width="100%" border="0" cellspacing="0" class="innerpadding" align="center" valign="top" cellpadding="0">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td align="center" valign="center" style="padding: 0 5px;">
                                                                                                                        <a href="<?= $this->config->item('email_iphone_app_link'); ?>" target="_blank" style="display: block;"><img src="<?php echo base_url('assets/frameworks/email/'); ?>images/apple-store-button.png" width="70%"></a>
                                                                                                                    </td>
                                                                                                                    <td align="center" valign="center" style="padding: 0 5px;">
                                                                                                                        <a href="<?= $this->config->item('email_google_play_app_link'); ?>" target="_blank" style="display: block;"><img src="<?php echo base_url('assets/frameworks/email/'); ?>images/google-play-button.png" width="70%"></a>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td height="25" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <!--FOOTER SLOGAN-->
                                                                                                    <td class="text22" align="center" style="color: #ffffff;font-family: sans-serif;font-size: 22px;text-align: center;line-height: 25px;">Bringing Simplicity to Live Sport</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td height="25" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                                                </tr> 
                                                                                                <tr>
                                                                                                    <!--ADDRESS OR ANY INFO SECTION-->
                                                                                                    <td  align="center" style="color: #ffffff;font-family: sans-serif;font-size: 14px;text-align: center;line-height: 190%;">Copyright <?= date('Y'); ?> TakeYourSeat. All rights reserved.</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td height="25" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td  align="center" style="color: #ffffff;font-family: sans-serif;font-size: 14px;text-align: center;line-height: 170%;">
                                                                                                        <a href="<?= base_url('terms-and-conditions'); ?>" target="_blank" style="text-decoration: none;color: #ffffff;">Terms and Conditions</a> | <a href="<?= base_url('privacy-policy'); ?>" target="_blank" style="text-decoration: none;color: #ffffff;">Privacy Policy</a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td height="40" class="h40" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                <td align="center" style="color: #ffffff;font-family: sans-serif;font-size: 14px;text-align: center;line-height: 1.3;">This is an automated service email. This is an unattended inbox, please do not respond to this email. You will receive this email regardless of your marketing preferences.</td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td width="20" class="wz"></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td height="40" class="h40" style="font-size:0;line-height:1;">&nbsp;</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </center>
</body>
</html>