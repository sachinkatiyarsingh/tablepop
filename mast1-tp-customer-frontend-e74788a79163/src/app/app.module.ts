import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
//import { CarouselModule } from 'ngx-owl-carousel-o';
//import { AgmCoreModule } from '@agm/core';
//import { NgxStripeModule } from 'ngx-stripe';
//import { InfiniteScrollModule } from 'ngx-infinite-scroll';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
//import { HeaderComponent } from './layout/header/header.component';
//import { FooterComponent } from './layout/footer/footer.component';
import { HomeComponent } from './home/home.component';
// import { BecomeAPlannerComponent } from './become-aplanner/become-aplanner.component';
// import { HowsitWorksComponent } from './howsit-works/howsit-works.component';
// import { HowsitWorks2Component } from './howsit-works2/howsit-works2.component';
//import { FormsModule, ReactiveFormsModule } from '@angular/forms';
//import { AlertComponent } from './component/alert/alert.component';
//import { LoaderComponent } from './component/loader/loader.component';
import { InterceptorService } from './services/http-interceptor.service';
// import { ServiceBudgetComponent } from './service-budget/service-budget.component';
// import { ParallaxDirective } from './directives/parallax.directive';
// import { ResetPasswordComponent } from './password-reset/password-reset.component';
import { Moment } from 'moment';
// import { EventsComponent } from './events/events.component';
// import { DpDatePickerModule } from 'ng2-date-picker';
// import { PaginatorComponent } from './component/paginator/paginator.component';
// import { SuccessPageComponent } from './success-page/success-page.component';
// import { PaymentComponent } from './payment/payment.component';
// import { AppDashboardComponent } from './dashboard/dashboard.component';
// import { AppNotificationComponent } from './notification/notification.component';
// import { AppPlannerListComponent } from './planner/planner-list/planner-list.component';
// import { AppPlannerDetailComponent } from './planner/planner-detail/planner-detail.component';
// import { AppPlannerPlanComponent } from './planner/planner-plan/planner-plan.component';
// import { AppMessageComponent } from './message/message.component';
// import { AppMessageBoxComponent } from './message/component/message-box.component';
// import { AppChatBoxComponent } from './message/component/chat-box.component';
// import { OfferPaymentComponent } from './offer-payment/offer-payment.component';
// import { MilestoneViewComponent } from './milestone-view/milestone-view.component';
// import { EventDetailComponent } from './event/event-detail/event-detail.component';
// import { AppEventVendorComponent } from './event/event-vendor/event-vendor.component';
// import { AppEventVendorDetailComponent } from './event/event-vendor/detail/event-vendor-detail.component';
// import { AppEventVendorProductComponent } from './event/event-vendor/products/event-vendor-product.component';
// import { AppProductPaymentComponent } from './event/event-vendor/product-payment/product-payment.component';
//import { environment } from '../environments/environment';
// import { AppEarningComponent } from './earnings/earning.component';
// import { AppEarningDetailComponent } from './earnings/details/earning-detail.component';
// import { NotificationComponent } from './component/notification/notification.component';
// import { MessageNotification } from './component/message-notification/messagenotification.component';
// import { BlogComponent } from './blog/blog.component';
// import { BlogDetailComponent } from './blog/blog-detail/blog-detail.component';
// import { ProfileComponent } from './profile/profile.component';

//social login
// import { SocialLoginModule, SocialAuthServiceConfig } from 'angularx-social-login';
// import {
//   GoogleLoginProvider,
//   FacebookLoginProvider
// } from 'angularx-social-login';
// import { EventSellerComponent } from './event-seller/event-seller.component';
// import { VendorDetailsComponent } from './event-seller/vendor-details/vendor-details.component';
// import { PlannerDetailsComponent } from './event-seller/planner-details/planner-details.component';
// import { FavoriteSellerComponent } from './favorite-seller/favorite-seller.component';
// import { FaqComponent } from './faq/faq.component';
//import { ContactusComponent } from './contactus/contactus.component';
//import { FindAPlannerComponent } from './find-a-planner/find-a-planner.component';
//import { Ng2TelInputModule } from 'ng2-tel-input';

//new
import { LayoutModule } from './layout/layout.module';

@NgModule({
  declarations: [
    AppComponent,
    // HeaderComponent,
    // FooterComponent,
    HomeComponent,
    // BecomeAPlannerComponent,
    // HowsitWorksComponent,
    // HowsitWorks2Component,
    // AlertComponent,
    // LoaderComponent,
    // ServiceBudgetComponent,
    // ParallaxDirective,
    // ResetPasswordComponent,
    // EventsComponent,
    // PaginatorComponent,
    // SuccessPageComponent,
    // PaymentComponent,
    // AppDashboardComponent,
    // AppNotificationComponent,
    // AppPlannerListComponent,
    // AppPlannerDetailComponent,
    // AppPlannerPlanComponent,
    // AppMessageComponent,
    // AppMessageBoxComponent,
    // AppChatBoxComponent,
    // OfferPaymentComponent,
    // MilestoneViewComponent,
    // EventDetailComponent,
    // AppEventVendorComponent,
    // AppEventVendorDetailComponent,
    // AppEventVendorProductComponent,
    // AppProductPaymentComponent,
    // AppEarningComponent,
    // AppEarningDetailComponent,
    // NotificationComponent,
    // MessageNotification,
    // BlogComponent,
    // BlogDetailComponent,
    // ProfileComponent,
    // EventSellerComponent,
    // VendorDetailsComponent,
    // PlannerDetailsComponent,
    // FavoriteSellerComponent,
    // FaqComponent,
    // ContactusComponent,
    // FindAPlannerComponent,
  ],
  imports: [
    BrowserModule,
    //NgxStripeModule.forRoot(environment.stripApiKey),
    LayoutModule,
    AppRoutingModule,
    // CarouselModule,
    //InfiniteScrollModule,
    BrowserAnimationsModule,
    //FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }),
    HttpClientModule,
    // AgmCoreModule.forRoot({
    //   apiKey: environment.googleApiKey,
    //   libraries: ['places']
    // }),
    //DpDatePickerModule,
    //SocialLoginModule,
    // Ng2TelInputModule,
  ],
  providers: [{
    provide: HTTP_INTERCEPTORS,
    useClass: InterceptorService,
    multi: true,

  },
    // {
    //   provide: 'SocialAuthServiceConfig',
    //   useValue: {
    //     autoLogin: false,
    //     providers: [
    //       {
    //         id: GoogleLoginProvider.PROVIDER_ID,
    //         provider: new GoogleLoginProvider(environment.googleLoginId),
    //       },
    //       {
    //         id: FacebookLoginProvider.PROVIDER_ID,
    //         provider: new FacebookLoginProvider(environment.facebookLoginId),
    //       }
    //     ],
    //   } as SocialAuthServiceConfig,
    // }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
