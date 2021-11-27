import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ContactusComponent } from './contactus.component';
const routes: Routes = [
    {
        path: "", component: ContactusComponent
    }
];
@NgModule({
    declarations: [
        ContactusComponent
    ],
    imports: [CommonModule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }), RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppContactusModule { }
