import { Component, OnInit, OnDestroy } from '@angular/core';
import { Subscription } from 'rxjs';

import { AlertService } from '../../services/alert.service';

@Component({
    selector: 'alert',
    templateUrl: './alert.component.html',
    styleUrls: ['./alert.component.scss']
})

export class AlertComponent implements OnInit, OnDestroy {
    private subscription: Subscription;
    data: any;
    timeOut: any;
    constructor(private alertService: AlertService) {
        this.subscription = this.alertService.getMessage().subscribe(message => {
            if (this.timeOut) {
                clearTimeout(this.timeOut);
            }
            this.data = message;
            this.timeOut = setTimeout(() => {
                this.data = undefined;
            }, 4000);
        });
    }

    ngOnInit() {
    }
    close() {
        clearTimeout(this.timeOut);
        this.data = undefined;
    }
    ngOnDestroy() {
        clearTimeout(this.timeOut);
        this.subscription.unsubscribe();
    }
}
