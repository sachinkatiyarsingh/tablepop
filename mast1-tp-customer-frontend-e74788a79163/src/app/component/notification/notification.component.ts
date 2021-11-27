import { Component, OnInit, Input } from '@angular/core';
import { DashboardService } from '../../services/dashboard.service';
import { Router } from "@angular/router";

@Component({
    selector: 'notification',
    templateUrl: './notification.component.html',
    styleUrls: ['./notification.component.scss']
})

export class NotificationComponent implements OnInit {
    notificationData: any[] = [];
    noRecordFound: boolean = true;
    loading: any = false;
    totalPage: any = 0
    pageOption: any = {
        pageNo: 1,
        pageSize: 10
    }
    notificationCount: any;
    constructor(private dashboardService: DashboardService, private router: Router) {
    }

    ngOnInit() {
        this.getDashboardNotification();
    }
    getDashboardNotification() {
        this.dashboardService.dashboardNotification(this.pageOption.pageNo, this.pageOption.pageSize).subscribe((res: any) => {
            if (res.status && res.data) {
                this.totalPage = res.data.totalPage;
                this.notificationData = this.notificationData.concat(res.data.notification || []);
                this.loading = false;
                this.noRecordFound = this.notificationData != undefined && this.notificationData != null && this.notificationData.length > 0 ? false : true;
            }
        })
    }

    getMoreNotification() {
        if (!this.loading && this.pageOption.pageNo < this.totalPage) {
            this.loading = true;
            this.pageOption.pageNo = this.pageOption.pageNo + 1;
            this.getDashboardNotification();
        }
    }
    deleteNotification(data: any, index: any) {
        this.dashboardService.notificationDelete(data.id).subscribe((res: any) => {
            if (res.status) {
                this.notificationData.splice(index, 1);
            }
        })
    }
    replyRequest(data: any) {
        this.deleteNotification(data, -1);
        if (data.urlType == 'event' && data.questionnaireId > 0) {
            this.router.navigate(['/event', data.questionnaireId])
        }
    }

}
