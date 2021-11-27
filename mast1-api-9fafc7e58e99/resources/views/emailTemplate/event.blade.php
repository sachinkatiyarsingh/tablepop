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
                <p> Thanks for posting your {{ $eventName ?? '' }}!</p>
                <p> A TablePop matchmaker is reviewing your project and determining who is the best fit <br>  based on the details you provided.</p>
                 <h4><strong>Here's what to do next:</strong></h4>
                <div style="padding-left: 30px;">
                  <p><strong>Check your email</strong>  <br>
                    Within 1-2 business days (or even sooner), you’ll receive an email with the best
                    professionals for your project.</p> 

                    <p><strong> Choose your shortlist</strong>  <br>
                    Review your matches, check out their profiles and message the ones you are
                    interested in working with.</p>

                    <p><strong>  Visit your dashboard</strong>  <br>
                    You can book, hire, rent, pay, choose packages, create custom projects, check
                    milestones, message and more on the planning portal dashboard. </p>
                    
            </div>
            <p>Best, <br>
                Team TablePop</p>
            </div>
                    <a href="{{ env('CUSTOMER_URL') }}" style="color: #6D0202;">Return to website</a>
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