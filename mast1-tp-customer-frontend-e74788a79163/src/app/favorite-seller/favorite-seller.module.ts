import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FavoriteSellerComponent } from './favorite-seller.component';
const routes: Routes = [
    {
        path: "", component: FavoriteSellerComponent
    }
];
@NgModule({
    declarations: [
        FavoriteSellerComponent
    ],
    imports: [CommonModule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppFavoriteSellerModule { }
