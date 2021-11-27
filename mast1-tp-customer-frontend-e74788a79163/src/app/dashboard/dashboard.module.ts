import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { InfiniteScrollModule } from 'ngx-infinite-scroll';
import { AppDashboardComponent } from './dashboard.component';
const routes: Routes = [
    {
        path: '',
        component: AppDashboardComponent
    }
];
@NgModule({
    declarations: [
        AppDashboardComponent,
    ],
    imports: [CommonModule, InfiniteScrollModule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppDashboardModule { }
