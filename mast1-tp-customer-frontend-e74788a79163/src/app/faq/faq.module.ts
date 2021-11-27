import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FaqComponent } from './faq.component';
const routes: Routes = [
    {
        path: "", component: FaqComponent
    }
];
@NgModule({
    declarations: [
        FaqComponent
    ],
    imports: [CommonModule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppFaqModule { }
