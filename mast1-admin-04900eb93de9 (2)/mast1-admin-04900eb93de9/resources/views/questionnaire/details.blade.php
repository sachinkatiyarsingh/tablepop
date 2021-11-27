@extends('layouts.app',['class'=>'questionnaire','activePage'=>'questionnaire'])
@section('content')
@php
    $levelOfService = [
     'in-person'=> 'In-person Planning',
     'online'=> 'Online Planning',
     'full-service'=> 'Full service planning (start to finish help)',
     'partial-service'=> 'Partial planning (help in select areas)',
    ];

    $partyPlanner = [
     'travel-planner'=> 'I travel to the party planner',
     'party-planner-me'=> 'The party planner travels to me',
     'phone-online-planner'=> 'Phone or online (no in-person meeting) ', 
    ];
    
     $helpedBudget = [
     'did-research'=> 'Did research',
     'talked-friend'=> 'Talked to friends',
     'got-other'=> 'Got other estimates',
     'just-guessing'=> 'Just guessing',
     'allocated'=> 'What I have allocated',
     'other-budget'=> '',
    ];

    $premiumEvent = [
     'low-min'=> 'Budget to Mid-range',
     'mid-high'=> 'Mid-range to high-end',
     'high-luxury'=> 'High-end to Luxury',
    ];
     
    $eventPlanning = [
     'personal'=> 'Personal',
     'corporate'=> 'Corporate',
     'social'=> 'Social',
     'child-birthday'=> 'Child Birthday Party',
     'adult-birthday'=> 'Adult Birthday Party',
     'wedding'=> 'Wedding',
     'graduation'=> 'Graduation Party',
     'baby-shower'=> 'Baby Shower',
     'bridal-shower'=> 'Bridal Shower',
     'bachelor-party'=> ',Bachelor(ette) Party',
     'conference'=> 'Conference',
     'product-lunch'=> 'Product Launch',
     'holiday'=> 'Holiday Party',
     'dinner'=> 'Dinner Party',
     'panel-discussion'=> 'Panel Discussion',
     'fundraiser'=> 'Fundraiser',
     'meet-greet'=> 'Meet & Greet',
     'forum'=> 'Forum',
    ];

    $farEvent = [
     'within-month'=> 'Within a month',
     'one-tow-months'=> '1 - 2 months',
     'three-five-months'=> '3 - 5 months',
     'six-plus-months'=> '6 months +',
     'exact-date'=> '',
    ];

    $partyPlaningService = [
     'creative'=> 'Creative',
     'logistics'=> 'Logistics',
     'marketing'=> 'Marketing',
     'management'=> 'Management',
     'partnerships'=> 'Partnerships',
    ];

     $vennu = [
     'outdoor-venue'=> 'Outdoor vennu',
     'indoor'=> 'Indoor vennu',
    ];
     
    $theme =[
     'classic'=> 'Classic',
     'romantic'=> 'Romantic',
     'modern'=> 'Modern',
     'natural'=> 'Natural',
     'glamorous'=> 'Glamorous',
     'bohemian'=> 'Bohemian',
     'urban'=> 'Urban',
     'theme-other'=> '',
    ];
@endphp
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Questions and Answers </h4>
            
            <div class="listview listview--bordered q-a">
                <div class="listview__item q-a__item">
                <div class="listview__content">
                    <span class="listview__heading">1. Which level of service do you require? </span>
                    <p class="text-capitalize">@if(array_key_exists($data['levelOfService'], $levelOfService))
                                  {{  $levelOfService[$data['levelOfService']] ?? '' }}
                    @endif</p>
                    <p class="text-capitalize">@if(array_key_exists($data['levelOfServicePlanningType'], $levelOfService))
                                  {{  $levelOfService[$data['levelOfServicePlanningType']] ?? '' }}
                    @endif</p>
                    </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">2. How would you like to meet with the party planner? </span>
                        <p class="text-capitalize">@if(array_key_exists($data['partyPlanner'], $partyPlanner))
                            {{  $partyPlanner[$data['partyPlanner']] ?? '' }}
                        @endif</p>
                       
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">3. What's your event budget range? </span>
                            <p class="text-capitalize">{{ $data['budgetRangeStart'] ?? '' }} - {{ $data['budgetRangeEnd'] ?? '' }}</p>
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">4. What helped you set your budget? </span>
                        <p class="text-capitalize">@if(array_key_exists($data['helpedBudget'], $helpedBudget))
                            {{  $helpedBudget[$data['helpedBudget']] == '' ? $data['helpedBudgetOther'] :  $helpedBudget[$data['helpedBudget']] }}
                        @endif</p>
                          
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">5. How premium do you want the event? </span>
                        <p class="text-capitalize">@if(array_key_exists($data['premiumEvent'], $premiumEvent))
                            {{  $premiumEvent[$data['premiumEvent']] ?? '' }}
                        @endif</p>
                     
                            
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">6. Please confirm where you need the party planner. </span>
                         <p class="text-capitalize">
                            {{  $data['confirmationPartyPlanner'] ?? '' }}
                       
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">7. Please enter your full name. </span>
                            <p class="text-capitalize">{{ $data['name'] ?? '' }}</p>
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">8. Where should we send your matches? </span>
                            <p class="text-capitalize">{{ $data['email'] ?? '' }}</p>
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">9. Get responses even faster with text alerts. </span>
                            <p class="text-capitalize">{{ $data['mobile'] ?? '' }}</p>
                        </div>
                </div>

                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">10. Give this event a name?  </span>
                            <p class="text-capitalize">{{ $data['eventName'] ?? '' }}</p>
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">11. What type of event are you planning?
                            ( might do drop list of personal/ social/ corporate events)  </span>
                            <p class="text-capitalize">@if(array_key_exists($data['eventPlanning'], $eventPlanning))
                                {{  $eventPlanning[$data['eventPlanning']] ?? '' }}
                            @endif</p>
                            <p class="text-capitalize">@if(array_key_exists($data['typeEvent'], $eventPlanning))
                                {{  $eventPlanning[$data['typeEvent']] ?? '' }}
                            @endif</p>
                            
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">12 How many guests do you expect?  </span>
                            <p class="text-capitalize">{{ $data['guestExpectStart'] ?? '' }}- {{ $data['guestExpectEnd'] ?? '' }}</p>
                            
                        </div>
                </div>

                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">13 How far away is your event?  </span>
                        <p class="text-capitalize">
                            @if($data['farEvent'] == 'exact')
                               {{ date('M d,Y, h:i:s A',strtotime( $data['farEventDate']))}}
                            @else
                            {{   $data['farEventDate'] ?? '' }}
                            @endif
                          
                      </p>
                     
                        </div>
                </div>

                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">14 What party planning services would you like?
                            (Drop down list from Event Support includes)  </span>
                            <p class="text-capitalize">@if(array_key_exists($data['partyPlaningService'], $partyPlaningService))
                                {{  $partyPlaningService[$data['partyPlaningService']] ?? '' }}
                            @endif</p>
                            
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">15 If you already have a venue, where is it?</span>
                        <p class="text-capitalize">@if(array_key_exists($data['vennu'], $vennu))
                            {{  $vennu[$data['vennu']] ?? '' }}
                        @endif</p>
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">16 Is there a specific theme for this event?</span>
                        <p class="text-capitalize">@if( strtolower($data['themeEvent'])== 'other')
                            {{  $data['themeEventOther'] ?? '' }}

                            @else
                            {{  $data['themeName'] ?? '' }}
                        @endif</p>

                            
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">17 Would you like to add photos to describe your vision for the party?</span>
                           @php
                               $addPhotos = $data['addPhotos'];
                               $imageArray = explode(',',$addPhotos);
                           @endphp
                            <ul style="list-style:none">
                           @if(!empty($addPhotos))
                              @foreach ($imageArray as $image)
                           <li style="display:inline-block">
                                <img width="100px" height="100px" src="{{ env('IMAGE_SHOW_PHTH') }}{{ $image }}" alt="">
                            </li>
                           @endforeach
                           @else
                           <li style="display:inline-block">
                           <img width="100px" height="100px" src="{{ asset('resources/assets') }}/demo/img/no_image.png" alt="">
                        </li>
                           @endif
                            </ul>
                           
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">18 Do you have a Pinterest board for your wedding ideas?</span>
                            <a target="_blank" href="{{ $data['weddindIdeas'] ?? '' }}"><p class="text-capitalize">{{ $data['weddindIdeas'] ?? '' }}</p></a>
                            <p class="text-capitalize"></p>
                        </div>
                </div>
                <div class="listview__item q-a__item">
                    <div class="listview__content">
                        <span class="listview__heading">19 Anything else the party planner should know? </span>
                            <p class="text-capitalize">{{ $data['anytningPartyPlanner'] ?? '' }}</p>
                            <p class="text-capitalize"></p>
                        </div>
                </div>
                <div class="clearfix mb-3"></div>
            </div>
              
		</div>
	</div>
</section>

@endsection