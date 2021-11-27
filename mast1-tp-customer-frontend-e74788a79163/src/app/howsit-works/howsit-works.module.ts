import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HowsitWorksComponent } from './howsit-works.component';
const routes: Routes = [
    {
        path: '',
        component: HowsitWorksComponent
    }
];
@NgModule({
    declarations: [
        HowsitWorksComponent,
    ],
    imports: [RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppHowsitWorksModule { }
