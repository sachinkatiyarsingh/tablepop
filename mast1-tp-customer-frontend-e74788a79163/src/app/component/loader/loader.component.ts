import { Component, OnInit, OnDestroy, } from '@angular/core';
import { Subscription } from 'rxjs';
import { AlertService } from '../../services/alert.service';

@Component({
    selector: 'loader',
    templateUrl: './loader.component.html',
    styleUrls: ['./loader.component.scss']
})

export class LoaderComponent implements OnInit, OnDestroy {

    private subscription: Subscription;
    ShowLoader = false;

    constructor(private alertService: AlertService) {
        this.subscription = this.alertService.getLoader().subscribe((res: any) => {
            this.ShowLoader = res;
        });
    }

    ngOnInit() {
    }

    ngOnDestroy() {
        this.subscription.unsubscribe();
    }
}
