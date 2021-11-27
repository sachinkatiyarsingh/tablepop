import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class eventsService {
    constructor(private http: HttpClient) { }

    questionnaire_list(request: any): Observable<any> {
        return this.http.post(`${API_END_POINT.questionnaire_list}`, request);
    }
    viewMilestones(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.view_milestones}`, { questionnaireId: id })
    }
    changeStatus(status: any, id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.milestone_status}`, { id: id, status: status })
    }
    eventDetail(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_event_detail}`, { eventId: id });
    }
    paymentDetail(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.payment_details}`, { productId: id })
    }
    eventSeller(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.event_seller}`, { eventId: id })
    }
    markFavorite(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.mark_favorite}`, { sellerId: id })
    }
    favoriteSellers(): Observable<any> {
        return this.http.post(`${API_END_POINT.favorite_seller}`, {});
    }
    unmarkFavorite(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.unmark_favorite}`, { sellerId: id })
    }
    shareEvent(formData: any): Observable<any> {
        return this.http.post(`${API_END_POINT.share_event}`, formData)
    }
}
