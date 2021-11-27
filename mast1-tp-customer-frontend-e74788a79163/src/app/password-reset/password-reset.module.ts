import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ResetPasswordComponent } from './password-reset.component';
const routes: Routes = [
    {
        path: '/:token',
        component: ResetPasswordComponent
    }
];
@NgModule({
    declarations: [
        ResetPasswordComponent,
    ],
    imports: [CommonModule, FormsModule, ReactiveFormsModule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppResetPasswordModule { }
