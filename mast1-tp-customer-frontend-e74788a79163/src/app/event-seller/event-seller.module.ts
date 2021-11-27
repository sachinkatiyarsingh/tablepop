import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { EventSellerComponent } from './event-seller.component';
const routes: Routes = [
    {
        path: ":id", component: EventSellerComponent
    }
];
@NgModule({
    declarations: [
        EventSellerComponent
    ],
    imports: [CommonModule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppEventSellerModule { }
