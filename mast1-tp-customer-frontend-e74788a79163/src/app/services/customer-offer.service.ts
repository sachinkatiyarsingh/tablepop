import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class customerOfferService {
    constructor(private http: HttpClient) { }

    customerEventOffer(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_event_offer}`, { questionnaireId: id });
    }
    customerOfferPayment(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_offer_payment}`, data);
        // return this.http.post(`${API_END_POINT.customer_offer_payment}?offerId=${data.offerId}&phoneNumber=${data.phoneNumber}&paymentToken=${data.paymentToken}&street=${data.street}&country=${data.country}`, {});
    }
    getCountry(): Observable<any> {
        return this.http.post(`${API_END_POINT.country}`, {});
    }
    getOfferDetail(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_offer_detail}`, { id: id });
    }
    paymentDetail(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.payment_details}`, { offerId: id })
    }
}
