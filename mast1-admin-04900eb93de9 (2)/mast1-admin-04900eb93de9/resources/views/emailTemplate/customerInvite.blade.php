<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,700&display=swap" rel="stylesheet">
<div style="font-family: 'Poppins', sans-serif;max-width: 1200px;margin: 0px auto;">
        <div style="background: #FAEFEA; padding: 40px 60px;">
            <a href="#">
                <img src="{{ asset('resources/images') }}/logo.png" alt="" style=" max-width: 190px;">
            </a>
        </div>
        <div style="padding: 20px 60px 40px;">
            <h2 style="color: #6D0202;font-size: 52px;font-weight: 300;max-width: 500px; margin: 0px;">Your event journey starts here.</h2>
                    <p style="color: #707070;font-size: 15px;max-width: 80%;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
                    <a href="{{ env('CUSTOMER_PANEL_LINK') }}" style="color: #6D0202;">Return to website</a><br><br>
                    @if(!empty($url))
                        <a href="{{ !empty($url) ? $url : '#' }}" style="background-color:#083d46;padding:6px 18px 6px 18px;border-radius:3px;line-height:18px!important;letter-spacing:0.125em;text-transform:uppercase;font-size:13px;font-family:'Open Sans',Arial,sans-serif;font-weight:400;color:#ffffff;text-decoration:none;display:inline-block;line-height:18px!important" target="_blank">Set Password
                    </a>
                 @endif
                </div>
        <div style="background: url({{ asset('resources/images') }}/bg.png);height: 450px;background-repeat: no-repeat;"></div>
        <div style="background: #FAEFEA;padding: 20px 60px 50px;">
            <h3 style="margin: 0px;color: #6D0202;font-size: 32px;font-weight: 500;padding-bottom: 40px;">What are you looking for?</h3>
            <div>
                <div style="float: left;width: 360px;background: #fff;border-radius: 10px; ">
                    <img src="{{ asset('resources/images') }}/Component.png" alt="" style="max-height: 200px;margin-left: -30px;">
                    <span style="display: block;text-align: center;padding-top: 10px;padding-bottom: 40px;">Online Planner</span>
                    <a href="#" style="color: #6D0202;text-align: center;display: block;margin-bottom: -30px;">See more</a>
                </div>
                    <div style="float: left;width: 360px;background: #fff;border-radius: 10px;margin-left: 320px;">
                        <img src="{{ asset('resources/images') }}/1.png" alt="" style="max-height: 200px;">
                        <span style="display: block;text-align: center;padding-top: 10px;padding-bottom: 40px;">In-person Planner</span>
                        <a href="#" style="color: #6D0202;text-align: center;display: block;margin-bottom: -30px;">See more</a>
                    </div>
                <div></div>
                <div style="clear:both;"></div>
            </div>
        </div>
    
        <div>
            <ul style="display: block;text-align: center;margin: 0px;padding: 20px;list-style: none;">
                <li style="display: inline-block;vertical-align: middle;margin: 0px 10px;"><a href="#" style="display: block;"><img src="{{ asset('resources/images') }}/fb.png" alt="" style="max-width: 40px;"></a></li>
                <li style="display: inline-block;vertical-align: middle;margin: 0px 10px;"><a href="#" style="display: block;"><img src="{{ asset('resources/images') }}/insta.png" alt="" style="max-width: 40px;"></a></li>
                <li style="display: inline-block;vertical-align: middle;margin: 0px 10px;"><a href="#" style="display: block;"><img src="{{ asset('resources/images') }}/twitter.png" alt="" style="max-width: 40px;"></a></li>
                <li style="display: inline-block;vertical-align: middle;margin: 0px 10px;"><a href="#" style="display: block;"><img src="{{ asset('resources/images') }}/pintrest.png" alt="" style="max-width: 40px;"></a></li>
            </ul>
        </div>
    </div>