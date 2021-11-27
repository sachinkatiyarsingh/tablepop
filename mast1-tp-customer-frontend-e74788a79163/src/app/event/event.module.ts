import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CarouselModule } from 'ngx-owl-carousel-o';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Ng2TelInputModule } from 'ng2-tel-input'
import { AppPaginatorodule } from '../component/paginator/paginator.module';
import { EventDetailComponent } from './event-detail/event-detail.component';
import { AppEventVendorComponent } from './event-vendor/event-vendor.component';
import { AppEventVendorDetailComponent } from './event-vendor/detail/event-vendor-detail.component';
import { AppEventVendorProductComponent } from './event-vendor/products/event-vendor-product.component';
import { AppProductPaymentComponent } from './event-vendor/product-payment/product-payment.component';
const routes: Routes = [
    {
        path: ":id", component: EventDetailComponent,
    },
    {
        path: ":id/vendor", component: AppEventVendorComponent
    },
    {
        path: ":id/vendor/:vid", component: AppEventVendorDetailComponent
    },
    {
        path: ":id/vendor/:vid/product", component: AppEventVendorProductComponent
    },
    {
        path: ":id/product/:pid/payment", component: AppProductPaymentComponent
    },
];
@NgModule({
    declarations: [
        EventDetailComponent,
        AppEventVendorComponent,
        AppEventVendorDetailComponent,
        AppEventVendorProductComponent,
        AppProductPaymentComponent
    ],
    imports: [CommonModule, Ng2TelInputModule, CarouselModule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }), AppPaginatorodule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppEventModule { }
