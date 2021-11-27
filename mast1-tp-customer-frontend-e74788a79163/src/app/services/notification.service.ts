import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class NotificationService {
    constructor(private http: HttpClient) { }

    customerNotification(): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_notification}`, {});
    }
    deleteNotification(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.notification_delete}`, { notificationId: id });
    }
}
