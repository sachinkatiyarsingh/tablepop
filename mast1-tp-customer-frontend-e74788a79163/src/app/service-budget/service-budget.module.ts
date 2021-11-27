import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DpDatePickerModule } from 'ng2-date-picker';
import { Ng2TelInputModule } from 'ng2-tel-input';
import { AgmCoreModule } from '@agm/core';
import { environment } from '../../environments/environment';
import { ServiceBudgetComponent } from './service-budget.component';
const routes: Routes = [
    {
        path: '',
        component: ServiceBudgetComponent
    },
    {
        path: ':id',
        component: ServiceBudgetComponent
    }
];
@NgModule({
    declarations: [
        ServiceBudgetComponent,
    ],
    imports: [CommonModule, DpDatePickerModule, Ng2TelInputModule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }),
        AgmCoreModule.forRoot({
            apiKey: environment.googleApiKey,
            libraries: ['places']
        }),
        RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppServiceBudgetModule { }
