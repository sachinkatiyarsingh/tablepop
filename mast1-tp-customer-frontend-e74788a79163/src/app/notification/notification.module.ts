import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AppNotificationComponent } from './notification.component';
const routes: Routes = [
    {
        path: '',
        component: AppNotificationComponent
    }
];
@NgModule({
    declarations: [
        AppNotificationComponent,
    ],
    imports: [RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppNotificationModule { }
