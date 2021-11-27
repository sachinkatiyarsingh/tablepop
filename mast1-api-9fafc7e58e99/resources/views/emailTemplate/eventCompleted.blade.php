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
               <p>You did it! Congrats we know you were the hostess with the “<strong>moistest</strong>.”</p>
               <p>Now for the after party? Lol, just kidding.</p>
               <p>Please head to your event professional’s profile and kindly leave a review. Your feedback
                will be much appreciated. </p>
                <p> Thanks for choosing TablePop to help with this event. Let’s do it again another time. </p>
                <p>Let us know if you have feedback or questions!</p>
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