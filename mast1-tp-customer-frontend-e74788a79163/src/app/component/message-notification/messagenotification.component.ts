import { Component, OnInit, Input } from '@angular/core';
import { DashboardService } from '../../services/dashboard.service';
import { Router } from "@angular/router";

@Component({
    selector: 'messagenotification',
    templateUrl: './messagenotification.component.html',
    styleUrls: ['./messagenotification.component.scss']
})

export class MessageNotification implements OnInit {
    noRecordFound: boolean = true;
    messageData: any[] = [];
    loading: any = false;
    totalPage: any = 0
    pageOption: any = {
        pageNo: 1,
        pageSize: 10
    }
    constructor(private dashboardService: DashboardService, private router: Router) {
    }

    ngOnInit() {
        this.getDashboardMessage()
    }

    getDashboardMessage() {
        this.dashboardService.dashboardMessage(this.pageOption.mPageNo, this.pageOption.pageSize).subscribe((res: any) => {
            if (res.status && res.data) {
                this.totalPage = res.data.totalPage;
                this.messageData = this.messageData.concat(res.data.message || []);
                this.loading = false;
                this.noRecordFound = this.messageData != undefined && this.messageData != null && this.messageData.length > 0 ? false : true;

            }
        })
    }
    getMoreMessage() {
        if (!this.loading && this.pageOption.pageNo < this.totalPage) {
            this.loading = true;
            this.pageOption.pageNo = this.pageOption.pageNo + 1;
            this.getDashboardMessage();
        }
    }
    deleteMessage(data: any, index: any) {
        this.dashboardService.notificationDelete(data.id).subscribe((res: any) => {
            if (res.status) {
                this.messageData.splice(index, 1);
            }
        })
    }
    goToMessage() {
        this.router.navigate(['/message'])
    }
}
