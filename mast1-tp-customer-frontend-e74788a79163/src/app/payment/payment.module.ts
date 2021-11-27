import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Ng2TelInputModule } from 'ng2-tel-input';
import { NgxStripeModule } from 'ngx-stripe';
import { environment } from '../../environments/environment';
import { PaymentComponent } from './payment.component';
const routes: Routes = [
    {
        path: "", component: PaymentComponent
    }
];
@NgModule({
    declarations: [
        PaymentComponent
    ],
    imports: [CommonModule, Ng2TelInputModule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }), NgxStripeModule.forRoot(environment.stripApiKey), RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppPaymentModule { }
