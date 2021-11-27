import { Component, OnInit } from '@angular/core';
import { DashboardService } from '../services/dashboard.service';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';
import { environment } from "../../environments/environment";
import { AlertService } from '../services/alert.service';
@Component({
    selector: 'app-dashboard',
    templateUrl: './dashboard.component.html',
    styleUrls: ['./dashboard.component.css']
})

export class AppDashboardComponent implements OnInit {
    noRecordFound: boolean = true;
    noRecord: boolean = true;
    noOngoing: boolean = true;
    dashboardData: any = {
        notification: [],
        ongoing: [],
        messages: []
    };
    loading: any = {
        notification: false,
        ongoing: false,
        messages: false
    }
    totalPage: any = {
        notification: 1,
        ongoing: 1,
        messages: 1
    }
    pageOption: any = {
        oPageNo: 1,
        nPageNo: 1,
        mPageNo: 1,
        pageSize: 10
    }
    isUserLogin: boolean = false;
    currentUser: any;
    invite: string = environment.siteBaseUrl;
    constructor(private dashboardService: DashboardService, private alertService: AlertService, private router: Router, private authService: AuthService,) { }

    ngOnInit(): void {

        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
        // this.getDashboardData();
        this.getDashboardOngoing();
        this.getDashboardNotification();
        this.getDashboardMessage();
        this.inviteLink();
    }
    getDashboardData() {
        this.dashboardService.dashboard().subscribe((res: any) => {
            if (res.status) {
                this.dashboardData = res.data;
            }
        })
    }
    getDashboardOngoing() {
        this.dashboardService.dashboardOngoing(this.pageOption.oPageNo, this.pageOption.pageSize).subscribe((res: any) => {
            if (res.status && res.data) {
                this.totalPage.ongoing = res.data.totalPage;
                this.dashboardData.ongoing = this.dashboardData.ongoing.concat(res.data.ongoing || []);
                this.loading.ongoing = false;
                this.noOngoing = this.dashboardData.ongoing != undefined && this.dashboardData.ongoing != null && this.dashboardData.ongoing.length > 0 ? false : true;
            }
        })
    }
    getDashboardNotification() {
        this.dashboardService.dashboardNotification(this.pageOption.nPageNo, this.pageOption.pageSize).subscribe((res: any) => {
            if (res.status && res.data) {
                this.totalPage.notification = res.data.totalPage;
                this.dashboardData.notification = this.dashboardData.notification.concat(res.data.notification || []);
                this.loading.notification = false;
                this.noRecordFound = this.dashboardData.notification != undefined && this.dashboardData.notification != null && this.dashboardData.notification.length > 0 ? false : true;
            }
        })
    }
    getDashboardMessage() {
        this.dashboardService.dashboardMessage(this.pageOption.mPageNo, this.pageOption.pageSize).subscribe((res: any) => {
            if (res.status && res.data) {
                this.totalPage.messages = res.data.totalPage;
                this.dashboardData.messages = this.dashboardData.messages.concat(res.data.message || []);
                this.loading.messages = false;
                this.noRecord = this.dashboardData.messages != undefined && this.dashboardData.messages != null && this.dashboardData.messages.length > 0 ? false : true;
            }
        })
    }
    getMoreNotification() {
        if (!this.loading.notification && this.pageOption.nPageNo < this.totalPage.notification) {
            this.loading.notification = true;
            this.pageOption.nPageNo = this.pageOption.nPageNo + 1;
            this.getDashboardNotification();
        }
    }
    getMoreOngoing() {
        if (!this.loading.ongoing && this.pageOption.oPageNo < this.totalPage.ongoing) {
            this.loading.ongoing = true;
            this.pageOption.oPageNo = this.pageOption.oPageNo + 1;
            this.getDashboardOngoing();
        }
    }
    getMoreMessage() {
        if (!this.loading.messages && this.pageOption.mPageNo < this.totalPage.messages) {
            this.loading.messages = true;
            this.pageOption.mPageNo = this.pageOption.mPageNo + 1;
            this.getDashboardMessage();
        }
    }
    goToPage(data: any) {
        this.deleteNotification(data, -1);
        if (data.urlType == 'eventPlanner') {
            this.router.navigate(['/planner', data.questionnaireId]);
        }
        if (data.urlType == 'event') {
            this.router.navigate(['/event', data.questionnaireId]);
        }

        if (data.urlType == 'vendor') {
            this.router.navigate(['/event', data.questionnaireId, 'vendor', data.id]);
        }
        if (data.urlType == 'offer') {
            this.router.navigate(['/offerPayment', data.offerId]);
        }
    }
    deleteNotification(data: any, index: any) {
        this.dashboardService.notificationDelete(data.id).subscribe((res: any) => {
            if (res.status) {
                this.dashboardData.notification.splice(index, 1);
            }
        })
    }
    inviteLink() {
        this.authService.currentLoginStatus.subscribe((res) => {
            this.isUserLogin = res;
        })
        this.authService.currentUserData.subscribe((res) => {
            this.currentUser = res;
        })
        this.authService.isShowLogin.subscribe((res) => {
            if (res && !this.isUserLogin) {
            }
        })
    }
    goToRegister() {
        this.router.navigate(['/'], { queryParams: { invitationCode: this.currentUser.invitationCode } })
    }
}
