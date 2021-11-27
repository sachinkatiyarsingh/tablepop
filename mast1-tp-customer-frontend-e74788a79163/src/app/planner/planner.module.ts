import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CarouselModule } from 'ngx-owl-carousel-o'
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AppChatBoxModule } from '../message/component/chat-box.module';
import { AppPlannerListComponent } from './planner-list/planner-list.component';
import { AppPlannerDetailComponent } from './planner-detail/planner-detail.component';
import { AppPlannerPlanComponent } from './planner-plan/planner-plan.component';
const routes: Routes = [
    {
        path: ":id", component: AppPlannerListComponent
    },
    {
        path: ":id/:qid/details", component: AppPlannerDetailComponent
    },
    {
        path: ":id/:qid/plans", component: AppPlannerPlanComponent
    },
];
@NgModule({
    declarations: [
        AppPlannerListComponent,
        AppPlannerDetailComponent,
        AppPlannerPlanComponent
    ],
    imports: [CommonModule, CarouselModule, AppChatBoxModule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }), RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppPlannerModule { }
