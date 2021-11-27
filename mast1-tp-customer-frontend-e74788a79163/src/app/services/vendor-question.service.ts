import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class vendorQuestionnaire {
    constructor(private http: HttpClient) { }

    vendor_questionnaire(data: FormData): Observable<any> {
        return this.http.post(`${API_END_POINT.vendor_questionnaire}`, data);
    }
  
}
