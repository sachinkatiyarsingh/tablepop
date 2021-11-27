import { Component, OnInit } from '@angular/core';
import { Router } from "@angular/router"
import { Earningervice } from '../services/earning.service';
import { AlertService } from '../services/alert.service';
@Component({
    selector: 'app-earning-list',
    templateUrl: './earning.component.html',
    styleUrls: ['./earning.component.css']
})
export class AppEarningComponent implements OnInit {
    transactionList: any[] = [];
    noRecordFound: boolean = true;
    pageOpts = {
        PageNumber: 1,
        PageSize: 10
    };
    totalPage: number = 0
    constructor(private router: Router, private alertService: AlertService, private earningervice: Earningervice) {
    }

    ngOnInit() {

        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(false);
        this.alertService.setHeaderClass('light');
        this.getTransaction();
    }
    getTransaction() {
        let req: any = {
            pageNo: this.pageOpts.PageNumber,
            PageSize: this.pageOpts.PageSize,
        }
        this.earningervice.getTransaction(req).subscribe((res: any) => {
            if (res.status) {
                this.transactionList = res.data ? res.data.event : [];
                this.totalPage = res.data ? res.data.totalPage : 0;
                this.noRecordFound = this.transactionList?.length != undefined && this.transactionList.length != null && this.transactionList.length > 0 ? false : true;
            }
        })
    }
    PageSelect(PageNumber) {
        this.pageOpts.PageNumber = PageNumber;
        this.getTransaction();
    }
    goToDetail(id: any) {
        this.router.navigate(['/transactions', id]);
    }

}
