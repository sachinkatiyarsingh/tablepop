import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AppPaginatorodule } from '../component/paginator/paginator.module';
import { AppEarningComponent } from './earning.component';
import { AppEarningDetailComponent } from './details/earning-detail.component';
const routes: Routes = [
    {
        path: "", component: AppEarningComponent
    },
    {
        path: ":id", component: AppEarningDetailComponent
    },
];
@NgModule({
    declarations: [
        AppEarningComponent,
        AppEarningDetailComponent
    ],
    imports: [CommonModule, AppPaginatorodule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppEarningModule { }
