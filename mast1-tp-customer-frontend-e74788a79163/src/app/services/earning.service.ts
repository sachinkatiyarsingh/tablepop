import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class Earningervice {
    constructor(private http: HttpClient) { }

    getTransaction(req: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_transection}`, req);
    }
    getTransactionDetails(req: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_transection_detail}`, req);
    }
}
