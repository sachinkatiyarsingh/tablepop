<aside class="sidebar ">
    <div class="scrollbar">
        <div class="user">
            <div class="user__info" data-toggle="dropdown">
                <img class="user__img" src="{{ asset('resources/assets') }}/demo/img/profile-pics/8.jpg" alt="">
                <div>
                    <div class="user__name">{{ Session::get('name') }}</div>
                    <div class="user__email">{{ Session::get('email') }}</div>
                </div>
            </div>

            <div class="dropdown-menu dropdown-menu--invert">
                <a class="dropdown-item" href="{{ url('profile') }}">View Profile</a>
                <a class="dropdown-item" href="{{ url('change-password') }}">Change-password</a>
                <a class="dropdown-item" href="{{ url('logout') }}">Logout</a>
            </div>
        </div>

        <ul class="navigation">
            <li class="{{ $activePage == 'dashboard' ? ' navigation__active' : '' }}"><a href="{{ url('/') }}"><i class="zwicon-home"></i> Home</a></li>
             @php
                 $access = Helper::permission();
                 $Customer = !empty($access['customer']) ? $access['customer'] :  array();
             @endphp
           
            @if(!empty($Customer))
             
             @if(in_array('index',$Customer))
                 
             <li class="{{ $activePage == "customers" ? 'navigation__active' : '' }}"><a href="{{ url('customers') }}"><i class="zwicon-edit-square-feather"></i>Customers</a></li>
            @endif

            @else
            @if(Session::get('type') == 1)
            <li class="{{ $activePage == "customers" ? 'navigation__active' : '' }}"><a href="{{ url('customers') }}"><i class="zwicon-edit-square-feather"></i>Customers</a></li>
            @endif
            @endif

            @if(Session::get('type') == 1)
               <li class="{{ $activePage == "staff" ? 'navigation__active' : '' }}"><a href="{{ url('staff') }}"><i class="zwicon-edit-square-feather"></i>Staff</a></li>
              
           @endif
           <li class="{{ $activePage == "questionnaire" ? 'navigation__active' : '' }}"><a href="{{ url('questionnaire') }}"><i class="zwicon-edit-square-feather"></i>Questionnaire</a></li>
         </ul>
    </div>
</aside>
<div class="themes">
    <div class="scrollbar">
        <a href="#" class="themes__item active" data-sa-value="1"><img src="{{ asset('resources/assets') }}/resources/img/bg/1.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="2"><img src="{{ asset('resources/assets') }}/resources/img/bg/2.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="3"><img src="{{ asset('resources/assets') }}/resources/img/bg/3.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="4"><img src="{{ asset('resources/assets') }}/resources/img/bg/4.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="5"><img src="{{ asset('resources/assets') }}/resources/img/bg/5.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="6"><img src="{{ asset('resources/assets') }}/resources/img/bg/6.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="7"><img src="{{ asset('resources/assets') }}/resources/img/bg/7.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="8"><img src="{{ asset('resources/assets') }}/resources/img/bg/8.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="9"><img src="{{ asset('resources/assets') }}/resources/img/bg/9.jpg" alt=""></a>
        <a href="#" class="themes__item" data-sa-value="10"><img src="{{ asset('resources/assets') }}/resources/img/bg/10.jpg" alt=""></a>
    </div>
</div>