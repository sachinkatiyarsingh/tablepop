import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { MilestoneViewComponent } from './milestone-view.component';
const routes: Routes = [
    {
        path: "", component: MilestoneViewComponent
    }
];
@NgModule({
    declarations: [
        MilestoneViewComponent
    ],
    imports: [RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppMilestoneViewModule { }
