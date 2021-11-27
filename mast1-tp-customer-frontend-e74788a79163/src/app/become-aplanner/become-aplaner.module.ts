import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { BecomeAPlannerComponent } from './become-aplanner.component';
const routes: Routes = [
    {
        path: '',
        component: BecomeAPlannerComponent
    }
];
@NgModule({
    declarations: [
        BecomeAPlannerComponent,
    ],
    imports: [RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppBecomeAPlannerModule { }
