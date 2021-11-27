import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AppPaginatorodule } from '../component/paginator/paginator.module';
import { EventsComponent } from './events.component';
const routes: Routes = [
    {
        path: '',
        component: EventsComponent
    }
];
@NgModule({
    declarations: [
        EventsComponent
    ],
    imports: [CommonModule, AppPaginatorodule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppEventsModule { }
