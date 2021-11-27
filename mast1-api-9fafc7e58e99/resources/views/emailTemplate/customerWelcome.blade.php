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
                <div style="padding-left: 30px;">
                <p><strong>We personally match you with event planner, vendors & pros.</strong> Not every pro is the right fit for the job, so we match you with ones that are qualified, ready, and excited to take on your project. You can book, hire, rent, pay, choose packages, create custom projects, check milestones, message and more on the planning portal.</p>
                <p><strong>Our event pros are hand picked, not randoms chosen from a database.</strong> Every TablePop planner, vendor or pro has been screened for adequate credentials, experience, and communication style, plus we publish their past work and client reviews.
                </p>
                <p><strong>We're here for you from beginning to end.</strong> TablePop Concierge is here to help throughout your event process, from questions about budget all the way through to the completion of your event project.</p>
                <p><strong>We take the current limitations to gathering seriously.</strong> We understand that engineering events is important now more than ever. We support virtual and niche events as well as provide work with professionals who implement social distance mapping and other safety measures.</p>
            </div>
            <p>Ready to chat? <a href="#" style="color: #6D0202;">Schedule a call</a> to see how we can help. If you haven’t already, take our <a href="#" style="color: #6D0202;">event questionnaire</a> and we'll send you matches. Happy planning!</p>
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