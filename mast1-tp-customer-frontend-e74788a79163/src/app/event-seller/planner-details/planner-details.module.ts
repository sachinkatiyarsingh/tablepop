import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CarouselModule } from 'ngx-owl-carousel-o';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { PlannerDetailsComponent } from './planner-details.component';
const routes: Routes = [
    {
        path: ":id", component: PlannerDetailsComponent
    }
];
@NgModule({
    declarations: [
        PlannerDetailsComponent
    ],
    imports: [CommonModule, CarouselModule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }), RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppPlannerDetailsModule { }
