import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { NotificationService } from '../services/notification.service';
import { AlertService } from '../services/alert.service';

@Component({
    selector: 'app-notification',
    templateUrl: './notification.component.html',
    styleUrls: ['./notification.component.css']
})
export class AppNotificationComponent implements OnInit {
    listData: any[] = [];
    constructor(private router: Router, private notificationService: NotificationService, private alertService: AlertService) { }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
        this.getNotification();
    }

    getNotification() {
        this.notificationService.customerNotification().subscribe((res: any) => {
            if (res.status) {
                this.listData = res.data || [];
            }
        })
    }
    goToPage(data: any) {
        if (data.type == 'eventPlanner') {
            this.router.navigate(['/planner', data.questionnaireId]);
        }
    }
    deleteNotification(data: any, index: any) {
        this.notificationService.deleteNotification(data.id).subscribe((res: any) => {
            if (res.status) {
                this.alertService.success(res.message);
                this.listData.splice(index, 1);
            }
            else {
                this.alertService.error(res.message);
            }
        })
    }

}
