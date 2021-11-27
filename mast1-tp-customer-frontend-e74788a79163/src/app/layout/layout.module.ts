import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { InfiniteScrollModule } from 'ngx-infinite-scroll';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Ng2TelInputModule } from 'ng2-tel-input';
import { SocialLoginModule, SocialAuthServiceConfig } from 'angularx-social-login';
import {
    GoogleLoginProvider,
    FacebookLoginProvider
} from 'angularx-social-login';
import { environment } from '../../environments/environment';
import { HeaderComponent } from './header/header.component';
import { FooterComponent } from './footer/footer.component';
import { AppLayoutComponent } from './layout.component';
import { AlertComponent } from '../component/alert/alert.component';
import { LoaderComponent } from '../component/loader/loader.component';
import { NotificationComponent } from '../component/notification/notification.component';
import { MessageNotification } from '../component/message-notification/messagenotification.component';

@NgModule({
    declarations: [
        HeaderComponent,
        FooterComponent,
        AppLayoutComponent,
        AlertComponent,
        LoaderComponent,
        NotificationComponent,
        MessageNotification
    ],
    imports: [
        RouterModule,
        CommonModule,
        SocialLoginModule,
        InfiniteScrollModule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }), Ng2TelInputModule
    ],
    exports: [
        HeaderComponent,
        FooterComponent,
        AppLayoutComponent,
        AlertComponent,
        LoaderComponent
    ],
    providers: [
        {
            provide: 'SocialAuthServiceConfig',
            useValue: {
                autoLogin: false,
                providers: [
                    {
                        id: GoogleLoginProvider.PROVIDER_ID,
                        provider: new GoogleLoginProvider(environment.googleLoginId),
                    },
                    {
                        id: FacebookLoginProvider.PROVIDER_ID,
                        provider: new FacebookLoginProvider(environment.facebookLoginId),
                    }
                ],
            } as SocialAuthServiceConfig,
        }
    ]
})
export class LayoutModule {
}
