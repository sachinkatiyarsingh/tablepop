import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from "@angular/router"
import { Earningervice } from '../../services/earning.service';
import { AlertService } from '../../services/alert.service';
@Component({
    selector: 'app-earning-detail',
    templateUrl: './earning-detail.component.html',
    styleUrls: ['./earning-detail.component.css']
})
export class AppEarningDetailComponent implements OnInit {
    eventId: any;
    transactionList: any[] = [];
    noRecordFound: boolean = true;
    pageOpts = {
        PageNumber: 1,
        PageSize: 10
    };
    totalPage: any = 0;
    constructor(private router: Router, private route: ActivatedRoute, private alertService: AlertService, private earningervice: Earningervice) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.eventId = param.get('id');
            this.getTransactionDetails();
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(false);
        this.alertService.setHeaderClass('light');
    }
    getTransactionDetails() {
        let req: any = {
            eventId: this.eventId,
            pageNo: this.pageOpts.PageNumber,
            PageSize: this.pageOpts.PageSize,
        }
        this.earningervice.getTransactionDetails(req).subscribe((res: any) => {
            if (res.status) {
                this.transactionList = res.data ? res.data.transactions : [];
                this.totalPage = res.data ? res.data.totalPage : 0;
                this.noRecordFound = this.transactionList.length != undefined && this.transactionList.length != null && this.transactionList.length > 0 ? false : true;
            }
        })
    }
    PageSelect(PageNumber) {
        this.pageOpts.PageNumber = PageNumber;
        this.getTransactionDetails();
    }
}