import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { SuccessPageComponent } from './success-page.component';
const routes: Routes = [
    {
        path: '',
        component: SuccessPageComponent
    }
];
@NgModule({
    declarations: [
        SuccessPageComponent,
    ],
    imports: [RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppSuccessPageModule { }
