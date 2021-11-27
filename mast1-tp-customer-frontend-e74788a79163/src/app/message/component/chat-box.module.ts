import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { InfiniteScrollModule } from 'ngx-infinite-scroll';
import { FormsModule } from '@angular/forms';
import { AppChatBoxComponent } from './chat-box.component';
import { AppMessageBoxComponent } from './message-box.component';


@NgModule({
    declarations: [
        AppChatBoxComponent,
        AppMessageBoxComponent
    ],
    entryComponents: [],
    imports: [
        CommonModule,
        FormsModule,
        InfiniteScrollModule
    ],
    exports: [
        AppChatBoxComponent,
        AppMessageBoxComponent
    ],
    providers: []
})
export class AppChatBoxModule { }
