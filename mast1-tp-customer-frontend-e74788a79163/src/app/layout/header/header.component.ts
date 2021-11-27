import { Component, OnInit, Renderer2, } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { AlertService } from '../../services/alert.service';
import { ConfirmPasswordValidator } from '../../custom-validators/confirm-password.validator';
import { SocialAuthService } from "angularx-social-login";
import { FacebookLoginProvider, GoogleLoginProvider, SocialUser } from "angularx-social-login";
import { DashboardService } from '../../services/dashboard.service';
import { MessageService } from '../../services/message.service';
@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
  isLoginShow: boolean = false
  isSignupShow: boolean = false
  isForgotShow: boolean = false
  loginForm: FormGroup;
  signUpForm: FormGroup;
  forgotForm: FormGroup;
  subscriptionForm: FormGroup;
  MOBILE_NUMBER: any = /^[6789]\d{9}$/;
  isUserLogin: boolean = false;
  isUserSignup: boolean = false;
  currentUser: any;
  countryData: any[] = [];
  stateData: any[] = [];
  headerClass: string = '';
  notification: boolean = false;
  message: boolean = false;
  user: SocialUser;
  isSocialCall: boolean = false;
  invitationCode: any = '';
  isSocialLogin: boolean = false;
  isLoginShowCont: boolean = false;
  dialCode: any;
  mobileWithDialCode: any;
  mobileValid: boolean;
  isMobile: boolean = false;
  defaultCountry: any;
  notificationCount: any;
  subscription: boolean = false;
  firsttime: string = "true";
  constructor(private dashboardService: DashboardService, private fb: FormBuilder, private messageService: MessageService, private socialAuthService: SocialAuthService, private route: ActivatedRoute, private alertService: AlertService, private authService: AuthService, private render: Renderer2) {
    this.route.queryParams.subscribe(params => {
      if (Object.keys(params).length) {
        this.invitationCode = params.invitationCode;
        if (params.invitationCode) {
          this.showSignup();
        }
      }
    });
  }

  ngOnInit(): void {
    this.authService.currentLoginStatus.subscribe((res) => {
      this.isUserLogin = res;
      if (this.isUserLogin) {
        this.messageService.initSocket();
        this.getNotificationCount();
      }
      else {
        this.messageService.closeSocket();
      }
    });

    this.alertService.getHeaderClass().subscribe((res) => {
      this.headerClass = res;
    })
    this.authService.currentUserData.subscribe((res) => {
      this.currentUser = res;
    })
    this.authService.isShowLogin.subscribe((res) => {
      if (res && !this.isUserLogin) {
        this.showLogin();
      }
    });
    this.authService.isShowSignup.subscribe((res) => {
      if (res && !this.isUserLogin) {
        this.showSignup();
      }
    })
    this.socialAuthService.authState.subscribe((user) => {
      this.user = user;
      this.isSocialLogin = user != null;
      if (user && !this.isSocialCall && !this.isUserLogin) {
        this.isSocialCall = true;
        this.socialRegister({ name: user.name, email: user.email, type: user.provider, socialId: user.id })
      }
    });
    this.messageService.onEvent('notification_count')
      .subscribe((res: any) => {
        if (res) {
          if (this.notificationCount) {
            this.notificationCount["notification"] = res.count;
          }
          else {
            this.notificationCount = {};
            this.notificationCount["notification"] = res.count;
          }
        }
      });
    this.messageService.onEvent('message_count')
      .subscribe((res: any) => {
        if (res) {
          if (this.notificationCount) {
            this.notificationCount["message"] = res.count;
          }
          else {
            this.notificationCount = {};
            this.notificationCount["message"] = res.count;
          }
        }
      });
    this.firsttime = window.localStorage.getItem('firsttime');
    if (window.localStorage.getItem("firsttime") == null ||
      window.localStorage.getItem("firsttime") == undefined) {
      this.subscription = true;
      this.initsubscriptionForm();
      window.localStorage.setItem("firsttime", "false");
    } else {
      this.subscription = false;
      window.localStorage.setItem("firsttime", "false");
    }
  }

  closeSign() {
    this.isSignupShow = !this.isSignupShow;
    this.isLoginShowCont = false;
    this.render.removeClass(document.body, "modal_open")
  }

  closeLogin() {
    this.isLoginShow = !this.isLoginShow;
    this.isLoginShowCont = false;
    this.render.removeClass(document.body, "modal_open")
  }
  closeLoginCont() {
    this.isLoginShowCont = false;
    this.isLoginShow = false;
    this.isForgotShow = false;
    this.isSignupShow = false;
    this.render.removeClass(document.body, "modal_open");
  }
  closeForgot() {
    this.isForgotShow = !this.isForgotShow;
    this.isLoginShowCont = false;
    this.render.removeClass(document.body, "modal_open");
  }
  showLogin() {
    this.initLoginForm();
    this.isSignupShow = false;
    this.isForgotShow = false;
    this.isLoginShow = true;
    this.render.addClass(document.body, "modal_open")
  }
  showSignup() {
    if (this.countryData.length == 0) {
      this.getCountry();
    }
    this.isLoginShowCont = true;
    this.initSignupForm();
    this.isLoginShow = false;
    this.isForgotShow = false;
    this.render.addClass(document.body, "modal_open")
  }
  showForgot() {
    this.forgotForm = this.fb.group({
      email: [
        '',
        Validators.compose([Validators.required, Validators.email])
      ],
    });
    this.isLoginShow = false
    this.isSignupShow = false
    this.isForgotShow = true;
    this.render.addClass(document.body, "modal_open");
  }
  initLoginForm() {
    this.loginForm = this.fb.group({
      email: [
        '',
        Validators.compose([Validators.required, Validators.email])
      ],
      password: ['', [Validators.required]]
    });
  }

  initSignupForm() {
    let socialData: any = {};
    if (this.user) {
      socialData.name = this.user.firstName,
        socialData.surname = this.user.lastName,
        socialData.email = this.user.email
    }
    this.signUpForm = this.fb.group({
      name: [socialData.name || '', [Validators.required]],
      surname: [socialData.surname || ''],
      email: [
        socialData.email || '',
        Validators.compose([Validators.required, Validators.email])
      ],
      password: ['', [Validators.required]],
      confirmPassword: [],
      mobile: [],
      country: ['', [Validators.required]],
      checked: ["", [Validators.requiredTrue]],
      state: [{ value: "", disabled: true }, [Validators.required]],
      invitationCode: [this.invitationCode]
    }, { validator: ConfirmPasswordValidator.MatchPassword });
  }
  login() {
    if (this.loginForm.valid) {
      const data: any = { ...this.loginForm.value };
      this.authService.login(data).subscribe((res: any) => {
        if (res.status) {
          let data: any = {
            data: res.data,
            token: res.token
          }
          this.authService.saveUserDetail(data);
          this.alertService.success('Login successful.');
          this.closeLogin();
        }
        else {
          this.alertService.error(res.message);
        }
      })
    }
  }
  getNumber(event: any) {
    this.mobileWithDialCode = event;
  }
  onCountryChange(event: any) {
    this.dialCode = event.dialCode;
  }
  onError(obj) {
    this.mobileValid = obj;
  }
  signup() {
    if (this.signUpForm.valid) {
      const data: any = { ...this.signUpForm.value };
      data.mobile = this.mobileWithDialCode;
      this.authService.signup(data).subscribe((res: any) => {
        if (res.status) {
          let data: any = {
            data: res.data,
            token: res.token
          }
          this.authService.saveUserDetail(data);
          this.alertService.success('Signup successfully.');
          this.closeLoginCont();
          this.closeSign();
          this.isSignupShow = false;
        }
        else {
          this.alertService.error(res.message);
        }
      })
    }
  }
  logout() {
    if (this.isSocialLogin) {
      this.socialAuthService.signOut().then((res: any) => {
        this.authService.logout();
      });
    }
    else {
      this.authService.logout();
    }
  }
  getCountry() {
    this.authService.getCountry().subscribe((res: any) => {
      if (res.status) {
        this.countryData = res.data;
        const defaultCountry = this.countryData.find(c => c.id == 231);
        this.signUpForm.get('country').setValue(defaultCountry.id);
        this.getState(defaultCountry.id);
      }
    })
  }
  getState(countryId: any) {
    this.authService.getState(countryId).subscribe((res: any) => {
      if (res.status) {
        this.stateData = res.data;
        this.signUpForm.get('state').enable();
      }
    })
  }
  changeCountry(e: any) {
    this.signUpForm.get('state').setValue('');
    this.signUpForm.get('state').enable();
    this.stateData = [];
    this.getState(e.target.value);
  }
  submit() {
    if (this.forgotForm.valid) {
      const data: any = { ...this.forgotForm.value };
      this.authService.ForgotPassword(data).subscribe((res: any) => {
        if (res.status) {
          let data: any = {
            data: res.data,
            token: res.token
          }
          this.alertService.success('mail sent success.');
          this.closeForgot();
        }
        else {
          this.alertService.error(res.message);
        }
      })
    }
  }
  showMessage() {
    this.message = !this.message;
    this.notification = false
  }
  showNotification() {
    this.notification = !this.notification;
    this.message = false;
  }
  signInWithGoogle(): void {
    this.socialAuthService.signIn(GoogleLoginProvider.PROVIDER_ID);
  }

  signInWithFB(): void {
    this.socialAuthService.signIn(FacebookLoginProvider.PROVIDER_ID);
  }

  signOut(): void {
    this.socialAuthService.signOut();
  }
  socialRegister(data: any) {
    this.authService.socialRegister(data).subscribe((res: any) => {
      this.isSocialCall = false;
      if (res.status) {
        let data: any = {
          data: res.data,
          token: res.token
        }
        this.authService.saveUserDetail(data);
        this.alertService.success('Login successful.');
        this.closeLoginCont();
        this.closeSign();
        this.isSignupShow = false;
      }
      else {
        this.alertService.error('Email not register with our platform.')
        this.showSignup();
      }
    })
  }
  isLoginShowContinue() {
    this.isLoginShowCont = true;
  }
  getNotificationCount() {
    this.dashboardService.notificationCount().subscribe((res: any) => {
      if (res.status) {
        this.notificationCount = res.data;
      }
    })
  }
  initsubscriptionForm() {
    this.subscriptionForm = this.fb.group({
      firstname: ['', [Validators.required]],
      lastname: ['', [Validators.required]],
      email: ['', Validators.compose([Validators.required, Validators.email])],
    })
  }
  submitSubForm() {
    if (this.subscriptionForm.valid) {
      const data = { ...this.subscriptionForm.getRawValue() };
      this.authService.subscriptionForm(data).subscribe((res: any) => {
        if (res.status) {
          this.firsttime = 'true';
          this.subscription = false;
          this.alertService.success(res.message);
          window.localStorage.setItem("firsttime", "true");
          //this.authService.updateUserDetail(this.firsttime);
        } else {
          localStorage.setItem("firsttime", "false");
        }

      })
    }
  }
  closesub() {
    this.subscription = false
  }
}
