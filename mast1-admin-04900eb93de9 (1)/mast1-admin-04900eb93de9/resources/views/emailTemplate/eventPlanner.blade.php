<!DOCTYPE >
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" align="center" style="background-color: #e5e5e5">
<div class="firma">
    @if(!empty($plannerData))
        @foreach($plannerData as $data)
            
   
    <table cellpadding="0" cellspacing="0" role="presentation" class="wrapper" width="450px">
        <tbody>
            <tr>
                <td style="background-color: #26656b; border-top-right-radius: 5px;  border-bottom-left-radius: 60px; border-top-left-radius: 60px;  border-bottom-right-radius: 60px;">
                    <table cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="vertical-align: top;">
                                    <table cellpadding="0" cellspacing="0" role="presentation">
                                        <tbody>
                                            <tr>
                                                <td style="padding-right: 10px;">
                                                    <div style="width: 120px; height: 120px; ">
                                                        <img style="width: 100%;" src="{{ $data['profileImage'] ?? '' }}" alt="">
                                                       
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style="vertical-align: top;">
                                    <table cellpadding="0" cellspacing="0" role="presentation" style="font-weight: 600;">
                                        <tbody>
                                            <tr>
                                                <td style="font-size: 0px; padding-top: 7px">
                                                    <div style="font-size: 14px; font-family: Trebuchet MS, Helvetica, sans-serif; line-height: 1.5em; display: block;">
                                                        <span style=" color: #fff; padding-right: 60px">{{ $data['name'] ?? '' }}</span>
                                                    </div>
                                                </td>
                                                <td align="right" style="padding-top: 9px">
                                                    <table  cellpadding="0" cellspacing="0" role="presentation" style="font-size: 0px;">
                                                        <tbody>
                                                           
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table cellpadding="0" cellspacing="0" role="presentation">
                                        <tbody>
                                            <tr>
                                                <td style="font-size: 0px; padding-bottom: 5px;">
                                                    <div style="font-size: 13px; font-family: Trebuchet MS, Helvetica, sans-serif; line-height: 1.5em; display: inline-block; color: #e5e5e5">
                                                        <span><strong>Email : </strong>{{ $data['email'] ?? '' }}</span>
                                                        <span><strong>Mobile No. : </strong>{{ $data['mobile'] ?? '' }}</span>
                                                        <span><strong>Address. : </strong>{{ $data['location'] ?? '' }}</span>
                                                    </div>
                                                  
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table cellpadding="0" cellspacing="0" role="presentation">
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
    @endif
</div>
</body>
</html>