import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AlertService } from '../services/alert.service';

@Component({
    selector: 'app-layout',
    templateUrl: './layout.component.html',
    styleUrls: []
})
export class AppLayoutComponent implements OnInit {
    isShowHeader: boolean = false;
    isShowFooter: boolean = false;
    constructor(private router: Router, private alertService: AlertService) {
    }

    ngOnInit() {
        this.alertService.isHeaderShow().subscribe((res) => {
            setTimeout(() => {
                this.isShowHeader = res;
            })
        });
        this.alertService.isFooterShow().subscribe((res) => {
            setTimeout(() => {
                this.isShowFooter = res;
            })
        })
    }

}
