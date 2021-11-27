import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HowsitWorks2Component } from './howsit-works2.component';
const routes: Routes = [
    {
        path: '',
        component: HowsitWorks2Component
    }
];
@NgModule({
    declarations: [
        HowsitWorks2Component,
    ],
    imports: [RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppHowsitWorks2Module { }
