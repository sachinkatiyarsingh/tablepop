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
                <h4>Hello {{ $name ?? '' }},</h4>
               <p>Your event professional has requested payment for an event milestone. </p>
               <p>Milestones are a series of tasks completed by the event professional and approved by you
                beforehand. They are based on the package you selected or the specifications of your
                custom package. Milestones are event specific and are outlined in your planning portal
                and/or in your contract with the event professional. 
                </p>
                 
                <p>Please head to your planning portal dashboard to view and evaluate the milestone and make payment if necessary.</p>
                <a href="{{ env('CUSTOMER_URL') }}" style="color: #6D0202;"> <button > Go to dashboard</button></a>
                <p>Please let us know if you have feedback or questions!</p>
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