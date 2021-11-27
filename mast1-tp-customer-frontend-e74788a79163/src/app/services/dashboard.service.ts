import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class DashboardService {
    constructor(private http: HttpClient) { }

    dashboard(): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_dashboard}`, {});
    }
    dashboardOngoing(pageNo: any, pageSize: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_dashboard_ongoing}`, { pageNo: pageNo, pageSize: pageSize });
    }
    dashboardNotification(pageNo: any, pageSize: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_dashboard_notification}`, { pageNo: pageNo, pageSize: pageSize });
    }
    dashboardMessage(pageNo: any, pageSize: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_dashboard_message}`, { pageNo: pageNo, pageSize: pageSize });
    }
    notificationDelete(notificationId: any): Observable<any> {
        return this.http.post(`${API_END_POINT.notification_delete}`, { notificationId: notificationId });
    }
    notificationCount(): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_notification_count}`, {});
    }
}
