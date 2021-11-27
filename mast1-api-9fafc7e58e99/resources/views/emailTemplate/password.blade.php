<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,700&display=swap" rel="stylesheet">
<div style="font-family: 'Poppins', sans-serif;max-width: 1200px;margin: 0px auto;background: #FAEFEA;">
        <div style="background: #FAEFEA; padding: 40px 60px;">
            <a href="#">
                <img src="{{ asset('resources/images') }}/logo.png" alt="" style=" max-width: 190px;">
            </a>
        </div>
        <div style="padding: 20px 60px 40px;">
            <h2 style="color: #6D0202;font-size: 52px;font-weight: 300;max-width: 500px; margin: 0px;">Your event journey starts here.</h2>
            <div>
                <h4>Hi {{ $name ?? '' }},</h4>
                <p>You requested that we help you to log in to <br>
                    Tablepop.co. If you did not request this email, you can<br>
                    ignore it -- your password has not been changed.</p>
                
                <p><strong>Your username:</strong> {{ $email ?? '' }} </p>   
                <p>Forgot your password? {{ $url ?? ''  }}</p>
            <p>Best, <br>
                Team TablePop</p>
            </div>
                   
                </div>
                   
    
        <div>
            <ul style="display: block;text-align: center;margin: 0px;padding: 20px;list-style: none;">
                <li style="display: inline-block;vertical-align: middle;margin: 0px 10px;"><a href="{{ env('FACEBOOK_LINK') }}" style="display: block;"><img src="{{ asset('resources/images') }}/fb.png" alt="" style="max-width: 40px;"></a></li>
                <li style="display: inline-block;vertical-align: middle;margin: 0px 10px;"><a href="{{ env('INSTAGRAM_LINK') }}" style="display: block;"><img src="{{ asset('resources/images') }}/insta.png" alt="" style="max-width: 40px;"></a></li>
                <li style="display: inline-block;vertical-align: middle;margin: 0px 10px;"><a href="{{ env('TWITTER_LINK') }}" style="display: block;"><img src="{{ asset('resources/images') }}/twitter.png" alt="" style="max-width: 40px;"></a></li>
                <li style="display: inline-block;vertical-align: middle;margin: 0px 10px;"><a href="{{ env('PINTEREST_LINK') }}" style="display: block;"><img src="{{ asset('resources/images') }}/pintrest.png" alt="" style="max-width: 40px;"></a></li>
            </ul>
        </div>
    </div>