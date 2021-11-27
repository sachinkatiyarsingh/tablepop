import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Ng2TelInputModule } from 'ng2-tel-input';
import { ProfileComponent } from './profile.component';
const routes: Routes = [
    {
        path: "", component: ProfileComponent
    }
];
@NgModule({
    declarations: [
        ProfileComponent
    ],
    imports: [CommonModule, Ng2TelInputModule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }), RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppProfileModule { }
