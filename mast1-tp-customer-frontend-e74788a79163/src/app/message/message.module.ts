import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { InfiniteScrollModule } from 'ngx-infinite-scroll';
import { AppChatBoxModule } from './component/chat-box.module';
import { AppMessageComponent } from './message.component';
const routes: Routes = [
    {
        path: '',
        component: AppMessageComponent
    }
];
@NgModule({
    declarations: [
        AppMessageComponent,
    ],
    imports: [CommonModule, InfiniteScrollModule, AppChatBoxModule, RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppMessageModule { }
