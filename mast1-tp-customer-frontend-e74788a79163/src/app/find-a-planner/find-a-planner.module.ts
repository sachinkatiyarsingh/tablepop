import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { FindAPlannerComponent } from './find-a-planner.component';
const routes: Routes = [
    {
        path: "", component: FindAPlannerComponent
    }
];
@NgModule({
    declarations: [
        FindAPlannerComponent
    ],
    imports: [RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppFindAPlannerModule { }
