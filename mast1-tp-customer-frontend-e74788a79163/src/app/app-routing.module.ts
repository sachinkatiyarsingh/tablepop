import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from './home/home.component';
// import { BecomeAPlannerComponent } from './become-aplanner/become-aplanner.component';
// import { HowsitWorksComponent } from './howsit-works/howsit-works.component';
// import { HowsitWorks2Component } from './howsit-works2/howsit-works2.component';
// import { ServiceBudgetComponent } from './service-budget/service-budget.component';
// import { ResetPasswordComponent } from './password-reset/password-reset.component';
import { AuthGuard } from './services/auth.guard';
// import { EventsComponent } from './events/events.component';
// import { SuccessPageComponent } from './success-page/success-page.component';
// import { PaymentComponent } from './payment/payment.component';
// import { AppDashboardComponent } from './dashboard/dashboard.component';
// import { AppNotificationComponent } from './notification/notification.component';
// import { AppPlannerListComponent } from './planner/planner-list/planner-list.component';
// import { AppPlannerDetailComponent } from './planner/planner-detail/planner-detail.component';
// import { AppPlannerPlanComponent } from './planner/planner-plan/planner-plan.component';
// import { AppMessageComponent } from './message/message.component';
// import { OfferPaymentComponent } from './offer-payment/offer-payment.component';
// import { MilestoneViewComponent } from './milestone-view/milestone-view.component';
// import { EventDetailComponent } from './event/event-detail/event-detail.component';
// import { AppEventVendorComponent } from './event/event-vendor/event-vendor.component';
// import { AppEventVendorDetailComponent } from './event/event-vendor/detail/event-vendor-detail.component';
// import { AppEventVendorProductComponent } from './event/event-vendor/products/event-vendor-product.component';
// import { AppProductPaymentComponent } from './event/event-vendor/product-payment/product-payment.component';
// import { AppEarningComponent } from './earnings/earning.component';
// import { AppEarningDetailComponent } from './earnings/details/earning-detail.component';
// import { BlogComponent } from './blog/blog.component';
// import { BlogDetailComponent } from './blog/blog-detail/blog-detail.component';
// import { ProfileComponent } from './profile/profile.component';
// import { EventSellerComponent } from './event-seller/event-seller.component';
// import { VendorDetailsComponent } from './event-seller/vendor-details/vendor-details.component';
// import { PlannerDetailsComponent } from './event-seller/planner-details/planner-details.component';
// import { FavoriteSellerComponent } from './favorite-seller/favorite-seller.component';
// import { ContactusComponent } from './contactus/contactus.component';
// import { FaqComponent } from './faq/faq.component';
// import { FindAPlannerComponent } from './find-a-planner/find-a-planner.component';

const routes: Routes = [
  {
    path: '', component: HomeComponent, pathMatch: 'full',
  },
  {
    path: "workwithus", loadChildren: () => import('./become-aplanner/become-aplaner.module').then(res => res.AppBecomeAPlannerModule)
  },
  {
    path: 'howsitworks', loadChildren: () => import('./howsit-works/howsit-works.module').then(res => res.AppHowsitWorksModule)
  },
  {
    path: "ourstory", loadChildren: () => import('./howsit-works2/howsit-works2.module').then(res => res.AppHowsitWorks2Module)
  },
  {
    path: "servicebudget", loadChildren: () => import('./service-budget/service-budget.module').then(res => res.AppServiceBudgetModule)
  },
  {
    path: "verify/:token", loadChildren: () => import('./password-reset/password-reset.module').then(res => res.AppResetPasswordModule)
  },
  {
    path: "events", loadChildren: () => import('./events/events.module').then(res => res.AppEventsModule), canActivate: [AuthGuard]
  },
  {
    path: "congratulation", loadChildren: () => import('./success-page/success-page.module').then(res => res.AppSuccessPageModule)
  },
  {
    path: "payment/:planId/:qid", loadChildren: () => import('./payment/payment.module').then(res => res.AppPaymentModule)
  },
  {
    path: "dashboard", loadChildren: () => import('./dashboard/dashboard.module').then(res => res.AppDashboardModule), canActivate: [AuthGuard]
  },
  {
    path: "notification", loadChildren: () => import('./notification/notification.module').then(res => res.AppNotificationModule), canActivate: [AuthGuard]
  },
  {
    path: "planner", loadChildren: () => import('./planner/planner.module').then(res => res.AppPlannerModule), canActivate: [AuthGuard]
  },
  // {
  //   path: "planner/:id/:qid/details", component: AppPlannerDetailComponent, canActivate: [AuthGuard]
  // },
  // {
  //   path: "planner/:id/:qid/plans", component: AppPlannerPlanComponent, canActivate: [AuthGuard]
  // },
  {
    path: "message", loadChildren: () => import('./message/message.module').then(res => res.AppMessageModule), canActivate: [AuthGuard]
  },
  // {
  //   path: "customeroffer/:id", component: CustomerEventOfferComponent
  // },
  {
    path: "offerPayment/:offerId", loadChildren: () => import('./offer-payment/offer-payment.module').then(res => res.AppOfferPaymentModule)
  },
  {
    path: "milestoneview/:id", loadChildren: () => import('./milestone-view/milestone-view.module').then(res => res.AppMilestoneViewModule),
  },
  {
    path: "event", loadChildren: () => import('./event/event.module').then(res => res.AppEventModule), canActivate: [AuthGuard]
  },
  // {
  //   path: "event/:id/vendor", component: AppEventVendorComponent, canActivate: [AuthGuard]
  // },
  // {
  //   path: "event/:id/vendor/:vid", component: AppEventVendorDetailComponent, canActivate: [AuthGuard]
  // },
  // {
  //   path: "event/:id/vendor/:vid/product", component: AppEventVendorProductComponent, canActivate: [AuthGuard]
  // },
  // {
  //   path: "event/:id/product/:pid/payment", component: AppProductPaymentComponent, canActivate: [AuthGuard]
  // },
  {
    path: "transactions", loadChildren: () => import('./earnings/earning.module').then(res => res.AppEarningModule), canActivate: [AuthGuard]
  },
  // {
  //   path: "transactions/:id", component: AppEarningDetailComponent, canActivate: [AuthGuard]
  // },
  {
    path: "blog", loadChildren: () => import('./blog/blog.module').then(res => res.AppBlogModule),
  },
  // {
  //   path: "blogdetail/:id", component: BlogDetailComponent,
  // },

  {
    path: "myprofile", loadChildren: () => import('./profile/profile.module').then(res => res.AppProfileModule),
  },
  {
    path: "eventsellers", loadChildren: () => import('./event-seller/event-seller.module').then(res => res.AppEventSellerModule), canActivate: [AuthGuard]
  },
  {
    path: "vendor", loadChildren: () => import('./event-seller/vendor-details/vendor-details.module').then(res => res.AppVendorDetailsModule), canActivate: [AuthGuard]
  },
  {
    path: "planners", loadChildren: () => import('./event-seller/planner-details/planner-details.module').then(res => res.AppPlannerDetailsModule), canActivate: [AuthGuard]
  },
  {
    path: "favoritesellers", loadChildren: () => import('./favorite-seller/favorite-seller.module').then(res => res.AppFavoriteSellerModule), canActivate: [AuthGuard]
  },
  {
    path: "contactus", loadChildren: () => import('./contactus/contactus.module').then(res => res.AppContactusModule),
  },
  {
    path: "faq", loadChildren: () => import('./faq/faq.module').then(res => res.AppFaqModule)
  },
  {
    path: 'findaplanner', loadChildren: () => import('./find-a-planner/find-a-planner.module').then(res => res.AppFindAPlannerModule)
  },

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
