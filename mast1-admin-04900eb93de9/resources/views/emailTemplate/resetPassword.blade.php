<html>
<head>
    <title></title>
</head>
<body>
    <table align="center" bgcolor="#083d46" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
         
            <tr style="font-size:0;line-height:0">
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="center" valign="top">
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <div style="color:inherit;font-size:inherit;line-height:inherit;margin:inherit;padding:inherit">
                    </div>
                    <table width="600">
                        <tbody>
                            
                            
                            
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="overflow:hidden!important;border-radius:3px" width="580">
                                        <tbody>
                                            
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <table width="85%">
                                                        <tbody>
                                                            
                                                            <tr>
                                                                <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:16px!important;line-height:38px!important;font-weight:400!important;color:#252b33!important;font-style:italic!important">
                                                                  {{  !empty($name) ? 'Hello :'.$name : '' }}
																</td>
															</tr>
															<tr>
																<td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:16px!important;line-height:38px!important;font-weight:400!important;color:#252b33!important;font-style:italic!important">
                                                                   
																</td>
															</tr>
															<tr>
																<td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:16px!important;line-height:38px!important;font-weight:400!important;color:#252b33!important;font-style:italic!important">
                                                                    {{ !empty($password) ? 'Password :'.$password : '' }}
																</td>
																
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="78%">
                                                        <tbody>
                                                            <tr>
                                                              
                                                                    <td align="center" style="font-family:'Open Sans',arial,sans-serif!important;font-size:16px!important;line-height:30px!important;font-weight:400!important;color:#7e8890!important">
                                                                     Someone requested to reset your password.Reset Your password by clicking the reset button below or copy and paste the below URL to your browser.
																</td>
                                                                
                                                                    
                                                               
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                           
                                                                <td align="center" valign="top">
                                                                @if(!empty($password))
                                                                    <a href="{{  !empty($url) ? $url : '#' }}" style="background-color:#083d46;padding:14px 28px 14px 28px;border-radius:3px;line-height:18px!important;letter-spacing:0.125em;text-transform:uppercase;font-size:13px;font-family:'Open Sans',Arial,sans-serif;font-weight:400;color:#ffffff;text-decoration:none;display:inline-block;line-height:18px!important" target="_blank">Login
                                                                    </a>
                                                                    @else
                                                                        <a href="{{ !empty($url) ? $url : '#' }}" style="background-color:#083d46;padding:14px 28px 14px 28px;border-radius:3px;line-height:18px!important;letter-spacing:0.125em;text-transform:uppercase;font-size:13px;font-family:'Open Sans',Arial,sans-serif;font-weight:400;color:#ffffff;text-decoration:none;display:inline-block;line-height:18px!important" target="_blank">Reset Password
                                                                    </a>
                                                                 @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                   
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>